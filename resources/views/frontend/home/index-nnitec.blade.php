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
    <p class="luxury-hero-small">{{ __('Welcome to') }}</p>
    <h1 class="luxury-hero-title">{{ config('app.name') }}</h1>
    <p class="luxury-hero-sub">{{ __('A mobile-first bookstore for readers on the go') }}</p>
    <a href="{{ route('website.products.shop') }}" class="luxury-cta">{{ __('Browse Books') }}</a>
    <p class="luxury-scroll-hint">{{ __('Explore the shelves') }}</p>
  </section>

  {{-- Featured products --}}
  <section class="luxury-section" id="featured">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Featured Books') }}</h2>
      <div class="luxury-products">
        @forelse($featuredProducts->take(6) as $product)
        <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="luxury-product-card">
          @if($product->image)
          <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}">
          @else
          <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}">
          @endif
          <div class="luxury-product-overlay"></div>
          <div class="luxury-product-info">
            <span class="luxury-product-name">{{ _local($product->name, $product->local_name) }}</span>
            <span class="luxury-product-price">{!! currency() !!}{!! priceFormat($product->selling_price ?? $product->price ?? 0, '') !!}</span>
          </div>
        </a>
        @empty
        @for($i = 1; $i <= 6; $i++)
        <div class="luxury-product-card">
          <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="Book {{ $i }}">
          <div class="luxury-product-overlay"></div>
          <div class="luxury-product-info">
            <span class="luxury-product-name">{{ __('Recommended Read') }} {{ $i }}</span>
            <span class="luxury-product-price">{!! currency() !!}399</span>
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
      <h2 class="luxury-section-title">{{ __('Why Readers Choose Us') }}</h2>
      <div class="luxury-about-grid">
        <div class="luxury-about-text">
          <p>{{ __('We make book ordering simple on mobile, with clear categories, reliable pricing, and a checkout flow built for smaller screens.') }}</p>
          <p>{{ __('From leisure reading to school and work essentials, we curate titles that help readers discover the right next book faster.') }}</p>
          <a href="{{ route('website.about.us') }}" class="luxury-cta">{{ __('Learn More') }}</a>
        </div>
        <div class="luxury-about-image">
          <img src="{{ asset('assets/frontend/images/about-story.png') }}" alt="{{ __('Our bookstore') }}" onerror="this.src='{{ asset('assets/frontend/images/200x150-blank.png') }}'">
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
          <h3>{{ __('Curated Shelves') }}</h3>
          <p>{{ __('Browse fiction, children’s books, study titles, comics, and practical reads in one place.') }}</p>
        </div>
        <div class="luxury-story-card">
          <span class="luxury-story-num">02</span>
          <h3>{{ __('Fast Ordering') }}</h3>
          <p>{{ __('The storefront is designed to work smoothly on phones so readers can search and order quickly.') }}</p>
        </div>
        <div class="luxury-story-card">
          <span class="luxury-story-num">03</span>
          <h3>{{ __('Trusted Delivery') }}</h3>
          <p>{{ __('Track favourites, place repeat orders, and get books delivered with clear status updates.') }}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Request --}}
  <section class="luxury-custom" id="request">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Need a Specific Title?') }}</h2>
      <p>{{ __('Looking for a book that is not listed yet? Save the request and use your account or enquiry flow to ask for the title, publisher, or edition you need.') }}</p>
      <a href="{{ route('website.products.shop') }}" class="luxury-cta">{{ __('Start Browsing') }}</a>
    </div>
  </section>

  {{-- Shelf --}}
  <section class="luxury-section">
    <div class="luxury-container">
      <h2 class="luxury-section-title">{{ __('Browse the Shelves') }}</h2>
      <div class="luxury-gallery">
        @php $galleryProducts = collect($featuredProducts ?? [])->merge($offerProducts ?? [])->merge($priorityProducts ?? [])->unique('id')->take(8); @endphp
        @forelse($galleryProducts as $p)
        <a href="{{ route('website.product', ['slug' => $p->slug]) }}" class="luxury-gallery-item">
          @if($p->image)
          <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $p->image)) }}" alt="{{ $p->name }}">
          @else
          <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $p->name }}">
          @endif
        </a>
        @empty
        @for($i = 1; $i <= 8; $i++)
        <div class="luxury-gallery-item">
          <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="Shelf {{ $i }}">
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
@endsection
