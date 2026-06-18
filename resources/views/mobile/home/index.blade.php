<!-- App Header — MG-style navy blue -->
<div class="appHeader text-light" style="background-color: #152B6E !important; height: 64px !important; min-height: 64px !important;">
    <div class="left" style="height: 64px !important;">
        <a href="#" class="headerButton sidenav-open" style="color: #ffffff !important; height: 64px !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
            </svg>
        </a>
    </div>
    <div class="pageTitle" style="text-align:center; color:#fff !important; display:flex; flex-direction:column; align-items:center; justify-content:center; height:64px !important; padding: 2px 0;">
        <img src="{{ asset('assets/mobile/logo-symbol.png') }}" alt="logo" style="max-height: 42px; width: auto; display: block; margin-bottom: 0px;">
        <span style="font-size: 8px; font-weight: 600; font-family: 'Inter', sans-serif; letter-spacing: 0.5px; color: rgba(255,255,255,0.85); text-transform: uppercase; line-height: 1; display: block; margin-top: 2px;">No. 1 Building Material Company</span>
    </div>
    <div class="right" style="height: 64px !important;">
        <a href="{{ route('mobile.cart',['referral' => route('mobile.home')]) }}" class="headerButton cart" style="color:#ffffff !important; position:relative; height: 64px !important;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M16 10a4 4 0 01-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @if(cartItemCount(null) > 0)
            <span class="badge badge-danger" style="position:absolute; top:14px; right:2px; background:#e53e3e !important; color:#fff; font-size:9px; min-width:16px; height:16px; border-radius:99px; display:flex; align-items:center; justify-content:center; padding:0 4px; font-weight:700;">{{ cartItemCount(null) }}</span>
            @endif
        </a>
    </div>
</div>

<!-- Extra Header — Search bar on navy bg -->
<div class="extraHeader p-0 auto-hide" style="background-color: #152B6E !important; height: auto !important; top: 64px !important;">
    <div class="section w-100" style="padding: 8px 12px 20px;">
        <form class="search-form" action="/" act-on="submit" act-request="{{ route('mobile.products') }}">
            <div style="position:relative;">
                <input type="search" name="search"
                       placeholder="{{ __('Search for products, brands and more...') }}"
                       style="width:100%; padding:10px 16px 10px 44px; border-radius:4px; border:none; background:#ffffff; color:#374151; font-size:14px; font-family:'Inter',sans-serif; outline:none; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
                <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; display:flex; align-items:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </span>
            </div>
        </form>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div class="appCapsule" style="background-color:#F2F4F8; padding-top:0;">

    <!-- Shop by Category -->
    <div class="section mb-1" style="padding:0 12px; margin-top: 148px !important;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <span style="font-family:'Inter',sans-serif; font-weight:700; font-size:17px; color:#111827;">{{ __('Shop by Category') }}</span>
            <a href="#" class="view-all-categories-link" style="color:#1a56db; font-weight:600; font-size:13px; font-family:'Inter',sans-serif; text-decoration:none; display:flex; align-items:center; gap:3px;">
                {{ __('View All') }}
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        <!-- Dynamic category cards from DB -->
        <div class="row" style="margin: 0 -6px;">
            @php
                $catCount = $categories->count();
                $catIndex = 0;
            @endphp
            @foreach($categories as $cat)
                @php
                    $catIndex++;
                    // Last card if odd total gets full width
                    $isLastOdd = ($catCount % 2 !== 0 && $catIndex === $catCount);
                    $colClass  = $isLastOdd ? 'col-12' : 'col-6';
                    $imgHeight = $isLastOdd ? '160px' : '140px';
                    // Use uploaded image if available (original size for perfect clarity), else a placeholder bg
                    $imgSrc = $cat->image ? asset('uploads/' . str_replace('/base/', '/original/', $cat->image)) : null;
                @endphp
                <div class="{{ $colClass }}" style="padding: 0 6px 12px;">
                    <div class="category-card-custom"
                         data-category="{{ $cat->slug }}"
                         style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; cursor: pointer;">
                        <div style="height: {{ $imgHeight }}; overflow: hidden; position: relative; background: #f3f4f6;">
                            @if($imgSrc)
                                <img src="{{ $imgSrc }}" alt="{{ $cat->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <!-- Gradient placeholder when no image set -->
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg,#e8edf7 0%,#d0daf0 100%);">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#152B6E" stroke-width="1.5" opacity="0.35"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18M3 9h18M3 15h18"/></svg>
                                </div>
                            @endif
                        </div>
                        <div style="padding: {{ $isLastOdd ? '14px 16px' : '12px' }}; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: {{ $isLastOdd ? '10px' : '8px' }};">
                                <div style="width: {{ $isLastOdd ? '32px' : '28px' }}; height: {{ $isLastOdd ? '32px' : '28px' }}; background: #e8edf7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #152B6E;">
                                    <svg width="{{ $isLastOdd ? '16' : '14' }}" height="{{ $isLastOdd ? '16' : '14' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18M3 9h18M3 15h18"/></svg>
                                </div>
                                <span style="font-size: {{ $isLastOdd ? '14px' : '13px' }}; font-weight: 700; color: #111827; font-family: 'Inter', sans-serif;">{{ $cat->name }}</span>
                            </div>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ============================================================
         TWO-STEP CATEGORY OVERLAY
         Step 1: Subcategory list  |  Step 2: Products list
         ============================================================ -->
    <div id="cat-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:68px; background:#f5f6fa; z-index:9999; font-family:'Inter',sans-serif; overflow:hidden; flex-direction:column;">

        <!-- ── OVERLAY HEADER ── -->
        <div id="cat-overlay-header" style="background:#152B6E; display:flex; align-items:center; min-height:56px; padding:0 8px; flex-shrink:0; justify-content:space-between;">
            <!-- Back / Close button -->
            <button id="cat-overlay-back" style="background:transparent; border:none; color:#fff; padding:8px 10px; cursor:pointer; display:flex; align-items:center; justify-content:center;" aria-label="Back">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </button>
            <!-- Title -->
            <div style="flex:1; text-align:center;">
                <div id="cat-overlay-title" style="font-weight:800; font-size:15px; letter-spacing:1px; color:#fff; text-transform:uppercase; line-height:1.1;">Shop by Category</div>
                <div id="cat-overlay-subtitle" style="font-size:9px; font-weight:400; color:rgba(255,255,255,0.72); letter-spacing:1.2px; text-transform:uppercase; margin-top:2px;">Select a sub-category</div>
            </div>
            <!-- Filter Button (on right) -->
            <button id="cat-overlay-filter" style="background:transparent; border:none; color:#fff; padding:8px 10px; cursor:pointer; display:none; align-items:center; justify-content:center;" aria-label="Filter">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5v6l-3 3v-9l-5 -5.5a1 1 0 0 1 .5 -1.5"/></svg>
            </button>
            <!-- Spacer to balance back button when filter is hidden -->
            <div id="cat-overlay-spacer" style="width:42px; height:42px;"></div>
        </div>

        <!-- ── STEP 1: SUBCATEGORY LIST ── -->
        <div id="cat-step-subcategory" style="flex:1; overflow-y:auto; padding:16px 12px 100px;">
            <!-- Injected by JS -->
        </div>

        <!-- ── STEP 2: PRODUCT LIST ── -->
        <div id="cat-step-products" style="display:none; flex:1; overflow-y:auto; padding:12px 12px 100px; background:#fafafa;">
            <!-- Injected by JS -->
        </div>

        <!-- ── BOTTOM CART DRAWER ── -->
        <div id="cat-cart-drawer" style="display:none; position:absolute; bottom:0; left:0; right:0; background:#152B6E; padding:14px 20px; color:#fff; align-items:center; justify-content:space-between; z-index:200; border-top:none !important;">
            <div>
                <div id="cat-cart-qty" style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">0 ITEMS</div>
                <div id="cat-cart-price" style="font-size:17px; font-weight:800; margin-top:1px;">₹0.00</div>
            </div>
            <a href="{{ route('mobile.cart') }}" style="color:#fff; font-size:14px; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:6px;">
                <span>View Cart</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <!-- ── DYNAMIC FILTER OVERLAY PANEL ── -->
        <div id="cat-filter-overlay" style="display:none; position:absolute; inset:0; background:#ffffff; z-index:10000; flex-direction:column; font-family:'Inter',sans-serif;">
            <!-- Filter Header -->
            <div style="background:#ffffff; border-bottom:1px solid #E5E7EB; display:flex; align-items:center; min-height:56px; padding:0 16px; justify-content:space-between; flex-shrink:0;">
                <h5 style="font-weight:700; color:#111827; margin:0; font-size:16px; font-family:'Inter',sans-serif;">Filters</h5>
                <button type="button" id="cat-filter-clear" style="background:none; border:none; color:#EF4444; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; padding:0; outline:none; font-family:'Inter',sans-serif;">Clear All</button>
            </div>
            
            <!-- Filter Body (Split Layout) -->
            <div style="flex:1; display:flex; overflow:hidden;">
                <!-- Left Tabs -->
                <div style="width:38%; background:#F3F4F6; border-right:1px solid #E5E7EB; overflow-y:auto; height: 100%;">
                    <div class="cat-filter-tab active" data-tab="brand" style="padding:16px 12px; font-size:13px; color:#4B5563; font-weight:600; border-bottom:1px solid #E5E7EB; cursor:pointer; font-family:'Inter',sans-serif;">Brand</div>
                    <div class="cat-filter-tab" data-tab="price" style="padding:16px 12px; font-size:13px; color:#4B5563; font-weight:600; border-bottom:1px solid #E5E7EB; cursor:pointer; font-family:'Inter',sans-serif;">Price Range</div>
                </div>
                <!-- Right Options -->
                <div style="width:62%; background:#ffffff; overflow-y:auto; padding:16px 12px; height: 100%;">
                    <!-- Brand Options -->
                    <div class="cat-filter-group active" id="cat-filter-group-brand">
                        <div style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:1px; margin-bottom:14px; font-family:'Inter',sans-serif;">Select Brands</div>
                        <div id="cat-filter-brands-list">
                            <!-- Injected dynamically by JS -->
                        </div>
                    </div>
                    <!-- Price Options -->
                    <div class="cat-filter-group" id="cat-filter-group-price" style="display:none;">
                        <div style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:1px; margin-bottom:14px; font-family:'Inter',sans-serif;">Price Range</div>
                        <div id="cat-filter-prices-list">
                            <!-- Injected dynamically by JS -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Footer -->
            <div style="border-top:1px solid #E5E7EB; display:flex; height:54px; flex-shrink:0;">
                <button type="button" id="cat-filter-close" style="width:50%; background:#ffffff; color:#4B5563; border:none; font-weight:700; font-size:14px; text-transform:uppercase; font-family:'Inter',sans-serif; outline:none;">Close</button>
                <button type="button" id="cat-filter-apply" style="width:50%; background:#152B6E; color:#ffffff; border:none; font-weight:700; font-size:14px; text-transform:uppercase; font-family:'Inter',sans-serif; outline:none;">Apply</button>
            </div>
        </div>
    </div>


</div>
<!-- * App Capsule -->

{{-- Pass the full category tree (from DB) to JS --}}
<script>
    window.bathCategoryTree = @json($categoryTree);
</script>
