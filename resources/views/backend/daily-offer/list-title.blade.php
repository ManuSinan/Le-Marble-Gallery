<div class="d-flex py-1 align-items-center">
@if($row->image == '')
<span class="avatar me-2" style="background-image: url({{ asset('assets/backend/img/upload-image.png') }})"></span>
@else
<span class="avatar me-2" style="background-image: url({{ asset('uploads/' . $row->image) }})"></span>
@endif
<div class="flex-fill">
    <div class="ms-3">
        <div>{{ $row->title ?: __('Daily Offer') }}</div>
        @if($row->link)
            <div class="text-muted small">
                <a href="{{ $row->link }}" target="_blank" rel="noopener noreferrer">{{ $row->link }}</a>
            </div>
        @endif
    </div>
</div>
</div>

