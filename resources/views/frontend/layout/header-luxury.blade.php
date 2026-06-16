{{-- Dark luxury header: logo left | centered menu | cart/profile right --}}
<header class="luxury-nav {{ isset($innerPage) && $innerPage ? 'luxury-nav-inner' : '' }}" id="luxuryNav">
  <a href="{{ route('home') }}" class="luxury-logo" aria-label="{{ config('app.name') }}">
    {{ config('app.name') }}
  </a>
  <button type="button" class="luxury-menu-toggle d-md-none" id="luxuryMenuToggle" aria-label="{{ __('Menu') }}">
    <span></span><span></span><span></span>
  </button>
  <nav class="luxury-menu" id="luxuryMenu">
    <a href="{{ route('website.products.shop') }}">{{ __('Browse Books') }}</a>
    <a href="{{ route('website.about.us') }}">{{ __('About') }}</a>
    <a href="{{ route('home') }}#featured">{{ __('Featured') }}</a>
    <a href="{{ route('home') }}#request">{{ __('Request a Book') }}</a>
  </nav>
  <div class="luxury-actions">
    <a href="{{ route('website.favourite') }}" class="luxury-icon d-none d-md-flex" aria-label="{{ __('Favourites') }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
    </a>
    <a href="{{ route('website.cart') }}" class="luxury-icon" aria-label="{{ __('Cart') }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      @if(cartItemCount(null) > 0)
      <span class="luxury-cart-count">{{ cartItemCount(null) }}</span>
      @endif
    </a>
    @if (Auth::guest())
    <a href="#" class="luxury-icon" data-toggle="modal" data-target="#loginModal" aria-label="{{ __('Sign in') }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </a>
    @else
    <a href="{{ route('website.account') }}" class="luxury-icon" aria-label="{{ __('Account') }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </a>
    @endif
  </div>
</header>
