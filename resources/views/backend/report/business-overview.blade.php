@extends('backend/layout/app')
@section('header')

<div class="row align-items-center">
    <div class="col-md-4 col-sm-12">
        <div class="mb-3">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Reports') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Business') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Business') }}</h2>
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
                            <th>Period</th>
                            <th>Orders</th>
                            <th>Products Sold</th>
                            <th>Delivery Charges</th>
                            <th>Total Business</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $value['Orders'] }}</td>
                            <td class="text-muted">{{ $value['Products Sold'] }}</td>
                            <td class="text-muted">{{ priceFormat($value['Delivery Charges'], '₹ ', ',' ) }}</td>
                            <td class="text-muted">{{ priceFormat($value['Total Business'], '₹ ', ',' ) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
 
    </div>
                    
</div> 
  
@endsection