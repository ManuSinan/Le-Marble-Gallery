@extends('backend/layout/app')

@section('body')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Edit Home Spotlight') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('home.spotlight.update', $spotlight) }}" method="POST">
                    @method('PUT')
                    @include('backend/home-spotlight/form', ['submitLabel' => __('Update')])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
