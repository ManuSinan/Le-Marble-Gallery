@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
    <div class="col-md-4 col-sm-12">
        <div class="mb-1">
            <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Products') }}</a></li>
            </ol>
        </div>
        <h2 class="page-title" act-on="click">{{ __('Products') }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none filters">
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
            @if(hasPermission('product.create'))
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                {{ __('Add New Product') }}
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
            <table act-sort='[[ 0, "DESC" ]]' act-datatable="{{ route('product.list') }}" search="#search" class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr class="bg-transparent">
                        <th name="id" priority="1" width="8%">SL</th>
                        <th name="product_code" priority="8">Product Code</th>
                        <th name="name" priority="2">Product Name</th>
                        <th name="brand_name" priority="9">Brand</th>
                        <th name="category_name" priority="3">Category</th>
                        <th name="stock_available" priority="5">Stock</th>
                        <th name="selling_price" priority="4">Selling Price</th>
                        <th name="status" priority="7">Status</th>
                        <th name="actions" priority="6" width="12%">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
