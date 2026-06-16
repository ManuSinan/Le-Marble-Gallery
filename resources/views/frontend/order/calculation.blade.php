<div class="payment-summary-card">
    <div class="payment-summary-content">
        <div class="payment-amount-row">
            <span class="payment-label">{{ __('Total Amount') }}:</span>
            <span class="payment-value">{!! currency() !!}{!! cartTotalAmount() !!}</span>
        </div>

        @if($delivery->delivery_charge > 0)
            <div class="payment-amount-row">
                <div class="payment-label-group">
                    <span class="payment-label">{{ __('Final Amount') }}:</span>
                    <span class="payment-tax-note">{{ __('(inclusive of all taxes)') }}</span>
                </div>
                <span class="payment-value payment-value-final">{!! currency() !!}{!! priceFormat( ( cartTotalAmount() + $delivery->delivery_charge ) , '')  !!}</span>
            </div>
        @else
            <div class="payment-amount-row">
                <div class="payment-label-group">
                    <span class="payment-label">{{ __('Final Amount') }}:</span>
                    <span class="payment-tax-note">{{ __('(inclusive of all taxes)') }}</span>
                </div>
                <span class="payment-value payment-value-final">{!! currency() !!}{!! cartTotalAmount() !!}</span>
            </div>
        @endif

        <div class="payment-buttons">
            @if($delivery->minimum_cart_amount > cartTotalAmount() )
                <button type="button" class="btn-checkout" disabled>{{ __('Proceed to Checkout') }}</button>
            @else
                <button type="submit" class="btn-checkout">{{ __('Proceed to Checkout') }}</button>
            @endif
            <a href="{{ route('home') }}" class="btn-continue-shopping">{{ __('Continue Shopping') }}</a>
        </div>
    </div>
</div>