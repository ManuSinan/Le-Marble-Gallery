<?php

namespace App\Http\Controllers;
use App\Models\BannerSlider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\DailyOffer;
use App\Models\HomeSpotlight;
use App\Models\ProductReview;
use App\Models\Favourite;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Address;
use App\Models\Location;
use App\Models\Pincode;
use App\Models\State;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Razorpay\Api\Api as RazorpayApi;
use Illuminate\Validation\Rule; 
use App\Mail\OtpMail;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
 
class WebsiteController extends Controller
{

    public function appstore(Request $request)
    {
        return Redirect::to( getOption('apple_app_store_link', route('home')) );
    }

    public function playstore(Request $request)
    {
        return Redirect::to( getOption('google_play_store_link', route('home')) );
    }

    public function aboutUs(Request $request)
    {
        return view('frontend/page/about-us');
    }

    public function tc(Request $request)
    {
        return view('frontend/page/tc');
    }

    public function privacyPolicy(Request $request)
    {
        return view('frontend/page/privacy-policy');
    }

    public function safetyTips(Request $request)
    {
        return view('frontend/page/safety-tips');
    }

    public function importUpdates(Request $request)
    {
        return view('frontend/page/import-updates');
    }

    public function home(Request $request)
    {
        $authUser = authUser();
 
        $bannerSliders = BannerSlider::orderBy('priority', 'desc')->get();
        $featuredProducts = Product::featured();
        $offerProducts = Product::offer();
        $priorityProducts = Product::priorityProducts($offerProducts->pluck('id')->toArray(), 4);
        $brands = Brand::all();

        $dailyOffers = Schema::hasTable('daily_offers')
            ? DailyOffer::where('status', true)->latest()->take(2)->get()
            : collect();

        $homeCategories = Category::query()
            ->whereNull('parent_id')
            ->withCount(['products' => function ($q) {
                $q->whereIn('status', ['published', 'active']);
            }])
            ->orderBy('priority', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $featuredCategory = $homeCategories->first();
        $homeAppliancesProducts = collect();
        if ($featuredCategory) {
            $categoryIds = collect([$featuredCategory->id])
                ->merge($featuredCategory->children()->pluck('id'))
                ->toArray();
            $homeAppliancesProducts = Product::with('unit')
                ->whereIn('status', ['published', 'active'])
                ->whereIn('category_id', $categoryIds)
                ->orderByDesc('created_at')
                ->orderBy('priority', 'desc')
                ->limit(4)
                ->get();
        }

        $homeSpotlights = collect();
        if (Schema::hasTable('home_spotlights')) {
            $homeSpotlights = HomeSpotlight::with(['product.unit'])
                ->where('status', true)
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->get();
        }

        $productsCount = Product::whereIn('status', ['published', 'active'])->count();
 
        return view('frontend/home/index-nnitec', compact(
            'bannerSliders',
            'featuredProducts',
            'offerProducts',
            'priorityProducts',
            'brands',
            'homeCategories',
            'dailyOffers',
            'homeAppliancesProducts',
            'homeSpotlights',
            'productsCount'
        ));
    }

    public function products(Request $request, $slug = null)
    {
        $category = Category::where('slug', $slug)->first();
        
        $sortby = $request->sortby ?? 'featured';
        $search = $request->search ?? '';
        $categoryId = $category->id ?? null;

        $brandSlug = $request->brand ?? null;
        $brand = Brand::where('slug', $brandSlug)->first();
        $brandId = $brand->id ?? null;
        
        $products = Product::retrieve($sortby, $search, $categoryId, $brandId);
        $brands = Brand::all();

        $title  = __('All Books');
        if($search){
            $title  = __('Search') .  ': ' . $search;
        }

        $metaTitle  = getOption('shop_meta_title');
        $metaDescription  = getOption('shop_meta_description');
        $metaKeywords  = getOption('shop_meta_keywords');

        if($categoryId){

            $metaTitle  = getOption('category_' . $categoryId . '_meta_title');
            if(!$metaTitle){
                $metaTitle  =  $category->name;
            }
            
            $title  = $category->name;

            if($search){
                $title  = __('Search') . ' ' . $category->name .  ': ' . $search;
                $metaTitle  =  $title;
            }

            $metaDescription  = getOption('category_' . $categoryId . '_meta_description');
            if(!$metaDescription){
                $metaDescription  = getOption('shop_meta_description');
            }
            
            $metaKeywords  = getOption('category_' . $categoryId . '_meta_keywords');
            if(!$metaKeywords){
                $metaKeywords  = getOption('shop_meta_keywords');
            }
        }

        setCart( cartUpdate() );

        return view('frontend/product/index-luxury', compact('metaTitle', 'metaDescription', 'metaKeywords', 'title', 'slug', 'sortby', 'search', 'category', 'categoryId', 'brands', 'brandSlug', 'products'));
    }

    public function product(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->first();

        if(!$product){
            abort(404);
        }

        $authUser = authUser();
        $favouriteStatus = false;

        if($authUser){
            $favouriteCount = Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->count();
            if($favouriteCount > 0){
                $favouriteStatus = true;
            }
        }

        setCart( cartUpdate() ); 



        $title  =  $product->name;
 
        $metaTitle  = getOption('product_' . $product->id . '_meta_title');
        if(!$metaTitle){
            $metaTitle  =  $title;
        }

        $metaDescription  = getOption('product_' . $product->id . '_meta_description');
        if(!$metaDescription){
            $metaDescription  = getOption('shop_meta_description');
        }
        
        $metaKeywords  = getOption('product_' . $product->id . '_meta_keywords');
        if(!$metaKeywords){
            $metaKeywords  = getOption('shop_meta_keywords');
        }

        $compatibility = false;
        $requestCompatible = str_replace('-', ' ', $request->compatible);
        $getCompatible = getOption('product_' . $product->id . '_compatibility');
        
        $compatibles = [];
        if($getCompatible){
            $compatibles = explode(', ', getOption('product_' . $product->id . '_compatibility') );
        }
        
        if (in_array($requestCompatible, $compatibles)){
            $compatibility = true;
        }

        // Product reviews (approved only)
        $productReviews = collect();
        $averageRating = 0;
        $reviewsCount = 0;
        $userHasReviewed = false;
        $ratingDistribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        if (Schema::hasTable('product_reviews')) {
            $productReviews = ProductReview::where('product_id', $product->id)
                ->where('status', 'approved')
                ->with('user:id,name')
                ->latest()
                ->get();
            $reviewsCount = $productReviews->count();
            $averageRating = $reviewsCount > 0 ? round($productReviews->avg('rating'), 1) : 0;
            foreach ($productReviews as $r) {
                if (isset($ratingDistribution[$r->rating])) {
                    $ratingDistribution[$r->rating]++;
                }
            }
            if ($authUser) {
                $userHasReviewed = ProductReview::where('product_id', $product->id)->where('user_id', $authUser->id)->exists();
            }
        }

        // Similar products (same category, exclude current)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereIn('status', ['published', 'active'])
            ->with(['brand', 'category', 'unit'])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Class selector (top-level categories or aggregated parents)
        $classCounts = Product::query()
            ->whereIn('products.status', ['published', 'active'])
            ->join('categories as subject_categories', 'products.category_id', '=', 'subject_categories.id')
            ->selectRaw('COALESCE(subject_categories.parent_id, subject_categories.id) as class_id, COUNT(*) as books_count')
            ->groupByRaw('COALESCE(subject_categories.parent_id, subject_categories.id)')
            ->pluck('books_count', 'class_id');

        $classIds = $classCounts->keys()->filter(function ($id) {
            return $id !== null && $id !== '';
        });

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

        return view('frontend/product/view-luxury', compact('title', 'metaTitle', 'metaDescription', 'metaKeywords', 'compatibles', 'compatibility', 'requestCompatible', 'product', 'favouriteStatus', 'productReviews', 'averageRating', 'reviewsCount', 'userHasReviewed', 'ratingDistribution', 'relatedProducts', 'classes'));
    }

    public function storeProductReview(Request $request, Product $product)
    {
        if (!Schema::hasTable('product_reviews')) {
            return redirect()->back()->with('error', __('Reviews are not set up yet. Please run: php artisan migrate'));
        }

        $rules = [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) {
                    if ($value && $value instanceof \Illuminate\Http\UploadedFile && !$value->isValid()) {
                        $fail(__('One or more images failed to upload. Use images under 5MB each (e.g. JPEG, PNG, GIF, WebP).'));
                    }
                },
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:5120',
            ],
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with('error', __('Please sign in to submit a review.'));
        }

        $data = [
            'rating' => (int) $request->rating,
            'comment' => $request->filled('comment') ? $request->comment : null,
            'status' => 'approved',
        ];

        $savedImages = [];
        if ($request->hasFile('images')) {
            $uploadDir = 'review';
            $publicPath = public_path('uploads/' . $uploadDir);
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $name = Str::random(16) . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $name);
                $savedImages[] = $uploadDir . '/' . $name;
            }
        }

        if (Schema::hasColumn('product_reviews', 'images')) {
            $existing = ProductReview::where('product_id', $product->id)->where('user_id', $user->id)->first();
            $existingImages = $existing && !empty($existing->images) ? $existing->images : [];
            $data['images'] = array_slice(array_merge($existingImages, $savedImages), 0, 3);
        }

        $review = ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
            $data
        );

        return redirect()->back()->with('success', __('Thank you! Your review has been submitted.'));
    }

    public function account(Request $request)
    {
        $authUser = authUser();               
        if($authUser){
            $orders = Order::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->get();
        }
        return view('frontend/account/index', compact('orders'));
    }

    public function order(Request $request)
    {
        $authUser = authUser();               
        if($authUser){
            $orders = Order::with('items')->where('user_id', $authUser->id)->orderBy('created_at', 'desc')->get();
        }
        return view('frontend/account/order', compact('orders'));
    }

    public function orderDetail($id)
    {
        $authUser = authUser(); 

        $id = substr($id, 0, strpos($id, '-'));

        $order = Order::with(['items', 'statuss'])->where('id',$id)->first();

        if($authUser->id != $order->user_id){
            return redirect()->route('home');
        }

       return view('frontend/account/detail',compact('order'));
    }

    public function changePassword(){
        return view('frontend/account/change-password');
    }

    public function changePasswordRequest(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'current-password' => [ 'required', 'min:6', new MatchOldPassword() ],
            'password' => [ 'required', 'min:6' ],
        ]);

        $validator->setAttributeNames([
            'current-password' => __('current password'),
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $user = authUser();

            $user->update(['password' => Hash::make($request->password)]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Change Password',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Change Password',
                'text' => 'Successfully Password updated.',
                'redirect' => route('website.account'),
            ],
        ]);
    }

    public function editProfile()
    {
        $user = authUser();
        return view('frontend/account/update-profile',compact('user'));     
    }

    public function updateProfile(Request $request)
    {
        $user = authUser();

        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'min:1', 'max:100'],
            'email' => [ 'nullable', Rule::unique('users')->where(function($query) use ($request, $user){
                $query->where('id', '!=' ,$user->id)->where('email_verified', 1);
            }), 'email', 'max:100' ],
            'mobile' => [ 'required', Rule::unique('users')->where(function($query) use ($request, $user){
                $query->where('id', '!=' ,$user->id)->where('mobile_verified', 1);
            }), 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
        ]);

        $validator->setAttributeNames([
            'mobile' => 'mobile number',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {

            $unicode = unicode();

            $otp = mt_rand(100000,999999);

            $input  = $request->only(['name', 'email']);

            if($input['email'] != $user->email){
                $input['email_verified'] = 0;  
                $input['verification_code'] = $unicode;  
                if($input['email']){
                    Mail::to($input['email'])->send( new VerificationMail($unicode, $input['name'])); 
                }
            }

            if( $user->mobile != $request->mobile ){
                $input['otp'] = $otp; 
                // sendSms($user->mobile, 'Your OTP to Register / Access @laptopspareworld.com is ' . $user->otp . '. It will be valid for 3 minutes.', '1307164086364084790');
            }

            $user->update($input);

        } catch (\Exception $e) {

            DB::rollback();
 
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Update Profile',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();


        $mobile = $request->mobile;
        $hash = Hash::make($mobile . $otp);

        if( $user->mobile != $request->mobile ){
            return response()->json([
                'jquery' => [
                    [
                        'element' => '#page',
                        'method' => 'html',
                        'value' => view('frontend/account/verify-profile',compact('user', 'mobile', 'hash'))->render(),
                    ],
                ],
                'init' => ['#page'],
            ]);
        }

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Update Profile',
                'text' => 'Successfully updated profile.',
                'redirect' => route('website.account'),
            ],
        ]);
    }

    public function verifyProfile(Request $request)
    {
 
        $validator = Validator::make(request()->all(), [
            'otp' => ['required', 'min:6', 'max:6'],
            'mobile' => ['required', 'min:10', 'max:10'],
            'hash' => ['required'],
            'id' => ['required'],
        ],[
            'otp.min' => 'Invalid OTP,  Try again!',
            'otp.max' => 'Invalid OTP, Try again!',
            'id.required' => 'Invalid OTP, Try again!',
            'mobile.required' => 'Invalid OTP, Try again!',
            'mobile.min' => 'Invalid OTP, Try again!',
            'mobile.max' => 'Invalid OTP, Try again!',
            'hash.required' => 'Invalid OTP, Try again!'
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {

            $user = User::where('id', $request->id)->where('status', 'active')->where('mobile_verified', 1)->where('otp', $request->otp)->first();

            if(!$user){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            'Invalid OTP, Try again!'
                        ]
                    ]
                ]);
            }

            $user = User::where('id', $request->id)->where('status', 'active')->where('mobile_verified', 1)->where('otp', $request->otp)->where('updated_at', '>=', Carbon::now()->subMinutes(3)->toDateTimeString())->first();

            if(!$user){
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Update Profile',
                        'text' => 'OTP expired, Try again!',
                        'redirect' => route('website.update.profile'),
                    ],
                ]);
            }
 
 
            if(! Hash::check($request->mobile .  $user->otp, $request->hash)  ) {
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Update Profile',
                        'text' => 'OTP expired, Try again!',
                        'redirect' => route('website.update.profile'),
                    ],
                ]);
            }
 
            $user->update([
                'mobile' => $request->mobile,
                'mobile_verified' => 1,
            ]);
  
        } catch (\Exception $e) {
 
            DB::rollback();

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Update Profile',
                    'text' => 'Something went wrong',
                    'redirect' => route('website.update.profile'),
                ],
            ]);

        }

        DB::commit();
 
        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Update Profile',
                'text' => 'Successfully updated profile.',
                'redirect' => route('website.account'),
            ],
        ]);
    }

    public function address(Request $request)
    {
        $authUser = authUser();
 
        $type = $request->type ?? 'normal';
        $defaultAddress = $authUser->defaultAddress();
        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
        $authUser->load(['address' => fn($q) => $q->with($usePincode ? ['pincode', 'location'] : ['location'])]);

        return view('frontend/account/address', compact('authUser', 'type', 'defaultAddress', 'usePincode'));

    }

    public function addressCreate(Request $request)
    {
        $authUser = authUser();
        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
        if ($usePincode) {
            $pincodes = Pincode::orderBy('pincode')->get();
            return view('frontend/account/address-create', compact('authUser', 'pincodes', 'usePincode'));
        }
        $locations = Location::orderBy('name')->get();
        $states = State::with(['locations' => fn($q) => $q->orderBy('name')])->orderBy('name')->get();
        $pincodes = collect();
        return view('frontend/account/address-create', compact('authUser', 'pincodes', 'usePincode', 'locations', 'states'));
    }

    public function addressSave(Request $request)
    {
        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
        $rules = [
            'type' => [ 'required', 'max:10' ],
            'name' => [ 'required', 'max:100' ],
            'mobile' => [ 'required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
            'line_1' => [ 'required' ],
            'line_2' => [ 'required' ],
            'line_3' => [ 'nullable' ],
        ];
        if ($usePincode) {
            $rules['pincode_id'] = [ 'required', 'integer', 'exists:pincodes,id' ];
        } else {
            $rules['location_id'] = [ 'required', 'integer' ];
        }
        $validator = Validator::make(request()->all(), $rules);

        $validator->setAttributeNames([
            'line_1' => __('address'),
            'line_2' =>  __('area and street'),
            'line_3' => __('Landmark (Optional)'),
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $user = authUser();

            Address::where('user_id', $user->id)->update([
                'default' => 0
            ]);

            if ($usePincode) {
                $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'pincode_id',  'type']);
                $input['location_id'] = null;
            } else {
                $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'location_id',  'type']);
            }
            
            $input['user_id'] = $user->id;

            $input['default'] = 1;
         
            Address::create($input);

        } catch (\Exception $e) {
            DB::rollback();

            \Illuminate\Support\Facades\Log::error('Address save failed', [
                'user_id' => isset($user) ? $user->id : null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => app()->hasDebugMode() && config('app.debug')
                        ? $e->getMessage()
                        : __('Failed to save address. Please try again.'),
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Address',
                'text' => 'Successfully saved.',
                'redirect' => route('website.address'),
            ],
        ]);
    }

     public function addressEdit(Request $request, Address $address)
    {
 
        $user = authUser();

        $type = $request->type ?? 'normal';

        if( $address->user_id != $user->id) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }

        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
        if ($usePincode) {
            $pincodes = Pincode::orderBy('pincode')->get();
            return view('frontend/account/address-edit', compact('user', 'address', 'type', 'pincodes', 'usePincode'));
        }
        $locations = Location::orderBy('name')->get();
        $states = State::with(['locations' => fn($q) => $q->orderBy('name')])->orderBy('name')->get();
        $pincodes = collect();
        return view('frontend/account/address-edit', compact('user', 'address', 'type', 'pincodes', 'usePincode', 'locations', 'states'));
    }

    public function addressUpdate(Request $request, Address $address)
    {
        $user = authUser();

        if( $address->user_id != $user->id) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
 
        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
        $rules = [
            'type' => [ 'required', 'max:10' ],
            'name' => [ 'required', 'max:100' ],
            'mobile' => [ 'required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
            'line_1' => [ 'required' ],
            'line_2' => [ 'required' ],
            'line_3' => [ 'nullable' ],
        ];
        if ($usePincode) {
            $rules['pincode_id'] = [ 'required', 'integer', 'exists:pincodes,id' ];
        } else {
            $rules['location_id'] = [ 'required', 'integer' ];
        }
        $validator = Validator::make(request()->all(), $rules);

        $validator->setAttributeNames([
            'line_1' => __('address'),
            'line_2' =>  __('area and street'),
            'line_3' => __('Landmark (Optional)'),
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            if ($usePincode) {
                $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'pincode_id',  'type']);
                $input['location_id'] = null;
            } else {
                $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'location_id',  'type']);
            }
            $address->update($input);

        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();
 
        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Address',
                'text' => 'Successfully updated.',
            ],
        ]);
    }

    public function addressDestroy(Request $request, Address $address)
    {
        $user = authUser();

        if( $address->user_id != $user->id) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
 
        DB::beginTransaction();
        try {

            if( $address->default == 1 ){
               $setDefault =  Address::where('user_id', $user->id)->where('id', '!=' , $address->id)->first();
                if($setDefault){
                    $setDefault->update([
                        'default' => 1
                    ]);
                }
            }
 
            $address->delete();

        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Address',
                'text' => 'Successfully deleted.',
                'redirect' => route('website.address'), 
            ]
        ]);
    }

    public function addressDefault(Request $request, Address $address)
    {
        $user = authUser();

        if( $address->user_id != $user->id) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Address',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
  
 
        DB::beginTransaction();
        try {

            Address::where('user_id', $user->id)->where('id', '!=' , $address->id)->update([
                'default' => 0
            ]);
 
            $input['default'] = 1;

            $address->update($input);

        } catch (\Exception $e) {
            DB::rollback();
 
            if( $address->user_id != $user->id) {
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Address',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }
        }
        DB::commit();

        $view = $request->view ?? 'normal';

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Address',
                'text' => 'Successfully changed default address.',
                'redirect' => route('website.address'), 
            ]
        ]);
    }

    public function favourite()
    {
        $authUser = authUser();

        $products = null;

        if($authUser){
            $products = Product::favourite($authUser->id);
        }
 
        return view('frontend/favourite/index', compact('products'));
    }

    public function favouriteToggle(Request $request, Product $product)
    {
 
        $authUser = authUser();

        if(!$authUser){
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Favourites',
                    'text' => 'Sign in to, Add to your Favourites.',
                ],
            ]);
        }

       if(Favourite::where('user_id', $authUser->id ?? 0 )->where('product_id', $product->id)->count() > 0){

            DB::beginTransaction();
            try {

                Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();

            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Favourites',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }

            DB::commit();

            return response()->json([

                'alert' => [
                    'icon' => 'success',
                    'title' => 'Favourites',
                    'text' => 'Removed from your Favourites.',
                ],

                'jquery' => [
                    [
                        'element' => '.item-product-' . $product->id . ' .fav-btn',
                        'method' => 'removeClass',
                        'value' => 'active',
                    ],
                ],

            ]);

       }else{

            DB::beginTransaction();
            try {

                Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();

                Favourite::create([
                    'user_id' => $authUser->id,
                    'product_id' => $product->id
                ]);

            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Favourites',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }

            DB::commit();

            return response()->json([
                'alert' => [
                    'icon' => 'success',
                    'title' => 'Favourites',
                    'text' => 'Added to your Favourites.',
                ],

                'jquery' => [
                    [
                        'element' => '.item-product-' . $product->id . ' .fav-btn',
                        'method' => 'addClass',
                        'value' => 'active',
                    ],
                ],

            ]);
       }
    }

    public function favouriteRemove(Request $request, Product $product)
    {
        $authUser = authUser();

        if(!$authUser){
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Favourites',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }

        DB::beginTransaction();
        try {

            Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();
 
        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Favourites',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Favourites',
                'text' => 'Removed from your Favourites.',
                'redirect' => route('website.favourite'),
            ],
        ]);
    }


    public function notify(Request $request, Product $product)
    {
 
        $authUser = authUser();

        if(!$authUser){
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Notify',
                    'text' => 'Sign in to notify.',
                ],
            ]);
        }
        
        if(Enquiry::where('user_id', $authUser->id ?? 0 )->where('product_id', $product->id)->count() > 0){

            DB::beginTransaction();
            try {
    
                Enquiry::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();
 
            } catch (\Exception $e) {
                DB::rollback();
     
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Notify',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }
            DB::commit();

            return response()->json([

                'alert' => [
                    'icon' => 'success',
                    'title' => 'Notify',
                    'text' => 'Removed from Product Notification.',
                ],

                'jquery' => [
                    [
                        'element' => '.item-product-' . $product->id . ' .notify-btn',
                        'method' => 'removeClass',
                        'value' => 'active',
                    ],
                ],

            ]);
            
        }else{

            DB::beginTransaction();
            try {
    
                Enquiry::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();
    
                Enquiry::create([
                    'user_id' => $authUser->id,
                    'product_id' => $product->id
                ]);
     
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Notify',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }
            DB::commit();

            return response()->json([
                'alert' => [
                    'icon' => 'success',
                    'title' => 'Notify',
                    'text' => 'We will notify you as soon as stock is available.',
                ],
                'jquery' => [
                    [
                        'element' => '.item-product-' . $product->id . ' .notify-btn',
                        'method' => 'addClass',
                        'value' => 'active',
                    ],
                ],
            ]);
        }
    }


    public function cart(Request $request)
    {
        $authUser = authUser();
        $cart = cartUpdate();

        $productIds = [];
        if (is_array($cart['products'])) {
            $productIds = array_map('intval', array_keys($cart['products']));
        }

        $products = Product::with(['brand', 'unit'])->whereIn('id', $productIds)->get();
        setCart($cart);

        $defaultAddress = null;
        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', false);
        $pincodes = collect();
        $locations = collect();
        $states = collect();
        $defaultDeliveryAmount = 0;
        $defaultMinAmount = 0;

        if ($authUser) {
            $defaultAddress = $authUser->defaultAddress();
            if ($defaultAddress) {
                $defaultAddress->load($usePincode ? ['pincode', 'location'] : ['location']);
            }
            if ($defaultAddress) {
                if ($usePincode && $defaultAddress->pincode) {
                    $defaultDeliveryAmount = $defaultAddress->pincode->delivery_charge;
                    $defaultMinAmount = $defaultAddress->pincode->minimum_cart_amount;
                } elseif ($defaultAddress->location) {
                    $defaultDeliveryAmount = $defaultAddress->location->delivery_charge;
                    $defaultMinAmount = $defaultAddress->location->minimum_cart_amount;
                }
            }
            if ($usePincode) {
                $pincodes = Pincode::orderBy('pincode')->get();
                $authUser->load(['address' => fn($q) => $q->with(['pincode', 'location'])]);
            } else {
                $locations = Location::orderBy('name')->get();
                $states = State::with(['locations' => fn($q) => $q->orderBy('name')])->orderBy('name')->get();
                $authUser->load(['address' => fn($q) => $q->with('location')]);
            }
        }

        return view('frontend/cart/index', compact('products', 'authUser', 'defaultAddress', 'usePincode', 'pincodes', 'locations', 'states', 'defaultDeliveryAmount', 'defaultMinAmount'));
    }

    public function cartProductRemove(Request $request, Product $product)
    {

        $cart = cartUpdate();
 
        if(isset($cart['products'])){
            unset($cart['products'][$product->id]);

            if(empty($cart['products'])){
                $cart['products'] = new \stdClass();
            }
        }

        setCart( $cart ); 
 
        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Cart',
                'text' => 'Cart item removed successfully.',
                'redirect' => route('website.cart'),
            ],
            
        ]);
    }


    public function cartProductMoveToFavourite(Request $request, Product $product)
    {
 
 
        $authUser = authUser();

        if(!$authUser){
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Favourites',
                    'text' => 'Sign in to, Add to your Favourites.',
                ],
            ]);
        }


        $cart = cartUpdate();
 
        if(isset($cart['products'])){
            unset($cart['products'][$product->id]);



            DB::beginTransaction();
            try {

                Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();

                Favourite::create([
                    'user_id' => $authUser->id,
                    'product_id' => $product->id
                ]);

            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Favourites',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }

            DB::commit();

            if(empty($cart['products'])){
                $cart['products'] = new \stdClass();
            }
        }

        setCart( $cart ); 
 
        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Cart',
                'text' => 'Product moved to favourite.',
                'redirect' => route('website.cart'),
            ],  
        ]);
    }


    public function checkout(Request $request)
    {
        $authUser = authUser();
        $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', false);

        $defaultAddress = $authUser->defaultAddress();
        if ($defaultAddress) {
            $defaultAddress->load($usePincode ? ['pincode', 'location'] : ['location']);
        }

        $cart = cartUpdate(true);

        $productIds = [];
        if(is_array($cart['products'])){
            $productIds = array_map('intval', array_keys($cart['products']));
        }
 
        $products = Product::whereIn('id', $productIds)->get();  

        setCart( $cart ); 
 
        $defaultDeliveryAmount = 0;
        $defaultMinAmount = 0;
        if ($defaultAddress) {
            if ($usePincode && $defaultAddress->pincode) {
                $defaultDeliveryAmount = $defaultAddress->pincode->delivery_charge;
                $defaultMinAmount = $defaultAddress->pincode->minimum_cart_amount;
            } elseif ($defaultAddress->location) {
                $defaultDeliveryAmount = $defaultAddress->location->delivery_charge;
                $defaultMinAmount = $defaultAddress->location->minimum_cart_amount;
            }
        }
        if ($usePincode) {
            $pincodes = Pincode::orderBy('pincode')->get();
            $authUser->load(['address' => fn($q) => $q->with(['pincode', 'location'])]);
            return view('frontend/order/checkout', compact('authUser', 'defaultAddress', 'pincodes', 'products', 'usePincode', 'defaultDeliveryAmount', 'defaultMinAmount'));
        }
        $locations = Location::orderBy('name')->get();
        $states = State::with(['locations' => fn($q) => $q->orderBy('name')])->orderBy('name')->get();
        $authUser->load(['address' => fn($q) => $q->with('location')]);
        $pincodes = collect();
        return view('frontend/order/checkout', compact('authUser', 'defaultAddress', 'pincodes', 'products', 'usePincode', 'locations', 'states', 'defaultDeliveryAmount', 'defaultMinAmount'));

    }


    public function cartCalculation(Request $request)
    {
        $delivery = null;

        if (Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true) && ($request->pincode_id || $request->pincode || $request->value)) {
            if ($request->pincode_id) {
                $delivery = Pincode::find($request->pincode_id);
            } elseif ($request->pincode) {
                $delivery = Pincode::where('pincode', $request->pincode)->first();
            } else {
                $delivery = Pincode::find($request->value);
                if (!$delivery) {
                    $delivery = Pincode::where('pincode', $request->value)->first();
                }
            }
        }

        if (!$delivery && ($locationId = $request->location_id ?? $request->location ?? $request->value ?? 0)) {
            $delivery = Location::find($locationId);
        }

        if (!$delivery) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Checkout',
                    'text' => $request->pincode || $request->pincode_id ? __('Invalid or unsupported pincode.') : __('Invalid location.'),
                ],
            ]);
        }

        return response()->json([
            'jquery' => [
                [
                    'element' => '#minimum-order-value-message',
                    'method' => 'html',
                    'value' => view('frontend/order/minimum-order-value-message', compact('delivery'))->render()
                ],
                [
                    'element' => '#calculation',
                    'method' => 'html',
                    'value' => view('frontend/order/calculation', compact('delivery'))->render()
                ],
            ],
        ]);
    }


    public function placeOrder(Request $request)
    {
        
        $authUser = authUser();

        $paymentMethod = 'cod'; //$request->payment_method ?? 'cod';
 
        $deliveryAddressId =  $request->delivery_address ?? 0;

        $address = null;

        if($deliveryAddressId == 0 ){
            $usePincode = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
            $rules = [
                'type' => [ 'required', 'max:10' ],
                'name' => [ 'required', 'max:100' ],
                'mobile' => [ 'required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
                'line_1' => [ 'required' ],
                'line_2' => [ 'required' ],
                'line_3' => [ 'nullable' ],
            ];
            if ($usePincode) {
                $rules['pincode_id'] = [ 'required', 'integer', 'exists:pincodes,id' ];
            } else {
                $rules['location_id'] = [ 'required', 'integer' ];
            }
            $validator = Validator::make(request()->all(), $rules);

            $validator->setAttributeNames([
                'line_1' => __('address'),
                'line_2' =>  __('area and street'),
                'line_3' => __('Landmark (Optional)'),
            ]);

            if (!$validator->passes()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }
            
    
            DB::beginTransaction();
            try {
 
                Address::where('user_id', $authUser->id)->update([
                    'default' => 0
                ]);

                if ($usePincode) {
                    $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'pincode_id',  'type']);
                    $input['location_id'] = null;
                } else {
                    $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'location_id',  'type']);
                }
                
                $input['user_id'] = $authUser->id;

                $input['default'] = 1;
            
                $address = Address::create($input);

            } catch (\Exception $e) {
                DB::rollback();
 
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Address',
                        'text' => 'Something went wrong.',
                    ],
                ]);
            }
            DB::commit();            
        } else {
            $usePincodeForAddress = Schema::hasTable('pincodes') && config('app.use_pincode_for_delivery', true);
            $address = Address::with($usePincodeForAddress ? ['pincode', 'location'] : ['location'])->find($deliveryAddressId);
        }

        if(!$authUser || !$address) {
 
            return response()->json([
                'redirect' => route('website.cart'),
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Place Order',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }

        $orderId = 0;
 
        
        DB::beginTransaction();
        try {   
 
            $cart = getCart();
 
            $cartProducts = [];

            if(isset($cart['products'])){
                $cartProducts = $cart['products'];
            }

            $order = Order::create([
                'user_id' => $authUser->id,
                'address_type' => $address->type,
                'address_name' => $address->name,
                'address_mobile' => $address->mobile,
                'address_line_1' => $address->line_1,
                'address_line_2' => $address->line_2,
                'address_line_3' => $address->line_3,
                'address_location' => $address->location->name,
                'address_local_location' => $address->location->local_name,
                'total_amount' => 0,
                'delivery_charge' => 0,
                'discount_amount' => 0,
                'canceled_amount' => 0,
                'final_amount' => 0,
                'status' => 'pending'
            ]);

            OrderStatus::create([
                'status' => 'pending',
                'public_note' => $paymentMethod == 'online' ? 'Payment Method : Online' : 'Payment Method : Cash On Delivery',
                'order_id' => $order->id
            ]);

            $total = 0;
 
            foreach($cartProducts as $cartProductId => $cartProduct){
                $product = Product::find($cartProductId);
    
                $quantity = $cartProduct['quantity'];
    
                if(!$product){

                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, Invalid cart item.',
                        ],
                    ]);
                }
    
                if($product->status != 'published'){

                    DB::rollback();

                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, Invalid cart item.',
                        ],
                    ]);
                }
    
                if($product->price != $cartProduct['price']  || $product->selling_price != $cartProduct['selling_price']){

                    DB::rollback();

                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, Product price has been updated.',
                        ],
                    ]);


                }
    
                if($product->stock_status == 'limited' && $product->stock_available < $quantity){
                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, Stock not available.',
                        ],
                    ]);
                }
    
                if($product->minimum_quantity > $quantity){   
                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, The product has been updated.',
                        ],
                    ]);
                }
    
                if($product->unit->stepper != $cartProduct['steper']){
                    DB::rollback();

                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, The product has been updated.',
                        ],
                    ]);
                } 
                
                if($product->stock_status == 'limited' && ( $product->stock_available <= 0 || $product->stock_available < $product->minimum_quantity) ){
                    DB::rollback();

                    return response()->json([
                        'redirect' => route('website.cart'),
                        'alert' => [
                            'icon' => 'error',
                            'title' => 'Place Order',
                            'text' => 'Try again, Stock not available.',
                        ],
                    ]);
                }

                $productTotal = $product->selling_price * ( $quantity / $product->unit->stepper );
    
                $total = $total + $productTotal;


                if($product->stock_status == 'limited' ){

                    $stockAvailable = ($product->stock_available - $quantity);

                    if($stockAvailable < 0){
                        $stockAvailable = 0;
                    }

                    $product->update([
                        'stock_available' => $stockAvailable
                    ]);
                }
 
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $product->name,
                    'local_product_name' => $product->local_name,
                    'product_image' => $product->image,
                    'product_id' => $product->id,
                    'category_id' => $product->category->id,
                    'brand_id' => $product->brand->id ?? null,
                    'product_code' =>  $product->product_code,
                    'unit_id' => $product->unit->id,
                    'unit_type' => $product->unit->type,
                    'local_unit_type' => $product->unit->local_type,
                    'stepper' => $product->unit->stepper,
                    'unit_name' => $product->unit->name,
                    'local_unit_name' => $product->unit->local_name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'selling_price' => $product->selling_price,
                    'final_price' => $productTotal,
                    'status' => 'pending',
                ]);
            }


            if( priceFormat($total, '') !=  cartTotalAmount()){

                DB::rollback();
 
                return response()->json([
                    'redirect' => route('website.cart'),
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Place Order',
                        'text' => 'Try again, Invalid cart item.',
                    ],
                ]);
            }

            $deliverySource = $address->pincode ?? $address->location;
            $deliveryCharge = $deliverySource ? $deliverySource->delivery_charge : 0;
            if ($deliverySource && isset($deliverySource->delivery_cart_amount) && $deliverySource->delivery_cart_amount && $deliverySource->delivery_cart_amount <= $total) {
                $deliveryCharge = 0;
            }

            $finalAmount = $deliveryCharge + $total;
 
            if($paymentMethod == 'online'){

                $order->update([
                    'total_amount' => $total,
                    'delivery_charge' => $deliveryCharge,
                    'final_amount' => $finalAmount,
                    'status' => 'payment-processing'
                ]);

                OrderStatus::create([
                    'status' => 'payment-processing',
                    'order_id' => $order->id
                ]);

            }else{

                $order->update([
                    'total_amount' => $total,
                    'delivery_charge' => $deliveryCharge,
                    'final_amount' => $finalAmount,
                    'status' => 'placed'
                ]);

                OrderStatus::create([
                    'status' => 'placed',
                    'order_id' => $order->id
                ]);
            }

            $orderId = $order->id;

        } catch (\Exception $e) {

            DB::rollback();

 
            return response()->json([
                'redirect' => route('website.cart'),
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Place Order',
                    'text' => 'Something went wrong.',
                ],
            ]);

        }

        DB::commit();

        $createdAt = Carbon::parse( $order->created_at );

        $cart = [];

        $cart['products'] = new \stdClass();
  
        setCart( $cart ); 

        if($paymentMethod == 'online'){

            $paymentGatewayUrl = "https://www.cashfree.com/checkout/post/submit";
            // $paymentGatewayUrl = "https://test.cashfree.com/billpay/checkout/post/submit";

            $secretKey = config('app.payment_gateway.secret', '') ;
            $paymentData = [
                "appId" => config('app.payment_gateway.key', '') , 
                "orderId" => $order->id, 
                "orderAmount" => $order->final_amount, 
                "orderCurrency" => 'INR', 
                "orderNote" => 'Reference No. ' . $order->id.'-'.$createdAt->format('dmy'), 
                "customerName" => $authUser->name, 
                "customerPhone" => $authUser->mobile, 
                "customerEmail" => $authUser->email,
                "returnUrl" => route('payment.response'), 
                "notifyUrl" => route('payment.response'),
            ];     
 
            ksort($paymentData);
            $signatureData = "";
            foreach ($paymentData as $key => $value){
                $signatureData .= $key.$value;
            }
            $signature = hash_hmac('sha256', $signatureData, $secretKey, true);
            $signature = base64_encode($signature);

            return response()->json([
                'jquery' => [
                    [
                        'element' => '#payment-form',
                        'method' => 'html',
                        'value' => view('frontend/order/payment-form',compact( 'paymentGatewayUrl', 'signature', 'paymentData' ))->render()
                    ],
                    [
                        'element' => '#payment-form form',
                        'method' => 'submit',
                    ],
                ],
            ]);

        }else{

            sendPushNotificationWithTopic( $authUser->name . ' placed new order');

            webhookEvents('order/placed', $orderId);

            return response()->json([
                'alert' => [
                    'icon' => 'success',
                    'title' => 'Place Order',
                    'text' => 'Order Placed successfully.',
                    'redirect' => route('website.order.detail',['order'=>$order->id.'-'.$createdAt->format('dmy')]),
                ],
            ]);
        }

    }

    public function paymentResponse(Request $request)
    {
        
        $secretkey = config('app.payment_gateway.secret', '');
 
        $orderId = $request->orderId;
        $orderAmount = $request->orderAmount;
        $referenceId = $request->referenceId;
        $txStatus = $request->txStatus;
        $paymentMode = $request->paymentMode;
        $txMsg = $request->txMsg;
        $txTime = $request->txTime;
        $signature = $request->signature;
        $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
        $hash_hmac =  hash_hmac('sha256', $data, $secretkey, true) ;
        $computedSignature = base64_encode($hash_hmac);

        $order = Order::find($orderId);

        $authUser = authUser();

        DB::beginTransaction();
        try {

            if($order && $order->final_amount == $orderAmount){


                if ($signature == $computedSignature) {
    

                    if($order->status == 'payment-processing'){
                        if ($txStatus == 'SUCCESS'){
        
                            $order->update([
                                'status' => 'placed'
                            ]);
        
                            OrderStatus::create([
                                'status' => 'placed',
                                'public_note' => 'Payment Mode: ' . $paymentMode . ', ' . $txMsg,
                                'private_note' => 'Reference ID: ' . $referenceId . ', Time: ' . $txTime,
                                'order_id' => $order->id
                            ]);
        
                        }else{
        
        
                            OrderStatus::create([
                                'status' => 'payment-failed',
                                'public_note' => 'Payment Mode: ' . $paymentMode . ', ' . $txMsg,
                                'private_note' => 'Reference ID: ' . $referenceId . ', Time: ' . $txTime,
                                'order_id' => $order->id
                            ]);
        
                            
                            foreach($order->items as $item){
                                $product = Product::find($item->product_id);
        
                                if($product && $product->stock_status == 'limited' ){
        
                                    $stockAvailable = ($product->stock_available + $item->quantity);
                
                                    if($stockAvailable < 0){
                                        $stockAvailable = 0;
                                    }
                
                                    $product->update([
                                        'stock_available' => $stockAvailable
                                    ]);
                                }
                            }
                    
                            $order->update([
                                'status' => 'canceled'
                            ]);
        
                            OrderStatus::create([
                                'status' => 'canceled',
                                'order_id' => $order->id
                            ]);

                            
                        }
                    }
                        
                }

                DB::commit();
                
                if($authUser){
                    $createdAt = Carbon::parse( $order->created_at );
                    return redirect()->route('website.order.detail',['order'=>$order->id.'-'.$createdAt->format('dmy')]);
                }else{
                    return redirect()->route('home');
                }
            }
 
    
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('home');
        }
        
        return redirect()->route('home');
    }

}
