<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="back headerButton item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
        </a>
    </div>
    <div class="pageTitle">{{ __('Order Summary') }}</div>
</div>
<div class="appCapsule">

    <div class="empty-products">

        <div class="error-page mt-5">
            <div id="party" class="icon-box text-primary ">
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="10"/>
                    <path class="cube" d="M25.8782 54.3972L40.3592 65.2145C42.5423 66.8452 45.6292 66.4276 47.3005 64.2755L72.9536 31.2424" stroke="currentColor" stroke-width="10"/>
                </svg>
            </div>
            <h1 class="title">{{ __('Order Placed Successfully') }}</h1>
            <div class="text mb-5">
                {{ __('We received your order and our team will reach you soon.') }}
            </div>

        </div>
    </div>
</div>

@include('mobile/layout/bottom-menu')
