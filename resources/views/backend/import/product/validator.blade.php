@foreach($errors as $key => $error)
<div class="alert alert-danger" role="alert">
  {{ __('Cell ') . str_replace('.', ':', $key). ' - ' .  $error[0] }}
</div>
@endforeach