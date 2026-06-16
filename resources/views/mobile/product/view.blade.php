<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ Str::limit(_local($product->name, $product->local_name), 22) }}</div>

    <div class="right">
    </div>
</div>

<!-- Search Component -->
<div id="search" class="appHeader" style="background-color: #1F2937 !important;">
    <form class="search-form" action="/" act-on="submit" act-request="{{ route('mobile.products') }}">
        <div class="form-group searchbox" style="margin: 0 auto; max-width: 95%;">
            <input type="search" class="form-control" name="search" placeholder="{{ __('Search materials...') }}" style="background-color: #fff; color: #111827; border-radius: 30px; padding-left: 40px;">
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
<div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 100px;">
    <div class="section full mb-3 position-relative">

        <div class="carousel-full owl-carousel owl-theme" style="background: #fff; padding: 20px 0;">
            <div class="loading-fix item text-center" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 0 ]) }}">
                @if( $product->image )
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}" class="imaged w-300 square" style="max-height: 250px; object-fit: contain; margin: 0 auto;"/>
                @else
                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="imaged w-300 square" style="max-height: 250px; object-fit: contain; margin: 0 auto;"/>
                @endif
            </div>

            @if( $product->gallery_image_1 )
            <div class="loading-fix item text-center" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 1 ]) }}"> 
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_1)) }}" alt="{{ $product->gallery_image_1 }}" class="imaged w-300 square" style="max-height: 250px; object-fit: contain; margin: 0 auto;"/>
            </div>
            @endif
            
            @if( $product->gallery_image_2 )
            <div class="loading-fix item text-center" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 2 ]) }}">
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_2)) }}" alt="{{ $product->gallery_image_2 }}" class="imaged w-300 square" style="max-height: 250px; object-fit: contain; margin: 0 auto;"/>
            </div>
            @endif
           
            @if( $product->gallery_image_3 )
            <div class="loading-fix item text-center" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 3 ]) }}">
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_3)) }}" alt="{{ $product->gallery_image_3 }}" class="imaged w-300 square" style="max-height: 250px; object-fit: contain; margin: 0 auto;"/>
            </div>
            @endif
            
        </div>

        @if($favouriteStatus)
        <a href="{{ route('mobile.favourite.remove', ['product' => $product->id, 'referral' => route('mobile.product', ['product' => $product->id, 'referral' => $returnLink])]) }}" class="favourite-btn" style="background: rgba(255,255,255,0.9); box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 50%;">
            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.84 2.61C19.3292 2.099 18.7228 1.69365 18.0554 1.41708C17.3879 1.14052 16.6725 0.998175 15.95 0.998175C15.2275 0.998175 14.5121 1.14052 13.8446 1.41708C13.1772 1.69365 12.5708 2.099 12.06 2.61L11 3.67L9.94 2.61C8.90831 1.57831 7.50903 0.998709 6.05 0.998709C4.59097 0.998709 3.19169 1.57831 2.16 2.61C1.12831 3.64169 0.548709 5.04097 0.548709 6.5C0.548709 7.95903 1.12831 9.35831 2.16 10.39L3.22 11.45L11 19.23L18.78 11.45L19.84 10.39C20.351 9.87924 20.7564 9.27281 21.0329 8.60536C21.3095 7.9379 21.4518 7.22249 21.4518 6.5C21.4518 5.77751 21.3095 5.0621 21.0329 4.39464C20.7564 3.72719 20.351 3.12076 19.84 2.61Z" fill="#D4AF37" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        @else
        <a href="{{ route('mobile.favourite.add', ['product' => $product->id, 'referral' => route('mobile.product', ['product' => $product->id, 'referral' => $returnLink])]) }}" class="favourite-btn" style="background: rgba(255,255,255,0.9); box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 50%;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        </a>
        @endif

        <div class="share-btn share" data-title="{{ config('app.name', '') }}" data-text="{{ $product->name }}" data-url="{{ route('share', ['product' => $product->slug]) }}" style="background: rgba(255,255,255,0.9); box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 50%;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
        </div>
 
    </div>
 
    <div class="section full">
        <div class="mb-1 wide-block product item-product-{{ $product->id }}" style="border-radius: 8px; margin: 0 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 16px;">
            <div class="row">
                <div class="col-8">
                    <div class="title large" style="font-family: 'Playfair Display', serif; font-weight: bold; font-size: 20px; color: #111827;">{{ _local($product->name, $product->local_name)}}</div>
                    <div class="brand" style="font-size: 11px; color: #6B7280; margin-top: 4px;">
                        @if($product->brand)
                        {{ __('Origin') }}: <span class="font-weight-bold" style="color: #D4AF37;">{{ _local($product->brand->name, $product->brand->local_name) }}</span> •
                        @endif
                        {{ __('Collection') }}: {{ _local($product->category->name, $product->category->local_name) }}
                    </div>
                
                    @if(($product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0) )
                    <div class="details" style="font-size: 12px; color: #374151; margin-top: 8px;">Area: <span class="unit" style="font-weight: bold; color: #111827;">{{productExistsInCart($product->id, $product->minimum_quantity)}}</span> {{ _local($product->unit->name, $product->unit->local_name) }}</div>
                    @else
                    <div class="details" style="color: #e20a0a;font-weight: 600; font-size: 12px; margin-top: 8px;">{{ __('Out of Stock') }}</div>
                    @endif
                    
                    <div class="price large" style="font-weight: 800; color: #111827; font-size: 18px; margin-top: 6px;">
                        {!! priceFormat(productTotalSellingPriceInCart( $product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper) ) , '₹') !!} 
                        @if(productTotalPriceInCart( $product->id, $product->price) > productTotalSellingPriceInCart( $product->id, $product->selling_price) ) 
                        <del class="ml-1" style="font-size: 13px; color: #9CA3AF; font-weight: normal;">{!! priceFormat(productTotalPriceInCart( $product->id, minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper) ) , '₹') !!}</del> 
                        @endif
                    </div>

                </div>


                @if(  ($product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0) )
                <div class="col-4 d-flex align-items-center justify-content-end">


                    @if($product->minimum_quantity <= $product->unit->stepper )
                    <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->unit->stepper }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn @if(!productExistsInCart( $product->id )) empty @endif" style="border-radius: 4px; border-color: #D4AF37 !important; height: 36px; line-height: 34px;">
                    @else
                    <div data-id="{{ $product->id }}"  data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->minimum_quantity }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn @if(!productExistsInCart( $product->id )) empty @endif" style="border-radius: 4px; border-color: #D4AF37 !important; height: 36px; line-height: 34px;">
                    @endif
                     
                        <div class="steper-btn-text" style="color: #D4AF37 !important; font-weight: bold; font-family: 'Inter', sans-serif;">{{ __('ADD') }}</div>

                        <div class="steper-btn-minus" style="color: #D4AF37 !important;">-</div>

                        @if(productExistsInCart($product->id, $product->minimum_quantity) <= productExistsInCart($product->id, $product->unit->stepper) )
                        <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{productExistsInCart($product->id, $product->unit->stepper)}}</div>
                        @else
                        <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{productExistsInCart($product->id, $product->minimum_quantity)}}</div>
                        @endif
                        
                        
                        <div class="steper-btn-plus" style="color: #D4AF37 !important;">+</div>
                    </div>

                </div>
                @else
                <div class="col-4 d-flex align-items-center justify-content-end">
                    <div class="notify-btn" act-on="click" act-request="{{ route('mobile.notify', ['product' => $product->id]) }}" style="border-radius: 4px; background-color: #1F2937 !important; font-family: 'Inter', sans-serif; font-weight: bold;">{{ __('NOTIFY ME') }}</div>
                </div>
                @endif
                <div class="col-12">
                    @if(productMessageInCart($product->id) ) 
                        @if(( $product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0))
                        <div class="item-message item-product-message-{{ $product->id }}" style="color: #D4AF37; font-size: 11px;">
                            {{ __( productMessageInCart($product->id) ) }}
                        </div>  
                        @endif 
                    @endif 
                </div>    
            </div>
        </div>



        @if($product->attribute && $product->attribute->variants)
            @foreach($product->attribute->variants as $variants) 

                @if($variants && $product->productGroupVariant($variants->id)->count() >= 1)
                <div class="wide-block mb-3" style="border-radius: 8px; margin: 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 16px;">
                    <div class="section-title pl-0" style="font-family: 'Playfair Display', serif; font-weight: bold; font-size: 14px; color: #1F2937; margin-bottom: 8px;">{{ $variants->name }}</div>
        
                    <div class="btn-group-sm" role="group" aria-label="variants">
                        @foreach($product->productGroupVariant($variants->id)->get() as $option) 
                            @if( $option->product->id == $product->id)
                            <button type="button" class="btn btn-outline-secondary active mb-1" style="background-color: #1F2937 !important; color: #fff !important; border-color: #1F2937 !important; border-radius: 4px; font-size: 11px;">{{ $option->variantOption->value }}</button>
                            @else 
                            <a href="{{ route('mobile.product', ['product' => $option->product->id]) }}" class="btn btn-outline-secondary mb-1" style="border-radius: 4px; font-size: 11px; border-color: #d1d5db; color: #4B5563;">{{ $option->variantOption->value }}</a>
                            @endif 
                        @endforeach
                    </div>
                </div>
                @endif
            
            @endforeach
        @endif
 
        <div class="wide-block mb-3" style="border-radius: 8px; margin: 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 16px;">
            <div class="section-title pl-0" style="font-family: 'Playfair Display', serif; font-weight: bold; font-size: 14px; color: #1F2937; margin-bottom: 8px;">Material Description</div>
            <p style="font-family: 'Inter', sans-serif; font-size: 13px; color: #4B5563; line-height: 1.6; margin-bottom: 0;">{!! nl2br(_local($product->description, $product->local_description)) !!}</p>
        </div>

        <div class="wide-block mb-3" style="border-radius: 8px; margin: 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 12px 16px; background-color: #FFFDF5; border-left: 3px solid #D4AF37;">
            <p class="mb-0" style="font-family: 'Inter', sans-serif; font-size: 11px; color: #856404; line-height: 1.5;">* {{ __('Slab images are for reference only. Natural veins and patterns will vary from batch to batch.') }}</p>
        </div>
 

    </div>


</div>
<!-- * App Capsule -->

<div class="appBottomMenu" style="border-top: none;">
    <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37;">
        <div class="checkout-btn-info">
            <div class="info-small"><span class="cart-item-count text-light">{{ cartItemCount() }}</span> {{ __('MATERIALS') }}</div>
            <div class="info-large mt-0"><span class="cart-total-sqft text-light">{{ cartTotalSqft() }}</span> Sq.Ft</div>
        </div>
        <a href="{{ route('mobile.cart',['referral' => route('mobile.product', ['product' => $product->id, 'referral' => $returnLink ])]) }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
            {{ __('View Basket') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
</div>
