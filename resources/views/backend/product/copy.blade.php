@extends('backend/layout/app')
@section('header')
<div class="row align-items-center">
   <div class="col-md-4 col-sm-12">
      <div class="mb-1">
         <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="javascript:;">{{ __('Application') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product') }}">{{ __('Product') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{ __('Copy') }}</a></li>
         </ol>
      </div>
      <h2 class="page-title" act-on="click">{{ __('Copy Product') }}</h2>
   </div>
   <div class="col-auto ms-auto d-print-none">
      <div class="d-flex">
         @if(hasPermission('product'))
         <a href="{{ route('product') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
               <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
               <line x1="9" y1="6" x2="20" y2="6" />
               <line x1="9" y1="12" x2="20" y2="12" />
               <line x1="9" y1="18" x2="20" y2="18" />
               <line x1="5" y1="6" x2="5" y2="6.01" />
               <line x1="5" y1="12" x2="5" y2="12.01" />
               <line x1="5" y1="18" x2="5" y2="18.01" />
            </svg>
            View All Products
         </a>
         @endif
      </div>
   </div>
</div>
@endsection
@section('body')
<div class="row">
   <div class="col-lg-12">
      <div class="card px-2">
         <div class="card-body">
            <form act-on="submit" act-request="{{ route('product.store') }}" act-image-compress="image, gallery_image_1, gallery_image_2, gallery_image_3">   
            <div class="row">
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


                        <li class="nav-item">
                           <a href="#tabs-create-advance" class="nav-link" data-bs-toggle="tab">{{ __('Advanced Options') }}</a>
                        </li>
                        
                     </ul>
                     <div class="card-body p-0 pt-2">
                        <div class="tab-content">
                           <div class="tab-pane active show" id="tabs-create-form">
 
                              <div class="row">
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Product Name') }}  <span class="text-danger">*</span></label>
                                       <div>
                                          <input type="text" slug-generate="#slug"   name="name"  required   class="form-control">
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Slug') }}  <span class="text-danger">*</span></label>
                                       <div>
                                          <input type="text" id="slug" name="slug"  required   class="form-control">
                                       </div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Variable Product') }} <span class="text-danger">*</span></label>
                                       <div>
                                          <select id="variable-product" name="variable_product" class="form-select select2">
                                             <option @if($product->combination_key == '') selected @endif value="no">No</option>
                                             <option @if($product->combination_key != '') selected @endif value="yes">Yes</option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Brand') }}</label>
                                       <div>
                                          <select name="brand_id" class="form-select select2">
                                              <option value="">&nbsp;</option>
                                                @foreach($brands as $brand)
                                                    
                                                    @if($brand->id == $product->brand_id)
                                                    <option value="{{$brand->id}}" selected>{{$brand->name}}</option>
                                                    @else
                                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                    @endif
                                                
                                                @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Category') }} <span class="text-danger">*</span></label>
                                       <div>
                                           <select name="category_id" required class="form-select select2">
                                              <option disabled value="">{{ __('Select Sub-category') }}</option>
                                              @php
                                                  $grouped = $categories->groupBy(fn($c) => $c->parent->name ?? 'Uncategorized');
                                              @endphp
                                              @foreach($grouped as $parentName => $subs)
                                                  <optgroup label="{{ $parentName }}">
                                                      @foreach($subs as $sub)
                                                          <option value="{{ $sub->id }}" {{ $product->category_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                                      @endforeach
                                                  </optgroup>
                                              @endforeach
                                          </select>
                                         
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Unit') }} <span class="text-danger">*</span></label>
                                       <div>
                                          <select name="unit_id" required act-on="change" act-request="{{ route('product.unit') }}" class="form-select select2">
                                              <option disabled selected value=""></option>
                                                @foreach($units as $unit)
                                                    
                                                    @if($unit->id == $product->unit_id)
                                                    <option value="{{$unit->id}}" selected>{{$unit->type}}: {{$unit->stepper}} {{$unit->name}}</option>
                                                    @else
                                                    <option value="{{$unit->id}}">{{$unit->type}}: {{$unit->stepper}} {{$unit->name}}</option>
                                                    @endif
                                                
                                                @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Product Code') }} </label>
                                       <div>
                                          <input type="text" name="product_code" class="form-control">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Stock Status') }}  <span class="text-danger">*</span></label>
                                       <div>
                                          <select id="stock_status" name="stock_status" required class="form-select select2">
                                             <option value="unlimited" @if($product->stock_status == 'unlimited') {{ 'selected' }} @endif >Unlimited</option>
                                             <option value="limited" @if($product->stock_status == 'limited') {{ 'selected' }} @endif >Limited</option>
                                          </select>  

                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Stock Available') }} <span class="stock-unit">{{ 'in ' . $product->unit->name }}</span></label>
                                       <div>
                                          <input type="text" value="{{ $product->stock_available }}" id="stock_available" name="stock_available"  @if($product->stock_status == 'unlimited') {{ 'disabled' }} @endif class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': 2, 'allowMinus': false, 'rightAlign': false">
                                       </div>
                                    </div>
                                 </div>


                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Minimum Quantity') }} <span class="stock-unit">{{ 'in ' . $product->unit->name }}</span> <span class="text-danger">*</span></label>
                                       <div>
                                          <input type="text" value="{{ $product->minimum_quantity }}" id="minimum_quantity" name="minimum_quantity" required  class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': 2, 'allowMinus': false, 'rightAlign': false">
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Price') }} <span class="price-unit">{{ 'for '. $product->unit->type . ': ' . $product->unit->stepper . ' ' . $product->unit->name }}</span>  <span class="text-danger">*</span></label>
                                       <div>
                                          <input type="text" name="price"  required   class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Selling Price') }} <span class="price-unit">{{ 'for '. $product->unit->type . ': ' . $product->unit->stepper . ' ' . $product->unit->name }}</span> <span class="text-danger">*</span></label>
                                       <div>
                                          <input type="text"  name="selling_price" required  class="form-control input-mask" data-inputmask="'alias': 'decimal', 'digits': {{ decimalPlace() }}, 'allowMinus': false, 'rightAlign': false">
                                       </div>
                                    </div>
                                 </div>


                                 
                                 <div class="col-sm-12">
                                    <div class="row">
                                       <div class="col-lg-8 col-sm-12">
                                          <div class="row">
                                             @if($taxs->count() > 0)
                                             <div class="col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                   <label>{{ __('Intra-State Tax') }}</label>
                                                   <div>
                                                      <select name="intra_state_tax[]" multiple class="form-select select2">
                                                         @foreach($taxs as $tax)
                                                         <option @if(in_array($tax->id, $product->intraStateTax->pluck('tax_id')->all())) {{'selected'}} @endif value="{{$tax->id}}">{{$tax->name}}</option>
                                                         @endforeach
                                                      </select>  
                                                   </div>
                                                </div>
                                             </div>


                                             <div class="col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                   <label>{{ __('Inter-State Tax') }}</label>
                                                   <div>
                                                      <select name="inter_state_tax[]" multiple class="form-select select2">
                                                         @foreach($taxs as $tax)
                                                         <option @if(in_array($tax->id, $product->interStateTax->pluck('tax_id')->all())) {{'selected'}} @endif  value="{{$tax->id}}">{{$tax->name}}</option>
                                                         @endforeach
                                                      </select>  
                                                   </div>
                                                </div>
                                             </div>

                                             @endif


                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                   <label>{{ __('Description') }} </label>
                                                   <div>
                                                      <textarea class="form-control"  name="description" rows="2">{{ $product->description }}</textarea>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                   <label>{{ __('Keywords') }} </label>
                                                   <div>
                                                      <textarea class="form-control"  name="keywords" placeholder="Enter Comma Separated Keywords Eg: Apple, Red Apple, Purchase Apple Fruit" rows="2">{{ $product->keywords }}</textarea>
                                                   </div>
                                                </div>
                                             </div>


                                             <div class="col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                <label>{{ __('Priority') }}  <span class="text-danger">*</span></label>
                                                   <div>
                                                      <select name="priority" required class="form-select select2">
                                                            @foreach(priority() as $priorityKey => $priorityValue)
                                                               <option value="{{ $priorityKey }}" @if($product->priority == $priorityKey) {{ 'selected' }} @endif>{{ $priorityValue }}</option>
                                                            @endforeach
                                                      </select>                                           
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                   <label>{{ __('Status') }}  <span class="text-danger">*</span></label>
                                                   <div>
                                                      <select name="status" required class="form-select select2">
                                                         <option value="draft" @if($product->status == 'draft') {{ 'selected' }} @endif>Draft</option>
                                                         <option value="published" @if($product->status == 'published') {{ 'selected' }} @endif>Published</option>
                                                         <option value="expired" @if($product->status == 'expired') {{ 'selected' }} @endif>Expired</option>
                                                      </select>  
                                                   </div>
                                                </div>
                                             </div>



                                          </div>
                                       </div>

                                       <div class="col-lg-4 col-sm-12">
 
                                       <div class="row">
 
 

                                          <div class="col-lg-4">
                                             <div class="form-group">
                                             <label>{{ __('Default Image') }} 
                                                   <i data-bs-toggle="tooltip" data-placement="top" title="Size : 800px X 600px">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                                   </i>
                                             </label>
                                                   <div>
                                                      <div class="custom-file">
                                                         <input type="file" id="edit-form-image" class="custom-file-input" name="image" hidden accept="image/*">
                                                         <div class="preview">
                                                            <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="custom-file-preview"/>
                                                         </div>
                                                         <label class="custom-file-label" for="edit-form-image">Choose file</label>
                                                      </div>                                    
                                                   </div>
                                             </div>
                                          </div>  


                                       </div>

                                       <div class="row mt-3">

                                          <div class="col-lg-4">
                                             <div class="form-group">
                                             <label>{{ __('Gallery Image') }} 
                                                   <i data-bs-toggle="tooltip" data-placement="top" title="Size : 800px X 600px">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                                   </i>
                                             </label>
                                                   <div>
                                                      <div class="custom-file">
                                                         <input type="file" id="edit-form-gallery-image-1" class="custom-file-input" name="gallery_image_1" hidden accept="image/*">
                                                         <div class="preview">
                                                            <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="custom-file-preview"/>
                                                         </div>
                                                         <label class="custom-file-label" for="edit-form-gallery-image-1">Choose file</label>
                                                      </div>                                    
                                                   </div>
                                             </div>
                                          </div>  


                                          <div class="col-lg-4">
                                             <div class="form-group">
                                             <label>{{ __('Gallery Image') }} 
                                                   <i data-bs-toggle="tooltip" data-placement="top" title="Size : 800px X 600px">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                                   </i>
                                             </label>
                                                   <div>
                                                      <div class="custom-file">
                                                         <input type="file" id="edit-form-gallery-image-2" class="custom-file-input" name="gallery_image_2" hidden accept="image/*">
                                                         <div class="preview">
                                                            <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="custom-file-preview"/>
                                                         </div>
                                                         <label class="custom-file-label" for="edit-form-gallery-image-2">Choose file</label>
                                                      </div>                                    
                                                   </div>
                                             </div>
                                          </div>  


                                          <div class="col-lg-4">
                                             <div class="form-group">
                                             <label>{{ __('Gallery Image') }} 
                                                   <i data-bs-toggle="tooltip" data-placement="top" title="Size : 800px X 600px">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                                   </i>
                                             </label>
                                                   <div>
                                                      <div class="custom-file">
                                                         <input type="file" id="edit-form-gallery-image-3" class="custom-file-input" name="gallery_image_3" hidden accept="image/*">
                                                         <div class="preview">
                                                            <img src="{{ asset('assets/backend/img/upload-image.png') }}" class="custom-file-preview"/>
                                                         </div>
                                                         <label class="custom-file-label" for="edit-form-gallery-image-3">Choose file</label>
                                                      </div>                                    
                                                   </div>
                                             </div>
                                          </div>  
 
                                       </div>
 
                                       </div>
                                    </div>
                                 </div>
 
                              </div>
 
                              <div id="variable-product-inputs" class="row @if($product->combination_key == '') d-none @endif">
 
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Combination') }} <span class="text-danger">*</span></label>
                                       <div>
                                             <select id="combination" class="form-select select2">
                                                <option value="new">{{ __('New') }}</option>
                                                <option selected value="existing">{{ __('Existing') }}</option>
                                             </select>
                                       </div>
                                    </div>
                                 </div>
 
                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Combination Attributes') }} <span class="text-danger">*</span></label>
                                       <div>
                                             <select name="attribute_id" act-on="change" act-request="{{ route('product.attribute') }}" class="form-select select2">
                                             <option value="">&nbsp;</option>
                                             @foreach($attributes as $attribute)
                                                   
                                                   @if($attribute->id == $product->attribute_id)
                                                   <option value="{{$attribute->id}}" selected>{{$attribute->name}}</option>
                                                   @else
                                                   <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                                                   @endif
                                             
                                             @endforeach
                                             </select>
                                       </div>
                                    </div>
                                 </div>
 

                                 <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Combination Name') }} <span class="text-danger">*</span></label>
                                       <div id="combination-name-existing">
                                          <select name="combination_key"  class="form-select select2 w-100 combination_key">
                                             <option value="">&nbsp;</option>
                                             @foreach($groups as $combination)
                                             <option @if($product->combination_key == $combination->combination_key ) selected @endif value="{{ $combination->combination_key }}">{{ $combination->combination_key }}</option>
                                             @endforeach
                                          </select>
                                          
                                       </div>

                                       <div id="combination-name-new" class="d-none">
                                          <input type="text" disabled name="combination_key" class="form-control combination_key">
                                       </div>
                                    </div>
                                 </div>

                              </div>

                              <div id="variants">
                                    <div class="row" >
                                    @if($product->variants)
                                       @foreach($product->variants as $variant)
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                                <label>{{ $variant->name }} <span class="text-danger">*</span></label>
                                                <div>
                                                   <select name="variants[{{ $variant->id }}]" required class="form-select select2">
                                                      <option selected disabled value=""></option>
                                                      @if($variant->options)
                                                            @foreach($variant->options as $option)
                                                            <option  value="{{$option->id}}">{{$option->value}}</option>
                                                            @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                          </div>
                                       </div>
                                       @endforeach
                                    @endif
                                    </div>
                              </div>

                           </div>
                           <div class="tab-pane" id="tabs-create-local-lang">
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="row">
                                       <div class="col-lg-4 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Name') }} </label>
                                             <div>
                                                <input type="text" value="{{ $product->local_name }}" name="local_name"   class="form-control">
                                             </div>
                                          </div>
                                       </div>
 
                                       <div class="col-lg-8 col-sm-12">
                                          <div class="form-group">
                                             <label>{{ __('Description') }} </label>
                                             <div>
                                                <input type="text" value="{{ $product->local_description }}" name="local_description" class="form-control">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="tab-pane" id="tabs-create-advance">
                              <div class="row">
 
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Private Note') }} </label>
                                       <div>
                                          <textarea name="private_note"class="form-control" cols="30" rows="5"></textarea>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Meta Title') }} </label>
                                       <div>
                                          <input type="text" name="meta_title" class="form-control">
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Meta Description') }} </label>
                                       <div>
                                          <textarea class="form-control"  name="meta_description" rows="2"></textarea>
                                       </div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Meta Keywords') }} </label>
                                       <div>
                                          <textarea class="form-control"  name="meta_keywords" placeholder="Enter Comma Separated Keywords Eg: Apple, Red Apple, Purchase Apple Fruit" rows="2"></textarea>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Youtube Video') }} </label>
                                       <div>
                                          <textarea class="form-control"  name="gallery_video" placeholder="Youtube Embed Code" rows="2"></textarea>
                                       </div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Related Products') }} </label>
                                       <div>
                                          <textarea class="form-control"  name="related" placeholder="Enter comma separated related product keywords" rows="1">{{ getOption('product_' . $product->id . '_related') }}</textarea>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>{{ __('Frequently Bought Together Products') }} </label>
                                       <div>
                                          <textarea class="form-control"  name="fbt" placeholder="Enter comma separated frequently bought together product keywords" rows="1">{{ getOption('product_' . $product->id . '_fbt') }}</textarea>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group">
                                          <label>{{ __('Content') }}</label>
                                          <div>
                                             <textarea name="rich_description" style="display:none">{{ $product->rich_description }}</textarea>  
                                             <div for="rich_description" class="editor" style="min-height:350px">{!! $product->rich_description !!}</div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>


                        </div>
                     </div>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-lg-4 col-sm-12">
                     <div class="form-footer mt-3">
                        <button type="button" class="btn btn-secondary" act-on="click" act-respond="{{ json_encode(['reload' => true]) }}">
                        Cancel
                        </button>
                        <button type="submit" class="btn btn-primary ms-2">
                        Save
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   $(function() {
       $('#stock_status').change(function(){
            if($(this).val() == 'limited'){
               $("#stock_available").prop('disabled', false);
            }else{
               $("#stock_available").prop('disabled', true);
               $("#stock_available").val('');
               $("#stock_available").next('.invalid-feedback').remove();
               $("#stock_available").removeClass('is-invalid');
            }
       });


      $('#combination').change(function(){
         if($(this).val() == 'existing'){
            $("#combination-name-existing").removeClass('d-none');
            $("#combination-name-existing .combination_key").prop('disabled', false);

            $("#combination-name-new").addClass('d-none');
            $("#combination-name-new .combination_key").prop('disabled', true);
         }else{

            $("#combination-name-existing").addClass('d-none')
            $("#combination-name-existing .combination_key").prop('disabled', true);

            $("#combination-name-new").removeClass('d-none');
            $("#combination-name-new .combination_key").prop('disabled', false);
         }
      });

      $('#variable-product').change(function(){
         if($(this).val() == 'yes'){
            $("#variable-product-inputs").removeClass('d-none');
         }else{
            $("#variable-product-inputs").addClass('d-none');
            
            $("[name='attribute_id']").val('');
            $("[name='attribute_id']").trigger('change');

            $("#combination").val('new');
            $("#combination").trigger('change');
         }
      });
   });
</script>
@endsection
