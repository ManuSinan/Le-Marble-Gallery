<!-- Shop section: one-line detailing (horizontal row per product) -->
<div class="product-list-view">

@foreach($products as $product)

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org/",
            "@type": "Product",
            "name":  "{{ $product->name }}",
            @if( $product->image )
            "image": "{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}",
            @endif
            "description" : "{{ $product->description }}",
            "brand": {
                "@type": "Brand",
                "name": "{{ $product->brand->name }}"
            },
            "category":"{{ $product->category->name }}",
            "url": "{{ route('website.product', ['slug' => $product->slug ]) }}",
            @if(productTotalPriceInCart( $product->id, $product->price) > productTotalSellingPriceInCart( $product->id, $product->selling_price) )
            "offers": {
                "@type": "Offer",
                "url": "{{ route('website.product', ['slug' => $product->slug ]) }}",
                "priceCurrency": "INR",
                @if(($product->stock_status == 'unlimited') ||  ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity &&  $product->stock_available > 0) )
                "availability": "https://schema.org/InStock",
                @endif
                "price" : "{{ $product->selling_price }}"
            },
            @endif
            "sku": "{{ $product->product_code }}"
        }
    </script>

    <div class="product-list-row item item-product-{{ $product->id }}">
        @php
            $showOriginalPrice = productTotalPriceInCart($product->id, $product->price) > productTotalSellingPriceInCart($product->id, $product->selling_price);
            $originalPrice = productTotalPriceInCart($product->id, minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper));
            $sellingPrice = productTotalSellingPriceInCart($product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper));
            $savings = $originalPrice - $sellingPrice;
        @endphp

        <!-- Left: Image -->
        <div class="product-list-row-image">
            <a href="{{ route('website.product', ['slug' => $product->slug ]) }}" class="product-list-row-img-link">
                @if( $product->image )
                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}"/>
                @else
                <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}"/>
                @endif
            </a>
            @php
                $isInStock = ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0);
            @endphp
            @if(!$isInStock)
            <div class="product-list-row-delivery">{{ __('Not deliverable') }}</div>
            @endif
        </div>

        <!-- Center: Details -->
        <div class="product-list-row-details">
            @if($product->brand)
            <div class="product-list-row-brand">{{ $product->brand->name }}</div>
            @endif
            <a href="{{ route('website.product', ['slug' => $product->slug ]) }}" class="product-list-row-title">
                {{ _local($product->name, $product->local_name) }}
            </a>
            <div class="product-list-row-rating">
                <span class="product-list-row-stars">★★★★★</span>
                <span class="product-list-row-rating-value">4.5</span>
            </div>
            <div class="product-list-row-description">
                {{ Str::limit(strip_tags(_local($product->description, $product->local_description) ?? ''), 180) }}
            </div>
        </div>

        <!-- Right: Price & Actions -->
        <div class="product-list-row-price-block">
            <div class="product-list-row-price">
                <span class="product-list-row-price-current">{!! currency() !!}{!! priceFormat($sellingPrice, '') !!}</span>
                @if($showOriginalPrice)
                <span class="product-list-row-price-original">{!! currency() !!}{!! priceFormat($originalPrice, '') !!}</span>
                <span class="product-list-row-price-save">{{ __('Save') }} {!! currency() !!}{!! priceFormat($savings, '') !!}</span>
                @endif
            </div>
            <div class="product-list-row-actions">
                @if($isInStock)
                <div class="cart-btn-modern product-list-row-cart">
                    @php
                        $maxAttr = ($product->stock_status == 'limited') ? ' data-max="' . $product->stock_available . '"' : '';
                        $emptyClass = !productExistsInCart($product->id) ? ' empty' : '';
                        $minQuantity = ($product->minimum_quantity <= $product->unit->stepper) ? $product->unit->stepper : $product->minimum_quantity;
                    @endphp
                    <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $minQuantity }}"{!! $maxAttr !!} class="steper-btn-modern steper-btn-list{{ $emptyClass }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="cart-icon"><path d="M7 18C5.895 18 5.01 18.895 5.01 20C5.01 21.105 5.895 22 7 22C8.105 22 9 21.105 9 20C9 18.895 8.105 18 7 18ZM1 2V4H3L6.595 11.585L5.245 14.035C5.09 14.325 5 14.65 5 15C5 16.105 5.895 17 7 17H19V15H7.425C7.285 15 7.175 14.89 7.175 14.75L8.1 13H15.55C16.3 13 16.955 12.585 17.3 11.97L20.875 5.48C20.955 5.34 21 5.175 21 5C21 4.445 20.55 4 20 4H5.215L4.265 2H1ZM17 18C15.895 18 15.01 18.895 15.01 20C15.01 21.105 15.895 22 17 22C18.105 22 19 21.105 19 20C19 18.895 18.105 18 17 18Z"/></svg>
                        <span class="steper-btn-text-modern">{{ __('Add to Cart') }}</span>
                        <div class="steper-btn-minus-modern">-</div>
                        @if(productExistsInCart($product->id, $product->minimum_quantity) <= productExistsInCart($product->id, $product->unit->stepper) )
                            <div class="steper-btn-value-modern">{{ productExistsInCart($product->id, $product->unit->stepper) }}</div>
                        @else
                            <div class="steper-btn-value-modern">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</div>
                        @endif
                        <div class="steper-btn-plus-modern">+</div>
                    </div>
                </div>
                @else
                @php
                    $enquiryActive = \App\Models\Enquiry::where('user_id', authUser()->id ?? 0)->where('product_id', $product->id)->count() > 0 ? ' active' : '';
                @endphp
                <div class="notify-btn-modern notify-btn-list{{ $enquiryActive }}" act-on="click" act-request="{{ route('website.notify', ['product' => $product->id]) }}">{{ __('NOTIFY ME') }}</div>
                @endif
                @php
                    $favouriteActive = \App\Models\Favourite::where('user_id', authUser()->id ?? 0)->where('product_id', $product->id)->count() > 0 ? ' active' : '';
                @endphp
                <div class="fav-btn-modern fav-btn-list{{ $favouriteActive }}" act-on="click" act-request="{{ route('website.favourite.toggle', ['product' => $product->id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </div>
            </div>
        </div>
    </div>

@endforeach

</div><!-- .product-list-view -->
