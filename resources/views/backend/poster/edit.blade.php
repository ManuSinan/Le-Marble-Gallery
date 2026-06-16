<form method="post" act-on="submit" act-request="{{ route('poster.update', ['poster' => $poster->id]) }}" act-image-compress="image">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Promotional Banner') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                    <div>
                        <input type="text" name="name" value="{{ $poster->name }}" required class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Image') }}</label>
                    <div>
                        <div class="custom-file">
                            <input type="file" id="edit-form-image" class="custom-file-input" name="image" hidden accept="image/*">
                            <div class="preview">
                                @if($poster->image == '')
                                <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="img-thumbnail custom-file-preview" width="150"/>
                                @else
                                <img src="{{ asset('uploads/' . $poster->image) }}" class="img-thumbnail custom-file-preview" width="150"/>
                                @endif
                            </div>
                            <label class="custom-file-label" for="edit-form-image">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label>{{ __('Priority') }} <span class="text-danger">*</span></label>
                    <div>
                        <select name="priority" required class="form-select select2">
                            @foreach(priority() as $priorityKey => $priorityValue)
                                @if($poster->priority == $priorityKey)
                                    <option value="{{ $priorityKey }}" selected>{{ $priorityValue }}</option>
                                @else
                                    <option value="{{ $priorityKey }}">{{ $priorityValue }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary waves-effect mr-2 px-3">Cancel</button>
        <button type="submit" disabled="disabled" class="btn btn-primary waves-effect waves-light mr-2 px-3">Update</button>
    </div>
</form>
