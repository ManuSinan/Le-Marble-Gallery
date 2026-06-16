@if(!authUser())
<script>
(function () {
    const authModalOverlay = document.getElementById('auth-modal-overlay');
    if (!authModalOverlay) return;

    const authModalClose = document.getElementById('auth-modal-close');
    const authModalAlert = document.getElementById('auth-modal-alert');
    const authModalTitle = document.getElementById('auth-modal-title');
    const authTabWrap = document.getElementById('auth-tab-wrap');
    const authSigninForm = document.getElementById('auth-signin-form');
    const authSignupForm = document.getElementById('auth-signup-form');
    const authOtpForm = document.getElementById('auth-otp-form');
    const otpUserIdInput = document.getElementById('otp-user-id');
    const authResendOtp = document.getElementById('auth-resend-otp');

    const routes = {
        signin: @json(route('signin')),
        home: @json(route('home')),
        signupStore: @json(route('signup.store')),
        signupVerify: @json(route('signup.verify')),
        signupRequest: @json(route('signup.request')),
    };

    function closeMobileNav() {
        const so = document.getElementById('sidebar-overlay');
        if (so && !so.classList.contains('knm-hidden')) {
            so.classList.add('knm-hidden');
            so.setAttribute('aria-hidden', 'true');
        }
    }

    function openAuthModal(tab) {
        authModalOverlay.classList.remove('knm-hidden');
        authModalOverlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('knm-body-lock');
        switchAuthTab(tab || 'signin');
        clearAuthAlert();
        closeMobileNav();
    }

    function closeAuthModal() {
        authModalOverlay.classList.add('knm-hidden');
        authModalOverlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('knm-body-lock');
        clearAuthAlert();
    }

    function clearAuthAlert() {
        if (!authModalAlert) return;
        authModalAlert.textContent = '';
        authModalAlert.className = 'knm-hidden knm-auth-alert';
    }

    function showAuthAlert(text, type) {
        if (!authModalAlert) return;
        authModalAlert.textContent = text;
        authModalAlert.className = 'knm-auth-alert knm-mb-4 ' + (type === 'success' ? 'knm-auth-alert--ok' : 'knm-auth-alert--err');
    }

    function switchAuthTab(tab) {
        if (!authSigninForm || !authSignupForm || !authOtpForm) return;
        const isSignin = tab === 'signin';
        const isSignup = tab === 'signup';
        const isOtp = tab === 'otp';
        authSigninForm.classList.toggle('knm-hidden', !isSignin);
        authSignupForm.classList.toggle('knm-hidden', !isSignup);
        authOtpForm.classList.toggle('knm-hidden', !isOtp);
        if (authTabWrap) authTabWrap.classList.toggle('knm-hidden', isOtp);
        if (authModalTitle) authModalTitle.textContent = isOtp ? 'Verify OTP' : (isSignin ? 'Sign in' : 'Register');
        const subtitle = document.querySelector('.auth-modal-subtitle');
        if (subtitle) {
            subtitle.textContent = isOtp
                ? 'Enter the code sent to your mobile to finish registration.'
                : (isSignin ? 'Access your orders and continue checkout.' : 'Create your account and verify with OTP.');
        }
        document.querySelectorAll('[data-auth-tab]').forEach((btn) => {
            if (isOtp) return;
            const active = btn.getAttribute('data-auth-tab') === tab;
            btn.classList.toggle('knm-btn--primary', active);
            btn.classList.toggle('knm-btn--ghost', !active);
        });
    }

    async function postJson(url, formData) {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
            body: formData,
            credentials: 'same-origin',
        });
        const data = await response.json().catch(() => ({}));
        return { response, data };
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-auth-trigger]');
        if (!trigger) return;
        e.preventDefault();
        openAuthModal(trigger.getAttribute('data-auth-trigger') || 'signin');
    });

    authModalClose?.addEventListener('click', closeAuthModal);
    document.getElementById('auth-modal-backdrop')?.addEventListener('click', closeAuthModal);

    document.querySelectorAll('[data-auth-tab]').forEach((btn) => {
        btn.addEventListener('click', () => switchAuthTab(btn.getAttribute('data-auth-tab')));
    });

    authSigninForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearAuthAlert();
        const { response, data } = await postJson(routes.signin, new FormData(authSigninForm));
        if (!response.ok) {
            showAuthAlert(data?.errors?.login?.[0] || 'Sign in failed.');
            return;
        }
        window.location.href = data.redirect || routes.home;
    });

    authSignupForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearAuthAlert();
        const { response, data } = await postJson(routes.signupStore, new FormData(authSignupForm));
        if (!response.ok) {
            const firstError = Object.values(data?.errors || {})[0]?.[0] || 'Registration failed.';
            showAuthAlert(firstError);
            return;
        }
        otpUserIdInput.value = data.user_id || '';
        showAuthAlert(data.message || 'OTP sent.', 'success');
        switchAuthTab('otp');
    });

    authOtpForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearAuthAlert();
        const { response, data } = await postJson(routes.signupVerify, new FormData(authOtpForm));
        if (!response.ok) {
            const firstError = Object.values(data?.errors || {})[0]?.[0] || 'Verification failed.';
            showAuthAlert(firstError);
            return;
        }
        window.location.href = data.redirect || routes.home;
    });

    authResendOtp?.addEventListener('click', async function () {
        clearAuthAlert();
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        formData.append('id', otpUserIdInput?.value || '');
        const { response, data } = await postJson(routes.signupRequest, formData);
        if (!response.ok) {
            const firstError = Object.values(data?.errors || {})[0]?.[0] || 'Could not resend OTP.';
            showAuthAlert(firstError);
            return;
        }
        showAuthAlert(data.message || 'OTP resent.', 'success');
    });

    window.knmOpenAuthModal = openAuthModal;
    window.knmCloseAuthModal = closeAuthModal;
})();
</script>
@endif
