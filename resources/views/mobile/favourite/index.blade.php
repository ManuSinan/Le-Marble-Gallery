<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="back headerButton item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="butt" stroke-linejoin="bevel"><path d="M19 12H6M12 5l-7 7 7 7"/></svg>
        </a>
    </div>
    <div class="pageTitle">{{ __('Favourite') }}</div>
    <div class="right">
        <a href="{{ route('mobile.cart',['referral' => route('mobile.favourite')]) }}" class="headerButton cart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 18C5.895 18 5.01 18.895 5.01 20C5.01 21.105 5.895 22 7 22C8.105 22 9 21.105 9 20C9 18.895 8.105 18 7 18ZM1 2V4H3L6.595 11.585L5.245 14.035C5.09 14.325 5 14.65 5 15C5 16.105 5.895 17 7 17H19V15H7.425C7.285 15 7.175 14.89 7.175 14.75C7.175 14.705 7.185 14.665 7.205 14.63L8.1 13H15.55C16.3 13 16.955 12.585 17.3 11.97L20.875 5.48C20.955 5.34 21 5.175 21 5C21 4.445 20.55 4 20 4H5.215L4.265 2H1ZM17 18C15.895 18 15.01 18.895 15.01 20C15.01 21.105 15.895 22 17 22C18.105 22 19 21.105 19 20C19 18.895 18.105 18 17 18Z" fill="currentColor"/>
            </svg>
            <span class="badge cart">{{ cartItemCount(null) }}</span>
        </a>
    </div>
</div>
 
 
<!-- App Capsule -->
<div class="appCapsule">
 

    @if($products && $products->count() > 0)
    <div class="section full">
        <div class="py-0 px-3">
   
            <div class="row  mt-3 mb-2">
                @foreach($products as $product)
                <div class="col-6 mb-2">
                    <div class="card product-card">
                        <div class="card-body position-relative">
                           <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.favourite') ]) }}" class="loading-fix"> 
                                @if( $product->image )
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image"/>
                                @else
                                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="image"/>
                                @endif
                            </a>
                            <h2 class="title"><a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.favourite') ]) }}">{{  Str::limit(_local($product->name, $product->local_name), 18) }}</a></h2>
                            <p class="text">{{ _local($product->units_type, $product->units_local_type) }}: {{ $product->minimum_quantity }} {{ _local($product->units_name, $product->units_local_name) }}</p>
                            <div class="price">{!! priceFormat( minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->units_stepper)  ) !!} @if($product->price >$product->selling_price ) <del class="ml-1">{!! priceFormat( minimumQuantityPrice($product->price, $product->minimum_quantity, $product->units_stepper) ) !!}</del> @endif</div>
  
                            <a href="{{ route('mobile.favourite.remove', ['product' => $product->id ]) }}" class="favourite-btn">
                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.84 2.61C19.3292 2.099 18.7228 1.69365 18.0554 1.41708C17.3879 1.14052 16.6725 0.998175 15.95 0.998175C15.2275 0.998175 14.5121 1.14052 13.8446 1.41708C13.1772 1.69365 12.5708 2.099 12.06 2.61L11 3.67L9.94 2.61C8.90831 1.57831 7.50903 0.998709 6.05 0.998709C4.59097 0.998709 3.19169 1.57831 2.16 2.61C1.12831 3.64169 0.548709 5.04097 0.548709 6.5C0.548709 7.95903 1.12831 9.35831 2.16 10.39L3.22 11.45L11 19.23L18.78 11.45L19.84 10.39C20.351 9.87924 20.7564 9.27281 21.0329 8.60536C21.3095 7.9379 21.4518 7.22249 21.4518 6.5C21.4518 5.77751 21.3095 5.0621 21.0329 4.39464C20.7564 3.72719 20.351 3.12076 19.84 2.61Z" fill="currentColor" stroke="#D10B50" stroke-opacity="0.67" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
 
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
 
        </div>
    </div>
    @else
    <div class="empty-products">


            <div class="error-page mt-5">
                <div class="icon-box text-primary">
                    <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M86.8333 19.2083C84.7052 17.0792 82.1784 15.3902 79.3973 14.2379C76.6162 13.0855 73.6354 12.4924 70.625 12.4924C67.6146 12.4924 64.6338 13.0855 61.8527 14.2379C59.0716 15.3902 56.5448 17.0792 54.4167 19.2083L50 23.625L45.5833 19.2083C41.2846 14.9096 35.4543 12.4946 29.375 12.4946C23.2957 12.4946 17.4654 14.9096 13.1667 19.2083C8.86795 23.5071 6.45295 29.3374 6.45295 35.4167C6.45295 41.496 8.86795 47.3263 13.1667 51.625L17.5833 56.0417L50 88.4583L82.4167 56.0417L86.8333 51.625C88.9625 49.4969 90.6515 46.9701 91.8038 44.189C92.9561 41.4079 93.5493 38.427 93.5493 35.4167C93.5493 32.4063 92.9561 29.4254 91.8038 26.6444C90.6515 23.8633 88.9625 21.3365 86.8333 19.2083Z" stroke="currentColor" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="title">{{  __('You haven\'t added any products yet!') }}</h1>
                <div class="text mb-5">
                    {{ __('Click ') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    {{ __(' to save products.') }}
                </div>
            </div>

    </div>
    @endif
 
</div>
<!-- * App Capsule -->
