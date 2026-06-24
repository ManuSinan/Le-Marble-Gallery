@extends('simple-bookstore.layout')

@section('title', 'Quotation Checkout | ' . ($storeName ?? config('app.name', 'Lee Marble Gallery')))
@section('description', 'Configure quotation details and place order.')

@section('extra_styles')
<style>
    :root {
        --marble-primary: {{ config('app.theme_primary', '#152B6E') }};
        --marble-accent: #D4AF37;
        --marble-accent-hover: #B8932F;
        --marble-bg: #F8FAFC;
        --marble-text: #1E293B;
        --marble-text-muted: #64748B;
        --marble-border: #E2E8F0;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.6);
        --glass-shadow: 0 8px 32px 0 rgba(15, 23, 42, 0.04);
    }

    body {
        background-color: var(--marble-bg) !important;
        color: var(--marble-text) !important;
        font-family: 'Inter', sans-serif !important;
    }

    .knm-checkout-grid {
        display: grid;
        gap: 32px;
        grid-template-columns: minmax(0, 1.3fr) minmax(360px, 0.8fr);
        align-items: start;
        margin-top: 24px;
    }

    .checkout-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        box-shadow: var(--glass-shadow);
        padding: 32px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid var(--marble-border);
        border-radius: 16px;
        box-shadow: var(--glass-shadow);
        padding: 24px;
        position: sticky;
        top: 96px;
    }

    .form-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--marble-primary);
        margin-bottom: 20px;
        border-bottom: 2px solid var(--marble-accent);
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .knm-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--marble-text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }

    .knm-input, .knm-select, .knm-textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-size: 14px;
        color: var(--marble-text);
        transition: all 0.2s;
        outline: none;
        background: #ffffff;
        font-family: inherit;
    }

    .knm-input:focus, .knm-select:focus, .knm-textarea:focus {
        border-color: var(--marble-primary);
        box-shadow: 0 0 0 3px rgba(21, 43, 110, 0.1);
    }

    /* Cost adjustments form section */
    .adjustment-group {
        background: rgba(241, 245, 249, 0.6);
        border-radius: 12px;
        padding: 16px;
        border: 1px dashed var(--marble-border);
    }

    .adjustment-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 12px;
    }
    .adjustment-row:last-child {
        margin-bottom: 0;
    }

    .adjustment-input-wrap {
        position: relative;
        width: 120px;
    }
    .adjustment-input-wrap span.currency-prefix {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        font-weight: 600;
        color: var(--marble-text-muted);
    }
    .adjustment-input-wrap input {
        padding-left: 22px;
        text-align: right;
        font-weight: 600;
        font-size: 13px;
        height: 38px;
    }

    .selected-slabs-list {
        max-height: 240px;
        overflow-y: auto;
        border: 1px solid var(--marble-border);
        border-radius: 8px;
        padding: 8px;
        background: #F8FAFC;
    }

    .knm-order-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-bottom: 1px solid var(--marble-border);
    }
    .knm-order-item:last-child {
        border-bottom: none;
    }

    .item-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        background: #E2E8F0;
        border: 1px solid var(--marble-border);
    }

    .breakdown-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: var(--marble-text-muted);
        margin-bottom: 8px;
    }

    .breakdown-row.total-row {
        font-size: 18px;
        font-weight: 800;
        color: var(--marble-text);
        border-top: 1px solid var(--marble-border);
        padding-top: 16px;
        margin-top: 12px;
        font-family: 'Playfair Display', serif;
    }

    .warning-alert {
        background-color: #FEF2F2;
        border: 1px solid #FCA5A5;
        color: #991B1B;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 16px;
        display: none;
    }

    @media (max-width: 900px) {
        .knm-checkout-grid {
            grid-template-columns: 1fr;
        }
        .checkout-card {
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="knm-container knm-pb-8" style="padding-top: 40px; padding-bottom: 60px;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 800; color: var(--marble-primary); margin-bottom: 4px;">Salesman Quotation Checkout</h1>
        <p class="knm-muted">Configure shipping zones and quotation details to place the order.</p>
    </div>

    <form method="POST" action="{{ route('simple-bookstore.place-order') }}" id="checkout-form">
        @csrf

        <div class="knm-checkout-grid">
            <div class="checkout-card">
                
                @if ($errors->any())
                    <div class="knm-notice knm-notice--error knm-mb-6" style="margin-bottom: 24px;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Section 1: Client & Project Details -->
                <div class="form-section-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Client & Project Info
                </div>

                <div style="display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-bottom: 24px;">
                    <div>
                        <label for="address_name" class="knm-label">Client Name <span class="text-danger">*</span></label>
                        <input id="address_name" name="address_name" type="text" class="knm-input" value="{{ old('address_name', $authUser->name ?? '') }}" required>
                    </div>
                    <div>
                        <label for="address_mobile" class="knm-label">Client Mobile <span class="text-danger">*</span></label>
                        <input id="address_mobile" name="address_mobile" type="text" inputmode="numeric" class="knm-input" value="{{ old('address_mobile', $authUser->mobile ?? '') }}" required>
                    </div>
                </div>

                <div style="display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-bottom: 24px;">
                    <div>
                        <label for="project_type" class="knm-label">Project Type</label>
                        <select id="project_type" name="project_type" class="knm-select">
                            <option value="Residential" {{ old('project_type') === 'Residential' ? 'selected' : '' }}>Residential</option>
                            <option value="Commercial" {{ old('project_type') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="Industrial" {{ old('project_type') === 'Industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="Other" {{ old('project_type') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="architect_name" class="knm-label">Architect Name (Optional)</label>
                        <input id="architect_name" name="architect_name" type="text" class="knm-input" value="{{ old('architect_name') }}">
                    </div>
                </div>

                <!-- Section 2: Shipping & Logistics -->
                <div class="form-section-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    Logistics & Address
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="location_id" class="knm-label">Transportation Zone <span class="text-danger">*</span></label>
                    <select id="location_id" name="location_id" class="knm-select" required>
                        <option value="" disabled selected>Select Transportation Zone</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" data-charge="{{ $location->delivery_charge }}" data-min="{{ $location->minimum_cart_amount }}" data-free-limit="{{ $location->delivery_cart_amount }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ _local($location->name, $location->local_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-bottom: 24px;">
                    <div>
                        <label for="address_line_1" class="knm-label">Address Line 1 (Building/Street) <span class="text-danger">*</span></label>
                        <input id="address_line_1" name="address_line_1" type="text" class="knm-input" value="{{ old('address_line_1') }}" required>
                    </div>
                    <div>
                        <label for="address_line_2" class="knm-label">Address Line 2 (Area/City) <span class="text-danger">*</span></label>
                        <input id="address_line_2" name="address_line_2" type="text" class="knm-input" value="{{ old('address_line_2') }}" required>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="address_line_3" class="knm-label">Address Line 3 / Landmark (Optional)</label>
                    <input id="address_line_3" name="address_line_3" type="text" class="knm-input" value="{{ old('address_line_3') }}">
                </div>

                <!-- Section 3: Salesman quotation cost elements -->
                <div class="form-section-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    Quotation Cost Adjustments
                </div>

                <div class="adjustment-group">
                    <div class="adjustment-row">
                        <label for="cutting_charge" class="knm-label" style="margin-bottom:0;">Cutting / Sizing Charges</label>
                        <div class="adjustment-input-wrap">
                            <span class="currency-prefix">{{ $currencySymbol }}</span>
                            <input type="number" id="cutting_charge" name="cutting_charge" class="knm-input" value="{{ old('cutting_charge', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="adjustment-row">
                        <label for="transportation_charge" class="knm-label" style="margin-bottom:0;">Transportation Charges</label>
                        <div class="adjustment-input-wrap">
                            <span class="currency-prefix">{{ $currencySymbol }}</span>
                            <input type="number" id="transportation_charge" name="transportation_charge" class="knm-input" value="{{ old('transportation_charge', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="adjustment-row">
                        <label for="installation_charge" class="knm-label" style="margin-bottom:0;">Installation Charges</label>
                        <div class="adjustment-input-wrap">
                            <span class="currency-prefix">{{ $currencySymbol }}</span>
                            <input type="number" id="installation_charge" name="installation_charge" class="knm-input" value="{{ old('installation_charge', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="adjustment-row">
                        <label for="manual_discount" class="knm-label" style="margin-bottom:0; color: #EF4444;">Special Gallery Discount</label>
                        <div class="adjustment-input-wrap">
                            <span class="currency-prefix" style="color: #EF4444;">-{{ $currencySymbol }}</span>
                            <input type="number" id="manual_discount" name="manual_discount" class="knm-input" style="color: #EF4444; border-color: #FCA5A5;" value="{{ old('manual_discount', 0) }}" min="0">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 24px;">
                    <label for="notes" class="knm-label">Special Notes / Terms (Optional)</label>
                    <textarea id="notes" name="notes" class="knm-textarea" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="summary-card">
                <h2 style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: var(--marble-primary); margin-bottom: 16px;">Selected Materials</h2>
                
                <div class="selected-slabs-list knm-mb-6" style="margin-bottom: 20px;">
                    @foreach($items as $item)
                        <div class="knm-order-item">
                            @if($item['product']->image)
                                <img src="{{ asset('uploads/' . $item['product']->image) }}" alt="" class="item-img">
                            @else
                                <div class="item-img" style="display:flex; align-items:center; justify-content:center; font-size:10px; color:var(--marble-text-muted);">Slab</div>
                            @endif
                            <div style="flex:1;">
                                <div style="font-weight:700; font-size:13px; color: var(--marble-text); line-height:1.2;">{{ strtoupper($item['product']->name) }}</div>
                                <div style="font-size:11px; color: var(--marble-text-muted); margin-top:2px;">
                                    {{ $item['quantity'] }} {{ $item['product']->unit->name }} @ {{ $currencySymbol }}{{ number_format($item['product']->selling_price, 0) }}
                                </div>
                            </div>
                            <div style="font-weight:700; font-size:13px; color: var(--marble-text); white-space:nowrap;">
                                {{ $currencySymbol }}{{ number_format($item['line_total'], 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Live calculation breakdown -->
                <div style="background: #F8FAFC; border: 1px solid var(--marble-border); border-radius: 12px; padding: 16px;">
                    <div class="breakdown-row">
                        <span>Material Cost Subtotal</span>
                        <span style="font-weight: 600; color: var(--marble-text);">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>Cutting / Sizing Charges</span>
                        <span style="font-weight: 600; color: var(--marble-text);">+{{ $currencySymbol }}<span id="label_cutting">0.00</span></span>
                    </div>
                    <div class="breakdown-row">
                        <span>Transportation Charges</span>
                        <span style="font-weight: 600; color: var(--marble-text);">+{{ $currencySymbol }}<span id="label_transportation">0.00</span></span>
                    </div>
                    <div class="breakdown-row">
                        <span>Installation Charges</span>
                        <span style="font-weight: 600; color: var(--marble-text);">+{{ $currencySymbol }}<span id="label_installation">0.00</span></span>
                    </div>
                    <div class="breakdown-row">
                        <span>Gallery Discount</span>
                        <span style="font-weight: 600; color: #EF4444;">-{{ $currencySymbol }}<span id="label_discount">0.00</span></span>
                    </div>

                    <div class="breakdown-row total-row">
                        <span>Grand Total Estimate</span>
                        <span style="color: var(--marble-accent);" id="label_grand_total">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>

                <div id="warning_box" class="warning-alert" style="margin-top: 16px;"></div>

                <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="knm-btn knm-btn--primary knm-btn--block" id="submit-button" style="background-color: var(--marble-primary); border-color: var(--marble-primary); border-radius: 8px;">Place Quotation</button>
                    <a href="{{ route('home') }}" class="knm-btn knm-btn--ghost knm-btn--block" id="clear-order-button" style="border-radius: 8px;">Clear Order</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const subtotal = parseFloat("{{ $subtotal }}") || 0;
        const currencySymbol = @json($currencySymbol);

        const form = document.getElementById('checkout-form');
        const locationSelect = document.getElementById('location_id');
        const cuttingInput = document.getElementById('cutting_charge');
        const transInput = document.getElementById('transportation_charge');
        const instInput = document.getElementById('installation_charge');
        const discInput = document.getElementById('manual_discount');

        const labelCutting = document.getElementById('label_cutting');
        const labelTransportation = document.getElementById('label_transportation');
        const labelInstallation = document.getElementById('label_installation');
        const labelDiscount = document.getElementById('label_discount');
        const labelGrandTotal = document.getElementById('label_grand_total');

        const warningBox = document.getElementById('warning_box');
        const submitButton = document.getElementById('submit-button');

        function formatCurrency(val) {
            return Number(val).toFixed(2);
        }

        function updateTotals() {
            const cutting = parseFloat(cuttingInput.value) || 0;
            const transportation = parseFloat(transInput.value) || 0;
            const installation = parseFloat(instInput.value) || 0;
            const discount = parseFloat(discInput.value) || 0;

            labelCutting.textContent = formatCurrency(cutting);
            labelTransportation.textContent = formatCurrency(transportation);
            labelInstallation.textContent = formatCurrency(installation);
            labelDiscount.textContent = formatCurrency(discount);

            const grandTotal = Math.max(0, subtotal + cutting + transportation + installation - discount);
            labelGrandTotal.textContent = currencySymbol + formatCurrency(grandTotal);
        }

        function handleLocationChange() {
            const selectedOption = locationSelect.options[locationSelect.selectedIndex];
            if (!selectedOption || selectedOption.value === "") {
                transInput.value = 0;
                warningBox.style.display = 'none';
                submitButton.disabled = false;
                updateTotals();
                return;
            }

            const charge = parseFloat(selectedOption.getAttribute('data-charge')) || 0;
            const minCart = parseFloat(selectedOption.getAttribute('data-min')) || 0;
            const freeLimit = parseFloat(selectedOption.getAttribute('data-free-limit')) || 0;

            // Calculate delivery/transportation charge
            let deliveryCharge = charge;
            if (freeLimit > 0 && subtotal >= freeLimit) {
                deliveryCharge = 0;
            }

            transInput.value = deliveryCharge;

            // Check minimum cart limit
            if (minCart > 0 && subtotal < minCart) {
                warningBox.textContent = `Transportation Zone requires a minimum material cost of ${currencySymbol}${minCart.toFixed(2)}. Your subtotal is only ${currencySymbol}${subtotal.toFixed(2)}.`;
                warningBox.style.display = 'block';
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
            } else {
                warningBox.style.display = 'none';
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
            }

            updateTotals();
        }

        // Event listeners
        cuttingInput.addEventListener('input', updateTotals);
        transInput.addEventListener('input', updateTotals);
        instInput.addEventListener('input', updateTotals);
        discInput.addEventListener('input', updateTotals);
        locationSelect.addEventListener('change', handleLocationChange);

        // Clear order handler
        document.getElementById('clear-order-button')?.addEventListener('click', function () {
            document.cookie = "__cart=" + encodeURIComponent(JSON.stringify({ products: {} })) + "; path=/; max-age=31556926";
        });

        // Initialize values
        if (locationSelect.selectedIndex > 0) {
            handleLocationChange();
        } else {
            updateTotals();
        }

        // Prevent Enter key from submitting form early
        form.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.tagName !== 'TEXTAREA' && target.tagName !== 'BUTTON' && target.id !== 'submit-button') {
                    e.preventDefault();
                    // Move focus to next input
                    const formElements = Array.from(form.elements).filter(el => !el.disabled && el.tabIndex !== -1 && el.tagName !== 'FIELDSET');
                    const index = formElements.indexOf(target);
                    if (index > -1 && index < formElements.length - 1) {
                        formElements[index + 1].focus();
                    }
                }
            }
        });
    })();
</script>
@endsection
