<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
 
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="app-url" content="{{ config('app.url') }}">
    <meta name="cart-url" content="{{ route('website.cart') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">
 
    <meta name="theme-color" content="#1a140d">
    <meta name="mobile-web-app-capable" content="yes" />
    
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', '') }}" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    
    <link rel="icon" type="image/png" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/frontend/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
 
    <meta property="og:site_name" content="{{ request()->getHost() }}"/>
    @yield('seo')
    @php $routeName = request()->route()->getName(); @endphp
    <style>
      body {
        background-image: url("{{ asset('images/knm-page-bg.png') }}");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center top;
        background-attachment: fixed;
      }
    </style>
    <link href="{{ asset('assets/frontend/css/style.css?version=' . config('app.asset_version')) }}" rel="stylesheet" type="text/css"/>
    @yield('style')
  </head>
 
@php
  $routeName = request()->route()->getName();
  $isHome = ($routeName === 'home' || request()->is('/') || request()->is('index'));
  $isAuthPage = in_array($routeName ?? '', ['signin', 'signup']);
  $isLuxuryTheme = true;
@endphp
<body class="d-flex flex-column min-vh-100 nnitec-body {{ $isLuxuryTheme ? 'luxury-theme' : '' }} {{ $isHome ? 'page-home' : '' }}">
  @if(!$isHome)
  <div class="nnitec-page luxury-inner-page">
    <div class="nnitec-container">
      @include('frontend/layout/header-luxury', ['innerPage' => true])
      <div class="nnitec-inner">
        @yield('body')
      </div>
    </div>
  </div>
  @else
  @yield('body')
  @endif
  @if($routeName !== 'signin' && $routeName !== 'signup' && !$isHome)
  @include('frontend/layout/ads-banner')
  @endif
  @include('frontend/layout/footer')
  @if (Auth::guest())
  @include('frontend/layout/login-modal')
  @include('frontend/layout/signup-modal')
  @include('frontend/layout/otp-verification-modal')
  @endif
  <section class="toast-alert">
    <div id="toast" class="toast" role="alert" aria-live="polite" aria-atomic="true" data-delay="1000" style="background: rgba(255,255,255,0.98); border: 1px solid #e5e7eb; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border-radius: 12px;">
      <div class="toast-body d-flex align-items-center p-3">
        <span class="success mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2l4 -4"/></svg></span>
        <span class="warning error mr-3 d-none"><svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
        <span class="text" style="font-weight: 600;"></span>
      </div>
    </div>
  </section>
	<script src="{{ asset('assets/frontend/js/script.js?version=' . config('app.asset_version')) }}" type="text/javascript"></script>
	@if(!in_array($routeName ?? '', ['signin', 'signup']))
	<script>
	(function(){
	  var nav = document.getElementById('luxuryNav');
	  var menu = document.getElementById('luxuryMenu');
	  var toggle = document.getElementById('luxuryMenuToggle');
	  if (nav) {
	    function updateNav() { nav.classList.toggle('scrolled', window.scrollY > 50); }
	    updateNav();
	    window.addEventListener('scroll', updateNav);
	  }
	  if (toggle && menu) {
	    toggle.addEventListener('click', function() {
	      menu.classList.toggle('open');
	      toggle.classList.toggle('active');
	      document.body.classList.toggle('luxury-menu-open', menu.classList.contains('open'));
	    });
	    menu && menu.querySelectorAll('a').forEach(function(a) {
	      a.addEventListener('click', function() {
	        menu.classList.remove('open');
	        toggle.classList.remove('active');
	        document.body.classList.remove('luxury-menu-open');
	      });
	    });
	  }
	})();
	</script>
	@endif
	@if (Auth::guest() && (session('openAuthModal') || session('error')))
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		if (typeof $ !== 'undefined' && $('#loginModal').length) {
			$('#loginModal').modal('show');
			@if(session('error'))
			alert('{{ addslashes(session('error')) }}');
			@endif
		}
	});
	</script>
	@endif
	@yield('script')
</body>
</html>