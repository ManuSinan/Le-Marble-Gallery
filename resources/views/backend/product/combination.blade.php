<select name="combination_key"  class="form-select select2 w-100 combination_key">
    <option value="">&nbsp;</option>
    @if($groups)
        @foreach($groups as $combination)
        <option value="{{ $combination->combination_key }}">{{ $combination->combination_key }}</option>
        @endforeach
    @endif
 </select>