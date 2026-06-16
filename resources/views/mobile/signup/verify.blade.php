<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="back headerButton item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
        </a>
    </div>
    <div class="pageTitle">{{ __('Verify') }}</div>
</div>

<!-- App Capsule -->
<div class="appCapsule">

    <form act-on="submit" act-request="{{ route('mobile.signup.verify', ['return' => $return]) }}">
        <div class="mt-3">
            <div class="section mb-5">

                <div class="form-group basic mb-1">
                    <div class="input-wrapper mb-1">
                        <label class="label" for="otp">{{ __('An OTP has been sent to:') }} {{ $user->mobile }}</label>
                        <input type="tel" id="otp" name="otp" required class="form-control"  placeholder="{{ __('Enter otp') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>

                <div class="form-links mt-2">
                    <div>
                        <a href="{{ route('mobile.signup') }}">{{ __('Try again') }}</a>  
                    </div>
                </div>


                <div class="appBottomMenu">
                    <input type="hidden" name="id" required class="form-control" value="{{ $user->id }}">
                    <button type="submit" disabled  class="btn btn-primary btn-block btn-lg">{{ __('Continue') }}</button>
                </div>

             </div>
        </div>
    </form>
 
</div>
<!-- * App Capsule -->