@extends('frontend/layout/main')
@section('seo')
<title>{{ $compatibility == true ? $requestCompatible : $title }}</title>
<meta name="description" content="{{ $compatibility == true ? str_replace($title, $requestCompatible, $metaDescription) : $metaDescription }}" />
<meta name="keywords" content="{{ $compatibility == true ? str_replace($title, $requestCompatible, $metaKeywords) : $metaKeywords }}" />
<meta name="robots" content="index, follow">
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $compatibility == true ? $requestCompatible : $title }}" />
@if($product->image)
<meta property="og:image" content="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" />
@endif
<meta property="og:description" content="{{ $compatibility == true ? str_replace($title, $requestCompatible, $metaDescription) : $metaDescription }}" />
<meta property="og:url" content="{{ request()->url() }}" />
<script type="application/ld+json">
{"@@context":"https://schema.org/","@type":"Product","name":"{{ $compatibility == true ? $requestCompatible : $title }}",@if($product->image)"image":"{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}",@endif"description":"{{ $product->description ?? '' }}",@if($product->brand)"brand":{"@type":"Brand","name":"{{ $product->brand->name }}"},@endif"category":"{{ $product->category->name ?? '' }}","url":"{{ request()->url() }}",@if(productTotalPriceInCart($product->id, $product->price) > productTotalSellingPriceInCart($product->id, $product->selling_price))"offers":{"@type":"Offer","url":"{{ request()->url() }}","priceCurrency":"INR",@if(($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0))"availability":"https://schema.org/InStock",@endif"price":"{{ $product->selling_price }}"},@endif"sku":"{{ $product->product_code }}"}
</script>
@endsection

@section('body')
@php
  $pdpImages = [];
  if ($product->image) $pdpImages[] = $product->image;
  foreach ([1, 2, 3] as $n) {
    $f = 'gallery_image_' . $n;
    if (!empty($product->$f)) $pdpImages[] = $product->$f;
  }
  $hasGallery = count($pdpImages) > 1;
@endphp
<div class="pdp-luxury">
  <div class="knm-class-hero" aria-label="Class selector">
    <div class="knm-class-hero__inner">
      <div class="knm-class-hero__title">{{ __('Select Premium Materials') }}</div>
      <div class="knm-class-hero__subtitle">{{ __('Filter by category to find the perfect stone or marble for your project.') }}</div>
      <div class="knm-class-hero__glass">
        <label for="knm-class-select" class="knm-class-hero__label">{{ __('Select Category') }}</label>
        <select id="knm-class-select" name="class" aria-label="Select category" data-class-base-url="{{ route('website.products') }}" @if(empty($classes) || (is_object($classes) && method_exists($classes,'isEmpty') && $classes->isEmpty())) disabled @endif>
          <option value="">{{ (empty($classes) || (is_object($classes) && method_exists($classes,'isEmpty') && $classes->isEmpty())) ? __('No categories with materials yet') : __('Select category…') }}</option>
          @foreach(($classes ?? []) as $c)
            <option value="{{ $c['slug'] ?? '' }}">{{ $c['name'] ?? '' }} @if(isset($c['count']))({{ $c['count'] }} {{ __('items') }})@endif</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="pdp-luxury-grid">
    <div class="pdp-luxury-gallery">
      <div class="pdp-luxury-gallery-wrap">
        @if(count($pdpImages) > 0)
        <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $pdpImages[0])) }}" alt="{{ $product->name }}" class="pdp-luxury-main-img" id="pdp-luxury-main-img">
        @else
        <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="pdp-luxury-main-img" id="pdp-luxury-main-img">
        @endif
        @if($hasGallery)
        <div class="pdp-luxury-thumbs">
          @foreach($pdpImages as $idx => $img)
          <button type="button" class="pdp-luxury-thumb {{ $idx === 0 ? 'active' : '' }}" data-src="{{ asset('uploads/' . str_replace('/base/','/large/', $img)) }}" aria-label="{{ __('Image') }} {{ $idx + 1 }}">
            <img src="{{ asset('uploads/' . $img) }}" alt="">
          </button>
          @endforeach
        </div>
        @endif
      </div>
    </div>

    <div class="pdp-luxury-detail">
      <div class="pdp-luxury-top-row">
        <a href="{{ route('website.products.shop') }}" class="pdp-luxury-back">{{ __('Back to Materials') }}</a>
        @if($product->category)
        <span class="pdp-luxury-category">{{ $product->category->name }}</span>
        @endif
      </div>

      <h1 class="pdp-luxury-title">{{ _local($product->name, $product->local_name) }}</h1>

      <div class="pdp-luxury-price-row">
        <span class="pdp-luxury-price">{!! currency() !!}{!! priceFormat(productTotalSellingPriceInCart($product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper)), '') !!}</span>
        @if(($reviewsCount ?? 0) > 0)
        <span class="pdp-luxury-rating">★ {{ number_format($averageRating ?? 0, 1) }} <span class="pdp-luxury-rating-count">({{ $reviewsCount }})</span></span>
        @endif
      </div>

      <div class="pdp-luxury-desc">
        {!! nl2br($compatibility == true ? str_replace($title, $requestCompatible, e(_local($product->description, $product->local_description))) : e(_local($product->description, $product->local_description))) !!}
      </div>

      @if($product->attribute && $product->attribute->variants)
        @foreach($product->attribute->variants as $variants)
          @if($variants && $product->productGroupVariant($variants->id)->count() >= 1)
          <div class="pdp-luxury-variant">
            <p class="pdp-luxury-variant-label">{{ $variants->name }}</p>
            @php $variantOptions = $product->productGroupVariant($variants->id)->get(); @endphp
            @if($variantOptions->count() > 6)
            <select class="pdp-luxury-select" data-product-base-url="{{ url('/product') }}">
              @foreach($variantOptions as $option)
              <option value="{{ $option->product->slug }}" {{ $option->product->id == $product->id ? 'selected' : '' }}>{{ $option->variantOption->value }}</option>
              @endforeach
            </select>
            @else
            <div class="pdp-luxury-variant-btns">
              @foreach($variantOptions as $option)
              @if($option->product->id == $product->id)
              <span class="pdp-luxury-variant-btn active">{{ $option->variantOption->value }}</span>
              @else
              <a href="{{ route('website.product', ['slug' => $option->product->slug]) }}" class="pdp-luxury-variant-btn">{{ $option->variantOption->value }}</a>
              @endif
              @endforeach
            </div>
            @endif
          </div>
          @endif
        @endforeach
      @endif

      <div class="pdp-luxury-actions">
        @if(($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0))
        <button type="button" class="pdp-luxury-btn-cart btn-add-to-cart"
                data-product-id="{{ $product->id }}"
                data-price="{{ $product->price }}"
                data-selling-price="{{ $product->selling_price }}"
                data-steper="{{ $product->unit->stepper }}"
                data-min="{{ $product->unit->stepper }}">
          {{ __('ADD TO CART') }}
        </button>
        @else
        <button type="button" class="pdp-luxury-btn-cart btn-notify-me @if(auth()->check() && \App\Models\Enquiry::where('user_id', auth()->id())->where('product_id', $product->id)->count() > 0) active @endif" act-on="click" act-request="{{ route('website.notify', ['product' => $product->id]) }}">{{ __('NOTIFY ME') }}</button>
        @endif
        <button type="button" class="pdp-luxury-btn-wishlist btn-favorite @if($favouriteStatus ?? false) active @endif" act-on="click" act-request="{{ route('website.favourite.toggle', ['product' => $product->id]) }}" aria-label="{{ __('Favourite') }}">♡</button>
      </div>

      <div class="pdp-luxury-details">
        <p class="pdp-luxury-details-heading">{{ __('Material Details') }}</p>
        <dl class="pdp-luxury-specs">
          @if($product->brand)
          <div class="pdp-luxury-spec-row">
            <dt>{{ __('Brand') }}</dt>
            <dd>{{ $product->brand->name }}</dd>
          </div>
          @endif
          @if($product->category)
          <div class="pdp-luxury-spec-row">
            <dt>{{ __('Category') }}</dt>
            <dd>{{ $product->category->name }}</dd>
          </div>
          @endif
          @if($product->unit)
          <div class="pdp-luxury-spec-row">
            <dt>{{ __('Order Unit') }}</dt>
            <dd>{{ $product->unit->name }}</dd>
          </div>
          @endif
          @if($product->product_code ?? null)
          <div class="pdp-luxury-spec-row">
            <dt>{{ __('SKU') }}</dt>
            <dd>{{ $product->product_code }}</dd>
          </div>
          @endif
          <div class="pdp-luxury-spec-row">
            <dt>{{ __('Shipping') }}</dt>
            <dd>{{ getOption('shipping_time_text', __('7-10 days')) }}</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>
</div>
@endsection

@section('style')
<style>
.knm-class-hero{border-radius:20px;background:linear-gradient(115deg,#1f6bbd 0%,#19b7c8 100%);box-shadow:0 18px 45px rgba(0,0,0,.12);margin:4px 0 22px;overflow:hidden}
.knm-class-hero__inner{padding:32px 28px;text-align:center;color:#fff}
.knm-class-hero__title{font-family:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;font-size:34px;line-height:1.15;font-weight:800;letter-spacing:-.02em;margin:0 0 8px}
.knm-class-hero__subtitle{font-size:14px;opacity:.9;margin:0 0 18px}
.knm-class-hero__glass{max-width:520px;margin:0 auto;padding:14px 14px 12px;border-radius:14px;background:rgba(255,255,255,.18);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.22)}
.knm-class-hero__label{display:block;text-align:left;font-size:12px;opacity:.95;margin:0 0 6px;font-weight:700}
#knm-class-select{width:100%;appearance:none;-webkit-appearance:none;padding:12px 14px;border-radius:10px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.95);color:#0f172a;font-weight:600;outline:none}
#knm-class-select:disabled{opacity:.65;cursor:not-allowed}
@media (max-width: 768px){
  .knm-class-hero__inner{padding:26px 18px}
  .knm-class-hero__title{font-size:28px}
}
</style>
@endsection

@section('script')
<script>
var knmClassSelect = document.getElementById('knm-class-select');
if (knmClassSelect) {
  knmClassSelect.addEventListener('change', function () {
    var slug = (this.value || '').trim();
    if (!slug) return;
    var base = this.getAttribute('data-class-base-url') || '{{ route('website.products') }}';
    window.location.href = base.replace(/\/$/, '') + '/' + encodeURIComponent(slug);
  });
}

document.querySelectorAll('.pdp-luxury-detail .btn-add-to-cart').forEach(function(btn) {
  btn.addEventListener('click', function() {
    if (typeof window.cookies === 'undefined' || !window.cookies.set) return;
    if (4096 <= new Blob([document.cookie]).size) return;
    var id = btn.getAttribute('data-product-id');
    var price = btn.getAttribute('data-price');
    var sellingPrice = btn.getAttribute('data-selling-price');
    var steper = parseFloat(btn.getAttribute('data-steper')) || 1;
    var minQty = parseFloat(btn.getAttribute('data-min')) || 1;
    var quantity = minQty;
    var cart = { products: {} };
    try {
      var cartJSON = document.cookie.split('; ').find(function(row) { return row.startsWith('__cart='); });
      if (cartJSON) {
        cartJSON = decodeURIComponent(cartJSON.split('=').slice(1).join('='));
        var parsed = JSON.parse(cartJSON);
        if (parsed && parsed.products) cart = parsed;
      }
    } catch (e) {}
    cart.products[id] = { price: price, selling_price: sellingPrice, quantity: quantity, steper: steper, total_price: parseFloat(price) * (quantity / steper), total_selling_price: parseFloat(sellingPrice) * (quantity / steper), message: '' };
    window.cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });
    var countEl = document.querySelector('.luxury-cart-count') || document.querySelector('.cart-item-count');
    if (countEl) countEl.textContent = Object.keys(cart.products).length;
    window.location.href = '{{ route("website.cart") }}';
  });
});
document.querySelectorAll('.pdp-luxury-select').forEach(function(select) {
  select.addEventListener('change', function() {
    var base = this.getAttribute('data-product-base-url') || '{{ url("/product") }}';
    window.location.href = base + '/' + this.value;
  });
});
document.querySelectorAll('.pdp-luxury-thumb').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var src = this.getAttribute('data-src');
    var main = document.getElementById('pdp-luxury-main-img');
    if (src && main) main.src = src;
    document.querySelectorAll('.pdp-luxury-thumb').forEach(function(t) { t.classList.remove('active'); });
    this.classList.add('active');
  });
});
</script>
@endsection
