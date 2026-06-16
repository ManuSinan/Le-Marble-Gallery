@extends('frontend/layout/main')
@section('seo')
<title>{{ __('Your Favourites') }}</title>
<meta name="robots" content="noindex, nofollow">
@endsection

@section('body')
<section class="section-page-info  mt-4 mb-1">
   <div class="container">
        <h1>{{ __('Your Favourites') }}</h1>
        <nav> 
		<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
			<li class="breadcrumb-item"><a href="{{ route('website.account') }}">{{ __('Account') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ __('Your Favourites') }}</li>
         </ol>  
        </nav> <!-- col.// -->
   </div>
</section>

<section class="section-content my-4">
	<div class="container">

		<div class="row">
			<aside class="col-md-3  order-2 order-md-1 mb-4">

				@include('frontend/account/menu')

			</aside>
			<main class="col-md-9  order-1 order-md-2 mb-4">

 				

				 	@if($products && $products->count() > 0)

					<div class="row products favourite-grid">
						@foreach($products as $product)
						<div class="col-6 col-lg-4">
							<div class="card favourite-card mb-4">
								<figure class="itemside p-3">
									<a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="aside favourite-card-img">
										@if( $product->image )
											<img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="image"/>
										@else
											<img src="{{ asset('assets/frontend/images/200x150-blank.png') }}" alt="{{ $product->name }}" class="image"/>
										@endif
									</a>
									<figcaption class="info favourite-card-info">
										<a href="{{ route('website.product', ['slug' => $product->slug]) }}" class="title favourite-card-title">
											{{ Str::limit(_local($product->name, $product->local_name), 45) }}
										</a>
										<div class="price favourite-card-price">
											<div class="favourite-selling-price">{!! priceFormat( minimumQuantityPrice($product->selling_price, $product->minimum_quantity, $product->units_stepper)  ) !!}</div>
											@if($product->price > $product->selling_price)
												<div class="favourite-mrp"><del>{!! priceFormat( minimumQuantityPrice($product->price, $product->minimum_quantity, $product->units_stepper) ) !!}</del></div>
											@endif
										</div>
										<a href="javascript:void(0)" act-on="click" act-request="{{ route('website.favourite.remove', ['product' => $product->id ]) }}" class="favourite-remove-btn" title="{{ __('Remove') }}">
											<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
											{{ __('Remove') }}
										</a>
									</figcaption>
								</figure>
							</div>
						</div>
						@endforeach
					</div>

 
					@else
					<div class="card favourite-empty-card">
						<div class="empty-content">
							<div class="title">{{ __('You haven\'t added any products yet!') }}</div>
							<p class="text mb-4">{{ __('Browse our collection and click the heart icon on products you love to save them here.') }}</p>
							<a href="{{ route('website.products.shop') }}" class="btn btn-primary">
								{{ __('Browse Shop') }}
							</a>
						</div>
					</div>
					@endif
 
 
			</main>
		</div> <!-- row.// -->

	</div> <!-- container .//  -->
</section>

@endsection
