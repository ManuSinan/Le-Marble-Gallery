<form method="post" act-on="submit" act-request="{{ route('pincode.update', ['pincode' => $pincode->id]) }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Pincode') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('Pincode') }} <span class="text-danger">*</span></label>
                <input type="text" name="pincode" value="{{ $pincode->pincode }}" required class="form-control" maxlength="20">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('Area') }}</label>
                <input type="text" name="area" value="{{ $pincode->area }}" class="form-control" maxlength="100">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('Minimum Cart Amount') }} <span class="text-danger">*</span></label>
                <input type="text" name="minimum_cart_amount" value="{{ $pincode->minimum_cart_amount }}" required class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('Delivery Charge upto Cart Amount') }}</label>
                <input type="text" name="delivery_cart_amount" value="{{ $pincode->delivery_cart_amount }}" class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('Delivery Charge') }} <span class="text-danger">*</span></label>
                <input type="text" name="delivery_charge" value="{{ $pincode->delivery_charge }}" required class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary waves-effect mr-2 px-3">Cancel</button>
        <button type="submit" disabled="disabled" class="btn btn-primary waves-effect waves-light mr-2 px-3">Update</button>
    </div>
</form>
