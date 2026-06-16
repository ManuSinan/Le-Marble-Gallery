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
 
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ $title }}</h1>
        <nav> 
            <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{ route('home') }}">
                        <span itemprop="name">{{ __('Home') }}</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li  class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{ request()->url() }}">
                        <span class="text-dark" itemprop="name">{{ $title }}</span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
            </ol>
        </nav>
   </div>
</section>

<section class="section-content my-4">
<div class="container">

<div class="row">
	<aside class="col-md-3 mb-4 order-2 order-md-1 @if($products->count() == 0) d-none d-md-block @endif">
        @if($category)
            @php
                // Get the top-level category
                $topLevelCategory = $category->parent ? $category->parent : $category;
        
                // Get all subcategories of the top-level category
                $subCategories = $topLevelCategory->children;
            @endphp
        
            @if($subCategories->isNotEmpty())
                <div class="card product-subcategories-card mb-4">
                    <div class="filter-group">
                        <header class="card-header product-subcategories-header">
                            <h6 class="product-subcategories-title">{{ __('SUB-CATEGORIES') }}</h6>
                        </header>
                        <div class="filter-content">
                            <div class="card-body product-subcategories-body">
                                <ul class="list-menu product-subcategories-list">
                                    @foreach($subCategories as $subCategory)
                                        <li class="product-subcategories-item {{ $subCategory->slug === $slug ? 'active' : '' }}">
                                            <a href="{{ route('website.products', ['slug' => $subCategory->slug, 'sortby' => $sortby, 'search' => $search]) }}" class="product-subcategories-link">
                                                {{ $subCategory->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div> <!-- card-body.// -->
                        </div>
                    </div> <!-- filter-group  .// -->
                </div> 
            @endif
        @endif
    
    
        
        <div class="card filter-by-brand-card d-none d-md-block">
            <div class="filter-by-brand">
                <div class="filter-by-brand-header">
                    <h6 class="filter-by-brand-title">{{ __('Filter by Brand') }}</h6>
                    <a href="{{ route('website.products', ['brand' => '', 'sortby' => $sortby, 'search' => $search, 'slug' => $slug]) }}" class="filter-by-brand-clear-link">{{ __('Clear Filters') }}</a>
                </div>
                <div class="filter-by-brand-search-wrap">
                    <span class="filter-by-brand-search-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="text" class="filter-by-brand-search" placeholder="{{ __('Search brand...') }}" autocomplete="off">
                </div>
                <div class="filter-by-brand-list-wrap">
                    <ul class="filter-by-brand-list">
                        <li class="filter-by-brand-item filter-by-brand-item-all {{ empty($brandSlug) ? 'selected' : '' }}" data-brand-name="">
                            <a href="{{ route('website.products', ['brand' => '', 'sortby' => $sortby, 'search' => $search, 'slug' => $slug]) }}" class="filter-by-brand-row">
                                <span class="filter-by-brand-checkbox {{ empty($brandSlug) ? 'checked' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                <span class="filter-by-brand-name">{{ __('All Brands') }}</span>
                                <span class="filter-by-brand-circle {{ empty($brandSlug) ? 'selected' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </span>
                            </a>
                        </li>
                        @php $brandShowLimit = 8; $brandIndex = 0; @endphp
                        @foreach($brands as $brand)
                        <li class="filter-by-brand-item {{ $brandSlug === $brand->slug ? 'selected' : '' }} {{ $brandIndex >= $brandShowLimit ? 'filter-by-brand-item-more' : '' }}" data-brand-name="{{ strtolower($brand->name) }}">
                            <a href="{{ route('website.products', ['brand' => $brand->slug, 'sortby' => $sortby, 'search' => $search, 'slug' => $slug]) }}" class="filter-by-brand-row">
                                <span class="filter-by-brand-checkbox {{ $brandSlug === $brand->slug ? 'checked' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                <span class="filter-by-brand-name">{{ $brand->name }}</span>
                                <span class="filter-by-brand-circle {{ $brandSlug === $brand->slug ? 'selected' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </span>
                            </a>
                        </li>
                        @php $brandIndex++; @endphp
                        @endforeach
                    </ul>
                    @if($brands->count() > $brandShowLimit)
                    <button type="button" class="filter-by-brand-show-more" data-expanded="false">{{ __('Show More') }}</button>
                    @endif
                </div>
                <div class="filter-by-brand-actions">
                    <a href="{{ route('website.products', ['brand' => '', 'sortby' => $sortby, 'search' => $search, 'slug' => $slug]) }}" class="filter-by-brand-clear-all-btn">{{ __('Clear All') }}</a>
                </div>
            </div>
        </div>

	</aside> <!-- col.// -->
	<main id="product-listing-main" class="col-md-9 mb-4 order-1 order-md-2 inner-page-product-listing {{ request()->has('page') ? 'product-listing-paginated' : '' }}"
	      data-pagination-page="{{ request()->get('page', 1) }}">

        <header class="product-listing-header border-bottom mb-4 pb-3">
                <div class="product-listing-header-inner form-inline">
                    <span class="product-listing-count mr-md-auto">{{ $products->total() .  __(' Items found') }}</span>
 
                    <form class="product-listing-sort-form" method="GET" action="{{ route('website.products', ['slug' => $slug ?? '']) }}">
                        <!-- Hidden inputs to preserve search and brand parameters -->
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="brand" value="{{ request('brand') }}">
                    
                        <select name="sortby" class="product-listing-sort-select" onchange="this.form.submit()">
                            <option value="featured" {{ request('sortby') === 'featured' ? 'selected' : '' }}>
                                {{ __('Featured') }}
                            </option>
                            <option value="price-low-to-high" {{ request('sortby') === 'price-low-to-high' ? 'selected' : '' }}>
                                {{ __('Price - Low to High') }}
                            </option>
                            <option value="price-high-to-low" {{ request('sortby') === 'price-high-to-low' ? 'selected' : '' }}>
                                {{ __('Price - High to Low') }}
                            </option>
                        </select>
                    </form>
                    
                </div>
        </header><!-- sect-heading -->

    @if($products->count() > 0)
        @include('frontend/product/list-grid') 
    @else
        <div class="row">
            <div class="col-md-12">
                {{ __('No products found.') }}
            </div>
        </div>
    @endif

    @if($products->hasPages())
        {{-- Keep Laravel's default paginator hidden (in case it's needed later) --}}
        <div class="d-none">
            {{ $products->appends(['brand' => request('brand'), 'search' => request('search')])->links() }}
        </div>

        {{-- Custom clean paginator --}}
        @php
            $currentPage = $products->currentPage();
            $lastPage = $products->lastPage();
            $query = request()->only('brand', 'search', 'sortby');
        @endphp
        <nav class="product-pagination-wrapper mt-3" aria-label="Pagination">
            <ul class="pagination product-pagination justify-content-center flex-wrap mb-0">
                {{-- Previous --}}
                <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                    @if($currentPage <= 1)
                        <span class="page-link">&laquo; {{ __('Previous') }}</span>
                    @else
                        <a class="page-link" href="{{ $products->appends($query)->previousPageUrl() }}" rel="prev">&laquo; {{ __('Previous') }}</a>
                    @endif
                </li>

                {{-- Page numbers (simple 1..last) --}}
                @for($page = 1; $page <= $lastPage; $page++)
                    <li class="page-item {{ $page === $currentPage ? 'active' : '' }}">
                        @if($page === $currentPage)
                            <span class="page-link">{{ $page }}</span>
                        @else
                            <a class="page-link" href="{{ $products->appends($query)->url($page) }}">{{ $page }}</a>
                        @endif
                    </li>
                @endfor

                {{-- Next --}}
                <li class="page-item {{ $currentPage >= $lastPage ? 'disabled' : '' }}">
                    @if($currentPage >= $lastPage)
                        <span class="page-link">{{ __('Next') }} &raquo;</span>
                    @else
                        <a class="page-link" href="{{ $products->appends($query)->nextPageUrl() }}" rel="next">{{ __('Next') }} &raquo;</a>
                    @endif
                </li>
            </ul>
        </nav>
    @endif

	</main> <!-- col.// -->

</div>

</div> <!-- container .//  -->
</section>

@endsection

@section('style')
<style>
/* Sub-categories sidebar */
.product-subcategories-card { border: 1px solid #e9e9eb; border-radius: 8px; overflow: hidden; }
.product-subcategories-header { background: #f8f9fa; border-bottom: 1px solid #e9e9eb; padding: 12px 16px; }
.product-subcategories-title { font-size: 12px; font-weight: 700; letter-spacing: 0.5px; color: #282c3f; margin: 0; text-transform: uppercase; }
.product-subcategories-body { padding: 12px 16px; }
.product-subcategories-list { list-style: none; padding: 0; margin: 0; }
.product-subcategories-item { margin: 0; border-bottom: 1px solid #f0f0f2; }
.product-subcategories-item:last-child { border-bottom: none; }
.product-subcategories-link { display: block; padding: 10px 0 10px 12px; font-size: 14px; color: #535766; text-decoration: none; transition: color 0.2s ease; }
.product-subcategories-link:hover { color: #c98a25; }
.product-subcategories-item.active .product-subcategories-link { color: #c98a25; font-weight: 600; padding-left: 9px; border-left: 3px solid #c98a25; }

/* Filter by Brand */
.filter-by-brand-card { border: 1px solid #e9e9eb; border-radius: 8px; overflow: hidden; }
.filter-by-brand-card .card-body { padding: 0; }
.filter-by-brand { padding: 1rem 1.25rem; background: #fff; }
.filter-by-brand-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem; }
.filter-by-brand-title { font-size: 12px; font-weight: 700; letter-spacing: 0.5px; color: #282c3f; margin: 0; text-transform: uppercase; }
.filter-by-brand-clear-link { color: #c98a25; font-size: 0.8125rem; font-weight: 500; text-decoration: none; }
.filter-by-brand-clear-link:hover { color: #a6721e; text-decoration: underline; }
.filter-by-brand-search-wrap { position: relative; margin-bottom: 0.75rem; }
.filter-by-brand-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #696e79; pointer-events: none; display: flex; align-items: center; justify-content: center; }
.filter-by-brand-search { width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #e0e0e2; border-radius: 6px; font-size: 0.875rem; color: #282c3f; background: #fafbfc; }
.filter-by-brand-search::placeholder { color: #9a9ca1; }
.filter-by-brand-search:focus { outline: none; border-color: #c98a25; background: #fff; }
.filter-by-brand-list-wrap { max-height: 280px; overflow-y: auto; margin-bottom: 0.75rem; }
.filter-by-brand-list { list-style: none; padding: 0; margin: 0; }
.filter-by-brand-item { margin: 0; }
.filter-by-brand-item.hidden-by-search { display: none !important; }
.filter-by-brand-item-more { display: none; }
.filter-by-brand-item-more.visible { display: block; }
.filter-by-brand-row { display: flex; align-items: center; padding: 0.5rem 0; text-decoration: none; color: #282c3f; border: none; background: none; width: 100%; cursor: pointer; gap: 0.5rem; transition: color 0.2s ease; }
.filter-by-brand-row:hover { color: #c98a25; }
.filter-by-brand-checkbox { width: 18px; height: 18px; min-width: 18px; border: 2px solid #bfc0c6; border-radius: 4px; background: #fff; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; transition: border-color 0.2s, background 0.2s; }
.filter-by-brand-checkbox.checked { background: #c98a25; border-color: #c98a25; }
.filter-by-brand-checkbox:not(.checked) svg { display: none; }
.filter-by-brand-name { flex: 1; font-size: 0.875rem; }
.filter-by-brand-circle { width: 22px; height: 22px; min-width: 22px; border: 2px solid #bfc0c6; border-radius: 50%; background: #f5f5f6; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; transition: border-color 0.2s, background 0.2s; }
.filter-by-brand-circle.selected { background: #c98a25; border-color: #c98a25; }
.filter-by-brand-circle:not(.selected) svg { display: none; }
.filter-by-brand-item.selected .filter-by-brand-checkbox { background: #c98a25; border-color: #c98a25; }
.filter-by-brand-item.selected .filter-by-brand-circle { background: #c98a25; border-color: #c98a25; }
.filter-by-brand-show-more { display: block; width: 100%; margin: 0.5rem 0; padding: 0; background: none; border: none; color: #c98a25; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
.filter-by-brand-show-more:hover { text-decoration: underline; }
.filter-by-brand-actions { padding-top: 0.75rem; border-top: 1px solid #e9e9eb; }
.filter-by-brand-clear-all-btn { display: block; width: 100%; padding: 0.6rem 1rem; background: #fff; color: #282c3f; border: 1px solid #bfc0c6; border-radius: 6px; font-size: 0.875rem; font-weight: 500; text-align: center; text-decoration: none; cursor: pointer; transition: border-color 0.2s, color 0.2s; }
.filter-by-brand-clear-all-btn:hover { border-color: #c98a25; color: #c98a25; background: #fff; }

/* Product listing header & Featured sort */
.product-listing-header { border-color: #e9e9eb !important; }
.product-listing-header-inner { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; width: 100%; }
.product-listing-count { font-size: 14px; color: #535766; font-weight: 500; }
.product-listing-sort-form { display: flex; align-items: center; }
.product-listing-sort-select {
    min-width: 180px;
    padding: 9px 36px 9px 14px;
    font-size: 14px;
    font-weight: 500;
    color: #282c3f;
    background: #fafbfc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
}
.product-listing-sort-select:hover {
    background: #fff;
    border-color: #d1d5db;
}
.product-listing-sort-select:focus {
    outline: none;
    border-color: #9ca3af;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.04);
    background: #fff;
}

/* Product list pagination – styled and responsive */
.product-pagination-wrapper {
    width: 100%;
    padding: 16px 12px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.product-pagination-wrapper .pagination.product-pagination {
    margin: 0;
    padding: 0;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
    list-style: none;
    border: none;
}
.inner-page-product-listing .product-pagination .page-item {
    margin: 0;
    font-size: 0.875rem;
}
.inner-page-product-listing .product-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    height: 2.25rem;
    padding: 0 0.6rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #282c3f;
    background: #fff;
    border: 1px solid #e0e0e2;
    border-radius: 6px;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}
.inner-page-product-listing .product-pagination .page-link:hover {
    background: #f8f9fa;
    border-color: #c98a25;
    color: #c98a25;
}
.inner-page-product-listing .product-pagination .page-item.active .page-link {
    background: #c98a25;
    border-color: #c98a25;
    color: #fff;
}
.inner-page-product-listing .product-pagination .page-item.disabled .page-link {
    background: #f5f5f6;
    border-color: #e9e9eb;
    color: #9a9ca1;
    cursor: not-allowed;
}
.inner-page-product-listing .product-pagination .page-item:first-child .page-link,
.inner-page-product-listing .product-pagination .page-item:last-child .page-link {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}
@media (max-width: 576px) {
    .product-pagination-wrapper { padding: 12px 8px; }
    .product-pagination-wrapper .pagination.product-pagination { gap: 4px; }
    .inner-page-product-listing .product-pagination .page-link {
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        font-size: 0.8125rem;
    }
    .inner-page-product-listing .product-pagination .page-item:first-child .page-link,
    .inner-page-product-listing .product-pagination .page-item:last-child .page-link {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
}

/* Smooth transition when landing on a paginated page */
@keyframes product-listing-fade-in {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.inner-page-product-listing.product-listing-paginated {
    animation: product-listing-fade-in 0.35s ease-out;
}

/* Hide any DataTables-style pagination/info blocks on this page */
.dataTables_paginate,
.dataTables_info {
    display: none !important;
}

/* Shop grid: square images, gallery-style */
.product-grid-view {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
@media (min-width: 768px) {
    .product-grid-view {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
}
@media (min-width: 992px) {
    .product-grid-view {
        grid-template-columns: repeat(3, 1fr);
    }
}

.product-grid-card {
    position: relative;
    background: transparent;
}

.product-grid-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.product-grid-image {
    aspect-ratio: 1;
    overflow: hidden;
    background: #f5f5f5;
    margin-bottom: 1rem;
}
.product-grid-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-grid-tag {
    display: block;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #878787;
    margin-bottom: 0.35rem;
}

.product-grid-price {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.35rem;
}

.product-grid-title {
    font-size: 15px;
    font-weight: 600;
    margin: 0 0 0.5rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-grid-desc {
    font-size: 13px;
    color: #545454;
    line-height: 1.5;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-grid-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.75rem;
    flex-wrap: wrap;
}

.product-grid-cart { flex: 1; min-width: 120px; }

.steper-btn-grid {
    background: #2874f0;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    width: 100%;
    min-height: 40px;
}
.steper-btn-grid .steper-btn-minus-modern,
.steper-btn-grid .steper-btn-value-modern,
.steper-btn-grid .steper-btn-plus-modern { display: none !important; }
.steper-btn-grid .steper-btn-text-modern { display: inline-block !important; }
.steper-btn-grid:hover { background: #1a5bc5; }

.fav-btn-grid {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.fav-btn-grid:hover { background: #eee; }
.fav-btn-grid.active { background: #fff5f5; border-color: #ff6b6b; }
.fav-btn-grid.active svg { fill: #ff6b6b; stroke: #ff6b6b; }

.notify-btn-grid {
    flex: 1;
    min-width: 120px;
    min-height: 40px;
    background: #f5f5f5;
    color: #212529;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 8px 12px;
    font-weight: 500;
    font-size: 13px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}
.notify-btn-grid:hover { background: #eee; }

/* Shop section: one-line detailing (horizontal row per product) */
.product-list-view {
    display: flex;
    flex-direction: column;
    gap: 0;
    background: #fff;
}

.product-list-row {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.25rem 0;
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
}

.product-list-row:last-child {
    border-bottom: none;
}

.product-list-row-image {
    flex-shrink: 0;
    width: 200px;
    position: relative;
}

.product-list-row-img-link {
    display: block;
    position: relative;
    height: 200px;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
}

.product-list-row-img-link img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.product-list-row-delivery {
    margin-top: 6px;
    font-size: 13px;
    color: #e20a0a;
    font-weight: 500;
}

.product-list-row-details {
    flex: 1;
    min-width: 0;
    padding-top: 2px;
}

.product-list-row-brand {
    font-size: 12px;
    color: #878787;
    margin-bottom: 4px;
}

.product-list-row-title {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #2874f0;
    text-decoration: none;
    margin-bottom: 6px;
    line-height: 1.35;
}

.product-list-row-title:hover {
    text-decoration: underline;
    color: #1a5bc5;
}

.product-list-row-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    font-size: 14px;
}

.product-list-row-stars {
    color: #ffc107;
    letter-spacing: 1px;
}

.product-list-row-rating-value {
    color: #212529;
    font-weight: 500;
}

.product-list-row-description {
    font-size: 13px;
    color: #545454;
    line-height: 1.5;
    margin: 0;
}

.product-list-row-price-block {
    flex-shrink: 0;
    width: 220px;
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: flex-start;
}

.product-list-row-price {
    margin-bottom: 12px;
}

.product-list-row-price-current {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 4px;
}

.product-list-row-price-original {
    font-size: 14px;
    color: #878787;
    text-decoration: line-through;
    margin-right: 8px;
}

.product-list-row-price-save {
    font-size: 13px;
    color: #16a34a;
    font-weight: 500;
}

.product-list-row-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.product-list-row-cart {
    flex: 1;
    min-width: 140px;
}

.steper-btn-list {
    background: #2874f0;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    width: 100%;
    min-height: 44px;
}

.steper-btn-list .steper-btn-minus-modern,
.steper-btn-list .steper-btn-value-modern,
.steper-btn-list .steper-btn-plus-modern {
    display: none !important;
}

.steper-btn-list .steper-btn-text-modern {
    display: inline-block !important;
    white-space: nowrap;
}

.steper-btn-list:hover {
    background: #1a5bc5;
}

.fav-btn-list {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.fav-btn-list:hover {
    background: #eee;
}

.fav-btn-list.active {
    background: #fff5f5;
    border-color: #ff6b6b;
}

.fav-btn-list.active svg {
    fill: #ff6b6b;
    color: #ff6b6b;
}

.notify-btn-list {
    flex: 1;
    min-width: 140px;
    min-height: 44px;
    background: #f5f5f5;
    color: #212529;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 10px 16px;
    font-weight: 500;
    font-size: 14px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notify-btn-list:hover {
    background: #eee;
}

@media (max-width: 767px) {
    .product-list-row {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    .product-list-row-image {
        width: 100%;
    }
    .product-list-row-img-link {
        height: 220px;
    }
    .product-list-row-price-block {
        width: 100%;
        align-items: stretch;
        text-align: left;
    }
    .product-list-row-actions {
        justify-content: flex-start;
    }
}
</style>
@endsection

@section('script')
<script>
(function() {
    var main = document.getElementById('product-listing-main');
    if (main && parseInt(main.getAttribute('data-pagination-page'), 10) > 1) {
        main.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
})();
(function() {
    var searchEl = document.querySelector('.filter-by-brand-search');
    var listEl = document.querySelector('.filter-by-brand-list');
    var showMoreBtn = document.querySelector('.filter-by-brand-show-more');
    if (!listEl) return;
    var items = listEl.querySelectorAll('.filter-by-brand-item:not(.filter-by-brand-item-all)');
    var moreItems = listEl.querySelectorAll('.filter-by-brand-item-more');
    function filterBrands() {
        var q = (searchEl && searchEl.value) ? searchEl.value.trim().toLowerCase() : '';
        items.forEach(function(item) {
            var name = (item.getAttribute('data-brand-name') || '').toLowerCase();
            if (!q || name.indexOf(q) !== -1) {
                item.classList.remove('hidden-by-search');
            } else {
                item.classList.add('hidden-by-search');
            }
        });
    }
    if (searchEl) {
        searchEl.addEventListener('input', filterBrands);
        searchEl.addEventListener('keyup', filterBrands);
    }
    if (showMoreBtn && moreItems.length) {
        showMoreBtn.addEventListener('click', function() {
            var expanded = showMoreBtn.getAttribute('data-expanded') === 'true';
            if (expanded) {
                moreItems.forEach(function(el) { el.classList.remove('visible'); });
                showMoreBtn.setAttribute('data-expanded', 'false');
                showMoreBtn.textContent = 'Show More';
            } else {
                moreItems.forEach(function(el) { el.classList.add('visible'); });
                showMoreBtn.setAttribute('data-expanded', 'true');
                showMoreBtn.textContent = 'Show Less';
            }
        });
    }
})();
</script>
@endsection
