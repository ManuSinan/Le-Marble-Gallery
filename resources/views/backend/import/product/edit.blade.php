@extends('backend/layout/app')
@section('header')

<div class="row align-items-center">
    <div class="col-md-12 col-sm-12">
        <div class="mb-3">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Product Bulk Update') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Product Bulk Update') }}</h2>
    </div>
</div>
@endsection

@section('body')
 
<div class="row p-3 pt-md-0">
    <div class="col-sm-12 col-lg-12">
 
      <div class="card">
          <div class="card-body p-0">
            <ul class="list-unstyled space-y-3">
              <li>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" /><path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" /><line x1="9.7" y1="17" x2="14.3" y2="17" /></svg>
                <a href="{{ route('import.product.edit.template') }}">
                    {{ __('Download the Excel Template') }}
                </a>
              </li>

              <li>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" /><path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" /><line x1="9.7" y1="17" x2="14.3" y2="17" /></svg>
              {{ __('Update Stock Status, Stock Available, Minimum Quantity, Price, Selling Price') }}
              </li>

              <li>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" /><path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" /><line x1="9.7" y1="17" x2="14.3" y2="17" /></svg>
               {{ __('Upload Excel & Bulk Update') }}
              </li>

            </ul>
          </div>
      </div>

    

    </div>

    <form act-on="submit" act-request="{{ route('import.product.edit') }}">
        <div class="row mt-4">
            <div class="col-lg-4 col-sm-12">
                <div class="form-group">
                    <label>{{ __('Upload Excel') }} </label>
                    <div>
                        <input type="file" name="file" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-lg-12" id="validator">
                 
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-4 col-sm-12">
                <div class="form-footer mt-3">
                <button type="reset" class="btn btn-secondary">
                {{ __('Cancel') }}
                </button>
                <button type="submit" class="btn btn-primary ms-2">
                {{ __('Bulk Update') }}
                </button>
                
                </div>
            </div>
        </div>
    </form>
                    
</div> 
  
@endsection
