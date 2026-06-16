{{-- Horizontal product card: left = brand, title, price; right = image --}}
@php
    $showOriginalPrice = $product->price > $product->selling_price;
    $originalPrice = minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper);
    $sellingPrice = minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper);
@endphp
<a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="special-product-card-horizontal item item-product-{{ $product->id }}">
    <div class="special-product-card-horizontal__content">
        @if($product->brand)
            <span class="special-product-card-horizontal__brand">{{ $product->brand->name }}</span>
        @endif
        <h3 class="special-product-card-horizontal__title">{{ Str::limit(_local($product->name, $product->local_name), 45) }}</h3>
        <div class="special-product-card-horizontal__price">
            {!! currency() !!}{!! priceFormat($sellingPrice, '') !!}
            @if($showOriginalPrice)
                <del>{!! currency() !!}{!! priceFormat($originalPrice, '') !!}</del>
            @endif
        </div>
    </div>
    <div class="special-product-card-horizontal__image-wrap">
        @if($product->image)
            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="special-product-card-horizontal__image"/>
        @else
            <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="special-product-card-horizontal__image"/>
        @endif
    </div>
</a>
