<!-- App Bottom Menu -->
@php $routeName = request()->route()->getName(); @endphp
<div class="appBottomMenu glass-effect">
    <!-- Home Tab -->
    <a href="{{ route('mobile.home') }}" class="item {{ ($routeName == 'mobile.home') ? 'active' : '' }}">
        <div class="col">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <div class="text">{{ __('Home') }}</div>
        </div>
    </a>

    <!-- Quotations Tab -->
    <a href="{{ route('mobile.orders') }}" class="item {{ ($routeName == 'mobile.orders' || $routeName == 'mobile.order.detail' || $routeName == 'mobile.order.success' || $routeName == 'mobile.cart') ? 'active' : '' }}">
        <div class="col">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                <line x1="9" y1="12" x2="15" y2="12"></line>
                <line x1="9" y1="16" x2="15" y2="16"></line>
            </svg>
            <div class="text">{{ __('Quotations') }}</div>
        </div>
    </a>

    <!-- Payments Tab -->
    <a href="#" onclick="alert('Payment Tracking is under development.'); return false;" class="item">
        <div class="col">
            <div class="icon" style="font-size: 20px; font-weight: 700; line-height: 24px; height: 24px; width: 24px; text-align: center; display: inline-block; font-family: 'Inter', sans-serif;">₹</div>
            <div class="text">{{ __('Payments') }}</div>
        </div>
    </a>

    <!-- Account Tab -->
    <a href="{{ route('mobile.account') }}" class="item {{ ($routeName == 'mobile.account' || $routeName == 'mobile.address' || $routeName == 'mobile.address.create' || $routeName == 'mobile.address.edit' || $routeName == 'mobile.change.password') ? 'active' : '' }}">
        <div class="col">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <div class="text">{{ __('Account') }}</div>
        </div>
    </a>
</div>