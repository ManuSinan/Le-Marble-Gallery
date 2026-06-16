@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
    <div class="col-md-4 col-sm-12">
        <div class="mb-1">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order') }}">{{ __('Order') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Edit') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Edit Order') }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="d-flex">
            <a href="javascript:window.print();" class="btn btn-primary me-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg>
                {{ __('Print')}}
            </a>

            @if(hasPermission('order'))
            <a href="{{ route('order') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="9" y1="6" x2="20" y2="6" /><line x1="9" y1="12" x2="20" y2="12" /><line x1="9" y1="18" x2="20" y2="18" /><line x1="5" y1="6" x2="5" y2="6.01" /><line x1="5" y1="12" x2="5" y2="12.01" /><line x1="5" y1="18" x2="5" y2="18.01" /></svg>
                {{ __('View All')}}
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
@section('body')
<div class="row">
    <div class="col-lg-12">
 
        <div class="card px-2">
            <div class="card-body">
    
                    <div class="row">
 
 
                        <div class="col-md-6">
 

                            <div class="section orde-info">
                                <div class="section-title">{{ __('Order Info') }}</div>
                                    @php
                                        $createdAt = \Carbon\Carbon::parse( $order->created_at );

                                        $dt = '';
                                        if($createdAt->isSameDay(now())){
                                            $dt = $createdAt->diffForHumans();
                                        }else{
                                            $dt = $createdAt->format('d M Y h:i A'); 
                                        }
                                    @endphp
                                    <div>
                                        <div>Reference No. <span class="text-highlight">{{ $order->id }}-{{ $createdAt->format('dmy') }}</span></div>
                                        <small>{{ $dt }}</small>

                                        <div class="mt-2">Status: <span class="text-highlight">{{ ucfirst( __(str_replace('-', ' ', $order->status)) ) }}</span></div>
                                    </div>
                            </div>


                            @if($order->items)
                            <div class="section ordered-items">
                                <div class="section-title">{{ __('Ordered items') }}</div>
                                <ul class="listview">
                                    @foreach($order->items as $item)

                                        <li>
                                            <div class="item">
                                                <div class="image-wrapper mr-1">
                                                    <div>
                                                        @if( $item->product_image )
                                                        <img src="{{ asset('uploads/' . $item->product_image) }}" alt="{{ $item->product_name }}"/>
                                                        @else
                                                        <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $item->product_name }}"/>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-grow-1">
                                                    <div class="flex-grow-1">
                                                        <div class="title">{{ _local($item->product_name, $item->local_product_name)}}</div>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="details">{{ _local($item->unit_type, $item->local_unit_type) }}:  {{ $item->quantity }} {{ _local($item->unit_name, $item->local_unit_name) }}</div>
                                                            <div class="price">{!! currency() !!} {!! priceFormat( $item->final_price , '') !!}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                    @endforeach
                                    
                                </ul>
                            </div>

                            @endif

                            <div class="section">
                                <div class="d-flex justify-content-between">
                                    <div>{{ __('Total Amount') }}</div>
                                    <div class="text-highlight">{!! priceFormat( $order->total_amount, '' ) !!}</div>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <div>{{ __('Delivery Charge') }}</div>
                                    <div class="text-highlight">{!! priceFormat( $order->delivery_charge, '' ) !!}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>{{ __('Final Amount') }}</div>
                                    <div class="text-highlight">{{ currency() }} {!! priceFormat( $order->final_amount, '' ) !!}</div>
                                </div>
                            </div>

                            <div class="section d-print-none">
                                <div class="section-title">{{ __('Order Updates') }}</div>


                                <div class="timeline">
                                    
                                    
                                    @foreach($order->statuss as $status)    

                                    @php
                                        $createdAt = \Carbon\Carbon::parse( $status->created_at );

                                        $dt = '';
                                        if($createdAt->isSameDay(now())){
                                            $dt = $createdAt->diffForHumans();
                                        }else{
                                            $dt = $createdAt->format('d M Y h:i A'); 
                                        }
                                    @endphp

                                    <div class="item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4 class="title mb-0">{{ ucfirst( str_replace('-', ' ', $status->status)  ) }}</h4>
                                            <small>{{ $dt }}</small>
                                            <div class="text">{{ $status->public_note }}</div>
                                            <div class="text">{{ $status->private_note }}</div>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>


                            <div class="section delivery-address">
                                <div class="section-title">{{ __('Delivery Address') }}</div>
                                <div class="deliver-to">
                                    <div class="d-flex">
                                    <div class="name"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5.52 19c.64-2.2 1.84-3 3.22-3h6.52c1.38 0 2.58.8 3.22 3"/><circle cx="12" cy="10" r="3"/><circle cx="12" cy="12" r="10"/></svg> {{ $order->address_name }}</div>
                                    <div class="mobile ml-2"> <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> <a href="tel:{{ $order->address_mobile }}">{{ $order->address_mobile }}</a></div>
                                    </div>

                                    <div>{{ $order->address_line_1 }}, {{ $order->address_line_2 }}</div>
                                    <div>{{ $order->address_line_3 }}</div>
                                    <div>{{ $order->address_location }}</div>
                                </div>
                            </div>
 
                        </div>

 
                        <div class="col-md-6 d-print-none ps-md-5">
                            
                            <form act-on="submit" act-request="{{ route('order.update', ['order' => $order->id]) }}">
                                <input type="hidden" name="_method" value="patch">   
 
                                   <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                            <label>{{ __('Status') }}  <span class="text-danger">*</span></label>
                                                <div>
                                                    <select name="status" required class="form-select select2">
                                                        <option value="">&nbsp;</option>
                                                        <option @if($order->status == 'accepted') selected @endif value="accepted">Accepted</option>
                                                        <option @if($order->status == 'rejected') selected @endif value="rejected">Rejected</option>
                                                        <option @if($order->status == 'on-the-way') selected @endif value="on-the-way">On the way</option>
                                                        <option @if($order->status == 'canceled') selected @endif value="canceled">Canceled</option>
                                                        <option @if($order->status == 'delivered') selected @endif value="delivered">Delivered</option>
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>
 
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                            <label>{{ __('Assign to') }}</label>
                                                <div>
                                                    <select name="assign_user_id" class="form-select select2">
                                                        <option value="">&nbsp;</option>
                                                        @foreach($users as $user)
                                                        <option @if($order->assign_user_id == $user->id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                            <label>{{ __('Note') }} </label>
                                                <div>
                                                    <textarea class="form-control"  name="public_note" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>


                                   </div>             
 
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div class="form-footer mt-3">
                                            <button type="reset" class="btn btn-secondary">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary ms-2">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
 

                    </div>
   
            </div>
        </div>
    </div>
</div>
@endsection
