@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ __('Login & security') }}</h1>
        <nav> 
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('website.account') }}">{{ __('Account') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Login & security') }}</li>
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
         <main class="col-md-9  order-1 order-md-2 mb-4">
            <div>
               <div class="card">
                  <div class="card-body">
                     <form act-on="submit" act-request="{{ route('website.change.password.request') }}" class="from">
                        
                        <div class="form-row mb-2">   
                           <div class="col-12 col-md-6 form-group">
                              <label>{{ __('Current Password') }}</label>
                              <input name="current-password" class="form-control" type="password" autocomplete="off">
                           </div>
                           <!-- form-group// -->
                           <div class="col-12 col-md-6 form-group">            
                              <label>{{ __('New Password') }}</label>
                              <input class="form-control" type="password" name="password">
                           </div>
                        </div>
                        

                        <div class="form-row mb-2">
                           <div class="col-sm-12">
                              @include('frontend.account.partials.form-actions')
                           </div>
                        </div>
                        <!-- form-group// -->    
                     </form>
                  </div>
                  <!-- card-body.// -->
               </div>
               <!-- card .// -->
            </div>
            <!-- order-group.// --> 
         </main>
      </div>
      <!-- row.// -->
   </div>
   <!-- container .//  -->
</section>
@endsection