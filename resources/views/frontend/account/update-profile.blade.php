@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ __('Manage Profile') }}</h1>
        <nav> 
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('website.account') }}">{{ __('Account') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Manage Profile') }}</li>
         </ol>   
        </nav> <!-- col.// -->
   </div>
</section>


<section class="section-content my-4">
    <div class="container">

     <div class="row">
         <aside class="col-md-3  order-2 order-md-1 mb-4">

          @include('frontend/account/menu')

      </aside>
      <main class="col-md-9 order-1 order-md-2 mb-4">
          <div>
              <div class="card">
                  <div class="card-body" id="page">
                      <form act-on="submit" act-request="{{ route('website.update.profile') }}">
                          
                        <div class="form-row mb-2">
                            <div class="col-12 col-md-6 form-group">
                                <label class="label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" value="{{ $user->name }}" required class="form-control"  placeholder="{{ __('Enter Name') }}">
                            </div> <!-- form-group// -->
                            <div class="col-12 col-md-6 form-group">            
                                <label class="label" for="email">{{ __('Email') }}</label>
                                <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control"  placeholder="{{ __('Enter Email Address') }}">
                                @if($user->email_verified == 0)
                                <div class="invalid-feedback" style="display: block;">{{ __('The email is not verified.') }}</div>
                                @endif
                            </div> 
                        </div>

                        <div class="form-row mb-2">
                            <div class="col-12 col-md-6 form-group"> 
                                <label class="label" for="mobile">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                                <input type="tel" id="mobile" name="mobile" required value="{{ $user->mobile }}" class="form-control"  placeholder="{{ __('Enter Mobile Number') }}">
                            </div>
                        </div>
 
                        <div class="form-row mb-2">
                           <div class="col-sm-12">
                              @include('frontend.account.partials.form-actions')
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
