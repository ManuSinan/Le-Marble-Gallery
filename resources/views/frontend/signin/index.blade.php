@extends('simple-bookstore.layout')

@section('title', 'Sign In | ' . config('app.name', 'KNM Bookstore'))
@section('description', 'Sign in with your mobile number or username to continue ordering books from the website.')

@section('extra_styles')
<style>
    .auth-center-wrap {
        min-height: calc(100vh - 250px);
        display: grid;
        place-items: center;
    }
    .auth-modern {
        width: 100%;
        max-width: 520px;
    }
    .auth-modern .panel {
        border-radius: 18px;
        border: 1px solid #d9deea;
        box-shadow: 0 10px 24px rgba(25, 28, 29, 0.08);
        padding: 30px 24px 22px;
        background: #ffffff;
    }
    .auth-modern .title {
        font-size: clamp(34px, 6vw, 44px);
        line-height: 1;
        letter-spacing: -0.04em;
        margin-bottom: 10px;
        text-align: center;
    }
    .auth-modern .subtitle {
        color: #555d6d;
        line-height: 1.6;
        margin-bottom: 24px;
        text-align: center;
        font-size: 14px;
    }
    .auth-modern .field label {
        display: none;
    }
    .auth-modern .field input {
        border-radius: 10px;
        border: 1px solid #cfd5e3;
        padding: 12px 14px;
        font-size: 15px;
    }
    .auth-modern .field input:focus {
        border-color: #0d6efd;
    }
    .auth-modern .button {
        background: #0d9373;
        border-color: #0d9373;
        border-radius: 10px;
        min-height: 46px;
        font-size: 18px;
        font-weight: 800;
    }
    .auth-modern .button:hover {
        background: #0a7a5f;
        border-color: #0a7a5f;
    }
    .auth-modern .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 4px 2px 10px;
        font-size: 14px;
        color: #6b7280;
    }
    .auth-modern .meta-row .remember {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .auth-modern .meta-row input[type="checkbox"] {
        width: 14px;
        height: 14px;
    }
    .auth-modern .meta-row a {
        color: #0d6efd;
        font-weight: 700;
    }
    .auth-modern .aux {
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }
    .auth-modern .aux a {
        color: #0d6efd;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<section class="auth-center-wrap">
    <div class="auth-modern">
        <div class="panel">
            <h1 class="title">Sign In</h1>
            <p class="subtitle">Enter your credentials to access your account</p>

            @if ($errors->any())
                <div class="notice notice-danger" style="margin-bottom: 16px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('signin') }}" class="form-grid">
                @csrf

                <div class="field">
                    <label for="login">Username or Mobile Number</label>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Username or Mobile Number" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="meta-row">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.reset') }}">Forgot password?</a>
                </div>

                <button type="submit" class="button block">Sign In</button>
            </form>

            <p class="aux" style="margin-top: 14px;">Don't have an account? <a href="{{ route('signup') }}">Create one</a></p>
        </div>
    </div>
</section>
@endsection
