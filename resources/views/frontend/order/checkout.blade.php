@extends('frontend/layout/main')
@section('seo')
<title>{{ __('Checkout') }}</title>
@endsection
@section('style')
<style>
/* Checkout – clean, minimal; no grey */
#calculation .payment-summary-card,
.payment-summary-card,
.payment-summary-card.card {
    background: #fff !important;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 24px !important;
    margin-bottom: 20px !important;
    border: 1px solid rgba(0,0,0,0.06) !important;
}
.checkout-section-content .form-control,
.checkout-section-content .custom-select {
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 8px;
}
.checkout-section-content .form-control:focus,
.checkout-section-content .custom-select:focus {
    border-color: #c98a25;
    outline: none;
    box-shadow: 0 0 0 2px rgba(201, 138, 37, 0.15);
}
.checkout-section-content .card {
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 8px;
}
.checkout-section-content .card-header {
    background: #fff;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.checkout-section-content .box {
    background: #fff !important;
    border: 1px solid rgba(0,0,0,0.06) !important;
}
.checkout-delivery-summary .badge { font-size: 11px; }
.checkout-delivery-change-btn { font-weight: 600; }
.btn-add-new {
    background: #fff !important;
    color: #c98a25 !important;
    border: 1px solid #c98a25 !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
}
.btn-add-new:hover {
    background: rgba(201, 138, 37, 0.06) !important;
    color: #b87a1e !important;
    border-color: #b87a1e !important;
}

.payment-summary-content {
    display: flex !important;
    flex-direction: column !important;
    gap: 20px !important;
}

.payment-amount-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    margin-bottom: 16px !important;
}

.payment-amount-row:last-of-type {
    margin-bottom: 0 !important;
}

.payment-label-group {
    display: flex !important;
    flex-direction: column !important;
    gap: 4px !important;
}

.payment-label {
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #212529 !important;
    line-height: 1.4 !important;
}

.payment-tax-note {
    font-size: 12px !important;
    color: #6c757d !important;
    font-weight: 400 !important;
    font-style: normal !important;
    line-height: 1.3 !important;
}

.payment-value {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #212529 !important;
    text-align: right !important;
    line-height: 1.4 !important;
}

.payment-value-final {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #212529 !important;
}

.payment-buttons {
    display: flex !important;
    flex-direction: column !important;
    gap: 12px !important;
    margin-top: 8px !important;
}

.btn-checkout {
    width: 100% !important;
    padding: 14px 20px !important;
    background: #fb641b !important;
    color: #fff !important;
    border: none !important;
    border-radius: 2px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    text-align: center !important;
    cursor: pointer !important;
    transition: background 0.2s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
}

.btn-checkout:hover:not(:disabled) {
    background: #e55a14 !important;
    color: #fff !important;
    text-decoration: none !important;
}

.btn-checkout:disabled {
    background: #f5f5f5 !important;
    color: #999 !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
}

.btn-continue-shopping {
    width: 100% !important;
    padding: 14px 20px !important;
    background: #fff !important;
    color: #2874f0 !important;
    border: none !important;
    border-radius: 0 !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: center !important;
    cursor: pointer !important;
    transition: opacity 0.2s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
}

.btn-continue-shopping:hover {
    background: #fff !important;
    color: #2874f0 !important;
    text-decoration: none !important;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .payment-summary-card {
        margin-top: 20px !important;
    }
}

@media (max-width: 576px) {
    .payment-summary-card {
        padding: 20px !important;
    }
    
    .payment-label {
        font-size: 14px !important;
    }
    
    .payment-value {
        font-size: 14px !important;
    }
    
    .btn-checkout,
    .btn-continue-shopping {
        padding: 12px 18px !important;
        font-size: 14px !important;
    }
}
</style>
@endsection
@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
      <h1>{{ __('Checkout') }}</h1>
      <nav>
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Checkout') }}</li>
         </ol>
      </nav>
      <!-- col.// -->
   </div>
</section>

<section>
	<div class="container">
		<div id="payment-form"></div>
	</div>
</section>

<section class="checkout-section-content section-content my-4">
	<form act-on="submit" act-request="{{ route('website.place.order') }}">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 mb-4">

					{{-- Deliver to card (same page, like screenshot) --}}
					<div class="card checkout-delivery-card">
						<div class="card-body py-3">
							@if($authUser->address && $authUser->address->count() > 0)
								{{-- Summary view: show selected address with Change --}}
								<div id="checkout-delivery-summary" class="checkout-delivery-summary d-flex align-items-start justify-content-between flex-wrap">
									<div class="checkout-delivery-summary-text">
										<div class="d-flex align-items-center flex-wrap gap-2 mb-1">
											<strong class="text-dark">{{ __('Deliver to') }}:</strong>
											<span id="delivery-summary-name" class="text-dark">{{ $defaultAddress ? $defaultAddress->name : '' }}</span>
											<span id="delivery-summary-pincode" class="text-muted">{{ $defaultAddress ? ($defaultAddress->pincode ? $defaultAddress->pincode->pincode . ($defaultAddress->pincode->area ? ' (' . $defaultAddress->pincode->area . ')' : '') : ($defaultAddress->location ? _local($defaultAddress->location->name, $defaultAddress->location->local_name) : '')) : '' }}</span>
											<span id="delivery-summary-type" class="badge badge-light border text-uppercase">{{ $defaultAddress ? $defaultAddress->type : '' }}</span>
										</div>
										<div id="delivery-summary-address" class="text-muted small">
											@if($defaultAddress)
												{{ $defaultAddress->line_1 }}, {{ $defaultAddress->line_2 }}@if($defaultAddress->line_3), {{ $defaultAddress->line_3 }}@endif
											@endif
										</div>
									</div>
									<button type="button" class="btn btn-link text-primary p-0 ml-2 checkout-delivery-change-btn" aria-expanded="false">{{ __('Change') }}</button>
								</div>
								{{-- Inline address list (hidden until Change clicked) --}}
								<div id="checkout-delivery-options" class="checkout-delivery-options mt-3" style="display: none;">
									<div class="d-flex align-items-center justify-content-between mb-2">
										<span class="text-muted">{{ __('Select or add delivery address') }}</span>
										<button type="button" class="btn btn-sm btn-outline-secondary checkout-delivery-done-btn">{{ __('Done') }}</button>
									</div>
									<div class="row form-group">
										@foreach($authUser->address as $address)
										<div class="col-12 col-md-6">
											<div class="box mb-3 checkout-address-box" style="min-height: 140px;" data-name="{{ e($address->name) }}" data-type="{{ e($address->type) }}" data-mobile="{{ e($address->mobile) }}" data-line1="{{ e($address->line_1) }}" data-line2="{{ e($address->line_2) }}" data-line3="{{ e($address->line_3) }}" data-pincode="{{ e($address->pincode ? $address->pincode->pincode . ($address->pincode->area ? ' (' . $address->pincode->area . ')' : '') : ($address->location ? _local($address->location->name, $address->location->local_name) : '')) }}">
												<div class="custom-control custom-radio d-inline">
													<input type="radio" id="address-{{ $address->id }}" act-on="change" act-request="{{ route('website.cart.calculation') }}" value="{{ $address->id }}" name="delivery_address" @if($address->pincode_id) pincode_id="{{ $address->pincode_id }}" @else location="{{ $address->location_id }}" @endif @if($address->default) {{ 'checked' }} @endif class="custom-control-input checkout-address-radio">
													<label class="custom-control-label" for="address-{{ $address->id }}">{{ $address->type }} → {{ $address->name }}</label>
												</div>
												<div class="mt-2">{{ $address->name }}</div>
												<div>{{ $address->mobile }}</div>
												<div>{{ $address->line_1 }}, {{ $address->line_2 }}</div>
												<div>{{ $address->line_3 }}</div>
												<div>{{ $address->pincode ? $address->pincode->pincode . ($address->pincode->area ? ' (' . $address->pincode->area . ')' : '') : ($address->location ? _local($address->location->name, $address->location->local_name) : '') }}</div>
											</div>
										</div>
										@endforeach
									</div>
									<a href="{{ route('website.address.create') }}" class="btn btn-add-new btn-sm">{{ __('Add New Address') }}</a>
								</div>
							@else
								{{-- No addresses: show Add delivery address with expandable form --}}
								<div id="checkout-delivery-summary" class="checkout-delivery-summary">
									<div class="d-flex align-items-center justify-content-between flex-wrap">
										<div>
											<strong class="text-dark">{{ __('Deliver to') }}:</strong>
											<span class="text-muted">{{ __('Add delivery address') }}</span>
										</div>
										<button type="button" class="btn btn-link text-primary p-0 checkout-delivery-change-btn" aria-expanded="false">{{ __('Add') }}</button>
									</div>
								</div>
								<div id="checkout-delivery-options" class="checkout-delivery-options mt-3" style="display: none;">
									<div class="d-flex align-items-center justify-content-between mb-2">
										<span class="text-muted">{{ __('Enter delivery details') }}</span>
										<button type="button" class="btn btn-sm btn-outline-secondary checkout-delivery-done-btn">{{ __('Done') }}</button>
									</div>
									<div class="section mt-1">
									<div class="form-group">
										<div class="custom-control custom-radio d-inline mr-2">
											<input type="radio" id="type1" name="type" value="Home" checked class="custom-control-input">
											<label class="custom-control-label" for="type1">{{ __('Home') }}</label>
										</div>
										<div class="custom-control custom-radio d-inline">
											<input type="radio" id="type2" name="type" value="Work" class="custom-control-input">
											<label class="custom-control-label" for="type2">{{ __('Work') }}</label>
										</div>
									</div>
									<div class="form-row mb-2">
									<div class="col-12 col-md-6 form-group">
										<div class="input-wrapper">
										@if(isset($usePincode) ? $usePincode : true)
										<label class="label" for="pincode_id">{{ __('Pincode') }} <span class="text-danger">*</span></label>
										<select id="pincode_id" name="pincode_id" act-on="change" act-request="{{ route('website.cart.calculation') }}" required class="form-control custom-select">
											<option value="">{{ __('Select Pincode') }}</option>
											@foreach($pincodes as $pincode)
											<option value="{{ $pincode->id }}" @if($defaultAddress && $defaultAddress->pincode_id == $pincode->id) selected @endif>{{ $pincode->pincode }}@if($pincode->area) ({{ $pincode->area }})@endif</option>
											@endforeach
										</select>
										@else
										<label class="label" for="location_id">{{ __('Location') }} <span class="text-danger">*</span></label>
										<select id="location_id" name="location_id" act-on="change" act-request="{{ route('website.cart.calculation') }}" required class="form-control custom-select">
											<option value="">{{ __('Select Location') }}</option>
											@if(isset($states) && $states->isNotEmpty() && $states->sum(fn($s) => $s->locations->count()) > 0)
											@foreach($states as $state)
												@if($state->locations->isNotEmpty())
												<optgroup label="{{ _local($state->name, $state->local_name) }}">
													@foreach($state->locations as $location)
													<option value="{{ $location->id }}" @if($defaultAddress && $defaultAddress->location_id == $location->id) selected @endif>{{ _local($location->name, $location->local_name) }}</option>
													@endforeach
												</optgroup>
												@endif
											@endforeach
											@elseif(isset($locations))
											@foreach($locations as $location)
											<option value="{{ $location->id }}" @if($defaultAddress && $defaultAddress->location_id == $location->id) selected @endif>{{ _local($location->name, $location->local_name) }}</option>
											@endforeach
											@endif
										</select>
										@endif
										</div>
									</div>
									<div class="col-12 col-md-6 form-group">
										<div class="input-wrapper">
										<label class="label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
										<input type="text" id="name" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}">
										</div>
									</div>
									</div>
									<div class="form-row mb-2">
									<div class="col-12 col-md-6 form-group">
										<div class="input-wrapper">
										<label class="label" for="mobile">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
										<input type="tel" id="mobile" name="mobile" class="form-control" placeholder="{{ __('Enter Mobile Number') }}">
										</div>
									</div>
									<div class="col-12 col-md-6 form-group">
										<div class="input-wrapper">
										<label class="label" for="line_1">{{ __('Address') }} <span class="text-danger">*</span></label>
										<input type="text" id="line_1" name="line_1" required class="form-control" placeholder="{{ __('Enter Address (Eg: House, Company, Building, Villa, Flat No.)') }}">
										</div>
									</div>
									</div>
									<div class="form-row mb-2">
									<div class="col-12 col-md-6 form-group">
										<div class="input-wrapper">
										<label class="label" for="line_2">{{ __('Area and Street') }} <span class="text-danger">*</span></label>
										<input type="text" id="line_2" name="line_2" required class="form-control" placeholder="{{ __('Enter Area and Street (Eg: Road, Avenue, Block No.)') }}">
										</div>
									</div>
									<div class="col-12 col-md-6 form-group">
										<div class="input-wrapper">
										<label class="label" for="line_3">{{ __('Landmark (Optional)') }}</label>
										<input type="text" id="line_3" name="line_3" class="form-control" placeholder="{{ __('Enter Landmark (Optional)') }}">
										</div>
									</div>
									</div>
									</div>
									</div>
								</div>
							@endif

							<div id="minimum-order-value-message">
								@php $defaultDelivery = $defaultAddress && ($defaultAddress->pincode ?? $defaultAddress->location) ? ($defaultAddress->pincode ?? $defaultAddress->location) : null; @endphp
								@if($defaultDelivery && $defaultDelivery->minimum_cart_amount > cartTotalAmount() )
									<div class="minimum-order-value-message">
										<span>{{ __('The minimum order value for this pincode must be') }}</span>
										<span>{!! priceFormat($defaultDelivery->minimum_cart_amount) !!}</span>
									</div>
								@endif
							</div>

							@if($authUser->address && $authUser->address->count() > 0)
								{{-- addresses rendered above in delivery card --}}
							@endif


						</div> <!-- card-body.// -->
					</div>

		
					<div class="card mt-3 d-none">
						<header class="card-header">{{ __('Payment Method') }}</header>
						<div class="card-body">

							<div class="form-group mt-3">
		
						
									<div class="custom-control custom-radio d-inline mr-3">
										<input type="radio" id="cod" name="payment_method" value="cod" class="custom-control-input">
										<label class="custom-control-label" for="cod">{{ __('Cash on Delivery') }}</label>
									</div>  
			
									<div class="custom-control custom-radio d-inline">
										<input type="radio" id="online" name="payment_method" value="online" checked class="custom-control-input">
										<label class="custom-control-label" for="online">{{ __('Pay Now') }}</label>
									</div>  
			

							</div>


						</div> <!-- card-body.// -->
					</div>
		
				</div>

				<!-- col.// -->
				<div id="calculation" class="col-lg-3 mb-4">
					<div class="payment-summary-card">
						<div class="payment-summary-content">
							<div class="payment-amount-row">
								<span class="payment-label">{{ __('Total Amount') }}:</span>
								<span class="payment-value">{!! currency() !!}{!! cartTotalAmount() !!}</span>
							</div>

							@if(($defaultDeliveryAmount ?? 0) > 0)
								<div class="payment-amount-row">
									<div class="payment-label-group">
										<span class="payment-label">{{ __('Final Amount') }}:</span>
										<span class="payment-tax-note">{{ __('(inclusive of all taxes)') }}</span>
									</div>
									<span class="payment-value payment-value-final">{!! currency() !!}{!! priceFormat( ( cartTotalAmount() + ($defaultDeliveryAmount ?? 0) ) , '')  !!}</span>
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
								@if(($defaultMinAmount ?? 0) > cartTotalAmount() )
									<button type="button" class="btn-checkout" disabled>{{ __('Place Order') }}</button>
								@else
									<button type="submit" class="btn-checkout">{{ __('Place Order') }}</button>
								@endif
								<a href="{{ route('home') }}" class="btn-continue-shopping">{{ __('Continue Shopping') }}</a>
							</div>
						</div>
					</div>
				</div>
				<!-- col.// -->

			</div>
		</div>
		<!-- container .//  -->
	</form>
</section>

@section('script')
<script>
(function() {
	var summary = document.getElementById('checkout-delivery-summary');
	var options = document.getElementById('checkout-delivery-options');
	var changeBtn = document.querySelector('.checkout-delivery-change-btn');
	var doneBtn = document.querySelector('.checkout-delivery-done-btn');
	if (!summary || !options) return;

	function showSummary() {
		summary.style.display = '';
		options.style.display = 'none';
		if (changeBtn) changeBtn.setAttribute('aria-expanded', 'false');
	}
	function showOptions() {
		summary.style.display = 'none';
		options.style.display = 'block';
		if (changeBtn) changeBtn.setAttribute('aria-expanded', 'true');
	}

	if (changeBtn) {
		changeBtn.addEventListener('click', function() { showOptions(); });
	}
	if (doneBtn) {
		doneBtn.addEventListener('click', function() { showSummary(); });
	}

	// Update summary when a different address is selected (has-addresses case)
	var radios = document.querySelectorAll('.checkout-address-radio');
	var boxes = document.querySelectorAll('.checkout-address-box');
	radios.forEach(function(radio, i) {
		radio.addEventListener('change', function() {
			var box = boxes[i];
			if (!box) return;
			var nameEl = document.getElementById('delivery-summary-name');
			var pincodeEl = document.getElementById('delivery-summary-pincode');
			var typeEl = document.getElementById('delivery-summary-type');
			var addressEl = document.getElementById('delivery-summary-address');
			if (nameEl) nameEl.textContent = box.dataset.name || '';
			if (pincodeEl) pincodeEl.textContent = box.dataset.pincode || '';
			if (typeEl) typeEl.textContent = (box.dataset.type || '').toUpperCase();
			if (addressEl) {
				var parts = [box.dataset.line1, box.dataset.line2].filter(Boolean);
				if (box.dataset.line3) parts.push(box.dataset.line3);
				addressEl.textContent = parts.join(', ');
			}
		});
	});

	// Prevent Enter from submitting the form early; move to next field instead.
	(function () {
		var form = document.querySelector('form[act-on="submit"][act-request]');
		if (!form) return;

		function isFocusable(el) {
			if (!el) return false;
			if (el.disabled) return false;
			if (el.getAttribute('aria-hidden') === 'true') return false;
			if (el.tabIndex === -1) return false;
			if (el.type === 'hidden') return false;
			if (el.offsetParent === null && getComputedStyle(el).position !== 'fixed') return false;
			return true;
		}

		function focusNext(current) {
			var focusables = Array.prototype.slice.call(
				form.querySelectorAll('input, select, textarea, button')
			).filter(isFocusable);

			var idx = focusables.indexOf(current);
			if (idx === -1) return false;

			for (var i = idx + 1; i < focusables.length; i++) {
				var el = focusables[i];
				if (el.tagName === 'BUTTON' && (el.type === 'submit' || el.getAttribute('type') === 'submit')) continue;
				el.focus();
				return true;
			}
			return false;
		}

		form.addEventListener('keydown', function (e) {
			if (e.key !== 'Enter') return;
			var target = e.target;
			if (!target) return;
			if (target.tagName === 'TEXTAREA') return;
			if (target.tagName === 'BUTTON') return;
			if (!form.contains(target)) return;

			if (focusNext(target)) {
				e.preventDefault();
			}
		}, true);
	})();
})();
</script>
@endsection