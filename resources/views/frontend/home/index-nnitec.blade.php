@extends('frontend/layout/main')
@section('seo')
<title>{{ getOption('website_meta_title') }}</title>
<meta name="description" content="{{ getOption('website_meta_description') }}" />
<meta name="keywords" content="{{ getOption('website_meta_keywords') }}" />
<meta name="robots" content="index, follow">
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ getOption('website_meta_title') }}" />
<meta property="og:image" content="{{ asset('assets/frontend/images/logo.png') }}" />
<meta property="og:description" content="{{ getOption('website_meta_description') }}" />
<meta property="og:url" content="{{ route('home') }}" />
@endsection

@section('body')
<div class="luxury-page">
  @include('frontend.layout.header-luxury')

  {{-- Full-screen hero --}}
  <section class="luxury-hero" id="hero">
    <h1 class="luxury-hero-title">{{ config('app.name') }}</h1>
    <p class="luxury-hero-sub">{{ __('A premium gallery for architectural stone and interior design') }}</p>
    <a href="{{ route('website.products.shop') }}" class="luxury-cta">{{ __('Browse Collections') }}</a>
    <p class="luxury-scroll-hint">{{ __('Explore the gallery') }}</p>
  </section>

  {{-- Featured products --}}
  <section class="luxury-section" id="featured">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Featured Materials') }}</h2>
      <div class="luxury-products">
        @forelse($featuredProducts->take(6) as $product)
        <div class="luxury-product-card">
          <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="luxury-product-image-link" aria-label="{{ $product->name }}">
            @if($product->image)
            <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}">
            @else
            <img src="{{ asset('assets/frontend/images/stone-' . (($loop->index) % 4 + 1) . '.png') }}" alt="{{ $product->name }}">
            @endif
            <div class="luxury-product-overlay"></div>
          </a>
          <div class="luxury-product-info">
            @if($product->category)
            <span class="luxury-product-tag">{{ $product->category->name }}</span>
            @endif
            <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="luxury-product-name-link">
              <h3 class="luxury-product-name">{{ _local($product->name, $product->local_name) }}</h3>
            </a>
            
            <div class="luxury-product-rating" style="display: flex; align-items: center; gap: 4px; font-size: 13px; color: #ffc107;">
              <span>★</span>
              <span style="color: var(--mg-text-muted); font-weight: 500;">4.5</span>
            </div>
            
            <div class="luxury-product-meta-row">
              <span class="luxury-product-price">{!! currency() !!}{!! priceFormat($product->selling_price ?? $product->price ?? 0, '') !!}</span>
              
              @php
                $favouriteActive = \App\Models\Favourite::where('user_id', authUser()->id ?? 0)->where('product_id', $product->id)->count() > 0 ? ' active' : '';
              @endphp
              <button type="button" class="fav-btn-modern fav-btn-list{{ $favouriteActive }}" act-on="click" act-request="{{ route('website.favourite.toggle', ['product' => $product->id]) }}" aria-label="{{ __('Favourite') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>

            @php
              $isInStock = ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0);
            @endphp
            @if($isInStock)
            <div class="cart-btn-modern luxury-product-cart-wrap" style="margin-top: 6px; width: 100%;">
              @php
                $maxAttr = ($product->stock_status == 'limited') ? ' data-max="' . $product->stock_available . '"' : '';
                $emptyClass = !productExistsInCart($product->id) ? ' empty' : '';
                $minQuantity = ($product->minimum_quantity <= $product->unit->stepper) ? $product->unit->stepper : $product->minimum_quantity;
              @endphp
              <button type="button" data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $minQuantity }}"{!! $maxAttr !!} class="steper-btn-modern steper-btn-list{{ $emptyClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="cart-icon"><path d="M7 18C5.895 18 5.01 18.895 5.01 20C5.01 21.105 5.895 22 7 22C8.105 22 9 21.105 9 20C9 18.895 8.105 18 7 18ZM1 2V4H3L6.595 11.585L5.245 14.035C5.09 14.325 5 14.65 5 15C5 16.105 5.895 17 7 17H19V15H7.425C7.285 15 7.175 14.89 7.175 14.75L8.1 13H15.55C16.3 13 16.955 12.585 17.3 11.97L20.875 5.48C20.955 5.34 21 5.175 21 5C21 4.445 20.55 4 20 4H5.215L4.265 2H1ZM17 18C15.895 18 15.01 18.895 15.01 20C15.01 21.105 15.895 22 17 22C18.105 22 19 21.105 19 20C19 18.895 18.105 18 17 18Z"/></svg>
                <span class="steper-btn-text-modern">{{ __('Add to Cart') }}</span>
                <div class="steper-btn-minus-modern">-</div>
                <div class="steper-btn-value-modern">{{ productExistsInCart($product->id, $minQuantity) }}</div>
                <div class="steper-btn-plus-modern">+</div>
              </button>
            </div>
            @else
            @php
              $enquiryActive = \App\Models\Enquiry::where('user_id', authUser()->id ?? 0)->where('product_id', $product->id)->count() > 0 ? ' active' : '';
            @endphp
            <button type="button" class="notify-btn-modern notify-btn-list{{ $enquiryActive }}" act-on="click" act-request="{{ route('website.notify', ['product' => $product->id]) }}" style="margin-top: 6px; width: 100%; border: 1px solid #d1d5db; background: #ffffff; color: var(--mg-navy); padding: 8px; font-weight: 600; font-size: 13px;">{{ __('NOTIFY ME') }}</button>
            @endif
          </div>
        </div>
        @empty
        @for($i = 1; $i <= 6; $i++)
        <div class="luxury-product-card">
          <div class="luxury-product-image-link">
            <img src="{{ asset('assets/frontend/images/stone-' . (($i - 1) % 4 + 1) . '.png') }}" alt="Stone {{ $i }}">
            <div class="luxury-product-overlay"></div>
          </div>
          <div class="luxury-product-info">
            <span class="luxury-product-tag">{{ __('Premium Slab') }}</span>
            <h3 class="luxury-product-name">{{ __('Premium Stone') }} {{ $i }}</h3>
            <div class="luxury-product-rating" style="display: flex; align-items: center; gap: 4px; font-size: 13px; color: #ffc107;">
              <span>★</span>
              <span style="color: var(--mg-text-muted); font-weight: 500;">4.5</span>
            </div>
            <div class="luxury-product-meta-row">
              <span class="luxury-product-price">{!! currency() !!}399</span>
              <button type="button" class="fav-btn-modern fav-btn-list" aria-label="{{ __('Favourite') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="cart-btn-modern luxury-product-cart-wrap" style="margin-top: 6px; width: 100%;">
              <button type="button" class="steper-btn-modern steper-btn-list empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="cart-icon"><path d="M7 18C5.895 18 5.01 18.895 5.01 20C5.01 21.105 5.895 22 7 22C8.105 22 9 21.105 9 20C9 18.895 8.105 18 7 18ZM1 2V4H3L6.595 11.585L5.245 14.035C5.09 14.325 5 14.65 5 15C5 16.105 5.895 17 7 17H19V15H7.425C7.285 15 7.175 14.89 7.175 14.75L8.1 13H15.55C16.3 13 16.955 12.585 17.3 11.97L20.875 5.48C20.955 5.34 21 5.175 21 5C21 4.445 20.55 4 20 4H5.215L4.265 2H1ZM17 18C15.895 18 15.01 18.895 15.01 20C15.01 21.105 15.895 22 17 22C18.105 22 19 21.105 19 20C19 18.895 18.105 18 17 18Z"/></svg>
                <span class="steper-btn-text-modern">{{ __('Add to Cart') }}</span>
              </button>
            </div>
          </div>
        </div>
        @endfor
        @endforelse
      </div>
    </div>
  </section>

  {{-- About --}}
  <section class="luxury-section luxury-about">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Why Designers Choose Us') }}</h2>
      <div class="luxury-about-grid">
        <div class="luxury-about-text">
          <p>{{ __('We make stone selection simple, with clear categories, premium finishes, and direct inquiry/ordering options.') }}</p>
          <p>{{ __('From premium marble panels to custom granite fittings, we curate high-quality materials to elevate your architectural spaces.') }}</p>
          <a href="{{ route('website.about.us') }}" class="luxury-cta">{{ __('Learn More') }}</a>
        </div>
        <div class="luxury-about-image">
          <img src="{{ asset('assets/frontend/images/about-story.png') }}" alt="{{ __('Our showroom') }}" onerror="this.src='{{ asset('assets/frontend/images/200x150-blank.png') }}'">
        </div>
      </div>
    </div>
  </section>

  {{-- Reasons --}}
  <section class="luxury-section">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Why Shop Here') }}</h2>
      <div class="luxury-stories">
        <div class="luxury-story-card">
          <span class="luxury-story-num">01</span>
          <h3>{{ __('Curated Collections') }}</h3>
          <p>{{ __('Browse marble, granite, quartz, quartzite, and exotic stone variants all in one place.') }}</p>
        </div>
        <div class="luxury-story-card">
          <span class="luxury-story-num">02</span>
          <h3>{{ __('Direct Inquiries') }}</h3>
          <p>{{ __('Our platform is optimized for both desktop and mobile, allowing fast material discovery and inquiries.') }}</p>
        </div>
        <div class="luxury-story-card">
          <span class="luxury-story-num">03</span>
          <h3>{{ __('Premium Logistics') }}</h3>
          <p>{{ __('Save favorites, track your materials, and request secure shipping and installation estimates.') }}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Request --}}
  <section class="luxury-custom" id="request">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Looking for a Custom Finish?') }}</h2>
      <p>{{ __('Need a specific material size, cut, or premium stone pattern? Send us an inquiry and our team will source it for you.') }}</p>
      <a href="{{ route('website.products.shop') }}" class="luxury-cta">{{ __('Start Exploring') }}</a>
    </div>
  </section>

  {{-- Shelf --}}
  <section class="luxury-section">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Explore the Gallery') }}</h2>
      <div class="luxury-gallery">
        @php $galleryProducts = collect($featuredProducts ?? [])->merge($offerProducts ?? [])->merge($priorityProducts ?? [])->unique('id')->take(8); @endphp
        @forelse($galleryProducts as $p)
        <a href="{{ route('website.product', ['slug' => $p->slug]) }}" class="luxury-gallery-item">
          @if($p->image)
          <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $p->image)) }}" alt="{{ $p->name }}">
          @else
          <img src="{{ asset('assets/frontend/images/stone-' . (($loop->index) % 4 + 1) . '.png') }}" alt="{{ $p->name }}">
          @endif
          <div class="luxury-gallery-hover-overlay">
            <div class="luxury-gallery-hover-content">
              @if($p->category)
              <span class="luxury-gallery-category-tag">{{ $p->category->name }}</span>
              @endif
              <h3 class="luxury-gallery-title-text">{{ _local($p->name, $p->local_name) }}</h3>
              @if($p->brand)
              <span class="luxury-gallery-origin-tag">{{ __('Origin: ') }}{{ $p->brand->name }}</span>
              @endif
              <span class="luxury-gallery-view-btn">{{ __('View Details') }} &rarr;</span>
            </div>
          </div>
        </a>
        @empty
        @for($i = 1; $i <= 8; $i++)
        <div class="luxury-gallery-item">
          <img src="{{ asset('assets/frontend/images/stone-' . (($i - 1) % 4 + 1) . '.png') }}" alt="Gallery Stone {{ $i }}">
          <div class="luxury-gallery-hover-overlay">
            <div class="luxury-gallery-hover-content">
              <span class="luxury-gallery-category-tag">{{ __('Premium Slab') }}</span>
              <h3 class="luxury-gallery-title-text">{{ __('Premium Stone') }} {{ $i }}</h3>
              <span class="luxury-gallery-origin-tag">{{ __('Origin: Brazil') }}</span>
              <span class="luxury-gallery-view-btn">{{ __('View Details') }} &rarr;</span>
            </div>
          </div>
        </div>
        @endfor
        @endforelse
      </div>
    </div>
  </section>
</div>

<script>
(function(){
  var nav = document.getElementById('luxuryNav');
  var menu = document.getElementById('luxuryMenu');
  var toggle = document.getElementById('luxuryMenuToggle');
  if (nav) {
    function updateNav() { nav.classList.toggle('scrolled', window.scrollY > 50); }
    updateNav();
    window.addEventListener('scroll', updateNav);
  }
  if (toggle && menu) {
    toggle.addEventListener('click', function() {
      menu.classList.toggle('open');
      toggle.classList.toggle('active');
      document.body.classList.toggle('luxury-menu-open', menu.classList.contains('open'));
    });
    menu.querySelectorAll('a').forEach(function(a) {
      a.addEventListener('click', function() {
        menu.classList.remove('open');
        toggle.classList.remove('active');
        document.body.classList.remove('luxury-menu-open');
      });
    });
  }
})();
</script>

<script>
// Handle modern stepper buttons - Add to Cart functionality on Home Page
(function() {
    function initCartButtons() {
        if (typeof jQuery === 'undefined' || typeof window.$ === 'undefined') {
            setTimeout(initCartButtons, 100);
            return;
        }
        var $ = window.jQuery || window.$;
        
        setTimeout(function() {
            function getDecimalFix() {
                if (typeof window.decimalFix === 'function') {
                    return window.decimalFix;
                }
                return function(value) {
                    var valueString = String(value);
                    if (valueString.includes('.')) {
                        if(valueString.split('.')[1].length == 1){
                            return parseFloat(value).toFixed(1);
                        }else{
                            return parseFloat(value).toFixed(2);
                        }
                    }
                    return value;
                };
            }
            
            function getCookies() {
                return window.cookies || cookies || null;
            }
            
            $(document).off('click', '.steper-btn-modern').on('click', '.steper-btn-modern', function(e){
                e.preventDefault();
                e.stopPropagation();
                
                var $btn = $(this);
                var cookiesHelper = getCookies();
                var decimalFix = getDecimalFix();
                
                if (!cookiesHelper) {
                    cookiesHelper = window.cookies || cookies;
                    if (!cookiesHelper) {
                        alert('Unable to add to cart. Please refresh the page.');
                        return false;
                    }
                }
                
                var id = $btn.attr('data-id');
                var sellingPrice = $btn.attr('data-selling-price');
                var price = $btn.attr('data-price');
                var steper = $btn.attr('data-steper');
                var min = $btn.attr('data-min');
                var max = $btn.attr('data-max');
                
                if (!id || !sellingPrice || !price || !steper) {
                    return false;
                }
                
                var cart = { "products": {} };
                try {
                    var cartData = cookiesHelper.get('__cart');
                    if (cartData) {
                        var cartJSON = $.parseJSON(cartData);
                        if(cartJSON && cartJSON.products){
                            cart = cartJSON;
                        }
                    }
                } catch(err) {}
                
                var currentQuantity = 0;
                if(cart.products[id]) {
                    currentQuantity = parseFloat(cart.products[id].quantity);
                }
                
                var newQuantity = currentQuantity > 0 ? currentQuantity + parseFloat(steper) : parseFloat(min);
                if(max && parseFloat(newQuantity) > parseFloat(max)) {
                    $btn.addClass('shake');
                    setTimeout(function() { $btn.removeClass('shake'); }, 1000);
                    return false;
                }
                
                cart.products[id] = {         
                    'price' : price,     
                    'selling_price' : sellingPrice,
                    'quantity' : parseFloat(newQuantity),
                    'steper' : steper,
                    'total_price' : parseFloat(price) * ( parseFloat(newQuantity) / parseFloat(steper) ),
                    'total_selling_price' : parseFloat(sellingPrice) * ( parseFloat(newQuantity) / parseFloat(steper) ),
                    'message' : ''
                };
                
                $('.cart-item-count').html( Object.keys( cart.products ).length );
                var countEl = document.querySelector('.luxury-cart-count');
                if (countEl) countEl.textContent = Object.keys(cart.products).length;
                
                try {
                    cookiesHelper.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });
                } catch(err) {}
                
                var cartUrl = $('meta[name="cart-url"]').attr('content') || '/cart';
                window.location.href = cartUrl;
                return false;
            });
        }, 500);
    }
    initCartButtons();
})();
</script>
@endsection
