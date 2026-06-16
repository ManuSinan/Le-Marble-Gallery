<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="back headerButton item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
        </a>
    </div>
    <div class="pageTitle">{{ __('Register') }}</div>
</div>

<!-- App Capsule -->
<div class="appCapsule">

    <form act-on="submit" act-request="{{ route('mobile.signup.request', ['return' => $return]) }}">
        <div class="mt-3">
            <div class="section mb-5">
 
                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                            <label class="label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" required class="form-control"  placeholder="{{ __('Enter full name') }}">
                            <span class="clear-input">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </span>
                    </div>
                </div>
 
                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                        <label class="label" for="email">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" class="form-control"  placeholder="{{ __('Enter email address') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>

 
                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                        <label class="label" for="mobile">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                        <input type="tel" id="mobile" name="mobile" required class="form-control"  placeholder="{{ __('Enter mobile number with country code') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>


                <div class="form-group basic mb-1">
                    <div class="input-wrapper mb-1">
                        <label class="label" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" required class="form-control"  placeholder="{{ __('Enter password') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>
 
                    <div class="form-links mt-2">
                        <div>
                            @if($return && $return == 'cart')
                                <a href="{{ route('mobile.signin', ['return' => $return]) }}">{{ __('Already have an account') }}</a>
                            @else
                                <a href="{{ route('mobile.signin') }}">{{ __('Back to Sign in') }}</a>    
                            @endif
                        </div>
                    </div>

                <div class="appBottomMenu">
                    <button type="submit" disabled  class="btn btn-primary btn-block btn-lg">{{ __('Continue') }}</button>
                </div>

             </div>
        </div>
    </form>
 
</div>
<!-- * App Capsule -->