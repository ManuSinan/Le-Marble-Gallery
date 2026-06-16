{{-- Original product card style: image on top, info below (card-product-grid) --}}
@php
    $showOriginalPrice = $product->price > $product->selling_price;
    $originalPrice = minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper);
    $sellingPrice = minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper);
    $isInStock = ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0);
@endphp
<div class="card card-product-grid item item-product-{{ $product->id }}">
    <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="img-wrap">
        @if($product->image)
        <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image"/>
        @else
        <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="image"/>
        @endif
    </a>
    <figcaption class="info-wrap">
        <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="product-title">{{ Str::limit(_local($product->name, $product->local_name), 50) }}</a>
        @if($product->brand)
        <div class="text-muted small mb-1">{{ $product->brand->name }}</div>
        @endif
        <div class="py-1">
            @if($isInStock)
                @if($product->stock_status == 'limited')
                    <p class="text mb-0" style="font-weight: 700; color: {{ $product->stock_available < 5 ? '#e20a0a' : '#28a745' }};">
                        {{ $product->stock_available }} {{ __('stock remaining') }}
                    </p>
                @else
                    <p class="text mb-0" style="font-weight: 700; color: #28a745;">{{ __('In Stock') }}</p>
                @endif
            @else
                <p class="text mb-0" style="color: #e20a0a; font-weight: 700;">{{ __('Out of Stock') }}</p>
            @endif
        </div>
        <div class="price mt-1">
            {!! currency() !!}{!! priceFormat($sellingPrice, '') !!}
            @if($showOriginalPrice)
            <del>{!! currency() !!}{!! priceFormat($originalPrice, '') !!}</del>
            @endif
        </div>
    </figcaption>
</div>
