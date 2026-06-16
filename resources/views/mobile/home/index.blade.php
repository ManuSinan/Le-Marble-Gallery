<!-- App Header -->
<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="headerButton sidenav-open" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="6" x2="20" y2="6" /><line x1="4" y1="12" x2="20" y2="12" /><line x1="4" y1="18" x2="20" y2="18" /></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; text-align: center; color: #fff !important; line-height: 1.2;">
        {{ config('app.name', 'Lee Marble Gallery') }}
        <span style="font-size: 8px; display: block; font-weight: normal; font-family: 'Inter', sans-serif; letter-spacing: 1px; color: #D4AF37;">Premium Stone & Interior Solutions</span>
    </div>
    <div class="right">
        <a href="{{ route('mobile.cart',['referral' => route('mobile.home')]) }}" class="headerButton cart" style="color: #D4AF37 !important;">
            <!-- Quotation basket icon -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" fill="currentColor"></rect>
            </svg>
            <span class="badge cart" style="background-color: #D4AF37 !important; color: #1F2937 !important; font-weight: bold;">{{ cartItemCount(null) }}</span>
        </a>
    </div>
</div>
 
<div class="extraHeader p-0 bg-primary text-light auto-hide" style="background-color: #1F2937 !important;">
    <div class="section w-100">
        <form class="search-form" action="/" act-on="submit" act-request="{{ route('mobile.products') }}">
            <div class="form-group searchbox" style="margin: 0 auto; max-width: 95%;">
                <input type="search" name="search" class="form-control" placeholder="{{ __('Search marble, granite, tiles and more...') }}" style="background-color: #fff; color: #111827; border-radius: 30px; border: 1px solid #d1d5db; padding-left: 40px;">
                <i class="input-icon" style="color: #111827;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                </i>
            </div>
        </form>
    </div>
</div>
<!-- * App Header -->
 
<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8;">

    @if($bannerSliders->count() > 0)
    <div class="section full mb-3" style="margin-top: 56px; min-height:160px">
        <div class="carousel-banner owl-carousel owl-theme">
            @foreach($bannerSliders as $bannerSlider)
            <div class="item" style="position: relative;">
                <img src="{{ asset('uploads/' . $bannerSlider->image) }}" alt="{{ $bannerSlider->name }}" class="imaged w-100 square" style="height: 180px; object-fit: cover; filter: brightness(0.8);">
                <div style="position: absolute; bottom: 20px; left: 20px; color: white;">
                    <h2 style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: bold; text-shadow: 1px 1px 4px rgba(0,0,0,0.6);" class="mb-0">{{ $bannerSlider->name }}</h2>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
 
    <div class="section mt-3 mb-3">
        <div class="section-title medium" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 18px; color: #111827;">{{ __( 'Browse Collections' ) }}</span>
            <a href="{{ route('mobile.products') }}" style="color: #D4AF37; font-weight: 600; font-size: 13px;">{{ __( 'View All →' ) }}</a>
        </div>
 
        <div class="row px-2">
            @php $parentCats = $categories->whereNull('parent_id')->values(); @endphp
            
            @if(isset($parentCats[0]))
            <!-- Card 1 (top-left) — Marble Collection -->
            <div class="col-6 p-1">
                <a href="{{ route('mobile.products', ['category_id' => $parentCats[0]->id]) }}" class="card m-0 border-0 shadow-sm text-decoration-none loading-fix">
                    <div style="height: 120px; background-image: url('{{ asset('images/marble-collection.jpg') }}'); background-size: cover; background-position: center; border-radius: 8px; position: relative;">
                        <div style="position: absolute; bottom: 8px; left: 8px; width: 32px; height: 32px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <!-- diamond/gem icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9z"></path></svg>
                        </div>
                    </div>
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <strong class="text-dark" style="font-size: 12px; font-family: 'Inter', sans-serif;">{{ $parentCats[0]->name }}</strong>
                        <span style="color: #D4AF37; font-weight: bold;">→</span>
                    </div>
                </a>
            </div>
            @endif

            @if(isset($parentCats[1]))
            <!-- Card 2 (top-right) — Granite Collection -->
            <div class="col-6 p-1">
                <a href="{{ route('mobile.products', ['category_id' => $parentCats[1]->id]) }}" class="card m-0 border-0 shadow-sm text-decoration-none loading-fix">
                    <div style="height: 120px; background-image: url('{{ asset('images/granite-collection.jpg') }}'); background-size: cover; background-position: center; border-radius: 8px; position: relative;">
                        <div style="position: absolute; bottom: 8px; left: 8px; width: 32px; height: 32px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <!-- mountain/stone icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2z"></path><path d="m18 10-4-4-6 6-4-4"></path></svg>
                        </div>
                    </div>
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <strong class="text-dark" style="font-size: 12px; font-family: 'Inter', sans-serif;">{{ $parentCats[1]->name }}</strong>
                        <span style="color: #D4AF37; font-weight: bold;">→</span>
                    </div>
                </a>
            </div>
            @endif

            @if(isset($parentCats[2]))
            <!-- Card 3 (full-width below) — Quartz & Tiles -->
            <div class="col-12 p-1 mt-1">
                <a href="{{ route('mobile.products', ['category_id' => $parentCats[2]->id]) }}" class="card m-0 border-0 shadow-sm text-decoration-none loading-fix">
                    <div style="height: 140px; background-image: url('{{ asset('images/quartz-collection.jpg') }}'); background-size: cover; background-position: center; border-radius: 8px; position: relative;">
                        <div style="position: absolute; bottom: 8px; left: 8px; width: 32px; height: 32px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <!-- tile/grid icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>
                        </div>
                    </div>
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <strong class="text-dark" style="font-size: 13px; font-family: 'Inter', sans-serif;">{{ $parentCats[2]->name }}</strong>
                        <span style="color: #D4AF37; font-weight: bold;">→</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>

    @if($featuredProducts->count() > 0 )
    <div class="section full pt-2 mt-2" style="background: #F8F8F8; border-top: 1px solid #e5e7eb;">
        <div class="section-title medium" style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 16px; color: #111827; padding-left: 16px; margin-bottom: 8px;">
            {{ __('Featured Materials') }}
        </div>
        <div class="py-0 px-3">
            <div class="row mb-1">
                @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 mb-2">
                    <div class="card product-card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden; background: white;">
                        <div class="card-body p-2">
                           <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.home') ]) }}" class="loading-fix"> 
                                @if( $product->image )
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image" style="border-radius: 6px; height: 110px; object-fit: cover;"/>
                                @else
                                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="image" style="border-radius: 6px; height: 110px; object-fit: cover;"/>
                                @endif
                            </a>
                            <h2 class="title" style="font-size: 12px; margin-top: 8px; line-height: 1.3; font-weight: bold;"><a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.home') ]) }}" class="text-dark">{{ Str::limit($product->name, 30) }}</a></h2>
                            <p class="text" style="font-size: 10px; color: #6b7280; margin-bottom: 4px;">{{ $product->product_code }}</p>
                            <div class="price" style="color: #111827; font-weight: bold; font-size: 13px;">{!! priceFormat($product->selling_price, '₹') !!} / Sq.Ft</div>
                        
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
 
    @if($offerProducts->count() > 0 )
    <div class="section full pt-2" style="background: #F8F8F8; border-top: 1px solid #e5e7eb; padding-bottom: 24px;">
        <div class="section-title medium" style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 16px; color: #111827; padding-left: 16px; margin-bottom: 8px;">
            {{ __('Special Offers') }}
        </div>
        <div class="py-0 px-3">
            <div class="row mb-1">
                @foreach($offerProducts as $product)
                <div class="col-6 col-md-4 mb-2">
                    <div class="card product-card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden; background: white;">
                        <div class="card-body p-2">
                           <a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.home') ]) }}" class="loading-fix"> 
                                @if( $product->image )
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image" style="border-radius: 6px; height: 110px; object-fit: cover;"/>
                                @else
                                <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $product->name }}" class="image" style="border-radius: 6px; height: 110px; object-fit: cover;"/>
                                @endif
                            </a>
                            <h2 class="title" style="font-size: 12px; margin-top: 8px; line-height: 1.3; font-weight: bold;"><a href="{{ route('mobile.product', ['product' => $product->id, 'referral' => route('mobile.home') ]) }}" class="text-dark">{{ Str::limit($product->name, 30) }}</a></h2>
                            <p class="text" style="font-size: 10px; color: #6b7280; margin-bottom: 4px;">{{ $product->product_code }}</p>
                            <div class="price" style="color: #111827; font-weight: bold; font-size: 13px;">
                                {!! priceFormat($product->selling_price, '₹') !!} / Sq.Ft 
                                @if($product->price > $product->selling_price ) 
                                    <del style="font-size: 10px; color: #9ca3af; margin-left: 4px;">{!! priceFormat($product->price, '₹') !!}</del> 
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
 
</div>
<!-- * App Capsule -->

@include('mobile/layout/bottom-menu')
