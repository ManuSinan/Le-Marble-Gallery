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
            margin: 0 auto 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .brand-logo-container img {
            max-height: 54px;
            width: auto;
            display: block;
        }
        .brand-title {
            color: #152B6E !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            letter-spacing: 2.5px !important;
            text-align: center !important;
            margin-bottom: 32px !important;
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
                <img src="{{ asset('assets/backend/logo-dark.png') }}" alt="logo">
            </div>
            <h2 class="brand-title">Le Marble Gallery</h2>

            <form act-on="submit" act-request="{{ route('mobile.signin.request', ['return' => $return]) }}">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="email">{{ __('Username') }}</label>
                        <input type="text" id="email" name="email" required class="form-control" placeholder="{{ __('Enter Username') }}">
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="password">{{ __('Password') }}</label>
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