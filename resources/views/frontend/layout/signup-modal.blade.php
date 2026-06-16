<!-- Signup Modal - Dark theme (matches login modal) -->
<div class="modal fade auth-modal-dark" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content auth-modal-content">
            <div class="modal-body auth-modal-body">
                <button type="button" class="auth-modal-close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <h2 class="auth-modal-title">{{ __('Create account') }}</h2>
                <p class="auth-modal-subtitle">{{ __('Register with mobile number and password. Your username will be your mobile number.') }}</p>

                <div id="signupFormContainer" class="auth-modal-form-wrapper">
                    <form id="signupModalForm" method="POST" action="{{ route('signup.store') }}" class="auth-modal-form">
                        @csrf
                        <div class="auth-modal-field">
                            <input type="text" id="signupName" name="name" required class="auth-modal-input" placeholder="{{ __('Name') }}" autofocus>
                            <div id="signupNameError" class="auth-modal-error" style="display: none;"></div>
                        </div>
                        <div class="auth-modal-field">
                            <input type="tel" id="signupMobile" name="mobile" required class="auth-modal-input" placeholder="{{ __('Mobile Number') }}">
                            <div id="signupMobileError" class="auth-modal-error" style="display: none;"></div>
                        </div>
                        <div class="auth-modal-field">
                            <input type="password" id="signupPassword" name="password" required class="auth-modal-input" placeholder="{{ __('Password') }}">
                            <div id="signupPasswordError" class="auth-modal-error" style="display: none;"></div>
                        </div>
                        <div class="auth-modal-field">
                            <input type="password" id="signupPasswordConfirmation" name="password_confirmation" required class="auth-modal-input" placeholder="{{ __('Confirm Password') }}">
                            <div id="signupPasswordConfirmationError" class="auth-modal-error" style="display: none;"></div>
                        </div>
                        <p class="auth-modal-terms">
                            {{ __('By continuing, you agree to') }}
                            <a href="{{ route('website.tc') }}">{{ __('Terms of Use') }}</a>
                            {{ __('and') }}
                            <a href="{{ route('website.privacy.policy') }}">{{ __('Privacy Policy') }}</a>
                        </p>
                        <button type="submit" class="auth-modal-submit">{{ __('Create Account') }}</button>
                    </form>
                    <p class="auth-modal-switch">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('signin') }}" class="auth-modal-switch-signin">{{ __('Sign in') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-modal-form-wrapper .auth-modal-form { margin-bottom: 0; }
.auth-modal-terms {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.5);
    margin: 8px 0 16px;
    line-height: 1.5;
}
.auth-modal-terms a {
    color: #b8956b;
    text-decoration: none;
}
.auth-modal-terms a:hover {
    color: #d4b896;
    text-decoration: underline;
}
#signupModal .auth-modal-dialog { max-width: 440px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const signupModal = document.getElementById('signupModal');
    const signupForm = document.getElementById('signupModalForm');
    const signupFormContainer = document.getElementById('signupFormContainer');

    // Handle signup form submission
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(signupForm);
            const submitBtn = signupForm.querySelector('.auth-modal-submit');
            const originalText = submitBtn.textContent;
            
            // Clear previous errors
            document.querySelectorAll('[id^="signup"][id$="Error"]').forEach(el => {
                el.style.display = 'none';
            });
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = '{{ __('Please wait...') }}';
            
            fetch(signupForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                
                console.log('Signup response:', data); // Debug log
                
                if (data.errors) {
                    // Show validation errors
                    Object.keys(data.errors).forEach(field => {
                        const fieldName = field.charAt(0).toUpperCase() + field.slice(1);
                        const errorEl = document.getElementById('signup' + fieldName + 'Error');
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(data.errors[field]) ? data.errors[field][0] : data.errors[field];
                            errorEl.style.display = 'block';
                        }
                    });
                } else if (data.alert) {
                    // Show alert message
                    alert(data.alert.text || data.alert);
                } else if (data.success && data.user_id) {
                    // OTP sent successfully - store user ID and mobile, then open OTP modal
                    sessionStorage.setItem('signupUserId', data.user_id);
                    
                    // Store mobile number (from response or form)
                    const mobileNumber = data.mobile || document.getElementById('signupMobile')?.value;
                    if (mobileNumber) {
                        sessionStorage.setItem('signupMobileNumber', mobileNumber);
                    }
                    
                    // Close signup modal
                    $('#signupModal').modal('hide');
                    // Open OTP modal after a short delay
                    setTimeout(function() {
                        $('#otpVerificationModal').modal('show');
                    }, 300);
                } else if (data.jquery && data.jquery.value) {
                    // Fallback: Extract user ID from HTML response if direct data not available
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.jquery.value;
                    const userIdInput = tempDiv.querySelector('input[name="id"]');
                    
                    if (userIdInput && userIdInput.value) {
                        // Store user ID for OTP modal
                        sessionStorage.setItem('signupUserId', userIdInput.value);
                        
                        // Extract and store mobile number from form
                        const mobileInput = document.getElementById('signupMobile');
                        if (mobileInput && mobileInput.value) {
                            sessionStorage.setItem('signupMobileNumber', mobileInput.value);
                        }
                        
                        // Close signup modal
                        $('#signupModal').modal('hide');
                        // Open OTP modal after a short delay
                        setTimeout(function() {
                            $('#otpVerificationModal').modal('show');
                        }, 300);
                    } else {
                        console.error('User ID not found in response');
                        alert((data.alert && data.alert.text) ? data.alert.text : (data.message) ? data.message : '{{ __('Something went wrong. Please try again.') }}');
                    }
                } else {
                    console.error('Unexpected response format:', data);
                    var msg = (data.alert && data.alert.text) ? data.alert.text : (data.message) ? data.message : null;
                    if (!msg && data.errors) {
                        var firstKey = Object.keys(data.errors)[0];
                        if (firstKey) msg = Array.isArray(data.errors[firstKey]) ? data.errors[firstKey][0] : data.errors[firstKey];
                    }
                    alert(msg || '{{ __('Something went wrong. Please try again.') }}');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                console.error('Signup error:', error);
                if (error.alert && error.alert.text) {
                    alert(error.alert.text);
                } else if (error.errors) {
                    Object.keys(error.errors).forEach(field => {
                        const fieldName = field.charAt(0).toUpperCase() + field.slice(1);
                        const errorEl = document.getElementById('signup' + fieldName + 'Error');
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(error.errors[field]) ? error.errors[field][0] : error.errors[field];
                            errorEl.style.display = 'block';
                        }
                    });
                } else {
                    alert('{{ __('Something went wrong. Please try again.') }}');
                }
            });
        });
    }


    // Reset form when modal is closed
    if (signupModal) {
        signupModal.addEventListener('hidden.bs.modal', function() {
            if (signupForm) signupForm.reset();
            document.querySelectorAll('[id^="signup"][id$="Error"]').forEach(el => {
                el.style.display = 'none';
            });
        });
    }

    document.querySelectorAll('.auth-modal-switch-signin').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            $('#signupModal').modal('hide');
            setTimeout(function() { $('#loginModal').modal('show'); }, 300);
        });
    });
});
</script>
