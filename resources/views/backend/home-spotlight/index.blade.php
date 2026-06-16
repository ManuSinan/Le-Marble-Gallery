@extends('backend/layout/app')

@section('body')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Home Sponsored Spotlights') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('home.spotlight.create') }}" class="btn btn-primary">
                    {{ __('Add Spotlight') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Sort order') }}</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($spotlights as $index => $spotlight)
                        <tr>
                            <td>{{ $spotlights->firstItem() + $index }}</td>
                            <td>
                                @if($spotlight->product)
                                    <a href="{{ route('website.product', ['slug' => $spotlight->product->slug]) }}" target="_blank">
                                        {{ $spotlight->product->name }}
                                    </a>
                                @else
                                    <em class="text-muted">{{ __('Product deleted') }}</em>
                                @endif
                            </td>
                            <td>
                                @if($spotlight->status)
                                    <span class="badge bg-green">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>{{ $spotlight->sort_order }}</td>
                            <td class="text-end">
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('home.spotlight.edit', $spotlight) }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('home.spotlight.destroy', $spotlight) }}" method="POST" onsubmit="return confirm('{{ __('Delete this spotlight?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('No home spotlights configured yet.') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($spotlights instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => $spotlights->firstItem(),
                            'to' => $spotlights->lastItem(),
                            'total' => $spotlights->total(),
                        ]) }}
                    </p>
                    <div class="ms-auto">
                        {{ $spotlights->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
