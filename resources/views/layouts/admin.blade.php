@php
    $u = authUser();
    $initial = $u ? strtoupper(mb_substr($u->name ?? $u->email ?? '?', 0, 1)) : '?';
    $dash = route('dashboard');
    $catalog = hasPermission('product') ? route('product') : $dash;
    $orders = hasPermission('order') ? route('order') : $dash;
    $reports = hasPermission('report.business.overview') ? route('report.business.overview') : (hasPermission('report.most.purchased.products') ? route('report.most.purchased.products') : $dash);
    $more = hasPermission('manage.store') ? route('manage.store') : (hasPermission('category') ? route('category') : $dash);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ config('app.url') }}">
    <title>@yield('admin_title', __('Dashboard')) — {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicons/knm-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/knm/css/admin.css') }}?v={{ config('app.asset_version', '1') }}">
    @stack('styles')
</head>
<body class="knm-acp">
    <div class="knm-acp__body">
        <aside class="knm-acp-sidebar" aria-label="Admin navigation">
            <a href="{{ $dash }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                {{ __('Dashboard') }}
            </a>
            @if(hasPermission('product'))
                <a href="{{ route('product') }}" class="{{ request()->routeIs('product') ? 'is-active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    {{ __('Books') }}
                </a>
            @endif
            @if(hasPermission('order'))
                <a href="{{ route('order') }}" class="{{ request()->routeIs('order') ? 'is-active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    {{ __('Orders') }}
                </a>
            @endif
            @if(hasPermission('report.business.overview'))
                <a href="{{ route('report.business.overview') }}" class="{{ request()->routeIs('report.*') ? 'is-active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    {{ __('Reports') }}
                </a>
            @endif
            @if(hasPermission('manage.store'))
                <a href="{{ route('manage.store') }}" class="{{ request()->routeIs('manage.store') ? 'is-active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    {{ __('Store') }}
                </a>
            @endif
        </aside>

        <div class="knm-acp-main">
            <header class="knm-acp-topbar">
                <div>
                    <div class="knm-acp-breadcrumb">@yield('admin_breadcrumb')</div>
                    <h1 class="knm-acp-title">@yield('admin_title', __('Dashboard'))</h1>
                </div>
                <div class="knm-flex knm-gap-3 knm-flex-center">
                    <span class="knm-muted knm-small knm-acp-user-meta">{{ $u->name ?? $u->email ?? '' }}</span>
                    <span class="knm-acp-avatar" aria-hidden="true">{{ $initial }}</span>
                </div>
            </header>
            <div class="knm-acp-content">
                @yield('content')
            </div>
        </div>
    </div>

    <nav class="knm-acp-nav-mobile" aria-label="Admin bottom navigation">
        <a href="{{ $dash }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            {{ __('Home') }}
        </a>
        <a href="{{ $catalog }}" class="{{ request()->routeIs('product') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            {{ __('Catalogue') }}
        </a>
        <a href="{{ $orders }}" class="{{ request()->routeIs('order') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/></svg>
            {{ __('Orders') }}
        </a>
        <a href="{{ $reports }}" class="{{ request()->routeIs('report.*') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/></svg>
            {{ __('Reports') }}
        </a>
        <a href="{{ $more }}" class="{{ request()->routeIs('manage.store') || request()->routeIs('category') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
            {{ __('More') }}
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2"></script>
    @stack('scripts')
</body>
</html>
