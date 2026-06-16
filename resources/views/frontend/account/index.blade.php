@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('style')
<style>
/* Match account sub-pages card look */
.section-content.account .card { border: 1px solid #e9e9eb; border-radius: 8px; }
.section-content.account .card .title { color: #282c3f; }
.section-content.account .card .title:hover { color: #c98a25; }
</style>
@endsection

@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ __('Account') }}</h1>
        <nav> 
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Account') }}</li>
            </ol>  
        </nav> <!-- col.// -->
   </div>
</section>

<section class="section-content account mt-2 mb-5">
<div class="container">

 <div class="row">
	<main class="col-md-12">
    <div class="row">
            <div class="col-md-4 mt-3">	
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="{{route('website.order')}}" class="title">{{ __('Your Orders') }}</a>
                            <p>{{ __('Track details or buy things again') }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->

            <div class="col-md-4 mt-3">
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="{{ route('website.favourite')}}" class="title">{{ __('Your Favourites') }}</a>
                            <p>{{ __('View favourite products') }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->


            <div class="col-md-4 mt-3">
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="11" r="3" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="{{route('website.address')}}" class="title">{{ __('Your Addresses') }}</a>
                            <p>{{ __('Add / edit addresses') }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->
            <div class="col-md-4 mt-3">
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="{{route('website.edit.profile')}}" class="title">{{ __('Manage Profile') }}</a>
                            <p>{{ __('Edit contact information') }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->
 
            <div class="col-md-4 mt-3">	
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="{{route('website.change.password')}}" class="title">{{ __('Sign in & security') }}</a>
                            <p>{{ __('Change Password') }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->

            @php $orderEnquiryNumber = getOption('order_enquiry_number'); @endphp
            @if($orderEnquiryNumber)
            <div class="col-md-4 mt-3">
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /><path d="M15 7a2 2 0 0 1 2 2" /><path d="M15 3a6 6 0 0 1 6 6" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="tel:{{ $orderEnquiryNumber }}" class="title">{{ __('Customer Support') }}</a>
                            <p>{{ $orderEnquiryNumber }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->
            @endif

            <div class="col-md-4 mt-3">
                <div class="card card-body">
                    <figure class="itemside">
                        <div class="aside">
                            <span class="icon-sm rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="42" height="42" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                            </span>
                        </div>
                        <figcaption class="info">
                            <a href="{{ route('signout') }}" class="title" style="color: #d32f2f;">{{ __('Sign Out') }}</a>
                            <p>{{ __('Logout from your account') }}</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </div> <!-- panel-lg.// -->
            </div><!-- col // -->


        </div>
    
	</main>
</div> <!-- row.// -->

</div> <!-- container .//  -->
</section>

@endsection
