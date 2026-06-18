<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important; height: 56px !important; display: flex !important; align-items: center !important; justify-content: flex-start !important; padding: 0 16px !important;">
    <div class="left" style="position: relative !important; left: auto !important; top: auto !important; height: 56px; display: flex; align-items: center;">
        <a href="#" class="back headerButton item" style="color: #fff !important; display: flex; align-items: center; justify-content: center; height: 56px; padding: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important; padding-left: 8px !important; font-size: 18px; display: flex; align-items: center; justify-content: flex-start; margin: 0; left: auto !important; position: relative !important; top: auto !important; transform: none !important;">{{ Str::limit($title, 18) }}</div>
    <div class="right" style="display: flex; align-items: center; height: 56px; position: relative !important; right: auto !important; top: auto !important; margin-left: auto;">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#action-sort" style="color: #fff !important; display: flex; align-items: center; justify-content: center; padding: 0 8px; height: 56px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
        </a>
        <a href="#" class="headerButton toggle-searchbox" style="color: #fff !important; display: flex; align-items: center; justify-content: center; padding: 0 8px; height: 56px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
        </a>
        <a href="#" class="headerButton @if(!empty($filters['brands']) || !empty($filters['price_ranges'])) text-warning @endif" data-toggle="modal" data-target="#action-filter" style="color: #fff !important; display: flex; align-items: center; justify-content: center; padding: 0 8px; height: 56px; position: relative;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5v6l-3 3v-9l-5 -5.5a1 1 0 0 1 .5 -1.5" /></svg>
            @if(!empty($filters['brands']) || !empty($filters['price_ranges']))
                <span style="position: absolute; top: 12px; right: 2px; width: 8px; height: 8px; background: #D4AF37; border-radius: 50%;"></span>
            @endif
        </a>
    </div>
</div>

<!-- Search Component -->
<div id="search" class="appHeader @if($search) show @endif" style="background-color: #1F2937 !important; height: 56px !important; display: flex !important; align-items: center !important; padding: 0 16px !important;">
    <form class="search-form" action="/" act-on="submit" act-request="{{ route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id ]) }}" style="width: 100%;">
        <div class="form-group searchbox" style="margin: 0 auto; max-width: 100%; position: relative;">
            <input type="search" class="form-control" name="search" placeholder="{{ __('Search marble, granite, tiles and more...') }}" value="{{ $search }}" style="background-color: #fff; color: #111827; border-radius: 4px; border: 1px solid #d1d5db; padding-left: 40px; padding-right: 40px; height: 40px !important; line-height: 40px !important; font-size: 14px; width: 100%;">
            <i class="input-icon" style="color: #111827; position: absolute; left: 8px; top: 0; height: 40px; display: flex; align-items: center; justify-content: center; width: 36px; pointer-events: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
            </i>
            @if($search)
            <a href="{{ route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id]) }}" class="ml-1 close loading-fix" style="color: #A1A1A2 !important; position: absolute; right: 8px; top: 0; height: 40px; display: flex; align-items: center; justify-content: center; width: 36px; opacity: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
            @else
            <a href="#" class="ml-1 close toggle-searchbox" style="color: #A1A1A2 !important; position: absolute; right: 8px; top: 0; height: 40px; display: flex; align-items: center; justify-content: center; width: 36px; opacity: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
            @endif
        </div>
    </form>
</div>
<!-- * Search Component -->
 
<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8; padding-top: 64px !important; padding-bottom: 140px !important;">

    <div class="section mt-1">
        <div class="carousel-text owl-carousel owl-theme" style="padding: 10px 0;">
            <div class="item @if($category_id == null) start-position @endif">
                <a href="{{ route('mobile.products', ['sortby' => $sortby ]) }}" class="tab-slide @if($category_id == null) active @endif" style="font-weight: 600; border-radius: 20px; padding: 6px 16px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif;">{{ __('ALL STONES') }} </a>
            </div>

            @foreach($categories as $category)
                @if($category->id == $category_id) 
                <div class="item start-position">
                    <a href="{{ route('mobile.products', ['sortby' => $sortby, 'category_id' => $category->id]) }}" class="tab-slide active" style="font-weight: bold; color: #111827; border-bottom: 2px solid #D4AF37; padding: 6px 12px; font-family: 'Inter', sans-serif;">{{ strtoupper(_local($category->name, $category->local_name)) }}</a>
                </div>  
                @else
                <div class="item">
                    <a href="{{ route('mobile.products', ['sortby' => $sortby, 'category_id' => $category->id]) }}" class="tab-slide" style="color: #6B7280; padding: 6px 12px; font-family: 'Inter', sans-serif;">{{ strtoupper(_local($category->name, $category->local_name)) }}</a>
                </div>       
                @endif
            @endforeach

        </div>

    </div>

    @if($page == 1 && !$category_id && !$search && empty($filters['brands']) && empty($filters['price_ranges']) && isset($featuredProducts) && $featuredProducts->count() > 0)
    <!-- Featured Materials Section -->
    <div class="section full pt-2 mt-2" style="background: #F8F8F8; margin-bottom: 15px;">
        <div class="section-title medium mb-2" style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #111827; padding-left: 16px;">
            {{ __('Featured Materials') }}
        </div>
        <div class="py-0 px-3">
            <div class="row">
                @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 mb-2">
                    <div class="card product-card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden; background: white;">
                        <div class="card-body p-2">
                           <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.products', ['sortby' => $sortby]) ]) }}" class="loading-fix"> 
                                @if( $product->image )
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image" style="border-radius: 6px; height: 110px; width: 100%; object-fit: cover;"/>
                                @else
                                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="image" style="border-radius: 6px; height: 110px; width: 100%; object-fit: cover;"/>
                                @endif
                            </a>
                            <h2 class="title" style="font-size: 12px; margin-top: 8px; line-height: 1.3; font-weight: bold; margin-bottom: 4px;"><a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.products', ['sortby' => $sortby]) ]) }}" class="text-dark">{{ Str::limit($product->name, 22) }}</a></h2>
                            <p class="text" style="font-size: 10px; color: #6b7280; margin-bottom: 4px;">{{ $product->product_code }}</p>
                            <div class="price" style="color: #111827; font-weight: bold; font-size: 13px;">{!! priceFormat($product->selling_price, '₹') !!} / Sq.Ft</div>
                            <div class="cart-btn mt-2" style="width: 100%;">
                                @if( ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available > 0) )
                                    @php $currentQty = productExistsInCart($product->id, 0); @endphp
                                    <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->minimum_quantity }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn {{ ($currentQty == 0) ? 'empty' : '' }}" style="border-radius: 4px; height: 32px; border-color: #D4AF37 !important; width: 100%;">
                                        <div class="steper-btn-text" style="color: #D4AF37 !important; font-weight: bold;">{{ __('ADD') }}</div>
                                        <div class="steper-btn-minus" style="color: #D4AF37 !important;">-</div>
                                        <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</div>
                                        <div class="steper-btn-plus" style="color: #D4AF37 !important;">+</div>
                                    </div>
                                @else
                                    <div class="notify-btn text-center" act-on="click" act-request="{{ route('mobile.notify', ['product' => $product->id]) }}" style="background-color: #EF4444; border-radius: 4px; padding: 6px 8px; font-size: 11px; font-weight: bold; color: white; cursor: pointer; width: 100%;">{{ __('AWAITING STOCK') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($products->count() > 0)
    <div class="mt-2">
        @if($page == 1 && !$category_id && !$search && empty($filters['brands']) && empty($filters['price_ranges']))
        <div class="section-title medium mb-2" style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #111827; padding-left: 16px;">
            {{ __('All Materials') }}
        </div>
        @endif
        @include('mobile/product/list') 
    </div>
    @else
    <div class="mt-1 empty-products text-center py-5" style="background: white; margin: 12px; border-radius: 8px;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        <div style="font-size: 14px; font-weight: bold; color: #111827;">{{ __('No stone materials found.') }}</div>
    </div>
    @endif
 
</div>
<!-- * App Capsule -->

<!-- Default Action Sheet -->
<div class="modal fade action-sheet" id="action-sort" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px 12px 0 0;">
            <div class="modal-body">
                <ul class="action-button-list">
                    <li>
                        <a href="{{ route('mobile.products', ['sortby' => 'featured',  'search' => $search, 'category_id' => $category_id]) }}" class="btn btn-list" data-dismiss="modal" style="font-family: 'Inter', sans-serif;">
                            <span>{{ __('Featured') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mobile.products', ['sortby' => 'price-low-to-high',  'search' => $search, 'category_id' => $category_id]) }}" class="btn btn-list" data-dismiss="modal" style="font-family: 'Inter', sans-serif;">
                            <span>{{ __('Price -- Low to High') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mobile.products', ['sortby' => 'price-high-to-low',  'search' => $search, 'category_id' => $category_id]) }}" class="btn btn-list" data-dismiss="modal" style="font-family: 'Inter', sans-serif;">
                            <span>{{ __('Price -- High to Low') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- * Default Action Sheet -->
 
<div class="checkout-bottom-bar" style="position: fixed; bottom: 68px; left: 0; right: 0; z-index: 998; background: transparent; padding: 0 10px; @if(cartItemCount() == 0) display: none; @endif">
    <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37; margin: 0;">
        <div class="checkout-btn-info">
            <div class="info-small"><span class="cart-item-count">{{ cartItemCount() }}</span> {{ __('MATERIALS') }}</div>
            <div class="info-large mt-0"><span class="cart-total-sqft">{{ cartTotalSqft() }}</span> {{ cartTotalUnitLabel() }}</div>
        </div>
        <a href="{{ route('mobile.cart', ['referral' => route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id ])]) }}" class="text-light checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
            {{ __('View Basket') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
</div>
<!-- Myntra-style Filter Modal -->
<div class="modal fade panelbox panelbox-right" id="action-filter" tabindex="-1" role="dialog" style="z-index: 99999;">
    <div class="modal-dialog" role="document" style="max-width: 100%; margin: 0; height: 100%;">
        <form class="modal-content filter-form" act-on="submit" act-request="{{ route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id, 'search' => $search]) }}" style="height: 100%; border-radius: 0; display: flex; flex-direction: column; overflow: hidden; border: none;">
            
            <!-- Hidden inputs to preserve sort and search state -->
            <input type="hidden" name="sortby" value="{{ $sortby }}">
            @if($category_id)
                <input type="hidden" name="category_id" value="{{ $category_id }}">
            @endif
            @if($search)
                <input type="hidden" name="search" value="{{ $search }}">
            @endif

            <!-- Modal Header -->
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #E5E7EB; padding: 16px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
                <h5 class="modal-title" style="font-family: 'Inter', sans-serif; font-weight: 700; color: #111827; margin: 0; font-size: 16px;">Filters</h5>
                <button type="button" class="btn-clear-filters" style="background: none; border: none; color: #EF4444; font-weight: 700; font-size: 12px; font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 0.5px; outline: none; padding: 0;">Clear All</button>
            </div>

            <!-- Modal Body (Split Layout) -->
            <div class="modal-body p-0" style="flex: 1; display: flex; overflow: hidden; height: calc(100vh - 110px); background: #ffffff;">
                
                <!-- Left column (Categories) -->
                <div class="filter-categories" style="width: 38%; background-color: #F3F4F6; border-right: 1px solid #E5E7EB; overflow-y: auto; height: 100%;">
                    <div class="filter-cat-item active" data-target="filter-brand" style="padding: 16px 12px; font-size: 13px; color: #4B5563; font-weight: 600; border-bottom: 1px solid #E5E7EB; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;">Brand</div>
                    <div class="filter-cat-item" data-target="filter-price" style="padding: 16px 12px; font-size: 13px; color: #4B5563; font-weight: 600; border-bottom: 1px solid #E5E7EB; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;">Price Range</div>
                </div>

                <!-- Right column (Options) -->
                <div class="filter-options" style="width: 62%; background-color: #FFFFFF; overflow-y: auto; height: 100%; padding: 16px 12px;">
                    
                    <!-- Brand Options -->
                    <div class="filter-opt-group active" id="filter-brand">
                        <div style="font-size: 10px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px; font-family: 'Inter', sans-serif;">Select Brands</div>
                        @foreach($brands as $brand)
                            <label class="filter-checkbox-label" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 4px; font-size: 13px; color: #1F2937; cursor: pointer; border-bottom: 1px solid #F3F4F6; margin: 0; font-family: 'Inter', sans-serif;">
                                <span>{{ $brand->name }}</span>
                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}" @if(in_array($brand->id, $filters['brands'] ?? [])) checked @endif style="width: 18px; height: 18px; accent-color: #152B6E; border-radius: 4px; border: 1.5px solid #D1D5DB;">
                            </label>
                        @endforeach
                    </div>

                    <!-- Price Range Options -->
                    <div class="filter-opt-group" id="filter-price" style="display: none;">
                        <div style="font-size: 10px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px; font-family: 'Inter', sans-serif;">Price (₹)</div>
                        @php
                            $ranges = [
                                '0-500' => 'Under ₹500',
                                '500-5000' => '₹500 - ₹5,000',
                                '5000-20000' => '₹5,000 - ₹20,000',
                                '20000-100000' => '₹20,000 - ₹1,00,000',
                                '100000+' => '₹1,00,000+'
                            ];
                        @endphp
                        @foreach($ranges as $value => $label)
                            <label class="filter-checkbox-label" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 4px; font-size: 13px; color: #1F2937; cursor: pointer; border-bottom: 1px solid #F3F4F6; margin: 0; font-family: 'Inter', sans-serif;">
                                <span>{{ $label }}</span>
                                <input type="checkbox" name="price_ranges[]" value="{{ $value }}" @if(in_array($value, $filters['price_ranges'] ?? [])) checked @endif style="width: 18px; height: 18px; accent-color: #152B6E; border-radius: 4px; border: 1.5px solid #D1D5DB;">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer p-0" style="background: #ffffff; border-top: 1px solid #E5E7EB; display: flex; height: 54px; flex-shrink: 0; margin: 0; border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <button type="button" class="btn btn-secondary filter-close-btn" data-dismiss="modal" style="width: 50%; height: 100%; border: none; background: #FFFFFF; color: #4B5563; font-weight: 700; font-size: 14px; text-transform: uppercase; border-radius: 0; margin: 0; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; box-shadow: none;">Close</button>
                <button type="submit" class="btn btn-primary filter-submit-btn" style="width: 50%; height: 100%; border: none; background: #152B6E; color: #FFFFFF; font-weight: 700; font-size: 14px; text-transform: uppercase; border-radius: 0; margin: 0; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; box-shadow: none;">Apply</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling for active category tab */
    .filter-cat-item.active {
        background-color: #FFFFFF !important;
        color: #152B6E !important;
        font-weight: 700 !important;
        border-left: 4px solid #D4AF37 !important;
    }
</style>

<script>
    // Tab switching for categories inside the filter modal
    $(document).off('click', '.filter-cat-item').on('click', '.filter-cat-item', function(e) {
        e.preventDefault();
        $('.filter-cat-item').removeClass('active');
        $(this).addClass('active');
        
        var targetId = $(this).attr('data-target');
        $('.filter-opt-group').hide();
        $('#' + targetId).show();
    });

    // Clear all filters
    $(document).off('click', '.btn-clear-filters').on('click', '.btn-clear-filters', function(e) {
        e.preventDefault();
        $('#action-filter input[type="checkbox"]').prop('checked', false);
    });

    // Dismiss modal on form submit
    $(document).off('submit', '.filter-form').on('submit', '.filter-form', function(e) {
        $('#action-filter').modal('hide');
    });
</script>
