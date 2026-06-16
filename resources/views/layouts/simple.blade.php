@php
    $siteName = $storeName ?? config('app.name', 'KNM Bookstore');
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
    <link rel="icon" type="image/png" href="{{ asset('favicons/knm-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/knm/css/knm.css') }}?v={{ config('app.asset_version', '1') }}">
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