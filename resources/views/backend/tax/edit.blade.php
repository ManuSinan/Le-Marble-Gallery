<form method="post" act-on="submit" act-request="{{ route('tax.update', ['tax' => $tax->id]) }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Tax') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">        


        <div class="card">
            <ul class="nav nav-tabs mb-2" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tabs-create-form" class="nav-link active" data-bs-toggle="tab">{{ __('En') }}</a>
                </li>
                @if(config('app.local_lang_code'))
                <li class="nav-item">
                    <a href="#tabs-create-local-lang" class="nav-link" data-bs-toggle="tab">{{ ucfirst( config('app.local_lang_code') ) }}</a>
                </li>
                @endif
            </ul>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-create-form">

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Name') }}  <span class="text-danger">*</span></label>
                                <div>
                                    <input type="text" name="name" value="{{ $tax->name }}"  required   class="form-control">
            
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Short') }}  <span class="text-danger">*</span></label>
                                <div>
                                    <input type="text" name="short" value="{{ $tax->short }}"  required   class="form-control">
            
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Percentage') }}  <span class="text-danger">*</span></label>
                                <div>
            
                                    <input type="text" name="percentage" value="{{ $tax->percentage }}"  required   class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="tabs-create-local-lang">

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Name') }} </label>
                                <div>
                                    <input type="text" name="local_name" value="{{ $tax->local_name }}"   class="form-control">
            
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Short') }} </label>
                                <div>
                                    <input type="text" name="local_short" value="{{ $tax->local_short }}"   class="form-control">
            
                                </div>
                            </div>
                        </div>

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