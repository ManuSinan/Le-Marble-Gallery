@extends('simple-bookstore.layout')

@section('title', 'Checkout | ' . ($storeName ?? config('app.name', 'Lee Marble Gallery')))
@section('description', 'Complete your order with delivery details.')

@section('extra_styles')
<style>
    .knm-checkout-grid { display: grid; gap: 24px; grid-template-columns: minmax(0, 1.2fr) minmax(340px, 0.8fr); align-items: start; }
    .knm-order-item { display: flex; justify-content: space-between; gap: 12px; padding: 16px; border: 1px solid var(--knm-border, #e2e8f0); border-radius: 12px; transition: border-color 0.2s; }
    .knm-order-item:hover { border-color: #cbd5e1; }
    
    /* New Form CSS */
    .knm-label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
    .knm-input, .knm-textarea { display: block; width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; color: #0f172a; transition: all 0.2s; outline: none; background: #fff; font-family: inherit; }
    .knm-input:focus, .knm-textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .knm-textarea { resize: vertical; min-height: 100px; }

    @media (max-width: 900px) {
        .knm-checkout-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="knm-container knm-pb-8" style="padding-top: 48px;">
    <section class="knm-checkout-grid">
        <div class="knm-card">
            <h1 class="knm-acp-title">Checkout</h1>
            <p class="knm-muted knm-mb-6">Add delivery details and place your order.</p>

            @if ($errors->any())
                <div class="knm-notice knm-notice--error knm-mb-6">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('simple-bookstore.place-order') }}" class="knm-stack-4">
                @csrf

                <div style="display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                    <div>
                        <label for="name" class="knm-label">Name</label>
                        <input id="name" name="name" type="text" class="knm-input" value="{{ old('name', $authUser->name ?? '') }}" required>
                    </div>
                    <div>
                        <label for="phone" class="knm-label">Phone Number</label>
                        <input id="phone" name="phone" type="text" inputmode="numeric" class="knm-input" value="{{ old('phone', $authUser->mobile ?? '') }}" required>
                    </div>
                </div>

                <div style="display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                    <div>
                        <label for="school" class="knm-label">School / Madrasa</label>
                        <input id="school" name="school" type="text" class="knm-input" value="{{ old('school') }}">
                    </div>
                    <div>
                        <label for="place" class="knm-label">Place</label>
                        <input id="place" name="place" type="text" class="knm-input" value="{{ old('place') }}" required>
                    </div>
                </div>

                <div style="display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                    <div>
                        <label for="pincode" class="knm-label">Pincode</label>
                        <input id="pincode" name="pincode" type="text" class="knm-input" value="{{ old('pincode') }}">
                    </div>
                    <div>
                        <label for="address" class="knm-label">Address</label>
                        <input id="address" name="address" type="text" class="knm-input" value="{{ old('address') }}" required>
                    </div>
                </div>

                <div>
                    <label for="notes" class="knm-label">Notes (optional)</label>
                    <textarea id="notes" name="notes" class="knm-textarea" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="knm-btn knm-btn--primary" style="flex:1;">Submit Order</button>
                    <a href="{{ route('home') }}" class="knm-btn knm-btn--ghost" id="clear-order-button" style="flex:1;">Clear Order</a>
                </div>
            </form>
        </div>

        <aside class="knm-card">
            <h2 class="knm-acp-title knm-mb-4">Selected Products</h2>
            <div class="knm-stack-2">
                @foreach($items as $item)
                    <div class="knm-order-item">
                        <div>
                            <div style="font-weight:700; color: #0f172a; margin-bottom: 4px;">{{ $item['product']->name }}</div>
                            <div class="knm-muted knm-small">{{ $item['class_name'] }} • {{ $item['subject_name'] }}</div>
                            <div class="knm-muted knm-small">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        <div style="font-weight:700; color: #1e3a8a; white-space:nowrap;">
                            {{ $currencySymbol }}{{ number_format($item['line_total'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--knm-border, #e2e8f0); display:flex; justify-content:space-between; font-weight:700; font-size: 18px; color: #0f172a;">
                <span>Subtotal</span>
                <span style="color: #059669;">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
            </div>
        </aside>
    </section>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('clear-order-button')?.addEventListener('click', function () {
        document.cookie = "__cart=" + encodeURIComponent(JSON.stringify({ products: {} })) + "; path=/; max-age=31556926";
    });

    // Prevent Enter from submitting the form early; move to next field instead.
    (function () {
        var form = document.querySelector('form.knm-stack-4');
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
                // Skip submit buttons unless we're at the end.
                if (el.tagName === 'BUTTON' && (el.type === 'submit' || el.getAttribute('type') === 'submit')) continue;
                el.focus();
                return true;
            }
            return false;
        }

        form.addEventListener(
            'keydown',
            function (e) {
                if (e.key !== 'Enter') return;
                var target = e.target;
                if (!target) return;
                if (target.tagName === 'TEXTAREA') return;
                if (target.tagName === 'BUTTON') return;

                // Only intercept Enter for fields inside this form.
                if (!form.contains(target)) return;

                // Move focus forward; if we can't, allow normal submit.
                if (focusNext(target)) {
                    e.preventDefault();
                }
            },
            true
        );
    })();
</script>
@endsection
