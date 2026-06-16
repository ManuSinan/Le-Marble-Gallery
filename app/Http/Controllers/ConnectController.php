<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Carbon\Carbon;

class ConnectController extends Controller
{
    public function product(Request $request, $code)
    {
        $product = Product::select('name', 'stock_status', 'stock_available', 'minimum_quantity', 'price', 'selling_price')->where('product_code', $code)->first();

        if ($product) {
            return response()->json([
                'status' => 1,
                'data' => $product
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'data' => []
            ]);
        }
    }

    public function order(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if ($order) {

            $orderItem = OrderItem::select('product_name', 'product_code', 'quantity', 'price', 'selling_price', 'final_price')->where('order_id', $order->id)->get();

            return response()->json([
                'status' => 1,
                'data' => [
                    'remote_customer' => $order->user_id,
                    'remote_user' => $order->user_id,
                    'customer_name' => $order->user ? $order->user->name : null,
                    'customer_mobile' => $order->user ? $order->user->mobile : null,
                    'address_type' => $order->address_type,
                    'address_name' => $order->address_name,
                    'address_mobile' => $order->address_mobile,
                    'address_line_1' => $order->address_line_1,
                    'address_line_2' => $order->address_line_2,
                    'address_line_3' => (int) $order->address_line_3,
                    'address_location' => $order->address_location,
                    'total_amount' => $order->total_amount,
                    'delivery_charge' => $order->delivery_charge,
                    'discount_amount' => $order->discount_amount,
                    'final_amount' => $order->final_amount,
                    'note' => $order->note,
                    'notes' => $order->notes,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'items' => $orderItem,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'data' => []
            ]);
        }
    }

    public function orders(Request $request, $id)
    {
        $orders = Order::select('id')->where('id', '>', $id)->pluck('id');

        if ($orders) {
            return response()->json([
                'status' => 1,
                'data' => $orders
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'data' => []
            ]);
        }
    }

    public function productUpdate(Request $request, $code)
    {
        $product = Product::where('product_code', $code)->first();

        if ($product) {

            $validator = Validator::make(request()->all(), [
                'stock_status' => ['required', 'in:limited,unlimited', 'max:10'],
                'stock_available' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99'],
                'minimum_quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99'],
                'price' => ['required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999'],
                'selling_price' => ['required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999', 'lte:price'],
            ]);

            if (!$validator->passes()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => 0,
                ]);
            }

            if ($request->stock_status == 'limited' && $request->stock_available == null) {
                return response()->json([
                    'errors' => [
                        'stock_available' => ['The stock available field is required.']
                    ],
                    'status' => 0,
                ]);
            }

            $input = $request->only(['stock_status', 'stock_available', 'minimum_quantity', 'price', 'selling_price']);

            DB::beginTransaction();
            try {
                $product->update($input);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'errors' => [
                        'stock_available' => ['Something went wrong.']
                    ],
                    'status' => 0,
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => 1,
            ]);

        } else {

            return response()->json([
                'status' => 0,
            ]);
        }
    }

    public function productsUpdate(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'product.*.code' => ['required', 'exists:products,product_code'],
            // 'product.*.code' => ['required'],
            'product.*.stock_status' => ['required', 'in:limited,unlimited', 'max:10'],
            'product.*.stock_available' => ['nullable', 'required_if:product.*.stock_status,==,limited', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99'],
            'product.*.minimum_quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99'],
            'product.*.price' => ['required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999'],
            'product.*.selling_price' => ['required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999', 'lte:product.*.price'],
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 0,
            ]);
        }

        foreach ($request->product as $data) {
            $product = Product::where('product_code', $data['code'])->first();

            if ($product) {

                unset($data['code']);

                DB::beginTransaction();
                try {
                    $product->update($data);
                } catch (\Exception $e) {
                    DB::rollback();

                    return response()->json([
                        'message' => 'Something went wrong.',
                        'status' => 0,
                    ]);
                }
                DB::commit();
            }

            return response()->json([
                'status' => 1,
            ]);

        }
    }

    public function orderUpdate(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if ($order) {

            $validator = Validator::make(request()->all(), [
                'public_note' => ['nullable'],
                'status' => ['required', 'in:accepted,rejected,on-the-way,canceled,delivered', 'max:10'],
            ]);

            if (!$validator->passes()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => 0,
                ]);
            }

            $input = $request->only(['public_note', 'status']);

            $input['order_id'] = $order->id;


            DB::beginTransaction();
            try {
                OrderStatus::create($input);

                $order->update([
                    'status' => $input['status'],
                ]);

                $createdAt = Carbon::parse($order->created_at);

                if ($order->user->fcm) {
                    $res = sendPushNotification('Hi ' . $order->user->name . ', Your order #' . $order->id . '-' . $createdAt->format('dmy') . ' status updated to ' . ucfirst(str_replace('-', ' ', $order->status)), $order->user->fcm);
                }

            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'errors' => [
                        'status' => ['Something went wrong.']
                    ],
                    'status' => 0,
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => 1,
            ]);

        } else {

            return response()->json([
                'status' => 0,
            ]);
        }
    }
}
