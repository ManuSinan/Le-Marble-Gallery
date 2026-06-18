<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    @if($type == 'select')
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ __('Select Delivery Site') }}</div>   
    @else
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ __('Site Addresses') }}</div>
    @endif


    <div class="right">
        <a href="{{ route('mobile.address.create', ['type' => $type ]) }}" class="headerButton cart" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        </a>
    </div>
</div>
 
<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 180px !important;">
 

    <div class="section full mt-0">
        <ul class="listview address-list text flush transparent pt-1">

            @foreach($authUser->address as $address)
            <li class="mb-2 shadow-sm" style="background: #fff; border-radius: 8px; margin: 8px 12px; list-style: none; padding: 12px;">
                <div class="address @if($address->default) active @endif" style="border: none; padding: 0;">
                    <a href="{{ route('mobile.address.default', ['address' => $address->id, 'view' => $type ]) }}" class="address-info text-dark" style="display: block; text-decoration: none;">
                        <div class="type" style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #D4AF37; margin-bottom: 4px; display: flex; align-items: center;">
                            @if($address->default) 
                            <svg class="mr-1" style="position: relative; top: -1px; stroke: #D4AF37;" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> 
                            @endif
                            {{ __( $address->type ) }} 
                         </div>
                        <div class="name" style="font-weight: 700; font-family: 'Playfair Display', serif; font-size: 15px; color: #111827;">{{ $address->name }}</div>
                        <div class="mobile" style="font-size: 12px; color: #6B7280; margin-top: 2px;">{{ $address->mobile }}</div>
                        <div style="font-size: 13px; color: #374151; margin-top: 4px;">{{ $address->line_1 }}</div>
                        <div style="font-size: 13px; color: #374151;">{{ $address->line_2 }}</div>
                        @if($address->line_3)
                        <div style="font-size: 13px; color: #6B7280; font-style: italic;">Landmark: {{ $address->line_3 }}</div>
                        @endif
                        <div style="font-size: 13px; color: #D4AF37; font-weight: 600; margin-top: 4px;">{{ _local($address->location->name,$address->location->local_name) }}</div>
                    </a>
 
                    <div class="actions mt-2 pt-2" style="border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end;">
                        <a href="{{ route('mobile.address.edit', ['address' => $address->id, 'type' => $type ]) }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 4px; font-size: 11px; padding: 2px 10px; border-color: #d1d5db; color: #4b5563;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1" style="position: relative; top: -1px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> {{ __('Edit') }}
                        </a> 
                    </div>
    
                </div>
            </li>
            @endforeach
        </ul>

    </div>

    @if($type != 'select')
    <div class="wide-block pt-2 pb-2 px-3">
        <a href="{{ route('mobile.address.create', ['type' => $type]) }}" class="btn btn-primary btn-block shadow-sm" style="background-color: #1F2937 !important; border-color: #1F2937 !important; font-family: 'Inter', sans-serif; font-weight: bold; border-radius: 4px; padding: 10px;">{{ __('Add New Address') }}</a>     
    </div>
    @endif
 
</div>

@if($type == 'select')

    @if($defaultAddress)

    <div style="position: fixed; bottom: 68px; left: 0; right: 0; z-index: 998; background: transparent; padding: 0 10px;">
 
    @if($defaultAddress->location && $defaultAddress->location->minimum_cart_amount > cartTotalAmount() )
    <div class="message" style="background-color: #EF4444; color: #fff; position: relative; margin-bottom: 5px;">
     <span>{{ __('Minimum order value must be') }}</span>
     <span>{!! priceFormat($defaultAddress->location->minimum_cart_amount) !!}</span>
    </div>
    @endif

        <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37; margin: 0;">
            <div class="checkout-btn-info">
                <div class="info-small"><span class="cart-item-count text-light">{{ cartItemCount() }}</span> {{ cartTotalUnitLabel() }}</div>
                <div class="info-large mt-0"><span class="cart-total-sqft text-light">{{ cartTotalSqft() }}</span> {{ cartTotalUnitLabel() }}</div>
            </div>
            
            <a href="{{ route('mobile.order.summary') }}" class="checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
                {{ __('Continue') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
    
        </div>
    </div>
    @endif

@endif
