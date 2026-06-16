<div class="section full header">
    <div class="profile-head">
        <div class="avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M5.52 19c.64-2.2 1.84-3 3.22-3h6.52c1.38 0 2.58.8 3.22 3"/><circle cx="12" cy="10" r="3"/><circle cx="12" cy="12" r="10"/></svg>
        </div>
        <div class="in">
            <h3 class="name">@if($authUser) {{ $authUser->name }} @else {{ __('Guest') }} @endif</h3>
            <h5 class="subtext">@if($authUser) {{ $authUser->email }} @else {{ __('Sign in / Register') }} @endif</h5>
        </div>
    </div>
</div>

<div class="section full">


    <ul class="listview link-listview mb-1">

        <li>
            <a href="{{ route('mobile.account') }}" class="item sidenav-close">
                <div class="in">
                    {{  __('My Account') }}
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('mobile.favourite') }}" class="item sidenav-close">
                <div class="in">
                    {{  __('Favourites') }}
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('mobile.cart') }}" class="item sidenav-close">
                <div class="in">
                    {{  __('Cart') }}
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('mobile.orders') }}" class="item sidenav-close">
                <div class="in">
                    {{  __('Orders') }}
                </div>
            </a>
        </li>

 
    </ul>

</div>



<div class="appFooter" style="border:none;">
    <div class="pr-4">{{ __('App Version') }} : {{ config('app.version') }}</div>
</div>