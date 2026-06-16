@if(hasPermission('product.edit'))
<a href="{{ route('product.edit', ['product' => $row->id]) }}" class="view-btn">
    {{ $row->unit_type  . ' → ' . $row->unit_name }}
</a>
@else
    {{ $row->unit_type  . ' → ' . $row->unit_name }}
@endif