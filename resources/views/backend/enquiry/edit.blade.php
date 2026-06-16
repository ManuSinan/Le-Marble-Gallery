<form method="post" act-on="submit" act-request="{{ route('enquiry.update', ['enquiry' => $enquiry->id]) }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit Enquiry') }}</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="_method" value="patch">        
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                <label>{{ __('Note') }} </label>
                    <div>
 
                        <textarea class="form-control"  name="note" rows="3">{{ $enquiry->note }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                <label>{{ __('Status') }}  <span class="text-danger">*</span></label>
                    <div>
                        <input type="text" name="status" value="{{ $enquiry->status }}"  required   class="form-control">
 
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                <label>{{ __('Product Name') }} <span class="text-danger">*</span></label>
                    <div>
                        <select name="product_id" required class="form-select select2">
                            <option disabled selected value=""></option>
                            @foreach($products as $product)
                                
                                @if($product->id == $enquiry->product_id)
                                <option value="{{$product->id}}" selected>{{$product->name}}</option>
                                @else
                                <option value="{{$product->id}}">{{$product->name}}</option>
                                @endif
                            
                            @endforeach
                        </select>                                           
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                <label>{{ __('User Name') }} <span class="text-danger">*</span></label>
                    <div>
                        <select name="user_id" required class="form-select select2">
                            <option disabled selected value=""></option>
                            @foreach($users as $user)
                                
                                @if($user->id == $enquiry->user_id)
                                <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                @else
                                <option value="{{$user->id}}">{{$user->name}}</option>
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