@extends('frontend/layout/main')
@section('seo')
<title>{{ __('Cart') }}</title>
@endsection
@section('style')
<style>
/* ========== Cart page – Luxury theme (matches site colors & card heights) ========== */
.cart-page-wrapper {
    background: transparent !important;
    margin: 0;
    padding: 0;
}

.section-content.cart-page {
    background: transparent !important;
    padding-top: 8px !important;
    padding-bottom: 40px !important;
}

.cart-breadcrumb {
    margin-bottom: 20px !important;
    font-size: 13px !important;
    padding: 0 !important;
    margin-top: 0 !important;
}
.breadcrumb-link { color: #c4b8a8 !important; text-decoration: none !important; font-weight: 500 !important; transition: color 0.2s !important; }
.breadcrumb-link:hover { color: #b8956b !important; text-decoration: none !important; }
.breadcrumb-separator { color: rgba(242,232,216,0.4) !important; margin: 0 6px !important; }
.breadcrumb-current { color: #f2e8d8 !important; font-weight: 500 !important; }

.cart-page-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.5rem;
    font-weight: 500;
    color: #f2e8d8;
    margin: 0 0 24px;
}

/* Delivery card – luxury colors, same height as other cards */
.section-content.cart-page .checkout-delivery-card {
    background: #231c14 !important;
    border: 1px solid rgba(242,232,216,0.1) !important;
    border-radius: 12px !important;
    box-shadow: none !important;
    margin-bottom: 24px !important;
    overflow: hidden !important;
    min-height: 120px !important;
}
.checkout-delivery-card .card-body {
    border: none !important;
    padding: 20px 24px !important;
}
.checkout-delivery-summary .badge {
    font-size: 10px !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    padding: 4px 8px !important;
    border-radius: 6px !important;
    background: rgba(184,149,107,0.2) !important;
    color: #b8956b !important;
    border: none !important;
}
.checkout-delivery-change-btn {
    font-weight: 600 !important;
    font-size: 14px !important;
    color: #b8956b !important;
}
.checkout-delivery-change-btn:hover {
    color: #d4b896 !important;
}
.checkout-delivery-card .text-dark { color: #f2e8d8 !important; }
.checkout-delivery-card .text-muted { color: #c4b8a8 !important; }
.checkout-delivery-card .box {
    padding: 14px 16px !important;
    border-radius: 10px !important;
    border: 1px solid rgba(242,232,216,0.15) !important;
    background: rgba(0,0,0,0.2) !important;
    transition: border-color 0.2s, box-shadow 0.2s !important;
    min-height: 100px !important;
}
.checkout-delivery-card .box:hover {
    border-color: #b8956b !important;
    box-shadow: 0 0 0 1px rgba(184,149,107,0.2) !important;
}
.checkout-delivery-card .custom-control-label { color: #f2e8d8 !important; }
.checkout-delivery-card .btn-outline-secondary {
    background: transparent !important;
    border-color: rgba(242,232,216,0.3) !important;
    color: #f2e8d8 !important;
}
.checkout-delivery-card .btn-outline-secondary:hover {
    border-color: #b8956b !important;
    color: #b8956b !important;
}
.checkout-delivery-card a[href*="address"].btn {
    color: #b8956b !important;
    border-color: #b8956b !important;
}
.checkout-delivery-card a[href*="address"].btn:hover {
    color: #d4b896 !important;
    border-color: #d4b896 !important;
    background: rgba(184,149,107,0.1) !important;
}

/* Cart items container – luxury colors */
.section-content.cart-page .card.mb-4:not(.checkout-delivery-card) {
    background: #231c14 !important;
    border: 1px solid rgba(242,232,216,0.1) !important;
    border-radius: 4px !important;
    box-shadow: none !important;
    overflow: hidden !important;
}
.section-content.cart-page .card.mb-4:not(.checkout-delivery-card) .card-body {
    padding: 0 !important;
}

/* Cart item – luxury colors, consistent height with product cards */
.section-content.cart-page .cart-item {
    background: transparent !important;
    border: none !important;
    border-bottom: 1px solid rgba(242,232,216,0.08) !important;
    border-radius: 0 !important;
    padding: 20px 24px !important;
    min-height: 150px !important;
    display: flex !important;
    align-items: center !important;
    transition: background 0.2s ease !important;
}
.section-content.cart-page .cart-item:last-child {
    border-bottom: none !important;
}
.section-content.cart-page .cart-item:hover {
    background: rgba(0,0,0,0.15) !important;
}
.section-content.cart-page .cart-item .item-img {
    display: block !important;
    width: 110px !important;
    height: 110px !important;
    border-radius: 2px !important;
    overflow: hidden !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important;
    flex-shrink: 0 !important;
    background: rgba(0,0,0,0.2) !important;
    padding: 0 !important;
}
.section-content.cart-page .cart-item .item-img .image {
    display: block !important;
    width: 100% !important;
    height: 100% !important;
    border-radius: 10px !important;
    object-fit: cover !important;
    object-position: center !important;
}
.section-content.cart-page .cart-item .title {
    font-weight: 600 !important;
    font-size: 15px !important;
    color: #f2e8d8 !important;
    line-height: 1.4 !important;
}
.section-content.cart-page .cart-item .title:hover {
    color: #b8956b !important;
}
.section-content.cart-page .cart-item-meta {
    font-size: 13px !important;
    color: #c4b8a8 !important;
    margin-top: 6px !important;
}
.section-content.cart-page .cart-item-brand {
    font-weight: 600 !important;
    color: #c4b8a8 !important;
}
.section-content.cart-page .cart-item-delivery {
    display: block !important;
    margin-top: 4px !important;
    color: #b8956b !important;
    font-size: 12px !important;
}
.section-content.cart-page .cart-item .price {
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #f2e8d8 !important;
}
.section-content.cart-page .cart-item .price .selling {
    color: #f2e8d8 !important;
}
.section-content.cart-page .cart-item .price del {
    color: #c4b8a8 !important;
    font-weight: 500 !important;
    font-size: 14px !important;
}
/* Cart item actions – Move to Favourite & Remove */
.section-content.cart-page .cart-item-actions {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 10px !important;
    margin-top: 12px !important;
}
.section-content.cart-page .cart-item-action-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 8px 14px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    letter-spacing: 0.03em !important;
    text-transform: uppercase !important;
    text-decoration: none !important;
    border-radius: 8px !important;
    border: 1px solid rgba(242,232,216,0.3) !important;
    background: rgba(0,0,0,0.2) !important;
    color: #c4b8a8 !important;
    transition: all 0.2s ease !important;
}
.section-content.cart-page .cart-item-action-btn .icon {
    flex-shrink: 0 !important;
    opacity: 0.9 !important;
}
.section-content.cart-page .cart-item-action-favourite:hover {
    border-color: rgba(184,149,107,0.6) !important;
    background: rgba(184,149,107,0.15) !important;
    color: #b8956b !important;
}
.section-content.cart-page .cart-item-action-remove:hover {
    border-color: #b8956b !important;
    background: rgba(184,149,107,0.12) !important;
    color: #b8956b !important;
}
.section-content.cart-page .steper-btn {
    border: 1px solid rgba(242,232,216,0.25) !important;
    border-radius: 8px !important;
    background: rgba(0,0,0,0.2) !important;
    padding: 0 !important;
    box-shadow: none !important;
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: center !important;
    overflow: hidden !important;
}
.section-content.cart-page .steper-btn.empty {
    border-style: dashed !important;
    background: rgba(0,0,0,0.15) !important;
}
.section-content.cart-page .steper-btn .steper-btn-text {
    font-weight: 600 !important;
    color: #c4b8a8 !important;
}
.section-content.cart-page .steper-btn-minus,
.section-content.cart-page .steper-btn-plus {
    color: #b8956b !important;
    font-weight: 700 !important;
    flex-shrink: 0 !important;
    min-width: 36px !important;
    height: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: none !important;
    background: transparent !important;
    cursor: pointer !important;
}
.section-content.cart-page .steper-btn .steper-btn-value {
    color: #f2e8d8 !important;
    font-weight: 600 !important;
    min-width: 32px !important;
    text-align: center !important;
    padding: 0 4px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Summary card – luxury colors, same height as sidebar cards */
.section-content.cart-page .payment-summary-card,
.payment-summary-card {
    background: #231c14 !important;
    border-radius: 4px !important;
    border: 1px solid rgba(242,232,216,0.1) !important;
    box-shadow: none !important;
    padding: 24px !important;
    margin-bottom: 20px !important;
    min-height: 280px !important;
}
.cart-sidebar-title {
    margin-top: 0 !important;
    margin-bottom: 24px !important;
    font-size: 17px !important;
    font-weight: 700 !important;
    color: #f2e8d8 !important;
    letter-spacing: 0.3px !important;
    padding-bottom: 16px !important;
    border-bottom: 1px solid rgba(242,232,216,0.12) !important;
}
.payment-summary-content {
    display: flex !important;
    flex-direction: column !important;
    gap: 18px !important;
}
.payment-amount-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    margin-bottom: 14px !important;
}
.payment-amount-row:last-of-type {
    margin-bottom: 0 !important;
}
.payment-label-group {
    display: flex !important;
    flex-direction: column !important;
    gap: 2px !important;
}
.payment-label {
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #c4b8a8 !important;
}
.payment-value {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #f2e8d8 !important;
    text-align: right !important;
}
.payment-value-final {
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #f2e8d8 !important;
}
.payment-tax-note {
    font-size: 12px !important;
    color: #c4b8a8 !important;
    font-weight: 400 !important;
}
.payment-buttons {
    display: flex !important;
    flex-direction: column !important;
    gap: 12px !important;
    margin-top: 20px !important;
    padding-top: 20px !important;
    border-top: 1px solid rgba(242,232,216,0.12) !important;
}
.btn-checkout {
    width: 100% !important;
    padding: 16px 24px !important;
    background: #b8956b !important;
    color: #1a140d !important;
    border: 1px solid #b8956b !important;
    border-radius: 10px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    letter-spacing: 0.12em !important;
    text-transform: uppercase !important;
    text-align: center !important;
    cursor: pointer !important;
    transition: background 0.2s, border-color 0.2s, transform 0.15s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
}
.btn-checkout:hover:not(:disabled) {
    background: #d4b896 !important;
    border-color: #d4b896 !important;
    color: #1a140d !important;
    text-decoration: none !important;
    transform: translateY(-1px) !important;
}
.btn-checkout:disabled {
    background: rgba(242,232,216,0.1) !important;
    color: #c4b8a8 !important;
    border-color: rgba(242,232,216,0.15) !important;
    cursor: not-allowed !important;
}
.btn-continue-shopping {
    width: 100% !important;
    padding: 14px 24px !important;
    background: transparent !important;
    color: #b8956b !important;
    border: 1px solid rgba(184,149,107,0.5) !important;
    border-radius: 10px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    letter-spacing: 0.06em !important;
    text-align: center !important;
    cursor: pointer !important;
    transition: background 0.2s, color 0.2s, border-color 0.2s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
}
.btn-continue-shopping:hover {
    background: rgba(184,149,107,0.15) !important;
    color: #d4b896 !important;
    text-decoration: none !important;
    border-color: #b8956b !important;
}

/* Empty cart & item message – luxury colors */
.section-content.cart-page .empty-content .title,
.section-content.cart-page .empty-content p { color: #f2e8d8 !important; }
.section-content.cart-page .empty-content .btn-light {
    background: rgba(0,0,0,0.2) !important;
    border: 1px solid rgba(242,232,216,0.2) !important;
    color: #f2e8d8 !important;
}
.section-content.cart-page .empty-content .btn-light:hover {
    background: rgba(184,149,107,0.2) !important;
    border-color: #b8956b !important;
    color: #b8956b !important;
}
.section-content.cart-page .item-message {
    background: rgba(184,149,107,0.15) !important;
    color: #d4b896 !important;
    border-color: rgba(184,149,107,0.3) !important;
}
.section-content.cart-page .minimum-order-value-message { color: #c9a878 !important; }
.section-content.cart-page .cart-item .details { color: #c9a878 !important; }
.section-content.cart-page .notify-btn {
    padding: 10px 20px !important;
    background: rgba(0,0,0,0.2) !important;
    border: 1px solid rgba(242,232,216,0.25) !important;
    color: #f2e8d8 !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    letter-spacing: 0.08em !important;
    cursor: pointer !important;
    transition: border-color 0.2s, color 0.2s, background 0.2s !important;
}
.section-content.cart-page .notify-btn:hover,
.section-content.cart-page .notify-btn.active {
    border-color: #b8956b !important;
    color: #b8956b !important;
    background: rgba(184,149,107,0.1) !important;
}

.section-page-info { margin-top: 1rem !important; margin-bottom: 1rem !important; }

/* Empty cart card – luxury colors, same height */
.section-content.cart-page .empty-cart-card {
    background: #231c14 !important;
    border: 1px solid rgba(242,232,216,0.1) !important;
    border-radius: 12px !important;
    min-height: 200px !important;
}
.section-content.cart-page .empty-cart-card .empty-content {
    min-height: 200px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Responsive */
@media (max-width: 991px) {
    .payment-summary-card { margin-top: 24px !important; }
}
@media (max-width: 576px) {
    .section-content.cart-page .cart-item { padding: 16px !important; min-height: auto !important; }
    .payment-summary-card { padding: 20px 18px !important; min-height: auto !important; }
    .payment-value-final { font-size: 16px !important; }
    .btn-checkout, .btn-continue-shopping { padding: 14px 20px !important; font-size: 14px !important; }
}
</style>
@endsection

@section('body')
<div class="cart-page-wrapper">
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <nav class="cart-breadcrumb"> 
            <a href="{{ route('home') }}" class="breadcrumb-link">{{ __('Home') }}</a>
            <span class="breadcrumb-separator"> / </span>
            <span class="breadcrumb-current">{{ __('Cart') }}</span>
        </nav>
   </div>
</section>

<section class="section-content cart-page my-4">
	<div class="container">
        <h1 class="cart-page-title">{{ __('Your Cart') }}</h1>

        @if($products->count() > 0)

        @if($authUser)
        <form act-on="submit" act-request="{{ route('website.place.order') }}" id="cart-place-order-form">
        @endif

		<div class="row">

			<div class="col-lg-9">

                @if($authUser)
                <div class="card mb-4 checkout-delivery-card">
                    <div class="card-body py-3">
                        @if($authUser->address && $authUser->address->count() > 0)
                        <div id="checkout-delivery-summary" class="checkout-delivery-summary d-flex align-items-start justify-content-between flex-wrap">
                            <div class="checkout-delivery-summary-text">
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                    <strong class="text-dark">{{ __('Deliver to') }}:</strong>
                                    <span id="delivery-summary-name" class="text-dark">{{ $defaultAddress ? $defaultAddress->name : '' }}</span>
                                    <span id="delivery-summary-pincode" class="text-muted">{{ $defaultAddress ? ($defaultAddress->pincode ? $defaultAddress->pincode->pincode . ($defaultAddress->pincode->area ? ' (' . $defaultAddress->pincode->area . ')' : '') : ($defaultAddress->location ? _local($defaultAddress->location->name, $defaultAddress->location->local_name) : '')) : '' }}</span>
                                    <span id="delivery-summary-type" class="badge badge-light border text-uppercase">{{ $defaultAddress ? $defaultAddress->type : '' }}</span>
                                </div>
                                <div id="delivery-summary-address" class="text-muted small">
                                    @if($defaultAddress)
                                    {{ $defaultAddress->line_1 }}, {{ $defaultAddress->line_2 }}@if($defaultAddress->line_3), {{ $defaultAddress->line_3 }}@endif
                                    @endif
                                </div>
                            </div>
                            <button type="button" class="btn btn-link text-primary p-0 ml-2 checkout-delivery-change-btn">{{ __('Change') }}</button>
                        </div>
                        <div id="checkout-delivery-options" class="checkout-delivery-options mt-3" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted">{{ __('Select or add delivery address') }}</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary checkout-delivery-done-btn">{{ __('Done') }}</button>
                            </div>
                            <div class="row form-group">
                                @foreach($authUser->address as $address)
                                <div class="col-12 col-md-6">
                                    <div class="box mb-3 checkout-address-box" style="min-height: 140px;" data-name="{{ e($address->name) }}" data-type="{{ e($address->type) }}" data-mobile="{{ e($address->mobile) }}" data-line1="{{ e($address->line_1) }}" data-line2="{{ e($address->line_2) }}" data-line3="{{ e($address->line_3) }}" data-pincode="{{ e($address->pincode ? $address->pincode->pincode . ($address->pincode->area ? ' (' . $address->pincode->area . ')' : '') : ($address->location ? _local($address->location->name, $address->location->local_name) : '')) }}">
                                        <div class="custom-control custom-radio d-inline">
                                            <input type="radio" id="cart-address-{{ $address->id }}" act-on="change" act-request="{{ route('website.cart.calculation') }}" value="{{ $address->id }}" name="delivery_address" @if($address->pincode_id) pincode_id="{{ $address->pincode_id }}" @else location="{{ $address->location_id }}" @endif @if($address->default) checked @endif class="custom-control-input checkout-address-radio">
                                            <label class="custom-control-label" for="cart-address-{{ $address->id }}">{{ $address->type }} → {{ $address->name }}</label>
                                        </div>
                                        <div class="mt-2">{{ $address->name }}</div>
                                        <div>{{ $address->mobile }}</div>
                                        <div>{{ $address->line_1 }}, {{ $address->line_2 }}</div>
                                        <div>{{ $address->line_3 }}</div>
                                        <div>{{ $address->pincode ? $address->pincode->pincode . ($address->pincode->area ? ' (' . $address->pincode->area . ')' : '') : ($address->location ? _local($address->location->name, $address->location->local_name) : '') }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="{{ route('website.address.create') }}" class="btn btn-sm border rounded" style="color:#c98a25;border-color:#c98a25!important">{{ __('Add New Address') }}</a>
                        </div>
                        @else
                        <div id="checkout-delivery-summary" class="checkout-delivery-summary">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <strong class="text-dark">{{ __('Deliver to') }}:</strong>
                                    <span class="text-muted">{{ __('Add delivery address') }}</span>
                                </div>
                                <a href="{{ route('website.address.create') }}" class="btn btn-link text-primary p-0">{{ __('Add') }}</a>
                            </div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">{{ __('Please add a delivery address to place order.') }} <a href="{{ route('website.address.create') }}">{{ __('Add address') }}</a></p>
                        @endif
                        @php $defaultDelivery = $defaultAddress && ($defaultAddress->pincode ?? $defaultAddress->location) ? ($defaultAddress->pincode ?? $defaultAddress->location) : null; @endphp
                        @if($defaultDelivery && $defaultDelivery->minimum_cart_amount > cartTotalAmount())
                        <div class="minimum-order-value-message mt-2 text-danger small">
                            {{ __('The minimum order value for this delivery area must be') }} {!! priceFormat($defaultDelivery->minimum_cart_amount) !!}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">

                            @foreach($products as $product)
                            <div class="col-md-12">

                                @if(productMessageInCart($product->id))
                                <div class="item-message item-product-message-{{ $product->id }}">
                                    {{ __( productMessageInCart($product->id) ) }}
                                </div>  
                                @endif 

                                <div class="cart-item item-product-{{ $product->id }}">
                                    <div class="item-content">
                                        <a href="{{ route('website.product', ['slug' => $product->slug ]) }}" class="item-img"> 
                                            @if( $product->image )
                                            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image"/>
                                            @else
                                            <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="image"/>
                                            @endif
                                        </a>
                                        
                                        <div class="item-info">
                                                <a href="{{ route('website.product', ['slug' => $product->slug ]) }}" class="title"> {{  Str::limit(_local($product->name, $product->local_name), 50) }} </a>

                                                @if(($product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0) )
                                                <div class="cart-item-meta">
                                                    @if($product->brand)
                                                    <span class="cart-item-brand">{{ $product->brand->name }}</span>
                                                    @endif
                                                    <span class="cart-item-delivery">{{ __('Delivery by') }} {{ now()->addDays(14)->format('D, M j') }}</span>
                                                </div>
                                                @else
                                                <div class="details" style="color: #e20a0a;font-weight: 600;">{{ __('Out of Stock') }}</div>
                                                @endif
                                                <div class="price">{!! currency() !!} <span class="selling">{!! priceFormat(productTotalSellingPriceInCart( $product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper) ) , '') !!}</span> @if(productTotalPriceInCart( $product->id, $product->price) > productTotalSellingPriceInCart( $product->id, $product->selling_price) ) <del class="ml-1">{!! currency() !!} {!! priceFormat(productTotalPriceInCart( $product->id, minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper) ) , '') !!}</del> @endif</div> 
                                        
                                                <div class="item-action cart-item-actions">
                                                    <a href="javascript:void(0)" act-on="click" act-request="{{ route('website.cart.product.moveto.favourite', ['product' => $product->id ]) }}" class="cart-item-action-btn cart-item-action-favourite" title="{{ __('Move to Favourite') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
                                                        {{ __('Move to Favourite') }}
                                                    </a>
                                                    <a href="javascript:void(0)" act-on="click" act-request="{{ route('website.cart.product.remove', ['product' => $product->id]) }}" class="cart-item-action-btn cart-item-action-remove" title="{{ __('Remove') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                        {{ __('Remove') }}
                                                    </a>
                                                </div>   
                                        </div>
                                    </div>

                                    <div class="item-btn">
                                    @if(  ($product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0) )
                                    <div class="cart-btn">
                                        @if($product->minimum_quantity <= $product->unit->stepper )
                                        <div data-id="{{ $product->id }}" data-clear="false" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->unit->stepper }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn @if(!productExistsInCart( $product->id )) empty @endif">
                                            @else
                                            <div data-id="{{ $product->id }}"  data-clear="false" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->minimum_quantity }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn @if(!productExistsInCart( $product->id )) empty @endif">
                                                @endif

                                                <div class="steper-btn-text">{{ __('Add to Cart') }}</div>
                                                <!-- <span class="steper-btn-symbol">+</span> -->

                                                <div class="steper-btn-minus">-</div>

                                                @if(productExistsInCart($product->id, $product->minimum_quantity) <= productExistsInCart($product->id, $product->unit->stepper) )
                                                <div class="steper-btn-value">{{productExistsInCart($product->id, $product->unit->stepper)}}</div>
                                                @else
                                                <div class="steper-btn-value">{{productExistsInCart($product->id, $product->minimum_quantity)}}</div>
                                                @endif

                                                <div class="steper-btn-plus">+</div>
                                            </div>
                                        </div>
                                    @else
                                    <div class="notify-btn @if( App\Enquiry::where('user_id', authUser()->id ?? 0 )->where('product_id', $product->id)->count() > 0){{ 'active' }}@endif" act-on="click" act-request="{{ route('website.notify', ['product' => $product->id]) }}">{{ __('NOTIFY ME') }}</div>
                                    @endif
                                    </div>
 
                                </div>
                                
                            </div>
                            @endforeach
   

                        </div> <!-- row.// -->
                    </div> <!-- card-body.// -->
                </div>

	 

            </div> <!-- col.// -->


            <div class="col-lg-3">
                @if($products && $products->count() > 0)
                <div class="payment-summary-card">
                    <h1 class="cart-sidebar-title">{{ __('YOUR SHOPPING CART') }}</h1>
                    <div class="payment-summary-content">
                        <div class="payment-amount-row">
                            <span class="payment-label">{{ __('Total Amount') }}:</span>
                            <span class="payment-value">{!! currency() !!}<span class="cart-total-amount">{!! cartTotalAmount() !!}</span></span>
                        </div>
                        <div class="payment-amount-row">
                            <div class="payment-label-group">
                                <span class="payment-label">{{ __('Final Amount') }}:</span>
                                <span class="payment-tax-note">{{ __('(inclusive of all taxes)') }}</span>
                            </div>
                            @if($authUser && ($defaultDeliveryAmount ?? 0) > 0)
                            <span class="payment-value payment-value-final">{!! currency() !!}{!! priceFormat(cartTotalAmount() + ($defaultDeliveryAmount ?? 0), '') !!}</span>
                            @else
                            <span class="payment-value payment-value-final">{!! currency() !!}<span class="cart-total-amount">{!! cartTotalAmount() !!}</span></span>
                            @endif
                        </div>

                        <div class="payment-buttons">
                            @if($authUser)
                                @if(!$authUser->address || $authUser->address->count() == 0)
                                <a href="{{ route('website.address.create') }}" class="btn-checkout">{{ __('Add address to place order') }}</a>
                                @elseif(($defaultMinAmount ?? 0) > cartTotalAmount())
                                <button type="button" class="btn-checkout" disabled>{{ __('Place Order') }}</button>
                                @else
                                <button type="submit" class="btn-checkout">{{ __('Place Order') }}</button>
                                @endif
                            @else
                                <a href="{{ route('website.checkout') }}" class="btn-checkout">{{ __('Place Order') }}</a>
                            @endif
                            <a href="{{ route('website.products') }}" class="btn-continue-shopping">{{ __('Continue Shopping') }}</a>
                        </div>
                    </div>
                </div>
                @endif
            </div> <!-- col.// -->

        </div>

        @if($authUser)
        </form>
        @endif

        @else

        <div class="row">
            <div class="col-md-12">
                <div class="card empty-cart-card">
                    <div class="empty-content">
                        <div class="title">{{  __('Your cart is currently empty!') }}</div>
                        <p>{{ __('Oh no, there\'s nothing in your cart! Let\'s go back to the shop to add something.') }}<p>
                        <a href="{{route('website.products')}}" class="btn btn-light mb-3">
                            <span class="text pt-2">{{ __('Shop Now') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @endif

    </div> <!-- container .//  -->
</section>
</div> <!-- cart-page-wrapper -->

@if($products->count() > 0 && $authUser && $authUser->address && $authUser->address->count() > 0)
@section('script')
<script>
(function() {
	var summary = document.getElementById('checkout-delivery-summary');
	var options = document.getElementById('checkout-delivery-options');
	var changeBtn = document.querySelector('.checkout-delivery-change-btn');
	var doneBtn = document.querySelector('.checkout-delivery-done-btn');
	if (!summary || !options) return;
	function showSummary() {
		summary.style.display = '';
		options.style.display = 'none';
		if (changeBtn) changeBtn.setAttribute('aria-expanded', 'false');
	}
	function showOptions() {
		summary.style.display = 'none';
		options.style.display = 'block';
		if (changeBtn) changeBtn.setAttribute('aria-expanded', 'true');
	}
	if (changeBtn) changeBtn.addEventListener('click', showOptions);
	if (doneBtn) doneBtn.addEventListener('click', showSummary);
	var radios = document.querySelectorAll('.checkout-address-radio');
	var boxes = document.querySelectorAll('.checkout-address-box');
	radios.forEach(function(radio, i) {
		radio.addEventListener('change', function() {
			var box = boxes[i];
			if (!box) return;
			var nameEl = document.getElementById('delivery-summary-name');
			var pincodeEl = document.getElementById('delivery-summary-pincode');
			var typeEl = document.getElementById('delivery-summary-type');
			var addressEl = document.getElementById('delivery-summary-address');
			if (nameEl) nameEl.textContent = box.dataset.name || '';
			if (pincodeEl) pincodeEl.textContent = box.dataset.pincode || '';
			if (typeEl) typeEl.textContent = (box.dataset.type || '').toUpperCase();
			if (addressEl) {
				var parts = [box.dataset.line1, box.dataset.line2].filter(Boolean);
				if (box.dataset.line3) parts.push(box.dataset.line3);
				addressEl.textContent = parts.join(', ');
			}
		});
	});
})();
</script>
@endsection
@endif
@endsection

