<div class="login-screen-wrap">
    <style>
        .login-screen-wrap {
            background: #F2F4F8 !important;
            min-height: 100vh;
            position: relative;
            padding-bottom: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-screen-wrap .appHeader {
            background: #152B6E !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
            box-shadow: none !important;
        }
        .login-screen-wrap .appHeader:before {
            background: #152B6E !important;
        }
        .login-screen-wrap .appHeader .pageTitle {
            color: #ffffff !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .login-screen-wrap .appHeader .back svg {
            color: #ffffff !important;
        }
        .login-screen-wrap .appCapsule {
            padding-top: 76px !important;
            background: transparent !important;
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 24px 20px !important;
            margin: 0 !important;
            box-shadow: none !important;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .brand-logo-container {
            width: 75px;
            height: 75px;
            background: #ffffff;
            border: 2px solid #D4AF37;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 18px;
            box-shadow: 0 4px 12px rgba(21, 43, 110, 0.08);
        }
        .brand-title {
            color: #152B6E !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            letter-spacing: 2.5px !important;
            text-align: center !important;
            margin-bottom: 6px !important;
            text-transform: uppercase !important;
        }
        .brand-subtitle {
            color: #B89225 !important;
            font-size: 11.5px !important;
            font-weight: 500 !important;
            letter-spacing: 1px !important;
            text-align: center !important;
            text-transform: uppercase !important;
            margin-bottom: 32px !important;
        }
        .login-card form {
            width: 100%;
        }
        .login-card .form-group.basic {
            border-bottom: none !important;
            margin-bottom: 22px !important;
            padding: 0 !important;
            width: 100%;
        }
        .login-card .form-group.basic .label {
            color: #374151 !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            margin-bottom: 8px !important;
            letter-spacing: 0.2px;
            text-align: left !important;
            display: block !important;
        }
        .login-card .form-control {
            background: #ffffff !important;
            border: 1.5px solid #d1d5db !important;
            border-radius: 12px !important;
            color: #1f2937 !important;
            padding: 12px 16px !important;
            height: auto !important;
            font-size: 15px !important;
            text-align: center !important;
            transition: all 0.3s ease !important;
            width: 100%;
        }
        .login-card .form-control:focus {
            border-color: #152B6E !important;
            box-shadow: 0 0 0 3px rgba(21, 43, 110, 0.1) !important;
            background: #ffffff !important;
            color: #1f2937 !important;
        }
        .login-card .form-control::placeholder {
            color: #9ca3af !important;
        }
        .login-card .input-wrapper {
            position: relative !important;
            width: 100%;
        }
        .login-card .clear-input {
            display: none !important;
        }
        .login-card .form-links {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
            margin-bottom: 26px;
            width: 100%;
        }
        .login-card .form-links a {
            color: #152B6E !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
        }
        .login-card .form-links a:hover {
            color: #B89225 !important;
        }
        .custom-login-btn {
            background: linear-gradient(135deg, #152B6E 0%, #0d1730 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            padding: 14px 20px !important;
            width: 100% !important;
            box-shadow: 0 6px 18px rgba(21, 43, 110, 0.12) !important;
            transition: all 0.3s ease !important;
            letter-spacing: 0.5px !important;
        }
        .custom-login-btn:hover {
            background: linear-gradient(135deg, #223f95 0%, #152b6e 100%) !important;
            box-shadow: 0 8px 22px rgba(21, 43, 110, 0.2) !important;
        }
        .custom-login-btn:active {
            transform: scale(0.98) !important;
        }
        /* Style validation errors */
        .login-card .invalid-feedback {
            color: #dc2626 !important;
            font-size: 12px !important;
            margin-top: 6px !important;
            font-weight: 500 !important;
            display: block !important;
            text-align: center !important;
        }
        .login-card .is-invalid {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }
    </style>

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="back headerButton item">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
            </a>
        </div>
        <div class="pageTitle">{{ __('Sign in') }}</div>
    </div>

    <!-- App Capsule -->
    <div class="appCapsule">
        <div class="login-card">
            <div class="brand-logo-container">
                <svg width="36" height="36" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.5563 14.0986C15.4453 14.0986 14.5417 15.0026 14.5417 16.1133C14.5417 17.2239 15.4453 18.128 16.5563 18.128C17.6667 18.128 18.5697 17.2239 18.5697 16.1133C18.5697 15.0026 17.6667 14.0986 16.5563 14.0986ZM16.5563 19.1333C14.8912 19.1333 13.5355 17.7786 13.5355 16.1133C13.5355 14.4493 14.8912 13.092 16.5563 13.092C18.2197 13.092 19.5765 14.4493 19.5765 16.1133C19.5765 17.7786 18.2197 19.1333 16.5563 19.1333Z" fill="#D4AF37"/>
                    <path d="M17.0593 13.6453V4.53335C17.0593 4.25601 16.8328 4.02934 16.5563 4.02934C16.2787 4.02934 16.0521 4.25601 16.0521 4.53335V13.6453C16.2161 13.6133 16.3828 13.596 16.5563 13.596C16.7292 13.596 16.8959 13.6133 17.0593 13.6453Z" fill="#D4AF37"/>
                    <path d="M22.2229 10.2827C22.025 10.084 21.7052 10.084 21.5084 10.2827L17.9432 14.0147C18.2234 14.2 18.4688 14.444 18.6546 14.7253L22.2229 10.9933C22.4182 10.7987 22.4182 10.4773 22.2229 10.2827Z" fill="#D4AF37"/>
                    <path d="M16.5563 31.216C8.22653 31.216 1.45213 24.4427 1.45213 16.1133C1.45213 7.78399 8.22653 1.00935 16.5563 1.00935C24.8855 1.00935 31.6587 7.78399 31.6587 16.1133C31.6587 17.064 31.5587 17.992 31.392 18.8947C31.772 19.0147 32.084 19.2827 32.2667 19.6293C32.5227 18.496 32.6667 17.3213 32.6667 16.1133C32.6667 7.23066 25.4391 0.00267933 16.5563 0.00267933C7.67239 0.00267933 0.444794 7.23066 0.444794 16.1133C0.444794 24.996 7.67239 32.224 16.5563 32.224C18.7219 32.224 20.7912 31.788 22.6823 31.0053H18.9631C18.1765 31.1333 17.3771 31.216 16.5563 31.216" fill="#D4AF37"/>
                    <path d="M18.6984 29.4946V28.7346L19.6745 27.7906C22.0156 25.56 23.0855 24.376 23.0855 22.9946C23.0855 22.064 22.6489 21.204 21.2817 21.204C20.4479 21.204 19.7588 21.6266 19.3349 21.9786L18.9407 21.104C19.5615 20.5813 20.4751 20.1733 21.5213 20.1733C23.4943 20.1733 24.3265 21.5266 24.3265 22.8413C24.3265 24.532 23.1011 25.9 21.1688 27.7626L20.4479 28.4373V28.4666H24.5531V29.4946H18.6984Z" fill="#D4AF37"/>
                    <path d="M29.7693 26.0266V22.9666C29.7693 22.4853 29.7813 22.008 29.808 21.5266H29.7693C29.484 22.064 29.2625 22.4573 29.0073 22.8813L26.7636 25.9986V26.0266H29.7693ZM29.7693 29.4946V26.9853H25.5079V26.1826L29.5973 20.3293H30.94V26.0266H32.2227V26.9853H30.94V29.4946H29.7693" fill="#D4AF37"/>
                    <path d="M28.6375 16.1133C28.6375 16.6707 28.1891 17.12 27.6323 17.12C27.0755 17.12 26.6251 16.6707 26.6251 16.1133C26.6251 15.556 27.0755 15.1067 27.6323 15.1067C28.1891 15.1067 28.6375 15.556 28.6375 16.1133Z" fill="#D4AF37"/>
                    <path d="M6.4864 16.1133C6.4864 16.6707 6.0364 17.12 5.4792 17.12C4.9224 17.12 4.47293 16.6707 4.47293 16.1133C4.47293 15.556 4.9224 15.1067 5.4792 15.1067C6.0364 15.1067 6.4864 15.556 6.4864 16.1133Z" fill="#D4AF37"/>
                </svg>
            </div>
            <h2 class="brand-title">Le Marble Gallery</h2>
            <p class="brand-subtitle">Premium Stones & Bath Fittings</p>

            <form act-on="submit" act-request="{{ route('mobile.signin.request', ['return' => $return]) }}">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="email">{{ __('Email / Mobile Number') }} <span class="text-danger">*</span></label>
                        <input type="text" id="email" name="email" required class="form-control" placeholder="{{ __('Enter Email / Mobile Number') }}">
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" required class="form-control" placeholder="{{ __('Enter Password') }}">
                    </div>
                </div>

                <div class="form-links">
                    <a href="{{ route('mobile.password.reset') }}">{{ __('Forgot Password?') }}</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg custom-login-btn">{{ __('Continue') }}</button>
            </form>
        </div>
    </div>
</div>