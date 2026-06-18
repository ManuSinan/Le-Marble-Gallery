<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important; padding: 12px 16px !important; height: 68px !important; display: flex !important; align-items: center !important; justify-content: space-between !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important; font-size: 18px; text-align: center; flex: 1; margin: 0 10px;">{{ Str::limit(_local($product->name, $product->local_name), 22) }}</div>

    <div class="right" style="width: 32px;">
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
<div class="appCapsule" style="background-color: #F8F8F8; padding-top: 76px !important; padding-bottom: 120px !important;">
    <div class="section full mb-3 position-relative">

        <div class="carousel-full owl-carousel owl-theme" style="background: #fff; padding: 20px 0;">
            <div class="loading-fix item text-center" style="background: #fff !important;" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 0 ]) }}">
                @if( $product->image )
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}" class="imaged w-300 square" style="width: 95% !important; height: 95% !important; max-height: 250px; object-fit: contain; top: 50%; left: 50%; transform: translate(-50%, -50%);"/>
                @else
                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="imaged w-300 square" style="width: 95% !important; height: 95% !important; max-height: 250px; object-fit: contain; top: 50%; left: 50%; transform: translate(-50%, -50%);"/>
                @endif
            </div>

            @if( $product->gallery_image_1 )
            <div class="loading-fix item text-center" style="background: #fff !important;" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 1 ]) }}"> 
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_1)) }}" alt="{{ $product->gallery_image_1 }}" class="imaged w-300 square" style="width: 95% !important; height: 95% !important; max-height: 250px; object-fit: contain; top: 50%; left: 50%; transform: translate(-50%, -50%);"/>
            </div>
            @endif
            
            @if( $product->gallery_image_2 )
            <div class="loading-fix item text-center" style="background: #fff !important;" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 2 ]) }}">
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_2)) }}" alt="{{ $product->gallery_image_2 }}" class="imaged w-300 square" style="width: 95% !important; height: 95% !important; max-height: 250px; object-fit: contain; top: 50%; left: 50%; transform: translate(-50%, -50%);"/>
            </div>
            @endif
           
            @if( $product->gallery_image_3 )
            <div class="loading-fix item text-center" style="background: #fff !important;" act-on="click" act-request="{{ route('mobile.product.zoom', ['product' => $product->id, 'index' => 3 ]) }}">
                <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->gallery_image_3)) }}" alt="{{ $product->gallery_image_3 }}" class="imaged w-300 square" style="width: 95% !important; height: 95% !important; max-height: 250px; object-fit: contain; top: 50%; left: 50%; transform: translate(-50%, -50%);"/>
            </div>
            @endif
            
        </div>

        @if($favouriteStatus)
        <a href="{{ route('mobile.favourite.remove', ['product' => $product->id, 'referral' => route('mobile.product', ['product' => $product->id, 'referral' => $returnLink])]) }}" class="favourite-btn" style="position: absolute; top: 16px; right: 16px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 50%; z-index: 998; border: none; transition: background-color 0.2s;">
            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.84 2.61C19.3292 2.099 18.7228 1.69365 18.0554 1.41708C17.3879 1.14052 16.6725 0.998175 15.95 0.998175C15.2275 0.998175 14.5121 1.14052 13.8446 1.41708C13.1772 1.69365 12.5708 2.099 12.06 2.61L11 3.67L9.94 2.61C8.90831 1.57831 7.50903 0.998709 6.05 0.998709C4.59097 0.998709 3.19169 1.57831 2.16 2.61C1.12831 3.64169 0.548709 5.04097 0.548709 6.5C0.548709 7.95903 1.12831 9.35831 2.16 10.39L3.22 11.45L11 19.23L18.78 11.45L19.84 10.39C20.351 9.87924 20.7564 9.27281 21.0329 8.60536C21.3095 7.9379 21.4518 7.22249 21.4518 6.5C21.4518 5.77751 21.3095 5.0621 21.0329 4.39464C20.7564 3.72719 20.351 3.12076 19.84 2.61Z" fill="#D4AF37" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        @else
        <a href="{{ route('mobile.favourite.add', ['product' => $product->id, 'referral' => route('mobile.product', ['product' => $product->id, 'referral' => $returnLink])]) }}" class="favourite-btn" style="position: absolute; top: 16px; right: 16px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 50%; z-index: 998; border: none; transition: background-color 0.2s;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1F2937" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        </a>
        @endif

        <div class="share-btn share" data-title="{{ config('app.name', '') }}" data-text="{{ $product->name }}" data-url="{{ route('share', ['product' => $product->slug]) }}" style="position: absolute; top: 68px; right: 16px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 50%; z-index: 998; border: none; cursor: pointer; transition: background-color 0.2s;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1F2937" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
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
                    <div class="details" style="font-size: 12px; color: #374151; margin-top: 8px;">{{ $product->unit->name == 'Sq.Ft' ? __('Area') : __('Quantity') }}: <span class="unit" style="font-weight: bold; color: #111827;">{{productExistsInCart($product->id, $product->minimum_quantity)}}</span> {{ _local($product->unit->name, $product->unit->local_name) }}</div>
                    @else
                    <div class="details" style="color: #e20a0a;font-weight: 600; font-size: 12px; margin-top: 8px;">{{ __('Out of Stock') }}</div>
                    @endif
                    
                    <div class="price large" style="font-weight: 800; color: #111827; font-size: 18px; margin-top: 6px;">
                        {!! priceFormat(productTotalSellingPriceInCart( $product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper) ) , '₹') !!} 
                    </div>

                </div>


                @if(  ($product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0) )
                <div class="col-4 d-flex align-items-center justify-content-end">

                    @php
                        $minQty = $product->minimum_quantity <= $product->unit->stepper ? $product->unit->stepper : $product->minimum_quantity;
                    @endphp
                    <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $minQty }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn @if(!productExistsInCart( $product->id )) empty @endif" style="border-radius: 6px; border: 1.5px solid #D4AF37 !important; height: 38px; line-height: 36px; width: 100px; background: transparent; overflow: hidden;">
                     
                        <div class="steper-btn-text" style="color: #D4AF37 !important; font-weight: 700; font-family: 'Inter', sans-serif; font-size: 13px; letter-spacing: 0.5px;">{{ __('ADD') }}</div>

                        <div class="steper-btn-minus" style="color: #D4AF37 !important; font-weight: bold; font-size: 16px; height: 100%; text-align: center;">-</div>

                        <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold; font-size: 13px; height: 100%; text-align: center;">{{ productExistsInCart($product->id, $minQty) }}</div>
                        
                        <div class="steper-btn-plus" style="color: #D4AF37 !important; font-weight: bold; font-size: 16px; height: 100%; text-align: center;">+</div>
                    </div>

                </div>
                @else
                <div class="col-4 d-flex align-items-center justify-content-end">
                    <div class="notify-btn" act-on="click" act-request="{{ route('mobile.notify', ['product' => $product->id]) }}" style="border-radius: 6px; background-color: #1F2937 !important; color: #fff !important; border: none; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 12px; height: 38px; line-height: 38px; width: 100px; text-align: center; cursor: pointer;">{{ __('NOTIFY') }}</div>
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
 
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="wide-block mb-3" style="border-radius: 8px; margin: 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 16px;">
            <div class="section-title pl-0" style="font-family: 'Playfair Display', serif; font-weight: bold; font-size: 15px; color: #1F2937; margin-bottom: 12px;">Related Materials</div>
            
            <div class="related-products-scroll" style="display: flex; overflow-x: auto; gap: 12px; padding-bottom: 8px; scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
                @foreach($relatedProducts as $relProduct)
                <div class="related-card" style="flex: 0 0 140px; background: #fff; border-radius: 6px; border: 1px solid #E5E7EB; overflow: hidden; display: flex; flex-direction: column;">
                    <a href="{{ route('mobile.product', ['product' => $relProduct->id, 'referral' => $returnLink]) }}" class="loading-fix" style="display: block; position: relative; width: 100%; height: 95px !important; padding-bottom: 0 !important; background: #f3f4f6;">
                        @if($relProduct->image)
                        <img src="{{ asset('uploads/' . $relProduct->image) }}" alt="{{ $relProduct->name }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute;">
                        @else
                        <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $relProduct->name }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute;">
                        @endif
                    </a>
                    <div style="padding: 8px; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between;">
                        <div>
                            <h4 style="font-size: 11px; font-weight: bold; color: #1F2937; margin: 0; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <a href="{{ route('mobile.product', ['product' => $relProduct->id, 'referral' => $returnLink]) }}" style="color: inherit;">{{ _local($relProduct->name, $relProduct->local_name) }}</a>
                            </h4>
                            <p style="font-size: 9px; color: #6B7280; margin: 2px 0 0 0;">{{ $relProduct->product_code }}</p>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #D4AF37; margin-top: 4px;">
                            {!! priceFormat($relProduct->selling_price, '₹') !!}
                        </div>
                        <div class="cart-btn mt-2" style="width: 100%;">
                            @if( ($relProduct->stock_status == 'unlimited') || ($relProduct->stock_status == 'limited' && $relProduct->stock_available > 0) )
                                @php $currentQty = productExistsInCart($relProduct->id, 0); @endphp
                                <div data-id="{{ $relProduct->id }}" data-clear="true" data-price="{{ $relProduct->price }}" data-selling-price="{{ $relProduct->selling_price }}" data-steper="{{ $relProduct->unit->stepper }}" data-min="{{ $relProduct->minimum_quantity }}" @if( $relProduct->stock_status == 'limited') data-max="{{ $relProduct->stock_available }}" @endif class="steper-btn {{ ($currentQty == 0) ? 'empty' : '' }}" style="border-radius: 4px; height: 32px; border-color: #D4AF37 !important; width: 100%;">
                                    <div class="steper-btn-text" style="color: #D4AF37 !important; font-weight: bold;">{{ __('ADD') }}</div>
                                    <div class="steper-btn-minus" style="color: #D4AF37 !important;">-</div>
                                    <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{ productExistsInCart($relProduct->id, $relProduct->minimum_quantity) }}</div>
                                    <div class="steper-btn-plus" style="color: #D4AF37 !important;">+</div>
                                </div>
                            @else
                                <div class="notify-btn text-center" act-on="click" act-request="{{ route('mobile.notify', ['product' => $relProduct->id]) }}" style="background-color: #EF4444; border-radius: 4px; padding: 6px 8px; font-size: 10px; font-weight: bold; color: white; cursor: pointer; width: 100%;">{{ __('AWAITING STOCK') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif


    </div>


</div>
<!-- * App Capsule -->

<div class="appBottomMenu checkout-bottom-bar" style="border-top: none; @if(cartItemCount() == 0) display: none; @endif">
    <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37;">
        <div class="checkout-btn-info">
            <div class="info-small"><span class="cart-item-count text-light">{{ cartItemCount() }}</span> {{ __('MATERIALS') }}</div>
            <div class="info-large mt-0"><span class="cart-total-sqft text-light">{{ cartTotalSqft() }}</span> {{ cartTotalUnitLabel() }}</div>
        </div>
        <a href="{{ route('mobile.cart',['referral' => route('mobile.product', ['product' => $product->id, 'referral' => $returnLink ])]) }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
            {{ __('View Basket') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
</div>
