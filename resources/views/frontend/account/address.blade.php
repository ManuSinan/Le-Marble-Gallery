@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')

<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ __('Your Addresses') }}</h1>
        <nav> 
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('website.account') }}">{{ __('Account') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Address') }}</li>
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
         <main class="col-md-9 order-1 order-md-2 mb-4">


            
            <div class="row">
               @if($authUser->address && $authUser->address->count() > 0)

                  @foreach($authUser->address as $address)
                  <div class="col-md-6">
                     <div class="box address-card mb-4">
                        <div class="address-card-header">
                           <span class="address-type">{{ $address->type }}</span>
                           <span class="address-name">{{ $address->name }}</span>
                        </div>
                        <div class="address-card-body">
                           <div class="address-line">{{ $address->mobile }}</div>
                           <div class="address-line">{{ $address->line_1 }}, {{ $address->line_2 }}</div>
                           @if($address->line_3)
                           <div class="address-line">{{ $address->line_3 }}</div>
                           @endif
                           <div class="address-line address-location">
                              {{ ($usePincode ?? false) && $address->pincode ? $address->pincode->pincode . ($address->pincode->area ? ' (' . $address->pincode->area . ')' : '') : ($address->location ? _local($address->location->name, $address->location->local_name) : '') }}
                           </div>
                        </div>
                        <div class="address-card-actions">
                           @if($address->default)
                              <span class="btn btn-sm btn-default-badge disabled">{{ __('Default') }}</span>
                           @else
                              <a href="javascript:void(0)" act-on="click" act-request="{{ route('website.address.default', [ 'address' => $address->id ] ) }}" class="btn btn-sm btn-action btn-default-action" title="{{ __('Make default') }}">
                                 {{ __('Make default') }}
                              </a>
                           @endif
                           <a href="{{ route('website.address.edit', ['address' => $address->id ]) }}" class="btn btn-sm btn-action btn-edit" title="{{ __('Edit') }}">
                              {{ __('Edit') }}
                           </a>
                           <a href="javascript:void(0)" act-on="click" act-confirm="{{ __('You want to delete!') }}" act-request="{{ route('website.address.destroy', [ 'address' => $address->id ] ) }}" class="btn btn-sm btn-action btn-delete" title="{{ __('Delete') }}">
                              {{ __('Delete') }}
                           </a>
                        </div>
                     </div>
                  </div>
                  @endforeach

                  <div class="col-md-6">
                     <a href="{{ route('website.address.create') }}" class="box address-add-card mb-4">
                        <span class="address-add-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        </span>
                        <span class="address-add-text">{{ __('Add New') }}</span>
                     </a>
                  </div>
 
               @else
               <div class="col-md-12">
                  <div class="card address-empty-card">
                     <div class="empty-content">
                        <div class="title">{{ __('You haven\'t added any address yet!') }}</div>
                        <p class="text mb-4">{{ __('Add your delivery addresses to speed up checkout.') }}</p>
                        <a href="{{ route('website.address.create') }}" class="btn btn-primary">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon mr-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                           {{ __('Add New Address') }}
                        </a>
                     </div>
                  </div>
               </div>
               @endif
            </div>

         </main>
      </div>
      <!-- row.// -->
   </div>
   <!-- container .//  -->
</section>
@endsection