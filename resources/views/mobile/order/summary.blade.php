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
    <div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 120px;">

        <!-- Delivery / Client Site Info -->
        <div class="section full mt-2 px-3">
            <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">{{ __('Client & Site Details') }}</div>
            <div class="deliver-to shadow-sm mb-3" style="background: #fff; border-radius: 8px; padding: 12px; border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-center mb-1">
                    <span class="badge badge-primary mr-2" style="background-color: #1F2937; color: #D4AF37; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 3px 6px;">{{ __( $defaultAddress->type ) }}</span>
                    <div class="name" style="font-weight: 700; color: #111827; font-family: 'Playfair Display', serif; font-size: 14px;">{{ $defaultAddress->name }}</div>
                </div>
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact: {{ $defaultAddress->mobile }}</div>
                <div style="font-size: 12px; color: #374151;">{{ $defaultAddress->line_1 }}, {{ $defaultAddress->line_2 }}</div>
                @if($defaultAddress->line_3)
                <div style="font-size: 12px; color: #6B7280; font-style: italic;">Landmark: {{ $defaultAddress->line_3 }}</div>
                @endif
                <div style="font-size: 12px; color: #D4AF37; font-weight: bold; margin-top: 4px;">Transportation Zone: {{ _local($defaultAddress->location->name,$defaultAddress->location->local_name) }}</div>
            </div>
        </div>

        <!-- Quotation Meta Inputs Card -->
        <div class="section full px-3 mb-3">
            <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">{{ __('Quotation Options') }}</div>
            <div class="card shadow-sm border-0" style="border-radius: 8px; background: #fff; padding: 16px;">
                <!-- Project Type Selection -->
                <div class="form-group basic mb-3">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Project Type <span class="text-danger">*</span></label>
                    <select name="project_type" required class="form-control custom-select" style="border-radius: 4px; border: 1px solid #d1d5db; height: auto; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
                        <option value="Residential" selected>Residential Interior</option>
                        <option value="Commercial">Commercial Project</option>
                        <option value="Villa">Luxury Villa</option>
                        <option value="Hotel">Hotel / Resort</option>
                        <option value="Apartment">Apartment Complex</option>
                    </select>
                </div>

                <!-- Quotation Type Radio Buttons -->
                <div class="form-group mb-3">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase; display: block; margin-bottom: 6px;">Quotation Type</label>
                    <div class="custom-control custom-radio d-inline mr-3">
                        <input type="radio" id="q_type_new" name="quotation_type" value="New" checked class="custom-control-input">
                        <label class="custom-control-label" for="q_type_new" style="font-size: 13px; font-family: 'Inter', sans-serif;">New Project</label>
                    </div>
                    <div class="custom-control custom-radio d-inline">
                        <input type="radio" id="q_type_rev" name="quotation_type" value="Revision" class="custom-control-input">
                        <label class="custom-control-label" for="q_type_rev" style="font-size: 13px; font-family: 'Inter', sans-serif;">Quotation Revision</label>
                    </div>
                </div>

                <!-- Architect / Designer Input -->
                <div class="form-group basic mb-0">
                    <label class="label" for="architect_name" style="font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase;">Architect / Designer Name</label>
                    <input type="text" id="architect_name" name="architect_name" class="form-control" placeholder="Enter Architect name if applicable" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
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
                                    <div class="details" style="font-size: 11px; color: #6B7280;">{{ productExistsInCart($product->id, $product->minimum_quantity )}} Sq.Ft @ ₹{{ number_format($product->selling_price, 0) }}/Sq.Ft</div>
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
                    if($defaultAddress->location && $defaultAddress->location->delivery_charge > 0) {
                        if(!($defaultAddress->location->delivery_cart_amount && $defaultAddress->location->delivery_cart_amount <= cartTotalAmount())) {
                            $initialDeliveryCharge = $defaultAddress->location->delivery_charge;
                        }
                    }
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
                    <div style="font-weight: 800; color: #D4AF37; font-size: 18px; font-family: 'Playfair Display', serif;">₹<span id="grand_total_val">{{ priceFormat($cartTotalAmount = cartTotalAmount() + $initialDeliveryCharge, '') }}</span></div>
                </div>
            </div>
            
            <div style="font-size: 11px; color: #6B7280; margin-top: 8px; text-align: center; font-family: 'Inter', sans-serif;">
                Quotation will be valid for 30 days. Slab vein patterns may vary from samples.
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
     
    <div class="appBottomMenu" style="border-top: none;">
        @if($defaultAddress->location && $defaultAddress->location->minimum_cart_amount > cartTotalAmount() )
        <div class="message" style="background-color: #EF4444; color: #fff;">
         <span>{{ __('Minimum order value must be') }}</span>
         <span>{!! priceFormat($defaultAddress->location->minimum_cart_amount) !!}</span>
        </div>
        @endif

        <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37;">
            <div class="checkout-btn-info">
                <div class="info-small"><span class="cart-item-count text-light">{{ cartItemCount() }}</span> {{ __('Slabs') }}</div>
                <div class="info-large mt-0"><span class="cart-total-sqft text-light">{{ cartTotalSqft() }}</span> Sq.Ft</div>
            </div>
     
            @if($defaultAddress->location && $defaultAddress->location->minimum_cart_amount > cartTotalAmount() )
                <a href="{{ route('mobile.home') }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
                    {{ __('Continue Shopping') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            @else
                <button type="submit" id="checkout-btn" class="checkout-btn-title" style="border: none; background: transparent; color: #fff; font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center; cursor: pointer; text-align: left; padding: 0;">
                    {{ __('Place Quotation') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            @endif
     
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

        function updateGrandTotal() {
            const cutting = parseFloat(cuttingEl.value) || 0;
            const transportation = parseFloat(transEl.value) || 0;
            const installation = parseFloat(instEl.value) || 0;
            const discount = parseFloat(discEl.value) || 0;

            const grandTotal = Math.max(0, subtotal + cutting + transportation + installation - discount);
            grandTotalText.textContent = grandTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        if (cuttingEl) cuttingEl.addEventListener('input', updateGrandTotal);
        if (transEl) transEl.addEventListener('input', updateGrandTotal);
        if (instEl) instEl.addEventListener('input', updateGrandTotal);
        if (discEl) discEl.addEventListener('input', updateGrandTotal);
    })();
</script>