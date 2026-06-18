<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
 
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="app-url" content="{{ config('app.url') }}">
    <meta name="cart-url" content="{{ route('website.cart') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">
 
    <meta name="theme-color" content="#152B6E">
    <meta name="mobile-web-app-capable" content="yes" />
    
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', '') }}" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    
    <link rel="icon" type="image/png" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
 
    <meta property="og:site_name" content="{{ request()->getHost() }}"/>
    @yield('seo')
    @php $routeName = request()->route()->getName(); @endphp
    <style>
      * {
        box-shadow: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
      }
      :root {
        --mg-navy: {{ config('app.theme_primary', '#152B6E') }};
        --mg-navy-dark: {{ config('app.theme_primary', '#152B6E') }};
        --mg-navy-light: {{ config('app.theme_primary', '#152B6E') }};
        --mg-blue: {{ config('app.theme_tertiary', '#1a56db') }};
        --mg-blue-light: {{ config('app.theme_secondary', '#e8edf7') }};
        --mg-bg: #F2F4F8;
        --mg-text: #1f2937;
        --mg-text-muted: #4b5563;
      }
      
      body.luxury-theme {
        background-color: var(--mg-bg) !important;
        background-image: none !important;
        color: var(--mg-text) !important;
        font-family: 'Inter', sans-serif !important;
      }
      
      /* Navigation Overrides */
      .luxury-nav {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
      }
      .luxury-nav.scrolled,
      .luxury-nav.luxury-nav-inner {
        background: var(--mg-navy) !important;
      }
      .luxury-logo {
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 800 !important;
        letter-spacing: 0.05em !important;
      }
      .luxury-menu a {
        color: rgba(255, 255, 255, 0.85) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 500 !important;
      }
      .luxury-menu a:hover, .luxury-menu a.active {
        color: #ffffff !important;
      }
      .luxury-actions a {
        color: #ffffff !important;
      }
      .luxury-icon svg {
        stroke: #ffffff !important;
      }
      .luxury-cart-count {
        background: var(--mg-blue) !important;
        color: #ffffff !important;
      }
      
      /* Hero overrides */
      .luxury-hero {
        background: linear-gradient(rgba(11, 24, 63, 0.82), rgba(21, 43, 110, 0.82)), url('{{ asset("assets/frontend/images/hero-bg.png") }}') no-repeat center/cover !important;
        color: #ffffff !important;
      }
      .luxury-hero-title {
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 800 !important;
      }
      .luxury-hero-sub {
        color: rgba(255, 255, 255, 0.9) !important;
        font-family: 'Inter', sans-serif !important;
      }
      
      /* CTAs and buttons */
      .luxury-cta {
        background: var(--mg-blue) !important;
        color: #ffffff !important;
        border: none !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
      }
      .luxury-cta:hover {
        background: #1e429f !important;
        transform: translateY(-2px);
        box-shadow: none !important;
      }
      
      /* Section elements */
      .luxury-section-title {
        color: var(--mg-navy) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      
      /* Categories filter bar */
      .shop-luxury-header {
        background: var(--mg-navy) !important;
      }
      .shop-luxury-nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        font-family: 'Inter', sans-serif !important;
      }
      .shop-luxury-nav-link:hover, .shop-luxury-nav-link.active {
        color: #ffffff !important;
        border-bottom-color: #ffffff !important;
      }
      .shop-luxury-title {
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
      }
      .shop-luxury-select {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
      }
      .shop-luxury-select option {
        background: var(--mg-navy) !important;
        color: #ffffff !important;
      }
      
      /* Cards overrides */
      .shop-luxury-card {
        background: #ffffff !important;
        border-radius: 4px !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: none !important;
        transition: all 0.3s ease !important;
      }
      .shop-luxury-card:hover {
        transform: translateY(-5px);
        box-shadow: none !important;
      }
      .shop-luxury-info {
        background: #ffffff !important;
      }
      
      .luxury-product-card {
        aspect-ratio: auto !important;
        display: flex !important;
        flex-direction: column !important;
        padding: 12px !important;
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 4px !important;
        box-shadow: none !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
      }
      .luxury-product-card:hover {
        transform: translateY(-5px);
        box-shadow: none !important;
      }
      
      .luxury-product-image-link {
        display: block !important;
        position: relative !important;
        aspect-ratio: 4/3 !important;
        overflow: hidden !important;
        background: #f3f4f6 !important;
        border-radius: 2px !important;
      }
      .luxury-product-image-link img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.5s !important;
      }
      .luxury-product-card:hover .luxury-product-image-link img {
        transform: scale(1.04) !important;
      }
      
      .luxury-product-info {
        position: relative !important;
        padding: 12px 0 0 !important;
        transform: none !important;
        opacity: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 6px !important;
        background: transparent !important;
      }
      
      .luxury-product-tag {
        display: inline-block !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        letter-spacing: 0.05em !important;
        text-transform: uppercase !important;
        color: var(--mg-text-muted) !important;
        margin-bottom: 2px !important;
      }
      
      .luxury-product-name-link {
        text-decoration: none !important;
      }
      .luxury-product-name {
        font-size: 15px !important;
        font-weight: 600 !important;
        color: var(--mg-text) !important;
        margin: 0 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: block !important;
        font-family: 'Inter', sans-serif !important;
      }
      .luxury-product-name-link:hover .luxury-product-name {
        color: var(--mg-blue) !important;
      }
      
      .luxury-product-meta-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 8px !important;
        margin-top: 4px !important;
      }
      .luxury-product-price {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: var(--mg-navy) !important;
        margin: 0 !important;
        font-family: 'Inter', sans-serif !important;
      }
      
      .luxury-product-card .fav-btn-list {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        color: var(--mg-text-muted) !important;
        border-radius: 4px !important;
        width: 34px !important;
        height: 34px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      .luxury-product-card .fav-btn-list:hover,
      .luxury-product-card .fav-btn-list.active {
        border-color: var(--mg-navy) !important;
        color: var(--mg-navy) !important;
        background: var(--mg-blue-light) !important;
      }
      .luxury-product-card .fav-btn-list.active svg {
        fill: var(--mg-navy) !important;
        stroke: var(--mg-navy) !important;
      }
      
      .luxury-product-card .steper-btn-list {
        width: 100% !important;
        height: 38px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 4px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        background: var(--mg-navy) !important;
        color: #ffffff !important;
        border: none !important;
      }
      .luxury-product-card .steper-btn-list:hover {
        background: var(--mg-navy-dark) !important;
      }
      .luxury-product-card .steper-btn-list.empty {
        background: var(--mg-navy) !important;
        color: #ffffff !important;
      }
      .luxury-product-card .steper-btn-list.empty:hover {
        background: var(--mg-navy-dark) !important;
      }
      
      .shop-luxury-tag {
        background: var(--mg-blue-light) !important;
        color: var(--mg-navy) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
      }
      .shop-luxury-price {
        color: var(--mg-navy) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      .shop-luxury-name {
        color: var(--mg-text) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
      }
      
      /* PDP Page overrides */
      .pdp-luxury-category {
        color: var(--mg-blue) !important;
        background: var(--mg-blue-light) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
      }
      .pdp-luxury-back {
        color: var(--mg-text-muted) !important;
        font-family: 'Inter', sans-serif !important;
      }
      .pdp-luxury-back:hover {
        color: var(--mg-navy) !important;
      }
      .pdp-luxury-title {
        color: var(--mg-navy) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      .pdp-luxury-price {
        color: var(--mg-navy) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      .pdp-luxury-btn-cart {
        background: var(--mg-navy) !important;
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
      }
      .pdp-luxury-btn-cart:hover {
        background: var(--mg-navy-dark) !important;
      }
      .pdp-luxury-btn-wishlist {
        border-color: var(--mg-navy) !important;
        color: var(--mg-navy) !important;
      }
      .pdp-luxury-btn-wishlist.active {
        background: var(--mg-navy) !important;
        color: #ffffff !important;
      }
      .pdp-luxury-details-heading {
        border-bottom: 2px solid var(--mg-blue-light) !important;
        color: var(--mg-navy) !important;
      }
      .pdp-luxury-specs dt {
        color: var(--mg-text-muted) !important;
      }
      .pdp-luxury-specs dd {
        color: var(--mg-navy) !important;
      }
      
      /* Custom Category / Class selector styling */
      .knm-class-hero {
        background: linear-gradient(115deg, var(--mg-navy) 0%, var(--mg-blue) 100%) !important;
        border-radius: 4px !important;
      }
      .knm-class-hero__glass {
        border-radius: 4px !important;
      }
      .knm-class-hero__title {
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      #knm-class-select {
        font-family: 'Inter', sans-serif !important;
        border-radius: 4px !important;
      }
      
      /* Footer */
      .footer-luxury {
        background: var(--mg-navy-dark) !important;
        color: rgba(255, 255, 255, 0.7) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
      }
      .footer-luxury .title {
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
      }
      .footer-luxury a {
        color: rgba(255, 255, 255, 0.7) !important;
      }
      .footer-luxury a:hover {
        color: #ffffff !important;
      }

      /* ==========================================================================
         WHITE THEME OVERRIDES (Transforms dark luxury view to matching white theme)
         ========================================================================== */
      .luxury-page,
      .luxury-inner-page {
        background: var(--mg-bg) !important;
      }

      .shop-luxury, .pdp-luxury, .pdp-luxury-gallery, .pdp-luxury-detail {
        background: var(--mg-bg) !important;
        color: var(--mg-text) !important;
      }

      /* Luxury Gallery - Authentic Design Overrides */
      .luxury-gallery {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 16px !important;
        grid-auto-rows: 240px !important;
      }

      .luxury-gallery-item {
        position: relative !important;
        overflow: hidden !important;
        text-decoration: none !important;
        display: block !important;
        border-radius: 4px !important;
        border: 1px solid #e5e7eb !important;
        aspect-ratio: auto !important;
        box-shadow: none !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      .luxury-gallery-item img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      /* Asymmetric grid ratios */
      .luxury-gallery-item:nth-child(1) {
        grid-column: span 2 !important;
        grid-row: span 2 !important;
      }
      .luxury-gallery-item:nth-child(3) {
        grid-row: span 2 !important;
      }
      .luxury-gallery-item:nth-child(4) {
        grid-column: span 1 !important;
      }
      .luxury-gallery-item:nth-child(6) {
        grid-column: span 2 !important;
      }

      @media (max-width: 991px) {
        .luxury-gallery {
          grid-template-columns: repeat(2, 1fr) !important;
          grid-auto-rows: 200px !important;
        }
        .luxury-gallery-item:nth-child(1),
        .luxury-gallery-item:nth-child(3),
        .luxury-gallery-item:nth-child(6) {
          grid-column: span 1 !important;
          grid-row: span 1 !important;
        }
      }

      @media (max-width: 575px) {
        .luxury-gallery {
          grid-template-columns: 1fr !important;
          grid-auto-rows: 220px !important;
        }
        .luxury-gallery-item {
          grid-column: span 1 !important;
          grid-row: span 1 !important;
        }
      }

      .luxury-gallery-hover-overlay {
        position: absolute !important;
        inset: 0 !important;
        background: linear-gradient(to top, rgba(21, 43, 110, 0.9) 0%, rgba(21, 43, 110, 0.4) 60%, transparent 100%) !important;
        opacity: 0 !important;
        transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        display: flex !important;
        align-items: flex-end !important;
        padding: 24px !important;
        box-sizing: border-box !important;
      }

      .luxury-gallery-hover-overlay::before {
        content: '' !important;
        position: absolute !important;
        top: 12px !important;
        left: 12px !important;
        right: 12px !important;
        bottom: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        pointer-events: none !important;
        transform: scale(0.95) !important;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      .luxury-gallery-item:hover {
        transform: translateY(-4px) !important;
        box-shadow: none !important;
      }

      .luxury-gallery-item:hover img {
        transform: scale(1.05) !important;
      }

      .luxury-gallery-item:hover .luxury-gallery-hover-overlay {
        opacity: 1 !important;
      }

      .luxury-gallery-item:hover .luxury-gallery-hover-overlay::before {
        transform: scale(1) !important;
      }

      .luxury-gallery-hover-content {
        color: #ffffff !important;
        transform: translateY(15px) !important;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        width: 100% !important;
        text-align: left !important;
      }

      .luxury-gallery-item:hover .luxury-gallery-hover-content {
        transform: translateY(0) !important;
      }

      .luxury-gallery-category-tag {
        font-family: 'Inter', sans-serif !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        color: rgba(255, 255, 255, 0.75) !important;
        display: block !important;
        margin-bottom: 4px !important;
      }

      .luxury-gallery-title-text {
        font-family: 'Inter', sans-serif !important;
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        margin: 0 0 6px 0 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        line-height: 1.25 !important;
      }

      .luxury-gallery-origin-tag {
        font-family: 'Inter', sans-serif !important;
        font-size: 12px !important;
        color: rgba(255, 255, 255, 0.85) !important;
        display: block !important;
        margin-bottom: 12px !important;
      }

      .luxury-gallery-view-btn {
        font-family: 'Inter', sans-serif !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #ffffff !important;
        border-bottom: 1.5px solid #ffffff !important;
        padding-bottom: 2px !important;
        display: inline-block !important;
      }

      .luxury-about-image {
        background: #ffffff !important;
      }
      
      .luxury-about-text p {
        color: var(--mg-text-muted) !important;
      }
      
      .luxury-story-card {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 4px !important;
        box-shadow: none !important;
        transition: all 0.3s ease !important;
      }
      .luxury-story-card:hover {
        border-color: var(--mg-navy) !important;
        box-shadow: none !important;
      }
      .luxury-story-num {
        color: var(--mg-blue) !important;
      }
      .luxury-story-card h3 {
        color: var(--mg-navy) !important;
      }
      .luxury-story-card p {
        color: var(--mg-text-muted) !important;
      }
      
      .luxury-custom {
        background: linear-gradient(180deg, transparent 0%, rgba(26, 86, 219, 0.05) 50%, transparent 100%) !important;
      }
      .luxury-custom p {
        color: var(--mg-text-muted) !important;
      }
      
      .luxury-product-overlay {
        background: linear-gradient(to top, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.2) 70%, transparent 100%) !important;
      }
      .shop-luxury-overlay {
        background: linear-gradient(to top, rgba(255, 255, 255, 0.4) 0%, transparent 40%) !important;
      }
      
      .shop-luxury-nav {
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
      }
      
      .shop-luxury-pagination {
        border-top: 1px solid #e5e7eb !important;
      }
      .shop-luxury-pag-link {
        color: var(--mg-text-muted) !important;
      }
      .shop-luxury-pag-link:hover {
        color: var(--mg-navy) !important;
      }
      .shop-luxury-pag-info {
        color: var(--mg-text-muted) !important;
      }
      .shop-luxury-empty {
        color: var(--mg-text-muted) !important;
      }
      
      .luxury-theme .text-dark {
        color: var(--mg-text) !important;
      }

      .pdp-luxury-title, .pdp-luxury-price, .pdp-luxury-spec-row dd {
        color: var(--mg-navy) !important;
      }
      .pdp-luxury-back, .pdp-luxury-category, .pdp-luxury-desc, .pdp-luxury-variant-label, .pdp-luxury-specs dt, .pdp-luxury-spec-row dt {
        color: var(--mg-text-muted) !important;
      }

      .pdp-luxury-variant-btn {
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
        background: #ffffff !important;
      }
      .pdp-luxury-variant-btn.active {
        border-color: var(--mg-navy) !important;
        color: var(--mg-navy) !important;
        background: var(--mg-blue-light) !important;
      }
      .pdp-luxury-variant-btn:not(.active):hover {
        border-color: var(--mg-navy) !important;
        color: var(--mg-navy) !important;
      }

      .pdp-luxury-select, .shop-luxury-select {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
      }
      .shop-luxury-select option {
        background: #ffffff !important;
        color: var(--mg-text) !important;
      }

      .pdp-luxury-spec-row {
        border-bottom-color: #e5e7eb !important;
      }

      .luxury-theme .card,
      .luxury-theme .product-subcategories-card,
      .luxury-theme .filter-by-brand-card {
        background: #ffffff !important;
        border-color: #e5e7eb !important;
        color: var(--mg-text) !important;
      }
      .luxury-theme .product-subcategories-header {
        background: var(--mg-blue-light) !important;
        border-color: #e5e7eb !important;
      }
      .luxury-theme .product-subcategories-title,
      .luxury-theme .filter-by-brand-title {
        color: var(--mg-navy) !important;
      }
      .luxury-theme .product-subcategories-link,
      .luxury-theme .filter-by-brand-name {
        color: var(--mg-text-muted) !important;
      }
      .luxury-theme .product-subcategories-link:hover,
      .luxury-theme .product-subcategories-item.active .product-subcategories-link {
        color: var(--mg-blue) !important;
      }
      .luxury-theme .filter-by-brand-search {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
      }
      .luxury-theme .filter-by-brand-clear-link,
      .luxury-theme .filter-by-brand-row:hover {
        color: var(--mg-blue) !important;
      }

      .luxury-theme .product-listing-header {
        border-color: #e5e7eb !important;
      }
      .luxury-theme .product-listing-count {
        color: var(--mg-text-muted) !important;
      }
      .luxury-theme .product-listing-sort-select {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
      }
      .luxury-theme .product-list-row {
        background: #ffffff !important;
        border-color: #e5e7eb !important;
        border-radius: 4px !important;
        box-shadow: none !important;
        margin-bottom: 20px !important;
        padding: 16px !important;
      }
      .luxury-theme .product-list-row-img-link {
        background: var(--mg-blue-light) !important;
        border-radius: 2px !important;
      }
      .luxury-theme .product-list-row-brand {
        color: var(--mg-text-muted) !important;
      }
      .luxury-theme .product-list-row-title {
        color: var(--mg-text) !important;
      }
      .luxury-theme .product-list-row-rating-value {
        color: var(--mg-text) !important;
      }
      .luxury-theme .product-list-row-description {
        color: var(--mg-text-muted) !important;
      }
      .luxury-theme .product-list-row-price-current {
        color: var(--mg-navy) !important;
        font-weight: 700 !important;
      }
      .luxury-theme .product-list-row-price-original {
        color: var(--mg-text-muted) !important;
      }

      .luxury-theme .steper-btn-list {
        background: var(--mg-navy) !important;
        color: #ffffff !important;
        border-radius: 4px !important;
      }
      .luxury-theme .steper-btn-list:hover {
        background: var(--mg-navy-dark) !important;
      }
      .luxury-theme .fav-btn-list {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
      }
      .luxury-theme .fav-btn-list:hover {
        background: var(--mg-blue-light) !important;
      }
      .luxury-theme .fav-btn-list.active svg {
        fill: var(--mg-navy) !important;
        stroke: var(--mg-navy) !important;
      }
      .luxury-theme .notify-btn-list {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
      }

      .luxury-theme .product-pagination .page-link {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text) !important;
      }
      .luxury-theme .product-pagination .page-link:hover {
        border-color: var(--mg-navy) !important;
        color: var(--mg-navy) !important;
      }
      .luxury-theme .product-pagination .page-item.active .page-link {
        background: var(--mg-navy) !important;
        border-color: var(--mg-navy) !important;
        color: #ffffff !important;
      }

      .cart-page-title {
        color: var(--mg-navy) !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      .section-content.cart-page .checkout-delivery-card,
      .section-content.cart-page .card.mb-4:not(.checkout-delivery-card),
      .section-content.cart-page .payment-summary-card,
      .payment-summary-card,
      .section-content.cart-page .empty-cart-card {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: none !important;
        border-radius: 4px !important;
      }
      .checkout-delivery-card .text-dark,
      .cart-sidebar-title,
      .payment-value,
      .payment-value-final,
      .section-content.cart-page .empty-content .title,
      .section-content.cart-page .cart-item .title {
        color: var(--mg-text) !important;
      }
      .checkout-delivery-card .text-muted,
      .payment-label,
      .section-content.cart-page .empty-content p,
      .section-content.cart-page .cart-item-meta,
      .section-content.cart-page .cart-item-brand {
        color: var(--mg-text-muted) !important;
      }
      .checkout-delivery-card .box {
        background: var(--mg-blue-light) !important;
        border-color: #d1d5db !important;
      }
      .checkout-delivery-card .box:hover {
        border-color: var(--mg-navy) !important;
      }
      .checkout-delivery-card .custom-control-label {
        color: var(--mg-text) !important;
      }
      .section-content.cart-page .cart-item {
        border-bottom: 1px solid #e5e7eb !important;
      }
      .section-content.cart-page .cart-item:hover {
        background: var(--mg-blue-light) !important;
      }
      .section-content.cart-page .cart-item .price,
      .section-content.cart-page .cart-item .price .selling {
        color: var(--mg-navy) !important;
        font-weight: 700 !important;
      }
      .section-content.cart-page .cart-item-action-btn {
        background: #ffffff !important;
        border-color: #d1d5db !important;
        color: var(--mg-text-muted) !important;
      }
      .section-content.cart-page .cart-item-action-favourite:hover,
      .section-content.cart-page .cart-item-action-remove:hover {
        border-color: var(--mg-navy) !important;
        background: var(--mg-blue-light) !important;
        color: var(--mg-navy) !important;
      }
      .section-content.cart-page .steper-btn {
        background: #ffffff !important;
        border-color: #d1d5db !important;
      }
      .section-content.cart-page .steper-btn .steper-btn-text,
      .section-content.cart-page .steper-btn .steper-btn-value {
        color: var(--mg-text) !important;
      }
      .btn-checkout {
        background: var(--mg-navy) !important;
        color: #ffffff !important;
        border-color: var(--mg-navy) !important;
      }
      .btn-checkout:hover:not(:disabled) {
        background: var(--mg-navy-dark) !important;
        border-color: var(--mg-navy-dark) !important;
        color: #ffffff !important;
      }
      .btn-continue-shopping {
        color: var(--mg-navy) !important;
        border-color: var(--mg-navy) !important;
      }
      .btn-continue-shopping:hover {
        background: var(--mg-blue-light) !important;
        color: var(--mg-navy) !important;
        border-color: var(--mg-navy) !important;
      }
      .section-content.cart-page .empty-content .btn-light {
        background: var(--mg-navy) !important;
        border-color: var(--mg-navy) !important;
        color: #ffffff !important;
      }
      .section-content.cart-page .empty-content .btn-light:hover {
        background: var(--mg-navy-dark) !important;
        color: #ffffff !important;
      }
    </style>
    <link href="{{ asset('assets/frontend/css/style.css?version=' . config('app.asset_version')) }}" rel="stylesheet" type="text/css"/>
    @yield('style')
  </head>
 
@php
  $routeName = request()->route()->getName();
  $isHome = ($routeName === 'home' || request()->is('/') || request()->is('index'));
  $isAuthPage = in_array($routeName ?? '', ['signin', 'signup']);
  $isLuxuryTheme = true;
@endphp
<body class="d-flex flex-column min-vh-100 nnitec-body {{ $isLuxuryTheme ? 'luxury-theme' : '' }} {{ $isHome ? 'page-home' : '' }}">
  @if(!$isHome)
  <div class="nnitec-page luxury-inner-page">
    <div class="nnitec-container">
      @include('frontend/layout/header-luxury', ['innerPage' => true])
      <div class="nnitec-inner">
        @yield('body')
      </div>
    </div>
  </div>
  @else
  @yield('body')
  @endif
  @if($routeName !== 'signin' && $routeName !== 'signup' && !$isHome)
  @include('frontend/layout/ads-banner')
  @endif
  @include('frontend/layout/footer')
  @if (Auth::guest())
  @include('frontend/layout/login-modal')
  @include('frontend/layout/signup-modal')
  @include('frontend/layout/otp-verification-modal')
  @endif
  <section class="toast-alert">
    <div id="toast" class="toast" role="alert" aria-live="polite" aria-atomic="true" data-delay="1000" style="background: rgba(255,255,255,0.98); border: 1px solid #e5e7eb; box-shadow: none; border-radius: 4px;">
      <div class="toast-body d-flex align-items-center p-3">
        <span class="success mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2l4 -4"/></svg></span>
        <span class="warning error mr-3 d-none"><svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
        <span class="text" style="font-weight: 600;"></span>
      </div>
    </div>
  </section>
	<script src="{{ asset('assets/frontend/js/script.js?version=' . config('app.asset_version')) }}" type="text/javascript"></script>
	@if(!in_array($routeName ?? '', ['signin', 'signup']))
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
	    menu && menu.querySelectorAll('a').forEach(function(a) {
	      a.addEventListener('click', function() {
	        menu.classList.remove('open');
	        toggle.classList.remove('active');
	        document.body.classList.remove('luxury-menu-open');
	      });
	    });
	  }
	})();
	</script>
	@endif
	@if (Auth::guest() && (session('openAuthModal') || session('error')))
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		if (typeof $ !== 'undefined' && $('#loginModal').length) {
			$('#loginModal').modal('show');
			@if(session('error'))
			alert('{{ addslashes(session('error')) }}');
			@endif
		}
	});
	</script>
	@endif
	@yield('script')
</body>
</html>