@php
    $posters = \App\Models\Poster::orderBy('priority', 'desc')->take(8)->get();
@endphp
@if($posters->count() > 0)
<section class="ads-banner-section" aria-label="Latest updates &amp; offers">
    <div class="ads-banner-wrap">
        <div class="ads-banner-track">
            @foreach($posters as $poster)
            <span class="ads-banner-item">
                <img src="{{ asset('uploads/' . str_replace('/base/', '/large/', $poster->image)) }}" alt="{{ $poster->name }}"/>
            </span>
            @endforeach
            {{-- Duplicate for seamless loop --}}
            @foreach($posters as $poster)
            <span class="ads-banner-item">
                <img src="{{ asset('uploads/' . str_replace('/base/', '/large/', $poster->image)) }}" alt="{{ $poster->name }}"/>
            </span>
            @endforeach
        </div>
    </div>
</section>
<style>
.ads-banner-section {
    background: rgb(255, 255, 255);
    padding: 12px 0;
    overflow: hidden;
}
@media (min-width: 768px) {
    .ads-banner-section {
        padding: 16px 0;
    }
}
.ads-banner-wrap {
    overflow: hidden;
    width: 100%;
}
.ads-banner-track {
    display: flex;
    gap: 16px;
    width: max-content;
    animation: ads-banner-scroll 40s linear infinite;
}
@media (min-width: 576px) {
    .ads-banner-track {
        gap: 20px;
    }
}
@media (min-width: 768px) {
    .ads-banner-track {
        gap: 28px;
    }
}
.ads-banner-track:hover {
    animation-play-state: paused;
}
/* Poster card: strict 3:2 ratio */
.ads-banner-item {
    flex-shrink: 0;
    display: block;
    width: 160px;
    aspect-ratio: 3 / 2;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
@media (min-width: 576px) {
    .ads-banner-item {
        width: 200px;
        border-radius: 10px;
    }
}
@media (min-width: 768px) {
    .ads-banner-item {
        width: 280px;
    }
}
@media (min-width: 992px) {
    .ads-banner-item {
        width: 400px;
    }
}
.ads-banner-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
@keyframes ads-banner-scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>
@endif
