@extends('frontend/layout/main')
@section('seo')
<title>{{ config('app.name', '') }} {{ __('Store Updates') }}</title>
<meta name="description" content="{!! getOption('import_updates_title') !!}" />
<meta name="keywords" content="Store Updates, Terms" />
<meta name="robots" content="index, follow">

<meta property="og:type" content="website" />
<meta property="og:title" content="{{ config('app.name', '') }} {{ __('Store Updates') }}" />
<meta property="og:image" content="{{ asset('assets/frontend/images/logo.png') }}" />
<meta property="og:description" content="{!! getOption('import_updates_title') !!}" />
<meta property="og:url" content="{{ request()->url() }}" />
 
@endsection
@section('body')
<section class="section-page-info mt-4 mb-1">
   <div class="container">
        <h1>{{ getOption('import_updates_title') }}</h1>
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
                        <span class="text-dark" itemprop="name">{{ config('app.name', '') }} {{ __('Store Updates') }}</span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
            </ol>
        </nav>

   </div>
</section>

<section class="section-conten padding-y mb-3"> 
    <div class="container">
    {!! getOption('import_updates_content') !!}
    </div> 
</section>
@endsection
