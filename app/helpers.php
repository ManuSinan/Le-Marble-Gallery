<?php

use App\Models\Enquiry;
use App\Models\Option;
use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Combinations
{
    private $_elements = array();

    public function __construct($elements)
    {
        $this->setElements($elements);
    }

    public function setElements($elements){
        $this->_elements = array_values($elements);
    }

    public function getCombinations($length, $with_repetition = false){
        $combinations = array();

        foreach ($this->x_calculateCombinations($length, $with_repetition) as $value){
            $combinations[] = $value;
        }

        return $combinations;
    }

    public function getPermutations($length, $with_repetition = false){
        $permutations = array();

        foreach ($this->x_calculatePermutations($length, $with_repetition) as $value){
            $permutations[] = $value;
        }

        return $permutations;
    }

    private function x_calculateCombinations($length, $with_repetition = false, $position = 0, $elements = array()){

        $items_count = count($this->_elements);

        for ($i = $position; $i < $items_count; $i++){

            $elements[] = $this->_elements[$i];

            if (count($elements) == $length){
                yield $elements;
            }
            else{
                foreach ($this->x_calculateCombinations($length, $with_repetition, ($with_repetition == true ? $i : $i + 1), $elements) as $value2){
                    yield $value2;
                }
            }

            array_pop($elements);
        }
    }

    private function x_calculatePermutations($length, $with_repetition = false, $elements = array(), $keys = array()){

        foreach($this->_elements as $key => $value){

            if ($with_repetition == false){
                if (in_array($key, $keys)){
                    continue;
                }
            }

            $keys[] = $key;
            $elements[] = $value;

            if (count($elements) == $length){
                yield $elements;
            }
            else{
                foreach ($this->x_calculatePermutations($length, $with_repetition, $elements, $keys) as $value2){
                    yield $value2;
                }
            }

            array_pop($keys);
            array_pop($elements);
        }
    }

    public function generate(){
        $size  = 2;
        if(sizeof($this->_elements) >= 4){
            $size  = 4;
        }
        $combinations = $this->getPermutations($size, false);
        $out = '';
        foreach($combinations as $combination){
            $out .= implode(' ', $combination) . ' ';
        }

        return $out;
    }

}

if (!function_exists('priority')) {
    function priority() {
        return ['Low', 'Medium', 'High', 'Featured'];
    }
}

if (!function_exists('setOption')) {
    function setOption($key, $value = null) {
        try {
            $option = Option::where('key', $key)->first();

            if($value){
                if($option){
                    $option->update([
                        'value' => $value
                    ]);
                }else{
                    Option::create([
                        'key' => $key,
                        'value' => $value
                    ]);
                }

            }else{
                if($option){
                    $option->delete();
                }
            }

            Cache::forget('cache_option_' . $key, $value);

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('getOption')) {
    function getOption($key, $defaultValue = null) {

        $return = $defaultValue;
        if (Cache::has('cache_option_' . $key)) {
            $return = Cache::get('cache_option_' . $key);
        }else{
            $option = Option::where('key', $key)->first();
            if( $option ){
                $return = $option->value;
                Cache::forever('cache_option_' . $key, $return);
            }
        }

        return $return;
    }
}

if (!function_exists('currency')) {
    function currency() {
        $locale = App::getLocale();
        $rupee = '₹';

        if($locale != 'en'){
            $localCurrency = getOption('local_currency', '');

            return '<span class="currency"> ' . ($localCurrency !== '' ? $localCurrency : $rupee) . ' </span>';
        }

        $currency = getOption('currency', '');

        return ($currency !== '' ? $currency : $rupee) . ' ';
    }
}

if (!function_exists('decimalPlace')) {
    function decimalPlace() {
        return getOption('decimal_place', 2);
    }
}

if (!function_exists('priceFormat')) {
    function priceFormat($value, $currency = 'default', $separator = '') {

        if($currency == 'default'){
            $currency =  currency();
        }

        return  $currency .  number_format($value, decimalPlace(), '.', $separator);
    }
}

if (!function_exists('brand_image_url')) {
    /** Return best available brand image URL (large 560×400 if exists, else base). */
    function brand_image_url($brand) {
        if (empty($brand->image)) {
            return asset('assets/frontend/images/200x150-blank.png');
        }
        $largePath = str_replace('/base/', '/large/', $brand->image);
        if (Storage::disk('public')->exists($largePath)) {
            return asset('uploads/' . $largePath);
        }
        return asset('uploads/' . $brand->image);
    }
}

if (!function_exists('fillNine')) {
    function fillNine( $input = null) {
        $values = explode(',', $input);
        $before = intval($values[0] ?? 0);
        $after  = intval($values[1]  ?? 0);
        $beforeOut = str_pad('', ($before-$after) , '9');
        $afterOut = str_pad('', $after , '9');
        return $beforeOut . '.' . $afterOut;
    }
}

if (!function_exists('crudValidator')) {
    function crudValidator($column) {

        $rules = [];
        if($column['required']==true){
            $rules[] = 'required';
        }else{
            $rules[] = 'nullable';
        }

        if($column['type']=='integer'){
            $rules[]  = 'integer';
        }

        if($column['type']=='email'){
            $rules[]  = 'email';
        }

        if($column['type']=='decimal'){
            $rules[]  = 'regex:/^\d+(\.\d{1,3})?$/';
        }

        if($column['type']=='image'){
            $rules[]  = 'file';
            $rules[]  = 'image';
            $rules[]  = 'max: 10240';
        }

        if($column['type']=='mobile'){
            $rules[]  = 'regex:/^([0-9\s\-\+\(\)]*)$/';
        }

        if($column['length'] && $column['type']!='decimal'){
            $rules[]  = 'max:' . $column['length'];
        }

        if($column['type']=='decimal'){
            $rules[]  = 'lt:' . fillNine($column['length']);
        }

        return '\'' . implode('\', \'',  $rules) . '\'';
    }
}

function findBetween(string $string, string $start, string $end, bool $greedy = false) {
    $start = preg_quote($start, '/');
    $end   = preg_quote($end, '/');

    $format = '/(%s)(.*';
    if (!$greedy) $format .= '?';
    $format .= ')(%s)/';

    $pattern = sprintf($format, $start, $end);

    preg_match_all($pattern, $string, $matches);

    return $matches[2] ?? [];
}

if (!function_exists('page')) {
    function page($name, $backlink, $view, $json = []) {
        return response()->json( array_merge_recursive([
            'jquery' => [
                [
                    'element' => '#page',
                    'method' => 'attr',
                    'value' => ['page-name', $name],
                ],
                [
                    'element' => '#page',
                    'method' => 'attr',
                    'value' => ['act-request', $backlink],
                ],
                [
                    'element' => '#page',
                    'method' => 'html',
                    'value' => $view,
                ],
            ],
            'init' => ['#page'],
            'scrolltop' => true
        ], $json));
    }
}

if (!function_exists('getPermissionWithRoutePath')) {

    function getPermissionWithRoutePath($route, $permits, $pre_path)
    {

        if (is_array($permits) && count($permits) > 0) {

            foreach ($permits as $key => $value) {

                $temp_path = $pre_path;

                array_push($temp_path, $key);

                if (is_array($value) && count($value) > 0) {
                    $res_path = getPermissionWithRoutePath($route, $value, $temp_path);

                    if ($res_path != null) {
                        return $res_path;
                    }
                } else if ($value == $route) {
                    return $temp_path;
                }
            }
        }

        return null;
    }
}

if (!function_exists('ifJsonDecode')) {
    function ifJsonDecode($string){
        $isJson = is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        if( $isJson ){
            return json_decode($string);
        }
        return $string;
    }
}

if (!function_exists('getCart')) {
    function getCart() {
        $cart = null;

        if(request()->cart){
            $cart = request()->cart;
        }else{
            if(isset($_COOKIE['__cart'])){
                $cart = $_COOKIE['__cart'];
            }
        }

        return json_decode($cart, true);
    }
}

if (!function_exists('setCart')) {
    function setCart($cart) {
        $jsonEncodeCart = json_encode( $cart );
        $_COOKIE['__cart'] = $jsonEncodeCart;
        return setcookie('__cart',  $jsonEncodeCart, time()+31556926, '/', null, true, false);
    }
}

if (!function_exists('productExistsInCart')) {
    function productExistsInCart( $productId, $defaultValue = null) {

        $cart = getCart();

        if(!isset($cart['products'])){
            $cart = [
                'products' => []
            ];
        }

        return $cart['products'][$productId]['quantity'] ?? $defaultValue;
    }
}

if (!function_exists('productStatusInCart')) {
    function productStatusInCart( $productId) {
        $cart = getCart();

        if(!isset($cart['products'])){
            $cart = [
                'products' => []
            ];
        }
        return $cart['products'][$productId]['status'] ?? true;
    }
}

if (!function_exists('productMessageInCart')) {
    function productMessageInCart( $productId) {
        $cart = getCart();

        if(!isset($cart['products'])){
            $cart = [
                'products' => []
            ];
        }
        return $cart['products'][$productId]['message'] ?? '';
    }
}

if (!function_exists('productTotalSellingPriceInCart')) {
    function productTotalSellingPriceInCart( $productId, $defaultValue = null) {
        $cart = getCart();

        if(!isset($cart['products'])){
            $cart = [
                'products' => []
            ];
        }

        return $cart['products'][$productId]['total_selling_price'] ?? $defaultValue;
    }
}

if (!function_exists('productTotalPriceInCart')) {
    function productTotalPriceInCart( $productId, $defaultValue = null) {
        $cart = getCart();

        if(!isset($cart['products'])){
            $cart = [
                'products' => []
            ];
        }

        return $cart['products'][$productId]['total_price'] ?? $defaultValue;
    }
}

if (!function_exists('minimumQuantityPrice')) {
    function minimumQuantityPrice( $price, $quantity, $steper) {
        return $price * ( $quantity /  $steper );
    }
}

if (!function_exists('cartItemCount')) {
    function cartItemCount($defaultValue = 0) {
        $cart = getCart();
        $count = 0;
        if(isset($cart['products'])){
            $count = count($cart['products']);
        }

        if($count <= 0){
            return $defaultValue;
        }

        return $count;
    }
}

if (!function_exists('cartTotalAmount')) {
    function cartTotalAmount() {
        $cart = getCart();

        $total = 0;
        if(isset($cart['products'])){
             foreach( $cart['products']  as $id => $data){
                $productTotal = $data['total_selling_price'] ?? 0;
                $total = $total + $productTotal;
             }
        }

        return priceFormat($total, '');
    }
}

if (!function_exists('cartTotalSqft')) {
    function cartTotalSqft() {
        $cart = getCart();
        $total = 0;
        if(isset($cart['products'])){
             foreach( $cart['products']  as $id => $data){
                $quantity = $data['quantity'] ?? 0;
                $total = $total + $quantity;
             }
        }
        return number_format($total, 0);
    }
}

if (!function_exists('cartTotalUnitLabel')) {
    function cartTotalUnitLabel() {
        $cart = getCart();
        $units = [];
        if (isset($cart['products'])) {
            foreach ($cart['products'] as $id => $data) {
                $product = \App\Models\Product::with('unit')->find($id);
                if ($product && $product->unit) {
                    $units[$product->unit->name] = true;
                }
            }
        }
        
        if (count($units) === 1) {
            return __(key($units));
        } elseif (count($units) > 1) {
            return __('Items');
        }
        
        return __('Sq.Ft');
    }
}

if (!function_exists('cartUpdate')) {
    function cartUpdate($clear = false){

        $return = [];
        $cart = getCart();
        $cartProducts = [];

        if(isset($cart['products'])){
            $cartProducts = $cart['products'];
            $cart['products'] = [];
        }

        foreach($cartProducts as $cartProductId => $cartProduct){
            $product = Product::find($cartProductId);

            if($product && $product->status == 'published'){

                $message = $cartProduct['message'] ?? '';

                $quantity = $cartProduct['quantity'];

                if($product->price != $cartProduct['price']  || $product->selling_price != $cartProduct['selling_price']){
                    $message = 'Product price has been updated.';
                }

                if($product->stock_status == 'limited' && $product->stock_available < $quantity){
                    $quantity = $product->stock_available;
                    $message = 'Product purchase quantity updated based on stock.';
                }

                if($product->minimum_quantity > $quantity){
                    if($quantity == 0){
                        $message = 'The product has been updated.';
                    }else{
                        $message = 'Product minimum purchase quantity has been updated.';
                    }
                    $quantity = $product->minimum_quantity;
                }

                if($product->unit->stepper != $cartProduct['steper']){
                    $quantity = $product->minimum_quantity;
                    $message = 'The product has been updated.';
                }

                if($product->stock_status == 'limited' && ( $product->stock_available <= 0 || $product->stock_available < $product->minimum_quantity) ){
                    $quantity = 0;
                    $message = 'Out of stock.';
                }


                if($clear){
                    if( $quantity > 0){
                        $cart['products'][$cartProductId] = [
                            "price" => $product->price,
                            "selling_price" => $product->selling_price,
                            "quantity" => $quantity,
                            "steper" => $product->unit->stepper,
                            "total_price" =>  $product->price * ( $quantity / $product->unit->stepper ),
                            "total_selling_price" => $product->selling_price * ( $quantity / $product->unit->stepper ),
                            "message" => $message,
                        ];
                    }
                }else{
                    $cart['products'][$cartProductId] = [
                        "price" => $product->price,
                        "selling_price" => $product->selling_price,
                        "quantity" => $quantity,
                        "steper" => $product->unit->stepper,
                        "total_price" =>  $product->price * ( $quantity / $product->unit->stepper ),
                        "total_selling_price" => $product->selling_price * ( $quantity / $product->unit->stepper ),
                        "message" => $message,
                    ];
                }


            }
        }

        if(empty($cart['products'])){
            $cart['products'] = new \stdClass();
        }

        return $cart;
    }
}

if (!function_exists('_local')) {

    function _local($data, $local = '')
    {
        $locale = App::getLocale();

        if($locale != 'en'){

            if($local == ''){
                return $data;
            }

            return $local;
        }

        return $data;
    }
}

if (!function_exists('fileName')) {
    function fileName($extension, $suffix = '') {
        $code = strtolower(md5(uniqid(rand(),true)));
        return  uniqid(). '-' . substr($code,0,8) . '-' . substr($code,8,4) . '-' . substr($code,12,4). '-' . substr($code,16,4). '-' . substr($code,20) .  $suffix . '.' . $extension;
    }
}

if (!function_exists('unicode')) {
    function unicode() {
        $code = strtolower(md5(uniqid(rand(),true)));
        return  uniqid(). '-' . substr($code,0,13) . '-' . substr($code,8,13) . '-' . substr($code,12,13). '-' . substr($code,16,13). '-' . substr($code,13);
    }
}

if (!function_exists('authUser')) {

    function authUser($type = 'web')
    {
        if($type == 'web'){

            $user = Auth::user();
            if ($user && (request()->is('mobile*') || request()->routeIs('mobile.*'))) {
                if (!$user->role || ($user->role->id != 4 && strtolower($user->role->name) !== 'salesman')) {
                    return null;
                }
            }
            return $user;

        }else if( $type == 'api' ){
            $token = request()->header('Authorization');

            if($token){
                $explode = explode('|', $token, 2);

                if( is_array($explode) ){
                    $bearer = $explode[0] ?? null;
                    $token = $explode[1] ?? null;

                    if($bearer != null && $token != null && $token != '' && $token != ' '){
                        $id = str_replace('Bearer ','', $bearer);

                        if($id){
                            $user = User::find($id);

                            if($user){
                               if($user->status == 'active'){
                                    if(Hash::check($token, $user->api_token)){
                                        if ($user->role && ($user->role->id == 4 || strtolower($user->role->name) === 'salesman')) {
                                            return $user;
                                        }
                                    }
                               }
                            }
                        }
                    }
                }
            }
        }else{

            return null;
        }

        return null;
    }
}

if (!function_exists('permissions')) {

    function permissions()
    {
        return Config::get('permissions', []);
    }
}

if (!function_exists('hasPermission')) {

    function hasPermission($route)
    {

        if (config('app.debug', false) && !config('app.permission', true)) {
            return 1;
        }

        $authUser = authUser();

        if (!$authUser || !$authUser->role) {
            return 0;
        }

        if ($authUser->role->type != 'private') {
            return 0;
        }

        if ($authUser->role->type == 'private' && $authUser->role->created_by == 'system') {
            return 1;
        }

        $role = $authUser->role;

        if (!$role) {
            return 0;
        }

        $role_id = $role->id;

        $permissionKeys = [];

        if (Cache::has('permissions')) {

            $permissionKeys = Cache::get('permissions');

        } else {

            $permissions = Permission::all();

            $permissionKeys = [];

            foreach ($permissions as $permission) {
                $permissionKeys[] = $permission->role_id . '|' . $permission->permission;
            }

            Cache::forever('permissions', $permissionKeys);
        }

        $routePath = getPermissionWithRoutePath($route, permissions(), []);

        if (is_array($routePath)) {
            if (count($routePath) == 3) {
                if (in_array($role_id . '|' . $routePath[0] . '-' . $routePath[1], $permissionKeys)) {
                    return 1;
                }
            }
        }

        return 0;
    }
}

if (!function_exists('subscribePushNotification')) {

    function subscribePushNotification($token, $topics = '/order')
    {

        $key = config('app.firebase.server_key', false);

        $title = config('app.name', false);

        $message = 'New test message';

        $headers = array(
            'Authorization:key=' . $key,
            'Content-Type:application/json'
        );

        $fields = array(
            'to' => '/topics' . $topics,
            "registration_tokens" => [$token]
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://iid.googleapis.com/iid/v1:batchAdd');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

if (!function_exists('sendPushNotificationWithTopic')) {

    function sendPushNotificationWithTopic($message, $topics = '/order')
    {

        $key = config('app.firebase.server_key', false);

        $title = config('app.name', false);

        $headers = array(
            'Authorization:key=' . $key,
            'Content-Type:application/json'
        );

        $fields = array(
            'to' => '/topics' . $topics,
            'notification' => ['title' => $title, 'body' => $message, 'sound' => 'default'],
            'data' => ['title' => $title, 'body' => $message, 'style' => 'inbox'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

if (!function_exists('sendPushNotification')) {

    function sendPushNotification($message, $token)
    {
        $key = config('app.firebase.server_key', false);

        $title = config('app.name', false);


        $headers = array(
            'Authorization:key=' . $key,
            'Content-Type:application/json'
        );

        $fields = array(
            'to' => $token,
            'priority' => 'high',
            'notification' => ['title' => $title, 'body' => $message, 'sound' => 'default'],
            'data' => ['title' => $title, 'body' => $message, 'style' => 'inbox', 'toast' => true],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

if (!function_exists('webhookEvents')) {

    function webhookEvents($event, $value)
    {
        // `app.webhook` is configured as an array (url, api_key, ...).
        // Older code treated it as a string, which can cause "Array to string conversion".
        $url = config('app.webhook.url', config('app.webhook', false));

        if (is_string($url) && $url !== '') {
            $headers = [
                'Authorization: Bearer ' . config('app.webhook.api_key', ''),
                'Content-Type: application/json',
                'Accept: application/json',
            ];

            $fields = [
                'event' => $event,
                'value' => $value,
            ];
            // if (!empty($payload)) {
            //     $fields = array_merge($fields, $payload);
            // }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }
    }
}

if (!function_exists('notify')) {

    function notify($productId)
    {
        $enquiries = Enquiry::where('product_id', $productId)->where('status', 'enquired')->get();
        foreach($enquiries as $enquiry){
            $status = false;
            if($enquiry->product->status == 'published'){

                if($enquiry->product->stock_status == 'limited'){
                    if($enquiry->product->stock_available > 0 ){
                        $status = true;
                    }
                }

                if($enquiry->product->stock_status == 'unlimited'){
                    $status = true;
                }
            }

            if($status){
                if($enquiry->user->fcm){
                    sendPushNotification('Hi ' . $enquiry->user->name . ', ' . $enquiry->product->name . 'is available now.'  , $enquiry->user->fcm);
                }

                // sendSms($enquiry->user->mobile, 'Hi, ' . $enquiry->user->name . ', Item ' . $enquiry->product->name . ' Currently Available in Stock @ laptopspareworld.com', '1307164095250242076');

                $enquiry->update([
                    'status' => 'notified'
                ]);
            }
        };
    }
}

if (!function_exists('sendSms')) {

    function sendSms($destination, $message, $templateId){

        $url= 'http://thesmsbuddy.com/api/v1/sms/send';


        $data['key'] = 'hnfdXdus9NerdjMfuFqCgB7ucJt3Zh7n';
        $data['type'] = 1;
        $data['to'] = $destination;
        $data['sender'] = 'LAPSPR';
        $data['message'] = $message;
        $data['flash'] = 0;
        $data['template_id'] = $templateId;

        $params = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url."?".$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $result;
        }
    }
}

if (!function_exists('sendWhatsAppOtp')) {

    /**
     * Send OTP via Meta WhatsApp Cloud API using template (e.g. peak_otp).
     * Requires WHATSAPP_GRAPH_URL, WHATSAPP_PHONE_NUMBER_ID, WHATSAPP_API_KEY in .env.
     * Endpoint: {graph_url}/{phone_number_id}/messages
     *
     * @param string $mobile 10-digit mobile (no country code)
     * @param string $otp 6-digit OTP
     * @return mixed response body or false on failure
     */
    function sendWhatsAppOtp($mobile, $otp)
    {
        \Illuminate\Support\Facades\Log::info('WhatsApp OTP: Function called', [
            'mobile' => $mobile,
            'otp' => $otp,
        ]);
        
        $graphUrl = config('whatsapp.graph_url');
        $phoneNumberId = config('whatsapp.phone_number_id');
        $key = config('whatsapp.api_key');
        $countryCode = config('whatsapp.country_code', '91');
        $templateName = config('whatsapp.otp_template_name', 'peak_otp');
        $useWhatsApp = config('whatsapp.use_whatsapp_otp');

        \Illuminate\Support\Facades\Log::info('WhatsApp OTP: Config values', [
            'use_whatsapp_otp' => $useWhatsApp,
            'has_graph_url' => !empty($graphUrl),
            'has_phone_number_id' => !empty($phoneNumberId),
            'has_api_key' => !empty($key),
            'phone_number_id' => $phoneNumberId ? substr($phoneNumberId, 0, 10) . '...' : null,
            'api_key_length' => $key ? strlen($key) : 0,
            'country_code' => $countryCode,
            'template_name' => $templateName,
        ]);

        if (empty($graphUrl) || empty($phoneNumberId) || empty($key)) {
            \Illuminate\Support\Facades\Log::warning('WhatsApp OTP: Missing config', [
                'has_graph_url' => !empty($graphUrl),
                'has_phone_number_id' => !empty($phoneNumberId),
                'has_api_key' => !empty($key),
            ]);
            return false;
        }

        $to = $countryCode . preg_replace('/\D/', '', $mobile);
        $url = $graphUrl . '/' . $phoneNumberId . '/messages';
        
        \Illuminate\Support\Facades\Log::info('WhatsApp OTP: Request details', [
            'to' => $to,
            'url' => $url,
            'template' => $templateName,
        ]);

        // Build template components - body is required, button is optional
        $components = [
            [
                'type'       => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => (string) $otp],
                ],
            ],
        ];
        
        // Add button component if template requires it (for peak_otp template)
        // Remove this if your template doesn't have a button
        $components[] = [
            'type'      => 'button',
            'sub_type'  => 'url',
            'index'     => '0',
            'parameters' => [
                ['type' => 'text', 'text' => (string) $otp],
            ],
        ];
        
        $body = [
            'messaging_product' => 'whatsapp',
            'to'                => $to,
            'type'              => 'template',
            'template'          => [
                'name'       => $templateName,
                'language'   => ['code' => 'en'],
                'components' => $components,
            ],
        ];

        try {
            \Illuminate\Support\Facades\Log::info('WhatsApp OTP: Sending request', [
                'url' => $url,
                'to' => $to,
                'template' => $templateName,
            ]);
            
            $response = \Illuminate\Support\Facades\Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $key,
                    'Content-Type'  => 'application/json',
                ])
                ->post($url, $body);

            $responseData = $response->json();
            
            \Illuminate\Support\Facades\Log::info('WhatsApp OTP: Response received', [
                'status' => $response->status(),
                'has_error' => isset($responseData['error']),
                'has_messages' => isset($responseData['messages']),
            ]);
            
            // Check for API errors in response body (even if HTTP status is 200)
            if (isset($responseData['error'])) {
                \Illuminate\Support\Facades\Log::warning('WhatsApp OTP: API error in response', [
                    'status' => $response->status(),
                    'error' => $responseData['error'],
                    'to' => $to,
                    'phone_number_id' => $phoneNumberId,
                ]);
                return false;
            }
            
            // Check HTTP status code
            if (!$response->successful()) {
                \Illuminate\Support\Facades\Log::warning('WhatsApp OTP: HTTP error', [
                    'status' => $response->status(),
                    'body' => $responseData,
                    'to' => $to,
                ]);
                return false;
            }
            
            // Success - check if we have messages array (successful send)
            if (isset($responseData['messages']) && !empty($responseData['messages'])) {
                \Illuminate\Support\Facades\Log::info('WhatsApp OTP: Sent successfully', [
                    'to' => $to,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                ]);
                return $responseData;
            }
            
            // Unexpected response format
            \Illuminate\Support\Facades\Log::warning('WhatsApp OTP: Unexpected response format', [
                'response' => $responseData,
                'to' => $to,
            ]);
            return false;
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WhatsApp OTP: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'to' => $to,
            ]);
            return false;
        }
    }
}

function renderCategoryOptions($categories, $selectedCategoryId = null, $prefix = '') {
    foreach ($categories as $category) {
        $selected = $category->id == $selectedCategoryId ? 'selected' : '';
        echo "<option value=\"{$category->id}\" {$selected}>{$prefix}{$category->name}</option>";
        if ($category->children->isNotEmpty()) {
            renderCategoryOptions($category->children, $selectedCategoryId, $prefix . '&nbsp;&nbsp;&nbsp;');
        }
    }
}
