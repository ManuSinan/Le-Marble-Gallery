<h1 class="card-title mb-4">{{ __('Password Reset') }}</h1>
<form act-on="submit" act-request="{{ route('password.reset.verify') }}" autocomplete="off"> 
 
        <div class="form-group">
            <label>{{ __('OTP') }} <span class="text-danger">*</span></label>
            <div class="form-element">
                <input type="text" name="otp" placeholder="{{ __('Enter the OTP send to your email / mobile') }}" required class="form-control" autocomplete="off">
            </div>
        </div>
        
        <div class="form-group">
            <label>{{ __('Password') }} <span class="text-danger">*</span></label>
            <div class="form-element">
                <input type="password" name="password" placeholder="{{ __('Enter new password') }}"  required class="form-control" autocomplete="off">
            </div>
        </div>

        <div class="form-footer mt-4">
            <input name="email" type="hidden" value="{{ $email ?? '' }}">
            <button type="submit" class="btn btn-primary w-100" tabindex="4">{{ __('Next') }}</button>
            <div class="form-text text-muted mt-3 w-100 text-center">
            {{ __('OTP is valid for 5 minutes') }}
            </div>
        </div>
</form>
 