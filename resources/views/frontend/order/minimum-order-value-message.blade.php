@if(isset($delivery) && $delivery->minimum_cart_amount > cartTotalAmount() )
    <div class="minimum-order-value-message">
        <span>{{ __('The minimum order value for this pincode must be') }}</span>
        <span>{!! priceFormat($delivery->minimum_cart_amount) !!}</span>
    </div>
@else
    <div> </div>
@endif
