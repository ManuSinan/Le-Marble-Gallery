<style>
/* ── Product List: Responsive Grid ── */
.products-list-inner {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
    padding: 0 12px;
}
@media (min-width: 640px) {
    .products-list-inner {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        padding: 0 16px;
    }
}
@media (min-width: 960px) {
    .products-list-inner {
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        padding: 0 20px;
    }
}
@media (min-width: 1280px) {
    .products-list-inner {
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding: 0 24px;
    }
}

/* Mobile: horizontal list card */
.product-list-card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.07); display: flex; flex-direction: row; align-items: stretch; }
.product-list-card .card-img { width: 90px; min-width: 90px; height: 90px; object-fit: cover; flex-shrink: 0; }
.product-list-card .card-img-wrap { width: 90px; min-width: 90px; height: 90px; overflow: hidden; flex-shrink: 0; background: #e5e7eb; }
.product-list-card .card-body { padding: 10px 12px; flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
.product-list-card .card-bottom { border-top: 1px solid #f3f4f6; padding-top: 6px; margin-top: 6px; display: flex; justify-content: space-between; }

/* Desktop: vertical card */
@media (min-width: 640px) {
    .product-list-card { flex-direction: column; }
    .product-list-card .card-img-wrap { width: 100%; min-width: unset; height: 180px; }
    .product-list-card .card-img { width: 100%; height: 180px; min-width: unset; }
    .product-list-card .card-body { padding: 12px 14px; }
}
@media (min-width: 960px) {
    .product-list-card .card-img-wrap { height: 200px; }
    .product-list-card .card-img { height: 200px; }
}
@media (min-width: 1280px) {
    .product-list-card .card-img-wrap { height: 220px; }
    .product-list-card .card-img { height: 220px; }
}
</style>

<div class="section full products-list mb-5" id="pagination-id-{{ ($page + 1) }}" style="background-color: #F8F8F8; padding-top: 8px;">
    <div class="products-list-inner">
 
        @foreach($products as $product)

            <div class="product-list-card item-product-{{ $product->id }}">
                <div class="card-img-wrap">
                    <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id, 'search' => $search ])]) }}" class="loading-fix">
                        @if( $product->image )
                        <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="card-img"/>
                        @else
                        <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="card-img"/>
                        @endif
                    </a>
                </div>

                <div class="card-body">
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: #111827; font-family: 'Inter', sans-serif; line-height: 1.3; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.products', ['sortby' => $sortby, 'category_id' => $category_id, 'search' => $search ])]) }}" class="text-dark loading-fix">
                                {{ strtoupper($product->name) }}
                            </a>
                        </div>
                        <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">{{ $product->product_code }}</div>
                        <div class="price mt-1" style="font-weight: 700; color: #111827; font-size: 14px;">
                            {!! priceFormat($product->selling_price, '₹') !!} / Sq.Ft
                        </div>
                    </div>

                    <div class="card-bottom align-items-center">
                        <span style="font-size: 10px; color: #10B981; font-weight: 600;">● In Stock</span>

                        @if( ($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available > 0) )
                        @php $currentQty = productExistsInCart($product->id, 0); @endphp
                        <div data-id="{{ $product->id }}" data-clear="true" data-price="{{ $product->price }}" data-selling-price="{{ $product->selling_price }}" data-steper="{{ $product->unit->stepper }}" data-min="{{ $product->minimum_quantity }}" @if( $product->stock_status == 'limited') data-max="{{ $product->stock_available }}" @endif class="steper-btn {{ ($currentQty == 0) ? 'empty' : '' }}" style="border-radius: 4px; height: 32px; border-color: #D4AF37 !important;">
                            <div class="steper-btn-text" style="color: #D4AF37 !important; font-weight: bold;">{{ __('ADD') }}</div>
                            <div class="steper-btn-minus" style="color: #D4AF37 !important;">-</div>
                            <div class="steper-btn-value" style="color: #111827 !important; font-weight: bold;">{{ productExistsInCart($product->id, $product->minimum_quantity) }}</div>
                            <div class="steper-btn-plus" style="color: #D4AF37 !important;">+</div>
                        </div>
                        @else
                        <div class="notify-btn" act-on="click" act-request="{{ route('mobile.notify', ['product' => $product->id]) }}" style="background-color: #EF4444; border-radius: 4px; padding: 4px 8px; font-size: 10px; font-weight: bold; color: white; cursor: pointer;">{{ __('AWAITING STOCK') }}</div>
                        @endif
                    </div>

                    @if(productMessageInCart($product->id))
                    <div class="item-product-message-{{ $product->id }}" style="margin-top: 4px; padding: 4px 8px; background: #FEF3C7; border-radius: 4px; color: #D97706; font-size: 10px;">
                        {{ __( productMessageInCart($product->id) ) }}
                    </div>
                    @endif
                </div>
            </div>

        @endforeach

    </div>
</div>
@if($page < $products->lastPage())
<div class="section full mt-2 mb-5 px-5 pagination" id="pagination-nav-{{ ($page + 1) }}">
    <a href="{{ route('mobile.products', array_merge(request()->query(), ['page' => ($page + 1),  'sortby' => $sortby,  'search' => $search, 'category_id' => $category_id])) }}" class="btn btn-secondary btn-md btn-block" style="background: #D4AF37 !important; border-color: #D4AF37 !important; color: white !important; font-weight: bold;">{{ __('Load More') }}</a>
</div>
@endif