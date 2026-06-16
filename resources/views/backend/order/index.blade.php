@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
    <div class="col-md-4 col-sm-12">
        <div class="mb-1">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Orders') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Orders') }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="d-flex">
            <div class="filter search">
                <div class="input-icon">
                    <form act-on="submit" action="/">
                        <input type="search" id="search" class="form-control" value="{{ $search }}" placeholder="Search {{ __('Products') }}">
                        <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="15" cy="15" r="4" /><path d="M18.5 18.5l2.5 2.5" /><path d="M4 6h16" /><path d="M4 12h4" /><path d="M4 18h4" /></svg>
                        </span>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('body')
<div class="row">
    <div class="col-lg-12">
         <div class="table-responsive">
            <table act-datatable="{{ route('order.list') }}" search="#search" class="table card-table table-vcenter text-nowrap datatable dtlink">
                <thead>
                    <tr class="bg-transparent">
                        <th name="id" priority="1" width="8%">SL</th>
                        <th name="ref_no" priority="2" width="15%">Reference No.</th>
                        <th name="datetime" priority="3" width="15%">Date Time</th>
                        <th name="name" priority="5" width="10%">User</th>
                        <th name="user_email" priority="7" width="12%">Email</th>
                        <th name="address_location" priority="6" width="15%">Location</th>
                        <th name="final_amount" priority="8" width="10%">Final Amount</th>
                        <th name="status" priority="4" width="10%">Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
