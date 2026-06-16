<form method="post" act-on="submit" act-request="{{ route('banner.slider.update', ['bannerSlider' => $bannerSlider->id]) }}" act-image-compress="image">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Banner Slider') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">        
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                <label>{{ __('Name') }}  <span class="text-danger">*</span></label>
                    <div>
                        <input type="text" name="name" value="{{ $bannerSlider->name }}"  required   class="form-control">
 
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Image') }} 
                            
                            <i data-bs-toggle="tooltip" data-placement="top" title="Size : 600px X 300px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                            </i>
                            <span class="text-danger">*</span>
                        </label>
                    <div>
                        <div>
                            <div class="custom-file">
                                <input type="file" id="edit-form-image" class="custom-file-input" name="image" hidden accept="image/*">
                                <div class="preview">
                                    @if( $bannerSlider->image == '')
                                    <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="img-thumbnail custom-file-preview" width="150"/>
                                    @else
                                    <img src="{{ asset('uploads/' . $bannerSlider->image) }}" class="img-thumbnail custom-file-preview" width="150"/>
                                    @endif
                                </div>
                                <label class="custom-file-label" for="edit-form-image">Choose file</label>
                            </div>                                    
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                <label>{{ __('Priority') }}  <span class="text-danger">*</span></label>
                    <div>
                        <select name="priority" required class="form-select select2">
                            @foreach(priority() as $priorityKey => $priorityValue)

                                @if($bannerSlider->priority == $priorityKey)
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
        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary waves-effect mr-2 px-3">
            Cancel
        </button>
        <button type="submit" disabled="disabled" class="btn btn-primary waves-effect waves-light mr-2 px-3">
            Update
        </button>
    </div> 
</form>