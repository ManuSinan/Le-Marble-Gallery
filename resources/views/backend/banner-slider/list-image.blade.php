<div class="d-flex py-1 align-items-center">
@if( $row->image == '')
<span class="avatar me-2" style="background-image: url({{ asset('assets/backend/img/upload-image.png') }})"></span>
@else
<span class="avatar me-2" style="background-image: url({{ asset('uploads/' . $row->image) }})"></span>
@endif
<div class="flex-fill">
    <div class="ms-3">{{ $row->name}}</div>
</div>
</div>