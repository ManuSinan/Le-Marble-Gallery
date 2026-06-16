<!-- Log in or Sign up Modal - Dark theme -->
<div class="modal fade auth-modal-dark" id="loginModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content auth-modal-content">
            <div class="modal-body auth-modal-body">
                <button type="button" class="auth-modal-close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <h2 class="auth-modal-title">{{ __('Sign in') }}</h2>
                <p class="auth-modal-subtitle">{{ __('Customers use mobile number and password. Admin uses username admin.') }}</p>

                <div class="auth-modal-social">
                    @if(config('services.google.client_id'))
                    <a href="{{ route('auth.google') }}" class="auth-modal-btn auth-modal-btn-google" style="text-decoration: none;">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        <span>{{ __('Continue with Google') }}</span>
                    </a>
                    @endif
                   
                </div>

                

                <form id="loginModalForm" method="post" action="{{ route('signin') }}" class="auth-modal-form">
                    @csrf
                    <div class="auth-modal-field">
                        <input type="text" id="loginEmail" name="login" required class="auth-modal-input" placeholder="{{ __('Username / Mobile Number') }}" autocomplete="username" autofocus>
                        <div id="loginEmailError" class="auth-modal-error" style="display: none;"></div>
                    </div>
                    <div class="auth-modal-field">
                        <input type="password" id="loginPassword" name="password" required class="auth-modal-input" placeholder="{{ __('Password') }}" autocomplete="current-password">
                        <div id="loginPasswordError" class="auth-modal-error" style="display: none;"></div>
                    </div>
                    <div class="auth-modal-footer-link">
                        <a href="{{ route('password.reset') }}" data-dismiss="modal">{{ __('Forgot password?') }}</a>
                    </div>
                    <button type="submit" class="auth-modal-submit">{{ __('Continue') }}</button>
                </form>

                <p class="auth-modal-switch">
                    {{ __('Don\'t have an account?') }}
                    <a href="{{ route('signup') }}">{{ __('Create one') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-modal-dark .modal-dialog {
    max-width: 440px;
    margin: 1.75rem auto;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 3.5rem);
}
.auth-modal-dark .modal-content {
    width: 100%;
}
.auth-modal-body {
    text-align: center;
}
.auth-modal-body .auth-modal-form,
.auth-modal-body .auth-modal-social {
    text-align: left;
    max-width: 100%;
}
.auth-modal-body .auth-modal-form {
    margin: 0 auto;
}
.auth-modal-content {
    background: #1a1612 !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 16px !important;
    overflow: hidden;
}
.auth-modal-body {
    padding: 32px 28px 28px !important;
    position: relative;
}
.auth-modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    color: rgba(255,255,255,0.6);
    cursor: pointer;
    border-radius: 8px;
    transition: color 0.2s, background 0.2s;
}
.auth-modal-close:hover {
    color: #fff;
    background: rgba(255,255,255,0.08);
}
.auth-modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #fff;
    margin: 0 0 8px;
}
.auth-modal-subtitle {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.6);
    line-height: 1.5;
    margin: 0 0 24px;
}
.auth-modal-social {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.auth-modal-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding: 12px 16px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: #fff;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s;
}
.auth-modal-btn:hover:not(:disabled) {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.15);
}
.auth-modal-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.auth-modal-btn-phone:hover:not(:disabled) {
    background: rgba(184,149,107,0.2);
    border-color: rgba(184,149,107,0.4);
}
.auth-modal-divider {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 20px 0;
}
.auth-modal-divider::before,
.auth-modal-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.12);
}
.auth-modal-divider span {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.5);
    font-weight: 500;
}
.auth-modal-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.auth-modal-field {
    margin-bottom: 0;
}
.auth-modal-input {
    width: 100%;
    padding: 14px 16px;
    background: #2d2720 !important;
    border: 1px solid rgba(184,149,107,0.25) !important;
    border-radius: 10px;
    color: #f2e8d8 !important;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.auth-modal-input::placeholder {
    color: rgba(242,232,216,0.5) !important;
}
.auth-modal-input:focus {
    outline: none !important;
    border-color: #b8956b !important;
    box-shadow: 0 0 0 2px rgba(184,149,107,0.2) !important;
}
/* Override browser autofill skyblue background */
.auth-modal-input:-webkit-autofill,
.auth-modal-input:-webkit-autofill:hover,
.auth-modal-input:-webkit-autofill:focus,
.auth-modal-input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #2d2720 inset !important;
    box-shadow: 0 0 0 30px #2d2720 inset !important;
    -webkit-text-fill-color: #f2e8d8 !important;
    caret-color: #f2e8d8;
    transition: background-color 5000s ease-in-out 0s;
}
.auth-modal-error {
    font-size: 0.8rem;
    color: #c9a878;
    margin-top: 6px;
}
.auth-modal-footer-link {
    margin: -4px 0 4px;
}
.auth-modal-footer-link a {
    font-size: 0.85rem;
    color: #b8956b;
    text-decoration: none;
}
.auth-modal-footer-link a:hover {
    color: #d4b896;
    text-decoration: underline;
}
.auth-modal-submit {
    width: 100%;
    padding: 14px 24px;
    background: #b8956b !important;
    border: none !important;
    border-radius: 10px;
    color: #1a1612 !important;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, opacity 0.2s;
    margin-top: 4px;
}
.auth-modal-submit:hover {
    background: #c9a87a !important;
}
.auth-modal-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
.auth-modal-switch {
    text-align: center;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.6);
    margin: 20px 0 0;
}
.auth-modal-switch a {
    color: #b8956b;
    font-weight: 500;
    text-decoration: none;
}
.auth-modal-switch a:hover {
    color: #d4b896;
    text-decoration: underline;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginModal = document.getElementById('loginModal');
    const loginForm = document.getElementById('loginModalForm');
    const loginEmailInput = document.getElementById('loginEmail');
    const loginEmailError = document.getElementById('loginEmailError');
    const loginPasswordInput = document.getElementById('loginPassword');
    const loginPasswordError = document.getElementById('loginPasswordError');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(loginForm);
            var submitBtn = loginForm.querySelector('.auth-modal-submit');
            var originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = '{{ __('Please wait...') }}';
            loginEmailError.style.display = 'none';
            loginPasswordError.style.display = 'none';

            fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                if (data.redirect) {
                    $('#loginModal').modal('hide');
                    window.location.href = data.redirect;
                } else if (data.errors) {
                    if (data.errors.email) {
                        loginEmailError.textContent = Array.isArray(data.errors.email) ? data.errors.email[0] : data.errors.email;
                        loginEmailError.style.display = 'block';
                    }
                    if (data.errors.password) {
                        loginPasswordError.textContent = Array.isArray(data.errors.password) ? data.errors.password[0] : data.errors.password;
                        loginPasswordError.style.display = 'block';
                    }
                } else {
                    alert(data.alert && data.alert.text ? data.alert.text : (data.message || '{{ __('Something went wrong. Please try again.') }}'));
                }
            })
            .catch(function(error) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                console.error('Login error:', error);
                alert('{{ __('Something went wrong. Please try again.') }}');
            });
        });
    }

    if (loginEmailInput) loginEmailInput.addEventListener('input', function() { loginEmailError.style.display = 'none'; });
    if (loginPasswordInput) loginPasswordInput.addEventListener('input', function() { loginPasswordError.style.display = 'none'; });

    if (loginModal) {
        loginModal.addEventListener('hidden.bs.modal', function() {
            if (loginForm) {
                loginForm.reset();
                loginEmailError.style.display = 'none';
                loginPasswordError.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('[data-dismiss="modal"][data-toggle="modal"][data-target="#signupModal"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            $('#loginModal').modal('hide');
            setTimeout(function() { $('#signupModal').modal('show'); }, 300);
        });
    });

    document.querySelectorAll('.auth-modal-open-signup').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            $('#loginModal').modal('hide');
            setTimeout(function() { $('#signupModal').modal('show'); }, 300);
        });
    });
});
</script>
