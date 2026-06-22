@if(!authUser())
<div id="auth-modal-overlay" class="knm-overlay knm-hidden" aria-hidden="true">
    <div class="knm-overlay__backdrop" id="auth-modal-backdrop"></div>
    <div class="knm-drawer knm-modal--desktop-card" id="auth-modal-card" role="dialog" aria-modal="true" aria-labelledby="auth-modal-title">
        <div class="knm-drawer__head">
            <div>
                <div id="auth-modal-title" class="knm-acp-title">Sign in</div>
                <p class="auth-modal-subtitle knm-muted knm-small knm-mt-4">Access your orders and continue checkout.</p>
            </div>
            <button type="button" class="knm-btn knm-btn--icon knm-btn--ghost" id="auth-modal-close" aria-label="Close">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="knm-drawer__body">
            <div id="auth-modal-alert" class="knm-hidden knm-auth-alert" role="alert"></div>
            <div class="knm-auth-tab-wrap knm-mb-4" id="auth-tab-wrap">
                <button type="button" data-auth-tab="signin" class="knm-btn knm-btn--sm knm-btn--primary">Sign in</button>
                <button type="button" data-auth-tab="signup" class="knm-btn knm-btn--sm knm-btn--ghost">Register</button>
            </div>
            <form id="auth-signin-form" class="knm-stack knm-gap-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input class="knm-auth-input" name="login" type="text" placeholder="Username" required autocomplete="username">
                <input class="knm-auth-input" name="password" type="password" placeholder="Password" required autocomplete="current-password">
                <button type="submit" class="knm-btn knm-btn--primary knm-btn--block">Continue</button>
            </form>
            <form id="auth-signup-form" class="knm-stack knm-gap-3 knm-hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input class="knm-auth-input" name="name" type="text" placeholder="Full name" required>
                <input class="knm-auth-input" name="mobile" type="text" inputmode="numeric" placeholder="Mobile number" required>
                <input class="knm-auth-input" name="password" type="password" placeholder="Password" required>
                <input class="knm-auth-input" name="password_confirmation" type="password" placeholder="Confirm password" required>
                <button type="submit" class="knm-btn knm-btn--primary knm-btn--block">Send OTP</button>
            </form>
            <form id="auth-otp-form" class="knm-stack knm-gap-3 knm-hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" id="otp-user-id">
                <input class="knm-auth-input" name="otp" type="text" inputmode="numeric" maxlength="6" placeholder="6-digit OTP" required>
                <button type="submit" class="knm-btn knm-btn--primary knm-btn--block">Verify OTP</button>
                <button type="button" class="knm-btn knm-btn--ghost knm-btn--block" id="auth-resend-otp">Resend OTP</button>
            </form>
            <p class="knm-muted knm-small knm-mt-4">By continuing you agree to the store terms.</p>
        </div>
    </div>
</div>
@endif
