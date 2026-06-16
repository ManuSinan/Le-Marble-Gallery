<!-- OTP Verification Modal - New Design -->
<div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content otp-v2-content">
            <button type="button" class="otp-v2-close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

            <div class="otp-v2-icon">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="28" cy="28" r="26" stroke="currentColor" stroke-width="2" opacity="0.3"/>
                    <path d="M28 18v20M18 28h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>

            <h2 class="otp-v2-title">{{ __('Verify your number') }}</h2>
            <p class="otp-v2-desc">
                {{ __('We sent a 6-digit code to') }} <span id="otpMobileNumber" class="otp-v2-phone"></span>
            </p>

            <form id="otpVerificationForm" method="POST" action="{{ route('signup.verify') }}" class="otp-v2-form">
                @csrf
                <input type="hidden" id="otpUserId" name="id" value="">
                <input type="hidden" id="otpInput" name="otp" value="">

                <div class="otp-v2-boxes">
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-v2-box" data-index="0" autocomplete="one-time-code">
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-v2-box" data-index="1">
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-v2-box" data-index="2">
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-v2-box" data-index="3">
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-v2-box" data-index="4">
                    <input type="text" inputmode="numeric" maxlength="1" class="otp-v2-box" data-index="5">
                </div>

                <div id="otpError" class="otp-v2-error" style="display: none;"></div>

                <button type="submit" class="otp-v2-submit">
                    <span class="otp-v2-submit-text">{{ __('Verify') }}</span>
                </button>

                <p class="otp-v2-resend">
                    {{ __('Didn\'t get the code?') }}
                    <a href="#" id="resendOtpLink" class="otp-v2-resend-link">{{ __('Resend') }}</a>
                    <span id="otpResendTimer" class="otp-v2-timer" style="display: none;"></span>
                </p>
            </form>
        </div>
    </div>
</div>

<style>
.otp-v2-content {
    background: linear-gradient(180deg, #2a231b 0%, #1a1612 100%);
    border: 1px solid rgba(184,149,107,0.2);
    border-radius: 24px;
    padding: 48px 40px 40px;
    max-width: 420px;
    margin: 0 auto;
    position: relative;
    box-shadow: 0 24px 48px rgba(0,0,0,0.4);
}
.otp-v2-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    border: none;
    background: rgba(255,255,255,0.06);
    border-radius: 12px;
    color: rgba(255,255,255,0.6);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.otp-v2-close:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.otp-v2-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(184,149,107,0.15);
    border-radius: 20px;
    color: #b8956b;
}
.otp-v2-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #f2e8d8;
    text-align: center;
    margin: 0 0 8px;
}
.otp-v2-desc {
    font-size: 0.95rem;
    color: rgba(242,232,216,0.65);
    text-align: center;
    margin: 0 0 32px;
    line-height: 1.5;
}
.otp-v2-phone {
    color: #b8956b;
    font-weight: 600;
}
.otp-v2-boxes {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 24px;
}
.otp-v2-box {
    width: 52px;
    height: 60px;
    background: rgba(45,39,32,0.8);
    border: 2px solid rgba(184,149,107,0.25);
    border-radius: 12px;
    color: #f2e8d8;
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s;
}
.otp-v2-box:focus {
    outline: none;
    border-color: #b8956b;
    box-shadow: 0 0 0 3px rgba(184,149,107,0.2);
}
.otp-v2-box.filled {
    border-color: rgba(184,149,107,0.5);
}
.otp-v2-error {
    font-size: 0.85rem;
    color: #c9a878;
    text-align: center;
    margin: -16px 0 20px;
}
.otp-v2-submit {
    width: 100%;
    padding: 16px 24px;
    background: #b8956b;
    border: none;
    border-radius: 14px;
    color: #1a1612;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 24px;
}
.otp-v2-submit:hover:not(:disabled) {
    background: #c9a87a;
}
.otp-v2-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
.otp-v2-resend {
    text-align: center;
    font-size: 0.9rem;
    color: rgba(242,232,216,0.55);
    margin: 0;
}
.otp-v2-resend-link {
    color: #b8956b;
    text-decoration: none;
    font-weight: 500;
}
.otp-v2-resend-link:hover {
    color: #d4b896;
    text-decoration: underline;
}
.otp-v2-resend-link.disabled {
    color: rgba(242,232,216,0.4);
    pointer-events: none;
}
.otp-v2-timer {
    margin-left: 4px;
    color: rgba(242,232,216,0.5);
}
@media (max-width: 480px) {
    .otp-v2-content { padding: 40px 24px 32px; }
    .otp-v2-box { width: 44px; height: 52px; font-size: 1.25rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpModal = document.getElementById('otpVerificationModal');
    const otpForm = document.getElementById('otpVerificationForm');
    const otpInput = document.getElementById('otpInput');
    const otpBoxes = document.querySelectorAll('.otp-v2-box');
    const otpUserIdInput = document.getElementById('otpUserId');
    const otpMobileNumber = document.getElementById('otpMobileNumber');
    const otpError = document.getElementById('otpError');
    const resendOtpLink = document.getElementById('resendOtpLink');
    const resendTimer = document.getElementById('otpResendTimer');

    function syncOtpInput() {
        otpInput.value = Array.from(otpBoxes).map(b => b.value).join('');
    }

    function updateBoxStates() {
        otpBoxes.forEach(b => {
            b.classList.toggle('filled', b.value.length > 0);
        });
    }

    if (otpModal) {
        otpModal.addEventListener('show.bs.modal', function() {
            const userId = sessionStorage.getItem('signupUserId');
            const mobileNumber = sessionStorage.getItem('signupMobileNumber');

            if (userId) otpUserIdInput.value = userId;

            if (mobileNumber) {
                const formatted = mobileNumber.length === 10 ? '+91 ' + mobileNumber.substring(0, 5) + ' ' + mobileNumber.substring(5) : mobileNumber;
                otpMobileNumber.textContent = formatted;
            } else {
                otpMobileNumber.textContent = '{{ __('your mobile number') }}';
            }

            otpBoxes.forEach(b => { b.value = ''; });
            syncOtpInput();
            updateBoxStates();
            otpError.style.display = 'none';

            setTimeout(function() {
                if (otpBoxes[0]) otpBoxes[0].focus();
            }, 300);
        });
    }

    otpBoxes.forEach((box, i) => {
        box.addEventListener('input', function(e) {
            const val = this.value.replace(/[^0-9]/g, '');
            this.value = val.slice(-1);
            updateBoxStates();
            otpError.style.display = 'none';

            if (val && i < 5) otpBoxes[i + 1].focus();
            syncOtpInput();
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                otpBoxes[i - 1].focus();
            }
        });

        box.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach((ch, j) => {
                if (otpBoxes[j]) otpBoxes[j].value = ch;
            });
            updateBoxStates();
            syncOtpInput();
            if (pasted.length > 0) otpBoxes[Math.min(pasted.length, 5)].focus();
        });
    });

    if (otpForm) {
        let isSubmitting = false;

        otpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (isSubmitting) return false;

            syncOtpInput();
            const otpValue = otpInput.value;

            if (!otpValue || otpValue.length !== 6) {
                otpError.textContent = '{{ __('Please enter the 6-digit code') }}';
                otpError.style.display = 'block';
                return false;
            }

            if (!otpUserIdInput.value) {
                const userIdFromStorage = sessionStorage.getItem('signupUserId');
                if (userIdFromStorage) {
                    otpUserIdInput.value = userIdFromStorage;
                } else {
                    alert('{{ __('Session expired. Please try again.') }}');
                    $('#otpVerificationModal').modal('hide');
                    $('#signupModal').modal('show');
                    return false;
                }
            }

            isSubmitting = true;
            const formData = new FormData(otpForm);
            const submitBtn = otpForm.querySelector('.otp-v2-submit');
            const submitText = submitBtn.querySelector('.otp-v2-submit-text');
            const originalText = submitText.textContent;

            otpError.style.display = 'none';
            submitBtn.disabled = true;
            submitText.textContent = '{{ __('Verifying...') }}';

            fetch(otpForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                const ct = response.headers.get("content-type");
                if (!response.ok) {
                    if (ct && ct.includes("application/json")) return response.json().then(err => Promise.reject(err));
                    return Promise.reject(new Error('Server error: ' + response.status));
                }
                return ct && ct.includes("application/json") ? response.json() : Promise.reject(new Error('Invalid response'));
            })
            .then(data => {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitText.textContent = originalText;

                if (data.errors && data.errors.otp) {
                    otpError.textContent = Array.isArray(data.errors.otp) ? data.errors.otp[0] : data.errors.otp;
                    otpError.style.display = 'block';
                } else if (data.alert && data.alert.icon === 'error') {
                    alert(data.alert.text || '{{ __('Something went wrong. Please try again.') }}');
                } else if (data.success || data.alert) {
                    let redirectUrl = data.redirect || data.alert?.redirect || '{{ url("/") }}';
                    if (redirectUrl && !redirectUrl.startsWith('http')) {
                        redirectUrl = window.location.origin + (redirectUrl.startsWith('/') ? redirectUrl : '/' + redirectUrl);
                    }
                    sessionStorage.removeItem('signupUserId');
                    sessionStorage.removeItem('signupMobileNumber');
                    $('#otpVerificationModal').modal('hide');
                    setTimeout(function() {
                        window.location.replace(redirectUrl || window.location.origin);
                    }, 300);
                } else {
                    var msg = (data.alert && data.alert.text) ? data.alert.text : (data.message) ? data.message : null;
                    if (!msg && data.errors && data.errors.otp) msg = Array.isArray(data.errors.otp) ? data.errors.otp[0] : data.errors.otp;
                    alert(msg || '{{ __('Something went wrong. Please try again.') }}');
                }
            })
            .catch(error => {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitText.textContent = originalText;
                if (error.alert && error.alert.text) alert(error.alert.text);
                else if (error.errors && error.errors.otp) {
                    otpError.textContent = Array.isArray(error.errors.otp) ? error.errors.otp[0] : error.errors.otp;
                    otpError.style.display = 'block';
                } else alert('{{ __('Something went wrong. Please try again.') }}');
            });

            return false;
        });
    }

    if (otpModal) {
        otpModal.addEventListener('hidden.bs.modal', function() {
            if (otpForm) otpForm.reset();
            otpBoxes.forEach(b => { b.value = ''; });
            otpError.style.display = 'none';
        });
    }
});
</script>
