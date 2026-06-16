@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
    <div class="col-sm-12">
        <div class="mb-1">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Manage Store') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Manage Store') }}</h2>
    </div>
</div>
@endsection
@section('body')
<div class="row">
    <div class="col-lg-12">
 
        <div class="card px-2">
            <div class="card-body">
 

            <form act-on="submit" act-request="{{ route('option.update') }}"> 
               <div class="row">
                  <div class="card">
                     <ul class="nav nav-tabs mb-2" data-bs-toggle="tabs">
                        <li class="nav-item">
                           <a href="#tabs-create-form" class="nav-link active" data-bs-toggle="tab">En</a>
                        </li>

                        @if(config('app.local_lang_code'))
                        <li class="nav-item">
                           <a href="#tabs-create-local-lang" class="nav-link" data-bs-toggle="tab">{{ ucfirst(config('app.local_lang_code')) }}</a>
                        </li>
                        @endif

 
                     </ul>
                     <div class="card-body p-0 pt-2">
                        <div class="tab-content">
 

                           <div class="tab-pane show active" id="tabs-create-form">
                              <div class="row">
                                 <div class="col-sm-12">

                                    <div class="form-group">
                                       <label>{{ __('Popup Content') }}</label>
                                       <div>
                                          <textarea name="option[popup]"class="form-control" cols="30" rows="5">{{ getOption('popup') }}</textarea>
                                       </div>
                                    </div>
 
                                 </div>
                              </div>
                           </div>

                           <div class="tab-pane show active" id="tabs-create-form">
                              <div class="row">
                                 <div class="col-sm-12">

                                    <div class="form-group">
                                       <label>{{ __('Facebook page') }}</label>
                                       <div>
                                          <input type="text" name="option[facebook_page]" value="{{ getOption('facebook_page') }}" class="form-control">
                                       </div>
                                    </div>

                                    <div class="form-group">
                                       <label>{{ __('Twitter Handle') }}</label>
                                       <div>
                                          <input type="text" name="option[twitter_handle]" value="{{ getOption('twitter_handle') }}" class="form-control">
                                       </div>
                                    </div>

                                    <div class="form-group">
                                       <label>{{ __('YouTube Channel') }}</label>
                                       <div>
                                          <input type="text" name="option[youtube_channel]" value="{{ getOption('twitter_handle') }}" class="form-control">
                                       </div>
                                    </div>

                                    <hr>
                                    
                                    <div class="row">
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Store Address') }}</label>
                                             <div>
                                                <input type="text" name="option[store_address]" value="{{ getOption('store_address') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>
 
 
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Country') }}</label>
                                             <div>
                                                <input type="text" name="option[store_country]" value="{{ getOption('store_country') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Store Location') }}</label>
                                             <div>
                                                <select name="option[store_location_id]" class="form-select select2">
                                                    <option disabled selected value=""></option>

                                                    @if( config('app.location_state') )

                                                      @foreach($states as $state)      
                                                            <optgroup label="{{ $state->name }}">

                                                               @foreach($state->locations as $location)
                                                                  
                                                                  @if($location->id == getOption('store_location_id'))
                                                                  <option value="{{$location->id}}" selected>{{$location->name}}</option>
                                                                  @else
                                                                  <option value="{{$location->id}}">{{$location->name}}</option>
                                                                  @endif
                                                               
                                                               @endforeach

                                                            </optgroup>
                                                      
                                                      @endforeach

                                                   @else
 
                                                      @foreach($locations as $location)
                                                         
                                                         @if($location->id == getOption('store_location_id'))
                                                         <option value="{{$location->id}}" selected>{{$location->name}}</option>
                                                         @else
                                                         <option value="{{$location->id}}">{{$location->name}}</option>
                                                         @endif
                                                      
                                                      @endforeach

                                                   @endif;
                                                </select> 
                                             </div>
                                          </div>
                                       </div>
 
                                    </div>

                                    <div class="row">

                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Currency Code') }}</label>
                                             <div>
                                                <input type="text" name="option[currency_code]" value="{{ getOption('currency_code') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Currency') }}</label>
                                             <div>
                                                <input type="text" name="option[currency]" value="{{ getOption('currency') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Decimal Place') }}</label>
                                             <div>
                                                <select name="option[decimal_place]" class="form-select select2">
                                                    <option disabled selected value=""></option>
                                                    <option @if(getOption('decimal_place')  == '2') selected @endif value="2">2</option>
                                                    <option @if(getOption('decimal_place')  == '3') selected @endif value="3">3</option>
                                                </select> 
                                             </div>
                                          </div>
                                       </div>

                                    </div>
 


                                    
                                    <div class="row">

                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Order Enquiry Number') }}</label>
                                             <div>
                                                <input type="text" name="option[order_enquiry_number]" value="{{ getOption('order_enquiry_number') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Google Play Store Link') }}</label>
                                             <div>
                                                <input type="text" name="option[google_play_store_link]" value="{{ getOption('google_play_store_link') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>
 
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Apple App Store Link') }}</label>
                                             <div>
                                                <input type="text" name="option[apple_app_store_link]" value="{{ getOption('apple_app_store_link') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                    </div>

 
                                    <div class="row">

                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Website Meta Title') }}</label>
                                             <div>
                                                <input type="text" name="option[website_meta_title]" value="{{ getOption('website_meta_title') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>
 
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Website Meta Description') }} </label>
                                             <div>
                                                <textarea class="form-control"  name="option[website_meta_description]" rows="2">{{ getOption('website_meta_description') }}</textarea>
                                             </div>
                                          </div>
                                       </div>

                                       
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Website Meta Keywords') }} </label>
                                             <div>
                                                <textarea class="form-control"  name="option[website_meta_keywords]" rows="2">{{ getOption('website_meta_keywords') }}</textarea>
                                             </div>
                                          </div>
                                       </div>
 
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Shop Meta Title') }}</label>
                                             <div>
                                                <input type="text" name="option[shop_meta_title]" value="{{ getOption('shop_meta_title') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Shop Meta Description') }} </label>
                                             <div>
                                                <textarea class="form-control"  name="option[shop_meta_description]" rows="2">{{ getOption('shop_meta_description') }}</textarea>
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Shop Meta Keywords') }} </label>
                                             <div>
                                                <textarea class="form-control"  name="option[shop_meta_keywords]" rows="2">{{ getOption('shop_meta_keywords') }}</textarea>
                                             </div>
                                          </div>
                                       </div>


 
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Product Meta Description') }} </label>
                                             <div>
                                                <textarea class="form-control"  name="option[product_meta_description]" rows="2">{{ getOption('product_meta_description') }}</textarea>
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Product Meta Keywords') }} </label>
                                             <div>
                                                <textarea class="form-control"  name="option[product_meta_keywords]" placeholder="Enter Comma Separated Keywords Eg: Apple, Red Apple, Purchase Apple Fruit" rows="2">{{ getOption('product_meta_keywords') }}</textarea>
                                             </div>
                                          </div>
                                       </div>

 
                                    </div>
 
                                 </div>
                              </div>
                           </div>

                          


                           <div class="tab-pane" id="tabs-create-local-lang">
                              <div class="row">
                                 <div class="col-sm-12">
 
                                    <div class="row">
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Store Address') }}</label>
                                             <div>
                                                <input type="text" name="option[store_local_address]" value="{{ getOption('store_local_address') }}"  class="form-control">
                                             </div>
                                          </div>
                                       </div>


                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Country') }}</label>
                                             <div>
                                                <input type="text" name="option[store_local_country_state]" value="{{ getOption('store_local_country_state') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                     
 
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Currency') }}</label>
                                             <div>
                                                <input type="text" name="option[local_currency]"  value="{{ getOption('local_currency') }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>

                                    </div>
 
                                    <div class="row">
 
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Switch to ') }} {{ config('app.local_lang') }}</label>
                                             <div>
                                                <input type="text" name="option[switch_to_{{ config('app.local_lang_code') }}]"  value="{{ getOption('switch_to_' . config('app.local_lang_code') ) }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>


                                    </div>

                                    


                                    

                                 </div>

   

                              </div>
                           </div>

 

                        </div>
                     </div>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-lg-4 col-sm-12">
                     <div class="form-footer mt-3">
                        <button type="reset" class="btn btn-secondary">
                        Cancel
                        </button>
                        <button type="submit" class="btn btn-primary ms-2">
                        Save
                        </button>
                     </div>
                  </div>
               </div>
            </form>     


            </div>
        </div>
    </div>
</div>
@endsection
