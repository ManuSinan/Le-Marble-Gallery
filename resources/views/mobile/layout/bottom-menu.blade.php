<!-- App Bottom Menu — MG style: white bar, navy/blue active -->
@php $routeName = request()->route()->getName(); @endphp
<div class="appBottomMenu" style="position: fixed; bottom: 0; left: 0; right: 0; height: 68px !important; display: none; align-items: center !important; justify-content: center !important; background:#ffffff; border-top:1px solid #e5e9f2; box-shadow:0 -2px 12px rgba(21,43,110,0.08); z-index: 99999999 !important;">

    <!-- Home Tab -->
    <a href="{{ route('mobile.home') }}" data-tab="home" class="item {{ ($routeName == 'mobile.home' || $routeName == 'mobile') ? 'active' : '' }}"
       style="color: {{ ($routeName == 'mobile.home' || $routeName == 'mobile') ? '#152B6E' : '#9ca3af' }}; display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; padding:8px 4px; text-decoration:none;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="{{ ($routeName == 'mobile.home' || $routeName == 'mobile') ? '#152B6E' : 'none' }}"
                 stroke="{{ ($routeName == 'mobile.home' || $routeName == 'mobile') ? '#152B6E' : '#9ca3af' }}"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span style="font-size:10px; font-family:'Inter',sans-serif; font-weight:{{ ($routeName == 'mobile.home' || $routeName == 'mobile') ? '700' : '400' }}; color:inherit;">{{ __('Home') }}</span>
        </div>
        @if($routeName == 'mobile.home' || $routeName == 'mobile')
        <div class="active-indicator" style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:32px; height:3px; background:#152B6E; border-radius:0 0 3px 3px;"></div>
        @endif
    </a>

    <!-- Quotations Tab -->
    @php $ordersActive = in_array($routeName, ['mobile.orders','mobile.order.detail','mobile.order.success','mobile.cart']); @endphp
    <a href="{{ route('mobile.orders') }}" data-tab="orders" class="item {{ $ordersActive ? 'active' : '' }}"
       style="color: {{ $ordersActive ? '#152B6E' : '#9ca3af' }}; display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; padding:8px 4px; text-decoration:none; position:relative;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $ordersActive ? '#152B6E' : '#9ca3af' }}"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                <line x1="9" y1="12" x2="15" y2="12"/>
                <line x1="9" y1="16" x2="15" y2="16"/>
            </svg>
            <span style="font-size:10px; font-family:'Inter',sans-serif; font-weight:{{ $ordersActive ? '700' : '400' }}; color:inherit;">{{ __('Orders') }}</span>
        </div>
        @if($ordersActive)
        <div class="active-indicator" style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:32px; height:3px; background:#152B6E; border-radius:0 0 3px 3px;"></div>
        @endif
    </a>

    <!-- Products Tab -->
    @php $productsActive = in_array($routeName, ['mobile.products','mobile.product']); @endphp
    <a href="{{ route('mobile.products') }}" data-tab="products" class="item {{ $productsActive ? 'active' : '' }}"
       style="color: {{ $productsActive ? '#152B6E' : '#9ca3af' }}; display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; padding:8px 4px; text-decoration:none; position:relative;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $productsActive ? '#152B6E' : '#9ca3af' }}"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
            <span style="font-size:10px; font-family:'Inter',sans-serif; font-weight:{{ $productsActive ? '700' : '400' }}; color:inherit;">{{ __('Products') }}</span>
        </div>
        @if($productsActive)
        <div class="active-indicator" style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:32px; height:3px; background:#152B6E; border-radius:0 0 3px 3px;"></div>
        @endif
    </a>

    <!-- Account Tab -->
    @php $accountActive = in_array($routeName, ['mobile.account','mobile.address','mobile.address.create','mobile.address.edit','mobile.change.password']); @endphp
    <a href="{{ route('mobile.account') }}" data-tab="account" class="item {{ $accountActive ? 'active' : '' }}"
       style="color: {{ $accountActive ? '#152B6E' : '#9ca3af' }}; display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; padding:8px 4px; text-decoration:none; position:relative;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $accountActive ? '#152B6E' : '#9ca3af' }}"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <span style="font-size:10px; font-family:'Inter',sans-serif; font-weight:{{ $accountActive ? '700' : '400' }}; color:inherit;">{{ __('Account') }}</span>
        </div>
        @if($accountActive)
        <div class="active-indicator" style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:32px; height:3px; background:#152B6E; border-radius:0 0 3px 3px;"></div>
        @endif
    </a>
</div>