<form method="post" act-on="submit" act-request="{{ route('subcategory.update', ['category' => $category->id, 'subcategory' => $subcategory->id]) }}" act-image-compress="image">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Sub-category') }} — {{ $category->name }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">
        <div class="row">
            <div class="card">
                <ul class="nav nav-tabs mb-2" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-sub-edit-form" class="nav-link active" data-bs-toggle="tab">{{ __('En') }}</a>
                    </li>
                    @if(config('app.local_lang_code'))
                    <li class="nav-item">
                        <a href="#tabs-sub-edit-local-lang" class="nav-link" data-bs-toggle="tab">{{ ucfirst(config('app.local_lang_code')) }}</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="#tabs-sub-edit-advance" class="nav-link" data-bs-toggle="tab">{{ __('Advanced Options') }}</a>
                    </li>
                </ul>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tabs-sub-edit-form">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" name="name" value="{{ $subcategory->name }}" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Slug') }} <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" name="slug" value="{{ $subcategory->slug }}" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Image') }}
                                        <i data-bs-toggle="tooltip" data-placement="top" title="Size : 200px X 150px">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                        </i>
                                    </label>
                                    <div>
                                        <div class="custom-file">
                                            <input type="file" id="sub-edit-form-image" class="custom-file-input" name="image" hidden accept="image/*">
                                            <div class="preview">
                                                @if($subcategory->image == '')
                                                <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="custom-file-preview"/>
                                                @else
                                                <img src="{{ asset('uploads/' . $subcategory->image) }}" class="custom-file-preview"/>
                                                @endif
                                            </div>
                                            <label class="custom-file-label" for="sub-edit-form-image">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Description') }}</label>
                                    <div>
                                        <textarea class="form-control" name="description" rows="2">{{ $subcategory->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Priority') }} <span class="text-danger">*</span></label>
                                    <div>
                                        <select name="priority" required class="form-select select2">
                                            @foreach(priority() as $priorityKey => $priorityValue)
                                                @if($subcategory->priority == $priorityKey)
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

                        <div class="tab-pane" id="tabs-sub-edit-local-lang">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Name') }}</label>
                                    <div>
                                        <input type="text" name="local_name" value="{{ $subcategory->local_name }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tabs-sub-edit-advance">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Meta Title') }}</label>
                                    <div><input type="text" name="meta_title" value="{{ getOption('category_' . $subcategory->id . '_meta_title') }}" class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Meta Description') }}</label>
                                    <div><textarea class="form-control" name="meta_description" rows="2">{{ getOption('category_' . $subcategory->id . '_meta_description') }}</textarea></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords') }}</label>
                                    <div><textarea class="form-control" name="meta_keywords" placeholder="Enter Comma Separated Keywords" rows="2">{{ getOption('category_' . $subcategory->id . '_meta_keywords') }}</textarea></div>
                                </div>
                            </div>
                        </div>
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
