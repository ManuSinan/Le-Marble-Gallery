@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }} - {{ __('Verification') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')
 
<div class="vh-100 d-flex justify-content-center flex-column">
    <div class="row justify-content-center align-middle">
        <div class="col-md-12 text-center">
            <span class="display-1 d-block">404</span>
            <div class="mb-4 lead">The page you are looking for was not found.</div>
            <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
        </div>
    </div>
</div>

@endsection
