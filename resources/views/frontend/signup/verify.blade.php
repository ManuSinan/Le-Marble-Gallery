<h1 class="card-title mb-4">{{ __('Account Verification') }}</h1>
 
 <form act-on="submit" act-request="{{ route('signup.verify') }}">

     <div class="form-row mb-2">

        <div class="col-12 form-group">
            <label class="label" for="otp">{{ __('An OTP has been sent to:') }} {{ $user->mobile }}</label>
            <input type="tel" id="otp" name="otp" required class="form-control"  placeholder="{{ __('Enter otp') }}">
        </div>
 
     </div>

     <div class="form-footer">
        <input type="hidden" name="id" required class="form-control" value="{{ $user->id }}">
        <button type="submit" class="btn btn-primary w-100" tabindex="4">{{ __('Continue') }}</button>
     </div>

 </form>

 <p class="text-center mt-4 mb-3"><a href="{{ route('signup') }}">{{ __('Try again') }}</a></p>
