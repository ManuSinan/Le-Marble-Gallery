@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
    <div class="col-md-4 col-sm-12">
        <div class="mb-1">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Pincodes') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Delivery Pincodes') }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="d-flex">
            <div class="filter search">
                <div class="input-icon">
                    <input type="text" id="search" class="form-control" placeholder="Search {{ $search }}">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="15" cy="15" r="4" /><path d="M18.5 18.5l2.5 2.5" /><path d="M4 6h16" /><path d="M4 12h4" /><path d="M4 18h4" /></svg>
                    </span>
                </div>
            </div>
            @if(hasPermission('pincode.store'))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-form">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                {{ __('Add New') }}
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
@section('body')
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table act-datatable="{{ route('pincode.list') }}" search="#search" class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr class="bg-transparent">
                        <th name="id" priority="1" width="8%">SL</th>
                        <th name="pincode">Pincode</th>
                        <th name="area">Area</th>
                        <th name="minimum_cart_amount">Minimum Cart Amount</th>
                        <th name="delivery_charge">Delivery Charge</th>
                        <th name="actions" priority="7" width="12%">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        @if(hasPermission('pincode.store'))
        <div class="modal fixed-left fade" id="create-form" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-aside" role="document">
                <div class="modal-content fix-padding">
                    <form act-on="submit" act-request="{{ route('pincode.store') }}">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ __('Add Pincode') }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Pincode') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" required class="form-control" placeholder="e.g. 673601" maxlength="20">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Area') }}</label>
                                    <input type="text" name="area" class="form-control" placeholder="Optional area name" maxlength="100">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Minimum Cart Amount') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="minimum_cart_amount" required class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Delivery Charge upto Cart Amount') }}</label>
                                    <input type="text" name="delivery_cart_amount" class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false" placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Delivery Charge') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="delivery_charge" required class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary waves-effect mr-2 px-3">Cancel</button>
                            <button type="submit" disabled="disabled" class="btn btn-primary waves-effect waves-light mr-2 px-3">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @if(hasPermission('pincode.edit'))
        <div class="modal fixed-left fade" id="edit-form" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-aside" role="document">
                <div class="modal-content fix-padding">
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
