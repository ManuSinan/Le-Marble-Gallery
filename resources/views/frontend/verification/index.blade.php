@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }} - {{ __('Verification') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')
 
<div class="my-5 d-flex justify-content-center flex-column">
    <div class="row my-5 justify-content-center align-middle">
        <div class="col-md-12 text-center">
            <h1 class="title">{{ $title }}</h1>
            <div class="mb-4 lead">{{ $message }}</div>
            <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
        </div>
    </div>
</div>

@endsection
