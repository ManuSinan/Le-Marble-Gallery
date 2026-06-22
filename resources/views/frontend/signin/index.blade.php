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
    <link rel="icon" type="image/png" href="{{ asset('favicons/knm-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            height: 100vh;
            width: 100vw;
            overflow: hidden; /* Remove all scrollability */
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 36px 30px;
            margin: 16px;
        }
        .auth-brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .auth-logo-icon {
            width: 44px;
            height: 44px;
            background: #e8edf7;
            color: #152B6E;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .auth-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            font-size: 24px;
            color: #152B6E;
            margin-bottom: 6px;
            line-height: 1.2;
        }
        .auth-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 0;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-input {
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #111827;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background-color: #fff;
            transition: all 0.2s ease-in-out;
            outline: none;
        }
        .form-input:focus {
            border-color: #152B6E;
            box-shadow: 0 0 0 3px rgba(21, 43, 110, 0.1);
        }
        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #4b5563;
            cursor: pointer;
            user-select: none;
        }
        .remember-me input {
            width: 15px;
            height: 15px;
            accent-color: #152B6E;
            cursor: pointer;
        }
        .forgot-link {
            color: #D4AF37;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .forgot-link:hover {
            color: #bfa02e;
            text-decoration: underline;
        }
        .submit-btn {
            width: 100%;
            background-color: #152B6E;
            border: 1px solid #152B6E;
            color: #ffffff;
            padding: 11px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .submit-btn:hover {
            background-color: #0f2052;
            border-color: #0f2052;
        }
        .submit-btn:active {
            transform: scale(0.98);
        }
        .auth-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }
        .auth-footer a {
            color: #152B6E;
            font-weight: 700;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-brand">
            <div style="margin-bottom: 16px; display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('assets/backend/logo-dark.png') }}" alt="logo" style="max-height: 54px; width: auto; display: block;">
            </div>
            <h1 class="auth-title">Le Marble Gallery</h1>
            <p class="auth-subtitle">Sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('signin') }}">
            @csrf

            <div class="form-group">
                <label for="login" class="form-label">Username</label>
                <input type="text" id="login" name="login" class="form-input" value="{{ old('login') }}" placeholder="Enter username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Enter password" required>
            </div>

            <div class="meta-row">
                <label class="remember-me">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.reset') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn">Sign In</button>
        </form>

        <p class="auth-footer">Don't have an account? <a href="{{ route('signup') }}">Register here</a></p>
    </div>
</body>
</html>
