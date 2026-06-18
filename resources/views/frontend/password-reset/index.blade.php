@extends('simple-bookstore.layout')

@section('title', 'Password Reset | ' . config('app.name', 'Le Marble Gallery'))
@section('description', 'Reset your password to continue accessing your Le Marble Gallery account.')

@section('content')
@php
    $resetIdentifier = old('email', session('password_reset_email'));
    $showVerifyStep = filled($resetIdentifier);
@endphp

<section class="auth-grid">
    <div class="auth-copy panel panel-accent">
        <span class="eyebrow">Account Recovery</span>
        <h1>Reset your password in two simple steps.</h1>
        <p>Enter your verified mobile number or email, receive an OTP, then set a new password without leaving the website flow.</p>

        <ul class="feature-list" style="margin-top: 24px;">
            <li class="feature-item">
                <strong>Step 1</strong>
                <span>Request the OTP using your verified contact information.</span>
            </li>
            <li class="feature-item">
                <strong>Step 2</strong>
                <span>Enter the OTP and save your new password on the same page.</span>
            </li>
            <li class="feature-item">
                <strong>After reset</strong>
                <span>You are signed in automatically and redirected back to the gallery homepage.</span>
            </li>
        </ul>
    </div>

    <div class="panel">
        @if (session('password_reset_status'))
            <div class="notice notice-success" style="margin-bottom: 18px;">
                {{ session('password_reset_status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notice notice-danger" style="margin-bottom: 18px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if ($showVerifyStep)
            <div class="section-head">
                <div>
                    <h2>Enter OTP</h2>
                    <p class="section-copy">We sent a code to <strong>{{ $resetIdentifier }}</strong>. Enter the OTP and choose your new password below.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.reset.verify') }}" class="form-grid" autocomplete="off">
                @csrf

                <div class="field">
                    <label for="otp">OTP</label>
                    <input type="text" id="otp" name="otp" value="{{ old('otp') }}" placeholder="Enter the OTP sent to your email / mobile" required autocomplete="off">
                </div>

                <div class="field">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required autocomplete="off">
                </div>

                <input type="hidden" name="email" value="{{ $resetIdentifier }}">

                <button type="submit" class="button block">Reset Password</button>
            </form>

            <div class="button-row">
                <a href="{{ route('password.reset', ['restart' => 1]) }}" class="button-ghost">Use Different Contact</a>
                <a href="{{ route('signin') }}" class="button-secondary">Back to Sign In</a>
            </div>

            <p class="muted-copy" style="margin-top: 18px;">OTP is valid for 3 minutes.</p>
        @else
            <div class="section-head">
                <div>
                    <h2>Request OTP</h2>
                    <p class="section-copy">Enter your verified mobile number or email and we will send the reset code.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.reset') }}" class="form-grid" autocomplete="off">
                @csrf

                <div class="field">
                    <label for="email">Email or Mobile Number</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email / mobile number" required autofocus autocomplete="off">
                </div>

                <button type="submit" class="button block">Send OTP</button>
            </form>

            <div class="button-row">
                <a href="{{ route('signin') }}" class="button-ghost">Back to Sign In</a>
            </div>
        @endif
    </div>
</section>
@endsection
