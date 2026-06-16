{{-- Shop grid: square images, gallery-style --}}
<div class="product-grid-view">
  @foreach($products as $product)
  <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $product->name }}",
      @if($product->image)
      "image": "{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}",
      @endif
      "description": "{{ $product->description }}",
      "brand": { "@type": "Brand", "name": "{{ $product->brand->name ?? '' }}" },
      "category": "{{ $product->category->name ?? '' }}",
      "url": "{{ route('website.product', ['slug' => $product->slug]) }}",
      @if(productTotalPriceInCart($product->id, $product->price) > productTotalSellingPriceInCart($product->id, $product->selling_price))
      "offers": {
        "@type": "Offer",
        "url": "{{ route('website.product', ['slug' => $product->slug]) }}",
        "priceCurrency": "INR",
        @if(($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0))
        "availability": "https://schema.org/InStock",
        @endif
        "price": "{{ $product->selling_price }}"
      },
      @endif
      "sku": "{{ $product->product_code }}"
    }
  </script>

  <article class="product-grid-card item item-product-{{ $product->id }}">
    <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="product-grid-card-link">
      <div class="product-grid-image">
        @if($product->image)
        <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}">
        @else
        <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}">
        @endif
      </div>
      <div class="product-grid-info">
        @if($product->category)
        <span class="product-grid-tag">{{ $product->category->name }}</span>
        @endif
        <span class="product-grid-price">{!! currency() !!}{!! priceFormat($product->selling_price ?? $product->price ?? 0, '') !!}</span>
        <h3 class="product-grid-title">{{ _local($product->name, $product->local_name) }}</h3>
        <p class="product-grid-desc">{{ Str::limit(strip_tags(_local($product->description, $product->local_description) ?? ''), 100) }}</p>
      </div>
    </a>
    <div class="product-grid-actions">
      @php
        $isInStock = ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0);
      @endphp
      @if($isInStock)
      <div class="cart-btn-modern product-grid-cart">
        @php
          $maxAttr = ($product->stock_status == 'limited') ? ' data-max="' . $product->stock_available . '"' : '';
          $emptyClass = !productExistsInCart($product->id) ? ' empty' : '';
          $minQuantity = ($product->minimum_quantity <= $product->unit->stepper) ? $product->unit->stepper : $product->minimum_quantity;
        @endphp
        <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $minQuantity }}"{!! $maxAttr !!} class="steper-btn-modern steper-btn-grid{{ $emptyClass }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          <span class="steper-btn-text-modern">{{ __('Add to Cart') }}</span>
          <div class="steper-btn-minus-modern">-</div>
          @if(productExistsInCart($product->id, $product->minimum_quantity) <= productExistsInCart($product->id, $product->unit->stepper))
<div class="steper-btn-value-modern">{{ productExistsInCart($product->id, $product->unit->stepper) ?: $product->unit->stepper }}</div>
@else
<div class="steper-btn-value-modern">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</div>
@endif
          <div class="steper-btn-plus-modern">+</div>
        </div>
      </div>
      @else
      @php $enquiryActive = \App\Models\Enquiry::where('user_id', authUser()->id ?? 0)->where('product_id', $product->id)->count() > 0 ? ' active' : ''; @endphp
      <div class="notify-btn-modern notify-btn-grid{{ $enquiryActive }}" act-on="click" act-request="{{ route('website.notify', ['product' => $product->id]) }}">{{ __('NOTIFY ME') }}</div>
      @endif
      @php $favouriteActive = \App\Models\Favourite::where('user_id', authUser()->id ?? 0)->where('product_id', $product->id)->count() > 0 ? ' active' : ''; @endphp
      <div class="fav-btn-modern fav-btn-grid{{ $favouriteActive }}" act-on="click" act-request="{{ route('website.favourite.toggle', ['product' => $product->id]) }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      </div>
    </div>
  </article>
  @endforeach
</div>
