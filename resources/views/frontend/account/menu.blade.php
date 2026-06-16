@php $routeName = Request::route()->getName(); @endphp
<div class="account-nav-card">
    <header class="account-nav-header">
        <h6 class="account-nav-title">{{ __('Account') }}</h6>
    </header>
    <nav class="account-nav-body">
        <a class="account-nav-link {{ $routeName == 'website.order' || $routeName == 'website.order.detail' ? 'active' : '' }}" href="{{ route('website.order') }}">{{ __('Your Orders') }}</a>
        <a class="account-nav-link {{ $routeName == 'website.favourite' ? 'active' : '' }}" href="{{ route('website.favourite') }}">{{ __('Your Favourites') }}</a>
        <a class="account-nav-link {{ $routeName == 'website.address' || $routeName == 'website.address.create' || $routeName == 'website.address.edit' ? 'active' : '' }}" href="{{ route('website.address') }}">{{ __('Your Addresses') }}</a>
        <a class="account-nav-link {{ $routeName == 'website.edit.profile' ? 'active' : '' }}" href="{{ route('website.edit.profile') }}">{{ __('Edit Profile') }}</a>
        <a class="account-nav-link {{ $routeName == 'website.change.password' ? 'active' : '' }}" href="{{ route('website.change.password') }}">{{ __('Login & security') }}</a>
    </nav>
</div>
