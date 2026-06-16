<form method="post" act-on="submit" act-request="{{ route('daily.offer.update', ['dailyOffer' => $dailyOffer->id]) }}" act-image-compress="image">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Daily Offer') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <div>
                        <input type="text" name="title" value="{{ $dailyOffer->title }}" class="form-control" maxlength="150">
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
                                @if($dailyOffer->image == '')
                                <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="img-thumbnail custom-file-preview" width="150"/>
                                @else
                                <img src="{{ asset('uploads/' . $dailyOffer->image) }}" class="img-thumbnail custom-file-preview" width="150"/>
                                @endif
                            </div>
                            <label class="custom-file-label" for="edit-form-image">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Link (optional)') }}</label>
                    <div>
                        <input type="url" name="link" value="{{ $dailyOffer->link }}" class="form-control" placeholder="https://example.com">
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                    <div>
                        <select name="status" required class="form-select select2">
                            <option value="1" @if($dailyOffer->status) selected @endif>{{ __('Active') }}</option>
                            <option value="0" @if(!$dailyOffer->status) selected @endif>{{ __('Inactive') }}</option>
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

