<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SimpleBookOrderController extends Controller
{
    public function index(Request $request)
    {
        $authUser = authUser();
        $search = trim((string) $request->get('search', ''));
        $sortby = (string) $request->get('sortby', 'subject');

        setCart(cartUpdate());

        $classCounts = Product::query()
            ->whereIn('products.status', ['published', 'active'])
            ->join('categories as subject_categories', 'products.category_id', '=', 'subject_categories.id')
            ->selectRaw('COALESCE(subject_categories.parent_id, subject_categories.id) as class_id, COUNT(*) as products_count')
            ->groupByRaw('COALESCE(subject_categories.parent_id, subject_categories.id)')
            ->pluck('products_count', 'class_id');

        $classIds = $classCounts->keys()->filter(function ($id) {
            return $id !== null && $id !== '';
        });

        // Any aggregated class id (not only top-level rows) so nested categories still surface.
        $classes = Category::query()
            ->whereIn('id', $classIds)
            ->get()
            ->map(function (Category $class) use ($classCounts) {
                return [
                    'id' => $class->id,
                    'slug' => $class->slug,
                    'name' => _local($class->name, $class->local_name),
                    'count' => (int) ($classCounts[$class->id] ?? 0),
                ];
            })
            ->sortBy(function (array $class) {
                if (preg_match('/\d+/', $class['name'], $matches)) {
                    return (int) $matches[0];
                }

                return PHP_INT_MAX;
            })
            ->values();

        $products = Product::query()
            ->with(['category.parent', 'unit'])
            ->whereIn('status', ['published', 'active'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('local_name', 'like', '%' . $search . '%')
                        ->orWhere('product_code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('priority', 'desc')
            ->latest('id')
            ->get()
            ->map(function (Product $product) {
                $defaultQuantity = max((int) $product->minimum_quantity, (int) ($product->unit?->stepper ?? 1));

                return [
                    'id' => $product->id,
                    'name' => _local($product->name, $product->local_name),
                    'price' => (float) $product->selling_price,
                    'mrp' => (float) $product->price,
                    'image_url' => $this->productImageUrl($product),
                    'product_code' => $product->product_code ?: ('BK-' . $product->id),
                    'stepper' => (float) ($product->unit?->stepper ?? 1),
                    'minimum_quantity' => $defaultQuantity,
                    'stock_status' => $product->stock_status,
                    'stock_available' => (int) $product->stock_available,
                    // Support both structures:
                    // - Preferred: book category = subject, parent = class
                    // - Fallback: book category = class (no parent)
                    'subject_id' => (int) ($product->category?->parent ? ($product->category?->id ?? 0) : 0),
                    'subject_name' => (string) ($product->category?->parent ? ($product->category?->name ?? 'General') : 'General'),
                    'class_id' => (int) ($product->category?->parent?->id ?? $product->category?->id ?? 0),
                    'class_name' => (string) ($product->category?->parent?->name ?? $product->category?->name ?? 'General'),
                ];
            })
            ->values();

        $subjectsByClass = collect($products)
            ->groupBy('class_id')
            ->map(function ($items) {
                return collect($items)
                    ->map(fn ($item) => ['id' => $item['subject_id'], 'name' => $item['subject_name']])
                    ->unique('id')
                    ->sortBy('name')
                    ->values();
            })
            ->toArray();

        if ($sortby === 'class') {
            $products = $products->sortBy([['class_name', 'asc'], ['subject_name', 'asc'], ['name', 'asc']])->values();
        } else {
            $products = $products->sortBy([['subject_name', 'asc'], ['name', 'asc']])->values();
        }

        if ($classes->isEmpty() && $products->isNotEmpty()) {
            $fromProducts = $products
                ->filter(static function (array $p) {
                    return (int) ($p['class_id'] ?? 0) > 0;
                })
                ->groupBy('class_id')
                ->map(static function ($items, $classId) {
                    $first = $items->first();

                    return [
                        'id' => (int) $classId,
                        'name' => (string) ($first['class_name'] ?? 'General'),
                        'count' => $items->count(),
                    ];
                })
                ->values()
                ->sortBy(static function (array $class) {
                    if (preg_match('/\d+/', $class['name'], $matches)) {
                        return (int) $matches[0];
                    }

                    return PHP_INT_MAX;
                })
                ->values();

            $uncategorised = $products->filter(static function (array $p) {
                return (int) ($p['class_id'] ?? 0) <= 0;
            });

            if ($fromProducts->isNotEmpty()) {
                $classes = $fromProducts;
            }

            if ($uncategorised->isNotEmpty()) {
                $classes = $classes->push([
                    'id' => 0,
                    'name' => __('General'),
                    'count' => $uncategorised->count(),
                ])->sortBy(static function (array $class) {
                    if ($class['id'] === 0) {
                        return PHP_INT_MAX - 1;
                    }
                    if (preg_match('/\d+/', $class['name'], $matches)) {
                        return (int) $matches[0];
                    }

                    return PHP_INT_MAX;
                })->values();
            }
        }

        $cartSummary = $this->cartSummary();

        $regularClasses = $classes->filter(static function (array $c) {
            return preg_match('/^\d+(st|nd|rd|th)$/i', (string) ($c['name'] ?? '')) === 1;
        })->values();

        $specialClasses = $classes->reject(static function (array $c) {
            return preg_match('/^\d+(st|nd|rd|th)$/i', (string) ($c['name'] ?? '')) === 1;
        })->values();

        return view('simple-bookstore.index', [
            'products' => $products,
            'classes' => $classes,
            'regularClasses' => $regularClasses,
            'specialClasses' => $specialClasses,
            'subjectsByClass' => $subjectsByClass,
            'search' => $search,
            'sortby' => $sortby,
            'storeName' => config('app.name', 'Lee Marble Gallery'),
            'contactNumber' => getOption('order_enquiry_number', ''),
            'currencySymbol' => html_entity_decode(trim(strip_tags(currency()))),
            'authUser' => $authUser,
            'cartCount' => $cartSummary['count'],
            'cartSubtotal' => $cartSummary['subtotal'],
        ]);
    }

    public function store(Request $request)
    {
        $authUser = authUser();

        if (!$authUser) {
            return redirect()->route('signin');
        }

        $validated = $request->validate([
            'class_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $class = Category::query()
            ->whereNull('parent_id')
            ->find($validated['class_id']);

        if (!$class) {
            return back()->withErrors([
                'class_id' => 'Select a valid category.',
            ])->withInput();
        }

        $subject = Category::query()
            ->where('parent_id', $class->id)
            ->find($validated['subject_id']);

        if (!$subject) {
            return back()->withErrors([
                'subject_id' => 'Select a valid subcategory.',
            ])->withInput();
        }

        $product = Product::with(['unit', 'brand', 'category'])
            ->whereIn('status', ['published', 'active'])
            ->where('category_id', $subject->id)
            ->first();

        if (!$product) {
            return back()->withErrors([
                'subject_id' => 'No product is available for the selected subcategory.',
            ])->withInput();
        }

        if ($validated['quantity'] < $product->minimum_quantity) {
            return back()->withErrors([
                'quantity' => 'Minimum quantity for this product is ' . $product->minimum_quantity . '.',
            ])->withInput();
        }

        if ($product->stock_status === 'limited' && $product->stock_available < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'Requested quantity is not available in stock.',
            ])->withInput();
        }

        $order = DB::transaction(function () use ($authUser, $validated, $class, $subject, $product) {
            $quantity = (int) $validated['quantity'];
            $lineTotal = $product->selling_price * ($quantity / max((float) $product->unit->stepper, 1));
            $orderNote = $this->buildOrderNote($class->name, $subject->name, $validated['note'] ?? null);

            $order = Order::create([
                'user_id' => $authUser->id,
                'address_type' => 'home',
                'address_name' => $authUser->name,
                'address_mobile' => $authUser->mobile,
                'address_line_1' => $validated['address'],
                'address_line_2' => null,
                'address_line_3' => null,
                'address_location' => $validated['city'],
                'address_local_location' => $validated['city'],
                'discount_code' => null,
                'total_amount' => $lineTotal,
                'delivery_charge' => 0,
                'discount_amount' => 0,
                'canceled_amount' => 0,
                'final_amount' => $lineTotal,
                'note' => $orderNote,
                'notes' => $validated['note'] ?? null,
                'status' => 'placed',
                'assign_user_id' => null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $product->name,
                'local_product_name' => $product->local_name,
                'product_image' => $product->image,
                'product_id' => $product->id,
                'category_id' => $product->category->id,
                'brand_id' => $product->brand?->id,
                'product_code' => $product->product_code,
                'unit_id' => $product->unit->id,
                'unit_type' => $product->unit->type,
                'local_unit_type' => $product->unit->local_type,
                'stepper' => $product->unit->stepper,
                'unit_name' => $product->unit->name,
                'local_unit_name' => $product->unit->local_name,
                'quantity' => $quantity,
                'price' => $product->price,
                'selling_price' => $product->selling_price,
                'final_price' => $lineTotal,
                'status' => 'placed',
            ]);

            OrderStatus::create([
                'order_id' => $order->id,
                'status' => 'placed',
                'public_note' => 'Quick order placed from category and subcategory form.',
            ]);

            if ($product->stock_status === 'limited') {
                $product->update([
                    'stock_available' => max(0, $product->stock_available - $quantity),
                ]);
            }

            return $order;
        });

        $createdAt = Carbon::parse($order->created_at);
        $orderSuccess = [
            'ref_no' => $order->id . '-' . $createdAt->format('dmy'),
            'book' => $product->name,
            'class' => $class->name,
            'subject' => $subject->name,
        ];

        webhookEvents('order/placed', $order->id);

        return redirect()->route('home', ['class' => $class->id, 'subject' => $subject->id])->with('order_success', $orderSuccess);
    }

    public function checkout()
    {
        $authUser = authUser();
        if (!$authUser) {
            return redirect()->route('signin');
        }

        setCart(cartUpdate());

        $cartItems = $this->cartItemsFromCookie();
        if (empty($cartItems)) {
            return redirect()->route('home')->withErrors([
                'cart' => 'Your order list is empty.',
            ]);
        }

        $subtotal = collect($cartItems)->sum('line_total');

        return view('simple-bookstore.checkout', [
            'storeName' => config('app.name', 'Lee Marble Gallery'),
            'authUser' => $authUser,
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'currencySymbol' => html_entity_decode(trim(strip_tags(currency()))),
            'contactNumber' => getOption('order_enquiry_number', ''),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $authUser = authUser();
        if (!$authUser) {
            return redirect()->route('signin');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'school' => ['nullable', 'string', 'max:150'],
            'place' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:500'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cartItems = $this->cartItemsFromCookie();
        if (empty($cartItems)) {
            return redirect()->route('home')->withErrors([
                'cart' => 'Your order list is empty.',
            ]);
        }

        $order = DB::transaction(function () use ($authUser, $validated, $cartItems) {
            $subtotal = collect($cartItems)->sum('line_total');

            $notes = [];
            $notes[] = 'Order source: Simple checkout';
            if (!empty($validated['school'])) {
                $notes[] = 'School/Madrasa: ' . $validated['school'];
            }
            if (!empty($validated['pincode'])) {
                $notes[] = 'Pincode: ' . $validated['pincode'];
            }
            if (!empty($validated['notes'])) {
                $notes[] = 'Customer note: ' . $validated['notes'];
            }

            $order = Order::create([
                'user_id' => $authUser->id,
                'address_type' => 'home',
                'address_name' => $validated['name'],
                'address_mobile' => $validated['phone'],
                'address_line_1' => $validated['address'],
                'address_line_2' => $validated['school'] ?? null,
                'address_line_3' => $validated['pincode'] ?? null,
                'address_location' => $validated['place'],
                'address_local_location' => $validated['place'],
                'discount_code' => null,
                'total_amount' => $subtotal,
                'delivery_charge' => 0,
                'discount_amount' => 0,
                'canceled_amount' => 0,
                'final_amount' => $subtotal,
                'note' => implode("\n", $notes),
                'notes' => $validated['notes'] ?? null,
                'status' => 'placed',
                'assign_user_id' => null,
            ]);

            foreach ($cartItems as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $quantity = $item['quantity'];
                $lineTotal = $item['line_total'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $product->name,
                    'local_product_name' => $product->local_name,
                    'product_image' => $product->image,
                    'product_id' => $product->id,
                    'category_id' => $product->category?->id,
                    'brand_id' => $product->brand?->id,
                    'product_code' => $product->product_code,
                    'unit_id' => $product->unit->id,
                    'unit_type' => $product->unit->type,
                    'local_unit_type' => $product->unit->local_type,
                    'stepper' => $product->unit->stepper,
                    'unit_name' => $product->unit->name,
                    'local_unit_name' => $product->unit->local_name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'selling_price' => $product->selling_price,
                    'final_price' => $lineTotal,
                    'status' => 'placed',
                ]);

                if ($product->stock_status === 'limited') {
                    $product->update([
                        'stock_available' => max(0, (int) $product->stock_available - (int) $quantity),
                    ]);
                }
            }

            OrderStatus::create([
                'order_id' => $order->id,
                'status' => 'placed',
                'public_note' => 'Order placed from simple checkout.',
            ]);

            return $order;
        });

        $createdAt = Carbon::parse($order->created_at);
        $orderSuccess = [
            'ref_no' => $order->id . '-' . $createdAt->format('dmy'),
            'book' => 'Multiple products',
            'class' => 'Selected categories',
            'subject' => 'Selected subcategories',
        ];

        webhookEvents('order/placed', $order->id);

        return redirect()->route('home')->with('order_success', $orderSuccess)
            ->withCookie(cookie()->forget('__cart'));
    }

    protected function buildOrderNote(string $className, string $subjectName, ?string $note): string
    {
        $lines = [
            'Order source: Simple category and subcategory form',
            'Category: ' . $className,
            'Subcategory: ' . $subjectName,
        ];

        if ($note) {
            $lines[] = 'Customer note: ' . $note;
        }

        return implode("\n", $lines);
    }

    protected function webhookOrderPayload(int $orderId): array
    {
        $order = Order::query()
            ->with(['user:id,name,mobile,email', 'orderItems'])
            ->find($orderId);

        if (!$order) {
            return [];
        }

        return [
            'id' => (int) $order->id,
            'status' => (string) $order->status,
            'total_amount' => (float) $order->total_amount,
            'final_amount' => (float) $order->final_amount,
            'delivery_charge' => (float) $order->delivery_charge,
            'discount_amount' => (float) $order->discount_amount,
            'note' => (string) ($order->note ?? ''),
            'created_at' => optional($order->created_at)->toDateTimeString(),
            'customer' => [
                'id' => (int) ($order->user?->id ?? 0),
                'name' => (string) ($order->address_name ?: ($order->user?->name ?? '')),
                'phone' => (string) ($order->address_mobile ?: ($order->user?->mobile ?? '')),
                'email' => (string) ($order->user?->email ?? ''),
            ],
            'shipping_address' => [
                'line_1' => (string) ($order->address_line_1 ?? ''),
                'line_2' => (string) ($order->address_line_2 ?? ''),
                'line_3' => (string) ($order->address_line_3 ?? ''),
                'location' => (string) ($order->address_location ?? ''),
                'local_location' => (string) ($order->address_local_location ?? ''),
            ],
            'items' => $order->orderItems->map(function (OrderItem $item) {
                return [
                    'product_id' => (int) ($item->product_id ?? 0),
                    'product_code' => (string) ($item->product_code ?? ''),
                    'product_name' => (string) ($item->product_name ?? ''),
                    'quantity' => (float) ($item->quantity ?? 0),
                    'price' => (float) ($item->price ?? 0),
                    'selling_price' => (float) ($item->selling_price ?? 0),
                    'final_price' => (float) ($item->final_price ?? 0),
                    'status' => (string) ($item->status ?? ''),
                ];
            })->values()->all(),
        ];
    }

    protected function productImageUrl(Product $product): string
    {
        if (!$product->image) {
            return asset('assets/frontend/images/200x150-blank.png');
        }

        $largePath = str_replace('/base/', '/large/', $product->image);

        if (Storage::disk('public')->exists($largePath)) {
            return asset('uploads/' . $largePath);
        }

        return asset('uploads/' . $product->image);
    }

    protected function cartSummary(): array
    {
        $cart = getCart();
        $count = 0;
        $subtotal = 0;

        if (isset($cart['products']) && is_array($cart['products'])) {
            foreach ($cart['products'] as $product) {
                $count++;
                $subtotal += (float) ($product['total_selling_price'] ?? 0);
            }
        }

        return [
            'count' => $count,
            'subtotal' => $subtotal,
        ];
    }

    protected function cartItemsFromCookie(): array
    {
        $cart = getCart();
        $productsInCart = is_array($cart['products'] ?? null) ? $cart['products'] : [];
        if (empty($productsInCart)) {
            return [];
        }

        $productIds = collect(array_keys($productsInCart))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values();

        if ($productIds->isEmpty()) {
            return [];
        }

        $products = Product::query()
            ->with(['unit', 'brand', 'category.parent'])
            ->whereIn('status', ['published', 'active'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = [];
        foreach ($productsInCart as $productId => $entry) {
            $id = (int) $productId;
            /** @var Product|null $product */
            $product = $products->get($id);
            if (!$product || !$product->unit) {
                continue;
            }

            $stepper = max((float) $product->unit->stepper, 1);
            $minimum = max((int) $product->minimum_quantity, (int) $stepper);
            $quantity = (int) ($entry['quantity'] ?? 0);
            if ($quantity < $minimum) {
                $quantity = $minimum;
            }

            if ($product->stock_status === 'limited') {
                $quantity = min($quantity, (int) $product->stock_available);
                if ($quantity <= 0) {
                    continue;
                }
            }

            $lineTotal = (float) $product->selling_price * ($quantity / $stepper);
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
                'class_name' => (string) ($product->category?->parent?->name ?? 'General'),
                'subject_name' => (string) ($product->category?->name ?? 'General'),
            ];
        }

        return $items;
    }
}
