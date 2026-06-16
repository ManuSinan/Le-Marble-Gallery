@extends('backend/layout/app')

@section('body')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Add Home Spotlight') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('home.spotlight.store') }}" method="POST">
                    @include('backend/home-spotlight/form', ['spotlight' => null, 'submitLabel' => __('Create')])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
