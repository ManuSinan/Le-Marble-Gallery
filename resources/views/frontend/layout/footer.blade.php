

<!-- ========================= FOOTER ========================= -->
@php
  $footerRoute = request()->route()?->getName();
  $isHome = ($footerRoute === 'home' || request()->is('/') || request()->is('index'));
  $isLuxury = $isHome || !in_array($footerRoute ?? '', ['signin', 'signup']);
@endphp
<footer class="section-footer border-top mt-auto {{ $isLuxury ? 'footer-luxury' : '' }}" style="{{ !$isLuxury ? 'background: #fff; border-color: #e8e4df !important;' : '' }}">
	<div class="container">
		<section class="footer-top pt-5">
			<div class="row text-center justify-content-center">
			
				<aside class="col-5 col-md-2 mb-4 order-2 order-md-2">
					<h6 class="title">{{ __('Pages') }}</h6>
					<ul class="list-unstyled">
						<li> <a href="{{ route('home')  }}">{{ __('Home') }}</a></li>
						<li> <a href="{{route('website.about.us')}}">{{ __('About') }}</a></li>	
						<li> <a href="{{ route('website.products.shop') }}">{{ __('Browse Books') }}</a></li>
						<li> <a href="{{route('sitemap')}}" target="_blank">{{ __('Sitemap') }}</a></li>	
					</ul>
				</aside>
				<aside class="col-7 col-md-2 mb-4 order-3 order-md-3">
					<h6 class="title">{{ __('Manage') }}</h6>
					<ul class="list-unstyled">
						<li> <a href="{{route('website.order')}}">{{ __('Your Orders') }}</a></li>
						<li> <a href="{{ route('website.favourite')}}">{{ __('Saved Books') }}</a></li>
						<li> <a href="{{route('website.address')}}">{{ __('Your Addresses') }}</a></li>	
						<li> <a href="{{route('website.edit.profile')}}">{{ __('Manage Profile') }}</a></li>
					</ul>
				</aside>
				
				<aside class="col-5 col-md-2 mb-4 order-4 order-md-4">
					<h6 class="title">{{ __('Policy Information') }}</h6>
					<ul class="list-unstyled">
						<li> <a href="{{ route('website.tc') }}">{{ __('Terms and Conditions') }}</a></li>
						<li> <a href="{{ route('website.privacy.policy') }}">{{ __('Privacy Policy') }}</a></li>
						<li> <a href="{{ route('website.safety.tips') }}">{{ __('Shopping Tips') }}</a></li>
						<li> <a href="{{ route('website.import.updates') }}">{{ __('Store Updates') }}</a></li>
					</ul>
				</aside>

				<aside class="col-12 col-md-3 order-1 order-md-5">
					<h6 class="title">{{ __('Social Media') }}</h6>
					<ul class="list-unstyled">
						<li> <a href="{{ getOption('facebook_page') }}" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" x-bind:width="size" x-bind:height="size" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-bind:stroke-width="stroke" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
								<path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
							  </svg>
							{{ __('Facebook Page') }}
						</a>
						</li>
						<li> <a href="{{ getOption('twitter_handle') }}" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" x-bind:width="size" x-bind:height="size" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-bind:stroke-width="stroke" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
								<path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
								<path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
							</svg>
							{{ __('Twitter Handle') }}
						</a>
						</li>
						<li> <a href="{{ getOption('youtube_channel') }}" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" x-bind:width="size" x-bind:height="size" viewBox="0 0 24 24" fill="none" width="24" height="24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" x-bind:stroke-width="stroke" stroke="currentColor">
								<path d="M2 8a4 4 0 0 1 4 -4h12a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-12a4 4 0 0 1 -4 -4v-8z"></path>
								<path d="M10 9l5 3l-5 3z"></path>
							</svg>
							{{ __('YouTube Channel') }}
						</a>
					</li>
					</ul>
				</aside>	

			</div> <!-- row.// -->
		</section>	<!-- footer-top.// -->

		<section class="footer-copyright border-top" style="{{ $isLuxury ? 'border-color: rgba(242,232,216,0.08) !important; ' : 'border-color: #e8e4df !important; ' }}padding: 20px 0;">
				<p class="text-muted text-center mb-0" style="font-size: 13px; {{ $isLuxury ? 'color: #c4b8a8 !important;' : 'color: #6b6b6b;' }}">© {{ date('Y') }} {{ config('app.name') }}. {{ __('Books for every reader.') }}</p>	
		</section>
	</div><!-- //container -->
</footer>
<!-- ========================= FOOTER END // ========================= -->
