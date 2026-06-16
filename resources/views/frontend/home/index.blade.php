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
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org/',
  '@type' => 'WebSite',
  'name' => getOption('website_meta_title'),
  'url' => route('home'),
  'potentialAction' => [
      '@type' => 'SearchAction',
      'target' => route('website.products') . '?search={search_term_string}',
      'query-input' => 'required name=search_term_string',
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => getOption('website_meta_title'),
  'url' => route('home'),
  'logo' => asset('assets/frontend/images/logo.png'),
  'contactPoint' => [[
      '@type' => 'ContactPoint',
      'telephone' => getOption('order_enquiry_number'),
      'contactType' => 'sales',
      'availableLanguage' => 'en',
  ]],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

@endsection

@section('body')
@if($bannerSliders->count() > 0)
<!-- ========================= HERO BANNER ========================= -->
<div class="hero-wrapper">
<section class="hero-banner-section hero-section">
    <div class="hero-banner-container">
        <div class="hero-banner-inner">
            <div class="hero-banner-media">
                <div class="intro-banner-wrap banner-carousel owl-carousel">
                    @foreach($bannerSliders as $bannerSlider)
                    <div
                        class="hero-banner-slide"
                        style="background-image: url({{ asset('uploads/' . str_replace('/base/','/large/', $bannerSlider->image)) }});"
                        role="img"
                        aria-label="{{ $bannerSlider->name }}"
                    ></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<!-- ========================= HERO BANNER END// ========================= -->
@endif

{{-- Dynamic categories strip (mobile-first) --}}
@include('frontend.home.partials.categories-strip', ['homeCategories' => $homeCategories ?? collect()])

@if($featuredProducts->count() > 0 )

<!-- ========================= FEATURED PAINTINGS ========================= -->
<section class="section-content home-page-product-listing painting-section">
    <div class="container">

        <header class="section-heading painting-section-heading">
            <h2 class="section-title">{{ __('Featured Paintings') }}</h2>
        </header><!-- sect-heading -->

        <div class="row product-grid-home">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3 mb-3">
                @include('frontend.home.partials.product-card-grid', ['product' => $product])
            </div>
            @endforeach
        </div>

    </div> <!-- container .//  -->
</section>
<!-- ========================= SECTION CONTENT END// ========================= -->

@endif
 



@if($offerProducts->count() > 0 )

<!-- ========================= SPECIAL OFFERS ========================= -->
<section class="section-content offers-deals-section painting-section">
    <div class="container">
        <header class="section-heading offers-deals-section__heading painting-section-heading">
            <h2 class="section-title">{{ __('Special Offers') }}</h2>
        </header>
        <div class="offers-deals-grid">
            @foreach($offerProducts as $product)
            <div class="offers-deals-grid__item">
                @include('frontend.home.partials.product-card-grid', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>

@endif

@if(isset($dailyOffers) && $dailyOffers->count() > 0)
<!-- ========================= DAILY OFFER (2 banners side by side) ========================= -->
<section class="section-content daily-offer-section">
    <div class="container">
        <header class="section-heading daily-offer-section__heading">
            <h2 class="section-title">{{ __('Daily Offer') }}</h2>
        </header>
        <div class="daily-offer-grid">
            @foreach($dailyOffers as $offer)
            <div class="daily-offer-grid__item">
                @if($offer->link)
                    <a href="{{ $offer->link }}" class="daily-offer-card">
                        <img src="{{ asset('uploads/' . $offer->image) }}" alt="{{ $offer->title ?: __('Daily Offer') }}" class="daily-offer-card__image">
                    </a>
                @else
                    <div class="daily-offer-card">
                        <img src="{{ asset('uploads/' . $offer->image) }}" alt="{{ $offer->title ?: __('Daily Offer') }}" class="daily-offer-card__image">
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($priorityProducts) && $priorityProducts->count() > 0)
<!-- ========================= CURATOR'S PICKS ========================= -->
<section class="section-content special-products-section painting-section">
    <div class="container">
        <header class="section-heading special-products-section__heading painting-section-heading">
            <h2 class="section-title">{{ __('Curator\'s Picks') }}</h2>
        </header>
        <div class="special-products-grid">
            @foreach($priorityProducts as $product)
            <div class="special-products-grid__item">
                @include('frontend.home.partials.product-card-horizontal', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@php
    $spotlightProducts = collect();
    if(isset($homeSpotlights) && $homeSpotlights->count() > 0) {
        $spotlightProducts = $homeSpotlights->map(function($spotlight) {
            return $spotlight->product;
        })->filter();
    } elseif(isset($homeAppliancesProducts) && $homeAppliancesProducts->count() > 0) {
        $spotlightProducts = $homeAppliancesProducts;
    }
@endphp

@if($spotlightProducts->count() > 0)
    <!-- ========================= HOME APPLIANCES SPONSORED SPOTLIGHT (rotates every 5s) ========================= -->
    <section class="section-content home-appliances-spotlight-section">
        <div class="container">
            <div class="home-appliances-spotlight-carousel owl-carousel owl-theme">
                @foreach($spotlightProducts as $product)
                    @if($product)
                        <div class="item">
                            @include('frontend.home.partials.product-spotlight-slide', ['product' => $product])
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif

@if(isset($homeAppliancesProducts) && $homeAppliancesProducts->count() > 0)
    <!-- ========================= COLLECTION HIGHLIGHTS ========================= -->
    <section class="section-content home-appliances-section painting-section">
        <div class="container">
            <header class="section-heading home-appliances-section__heading painting-section-heading">
                <h2 class="section-title">{{ __('Collection Highlights') }}</h2>
            </header>
            <div class="home-appliances-grid">
                @foreach($homeAppliancesProducts as $product)
                    <div class="home-appliances-grid__item">
                        @include('frontend.home.partials.product-card-featured', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

