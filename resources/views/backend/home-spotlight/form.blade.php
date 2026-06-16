@csrf
<div class="mb-3">
    <label class="form-label">{{ __('Product') }}</label>
    <select name="product_id" class="form-select" required>
        <option value="">{{ __('Select product') }}</option>
        @foreach($products as $id => $name)
            <option value="{{ $id }}" @if(old('product_id', $spotlight->product_id ?? null) == $id) selected @endif>
                {{ $name }}
            </option>
        @endforeach
    </select>
    @error('product_id')
    <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Sort order') }}</label>
    <input type="number" name="sort_order" class="form-control" min="0"
           value="{{ old('sort_order', $spotlight->sort_order ?? 0) }}">
    @error('sort_order')
    <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Status') }}</label>
    <select name="status" class="form-select" required>
        @php $currentStatus = old('status', isset($spotlight) ? (int)$spotlight->status : 1); @endphp
        <option value="1" @if($currentStatus === 1) selected @endif>{{ __('Active') }}</option>
        <option value="0" @if($currentStatus === 0) selected @endif>{{ __('Inactive') }}</option>
    </select>
    @error('status')
    <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-footer">
    <button type="submit" class="btn btn-primary">
        {{ $submitLabel ?? __('Save') }}
    </button>
</div>

