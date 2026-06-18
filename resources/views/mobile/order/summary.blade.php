<form act-on="submit" act-request="{{ route('mobile.place.order') }}">
    <div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
        <div class="left">
            <a href="#" class="back headerButton item" style="color: #fff !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            </a>
        </div>
        <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ __('Quotation Summary') }}</div>
    </div>
     
    <!-- App Capsule -->
    <div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 180px !important;">

        <!-- Delivery / Client Site Info -->
        <div class="section full mt-2 px-3">
            <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">{{ __('Client & Site Details') }}</div>
            <div class="card shadow-sm mb-3" style="border-radius: 8px; background: #fff; padding: 16px; border-left: 4px solid #D4AF37;">
                <input type="hidden" name="address_type" value="Shipping">
                
                <!-- Client Name Input -->
                <div class="form-group basic mb-2">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Client Name <span class="text-danger">*</span></label>
                    <input type="text" name="address_name" required class="form-control" placeholder="Enter Client Name" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                </div>

                <!-- Client Mobile Input -->
                <div class="form-group basic mb-2">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Client Mobile <span class="text-danger">*</span></label>
                    <input type="tel" name="address_mobile" required class="form-control" placeholder="Enter 10-digit Mobile Number" pattern="[0-9]{10}" maxlength="10" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                </div>

                <!-- Address Line 1 -->
                <div class="form-group basic mb-2">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Address Line 1 <span class="text-danger">*</span></label>
                    <input type="text" name="address_line_1" required class="form-control" placeholder="Building/House No, Street name" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                </div>

                <!-- Address Line 2 -->
                <div class="form-group basic mb-2">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Address Line 2 <span class="text-danger">*</span></label>
                    <input type="text" name="address_line_2" required class="form-control" placeholder="Locality, Area" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                </div>

                <!-- Address Line 3 / Landmark -->
                <div class="form-group basic mb-2">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Landmark / Address Line 3</label>
                    <input type="text" name="address_line_3" class="form-control" placeholder="Nearby landmark (optional)" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                </div>

                <!-- Transportation Zone Select -->
                <div class="form-group basic mb-0">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Transportation Zone <span class="text-danger">*</span></label>
                    <select id="location_select" name="location_id" required class="form-control custom-select" style="border-radius: 4px; border: 1px solid #d1d5db; height: auto; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                        <option value="" disabled selected>Select Transportation Zone</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" data-charge="{{ $location->delivery_charge }}" data-min="{{ $location->minimum_cart_amount }}" data-free-limit="{{ $location->delivery_cart_amount }}">
                                {{ _local($location->name, $location->local_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

     
        <!-- Slabs / Materials List -->
        @if($products && $products->count() > 0)
        <div class="section full products-list px-3 mb-3">
            <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">Selected Slabs</div>
            <ul class="listview image-listview media border-0 shadow-sm" style="background: #fff; border-radius: 8px; padding: 0;">
                @foreach($products as $product)
                    <li style="border-bottom: 1px solid #f3f4f6; list-style: none; padding: 10px 12px;">
                        <div class="item d-flex align-items-center">
                            <div class="imageWrapper" style="width: 50px; height: 50px; border-radius: 4px; overflow: hidden; background: #e5e7eb;">
                                @if( $product->image )
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;"/>
                                @else
                                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;"/>
                                @endif
                            </div>
                            <div class="in pl-2 flex-grow-1" style="padding-left: 10px !important;">
                                <div class="title" style="font-size: 12px; font-weight: 700; color: #111827; font-family: 'Inter', sans-serif;">{{ strtoupper($product->name) }}</div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div class="details" style="font-size: 11px; color: #6B7280;">{{ productExistsInCart($product->id, $product->minimum_quantity )}} {{ _local($product->unit->name, $product->unit->local_name) }} @ ₹{{ number_format($product->selling_price, 0) }}/{{ _local($product->unit->name, $product->unit->local_name) }}</div>
                                    <div class="price" style="font-weight: 700; color: #111827; font-size: 12px;">{!! priceFormat(productTotalSellingPriceInCart( $product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper) ) , '₹') !!}</div>     
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Quotation Cost Breakdown Card -->
        <div class="section mt-2 px-3">
            <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">Quotation Breakdown</div>
            <div class="card shadow-sm border-0" style="border-radius: 8px; background: #fff; padding: 16px;">
                
                <!-- Subtotal (Material Value) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="font-size: 13px; color: #4B5563; font-family: 'Inter', sans-serif;">Material Cost Subtotal</div>
                    <div style="font-weight: 700; color: #111827; font-size: 13px;">₹<span id="subtotal_val">{{ priceFormat(cartTotalAmount(), '') }}</span></div>
                </div>

                <!-- Cutting Charge Input -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="font-size: 13px; color: #4B5563; font-family: 'Inter', sans-serif;">Cutting / Sizing Charges</div>
                    <div style="width: 100px;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border-right: none; font-size: 11px; padding: 2px 4px;">₹</span></div>
                            <input type="number" id="cutting_charge" name="cutting_charge" value="0" class="form-control text-right" style="border-left: none; padding-left: 0; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: bold;" min="0">
                        </div>
                    </div>
                </div>

                <!-- Transportation Charge Input -->
                @php
                    $initialDeliveryCharge = 0;
                @endphp
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="font-size: 13px; color: #4B5563; font-family: 'Inter', sans-serif;">Transportation Charges</div>
                    <div style="width: 100px;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border-right: none; font-size: 11px; padding: 2px 4px;">₹</span></div>
                            <input type="number" id="transportation_charge" name="transportation_charge" value="{{ $initialDeliveryCharge }}" class="form-control text-right" style="border-left: none; padding-left: 0; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: bold;" min="0">
                        </div>
                    </div>
                </div>

                <!-- Installation Charge Input -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="font-size: 13px; color: #4B5563; font-family: 'Inter', sans-serif;">Installation Charges</div>
                    <div style="width: 100px;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border-right: none; font-size: 11px; padding: 2px 4px;">₹</span></div>
                            <input type="number" id="installation_charge" name="installation_charge" value="0" class="form-control text-right" style="border-left: none; padding-left: 0; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: bold;" min="0">
                        </div>
                    </div>
                </div>

                <!-- Manual Discount Input -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="font-size: 13px; color: #4B5563; font-family: 'Inter', sans-serif;">Special Gallery Discount</div>
                    <div style="width: 100px;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border-right: none; font-size: 11px; padding: 2px 4px; color: #EF4444;">-₹</span></div>
                            <input type="number" id="manual_discount" name="manual_discount" value="0" class="form-control text-right text-danger" style="border-left: none; padding-left: 0; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: bold;" min="0">
                        </div>
                    </div>
                </div>

                <hr style="border-color: #f3f4f6; margin: 12px 0;">

                <!-- Grand Total -->
                <div class="d-flex justify-content-between align-items-center">
                    <div style="font-size: 14px; font-weight: 700; color: #1F2937; font-family: 'Playfair Display', serif;">Grand Total Estimate</div>
                    <div style="font-weight: 800; color: #D4AF37; font-size: 18px; font-family: 'Playfair Display', serif;">₹<span id="grand_total_val">{{ priceFormat(cartTotalAmount(), '') }}</span></div>
                </div>
            </div>
            
            <div style="font-size: 11px; color: #6B7280; margin-top: 8px; text-align: center; font-family: 'Inter', sans-serif;">
                Quotation will be valid for 30 days. Slab vein patterns may vary from samples.
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
     
    <div style="position: fixed; bottom: 68px; left: 0; right: 0; z-index: 998; background: transparent; padding: 0 10px;">
        <div id="min-cart-warning" class="message" style="background-color: #EF4444; color: #fff; position: relative; margin-bottom: 5px; display: none;">
         <span>{{ __('Minimum order value must be') }}</span>
         <span id="min-cart-warning-amount"></span>
        </div>

        <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37; margin: 0;">
            <div class="checkout-btn-info">
                <div class="info-small"><span class="cart-item-count text-light">{{ cartItemCount() }}</span> {{ cartTotalUnitLabel() }}</div>
                <div class="info-large mt-0"><span class="cart-total-sqft text-light">{{ cartTotalSqft() }}</span> {{ cartTotalUnitLabel() }}</div>
            </div>
     
            <!-- Continue shopping link block -->
            <a id="continue-shopping-btn" href="{{ route('mobile.home') }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: none; align-items: center;">
                {{ __('Continue Shopping') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>

            <!-- Submit button block -->
            <button type="submit" id="checkout-btn" class="checkout-btn-title" style="border: none; background: transparent; color: #fff; font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center; cursor: pointer; text-align: left; padding: 0;">
                {{ __('Place Quotation') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
     
        </div>
    </div>
</form>

<script>
    (function() {
        const subtotal = parseFloat("{{ cartTotalAmount() }}".replace(/,/g, '')) || 0;
        const cuttingEl = document.getElementById('cutting_charge');
        const transEl = document.getElementById('transportation_charge');
        const instEl = document.getElementById('installation_charge');
        const discEl = document.getElementById('manual_discount');
        const grandTotalText = document.getElementById('grand_total_val');
        const locationSelect = document.getElementById('location_select');
        const minCartWarning = document.getElementById('min-cart-warning');
        const minCartWarningAmount = document.getElementById('min-cart-warning-amount');
        const continueShoppingBtn = document.getElementById('continue-shopping-btn');
        const checkoutBtn = document.getElementById('checkout-btn');

        function formatCurrency(val) {
            return val.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updateGrandTotal() {
            const cutting = parseFloat(cuttingEl.value) || 0;
            const transportation = parseFloat(transEl.value) || 0;
            const installation = parseFloat(instEl.value) || 0;
            const discount = parseFloat(discEl.value) || 0;

            const grandTotal = Math.max(0, subtotal + cutting + transportation + installation - discount);
            grandTotalText.textContent = formatCurrency(grandTotal);
        }

        function handleLocationChange() {
            const selectedOption = locationSelect.options[locationSelect.selectedIndex];
            if (!selectedOption || selectedOption.value === "") {
                transEl.value = 0;
                minCartWarning.style.display = 'none';
                continueShoppingBtn.style.display = 'none';
                checkoutBtn.style.display = 'flex';
                updateGrandTotal();
                return;
            }

            const charge = parseFloat(selectedOption.getAttribute('data-charge')) || 0;
            const minCart = parseFloat(selectedOption.getAttribute('data-min')) || 0;
            const freeLimit = parseFloat(selectedOption.getAttribute('data-free-limit')) || 0;

            // Calculate delivery charge
            let deliveryCharge = charge;
            if (freeLimit > 0 && subtotal >= freeLimit) {
                deliveryCharge = 0;
            }

            transEl.value = deliveryCharge;

            // Check minimum cart limit
            if (minCart > 0 && subtotal < minCart) {
                minCartWarning.style.display = 'block';
                minCartWarningAmount.textContent = '₹' + formatCurrency(minCart);
                continueShoppingBtn.style.display = 'flex';
                checkoutBtn.style.display = 'none';
            } else {
                minCartWarning.style.display = 'none';
                continueShoppingBtn.style.display = 'none';
                checkoutBtn.style.display = 'flex';
            }

            updateGrandTotal();
        }

        if (cuttingEl) cuttingEl.addEventListener('input', updateGrandTotal);
        if (transEl) transEl.addEventListener('input', updateGrandTotal);
        if (instEl) instEl.addEventListener('input', updateGrandTotal);
        if (discEl) discEl.addEventListener('input', updateGrandTotal);
        if (locationSelect) locationSelect.addEventListener('change', handleLocationChange);

        // Run initially in case of preselected value or standard load
        if (locationSelect) {
            handleLocationChange();
        }
    })();
</script>