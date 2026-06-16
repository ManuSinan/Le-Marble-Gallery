{{-- Nnitec-style header: logo | search | user bar --}}
<header class="nnitec-header">
    <a href="{{ route('home') }}" class="nnitec-logo" aria-label="{{ config('app.name') }}">
        <img src="{{ asset('assets/frontend/images/logo.png') }}" alt="" />

    </a>

    <form class="nnitec-search-wrap" action="{{ route('website.products.shop') }}" method="get">
        <svg class="nnitec-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/>
        </svg>
        <input type="search" name="search" class="nnitec-search" placeholder="{{ __('Search paintings...') }}" value="{{ request('search') }}" />
        <button type="submit" class="nnitec-search-btn" aria-label="{{ __('Search') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
        </button>
    </form>

    <div class="nnitec-user-bar">
        <a href="{{ route('website.cart') }}" class="nnitec-user-icon cart-icon" aria-label="{{ __('Cart') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            @if(cartItemCount(null) > 0)
            <span class="nnitec-cart-count">{{ cartItemCount(null) }}</span>
            @endif
        </a>
        <a href="{{ route('website.favourite') }}" class="nnitec-user-icon" aria-label="{{ __('Favourites') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </a>
        @if (Auth::guest())
        <a href="#" class="nnitec-user-icon" data-toggle="modal" data-target="#loginModal" aria-label="{{ __('Sign in') }}" style="cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
        </a>
        @else
        @php $isAdmin = authUser() && authUser()->role && authUser()->role->type === 'private'; @endphp
        @if ($isAdmin)
        <a href="{{ route('dashboard') }}" class="nnitec-user-icon" aria-label="{{ __('Admin Panel') }}" title="{{ __('Admin Panel') }}" style="margin-right: 4px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </a>
        @endif
        <div class="header-user-dropdown" style="position: relative;">
            <a href="{{ route('website.account') }}" class="nnitec-user-icon d-flex align-items-center" aria-label="{{ __('Account') }}" style="text-decoration: none; gap: 8px;">
                <span class="nnitec-user-name">{{ Auth::user()->name ?? 'Account' }}</span>
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #666;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
            </a>
        </div>
        @endif
    </div>
</header>

{{-- Mobile menu toggle (for categories) --}}
<button type="button" class="d-md-none btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#menu-modal" style="border-radius: 12px;">
    {{ __('All Categories') }}
</button>
