<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="back headerButton item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
        </a>
    </div>
    <div class="pageTitle">{{ __('Manage Profile') }}</div>
    <div class="right">
        <a href="{{ route('mobile.cart',['referral' => route('mobile.update.profile')]) }}" class="headerButton cart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 18C5.895 18 5.01 18.895 5.01 20C5.01 21.105 5.895 22 7 22C8.105 22 9 21.105 9 20C9 18.895 8.105 18 7 18ZM1 2V4H3L6.595 11.585L5.245 14.035C5.09 14.325 5 14.65 5 15C5 16.105 5.895 17 7 17H19V15H7.425C7.285 15 7.175 14.89 7.175 14.75C7.175 14.705 7.185 14.665 7.205 14.63L8.1 13H15.55C16.3 13 16.955 12.585 17.3 11.97L20.875 5.48C20.955 5.34 21 5.175 21 5C21 4.445 20.55 4 20 4H5.215L4.265 2H1ZM17 18C15.895 18 15.01 18.895 15.01 20C15.01 21.105 15.895 22 17 22C18.105 22 19 21.105 19 20C19 18.895 18.105 18 17 18Z" fill="currentColor"/>
            </svg>
            <span class="badge cart">{{ cartItemCount(null) }}</span>
        </a>
    </div>
</div>

<!-- App Capsule -->
<div class="appCapsule">
 
    <div class="mt-1">

        <form act-on="submit" act-request="{{ route('mobile.update.profile') }}">
            <div class="section mt-1">

                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                        <label class="label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" value="{{ $user->name }}" required class="form-control"  placeholder="{{ __('Enter Name') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>

                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                        <label class="label" for="email">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control"  placeholder="{{ __('Enter Email Address') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                    @if($user->email_verified == 0)
                    <div class="invalid-feedback" style="display: block;">{{ __('The email is not verified.') }}</div>
                    @endif
                </div>

                <div class="form-group basic mb-1">
                    <div class="input-wrapper">
                        <label class="label" for="mobile">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                        <input type="tel" id="mobile" name="mobile" required value="{{ $user->mobile }}" class="form-control"  placeholder="{{ __('Enter Mobile Number') }}">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>


            </div>

            <div class="section full  mb-5">
                <div class="wide-block pt-2 pb-2">
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Update Profile') }}</button>
                </div>
            </div>
        </form>

    </div>
 
</div>
<!-- * App Capsule -->


@include('mobile/layout/bottom-menu')