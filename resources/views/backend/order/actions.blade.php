@if(hasPermission('order.view'))
<a href="{{ route('order.view', ['order' => $row->id]) }}" class="view-btn">
    {{ ucfirst(str_replace('-', ' ', $row->status) )}}
</a>
@else
    {{ ucfirst(str_replace('-', ' ', $row->status) )}}
@endif