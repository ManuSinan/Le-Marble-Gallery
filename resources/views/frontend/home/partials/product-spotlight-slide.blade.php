{{-- Single slide for home appliances spotlight carousel (sponsored-style: image + details + Shop now) --}}
@php
    $unit = $product->unit;
    $stepper = $unit ? $unit->stepper : 1;
    $showOriginalPrice = $product->price > $product->selling_price;
    $originalPrice = minimumQuantityPrice($product->price, $product->minimum_quantity, $stepper);
    $sellingPrice = minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $stepper);
    $imageUrl = $product->image ? asset('uploads/' . $product->image) : asset('assets/frontend/images/200x150-blank.png');
@endphp
<div class="home-appliances-spotlight-slide">
    <div class="home-appliances-spotlight-slide__inner">
        <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="home-appliances-spotlight-slide__image-wrap">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="home-appliances-spotlight-slide__image" loading="lazy" onerror="this.src='{{ asset('assets/frontend/images/200x150-blank.png') }}'; this.onerror=null;"/>
        </a>
        <div class="home-appliances-spotlight-slide__content">
            <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="home-appliances-spotlight-slide__title">{{ Str::limit(_local($product->name, $product->local_name), 70) }}</a>
            <div class="home-appliances-spotlight-slide__price">
                {!! currency() !!}{!! priceFormat($sellingPrice, '') !!}
                @if($showOriginalPrice)
                    <span class="home-appliances-spotlight-slide__mrp">{!! currency() !!}{!! priceFormat($originalPrice, '') !!}</span>
                @endif
            </div>
            <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="home-appliances-spotlight-slide__btn">{{ __('Shop now') }}</a>
        </div>
    </div>
    <div class="home-appliances-spotlight-slide__sponsored">
        <span class="home-appliances-spotlight-slide__sponsored-text">{{ __('Sponsored') }}</span>
        <span class="home-appliances-spotlight-slide__sponsored-icon" aria-hidden="true">ⓘ</span>
    </div>
</div>
