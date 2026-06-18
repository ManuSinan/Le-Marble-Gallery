<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ __('Quotation Basket') }}</div>
    
    <div class="right">
        <a href="#" class="headerButton toggle-searchbox" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
        </a>
    </div>
</div>
 
<!-- Search Component -->
<div id="search" class="appHeader" style="background-color: #1F2937 !important;">
    <form class="search-form" action="/" act-on="submit" act-request="{{ route('mobile.products') }}">
        <div class="form-group searchbox" style="margin: 0 auto; max-width: 95%;">
            <input type="text" class="form-control" name="search" placeholder="{{ __('Search materials...') }}" style="background-color: #fff; color: #111827; border-radius: 4px; padding-left: 40px;">
            <i class="input-icon" style="color: #111827;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
            </i>
            <a href="#" class="ml-1 close toggle-searchbox" style="color: #fff !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
        </div>
    </form>
</div>
<!-- * Search Component -->
 
<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 140px !important;">
 
    @if($products && $products->count() > 0)
        <div class="section full products-list mb-3">
            <ul class="listview image-listview media border-0" style="background: transparent;">
                @foreach($products as $product)

                    <li class="mb-2 shadow-sm" style="background: #fff; border-radius: 8px; margin: 8px 12px; list-style: none; padding: 10px;">
                        <div class="item d-flex">
                            <div class="imageWrapper" style="width: 80px; height: 80px; border-radius: 6px; overflow: hidden; background: #e5e7eb;">
                                <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.cart')]) }}" class="loading-fix"> 
                                    @if( $product->image )
                                    <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;"/>
                                    @else
                                    <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;"/>
                                    @endif
                                </a>
                            </div>
                            <div class="in d-flex flex-column pl-2" style="border: none; padding-left: 12px !important; flex: 1;">
                                <div class="d-flex justify-content-between align-items-start w-100">
                                    <div class="pt-0">
                                        <div class="title" style="font-size: 13px; font-weight: 700; color: #111827; font-family: 'Inter', sans-serif;">
                                            <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.cart')]) }}" class="text-dark">
                                                {{ strtoupper($product->name) }}
                                            </a>
                                        </div>
                                        <div style="font-size: 11px; color: #6B7280;">{{ $product->unit->name == 'Sq.Ft' ? __('Area') : __('Quantity') }}: <span class="unit" style="font-weight: bold; color: #111827;">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</span> {{ _local($product->unit->name, $product->unit->local_name) }}</div>
                                        <div class="price mt-1" style="font-weight: 700; color: #111827; font-size: 13px;">
                                            {!! priceFormat(productTotalSellingPriceInCart($product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper)), '₹') !!}
                                        </div>
                                    </div>
                                    
                                    <div class="cart-btn" style="align-self: center;">
                                        <div data-id="{{ $product->id }}" data-clear="false" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->minimum_quantity }}" @if($product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn" style="border-radius: 4px; height: 32px; border-color: #D4AF37 !important;">
                                            <div class="steper-btn-minus" style="color: #D4AF37 !important;">-</div>
                                            <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</div>
                                            <div class="steper-btn-plus" style="color: #D4AF37 !important;">+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Remarks and customized cut fields -->
                        <div class="mt-2" style="border-top: 1px solid #f3f4f6; padding-top: 8px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Add custom cut remarks (e.g. edge polishing, specific length)..." style="font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px 10px; background: #F9FBFD; font-family: 'Inter', sans-serif;">
                        </div>
 
                        <div class="item-action mt-2 d-flex justify-content-end" style="border: none; padding: 0;">
                            <a href="{{ route('mobile.cart.product.remove', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-danger" style="border-radius: 4px; font-size: 10px; padding: 2px 8px; border-color: #EF4444; color: #EF4444;">
                                {{ __('Remove') }}
                            </a>
                        </div>   
                    </li>
        
                @endforeach
            </ul>
        </div>
    @else
    <div class="empty-products text-center py-5" style="background: white; margin: 24px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div class="error-page">
            <div class="icon-box" style="color: #D4AF37; margin-bottom: 16px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
            </div>
            <h1 class="title" style="font-size: 18px; font-weight: bold; color: #111827; font-family: 'Playfair Display', serif;">{{ __('Your Quotation Basket is empty!') }}</h1>
            <div class="text px-3 mb-4" style="font-size: 13px; color: #6B7280; font-family: 'Inter', sans-serif;">
                {{ __('Select premium marble, granite, or quartz slabs to start building a quotation.') }}
            </div>
            <a href="{{ route('mobile.products') }}" class="btn btn-primary" style="background-color: #1F2937 !important; border-color: #1F2937 !important; color: white; padding: 8px 20px; font-weight: bold; border-radius: 4px;">Browse Catalog</a>
        </div>
    </div>
    @endif
 
</div>
<!-- * App Capsule -->
 
@if($products && $products->count() > 0)
<div style="position: fixed; bottom: 68px; left: 0; right: 0; z-index: 998; background: transparent; padding: 0 10px;">
    <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37; margin: 0;">
        <div class="checkout-btn-info">
            <div class="info-small"><span class="cart-item-count">{{ cartItemCount() }}</span> {{ __('MATERIALS') }}</div>
            <div class="info-large mt-0"><span class="cart-total-sqft">{{ cartTotalSqft() }}</span> {{ cartTotalUnitLabel() }}</div>
        </div>
 
        @if($authUser)
        <a href="{{ route('mobile.order.summary') }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
        @else
        <a href="{{ route('mobile.signin', ['return' => 'cart']) }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
        @endif
            {{ __('Review Quotation' ) }}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
</div>
@endif
