@extends('simple-bookstore.layout')

@section('title', 'Register | ' . config('app.name', 'Le Marble Gallery'))
@section('description', 'Create an account to explore premium materials and manage your inquiries at Le Marble Gallery.')

@section('content')
@php
    $pendingMobile = session('signup_pending_mobile', $pendingUser?->mobile);
@endphp
<section class="auth-grid auth-grid-single">
    <div class="panel">
        <div class="section-head">
            <div>
                @if($pendingUser)
                    <h2>Verify Account</h2>
                    <p class="section-copy">Enter the 6-digit OTP sent to your WhatsApp number {{ $pendingMobile ? '(' . $pendingMobile . ')' : '' }}.</p>
                @else
                    <h2>Create Account</h2>
                    <p class="section-copy">Register once, then verify your WhatsApp OTP to activate the account.</p>
                @endif
            </div>
        </div>

        @if (session('signup_status'))
            <div class="notice notice-success" style="margin-bottom: 18px;">
                {{ session('signup_status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notice notice-danger" style="margin-bottom: 18px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if($pendingUser)
            <form method="POST" action="{{ route('signup.verify') }}" class="form-grid" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{ $pendingUser->id }}">

                <div class="field">
                    <label for="otp">OTP</label>
                    <input type="text" id="otp" name="otp" value="{{ old('otp') }}" inputmode="numeric" maxlength="6" placeholder="Enter the 6-digit OTP" required autocomplete="one-time-code">
                </div>

                <button type="submit" class="button block">Verify OTP</button>
            </form>

            <div class="button-row">
                <form method="POST" action="{{ route('signup.request') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $pendingUser->id }}">
                    <button type="submit" class="button-ghost">Resend OTP</button>
                </form>
                <a href="{{ route('signup', ['restart' => 1]) }}" class="button-secondary">Use Another Number</a>
            </div>
        @else
            <form method="POST" action="{{ route('signup.store') }}" class="form-grid">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" inputmode="numeric" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="button block">Create Account</button>
            </form>

            <div class="button-row">
                <a href="{{ route('signin') }}" class="button-ghost">Already have an account?</a>
            </div>
        @endif

        <p class="muted-copy" style="margin-top: 18px;">
            Want to browse first? <a href="{{ route('home') }}" class="subtle-link">Back to gallery</a>
        </p>
    </div>
</section>
@endsection
