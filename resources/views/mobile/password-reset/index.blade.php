<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="back headerButton item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
        </a>
    </div>
    <div class="pageTitle">{{ __('Password Reset') }}</div>
</div>

<!-- App Capsule -->
<div class="appCapsule">

    <form act-on="submit" act-request="{{ route('mobile.password.reset.request') }}">
        <div class="mt-3">
            <div class="section mb-5">
                <form act-on="submit" act-request="{{ route('mobile.home') }}">
 
                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                        <label class="label" for="email">{{ __('Email / Mobile Number') }} <span class="text-danger">*</span></label>
                        <input type="text" id="email" name="email" required class="form-control"  placeholder="{{ __('Enter Email / Mobile Number') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>

                <div class="form-links mt-2">
                        <div>
                            <a href="{{ route('mobile.signin') }}">{{ __('Back to Sign in') }}</a>
                        </div>
                    </div>

                <div class="appBottomMenu">
                    <button type="submit" disabled  class="btn btn-primary btn-block btn-lg">{{ __('Continue') }}</button>
                </div>

                </form>
            </div>
        </div>
    </form>
 
</div>
<!-- * App Capsule -->