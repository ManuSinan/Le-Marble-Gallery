@php
    $siteName = $storeName ?? config('app.name', 'Lee Marble Gallery');
    $metaDescription = trim(strip_tags($__env->yieldContent('description', getOption('website_meta_description', ''))));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName)</title>
    <meta name="description" content="{{ e($metaDescription) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/knm/css/knm.css') }}?v={{ config('app.asset_version', '1') }}">
    <style>
      * {
        box-shadow: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
      }
      :root {
        --knm-primary: {{ config('app.theme_primary', '#152B6E') }} !important;
        --knm-primary-hover: {{ config('app.theme_primary', '#152B6E') }} !important;
        --knm-bg: #F2F4F8 !important;
        --knm-text: #1f2937 !important;
      }
      body.knm-body {
        background-color: #F2F4F8 !important;
        font-family: 'Inter', sans-serif !important;
      }
      .knm-header {
        background-color: {{ config('app.theme_primary', '#152B6E') }} !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
      }
      .knm-brand span {
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
      }
      .knm-header-actions .knm-btn--ghost {
        color: #ffffff !important;
      }
      .knm-header-actions .knm-btn--ghost svg {
        stroke: #ffffff !important;
      }
      .knm-header-actions__badge {
        background-color: {{ config('app.theme_tertiary', '#1a56db') }} !important;
        color: #ffffff !important;
      }
      .knm-btn--primary {
        background-color: {{ config('app.theme_tertiary', '#1a56db') }} !important;
        border-color: {{ config('app.theme_tertiary', '#1a56db') }} !important;
        color: #ffffff !important;
      }
      .knm-btn--primary:hover {
        background-color: {{ config('app.theme_primary', '#152B6E') }} !important;
        border-color: {{ config('app.theme_primary', '#152B6E') }} !important;
      }
      .knm-nav-desktop .knm-btn--ghost {
        color: rgba(255, 255, 255, 0.8) !important;
      }
      .knm-nav-desktop .knm-btn--ghost.is-active, .knm-nav-desktop .knm-btn--ghost:hover {
        color: #ffffff !important;
      }
      .knm-card {
        background: #ffffff !important;
        border-radius: 4px !important;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
      }
      .knm-btn--store-primary {
        background-color: {{ config('app.theme_primary', '#152B6E') }} !important;
        color: #ffffff !important;
      }
      .knm-btn--store-primary:hover {
        background-color: {{ config('app.theme_primary', '#152B6E') }} !important;
      }
    </style>
    @stack('styles')
    @yield('extra_styles')
</head>

<body class="knm-body knm-body--store">
    @include('layouts.partials.store-header', ['storeName' => $siteName, 'search' => $search ?? null, 'sortby' => $sortby ?? null, 'cartCount' => $cartCount ?? null])

    <div class="knm-grow">
        @yield('content')
    </div>

    @include('layouts.partials.auth-modal')

    {{-- @include('layouts.partials.store-footer', ['storeName' => $siteName, 'contactNumber' => $contactNumber ?? null]) --}}

    @yield('scripts')
    @include('layouts.partials.auth-modal-script')
</body>

</html>