@extends('backend/layout/app')
@section('header')

<div class="row align-items-center">
    <div class="col-md-12 col-sm-12">
        <div class="mb-3">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Reports') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Products') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Products') }}</h2>
        <p class="pt-1 mb-0">{{ $dateS->format("Y M d") . ' to ' . $dateE->format("Y M d") }}</p>
    </div>
</div>
@endsection

@section('body')
 
<div class="row p-3 pt-md-0">
    <div class="col-sm-12 col-lg-12">

        <div class="card">
            <div class="table-responsive  p-0">
                <table class="table table-vcenter report-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Products Sold</th>
                            <th>Total Business</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(count($data) > 0)
                        @foreach($data as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $value['Products Sold'] }}</td>
                            <td class="text-muted">{{ priceFormat($value['Total Business'], '₹ ', ',' ) }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td align="center" colspan="3">{{ __('No Records Found') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
 
    </div>
                    
</div> 
  
@endsection
