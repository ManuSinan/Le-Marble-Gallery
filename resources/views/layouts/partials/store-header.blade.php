@php
    $siteName = $storeName ?? config('app.name', 'Lee Marble Gallery');
    $authViewer = authUser();
    $accountUrl = $authViewer ? route('website.account') : route('signin');
    $ordersUrl = $authViewer ? route('website.order') : route('signin');
    $isAuthPage = request()->routeIs('signin') || request()->routeIs('signup');
    $hideAccountInNav = request()->routeIs('website.order') || request()->routeIs('website.order.detail');
    $searchVal = $search ?? request('search', '');
    $sortbyVal = $sortby ?? request('sortby', 'subject');
@endphp
<style>
    @media (min-width: 1024px) {
        #menu-button { display: none !important; }
    }
    /* Mobile header action visibility */
    @media (max-width: 1023px) {
        .knm-mobile-only { display: inline-flex !important; }
        .knm-desktop-only { display: none !important; }
    }
    @media (min-width: 1024px) {
        .knm-mobile-only { display: none !important; }
        .knm-desktop-only { display: inline-flex !important; }
    }
    @media (max-width: 480px) {
        .knm-brand span { font-size: 13.5px !important; white-space: normal !important; overflow: visible !important; }
    }
</style>
<header class="knm-header">
    <div class="knm-container knm-header__inner">
        <button type="button" class="knm-btn knm-btn--icon knm-btn--ghost" id="menu-button" aria-label="Open menu">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <a href="{{ route('home') }}" class="knm-brand">
            <img src="{{ asset('assets/frontend/images/logo.png') }}" alt="" style="max-height: 32px;">
            <span>{{ \Illuminate\Support\Str::limit($siteName, 28) }}</span>
        </a>

        <nav class="knm-nav-desktop" aria-label="Account links">
            <a href="{{ route('home') }}" class="knm-btn knm-btn--sm knm-btn--ghost">Home</a>
            @if ($isAuthPage)
                @if(request()->routeIs('signin'))
                    <a href="{{ route('signin') }}" class="knm-btn knm-btn--sm knm-btn--ghost is-active">Sign in</a>
                    <a href="{{ route('signup') }}" class="knm-btn knm-btn--sm knm-btn--ghost">Register</a>
                @else
                    <a href="{{ route('signin') }}" class="knm-btn knm-btn--sm knm-btn--ghost">Sign in</a>
                    <a href="{{ route('signup') }}" class="knm-btn knm-btn--sm knm-btn--ghost is-active">Register</a>
                @endif
            @endif
        </nav>

        <div class="knm-header-actions">
            @if (!$authViewer)
                <button type="button" class="knm-btn knm-btn--sm knm-btn--ghost" data-auth-trigger="signin">Sign&nbsp;in</button>
                <button type="button" class="knm-btn knm-btn--sm knm-btn--primary" data-auth-trigger="signup">Register</button>
            @endif
            @if ($authViewer)
                <a href="{{ $ordersUrl }}" class="knm-btn knm-btn--sm knm-btn--ghost knm-desktop-only @if(request()->routeIs('website.order') || request()->routeIs('website.order.detail')) is-active @endif">Order</a>
                <a href="{{ route('signout') }}" class="knm-btn knm-btn--sm knm-btn--ghost knm-desktop-only">Sign out</a>
                <a href="{{ route('signout') }}" class="knm-btn knm-btn--icon knm-btn--ghost knm-mobile-only" aria-label="Sign out" title="Sign out">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </a>
            @endif
            @if($authViewer)
                <a href="{{ $ordersUrl }}" class="knm-btn knm-btn--icon knm-btn--ghost knm-mobile-only" id="mobile-order-icon" aria-label="Order History" title="Order History">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                </a>
            @endif
            <button type="button" class="knm-btn knm-btn--icon knm-btn--ghost" id="cart-button" aria-label="View order">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M6 6 5 3H2"/></svg>
                <span class="knm-sr-only">Items in order:</span>
                <span id="cart-count-badge" class="knm-header-actions__badge" aria-live="polite">{{ $cartCount ?? 0 }}</span>
            </button>
        </div>
    </div>
</header>
