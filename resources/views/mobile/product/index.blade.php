<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ Str::limit($title, 18) }}</div>
    <div class="right">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#action-sort" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
        </a>
        <a href="#" class="headerButton toggle-searchbox" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
        </a>
    </div>
</div>

<!-- Search Component -->
<div id="search" class="appHeader" style="background-color: #1F2937 !important;">
    <form class="search-form" action="/" act-on="submit" act-request="{{ route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id ]) }}">
        <div class="form-group searchbox" style="margin: 0 auto; max-width: 95%;">
            <input type="search" class="form-control" name="search" placeholder="{{ __('Search marble, granite, tiles and more...') }}" value="{{ $search }}" style="background-color: #fff; color: #111827; border-radius: 30px; border: 1px solid #d1d5db; padding-left: 40px;">
            <i class="input-icon" style="color: #111827;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
            </i>
            <a href="#" class="ml-1 close toggle-searchbox" style="color: #fff !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
        </div>
    </form>
</div>
<!-- * Search Component -->
 
<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8;">

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

    @if($products->count() > 0)
    <div class="mt-1">
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
 
<div class="appBottomMenu" style="border-top: none;">
    <div class="checkout-btn bg-primary text-light" style="background-color: #1F2937 !important; border-top: 2px solid #D4AF37;">
        <div class="checkout-btn-info">
            <div class="info-small"><span class="cart-item-count">{{ cartItemCount() }}</span> {{ __('MATERIALS') }}</div>
            <div class="info-large mt-0"><span class="cart-total-sqft">{{ cartTotalSqft() }}</span> Sq.Ft</div>
        </div>
        <a href="{{ route('mobile.cart', ['referral' => route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id ])]) }}" class="text-light checkout-btn-title" style="font-family: 'Inter', sans-serif; font-weight: bold; display: flex; align-items: center;">
            {{ __('View Basket') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
</div>
