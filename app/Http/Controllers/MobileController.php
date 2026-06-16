<?php

namespace App\Http\Controllers;
use App\Models\BannerSlider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Favourite;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Address;
use App\Models\Location;
use App\Models\State;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
 
class MobileController extends Controller
{
    public function pwa()
    {
        return view('mobile/pwa/index');
    }

    public function home(Request $request)
    {   
        // $build = request()->header('Build') ?? null;
        // $appVersion = request()->header('App-Version') ?? null;
        // $link = getOption('apple_app_store_link', '#');
  
        // if($build == 'pwa' && $appVersion != '2.0.0' ){
        //     return page('home', route('mobile.home'), view('mobile/update/index', compact('link'))->render());
        // }

        $authUser = authUser('api');
 
        $bannerSliders = BannerSlider::orderBy('priority', 'desc')->get(); 
        $categories = Category::orderBy('priority', 'desc')->get(); 
        $featuredProducts = Product::featured();
        $offerProducts = Product::offer();

        $decimalPlace = decimalPlace();
 
 
        $attributes = [
            'jquery' => [
                [
                    'element' => '#sidebar',
                    'method' => 'html',
                    'value' => view('mobile/home/navigation', compact('authUser'))->render(),
                ],
            ],
            'decimal_place' => $decimalPlace,
            'init' => ['#sidebar']
        ];

        $intro = request()->header('Intro') ?? null;

        if($intro == 'show'){
            $attributes['intro'] = 'hide'; 
        }

        $locale = App::getLocale();

        if($locale != 'en'){
            $attributes['jquery'][] = [
                'element' => 'body',
                'method' => 'addClass',
                'value' => 'local',
            ];
        }else{
            $attributes['jquery'][] = [
                'element' => 'body',
                'method' => 'removeClass',
                'value' => 'local',
            ];
        }
 
        return page('home', route('mobile.home'), view('mobile/home/index', compact('bannerSliders', 'categories', 'featuredProducts', 'offerProducts'))->render(), $attributes);
    }
 

    public function products(Request $request)
    {
        $page = $request->page ?? 1;
        $sortby = $request->sortby ?? 'featured';
        $search = $request->search ?? '';
        $category_id = $request->category_id ?? null;
        $categories = Category::orderBy('name', 'asc')->orderBy('priority', 'desc')->get();
        $products = Product::retrieve($sortby, $search, $category_id);

        $title  = __('All Books');

        if($search){
            $title  = __('Search') .  ': ' . $search;
        }


        if($category_id){
            $category = Category::findOrFail($category_id);
            $title  = _local($category->name, $category->local_name);

            if($search){
                $title  = __('Search') . ' ' . _local($category->name, $category->local_name) .  ': ' . $search;
            }
        }


        $cart = cartUpdate();
  
        $request->merge([
            'cart' => json_encode($cart),
        ]);

 
        if($page > 1){
 
            return response()->json([
                'cart' => $cart,
                'jquery' => [
                    [
                        'element' => '#pagination-id-' . $page,
                        'method' => 'after',
                        'value' => view('mobile/product/list', compact('page', 'sortby', 'search', 'category_id', 'products'))->render(),
                    ],
                    [
                        'element' => '#pagination-nav-' . $page,
                        'method' => 'remove',
                    ],
                ],
                'init' => ['#pagination-id-' . ($page + 1), '.pagination']
            ]);
        }
 
        return page('products', route('mobile.home'), view('mobile/product/index', compact('title', 'page', 'sortby', 'search', 'category_id', 'categories', 'products'))->render(),[
            'cart' => $cart
        ]);
    }

    public function product(Request $request, Product $product)
    {

        $cart = cartUpdate();
 
        $request->merge([
            'cart' => json_encode($cart),
        ]);
 
        $authUser = authUser('api');
        $favouriteStatus = false;
        if($authUser){
            $favouriteCount = Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->count();
            if($favouriteCount > 0){
                $favouriteStatus = true;
            }
        }
 
        $returnLink = $request->referral ?? route('mobile.products', ['category_id' => $product->category_id]);
 
        return page('product', $returnLink, view('mobile/product/view', compact('product', 'returnLink', 'favouriteStatus'))->render(), [
            'cart' => $cart
        ]);
    }

    public function productZoom(Request $request, Product $product)
    {
        $items = [];
 
        if($product->image){
            $items[] = [
                'src' => asset('uploads/' . str_replace('/base/','/large/', $product->image)),
                'w' => 0,
                'h' => 0
            ];
        }else{
            $items[] = [
                'src' => asset('assets/mobile/img/200x150-blank.png'),
                'w' => 0,
                'h' => 0
            ]; 
        }

        if($product->gallery_image_1){
            $items[] = [
                'src' => asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_1)),
                'w' => 0,
                'h' => 0
            ]; 
        }

        if($product->gallery_image_2){
            $items[] = [
                'src' => asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_2)),
                'w' => 0,
                'h' => 0
            ]; 
        }

        if($product->gallery_image_3){
            $items[] = [
                'src' => asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_3)),
                'w' => 0,
                'h' => 0
            ]; 
        }

        return response()->json([
            'photoswipe' => [
                'items' => $items,
                'index' => $request->index,
            ]   
        ]);
    }


    public function notify(Request $request, Product $product)
    {
 
        $authUser = authUser('api');

        if(!$authUser){
            return response()->json([
                'toast' => __('Sign in to notify.'),
            ]);
        }
 
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
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        return response()->json([
            'vibrate' => true,
            'toast' => __('We will notify you as soon as stock is available.'),
        ]);
    }

    public function favourite()
    {
        $authUser = authUser('api');

        $products = null;

        if($authUser){
            $products = Product::favourite($authUser->id);
        }
 
        return page('favourite', route('mobile.home'), view('mobile/favourite/index', compact('products'))->render());
    }

    public function favouriteAdd(Request $request, Product $product)
    {
 
        $authUser = authUser('api');

        if(!$authUser){
            return response()->json([
                'toast' => __('Sign in to, Add to favorite.'),
            ]);
        }
 
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
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $returnLink = $request->referral ?? route('mobile.favourite');

        return response()->json([
            'redirect' => $returnLink,
            'vibrate' => true,
            'toast' => __('Added to favourite.'),
        ]);
    }
 
    public function favouriteRemove(Request $request, Product $product)
    {

        $authUser = authUser();

        if(!$authUser){
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        
        DB::beginTransaction();
        try {

            Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();
 
        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $returnLink = $request->referral ?? route('mobile.favourite');

        return response()->json([
            'redirect' => $returnLink,
            'vibrate' => true,
            'toast' => __('Removed from favourite.'),
        ]);
    }
 
    public function cartProductMoveToFavourite(Request $request, Product $product)
    {
 
        $authUser = authUser('api');

        if(!$authUser){
            return response()->json([
                'toast' => __('Sign in to add to favorite.'),
            ]);
        }
 
        DB::beginTransaction();
        try {

            Favourite::where('user_id', $authUser->id)->where('product_id', $product->id)->delete();

            $cart = json_decode(request()->cart, true);
 
            if(isset($cart['products'])){
                unset($cart['products'][$product->id]);
                
                if(empty($cart['products'])){
                    $cart['products'] = new \stdClass();
                }
            }
            
            Favourite::create([
                'user_id' => $authUser->id,
                'product_id' => $product->id
            ]);
 
        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $returnLink = $request->referral ?? route('mobile.favourite');

        return response()->json([
            'redirect' => $returnLink,
            'vibrate' => true,
            'cart' => $cart,
            'toast' => __('Product moved to favourite.'),
        ]);
    }

    public function cartProductRemove(Request $request, Product $product)
    {
 
        $cart = json_decode(request()->cart, true);
 
        if(isset($cart['products'])){
            unset($cart['products'][$product->id]);

            if(empty($cart['products'])){
                $cart['products'] = new \stdClass();
            }
        }
 
        return response()->json([
            'redirect' => route('mobile.cart'),
            'vibrate' => true,
            'cart' => $cart,
            'toast' => __('Cart item removed successfully.'),
        ]);
    }

    public function cart(Request $request)
    {
        $authUser = authUser('api');

        $cart = cartUpdate();
 
        $productIds = [];
        if(is_array($cart['products'])){
            $productIds = array_map('intval', array_keys($cart['products']));
        }
        
        $request->merge([
            'cart' => json_encode($cart),
        ]);
 
        $products = Product::whereIn('id', $productIds)->get();

        $returnLink = $request->referral ?? route('mobile.home');

        return page('cart', $returnLink, view('mobile/cart/index', compact('products', 'authUser'))->render(), [
            'cart' => $cart
        ]);
    }
 
    public function orderSummary(Request $request)
    {

        $authUser = authUser();

        $defaultAddress =  $authUser->defaultAddress();

        if(!$authUser || !$defaultAddress) {
            return response()->json([
                'redirect' => route('mobile.cart'),
                'toast' => __('Something went wrong.'),
            ]);  
        }
 
        $cart = cartUpdate(true);
 
        $productIds = [];
        if(is_array($cart['products'])){
            $productIds = array_map('intval', array_keys($cart['products']));
        }


        $request->merge([
            'cart' => json_encode($cart),
        ]);
 
        $products = Product::whereIn('id', $productIds)->get();
 
        if($products->count() <= 0){
            return response()->json([
                'redirect' => route('mobile.cart'),
                'toast' => __('Cart is empty.'),
            ]);   
        }
 
        return page('order-summary', route('mobile.address', ['type' => 'select']), view('mobile/order/summary', compact('defaultAddress', 'products'))->render(), [
            'cart' => $cart
        ]);
    }
 
    public function placeOrder(Request $request)
    {
        $authUser = authUser();

        $defaultAddress =  $authUser->defaultAddress();

        if(!$authUser || !$defaultAddress) {
            return response()->json([
                'redirect' => route('mobile.cart'),
                'toast' => __('Something went wrong.'),
            ]);  
        }
 
        DB::beginTransaction();
        try {   
 
            $cart = json_decode(request()->cart, true);
 
            $cartProducts = [];

            if(isset($cart['products'])){
                $cartProducts = $cart['products'];
            }

            $projectType = $request->project_type ?? 'Residential';
            $architectName = $request->architect_name ?? null;
            $cuttingCharge = floatval($request->cutting_charge ?? 0);
            $installationCharge = floatval($request->installation_charge ?? 0);
            $manualDiscount = floatval($request->manual_discount ?? 0);
            $transportationCharge = floatval($request->transportation_charge ?? ($defaultAddress->location->delivery_charge ?? 0));
            $validityDate = now()->addDays(30);

            $order = Order::create([
                'user_id' => $authUser->id,
                'address_type' => $defaultAddress->type,
                'address_name' => $defaultAddress->name,
                'address_mobile' => $defaultAddress->mobile,
                'address_line_1' => $defaultAddress->line_1,
                'address_line_2' => $defaultAddress->line_2,
                'address_line_3' => $defaultAddress->line_3,
                'address_location' => $defaultAddress->location->name,
                'address_local_location' => $defaultAddress->location->local_name,
                'total_amount' => 0,
                'delivery_charge' => $transportationCharge,
                'discount_amount' => $manualDiscount,
                'canceled_amount' => 0,
                'final_amount' => 0,
                'status' => 'pending',
                'project_type' => $projectType,
                'architect_name' => $architectName,
                'cutting_charge' => $cuttingCharge,
                'transportation_charge' => $transportationCharge,
                'installation_charge' => $installationCharge,
                'manual_discount' => $manualDiscount,
                'validity_date' => $validityDate,
            ]);

            OrderStatus::create([
                'status' => 'pending',
                'order_id' => $order->id
            ]);

            $total = 0;
 
            foreach($cartProducts as $cartProductId => $cartProduct){
                $product = Product::find($cartProductId);
    
                $quantity = $cartProduct['quantity'];
    
                if(!$product){

                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, Invalid cart item.'),
                    ]);
                }
    
                if($product->status != 'published'){

                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, Invalid cart item.'),
                    ]);
                }
    
                if($product->price != $cartProduct['price']  || $product->selling_price != $cartProduct['selling_price']){

                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, Product price has been updated.'),
                    ]);

                }
    
                if($product->stock_status == 'limited' && $product->stock_available < $quantity){
                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, Stock not available.'),
                    ]);
                }
    
                if($product->minimum_quantity > $quantity){   
                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, The product has been updated.'),
                    ]);
                }
    
                if($product->unit->stepper != $cartProduct['steper']){
                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, The product has been updated.'),
                    ]);
                } 
                
                if($product->stock_status == 'limited' && ( $product->stock_available <= 0 || $product->stock_available < $product->minimum_quantity) ){
                    DB::rollback();
            
                    return response()->json([
                        'redirect' => route('mobile.cart'),
                        'toast' => __('Try again, Stock not available.'),
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
                    'area_sqft' => $quantity,
                    'thickness' => '18mm',
                    'finish_type' => 'Polished',
                ]);
            }


            if( priceFormat($total, '') !=  cartTotalAmount()){

                DB::rollback();

                return response()->json([
                    'redirect' => route('mobile.cart'),
                    'toast' => __('Try again, Invalid cart item.'),
                ]);
            }

            $finalAmount = $total + $cuttingCharge + $transportationCharge + $installationCharge - $manualDiscount;

            // Change
            $order->update([
                'total_amount' => $total,
                'delivery_charge' => $transportationCharge,
                'final_amount' => $finalAmount,
                'status' => 'placed'
            ]);
 

            OrderStatus::create([
                'status' => 'placed',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'redirect' => route('mobile.cart'),
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        sendPushNotificationWithTopic( $authUser->name . ' placed new order');

        webhookEvents('order/placed', $order->id);

        $cart = [];

        $cart['products'] = new \stdClass();
 
        // $api = new RazorpayApi(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        // $razorpayorder = $api->order->create(array(
        //     'receipt' => $order->id . '_' . $authUser->id . '_' . rand(1000,9999),
        //     'amount' => $finalAmount * 100,
        //     'currency' => 'INR'
        //     )
        // );

        // Payment::create([
        //     'user_id' => $authUser->id,
        //     'course_id' => $course->id,
        //     'amount' => $finalAmount,
        //     'razorpay_order_id' => $razorpayorder->id,
        // ]);

        // return response()->json([
        //     'razorpay' => [
        //         'element' => '#checkout-btn',
        //         'option' => [
        //             'key' => env('RAZOR_KEY'),
        //             'amount' => $razorpayorder->amount,
        //             'currency' => $razorpayorder->currency,
        //             'name' => config('app.name', 'Laravel'),
        //             'description' => $order->id . ' Payment',
        //             'order_id' => $razorpayorder->id,
        //             'prefill' => [
        //                 'name' => $authUser->name,
        //                 'email' => $authUser->email,
        //                 'contact' => $authUser->mobile,
        //             ],
        //             'notes' => [
        //                 'address' => 'Razorpay Corporate Office'
        //             ]
        //         ],
        //         'response' => route('mobile.order.success'),
        //     ],
        // ]);
 
        return response()->json([
            'redirect' => route('mobile.order.success'),
            'cart' => $cart
        ]);
    }
 
    public function orderSuccess(Request $request)
    {
        return page('order.success', route('mobile.home'), view('mobile/order/success')->render(),[
            'party' => '#party'
        ]);
    }

    public function orders()
    {
        $authUser = authUser('api');

        $orders = null;

        if($authUser){
            $orders = Order::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->get();
        }
 
        return page('orders', route('mobile.home'), view('mobile/order/index', compact('orders'))->render());

    }

    public function orderDetail(Order $order)
    {
        $authUser = authUser('api');

        if($order->user_id !=  $authUser->id){
            return response()->json([
                'redirect' => route('mobile.orders'),
                'toast' => __('Try again, Invalid order.'),
            ]);
        }
 
        return page('orders', route('mobile.orders'), view('mobile/order/detail', compact('order'))->render());
    }

    public function account(Request $request)
    {
 
        $language = request()->header('Language');

        $authUser = authUser('api');
 
        return page('account', route('mobile.home'), view('mobile/account/index', compact('language', 'authUser'))->render());
    }

    public function switchLanguage(Request $request)
    {
        $language = $request->value ?? 'en';
        
        $authUser = authUser('api');

        App::setLocale($language);

        $attributes['language'] = $language;

        if($language != 'en'){
            $attributes['jquery'][] = [
                'element' => 'body',
                'method' => 'addClass',
                'value' => 'local',
            ];
        }else{
            $attributes['jquery'][] = [
                'element' => 'body',
                'method' => 'removeClass',
                'value' => 'local',
            ];
        }
 
        return page('switch-language', route('mobile.home'), view('mobile/account/index', compact('language', 'authUser'))->render(), $attributes);
    }

    public function editProfile()
    {
        $user = authUser();

        return page('update-profile', route('mobile.account'), view('mobile/account/update-profile', compact('user'))->render());
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
            }

            $user->update($input);

        } catch (\Exception $e) {

            DB::rollback();
 
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();


        $mobile = $request->mobile;
        $hash = Hash::make($mobile . $otp);

        if( $user->mobile != $request->mobile ){
            return page('verify', route('mobile.edit.profile'), view('mobile/account/verify-profile', compact('user', 'mobile', 'hash'))->render());
        }

        return response()->json([
            'redirect' => route('mobile.account'),
            'toast' => __('Profile updated successfully.'),
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

            $user = User::where('id', $request->id)->where('status', 'active')->where('mobile_verified', 1)->where('otp', $request->otp)->where('updated_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())->first();

            if(!$user){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            __('OTP expired, Try again!')
                        ]
                    ],
                    'toast' => __('OTP expired, Try again!'),
                    'redirect' => route('mobile.edit.profile')
                ]);
            }
 
 
            if(! Hash::check($request->mobile .  $user->otp, $request->hash)  ) {
                return response()->json([
                    'errors' => [
                        'otp' => [
                            __('OTP expired, Try again!')
                        ]
                    ],
                    'toast' => __('OTP expired, Try again!'),
                    'redirect' => route('mobile.edit.profile')
                ]);     
            }
 
            $user->update([
                'mobile' => $request->mobile,
                'mobile_verified' => 1,
            ]);
  
        } catch (\Exception $e) {
 
            DB::rollback();
            return response()->json([
                'errors' => [
                    'otp' => [
                        __('Something went wrong.')
                    ]
                ],
                'toast' => __('Something went wrong.'),
                'redirect' => route('mobile.edit.profile')
            ]);
        }

        DB::commit();
 
        return response()->json([
            'redirect' => route('mobile.account'),
            'toast' => __('Profile updated successfully.'),
        ]);
    }


    public function address(Request $request)
    {
        $authUser = authUser();
 
        $type = $request->type ?? 'normal';

        $locations = Location::all();
        $states = State::all();

        if($authUser->address->count() <= 0){
            return page('address-create', route('mobile.account', ['type' => $type]), view('mobile/address/create', compact('authUser', 'type', 'locations', 'states'))->render());
        }

        $defaultAddress =  $authUser->defaultAddress();
 
        if($type == 'select'){
            return page('address', route('mobile.cart'), view('mobile/address/index', compact('authUser', 'type', 'defaultAddress'))->render());
        }else{
            return page('address', route('mobile.account'), view('mobile/address/index', compact('authUser', 'type', 'defaultAddress'))->render());
        }
    }

    public function addressCreate(Request $request)
    {
        $authUser = authUser();

        $type = $request->type ?? 'normal';

        $locations = Location::all();
        $states = State::all();
        
        return page('address-create', route('mobile.address', ['type' => $type]), view('mobile/address/create', compact('authUser', 'type', 'locations', 'states'))->render());
    }
 
    public function addressSave(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'type' => [ 'required', 'max:10' ],
            'name' => [ 'required', 'max:100' ],
            'mobile' => [ 'required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15' ],
            'line_1' => [ 'required' ],
            'line_2' => [ 'required' ],
            'line_3' => [ 'nullable' ],
            'location_id' => [ 'required', 'integer' ],
        ]);

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

            $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'location_id',  'type',  ]);
            
            $input['user_id'] = $user->id;

            $input['default'] = 1;
         
            Address::create($input);

        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $view = $request->view ?? 'normal';

        if($view == 'select'){
            return response()->json([
                'redirect' => route('mobile.address', ['type' => $view]),
            ]);
        }
        
        return response()->json([
            'redirect' => route('mobile.address', ['type' => $view]),
            'toast' => __('Address saved successfully.'),
        ]);
    }
 
    public function addressEdit(Request $request, Address $address)
    {
 
        $user = authUser();

        $type = $request->type ?? 'normal';

        if( $address->user_id != $user->id) {
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);  
        }

        $locations = Location::all();
        $states = State::all();
        
 
        return page('address-edit', route('mobile.address', ['type' => $type]), view('mobile/address/edit', compact('user', 'address', 'type', 'locations', 'states'))->render());
    }
 
    public function addressUpdate(Request $request, Address $address)
    {

        $user = authUser();

        if( $address->user_id != $user->id) {
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);  
        }
 
        $validator = Validator::make(request()->all(), [
            'type' => [ 'required', 'max:10' ],
            'name' => [ 'required', 'max:100' ],
            'mobile' => [ 'required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15' ],
            'line_1' => [ 'required' ],
            'line_2' => [ 'required' ],
            'line_3' => [ 'nullable' ],
            'location_id' => [ 'required', 'integer' ],
        ]);

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

            Address::where('user_id', $user->id)->where('id', '!=' , $address->id)->update([
                'default' => 0
            ]);
  
            $input = $request->only([ 'name', 'mobile',  'line_1',  'line_2',  'line_3',  'location_id',  'type',  ]);
            
            $input['default'] = 1;

            $address->update($input);

        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $view = $request->view ?? 'normal';

        if($view == 'select'){
            return response()->json([
                'redirect' => route('mobile.address', ['type' => $view]),
            ]);
        }

        return response()->json([
            'redirect' => route('mobile.address', ['type' => $view]),
            'toast' => __('Address updated successfully.'),
        ]);
    }

    public function addressDestroy(Request $request, Address $address)
    {
        $user = authUser();

        if( $address->user_id != $user->id) {
            return response()->json([
                'toast' => __('Something went wrong.'),
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
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $type = $request->type ?? 'normal';

        return response()->json([
            'redirect' => route('mobile.address', ['type' => $type]), 
            'toast' => __('Address deleted successfully.'),
        ]);
    }

    public function addressDefault(Request $request, Address $address)
    {

        $user = authUser();

        if( $address->user_id != $user->id) {
            return response()->json([
                'toast' => __('Something went wrong.'),
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
 
            return response()->json([
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        $view = $request->view ?? 'normal';

        return response()->json([
            'redirect' => route('mobile.address', ['type' => $view]),
        ]);
    }

    public function changePassword()
    {
        return page('change-password', route('mobile.account'), view('mobile/account/change-password')->render());
    }

    public function changePasswordRequest(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'current-password' => [ 'required', 'min:6', new MatchOldPassword('api') ],
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
                'errors' => [
                    'current-password' => 'Something went wrong.',
                ],
                'toast' => __('Something went wrong.'),
            ]);
        }
        DB::commit();

        return response()->json([
            'redirect' => route('mobile.account'),
            'toast' => __('Password updated successfully.'),
        ]);
    }

    public function passwordReset()
    {
        return page('password-reset', route('mobile.signin'), view('mobile/password-reset/index')->render());
    }
 
    public function passwordResetRequest(Request $request)
    {
 
        if( is_numeric( $request->email ) ){
            
            $validator = Validator::make(request()->all(), [
                'email' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10'],
            ],[
                'email.min' => __('The Mobile Number may not be less than 10 digits.'),
                'email.max' => __('The Mobile Number may not be greater than 10 digits.')
            ]);

            $validator->setAttributeNames([
                'email' => 'Mobile Number',
            ]);
 
        }else{

            $validator = Validator::make(request()->all(), [
                'email' => ['required', 'email'],
            ],[
                'email.email' => __('The Email / Mobile Number must be a valid email address or mobile number.'), 
            ]);

            $validator->setAttributeNames([
                'email' => 'Email / Mobile Number',
            ]);
        }
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
 
            $otp = mt_rand(100000,999999);
            $email = $request->email;

            if( is_numeric( $email ) ){
                $user = User::where('mobile', $email)->where('status', 'active')->where('mobile_verified', 1)->first();

            }else{
                $user = User::where('email', $email)->where('status', 'active')->where('email_verified', 1)->first();
            }
  
            if($user){

                if($user->email != '' && $user->email_verified == 1){
                    Mail::to($user->email)->send( new OtpMail($otp)); 
                }

                $user->update([
                    'otp' => $otp
                ]);
            }else{

                return response()->json([
                    'errors' => [
                        'email' => [
                            'Account is blocked or not available.'
                        ]
                    ],
                ]);

            } 
 
        } catch (\Exception $e) {

            DB::rollback();
 
            return response()->json([
                'errors' => [
                    'email' => [
                        'Something went wrong.'
                    ]
                ],
            ]);
        }

        DB::commit();

        return page('password-reset-verify', route('mobile.signin'), view('mobile/password-reset/verify', compact('email'))->render());

    }

    public function passwordResetVerify(Request $request)
    {   

        $validator = Validator::make(request()->all(), [
            'otp' => ['required', 'min:6', 'max:6'],
            'email' => ['required'],
            'password' => ['required', 'min:6']
        ],[
            'otp.min' => 'Invalid OTP,  Try again!',
            'otp.max' => 'Invalid OTP, Try again!',
            'email.required' => 'Invalid OTP, Try again!'
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
 

            $email = $request->email;

            if( is_numeric( $email ) ){
                $user = User::where('mobile', $email)->where('status', 'active')->where('mobile_verified', 1)->where('otp', $request->otp)->first();
            }else{
                $user = User::where('email', $email)->where('status', 'active')->where('email_verified', 1)->where('otp', $request->otp)->first();
            } 
 
            if(!$user){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            'Invalid OTP, Try again!'
                        ]
                    ]
                ]);
            }


            if( is_numeric( $email ) ){
                $user = User::where('mobile', $email)->where('status', 'active')->where('mobile_verified', 1)->where('otp', $request->otp)->where('updated_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())->first();
            }else{
                $user = User::where('email', $email)->where('status', 'active')->where('email_verified', 1)->where('otp', $request->otp)->where('updated_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())->first();
            }

            if(!$user){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            __('OTP expired, Try again!')
                        ]
                    ],
                    'toast' => __('OTP expired, Try again!'),
                    'redirect' => route('mobile.password.reset')
                ]);
            }

            $random = Str::random(40);

            $user->update([
                'password' => Hash::make($request->password),
                'api_token' => Hash::make($random)
            ]);

            $token = $user->id . '|' . $random;
            
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'errors' => [
                    'otp' => [
                        __('Something went wrong.')
                    ]
                ],
                'toast' => __('Something went wrong.'),
                'redirect' => route('mobile.password.reset')
            ]);
        }

        DB::commit();

        return response()->json([
            'toast' => __('Password reset successfully.'),
            'authorization' => $token,
            'redirect' => route('mobile.home')
        ]);
    }
 
    public function signup(Request $request)
    {

        $return = $request->return ?? 'home';
 
        $returnLink = route('mobile.account');

        if($return == 'cart'){
            $returnLink = route('mobile.cart');
        }

        return page('signup', $returnLink, view('mobile/signup/index', compact('return') )->render());
    }
 
    public function signupRequest(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'min:1', 'max:100'],
            'email' => [ 'nullable', Rule::unique('users')->where(function($query) use ($request){
                $query->where('email_verified', 1);
            }), 'email', 'max:100' ],
            'mobile' => [ 'required', Rule::unique('users')->where(function($query) use ($request){
                $query->where('mobile_verified', 1);
            }), 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
            'password' => ['required', 'min:6']
        ]);

        $validator->setAttributeNames([
            'mobile' => __('Mobile Number'),
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $fcm = request()->header('Fcm') ?? null;

        $otp = mt_rand(100000,999999);
 
        DB::beginTransaction();
        try {

            $input  = $request->only(['name', 'email', 'mobile']);
 
            $input['password'] = Hash::make($request->password);  

            $input['username'] = $input['mobile'];

            $input['status'] = 'created';
        
            $input['role_id'] = 1;

            $input['fcm'] = $fcm;

            $input['otp'] = $otp;
 
            $user = User::where('mobile', $input['mobile'])->where('status', 'created')->first();

            if($user){
                $user->update($input);
            }else{
                $user = User::create($input);
            }


            $sent = false;
            if (config('whatsapp.use_whatsapp_otp')) {
                $sent = sendWhatsAppOtp($user->mobile, $user->otp);
            } else {
                $smsResult = sendSms($user->mobile, 'Your OTP to Register / Access @laptopspareworld.com is ' . $user->otp . '. It will be valid for 3 minutes.', '1307164086364084790');
                $sent = ($smsResult !== false && strpos($smsResult, 'Error') === false);
            }
            if (!$sent) {
                \Illuminate\Support\Facades\Log::warning('Mobile signup OTP: Delivery failed', ['user_id' => $user->id, 'mobile' => $user->mobile]);
                return response()->json([
                    'errors' => ['mobile' => [__('OTP could not be sent. Please check your mobile number or try again later.')]],
                    'toast' => __('OTP could not be sent.'),
                ]);
            }

        } catch (\Exception $e) {

            DB::rollback();

            \Illuminate\Support\Facades\Log::error('Registration exception: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'errors' => [
                    'mobile' => [
                        __('Something went wrong.')
                    ]
                ],
                'toast' => __('Something went wrong.'),
            ]);
        }

        DB::commit();

        $return = $request->return ?? 'home';

        return page('verify', route('mobile.signup'), view('mobile/signup/verify', compact('user', 'return'))->render());
    }

    public function signupVerify(Request $request)
    {
        $return = $request->return ?? 'home';

        $validator = Validator::make(request()->all(), [
            'otp' => ['required', 'min:6', 'max:6'],
            'id' => ['required'],
        ],[
            'otp.min' => 'Invalid OTP,  Try again!',
            'otp.max' => 'Invalid OTP, Try again!',
            'id.required' => 'Invalid OTP, Try again!'
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {

            $user = User::where('id', $request->id)->where('status', 'created')->where('mobile_verified', 0)->where('otp', $request->otp)->first();

            if(!$user){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            'Invalid OTP, Try again!'
                        ]
                    ]
                ]);
            }

            $user = User::where('id', $request->id)->where('status', 'created')->where('mobile_verified', 0)->where('otp', $request->otp)->where('updated_at', '>=', Carbon::now()->subMinutes(3)->toDateTimeString())->first();

            if(!$user){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            __('OTP expired, Try again!')
                        ]
                    ],
                    'toast' => __('OTP expired, Try again!'),
                    'redirect' => route('mobile.signup', ['return' => $return])
                ]);
            }

            $random = Str::random(40);

            $count = User::where('mobile', $user->mobile)->where('mobile_verified', 1)->count();

            if($count > 0){
                return response()->json([
                    'errors' => [
                        'otp' => [
                            __('OTP expired, Try again!')
                        ]
                    ],
                    'toast' => __('OTP expired, Try again!'),
                    'redirect' => route('mobile.signup', ['return' => $return])
                ]);       
            }

            $unicode = unicode();
 
            $user->update([
                'status' => 'active',
                'mobile_verified' => 1,
                'api_token' => Hash::make($random),
                'verification_code' => $unicode,
            ]);

            if($user->email){
                Mail::to($user->email)->send( new VerificationMail($unicode, $user->name)); 
            }

            // Don't auto-login, redirect to login page instead
 
        } catch (\Exception $e) {
 
            DB::rollback();
            return response()->json([
                'errors' => [
                    'otp' => [
                        __('Something went wrong.')
                    ]
                ],
                'toast' => __('Something went wrong.'),
                'redirect' => route('mobile.signup', ['return' => $return])
            ]);
        }

        DB::commit();
        
        return response()->json([
            'toast' => __('Account created successfully. Please login to continue.'),
            'redirect' => route('mobile.signin', ['return' => $return])
        ]);
    }

    public function signin(Request $request)
    {
        $return = $request->return ?? 'home';

        $returnLink = route('mobile.account');

        if($return == 'cart'){
            $returnLink = route('mobile.cart');
        }

        return page('signin', $returnLink, view('mobile/signin/index', compact('return') )->render());
    }

    public function signinRequest(Request $request)
    {
 
        if( is_numeric( $request->email ) ){
            
            $validator = Validator::make(request()->all(), [
                'email' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10'],
                'password' => ['required', 'string'],
            ],[
                'email.min' => __('The Mobile Number may not be less than 10 digits.'),
                'email.max' => __('The Mobile Number may not be greater than 10 digits.')
            ]);

            $validator->setAttributeNames([
                'email' => 'Mobile Number',
            ]);
 
        }else{

            $validator = Validator::make(request()->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ],[
                'email.email' => __('The Email / Mobile Number must be a valid email address or mobile number.'), 
            ]);

            $validator->setAttributeNames([
                'email' => 'Email / Mobile Number',
            ]);
        }

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        if( is_numeric( $request->email ) ){
            $credentials = [
                'mobile' => $request->email,
                'password' => $request->password,
                'mobile_verified' => 1,
            ];
        }else{
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'email_verified' => 1,
            ];
        }

        if (Auth::once($credentials)) {
            $authUser = Auth::user();

            if($authUser->status != 'active'){

                Auth::logout();

                return response()->json([
                    'errors' => [
                        'email' => [
                            __('Account is blocked or temporarily not available.')
                        ]
                    ]
                ]);

            }

            $random = Str::random(40);

            $token = $authUser->id . '|' . $random;

            $fcm = request()->header('Fcm') ?? null;
 
            $authUser->update([
                'api_token' => Hash::make($random),
                'fcm' => $fcm
            ]);

            Auth::logout();

            $return = $request->return ?? 'home';

            $redirect = route('mobile.home');
            if($return == 'cart'){
                $redirect = route('mobile.address', ['type' => 'select']);
            }

            return response()->json([
                'toast' => __('Sign in successfully.'),
                'authorization' => $token,
                'redirect' => $redirect,
                'fcm' => $fcm
            ]);
        }

        return response()->json([
            'errors' => [
                'email' => [
                    __('These credentials do not match our records.')
                ]
                ],
            'toast' => __('These credentials do not match our records.'),
        ]);

    }

    public function signout()
    {
        return response()->json([
            'toast' => __('Sign out successfully.'),
            'signout' => true,
            'redirect' => route('mobile.home')
        ]);
    }
}
