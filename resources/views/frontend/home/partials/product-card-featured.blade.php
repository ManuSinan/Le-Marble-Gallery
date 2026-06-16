{{-- Featured-style card: large image, name, price, Shop now (e.g. Featured in Kitchen) --}}
@php
    $showOriginalPrice = $product->price > $product->selling_price;
    $originalPrice = minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper);
    $sellingPrice = minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper);
@endphp
<div class="home-appliances-card item item-product-{{ $product->id }}">
    <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="home-appliances-card__image-wrap">
        @if($product->image)
            <img src="{{ asset('uploads/' . str_replace('/base/', '/large/', $product->image)) }}" alt="{{ $product->name }}" class="home-appliances-card__image"/>
        @else
            <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="home-appliances-card__image"/>
        @endif
    </a>
    <div class="home-appliances-card__body">
        <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="home-appliances-card__title">{{ Str::limit(_local($product->name, $product->local_name), 55) }}</a>
        <div class="home-appliances-card__price">
            {!! currency() !!}{!! priceFormat($sellingPrice, '') !!}
            @if($showOriginalPrice)
                <span class="home-appliances-card__mrp">M.R.P: {!! currency() !!}{!! priceFormat($originalPrice, '') !!}</span>
            @endif
        </div>
    </div>
</div>
