@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }} {{ __('Shopping Tips') }}</title>
<meta name="description" content="{!! getOption('safety_tips_title') !!}" />
<meta name="keywords" content="Shopping Tips" />
<meta name="robots" content="index, follow">

<meta property="og:type" content="website" />
<meta property="og:title" content="{{ config('app.name', '') }} {{ __('Shopping Tips') }}" />
<meta property="og:image" content="{{ asset('assets/frontend/images/logo.png') }}" />
<meta property="og:description" content="{!! getOption('safety_tips_title') !!}" />
<meta property="og:url" content="{{ request()->url() }}" />
 
@endsection
@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ getOption('safety_tips_title') }}</h1>
        <nav> 
            <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{ route('home') }}">
                        <span itemprop="name">{{ __('Home') }}</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li  class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{ request()->url() }}">
                        <span class="text-dark" itemprop="name">{{ config('app.name', '') }} {{ __('Shopping Tips') }}</span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
            </ol>
        </nav>

   </div>
</section>

<section class="section-conten padding-y mb-3"> 
    <div class="container">
    {!! getOption('safety_tips_content') !!}
    </div> 
</section>
@endsection
