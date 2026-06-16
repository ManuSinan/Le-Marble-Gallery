{{-- Bridge: pages using simple-bookstore.layout get the new global storefront shell --}}
@extends('layouts.simple')

@section('extra_styles')
    @yield('extra_styles')
@endsection

@section('content')
    @yield('content')
@endsection

@section('scripts')
    @yield('scripts')
@endsection
