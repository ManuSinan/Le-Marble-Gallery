@php
    /** @var \Illuminate\Support\Collection|\App\Models\Category[] $homeCategories */
@endphp

@if(isset($homeCategories) && $homeCategories->count() > 0)
<section class="home-categories-strip" aria-label="{{ __('Browse categories') }}">
    <div class="home-categories-strip__container">
        <div class="home-categories-strip__track" role="list">
            @foreach([1, 2] as $copy)
                @foreach($homeCategories as $category)
                    @php
                        $imgSrc = $category->image
                            ? asset('uploads/' . ltrim($category->image, '/'))
                            : asset('assets/frontend/images/200x150-blank.png');
                    @endphp
                    <a href="{{ route('website.products', ['slug' => $category->slug]) }}"
                       class="home-categories-strip__item"
                       role="listitem"
                       aria-label="{{ $category->name }}">
                        <span class="home-categories-strip__circle" aria-hidden="true">
                            <img src="{{ $imgSrc }}" alt="" loading="lazy" decoding="async">
                        </span>
                        <span class="home-categories-strip__name">{{ $category->name }}</span>
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
@endif

