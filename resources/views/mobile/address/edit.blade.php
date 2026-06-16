<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ __('Edit Delivery Site') }}</div>

    <div class="right">
        <a href="{{ route('mobile.address.destroy', ['address' => $address->id, 'type' => $type]) }}" class="headerButton cart" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
        </a>
    </div>
</div>

<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8;">
 
    <div class="mt-3">

        <form act-on="submit" act-request="{{ route('mobile.address.update', ['address' => $address->id, 'view' => $type]) }}">
            <div class="section mt-1 px-3">
 
                <div class="form-group mb-2">
                    <label class="label" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">Site Type</label>
                    <div>
                        <div class="custom-control custom-radio d-inline mr-2">
                            <input type="radio" id="type1" name="type" value="Home" @if($address->type == 'Home') checked @endif class="custom-control-input">
                            <label class="custom-control-label" for="type1" style="font-family: 'Inter', sans-serif; font-size: 13px;">{{ __('Residential Site') }}</label>
                        </div>
                        
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="type2" name="type" value="Work" @if($address->type == 'Work') checked @endif class="custom-control-input">
                            <label class="custom-control-label" for="type2" style="font-family: 'Inter', sans-serif; font-size: 13px;">{{ __('Commercial Site') }}</label>
                        </div>  
                    </div>
                </div>

                <div class="form-group basic mb-2">
                    <div class="input-wrapper">
                        <label class="label" for="name" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">{{ __('Client Name / Project Reference') }} <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" value="{{ $address->name }}" required class="form-control" placeholder="{{ __('Enter Client Name or Project Reference') }}" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 10px; font-family: 'Inter', sans-serif;">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>
 
                <div class="form-group basic mb-2">
                    <div class="input-wrapper">
                        <label class="label" for="mobile" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">{{ __('Contact Number') }} <span class="text-danger">*</span></label>
                        <input type="tel" id="mobile" name="mobile" value="{{ $address->mobile }}" required class="form-control" placeholder="{{ __('Enter Contact Mobile Number') }}" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 10px; font-family: 'Inter', sans-serif;">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>
 
                <div class="form-group basic mb-2">
                    <div class="input-wrapper">
                        <label class="label" for="line_1" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">{{ __('Site Address') }} <span class="text-danger">*</span></label>
                        <input type="text" id="line_1" name="line_1" value="{{ $address->line_1 }}" required class="form-control" placeholder="{{ __('Eg: Villa/Plot No., Building Name, Floor') }}" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 10px; font-family: 'Inter', sans-serif;">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>

                <div class="form-group basic mb-2">
                    <div class="input-wrapper">
                        <label class="label" for="line_2" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">{{ __('Area & Street') }} <span class="text-danger">*</span></label>
                        <input type="text" id="line_2" name="line_2" value="{{ $address->line_2 }}" required class="form-control" placeholder="{{ __('Eg: Road name, Sector, Zone') }}" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 10px; font-family: 'Inter', sans-serif;">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>


                <div class="form-group basic mb-2">
                    <div class="input-wrapper">
                        <label class="label" for="line_3" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">{{ __('Landmark (Optional)') }}</label>
                        <input type="text" id="line_3" name="line_3" value="{{ $address->line_3 }}" class="form-control" placeholder="{{ __('Enter Landmark details') }}" style="border-radius: 4px; border: 1px solid #d1d5db; padding: 10px; font-family: 'Inter', sans-serif;">
                        <span class="clear-input">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                    </div>
                </div>
 
                <div class="form-group basic mb-4">
                    <div class="input-wrapper">
                        <label class="label" for="location_id" style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #4B5563;">{{ __('Transportation Zone / Location') }} <span class="text-danger">*</span></label>
                        <select id="location_id" name="location_id" required class="form-control custom-select" style="border-radius: 4px; border: 1px solid #d1d5db; height: auto; padding: 10px; font-family: 'Inter', sans-serif;">
                            <option disabled selected value="">Select site location...</option>
 
                                @if( config('app.location_state') )

                                    @foreach($states as $state)      
                                        <optgroup label="{{ _local($state->name, $state->local_name) }}">

                                            @foreach($state->locations as $location)
                                                @if($location->id == $address->location_id )
                                                <option value="{{$location->id}}" selected>{{ _local($location->name, $location->local_name) }}</option>
                                                @else
                                                <option value="{{$location->id}}">{{ _local($location->name, $location->local_name) }}</option>
                                                @endif
                                            @endforeach

                                        </optgroup>
                                    
                                    @endforeach

                                @else
                                    <optgroup label="{{ getOption('store_country') }}">   
                                        @foreach($locations as $location)
                                            @if($location->id == $address->location_id )
                                            <option value="{{$location->id}}" selected>{{ _local($location->name, $location->local_name) }}</option>
                                            @else
                                            <option value="{{$location->id}}">{{ _local($location->name, $location->local_name) }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>

                                @endif
 
                        </select> 
                    </div> 
                </div> 
 
            </div>
 

            @if( $type == 'select')
            <div class="appBottomMenu pt-2 pb-2 px-3" style="position: relative; box-shadow: none; background: transparent; height: auto; border: none;">
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm" style="background-color: #1F2937 !important; border-color: #1F2937 !important; font-family: 'Inter', sans-serif; font-weight: bold; border-radius: 4px; padding: 12px;">{{ __('Update and Continue') }}</button>
            </div>
            @else
            <div class="section full mb-5 px-3">
                <div class="wide-block pt-2 pb-2" style="background: transparent; border: none; padding: 0;">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm" style="background-color: #1F2937 !important; border-color: #1F2937 !important; font-family: 'Inter', sans-serif; font-weight: bold; border-radius: 4px; padding: 12px;">{{ __('Update Address') }}</button>
                </div>
            </div>
            @endif

        </form>

    </div>
 
</div>
<!-- * App Capsule -->


@if( $type != 'select')
@include('mobile/layout/bottom-menu')
@endif