@php
    $siteName = config('app.name', 'Le Marble Gallery');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | {{ $siteName }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background-color: #F2F4F8;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-screen-wrap {
            background: #F2F4F8 !important;
            min-height: 100vh;
            width: 100%;
            position: relative;
            padding-bottom: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        /* Top Navigation Header styled exactly like mobile appHeader */
        .appHeader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: #152B6E !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 1000;
        }
        .appHeader .left {
            display: flex;
            align-items: center;
        }
        .appHeader .back {
            color: #ffffff !important;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .appHeader .back:hover {
            opacity: 0.8;
        }
        .appHeader .pageTitle {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: 0.5px;
        }
        .appHeader .right {
            width: 24px;
        }
        .appCapsule {
            padding-top: 80px !important;
            background: transparent !important;
            width: 100%;
            max-width: 440px;
            padding: 0 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 24px 0 !important;
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
            box-sizing: border-box;
            outline: none;
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
        .form-links-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 26px;
            width: 100%;
            font-size: 13.5px;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #374151;
            cursor: pointer;
            font-weight: 600;
            user-select: none;
        }
        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: #152B6E;
            cursor: pointer;
        }
        .forgot-link {
            color: #152B6E !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
        }
        .forgot-link:hover {
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
            cursor: pointer;
        }
        .custom-login-btn:hover {
            background: linear-gradient(135deg, #223f95 0%, #152b6e 100%) !important;
            box-shadow: 0 8px 22px rgba(21, 43, 110, 0.2) !important;
        }
        .custom-login-btn:active {
            transform: scale(0.98) !important;
        }
        .alert-error-wrap {
            width: 100%;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .invalid-feedback {
            color: #dc2626 !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            display: block !important;
            text-align: center !important;
            margin: 4px 0;
        }
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .auth-footer a {
            color: #152B6E;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .auth-footer a:hover {
            color: #B89225;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-screen-wrap">
        <!-- Header -->
        <div class="appHeader">
            <div class="left">
                <a href="{{ route('home') }}" class="back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
            </div>
            <div class="pageTitle">{{ __('Sign in') }}</div>
            <div class="right"></div>
        </div>

        <!-- App Capsule -->
        <div class="appCapsule">
            <div class="login-card">
                <div class="brand-logo-container">
                    <img src="{{ asset('assets/backend/logo-dark.png') }}" alt="logo">
                </div>
                <h2 class="brand-title">{{ $siteName }}</h2>

                <form method="POST" action="{{ route('signin') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="alert-error-wrap">
                            @foreach ($errors->all() as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="login">{{ __('Username / Mobile') }}</label>
                            <input type="text" id="login" name="login" required class="form-control" placeholder="{{ __('Enter Username or Mobile') }}" value="{{ old('login') }}" autofocus autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="password">{{ __('Password') }}</label>
                            <input type="password" id="password" name="password" required class="form-control" placeholder="{{ __('Enter Password') }}" autocomplete="current-password">
                        </div>
                    </div>

                    <div class="form-links-container">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <span>{{ __('Remember me') }}</span>
                        </label>
                        <a href="{{ route('password.reset') }}" class="forgot-link">{{ __('Forgot Password?') }}</a>
                    </div>

                    <button type="submit" class="custom-login-btn">{{ __('Continue') }}</button>
                </form>

                <p class="auth-footer">{{ __("Don't have an account?") }} <a href="{{ route('signup') }}">{{ __('Register here') }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>
