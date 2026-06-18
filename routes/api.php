<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\ConnectController;
use App\Http\Controllers\WebhookController;

Route::post('webhook', [WebhookController::class, 'handle'])->name('api.webhook');

Route::get('mobile/{any}', function () {
    return redirect()->route('mobile');
})->where('any', '.*');

Route::middleware(['localize'])->prefix('mobile')->group(function () {

    Route::post('home', [MobileController::class, 'home'])->name('mobile.home');
 
    Route::post('products', [MobileController::class, 'products'])->name('mobile.products');
    Route::post('search-suggestions', [MobileController::class, 'searchSuggestions'])->name('mobile.search.suggestions');
    Route::post('product/{product}', [MobileController::class, 'product'])->name('mobile.product');
    Route::post('product-zoom/{product}', [MobileController::class, 'productZoom'])->name('mobile.product.zoom');


    Route::post('signup', [MobileController::class, 'signup'])->name('mobile.signup');
    Route::post('signup/request', [MobileController::class, 'signupRequest'])->name('mobile.signup.request');
    Route::post('signup/verify', [MobileController::class, 'signupVerify'])->name('mobile.signup.verify');



    Route::post('signin', [MobileController::class, 'signin'])->name('mobile.signin');
    Route::post('signin/request', [MobileController::class, 'signinRequest'])->name('mobile.signin.request');
    
    
    Route::post('signout', [MobileController::class, 'signout'])->name('mobile.signout');
    
    Route::post('password-reset', [MobileController::class, 'passwordReset'])->name('mobile.password.reset');
    Route::post('password-reset/request', [MobileController::class, 'passwordResetRequest'])->name('mobile.password.reset.request');
    Route::post('password-reset/verify', [MobileController::class, 'passwordResetVerify'])->name('mobile.password.reset.verify');
 
    Route::post('account', [MobileController::class, 'account'])->name('mobile.account');
    Route::post('change-password', [MobileController::class, 'changePassword'])->name('mobile.change.password');
 
    Route::post('switch-language', [MobileController::class, 'switchLanguage'])->name('mobile.switch.language');
 
    Route::post('favourite', [MobileController::class, 'favourite'])->name('mobile.favourite');
    Route::post('favourite/add/{product}', [MobileController::class, 'favouriteAdd'])->name('mobile.favourite.add');
 
    Route::post('notify/{product}', [MobileController::class, 'notify'])->name('mobile.notify');




    Route::post('cart', [MobileController::class, 'cart'])->name('mobile.cart');
    Route::post('cart/product/move-to/favourite/{product}', [MobileController::class, 'cartProductMoveToFavourite'])->name('mobile.cart.product.moveto.favourite');
    Route::post('cart/product/remove/{product}', [MobileController::class, 'cartProductRemove'])->name('mobile.cart.product.remove');

    Route::post('orders', [MobileController::class, 'orders'])->name('mobile.orders');
    Route::post('order-detail/{order}', [MobileController::class, 'orderDetail'])->name('mobile.order.detail');
});


Route::middleware(['auth:api', 'localize'])->prefix('mobile')->group(function () {

    Route::post('edit-profile', [MobileController::class, 'editProfile'])->name('mobile.edit.profile');
    Route::post('update-profile', [MobileController::class, 'updateProfile'])->name('mobile.update.profile');

    Route::post('verify-profile', [MobileController::class, 'verifyProfile'])->name('mobile.verify.profile');



    Route::post('address', [MobileController::class, 'address'])->name('mobile.address');
    Route::post('address/create', [MobileController::class, 'addressCreate'])->name('mobile.address.create');
    Route::post('address/save', [MobileController::class, 'addressSave'])->name('mobile.address.save');
    Route::post('address/edit/{address}', [MobileController::class, 'addressEdit'])->name('mobile.address.edit');
    Route::post('address/update/{address}', [MobileController::class, 'addressUpdate'])->name('mobile.address.update');
    Route::post('address/destroy/{address}', [MobileController::class, 'addressDestroy'])->name('mobile.address.destroy');
    Route::post('address/default/{address}', [MobileController::class, 'addressDefault'])->name('mobile.address.default');

    Route::post('favourite/remove/{product}', [MobileController::class, 'favouriteRemove'])->name('mobile.favourite.remove');
 
    Route::post('order/summary', [MobileController::class, 'orderSummary'])->name('mobile.order.summary');
    Route::post('place/order', [MobileController::class, 'placeOrder'])->name('mobile.place.order');
    Route::post('order/success', [MobileController::class, 'orderSuccess'])->name('mobile.order.success');

    Route::post('change-password/request', [MobileController::class, 'changePasswordRequest'])->name('mobile.change.password.request');
});


Route::middleware(['connect'])->prefix('connect/v1')->group(function () {
    
    Route::get('product/{code}', [ConnectController::class, 'product'])->name('connect.product');

    Route::get('order/{id}', [ConnectController::class, 'order'])->name('connect.order');

    Route::get('orders/{id}', [ConnectController::class, 'orders'])->name('connect.orders');

    Route::post('product/{code}', [ConnectController::class, 'productUpdate'])->name('connect.update.product');

    Route::post('products', [ConnectController::class, 'productsUpdate'])->name('connect.update.products');

    Route::post('order/{id}', [ConnectController::class, 'orderUpdate'])->name('connect.update.order');
});