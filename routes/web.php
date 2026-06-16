<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SimpleBookOrderController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportProductController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PincodeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\VariantOptionController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\BannerSliderController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\DailyOfferController;
use App\Http\Controllers\HomeSpotlightController;

Route::get('signin', [SigninController::class, 'index'])->name('signin');
Route::post('signin', [SigninController::class, 'login']);
Route::get('signout', [SigninController::class, 'logout'])->name('signout');

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('signup', [SignupController::class, 'index'])->name('signup');
Route::post('signup', [SignupController::class, 'store'])->name('signup.store');
Route::post('signup/request', [SignupController::class, 'requestOtp'])->name('signup.request');
Route::post('signup/verify', [SignupController::class, 'verify'])->name('signup.verify');

Route::get('password-reset', [PasswordResetController::class, 'index'])->name('password.reset');
Route::post('password-reset', [PasswordResetController::class, 'reset']);
Route::post('password-reset/verify', [PasswordResetController::class, 'verify'])->name('password.reset.verify');    

Route::post('payment-response', [WebsiteController::class, 'paymentResponse'])->name('payment.response');


 
Route::get('/', function () { return redirect('/mobile'); })->name('home');
Route::get('index', function () { return redirect('/mobile'); });
Route::post('quick-order', [SimpleBookOrderController::class, 'store'])->middleware('auth')->name('quick-order.store');
Route::get('simple-checkout', [SimpleBookOrderController::class, 'checkout'])->middleware('auth')->name('simple-bookstore.checkout');
Route::post('simple-checkout', [SimpleBookOrderController::class, 'placeOrder'])->middleware('auth')->name('simple-bookstore.place-order');
Route::get('shop', [WebsiteController::class, 'products'])->name('website.products.shop');
Route::get('products/{slug?}', [WebsiteController::class, 'products'])->name('website.products');
Route::get('product/{slug}', [WebsiteController::class, 'product'])->name('website.product');
Route::post('product/{product}/review', [WebsiteController::class, 'storeProductReview'])->name('website.product.review.store')->middleware('auth');
Route::get('cart', [WebsiteController::class, 'cart'])->name('website.cart');
Route::post('cart/product/move-to/favourite/{product}', [WebsiteController::class, 'cartProductMoveToFavourite'])->name('website.cart.product.moveto.favourite');
Route::post('cart/product/remove/{product}', [WebsiteController::class, 'cartProductRemove'])->name('website.cart.product.remove');
Route::post('favourite/toggle/{product}', [WebsiteController::class, 'favouriteToggle'])->name('website.favourite.toggle');
Route::post('notify/{product}', [WebsiteController::class, 'notify'])->name('website.notify');

Route::get('pwa', [BaseController::class, 'home'])->name('pwa.home');
Route::get('mobile', [MobileController::class, 'pwa'])->name('mobile');
Route::get('offline', [BaseController::class, 'offline'])->name('offline');
Route::get('sw.js', [BaseController::class, 'swjs'])->name('swjs');
Route::get('firebase-messaging-sw.js', [BaseController::class, 'firebasejs'])->name('firebasejs');
Route::get('manifest.json', [BaseController::class, 'manifest'])->name('manifest');
Route::get('buy-now', [BaseController::class, 'share'])->name('share');
Route::get('verification/{code}', [BaseController::class, 'verification'])->name('verification');

Route::get('tc', [WebsiteController::class, 'tc'])->name('website.tc');
Route::get('about-us', [WebsiteController::class, 'aboutUs'])->name('website.about.us');
Route::get('privacy-policy', [WebsiteController::class, 'privacyPolicy'])->name('website.privacy.policy');
Route::get('safety-tips', [WebsiteController::class, 'safetyTips'])->name('website.safety.tips');
Route::get('import-updates', [WebsiteController::class, 'importUpdates'])->name('website.import.updates');
 
Route::get('sitemap.xml', [BaseController::class, 'sitemap'])->name('sitemap');


Route::middleware(['auth'])->group(function () {

    Route::get('account', [WebsiteController::class, 'account'])->name('website.account');
    Route::get('account/orders', [WebsiteController::class, 'order'])->name('website.order');
    Route::get('account/order/{order}', [WebsiteController::class, 'orderDetail'])->name('website.order.detail');
    Route::get('favourite', [WebsiteController::class, 'favourite'])->name('website.favourite');
    Route::post('favourite/remove/{product}', [WebsiteController::class, 'favouriteRemove'])->name('website.favourite.remove');
    Route::get('account/address/create', [WebsiteController::class, 'addressCreate'])->name('website.address.create');
    Route::post('account/address/save', [WebsiteController::class, 'addressSave'])->name('website.address.save');
    Route::get('account/address', [WebsiteController::class, 'address'])->name('website.address');
    Route::get('account/address/edit/{address}', [WebsiteController::class, 'addressEdit'])->name('website.address.edit');
    Route::post('account/address/update/{address}', [WebsiteController::class, 'addressUpdate'])->name('website.address.update');
    Route::post('account/address/destroy/{address}', [WebsiteController::class, 'addressDestroy'])->name('website.address.destroy');
    Route::post('account/address/default/{address}', [WebsiteController::class, 'addressDefault'])->name('website.address.default');
    Route::get('account/profile/edit', [WebsiteController::class, 'editProfile'])->name('website.edit.profile');
    Route::post('account/profile/update', [WebsiteController::class, 'updateProfile'])->name('website.update.profile');
    Route::post('account/profile/verify', [WebsiteController::class, 'verifyProfile'])->name('website.verify.profile');
    Route::get('account/change-password', [WebsiteController::class, 'changePassword'])->name('website.change.password');
    Route::post('account/change-password/request', [WebsiteController::class, 'changePasswordRequest'])->name('website.change.password.request');
    Route::get('checkout', function () { return redirect()->route('website.cart'); })->name('website.checkout');   
    
    Route::post('cart-calculation', [WebsiteController::class, 'cartCalculation'])->name('website.cart.calculation');   

    Route::post('place/order', [WebsiteController::class, 'placeOrder'])->name('website.place.order');
});



Route::middleware(['auth', 'admin'])->prefix('acp')->group(function () {

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('help/connect', [DashboardController::class, 'connect'])->name('help.connect');
    Route::post('subscribe-push-notification', [DashboardController::class, 'subscribePushNotification'])->name('subscribe.push.notification');
    Route::get('user/update-profile', [UserController::class, 'profile'])->name('user.update.profile');
    Route::post('user/update-profile', [UserController::class, 'updateProfile']);
    Route::get('user/change-password', [UserController::class, 'changePassword'])->name('user.change.password');    
    Route::post('user/change-password', [UserController::class, 'updatePassword']); 
});
 
Route::middleware(['auth', 'admin', 'permission'])->prefix('acp')->group(function () {
 
    Route::get('report/business-overview', [ReportController::class, 'businessOverview'])->name('report.business.overview');
    Route::get('report/location', [ReportController::class, 'location'])->name('report.location');
    Route::get('report/most-purchased-brands', [ReportController::class, 'mostPurchasedBrands'])->name('report.most.purchased.brands');
    Route::get('report/most-purchased-categories', [ReportController::class, 'mostPurchasedCategories'])->name('report.most.purchased.categories');
    Route::get('report/most-purchased-products', [ReportController::class, 'mostPurchasedProducts'])->name('report.most.purchased.products');
    Route::get('import/product', [ImportProductController::class, 'index'])->name('import.product');
    Route::get('import/product/insert/template', [ImportProductController::class, 'insertTemplate'])->name('import.product.insert.template');
    Route::post('import/product/create', [ImportProductController::class, 'store'])->name('import.product.store');

    Route::get('import/product/edit/template', [ImportProductController::class, 'editTemplate'])->name('import.product.edit.template');
    Route::get('import/product/edit', [ImportProductController::class, 'edit'])->name('import.product.edit');
    Route::post('import/product/edit', [ImportProductController::class, 'update'])->name('import.product.update');

    Route::get('manage-store', [OptionController::class, 'manageStore'])->name('manage.store');
    Route::get('tc', [OptionController::class, 'tc'])->name('tc');
    Route::get('about-us', [OptionController::class, 'aboutUs'])->name('about.us');
    Route::get('safety-tips', [OptionController::class, 'safetyTips'])->name('safety.tips');
    Route::get('import-updates', [OptionController::class, 'importUpdates'])->name('import.updates');
    Route::get('privacy-policy', [OptionController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('pwa-webview', [OptionController::class, 'pwaWebview'])->name('pwa.webview');

    Route::post('option/update', [OptionController::class, 'update'])->name('option.update');
 
    Route::get('role', [RoleController::class, 'index'])->name('role');
    Route::post('role', [RoleController::class, 'list'])->name('role.list');
    Route::post('role/create', [RoleController::class, 'store'])->name('role.store');
    Route::post('role/edit/{role}', [RoleController::class, 'edit'])->name('role.edit');
    Route::patch('role/edit/{role}', [RoleController::class, 'update'])->name('role.update');
    Route::post('role/delete/{role}', [RoleController::class, 'destroy'])->name('role.destroy');
 
    Route::get('permission', [PermissionController::class, 'index'])->name('permission');
    Route::post('permission', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::patch('permission', [PermissionController::class, 'update'])->name('permission.update');
 
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::post('user', [UserController::class, 'list'])->name('user.list');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store');
    Route::post('user/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('user/edit/{user}', [UserController::class, 'update'])->name('user.update');
    Route::post('user/delete/{user}', [UserController::class, 'destroy'])->name('user.destroy');
 
    Route::get('banner-slider', [BannerSliderController::class, 'index'])->name('banner.slider');
    Route::post('banner-slider', [BannerSliderController::class, 'list'])->name('banner.slider.list');
    Route::post('banner-slider/create', [BannerSliderController::class, 'store'])->name('banner.slider.store');
    Route::post('banner-slider/edit/{bannerSlider}', [BannerSliderController::class, 'edit'])->name('banner.slider.edit');
    Route::patch('banner-slider/edit/{bannerSlider}', [BannerSliderController::class, 'update'])->name('banner.slider.update');
    Route::post('banner-slider/delete/{bannerSlider}', [BannerSliderController::class, 'destroy'])->name('banner.slider.destroy');

    Route::get('poster', [PosterController::class, 'index'])->name('poster');
    Route::post('poster', [PosterController::class, 'list'])->name('poster.list');
    Route::post('poster/create', [PosterController::class, 'store'])->name('poster.store');
    Route::post('poster/edit/{poster}', [PosterController::class, 'edit'])->name('poster.edit');
    Route::patch('poster/edit/{poster}', [PosterController::class, 'update'])->name('poster.update');
    Route::post('poster/delete/{poster}', [PosterController::class, 'destroy'])->name('poster.destroy');

    Route::get('daily-offer', [DailyOfferController::class, 'index'])->name('daily.offer');
    Route::post('daily-offer', [DailyOfferController::class, 'list'])->name('daily.offer.list');
    Route::post('daily-offer/create', [DailyOfferController::class, 'store'])->name('daily.offer.store');
    Route::post('daily-offer/edit/{dailyOffer}', [DailyOfferController::class, 'edit'])->name('daily.offer.edit');
    Route::patch('daily-offer/edit/{dailyOffer}', [DailyOfferController::class, 'update'])->name('daily.offer.update');
    Route::post('daily-offer/delete/{dailyOffer}', [DailyOfferController::class, 'destroy'])->name('daily.offer.destroy');

    // Home page sponsored spotlight products
    Route::get('home-spotlight', [HomeSpotlightController::class, 'index'])->name('home.spotlight.index');
    Route::get('home-spotlight/create', [HomeSpotlightController::class, 'create'])->name('home.spotlight.create');
    Route::post('home-spotlight', [HomeSpotlightController::class, 'store'])->name('home.spotlight.store');
    Route::get('home-spotlight/{homeSpotlight}/edit', [HomeSpotlightController::class, 'edit'])->name('home.spotlight.edit');
    Route::put('home-spotlight/{homeSpotlight}', [HomeSpotlightController::class, 'update'])->name('home.spotlight.update');
    Route::delete('home-spotlight/{homeSpotlight}', [HomeSpotlightController::class, 'destroy'])->name('home.spotlight.destroy');

    Route::get('location', [LocationController::class, 'index'])->name('location');
    Route::post('location', [LocationController::class, 'list'])->name('location.list');
    Route::post('location/create', [LocationController::class, 'store'])->name('location.store');
    Route::post('location/edit/{location}', [LocationController::class, 'edit'])->name('location.edit');
    Route::patch('location/edit/{location}', [LocationController::class, 'update'])->name('location.update');
    Route::post('location/delete/{location}', [LocationController::class, 'destroy'])->name('location.destroy');

    Route::get('pincode', [PincodeController::class, 'index'])->name('pincode');
    Route::post('pincode', [PincodeController::class, 'list'])->name('pincode.list');
    Route::post('pincode/create', [PincodeController::class, 'store'])->name('pincode.store');
    Route::post('pincode/edit/{pincode}', [PincodeController::class, 'edit'])->name('pincode.edit');
    Route::patch('pincode/edit/{pincode}', [PincodeController::class, 'update'])->name('pincode.update');
    Route::post('pincode/delete/{pincode}', [PincodeController::class, 'destroy'])->name('pincode.destroy');

    Route::get('state', [StateController::class, 'index'])->name('state');
    Route::post('state', [StateController::class, 'list'])->name('state.list');
    Route::post('state/create', [StateController::class, 'store'])->name('state.store');
    Route::post('state/edit/{state}', [StateController::class, 'edit'])->name('state.edit');
    Route::patch('state/edit/{state}', [StateController::class, 'update'])->name('state.update');
    Route::post('state/delete/{state}', [StateController::class, 'destroy'])->name('state.destroy');

    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::post('category', [CategoryController::class, 'list'])->name('category.list');
    Route::post('category/create', [CategoryController::class, 'store'])->name('category.store');
    Route::post('category/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::patch('category/edit/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::post('category/delete/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('brand', [BrandController::class, 'index'])->name('brand');
    Route::post('brand', [BrandController::class, 'list'])->name('brand.list');
    Route::post('brand/create', [BrandController::class, 'store'])->name('brand.store');
    Route::post('brand/edit/{brand}', [BrandController::class, 'edit'])->name('brand.edit');
    Route::patch('brand/edit/{brand}', [BrandController::class, 'update'])->name('brand.update');
    Route::post('brand/delete/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');

    Route::get('unit', [UnitController::class, 'index'])->name('unit');
    Route::post('unit', [UnitController::class, 'list'])->name('unit.list');
    Route::post('unit/create', [UnitController::class, 'store'])->name('unit.store');
    Route::post('unit/edit/{unit}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::patch('unit/edit/{unit}', [UnitController::class, 'update'])->name('unit.update');
    Route::post('unit/delete/{unit}', [UnitController::class, 'destroy'])->name('unit.destroy');

    Route::get('tax', [TaxController::class, 'index'])->name('tax');
    Route::post('tax', [TaxController::class, 'list'])->name('tax.list');
    Route::post('tax/create', [TaxController::class, 'store'])->name('tax.store');
    Route::post('tax/edit/{tax}', [TaxController::class, 'edit'])->name('tax.edit');
    Route::patch('tax/edit/{tax}', [TaxController::class, 'update'])->name('tax.update');
    Route::post('tax/delete/{tax}', [TaxController::class, 'destroy'])->name('tax.destroy');

    Route::get('variant', [VariantController::class, 'index'])->name('variant');
    Route::post('variant', [VariantController::class, 'list'])->name('variant.list');
    Route::post('variant/create', [VariantController::class, 'store'])->name('variant.store');
    Route::post('variant/edit/{variant}', [VariantController::class, 'edit'])->name('variant.edit');
    Route::patch('variant/edit/{variant}', [VariantController::class, 'update'])->name('variant.update');
    Route::post('variant/delete/{variant}', [VariantController::class, 'destroy'])->name('variant.destroy');

    Route::get('variant-option', [VariantOptionController::class, 'index'])->name('variant.option');
    Route::post('variant-option', [VariantOptionController::class, 'list'])->name('variant.option.list');
    Route::post('variant-option/create', [VariantOptionController::class, 'store'])->name('variant.option.store');
    Route::post('variant-option/edit/{variantOption}', [VariantOptionController::class, 'edit'])->name('variant.option.edit');
    Route::patch('variant-option/edit/{variantOption}', [VariantOptionController::class, 'update'])->name('variant.option.update');
    Route::post('variant-option/delete/{variantOption}', [VariantOptionController::class, 'destroy'])->name('variant.option.destroy');
                    
    Route::get('attribute', [AttributeController::class, 'index'])->name('attribute');
    Route::post('attribute', [AttributeController::class, 'list'])->name('attribute.list');
    Route::post('attribute/create', [AttributeController::class, 'store'])->name('attribute.store');
    Route::post('attribute/edit/{attribute}', [AttributeController::class, 'edit'])->name('attribute.edit');
    Route::patch('attribute/edit/{attribute}', [AttributeController::class, 'update'])->name('attribute.update');
    Route::post('attribute/delete/{attribute}', [AttributeController::class, 'destroy'])->name('attribute.destroy');
    
    Route::get('product', [ProductController::class, 'index'])->name('product');
    Route::post('product', [ProductController::class, 'list'])->name('product.list');

    Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product/create', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/edit/{product}', [ProductController::class, 'edit'])->name('product.edit');
    Route::patch('product/edit/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::post('product/delete/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('product/copy/{product}', [ProductController::class, 'copy'])->name('product.copy');
    Route::post('product/attribute', [ProductController::class, 'attribute'])->name('product.attribute');
    Route::post('product/unit', [ProductController::class, 'unit'])->name('product.unit');

    Route::get('stock/{max?}/{title?}', [ProductController::class, 'stock'])->name('stock');
    Route::post('stock/{max?}', [ProductController::class, 'stocklist'])->name('product.stocklist');

    Route::get('order', [OrderController::class, 'index'])->name('order');
    Route::post('order', [OrderController::class, 'list'])->name('order.list');
    Route::get('order/view/{order}', [OrderController::class, 'view'])->name('order.view');
    Route::patch('order/view/{order}', [OrderController::class, 'update'])->name('order.update');
 
    Route::get('enquiry', [EnquiryController::class, 'index'])->name('enquiry');
    Route::post('enquiry', [EnquiryController::class, 'list'])->name('enquiry.list');
});
 
