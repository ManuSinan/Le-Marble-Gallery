<form act-on="submit" act-request="{{ route('website.verify.profile') }}">
 
 <div class="form-row mb-2">
     <div class="col-12 col-md-6 form-group">
         <label class="label d-flex justify-content-between" for="otp">{{ __('An OTP has been sent to:') }} {{ $mobile }} <a href="{{ route('website.update.profile') }}">{{ __('Try again') }}</a></label>
         <input type="tel" id="otp" name="otp" required class="form-control"  placeholder="{{ __('Enter otp') }}">
     </div>
 </div>


 <div class="form-row mb-2">
    <div class="col-sm-12">
         <input type="hidden" name="id" required class="form-control" value="{{ $user->id }}">
         <input type="hidden" name="mobile" required class="form-control" value="{{ $mobile }}">
         <input type="hidden" name="hash" required class="form-control" value="{{ $hash }}">

         @include('frontend.account.partials.form-actions')
    </div>
 </div>

</form>