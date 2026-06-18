@extends('frontend/layout/main')
@section('seo')
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}" />
<meta name="keywords" content="{{ $metaKeywords }}" />
<meta name="robots" content="index, follow">
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $metaTitle }}" />
<meta property="og:description" content="{{ $metaDescription }}" />
<meta property="og:image" content="{{ asset('assets/frontend/images/logo.png') }}" />
<meta property="og:url" content="{{ request()->url() }}" />
@endsection

@section('body')
@php $shopCategories = \App\Models\Category::whereNull('parent_id')->orderBy('priority', 'desc')->orderBy('name')->get(); @endphp
<div class="shop-luxury">
  <div class="shop-luxury-header">
    <div class="shop-luxury-container">
      @if($shopCategories->isNotEmpty())
      <nav class="shop-luxury-nav">
        <a href="{{ route('website.products.shop') }}" class="shop-luxury-nav-link {{ empty($slug) ? 'active' : '' }}">{{ __('All Materials') }}</a>
        @foreach($shopCategories as $cat)
        <a href="{{ route('website.products', ['slug' => $cat->slug]) }}" class="shop-luxury-nav-link {{ ($slug ?? '') === $cat->slug ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
      </nav>
      @endif
      <div class="shop-luxury-title-row" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <h1 class="shop-luxury-title">{{ $title }}</h1>
        <form method="GET" action="{{ route('website.products', ['slug' => $slug ?? '']) }}" class="shop-luxury-sort" style="margin-left: auto;">
          <input type="hidden" name="search" value="{{ request('search') }}">
          <input type="hidden" name="brand" value="{{ request('brand') }}">
          <select name="sortby" onchange="this.form.submit()" class="shop-luxury-select">
            <option value="featured" {{ request('sortby') === 'featured' ? 'selected' : '' }}>{{ __('Featured') }}</option>
            <option value="price-low-to-high" {{ request('sortby') === 'price-low-to-high' ? 'selected' : '' }}>{{ __('Price - Low to High') }}</option>
            <option value="price-high-to-low" {{ request('sortby') === 'price-high-to-low' ? 'selected' : '' }}>{{ __('Price - High to Low') }}</option>
          </select>
        </form>
      </div>
    </div>
  </div>

  <div class="shop-luxury-container">
    @if($products->count() > 0)
    <div class="shop-luxury-grid">
      @foreach($products as $product)
      <a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="shop-luxury-card">
        <div class="shop-luxury-image">
          @if($product->image)
          <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}">
          @else
          <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}">
          @endif
          <div class="shop-luxury-overlay"></div>
        </div>
        <div class="shop-luxury-info">
          @if($product->category)
          <span class="shop-luxury-tag">{{ $product->category->name }}</span>
          @endif
          <span class="shop-luxury-price">{!! currency() !!}{!! priceFormat($product->selling_price ?? $product->price ?? 0, '') !!}</span>
          <h3 class="shop-luxury-name">{{ _local($product->name, $product->local_name) }}</h3>
        </div>
      </a>
      @endforeach
    </div>

    @if($products->hasPages())
    @php $currentPage = $products->currentPage(); $lastPage = $products->lastPage(); $query = request()->only('brand', 'search', 'sortby'); @endphp
    <nav class="shop-luxury-pagination">
      @if($currentPage > 1)
      <a href="{{ $products->appends($query)->previousPageUrl() }}" class="shop-luxury-pag-link">&larr; {{ __('Previous') }}</a>
      @endif
      <span class="shop-luxury-pag-info">{{ $currentPage }} / {{ $lastPage }}</span>
      @if($currentPage < $lastPage)
      <a href="{{ $products->appends($query)->nextPageUrl() }}" class="shop-luxury-pag-link">{{ __('Next') }} &rarr;</a>
      @endif
    </nav>
    @endif
    @else
    <p class="shop-luxury-empty">{{ __('No materials found.') }}</p>
    @endif
  </div>
</div>
@endsection
