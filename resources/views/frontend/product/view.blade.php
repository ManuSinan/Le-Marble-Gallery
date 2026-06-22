@extends('frontend/layout/main')
@section('seo')
<title>{{ $compatibility == true ? $requestCompatible : $title }}</title>
<meta name="description" content="{{ $compatibility == true ? str_replace($title, $requestCompatible, $metaDescription) : $metaDescription }}" />
<meta name="keywords" content="{{ $compatibility == true ? str_replace($title, $requestCompatible, $metaKeywords) : $metaKeywords }}" />
<meta name="robots" content="index, follow">
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $compatibility == true ? $requestCompatible : $title }}" />
@if($product->image)
<meta property="og:image" content="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" />
@endif
<meta property="og:description" content="{{ $compatibility == true ? str_replace($title, $requestCompatible, $metaDescription) : $metaDescription }}" />
<meta property="og:url" content="{{ request()->url() }}" />
<script type="application/ld+json">
{
    "@@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $compatibility == true ? $requestCompatible : $title }}",
    @if($product->image)
    "image": "{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}",
    @endif
    "description": "{{ $compatibility == true ? str_replace($title, $requestCompatible, $product->description) : $product->description }}",
    @if($product->brand)
    "brand": { "@type": "Brand", "name": "{{ $product->brand->name }}" },
@endif
    "category": "{{ $product->category->name }}",
    "url": "{{ request()->url() }}",
    @if(productTotalPriceInCart($product->id, $product->price) > productTotalSellingPriceInCart($product->id, $product->selling_price))
    "offers": {
        "@type": "Offer",
        "url": "{{ request()->url() }}",
        "priceCurrency": "INR",
        @if(($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0))
        "availability": "https://schema.org/InStock",
        @endif
        "price": "{{ $product->selling_price }}"
    },
    @endif
    "sku": "{{ $product->product_code }}"
}
</script>
@endsection

@section('body')
<div class="pdp-myntra">
    <nav class="pdp-breadcrumb">
        <div class="container">
            <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{ route('home') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{ route('website.products', ['slug' => $product->category->slug]) }}">{{ _local($product->category->name, $product->category->local_name) }}</a>
                </li>
                <li class="breadcrumb-item active">{{ Str::limit(_local($product->name, $product->local_name), 40) }}</li>
            </ol>
        </div>
    </nav>

    <div class="container">
        <div class="pdp-grid">
            <!-- Left: Product media -->
            <div class="pdp-gallery">
                <div class="pdp-main-image">
                    @if($product->image)
                        <img src="{{ asset('uploads/' . str_replace('/base/','/large/', $product->image)) }}" alt="{{ $product->name }}" id="zoom-image" class="img-fluid">
                    @else
                        <img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="img-fluid">
                    @endif
                    @if($product->gallery_video)
                    <span class="pdp-media-badge" title="{{ __('Video') }}">▶</span>
                    @endif
                </div>
                @if($product->gallery_image_1 || $product->gallery_image_2 || $product->gallery_image_3 || $product->image)
                <div class="pdp-thumbs">
                    @if($product->image)
                    <div class="pdp-thumb active">
                        <img src="{{ asset('uploads/' . $product->image) }}" alt="">
                    </div>
                    @endif
                    @foreach([1, 2, 3] as $num)
                        @php $imageField = "gallery_image_{$num}"; @endphp
                        @if($product->$imageField)
                        <div class="pdp-thumb">
                            <img src="{{ asset('uploads/' . $product->$imageField) }}" alt="">
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Product info -->
            <div class="pdp-detail">
                @if($product->brand)
<p class="pdp-brand">{{ strtoupper(_local($product->brand->name, $product->brand->local_name)) }}</p>
@endif
                <h1 class="pdp-title">{{ _local($product->name, $product->local_name) }}</h1>
                @if($compatibility)
                <div class="pdp-compat">{{ __('Compatible with') }} {{ $requestCompatible }}</div>
                @endif

                <!-- Rating -->
                <div class="pdp-rating-row">
                    <span class="pdp-rating-num">{{ number_format($averageRating ?? 0, 1) }}</span>
                    <span class="pdp-rating-star">★</span>
                    <span class="pdp-rating-count">({{ $reviewsCount ?? 0 }} {{ __('Ratings') }})</span>
                </div>

                <!-- Price: MRP + Best price -->
                <div class="pdp-price-block">
                    @if(productTotalPriceInCart($product->id, $product->price) > productTotalSellingPriceInCart($product->id, $product->selling_price))
                    <p class="pdp-mrp">MRP {!! currency() !!}{!! priceFormat(productTotalPriceInCart($product->id, minimumQuantityPrice($product->price, $product->minimum_quantity, $product->unit->stepper)), '') !!}</p>
                    @endif
                    <p class="pdp-tax">{{ __('inclusive of all taxes') }}</p>
                    <p class="pdp-best-label">{{ __('BEST OFFERS') }}</p>
                    <p class="pdp-best-price">{{ __('Best Price') }}: {!! currency() !!}{!! priceFormat(productTotalSellingPriceInCart($product->id, minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->unit->stepper)), '') !!}</p>
                </div>

                <!-- Variant / Size -->
                @if($product->attribute && $product->attribute->variants)
                    @foreach($product->attribute->variants as $variants)
                        @if($variants && $product->productGroupVariant($variants->id)->count() >= 1)
                        <div class="pdp-size-section">
                            <p class="pdp-size-heading">{{ __('SELECT') }} {{ strtoupper($variants->name) }}</p>
                            @php $variantOptions = $product->productGroupVariant($variants->id)->get(); @endphp
                            @if($variantOptions->count() > 6)
                            <select class="form-select pdp-size-select" data-product-base-url="{{ url('/product') }}">
                                @foreach($variantOptions as $option)
                                <option value="{{ $option->product->slug }}" {{ $option->product->id == $product->id ? 'selected' : '' }}>{{ $option->variantOption->value }}</option>
                                @endforeach
                            </select>
                            @else
                            <div class="pdp-size-btns">
                                @foreach($variantOptions as $option)
                                @if($option->product->id == $product->id)
                                <button type="button" class="pdp-size-btn active">{{ $option->variantOption->value }}</button>
                                @else
                                <a href="{{ route('website.product', ['slug' => $option->product->slug]) }}" class="pdp-size-btn">{{ $option->variantOption->value }}</a>
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                @endif

                <!-- Add to Bag / Wishlist -->
                <div class="pdp-actions">
                    @if(($product->stock_status == 'unlimited') || ($product->stock_status == 'limited' && $product->stock_available >= $product->minimum_quantity && $product->stock_available > 0))
                    <button type="button" class="pdp-btn-add btn-add-to-cart"
                            data-product-id="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-selling-price="{{ $product->selling_price }}"
                            data-steper="{{ $product->unit->stepper }}"
                            data-min="{{ $product->unit->stepper }}">
                        <span class="pdp-btn-icon"></span> {{ __('ADD TO BAG') }}
                    </button>
                    @else
                    <button type="button" class="pdp-btn-notify btn-notify-me @if(auth()->check() && \App\Models\Enquiry::where('user_id', auth()->id())->where('product_id', $product->id)->count() > 0) active @endif" act-on="click" act-request="{{ route('website.notify', ['product' => $product->id]) }}">{{ __('NOTIFY ME') }}</button>
                    @endif
                    <button type="button" class="pdp-btn-wishlist btn-favorite @if(auth()->check() && \App\Models\Favourite::where('user_id', auth()->id())->where('product_id', $product->id)->count() > 0) active @endif" act-on="click" act-request="{{ route('website.favourite.toggle', ['product' => $product->id]) }}">
                        <span class="pdp-btn-icon">♡</span> {{ __('WISHLIST') }}
                    </button>
                </div>

                <!-- Delivery options -->
                <div class="pdp-delivery">
                    <p class="pdp-delivery-heading">{{ __('DELIVERY OPTIONS') }}</p>
                    <div class="pdp-delivery-row">
                        <input type="text" class="pdp-pincode" id="pdp-pincode" placeholder="{{ __('Enter PIN code') }}" maxlength="6" value="">
                        <button type="button" class="pdp-pincode-btn">{{ __('CHECK') }}</button>
                    </div>
                    <p class="pdp-delivery-hint">{{ __('Enter PIN code to check delivery time & availability') }}</p>
                    <p class="pdp-delivery-msg" id="pdp-delivery-msg"></p>
                </div>

                <!-- Trust -->
                <ul class="pdp-trust">
                    <li>{{ __('100% Original Products') }}</li>
                    <li>{{ __('Pay on delivery might be available') }}</li>
                    <li>{{ __('Easy 14 days returns and exchanges') }}</li>
                </ul>

                <div class="pdp-description mt-4">
                    <h3 class="pdp-desc-title">{{ __('Description') }}</h3>
                    <div class="pdp-desc-text">
                        {!! nl2br($compatibility == true ? str_replace($title, $requestCompatible, _local($product->description, $product->local_description)) : _local($product->description, $product->local_description)) !!}
                    </div>
                </div>

                @if($product->model_name || $product->finish_colour || $product->product_type || $product->installation_type || $product->compatibility_notes)
                <div class="pdp-specs mt-4 pt-3 border-top">
                    <h3 class="pdp-desc-title mb-3">{{ __('Specifications') }}</h3>
                    <div class="pdp-specs-grid">

                        
                        @if($product->finish_colour)
                        <div class="pdp-spec-item">
                            <span class="pdp-spec-label">{{ __('Finish / Colour') }}</span>
                            <span class="pdp-spec-value">{{ $product->finish_colour }}</span>
                        </div>
                        @endif

                        @if($product->product_type)
                        <div class="pdp-spec-item">
                            <span class="pdp-spec-label">{{ __('Product Type') }}</span>
                            <span class="pdp-spec-value">{{ $product->product_type }}</span>
                        </div>
                        @endif

                        @if($product->installation_type)
                        <div class="pdp-spec-item">
                            <span class="pdp-spec-label">{{ __('Installation Type') }}</span>
                            <span class="pdp-spec-value">{{ $product->installation_type }}</span>
                        </div>
                        @endif
                    </div>

                    @if($product->compatibility_notes)
                    <div class="pdp-compat-notes mt-3 p-3 bg-light rounded border-start border-warning border-3">
                        <strong class="text-dark d-block mb-1">{{ __('Compatibility & Notes') }}</strong>
                        <span class="text-muted small">{{ $product->compatibility_notes }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="reviews-section mt-5 pdp-reviews">
            @if(session('success'))
                <div class="alert alert-success mb-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif

            @php $dist = $ratingDistribution ?? [5=>0,4=>0,3=>0,2=>0,1=>0]; $totalRev = array_sum($dist); $maxBar = max(1, max($dist)); @endphp
            <div class="pdp-reviews-layout">
                <div class="pdp-ratings-block">
                    <div class="pdp-ratings-score">
                        <span class="pdp-ratings-num">{{ number_format($averageRating ?? 0, 1) }}</span>
                        <span class="pdp-ratings-star-icon">★</span>
                    </div>
                    <p class="pdp-ratings-verified">{{ $reviewsCount ?? 0 }} {{ __('reviews') }}</p>
                    <div class="pdp-ratings-bars">
                        @for($s = 5; $s >= 1; $s--)
                        <div class="pdp-rating-bar-row">
                            <span>{{ $s }}</span>
                            <div class="pdp-rating-bar-wrap">
                                <div class="pdp-rating-bar-fill" style="width: {{ ($dist[$s] ?? 0) / $maxBar * 100 }}%"></div>
                            </div>
                            <span class="pdp-rating-bar-count">{{ $dist[$s] ?? 0 }}</span>
                        </div>
                        @endfor
                    </div>
                </div>
                <div>
                    <h2 class="pdp-reviews-main-title">{{ __('Ratings and reviews') }}</h2>

                    @auth
                        @if(!($userHasReviewed ?? false))
                        <div class="pdp-write-review">
                            <h3 class="pdp-write-review-title">{{ __('Write a Review') }}</h3>
                            <form action="{{ route('website.product.review.store', $product) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="pdp-review-stars mb-3">
                                    <span class="pdp-review-stars-label">{{ __('Your rating') }}</span>
                                    <div class="star-rating">
                                        @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}">★</label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control pdp-review-textarea" rows="3" placeholder="{{ __('Share your experience...') }}" maxlength="2000">{{ old('comment') }}</textarea>
                                </div>
                                <div class="pdp-review-actions-row">
                                    <div class="pdp-review-file-block">
                                        <label class="pdp-review-photo-label">{{ __('Add photos') }} ({{ __('optional') }}, {{ __('max 3') }})</label>
                                        <div class="pdp-review-file-wrap">
                                            <input type="file" name="images[]" id="pdp-review-photos" class="pdp-review-file-input" accept="image/*" multiple>
                                            <label for="pdp-review-photos" class="pdp-review-file-btn">{{ __('Choose files') }}</label>
                                            <span class="pdp-review-file-text">{{ __('No file chosen') }}</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn pdp-review-submit-btn">{{ __('Submit Review') }}</button>
                                </div>
                            </form>
                        </div>
                        @else
                        <p class="pdp-already-reviewed">{{ __('You have already submitted a review for this product.') }}</p>
                        @endif
                    @else
                    <p class="pdp-signin-prompt"><a href="{{ route('signin') }}?redirect={{ urlencode(request()->url()) }}">{{ __('Sign in') }}</a> {{ __('to rate and review.') }}</p>
                    @endauth

                    @php
                        $allReviewImages = [];
                        if (isset($productReviews) && $productReviews->count() > 0) {
                            foreach ($productReviews as $r) {
                                if (!empty($r->images) && is_array($r->images)) {
                                    foreach ($r->images as $img) { $allReviewImages[] = $img; }
                                }
                            }
                        }
                        $totalPhotos = count($allReviewImages);
                    @endphp
                    @if($totalPhotos > 0)
                    <div class="pdp-customer-photos">
                        <h3 class="pdp-customer-photos-title">{{ __('Customer Photos') }} ({{ $totalPhotos }})</h3>
                        <div class="pdp-customer-photos-grid">
                            @foreach(array_slice($allReviewImages, 0, 4) as $img)
                            <a href="{{ asset('uploads/' . $img) }}" target="_blank" rel="noopener" class="pdp-customer-photo-item"><img src="{{ asset('uploads/' . $img) }}" alt=""></a>
                            @endforeach
                            @if($totalPhotos > 4)<div class="pdp-customer-photo-more">+{{ $totalPhotos - 4 }}</div>@endif
                        </div>
                    </div>
                    @endif

                    <h3 class="pdp-customer-reviews-title">{{ __('Customer reviews') }}</h3>
                    <div class="pdp-reviews-list">
                        @if(isset($productReviews) && $productReviews->count() > 0)
                            @foreach($productReviews as $review)
                            @php $reviewerName = $review->user ? $review->user->name : __('Customer'); @endphp
                            <div class="pdp-review-card">
                                <div class="pdp-review-card-rating">
                                    <span class="pdp-review-card-stars">{{ $review->rating }}</span>
                                    <span class="pdp-review-card-star-icon">★</span>
                                </div>
                                @if($review->comment)
                                <p class="pdp-review-card-text">{!! nl2br(e($review->comment)) !!}</p>
                                @endif
                                @if(!empty($review->images) && is_array($review->images))
                                <div class="pdp-review-card-images">
                                    @foreach($review->images as $img)
                                    <a href="{{ asset('uploads/' . $img) }}" target="_blank" rel="noopener" class="pdp-review-card-img-link"><img src="{{ asset('uploads/' . $img) }}" alt=""></a>
                                    @endforeach
                                </div>
                                @endif
                                <div class="pdp-review-card-meta">
                                    <span>{{ $reviewerName }}</span>
                                    <span>{{ $review->created_at->format('d F Y') }}</span>
                                    <div class="pdp-review-card-actions">
                                        <button type="button" class="pdp-review-like-btn">{{ __('Helpful') }}</button>
                                        <button type="button" class="pdp-review-dislike-btn">{{ __('Not helpful') }}</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <p class="pdp-no-reviews">{{ __('No reviews yet.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="related-products mt-5 pt-5 pdp-similar">
            <h2 class="section-title">{{ __('Similar Products') }}</h2>
            <div class="pdp-related-grid">
                @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('website.product', ['slug' => $relatedProduct->slug]) }}" class="pdp-rel-card">
                    <div class="pdp-rel-card__cover">
                        @if($relatedProduct->image)
                        <img src="{{ asset('uploads/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" loading="lazy">
                        @else
                        <div class="pdp-rel-card__placeholder">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="pdp-rel-card__body">
                        <p class="pdp-rel-card__title">{{ Str::limit(_local($relatedProduct->name, $relatedProduct->local_name), 50) }}</p>
                        <p class="pdp-rel-card__cat">{{ _local($relatedProduct->category->name ?? '', $relatedProduct->category->local_name ?? '') }}</p>
                    </div>
                    <div class="pdp-rel-card__footer">
                        <span class="pdp-rel-card__price">{!! currency() !!}{!! priceFormat(minimumQuantityPrice($relatedProduct->selling_price, $relatedProduct->minimum_quantity, $relatedProduct->unit->stepper), '') !!}</span>
                        <span class="pdp-rel-card__arrow">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('style')
<style>
.pdp-myntra { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #fff; color: #282c3f; }
.pdp-breadcrumb { padding: 12px 0; font-size: 12px; color: #696e79; }
.pdp-breadcrumb .breadcrumb { background: none; padding: 0; margin: 0; }
.pdp-breadcrumb .breadcrumb-item + .breadcrumb-item::before { content: " / "; }
.pdp-breadcrumb a { color: #696e79; text-decoration: none; }
.pdp-breadcrumb .active { color: #282c3f; }

.pdp-grid { display: grid; grid-template-columns: 42% 1fr; gap: 48px; max-width: 1200px; margin: 0 auto 48px; padding: 24px 0; }
.pdp-gallery { position: sticky; top: 24px; }
.pdp-main-image { position: relative; background: #f5f5f6; border-radius: 4px; overflow: hidden; margin-bottom: 12px; }
.pdp-main-image img { width: 100%; height: auto; display: block; }
.pdp-media-badge { position: absolute; bottom: 12px; right: 12px; width: 32px; height: 32px; background: rgba(0,0,0,.5); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; }
.pdp-thumbs { display: flex; gap: 8px; flex-wrap: wrap; }
.pdp-thumb { width: 56px; height: 56px; border-radius: 4px; overflow: hidden; cursor: pointer; border: 2px solid transparent; flex-shrink: 0; }
.pdp-thumb.active { border-color: #c98a25; }
.pdp-thumb img { width: 100%; height: 100%; object-fit: cover; }

.pdp-detail { max-width: 520px; }
.pdp-brand { font-size: 18px; font-weight: 700; color: #282c3f; margin: 0 0 4px; letter-spacing: 0.5px; }
.pdp-title { font-size: 20px; font-weight: 400; color: #282c3f; margin: 0 0 8px; line-height: 1.3; }
.pdp-compat { font-size: 12px; color: #20bd99; margin-bottom: 12px; }
.pdp-rating-row { display: flex; align-items: center; gap: 6px; margin-bottom: 12px; font-size: 14px; }
.pdp-rating-num { font-weight: 700; color: #282c3f; }
.pdp-rating-star { color: #ffc107; }
.pdp-rating-count { color: #696e79; }

.pdp-price-block { margin-bottom: 20px; }
.pdp-mrp { font-size: 14px; color: #696e79; text-decoration: line-through; margin: 0 0 2px; }
.pdp-tax { font-size: 12px; color: #696e79; margin: 0 0 8px; }
.pdp-best-label { font-size: 12px; font-weight: 600; color: #282c3f; margin: 0 0 4px; letter-spacing: 0.5px; }
.pdp-best-price { font-size: 18px; font-weight: 700; color: #c98a25; margin: 0; }

.pdp-size-section { margin-bottom: 20px; }
.pdp-size-heading { font-size: 12px; font-weight: 600; color: #282c3f; margin: 0 0 10px; letter-spacing: 0.5px; }
.pdp-size-btns { display: flex; flex-wrap: wrap; gap: 10px; }
.pdp-size-btn { width: 44px; height: 44px; border-radius: 50%; border: 1px solid #bfc0c6; background: #fff; font-size: 14px; font-weight: 600; color: #282c3f; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; }
.pdp-size-btn:hover { border-color: #c98a25; color: #c98a25; }
.pdp-size-btn.active { border: 2px solid #c98a25; color: #c98a25; background: #fff; }
.pdp-size-select { max-width: 200px; }

.pdp-actions { display: flex; gap: 12px; margin-bottom: 24px; }
.pdp-btn-add { flex: 1; padding: 14px 24px; background: #c98a25; color: #fff; border: none; border-radius: 4px; font-size: 14px; font-weight: 600; letter-spacing: 0.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; }
.pdp-btn-add:hover { background: #a6721e; color: #fff; }
@keyframes pdp-shake { 0%, 100% { transform: translateX(0); } 20% { transform: translateX(-6px); } 40% { transform: translateX(6px); } 60% { transform: translateX(-4px); } 80% { transform: translateX(4px); } }
.pdp-btn-add.shake { animation: pdp-shake 0.4s ease-in-out; }
.pdp-btn-notify { flex: 1; padding: 14px 24px; background: #c98a25; color: #fff; border: none; border-radius: 4px; font-size: 14px; font-weight: 600; cursor: pointer; }
.pdp-btn-wishlist { padding: 14px 20px; background: #fff; color: #282c3f; border: 1px solid #bfc0c6; border-radius: 4px; font-size: 14px; font-weight: 600; letter-spacing: 0.5px; cursor: pointer; display: flex; align-items: center; gap: 8px; }
.pdp-btn-wishlist:hover, .pdp-btn-wishlist.active { border-color: #c98a25; color: #c98a25; }
.pdp-btn-icon { font-size: 16px; }

.pdp-delivery { margin-bottom: 20px; padding: 16px 0; border-top: 1px solid #e9e9eb; border-bottom: 1px solid #e9e9eb; }
.pdp-delivery-heading { font-size: 12px; font-weight: 600; color: #282c3f; margin: 0 0 10px; letter-spacing: 0.5px; }
.pdp-delivery-row { display: flex; gap: 8px; margin-bottom: 6px; }
.pdp-pincode { width: 120px; padding: 8px 12px; border: 1px solid #bfc0c6; border-radius: 4px; font-size: 14px; }
.pdp-pincode-btn { padding: 8px 16px; background: #fff; border: 1px solid #bfc0c6; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer; }
.pdp-pincode-btn:hover { border-color: #c98a25; color: #c98a25; }
.pdp-delivery-hint { font-size: 12px; color: #696e79; margin: 0; }
.pdp-delivery-msg { font-size: 12px; margin: 4px 0 0; }
.pdp-delivery-msg.error { color: #e43834; }
.pdp-delivery-msg.success { color: #20bd99; }

.pdp-trust { list-style: none; padding: 0; margin: 0 0 24px; font-size: 12px; color: #696e79; }
.pdp-trust li { padding: 4px 0; padding-left: 18px; position: relative; }
.pdp-trust li::before { content: "✓"; position: absolute; left: 0; color: #20bd99; font-weight: 700; }

.pdp-description { }
.pdp-desc-title { font-size: 16px; font-weight: 600; color: #282c3f; margin: 0 0 10px; }
.pdp-desc-text { font-size: 14px; line-height: 1.6; color: #535766; }

.pdp-specs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px 24px; }
.pdp-spec-item { display: flex; flex-direction: column; padding: 8px 0; border-bottom: 1px solid #f5f5f6; }
.pdp-spec-label { font-size: 12px; color: #9496a5; font-weight: 500; text-transform: uppercase; margin-bottom: 2px; }
.pdp-spec-value { font-size: 14px; color: #282c3f; font-weight: 600; }
.pdp-compat-notes { font-size: 13px; background-color: #fff9e6 !important; border-left-color: #ffc107 !important; }
@media (max-width: 576px) {
    .pdp-specs-grid { grid-template-columns: 1fr; }
}

/* Review section - Myntra-style layout */
.pdp-reviews-layout { display: grid; grid-template-columns: 320px 1fr; gap: 32px; align-items: start; }
.pdp-reviews-main-title { font-size: 14px; font-weight: 700; color: #282c3f; margin: 0 0 16px; letter-spacing: 0.5px; }
.pdp-ratings-block { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
.pdp-ratings-score { display: flex; align-items: center; gap: 4px; margin-bottom: 4px; }
.pdp-ratings-num { font-size: 28px; font-weight: 700; color: #282c3f; }
.pdp-ratings-star-icon { font-size: 24px; color: #20bd99; }
.pdp-ratings-verified { font-size: 12px; color: #696e79; margin: 0 0 16px; }
.pdp-ratings-bars { display: flex; flex-direction: column; gap: 8px; }
.pdp-rating-bar-row { display: grid; grid-template-columns: 50px 1fr 28px; align-items: center; gap: 10px; font-size: 12px; color: #535766; }
.pdp-rating-bar-wrap { height: 8px; background: #e9e9eb; border-radius: 4px; overflow: hidden; }
.pdp-rating-bar-fill { height: 100%; background: #20bd99; border-radius: 4px; min-width: 4px; transition: width 0.3s; }
.pdp-rating-bar-count { text-align: right; font-weight: 600; }
.pdp-write-review { border: 1px solid #e9e9eb; border-radius: 8px; padding: 20px; margin-bottom: 16px; }
.pdp-write-review-title { font-size: 14px; font-weight: 700; color: #282c3f; margin: 0 0 16px; }
.pdp-review-stars-label { font-size: 12px; color: #535766; margin-right: 8px; }
.pdp-review-stars { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
.pdp-review-textarea { font-size: 14px; resize: vertical; }
.pdp-review-actions-row { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-top: 16px; }
.pdp-review-file-block { flex: 1; min-width: 0; }
.pdp-review-photo-label { font-size: 12px; color: #535766; display: block; margin-bottom: 6px; }
.pdp-review-file-wrap { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; }
.pdp-review-file-input { position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1; }
.pdp-review-file-btn {
    display: inline-block;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #282c3f;
    background: #fff;
    border: 1px solid #bfc0c6;
    border-radius: 6px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.pdp-review-file-btn:hover { border-color: #9a9ca1; background: #f5f5f6; }
.pdp-review-file-text { font-size: 13px; color: #696e79; }
.pdp-review-submit-btn { margin-left: auto; flex-shrink: 0; background: #c98a25; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; font-size: 14px; }
.pdp-review-submit-btn:hover { background: #a6721e; color: #fff; }
.pdp-already-reviewed, .pdp-signin-prompt { font-size: 14px; color: #696e79; margin: 0; }
.pdp-signin-prompt a { color: #c98a25; font-weight: 600; }

.pdp-customer-photos { margin-bottom: 24px; }
.pdp-customer-photos-title { font-size: 14px; font-weight: 700; color: #282c3f; margin: 0 0 12px; }
.pdp-customer-photos-grid { display: flex; flex-wrap: wrap; gap: 10px; }
.pdp-customer-photo-item { width: 80px; height: 80px; border-radius: 6px; overflow: hidden; border: 1px solid #e9e9eb; display: block; }
.pdp-customer-photo-item img { width: 100%; height: 100%; object-fit: cover; }
.pdp-customer-photo-item:hover { border-color: #c98a25; }
.pdp-customer-photo-more { width: 80px; height: 80px; border-radius: 6px; background: #282c3f; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; }

.pdp-customer-reviews-title { font-size: 14px; font-weight: 700; color: #282c3f; margin: 0 0 16px; }
.pdp-reviews-list { display: flex; flex-direction: column; gap: 20px; }
.pdp-review-card { padding-bottom: 20px; border-bottom: 1px solid #e9e9eb; }
.pdp-review-card:last-child { border-bottom: none; }
.pdp-review-card-rating { display: flex; align-items: center; gap: 4px; margin-bottom: 8px; }
.pdp-review-card-stars { font-size: 16px; font-weight: 700; color: #282c3f; }
.pdp-review-card-star-icon { font-size: 16px; color: #20bd99; }
.pdp-review-card-text { font-size: 14px; line-height: 1.5; color: #535766; margin: 0 0 10px; }
.pdp-review-card-images { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.pdp-review-card-img-link { width: 56px; height: 56px; border-radius: 4px; overflow: hidden; border: 1px solid #e9e9eb; display: block; }
.pdp-review-card-img-link img { width: 100%; height: 100%; object-fit: cover; }
.pdp-review-card-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; font-size: 12px; color: #696e79; }
.pdp-review-card-actions { display: flex; gap: 16px; margin-left: auto; }
.pdp-review-like-btn, .pdp-review-dislike-btn { background: none; border: none; cursor: pointer; font-size: 12px; color: #696e79; padding: 0; }
.pdp-review-like-btn:hover, .pdp-review-dislike-btn:hover { color: #c98a25; }
.pdp-no-reviews { font-size: 14px; color: #696e79; margin: 0; }

.star-rating { direction: rtl; display: inline-flex; }
.star-rating input { display: none; }
.star-rating label { font-size: 22px; color: #ddd; cursor: pointer; }
.star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label { color: #ffc107; }

.pdp-similar { border-top: 1px solid #e9e9eb; }
.pdp-similar .section-title { font-size: 18px; font-weight: 600; color: #282c3f; margin-bottom: 20px; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
.product-card { border: 1px solid #e9e9eb; border-radius: 4px; overflow: hidden; transition: box-shadow 0.2s; }
.product-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,.08); }
.product-card img { width: 100%; height: 220px; object-fit: cover; }
.product-card a { text-decoration: none; color: inherit; display: block; padding: 12px; }
.product-name { font-size: 14px; font-weight: 500; color: #282c3f; margin: 8px 0 4px; line-height: 1.3; height: 2.6em; overflow: hidden; }
.product-price { font-size: 16px; font-weight: 700; color: #c98a25; }

@media (max-width: 768px) {
    .pdp-reviews-layout { grid-template-columns: 1fr; }
    .pdp-grid { grid-template-columns: 1fr; gap: 24px; padding: 16px 0; }
    .pdp-gallery { position: static; }
    .pdp-detail { max-width: none; }
    .pdp-actions { flex-direction: column; }
    .pdp-btn-add, .pdp-btn-wishlist { width: 100%; }
    .products-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}
</style>
@endsection

@section('script')
<script>
document.querySelectorAll('.star-rating').forEach(function(rating) {
    rating.addEventListener('click', function(e) {
        if (e.target.tagName === 'LABEL') {
            var input = e.target.previousElementSibling;
            if (input) input.checked = true;
        }
    });
});

var pdpReviewPhotos = document.getElementById('pdp-review-photos');
var pdpReviewFileText = document.querySelector('.pdp-review-file-text');
if (pdpReviewPhotos && pdpReviewFileText) {
    pdpReviewPhotos.addEventListener('change', function() {
        var files = this.files;
        if (files && files.length > 0) {
            pdpReviewFileText.textContent = files.length === 1 ? files[0].name : files.length + ' {{ __("files chosen") }}';
        } else {
            pdpReviewFileText.textContent = '{{ __("No file chosen") }}';
        }
    });
}

// Add to Bag: update cart cookie and header count (same structure as listing-page cart)
document.querySelectorAll('.pdp-detail .btn-add-to-cart').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (typeof window.cookies === 'undefined' || !window.cookies.set) return;
        if (4096 <= new Blob([document.cookie]).size) {
            btn.classList.add('shake');
            setTimeout(function() { btn.classList.remove('shake'); }, 1000);
            return;
        }
        var id = btn.getAttribute('data-product-id');
        var price = btn.getAttribute('data-price');
        var sellingPrice = btn.getAttribute('data-selling-price');
        var steper = parseFloat(btn.getAttribute('data-steper')) || 1;
        var minQty = parseFloat(btn.getAttribute('data-min')) || 1;
        var quantity = minQty;

        var cart = { products: {} };
        try {
            var cartJSON = document.cookie.split('; ').find(function(row) { return row.startsWith('__cart='); });
            if (cartJSON) {
                cartJSON = decodeURIComponent(cartJSON.split('=').slice(1).join('='));
                var parsed = JSON.parse(cartJSON);
                if (parsed && parsed.products) cart = parsed;
            }
        } catch (e) {}

        cart.products[id] = {
            price: price,
            selling_price: sellingPrice,
            quantity: quantity,
            steper: steper,
            total_price: parseFloat(price) * (quantity / steper),
            total_selling_price: parseFloat(sellingPrice) * (quantity / steper),
            message: ''
        };

        var total = 0;
        Object.keys(cart.products).forEach(function(pid) { total += cart.products[pid].total_selling_price; });
        var decimalPlace = 2;
        window.cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });

        var countEl = document.querySelector('.cart-item-count') || document.querySelector('.cart-count');
        if (countEl) countEl.textContent = Object.keys(cart.products).length;
        var totalEl = document.querySelector('.cart-total-amount');
        if (totalEl) totalEl.textContent = total.toFixed(decimalPlace);

        var cartUrl = document.querySelector('meta[name="cart-url"]');
        if (cartUrl && cartUrl.getAttribute('content')) {
            window.location.href = cartUrl.getAttribute('content');
        } else {
            window.location.href = '{{ route("website.cart") }}';
        }
    });
});

document.querySelectorAll('.pdp-thumb').forEach(function(thumb) {
    thumb.addEventListener('click', function() {
        document.querySelectorAll('.pdp-thumb').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        var mainImg = document.getElementById('zoom-image');
        var img = this.querySelector('img');
        if (mainImg && img) {
            var src = img.src.replace('/base/', '/large/');
            mainImg.src = src;
        }
    });
});

document.querySelectorAll('.pdp-size-select').forEach(function(select) {
    select.addEventListener('change', function() {
        var base = this.getAttribute('data-product-base-url') || '{{ url("/product") }}';
        window.location.href = base + '/' + this.value;
    });
});

var pincodeBtn = document.querySelector('.pdp-pincode-btn');
var pincodeInput = document.getElementById('pdp-pincode');
var deliveryMsg = document.getElementById('pdp-delivery-msg');
if (pincodeBtn && pincodeInput && deliveryMsg) {
    pincodeBtn.addEventListener('click', function() {
        var pin = (pincodeInput.value || '').replace(/\D/g, '').slice(0, 6);
        pincodeInput.value = pin;
        deliveryMsg.className = 'pdp-delivery-msg';
        if (pin.length !== 6) {
            deliveryMsg.textContent = '{{ __("Please enter a valid 6-digit PIN code") }}';
            deliveryMsg.classList.add('error');
        } else {
            deliveryMsg.textContent = '{{ __("Delivery available to this location") }}';
            deliveryMsg.classList.add('success');
        }
    });
}
</script>
@endsection
