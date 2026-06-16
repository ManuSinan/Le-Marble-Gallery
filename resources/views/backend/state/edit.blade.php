<form method="post" act-on="submit" act-request="{{ route('state.update', ['state' => $state->id]) }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit State') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">        



        <div class="card">
            <ul class="nav nav-tabs mb-2" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tabs-edit-form" class="nav-link active" data-bs-toggle="tab">{{ __('En') }}</a>
                </li>
                @if(config('app.local_lang_code'))
                <li class="nav-item">
                    <a href="#tabs-edit-local-lang" class="nav-link" data-bs-toggle="tab">{{ ucfirst( config('app.local_lang_code') ) }}</a>
                </li>
                @endif
            </ul>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-edit-form">

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Name') }}  <span class="text-danger">*</span></label>
                                <div>
                                    <input type="text" name="name" value="{{ $location->name }}"  required   class="form-control">
            
                                </div>
                            </div>
                        </div>
 
                    </div>
                    <div class="tab-pane" id="tabs-edit-local-lang">

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Local Name') }} </label>
                                <div>
                                    <input type="text" name="local_name" value="{{ $location->local_name }}"   class="form-control">
            
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