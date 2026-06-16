<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 
    <url>
        <loc>{{ route('home') }}</loc>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('website.products.shop') }}</loc>
        <priority>1.0</priority>
    </url>
    @foreach(\App\Models\Category::all() as $category)
        <url>
            <loc>{{ route('website.products', ['slug' => $category->slug]) }}</loc>
            <lastmod>{{ Carbon\Carbon::parse($category->updated_at)->format('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            @if($category->priority == 3)
            <priority>1.0</priority>
            @elseif ($category->priority == 2)
            <priority>0.9</priority>
            @else
            <priority>0.8</priority>
            @endif
        </url>

    @endforeach
    @foreach ($prodata as $product)
    
        <url>
            <loc>{{ $product['loc'] }}</loc>
            <lastmod>{{ $product['lastmod'] }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    <url>
        <loc>{{ route('website.cart') }}</loc>
        <priority>0.4</priority>
    </url>
    <url>
        <loc>{{ route('signup') }}</loc>
        <priority>0.3</priority>
    </url>    <url>
        <loc>{{ route('signin') }}</loc>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('website.about.us') }}</loc>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('website.tc') }}</loc>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('website.privacy.policy') }}</loc>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('website.safety.tips') }}</loc>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('website.import.updates') }}</loc>
        <priority>0.3</priority>
    </url>
</urlset>