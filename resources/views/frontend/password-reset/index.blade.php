@php
    $siteName = config('app.name', 'Le Marble Gallery');
    $resetIdentifier = old('email', session('password_reset_email'));
    $showVerifyStep = filled($resetIdentifier);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Password Reset | {{ $siteName }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
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
            overflow-x: hidden;
        }
        .split-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }
        .visual-side {
            flex: 1.2;
            background: linear-gradient(135deg, #152B6E 0%, #0d1730 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            color: #ffffff;
        }
        .visual-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(212, 175, 55, 0.12) 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(212, 175, 55, 0.08) 0%, transparent 45%);
            pointer-events: none;
        }
        .visual-header {
            display: flex;
            align-items: center;
            gap: 14px;
            z-index: 10;
        }
        .visual-logo {
            max-height: 48px;
            width: auto;
            display: block;
        }
        .visual-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 1.5px;
            color: #ffffff;
            text-transform: uppercase;
        }
        .visual-main {
            max-width: 520px;
            margin-top: auto;
            margin-bottom: auto;
            z-index: 10;
        }
        .visual-main h1 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            line-height: 1.2;
            font-weight: 700;
            margin-bottom: 20px;
            color: #ffffff;
        }
        .visual-main h1 span {
            color: #D4AF37;
        }
        .visual-main p {
            font-size: 15.5px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 40px;
        }
        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #D4AF37;
            flex-shrink: 0;
            font-weight: 800;
            font-size: 14px;
        }
        .feature-text h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #ffffff;
            letter-spacing: 0.3px;
        }
        .feature-text p {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.4;
            margin-bottom: 0;
        }
        .visual-footer {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            z-index: 10;
        }
        .form-side {
            flex: 0.8;
            background-color: #F2F4F8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(21, 43, 110, 0.08);
            padding: 40px 36px;
            border: 1px solid rgba(21, 43, 110, 0.06);
            transition: all 0.3s ease;
        }
        .auth-card:hover {
            box-shadow: 0 24px 48px -12px rgba(21, 43, 110, 0.12);
        }
        .auth-card-header {
            margin-bottom: 28px;
            text-align: center;
        }
        .auth-logo-mobile {
            display: none;
            justify-content: center;
            margin-bottom: 20px;
        }
        .auth-card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #152B6E;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .auth-card-header p {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }
        .form-input {
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px;
            color: #1f2937;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1.5px solid #d1d5db;
            background-color: #fff;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: #152B6E;
            box-shadow: 0 0 0 4px rgba(21, 43, 110, 0.1);
        }
        .button-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            width: 100%;
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #152B6E 0%, #0d1730 100%);
            border: none;
            color: #ffffff;
            padding: 13px 20px;
            font-family: 'Inter', sans-serif;
            font-size: 15.5px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(21, 43, 110, 0.12);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #223f95 0%, #152b6e 100%);
            box-shadow: 0 8px 22px rgba(21, 43, 110, 0.2);
        }
        .submit-btn:active {
            transform: scale(0.98);
        }
        .btn-ghost {
            background: transparent !important;
            border: 1.5px solid #d1d5db !important;
            color: #374151 !important;
            box-shadow: none !important;
            text-align: center;
            width: 50%;
            padding: 13px 14px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-ghost:hover {
            border-color: #152B6E !important;
            color: #152B6E !important;
        }
        .btn-secondary {
            background: #e8edf7 !important;
            border: none !important;
            color: #152B6E !important;
            box-shadow: none !important;
            text-align: center;
            width: 50%;
            padding: 13px 14px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #d0daf0 !important;
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
        .notice {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: left;
        }
        .notice-success {
            background-color: #ecfdf3;
            border: 1px solid #d1fadf;
            color: #027a48;
        }
        .notice-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        @media (max-width: 991px) {
            .split-container {
                flex-direction: column;
            }
            .visual-side {
                display: none !important;
            }
            .form-side {
                width: 100% !important;
                flex: 1 !important;
                padding: 24px 16px;
            }
            .auth-card {
                padding: 36px 24px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            }
            .auth-logo-mobile {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Visual Panel Side -->
        <div class="visual-side">
            <div class="visual-header">
                <img src="{{ asset('assets/mobile/logo-symbol.png') }}" alt="logo" class="visual-logo">
                <span class="visual-title">Le Marble Gallery</span>
            </div>
            
            <div class="visual-main">
                <h1>Reset your password in <span>two steps</span></h1>
                <p>Welcome back to account recovery. Request a WhatsApp or SMS verification code and secure your partner panel in seconds.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <div class="feature-icon">1</div>
                        <div class="feature-text">
                            <h3>Request Secure OTP</h3>
                            <p>Enter your registered mobile or email to send the 6-digit verification code.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">2</div>
                        <div class="feature-text">
                            <h3>Configure New Password</h3>
                            <p>Verify the OTP and choose a strong new password on the same screen.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">
                            <h3>Automatic Login</h3>
                            <p>Once updated, you are immediately authenticated and returned to your previous page.</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="visual-footer">
                <p>&copy; {{ date('Y') }} Le Marble Gallery. All rights reserved.</p>
            </div>
        </div>
        
        <!-- Form Side -->
        <div class="form-side">
            <div class="auth-card">
                <div class="auth-card-header">
                    <div class="auth-logo-mobile">
                        <img src="{{ asset('assets/backend/logo-dark.png') }}" alt="logo" style="max-height: 54px; width: auto; display: block;">
                    </div>
                    @if ($showVerifyStep)
                        <h2>Verify Recovery</h2>
                        <p>We sent a verification code to <strong>{{ $resetIdentifier }}</strong></p>
                    @else
                        <h2>Forgot Password?</h2>
                        <p>Enter your verified credentials to request a recovery code</p>
                    @endif
                </div>

                @if (session('password_reset_status'))
                    <div class="notice notice-success">
                        {{ session('password_reset_status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="notice notice-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if ($showVerifyStep)
                    <!-- Enter OTP and Password -->
                    <form method="POST" action="{{ route('password.reset.verify') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="email" value="{{ $resetIdentifier }}">

                        <div class="form-group">
                            <label for="otp" class="form-label">OTP Code</label>
                            <input type="text" id="otp" name="otp" value="{{ old('otp') }}" class="form-input" placeholder="Enter OTP code" required autocomplete="off" style="text-align: center; letter-spacing: 4px; font-weight: 700; font-size: 17px;">
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Enter new password" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="submit-btn">Reset Password</button>
                    </form>

                    <div class="button-row">
                        <a href="{{ route('password.reset', ['restart' => 1]) }}" class="btn-ghost">Different Info</a>
                        <a href="{{ route('signin') }}" class="btn-secondary" style="width: 50%;">Back to Sign In</a>
                    </div>
                @else
                    <!-- Request OTP -->
                    <form method="POST" action="{{ route('password.reset') }}" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">Email or Mobile Number</label>
                            <input type="text" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Enter email or mobile" required autofocus autocomplete="off">
                        </div>

                        <button type="submit" class="submit-btn">Send OTP</button>
                    </form>

                    <p class="auth-footer"><a href="{{ route('signin') }}">Back to Sign In</a></p>
                @endif

                <p class="auth-footer" style="margin-top: 18px; font-size: 13px;">
                    Want to browse first? <a href="{{ route('home') }}">Back to gallery</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
