@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
    <div class="col-sm-12">
        <div class="mb-1">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Terms and Conditions') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Terms and Conditions') }}</h2>
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
                  <div class="col-sm-12">
                      
                     <div class="row">
                        <div class="col-lg-8 col-sm-12">
                           <div class="form-group">
                              <label>{{ __('Title') }}</label>
                              <div>
                                 <input type="text" name="page[tc_title]" value="{{ getOption('tc_title') }}" class="form-control">
                              </div>
                           </div>
                        </div>
                     </div>


                     <div class="row">
                        <div class="col-sm-12">
                           <div class="form-group">
                              <label>{{ __('Content') }}</label>
                              <div>
                                 <textarea name="page[tc_content]" style="display:none">{{ getOption('tc_content') }}</textarea>  
                                 <div for="page[tc_content]" class="editor" style="min-height:350px">{!! getOption('tc_content') !!}</div>
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
