@php
    $siteName = config('app.name', 'KNM Bookstore');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName)</title>
    <link rel="icon" type="image/png" href="{{ asset('favicons/knm-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/knm/css/knm.css') }}?v={{ config('app.asset_version', '1') }}">
    <style>
        .knm-auth-page { min-height: 100vh; display: flex; flex-direction: column; }
        .knm-auth-top { padding: 16px 24px; border-bottom: 1px solid var(--knm-border, #e8e8e8); background: #fff; }
        .knm-auth-main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px 16px 48px; }
        .knm-auth-card { width: 100%; max-width: 400px; background: #fff; border: 1px solid var(--knm-border, #e8e8e8); border-radius: 8px; padding: 24px; }
    </style>
</head>
<body class="knm-body knm-auth-page">
    <header class="knm-auth-top">
        <a href="{{ route('home') }}" class="knm-brand">
            <img src="{{ asset('favicons/knm-logo-navbar.png') }}" alt="">
            <span>{{ $siteName }}</span>
        </a>
    </header>
    <main class="knm-auth-main">
        <div class="knm-auth-card">
            @yield('content')
        </div>
    </main>
    @yield('scripts')
</body>
</html>
