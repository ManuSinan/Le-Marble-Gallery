<div class="section full products-list mb-5" id="pagination-id-{{ ($page + 1) }}" style="background-color: #F8F8F8;">
    <ul class="listview image-listview media border-0" style="background: transparent;">
 
        @foreach($products as $product)

            <li class="border-0 mb-2 shadow-sm" style="background: #fff; border-radius: 8px; margin: 8px 12px; list-style: none;">
                <div class="item item-product-{{ $product->id }} p-2">
                    <div class="imageWrapper" style="width: 80px; height: 80px; border-radius: 6px; overflow: hidden; background: #e5e7eb;">
                        <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id, 'search' => $search ])]) }}" class="loading-fix"> 
                            @if( $product->image )
                            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;"/>
                            @else
                            <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;"/>
                            @endif
                        </a>
                    </div>
 
                    <div class="in d-flex flex-column pl-2" style="border: none; padding-left: 12px !important; flex: 1;">
                        <div class="d-flex justify-content-between align-items-start w-100">
                            <div class="pt-0 flex-grow-1">
                                <div class="title" style="font-size: 13px; font-weight: 700; color: #111827; font-family: 'Inter', sans-serif;">
                                    <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id, 'search' => $search ])]) }}" class="text-dark">
                                        {{ strtoupper($product->name) }}
                                    </a>
                                </div>
                                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">Finish: Polished | 18mm</div>
                                <div style="font-size: 11px; color: #6B7280;">Size: Custom Cut Available</div>
                                <div class="price mt-1" style="font-weight: 700; color: #111827; font-size: 14px;">
                                    {!! priceFormat($product->selling_price, '₹') !!} / Sq.Ft
                                </div>
                            </div>

                            @if( ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available > 0) )
                            <div class="cart-btn" style="align-self: center;">
                                @php $currentQty = productExistsInCart($product->id, 0); @endphp
                                <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->minimum_quantity }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn {{ ($currentQty == 0) ? 'empty' : '' }}" style="border-radius: 4px; height: 32px; border-color: #D4AF37 !important;">
                                    <div class="steper-btn-text" style="color: #D4AF37 !important; font-weight: bold;">{{ __('ADD') }}</div>
                                    <div class="steper-btn-minus" style="color: #D4AF37 !important;">-</div>
                                    <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</div>
                                    <div class="steper-btn-plus" style="color: #D4AF37 !important;">+</div>
                                </div>
                            </div>
                            @else
                            <div class="notify-btn" act-on="click" act-request="{{ route('mobile.notify', ['product' => $product->id]) }}" style="background-color: #EF4444; border-radius: 4px; padding: 4px 8px; font-size: 10px; font-weight: bold; color: white;">{{ __('AWAITING STOCK') }}</div>
                            @endif
                        </div>

                        <!-- Status row -->
                        <div class="w-100 mt-2 pt-1" style="border-top: 1px solid #f3f4f6; font-size: 10px; display: flex; justify-content: space-between; color: #6B7280; font-family: 'Inter', sans-serif;">
                            <span style="color: #10B981; font-weight: 600;">● Available: Yes</span>
                            <span>🕐 Pending Quotations: 0</span>
                        </div>
                    </div>
                </div>
                @if(productMessageInCart($product->id))
                <div class="item-message item-product-message-{{ $product->id }}" style="margin: 0 12px 8px 12px; padding: 4px 8px; background: #FEF3C7; border-radius: 4px; color: #D97706; font-size: 10px;">
                    {{ __( productMessageInCart($product->id) ) }}
                </div>  
                @endif 
            </li>

        @endforeach

    </ul>
</div>
@if($page < $products->lastPage())
<div class="section full mt-2 mb-5 px-5 pagination" id="pagination-nav-{{ ($page + 1) }}">
    <a href="{{ route('mobile.products', array_merge(request()->query(), ['page' => ($page + 1),  'sortby' => $sortby,  'search' => $search, 'category_id' => $category_id])) }}" class="btn btn-secondary btn-md btn-block" style="background: #D4AF37 !important; border-color: #D4AF37 !important; color: white !important; font-weight: bold;">{{ __('Load More') }}</a>     
</div>
@endif