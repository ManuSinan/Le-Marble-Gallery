@if($row->status)
    <span class="badge bg-success">{{ __('Active') }}</span>
@else
    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
@endif

