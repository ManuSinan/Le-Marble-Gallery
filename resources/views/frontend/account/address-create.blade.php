@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ __('New Address') }}</h1>
        <nav> 
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('website.account') }}">{{ __('Account') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('website.address') }}">{{ __('Address') }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __('Add New') }}</li>
          </ol> 
        </nav> <!-- col.// -->
   </div>
</section>


<section class="section-content my-4">
  <div class="container">

   <div class="row">
     <aside class="col-md-3 order-2 order-md-1 mb-4">

      @include('frontend/account/menu')

    </aside>
    <main class="col-md-9  order-1 order-md-2">
      <div>
        <div class="card">
          <div class="card-body">
            <form act-on="submit" act-request="{{ route('website.address.save') }}">
              @csrf
              <div class="section mt-1">
               
                <div class="form-group">
                  <div class="custom-control custom-radio d-inline mr-2">
                    <input type="radio" id="type1" name="type" value="Home" checked class="custom-control-input">
                    <label class="custom-control-label" for="type1">{{ __('Home') }}</label>
                  </div>
                  
                  <div class="custom-control custom-radio d-inline">
                    <input type="radio" id="type2" name="type" value="Work" class="custom-control-input">
                    <label class="custom-control-label" for="type2">{{ __('Work') }}</label>
                  </div>  
                </div>


                <div class="form-row mb-2">

                  <div class="col-12 col-md-6 form-group">
                    <div class="input-wrapper">
                      <label class="label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                      <input type="text" id="name" name="name" required class="form-control"  placeholder="{{ __('Enter Name') }}">
                    </div>
                  </div>
                  
                  <div class="col-12 col-md-6 form-group">
                    <div class="input-wrapper">
                      <label class="label" for="mobile">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                      <input type="tel" id="mobile" name="mobile"  class="form-control"  placeholder="{{ __('Enter Mobile Number') }}">
                    </div>
                  </div>

                </div>


                <div class="form-row mb-2">

                  <div class="col-12 col-md-6 form-group">
                    <div class="input-wrapper">
                      <label class="label" for="line_1">{{ __('Address') }} <span class="text-danger">*</span></label>
                      <input type="text" id="line_1" name="line_1" required class="form-control"  placeholder="{{ __('Enter Address (Eg: House, Company, Building, Villa, Flat No.)') }}">
                    </div>
                  </div>

                  <div class="col-12 col-md-6 form-group">
                    <div class="input-wrapper">
                      <label class="label" for="line_2">{{ __('Area and Street') }} <span class="text-danger">*</span></label>
                      <input type="text" id="line_2" name="line_2" required class="form-control"  placeholder="{{ __('Enter Area and Street (Eg: Road, Avenue, Block No.)') }}">
                    </div>
                  </div>

                </div>


                <div class="form-row mb-2">

                  <div class="col-12 col-md-6 form-group">
                    <div class="input-wrapper">
                      <label class="label" for="line_3">{{ __('Landmark (Optional)') }}</label>
                      <input type="text" id="line_3" name="line_3" class="form-control"  placeholder="{{ __('Enter Landmark (Optional)') }}">
                      
                    </div>
                  </div>
                  
                  <div class="col-12 col-md-6 form-group">
                    <div class="input-wrapper">
                      @if($usePincode ?? true)
                      <label class="label" for="pincode_id">{{ __('Pincode') }} <span class="text-danger">*</span></label>
                      <select id="pincode_id" name="pincode_id" required class="form-control custom-select">
                        <option value="">{{ __('Select Pincode') }}</option>
                        @foreach($pincodes as $pincode)
                        <option value="{{ $pincode->id }}">{{ $pincode->pincode }}@if($pincode->area) ({{ $pincode->area }})@endif</option>
                        @endforeach
                      </select>
                      @else
                      <label class="label" for="location_id">{{ __('Location') }} <span class="text-danger">*</span></label>
                      <select id="location_id" name="location_id" required class="form-control custom-select">
                        <option value="">{{ __('Select Location') }}</option>
                        @if(config('app.location_state') && isset($states) && $states->isNotEmpty())
                        @foreach($states as $state)
                          @if($state->locations->isNotEmpty())
                          <optgroup label="{{ _local($state->name, $state->local_name) }}">
                            @foreach($state->locations as $location)
                            <option value="{{ $location->id }}">{{ _local($location->name, $location->local_name) }}</option>
                            @endforeach
                          </optgroup>
                          @endif
                        @endforeach
                        @elseif(isset($locations))
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ _local($location->name, $location->local_name) }}</option>
                        @endforeach
                        @endif
                      </select>
                      @endif
                    </div>
                  </div>

                </div>

                <div class="form-row mb-2">
                    <div class="col-sm-12">
                      @include('frontend.account.partials.form-actions', [
                          'saveText' => __('Save'),
                          'cancelUrl' => route('website.address'),
                      ])
                    </div>
                </div>

                
              </div>
              
            </form> 
            
          </div> <!-- card-body.// -->

        </div> <!-- card .// -->

      </div> <!-- order-group.// --> 
    </main>
  </div> <!-- row.// -->

</div> <!-- container .//  -->
</section>

@endsection
