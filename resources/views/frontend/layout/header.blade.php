<header class="site-header site-header-canvas">
    <div class="header-inner">
        <div class="header-left">
            <button type="button" class="menu-toggle" data-toggle="modal" data-target="#menu-modal" aria-label="{{ __('Menu') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="6" x2="20" y2="6" /><line x1="4" y1="12" x2="20" y2="12" /><line x1="4" y1="18" x2="20" y2="18" /></svg>
            </button>
            <nav class="header-menu d-none d-md-flex">
                <a class="header-nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                <a class="header-nav-link" href="{{ route('website.products.shop') }}">{{ __('Shop') }}</a>
            </nav>
        </div>

        <a href="{{ route('home') }}" class="header-logo-center logo" aria-label="{{ config('app.name') }}">
            <img src="{{ asset('assets/frontend/images/logo.png') }}" alt="{{ config('app.name') }}" class="site-logo" />
        </a>

        <div class="header-right">
            <button type="button" class="header-icon search-icon-btn" aria-label="{{ __('Search') }}" data-toggle="search-overlay">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
            </button>
            <a href="{{ route('website.favourite') }}" class="header-icon favourite-icon" aria-label="{{ __('Favourites') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 20l-1.45-1.32C6 15 3 12.36 3 9.28 3 7 4.79 5 7.1 5c1.3 0 2.6.63 3.4 1.64C11.3 5.63 12.6 5 13.9 5 16.21 5 18 7 18 9.28c0 3.08-3 5.72-7.55 9.4L12 20z" />
                </svg>
            </a>
            @if (Auth::guest())
                <a href="#" class="header-icon user-icon" data-toggle="modal" data-target="#loginModal" aria-label="{{ __('Sign in') }}" style="cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                </a>
            @else
                <div class="header-user-dropdown" style="position: relative;">
                    <a href="{{ route('website.account') }}" class="header-icon user-icon" aria-label="{{ __('Account') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    </a>
                    <div class="header-user-menu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 180px; z-index: 1000; padding: 8px 0;">
                        <a href="{{ route('website.account') }}" class="header-user-menu-item" style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" style="vertical-align: middle; margin-right: 8px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            {{ __('My Account') }}
                        </a>
                        <a href="{{ route('signout') }}" class="header-user-menu-item" style="display: block; padding: 10px 16px; color: #d32f2f; text-decoration: none; font-size: 14px; border-top: 1px solid #e5e7eb; margin-top: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" style="vertical-align: middle; margin-right: 8px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                            {{ __('Sign Out') }}
                        </a>
                    </div>
                </div>
            @endif
            <a href="{{ route('website.cart') }}" class="header-icon cart-icon" aria-label="{{ __('Cart') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 18C5.895 18 5.01 18.895 5.01 20C5.01 21.105 5.895 22 7 22C8.105 22 9 21.105 9 20C9 18.895 8.105 18 7 18ZM1 2V4H3L6.595 11.585L5.245 14.035C5.09 14.325 5 14.65 5 15C5 16.105 5.895 17 7 17H19V15H7.425C7.285 15 7.175 14.89 7.175 14.75L8.1 13H15.55C16.3 13 16.955 12.585 17.3 11.97L20.875 5.48C20.955 5.34 21 5.175 21 5C21 4.445 20.55 4 20 4H5.215L4.265 2H1ZM17 18C15.895 18 15.01 18.895 15.01 20C15.01 21.105 15.895 22 17 22C18.105 22 19 21.105 19 20C19 18.895 18.105 18 17 18Z"/>
                </svg>
                <span class="cart-count">{{ cartItemCount(null) }}</span>
            </a>
        </div>
    </div>
</header>

<!-- Search overlay (Canvas & Co style) -->
<div class="header-search-overlay" id="header-search-overlay" style="display: none;">
    <div class="header-search-overlay-inner">
        <form class="header-search-overlay-form" action="{{ route('website.products', ['slug' => request()->slug ?? '']) }}">
            <input type="search" name="search" placeholder="{{ __('Search for Products') }}" class="header-search-overlay-input" autofocus />
            <button type="submit" class="header-search-overlay-btn" aria-label="{{ __('Search') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
            </button>
        </form>
        <button type="button" class="header-search-overlay-close" data-dismiss="search-overlay" aria-label="{{ __('Close') }}">&times;</button>
    </div>
</div>

<style>
/* The Canvas Co. – painting gallery navbar */
.site-header-canvas {
	background: #fff !important;
	border-bottom: 1px solid #e8e4df !important;
	box-shadow: 0 1px 0 rgba(44,44,44,0.04);
}
.site-header-canvas .header-inner {
	padding: 14px 28px !important;
	gap: 24px !important;
}
.site-header-canvas .header-left {
	gap: 24px !important;
	flex: 1;
	justify-content: flex-start;
}
.site-header-canvas .header-right {
	flex: 1;
	justify-content: flex-end;
}
.site-header-canvas .menu-toggle,
.site-header-canvas .header-nav-link,
.site-header-canvas .header-icon {
	color: #2c2c2c !important;
}
.site-header-canvas .menu-toggle:hover,
.site-header-canvas .menu-toggle:focus,
.site-header-canvas .header-nav-link:hover,
.site-header-canvas .header-icon:hover,
.site-header-canvas .header-icon:focus {
	color: #b85c38 !important;
}
.site-header-canvas .header-nav-link {
	font-family: 'Source Sans 3', sans-serif;
	font-size: 14px;
	font-weight: 500;
	letter-spacing: 0.04em;
	text-transform: uppercase;
}
.site-header-canvas .header-inner {
	position: relative;
}
.site-header-canvas .header-logo-center {
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
}
.site-header-canvas .site-logo {
	height: 48px !important;
	max-width: 160px !important;
}
@media (min-width: 992px) {
	.site-header-canvas .site-logo {
		height: 56px !important;
		max-width: 200px !important;
	}
}
@media (max-width: 767px) {
	.site-header-canvas .site-logo {
		height: 40px !important;
		max-width: 140px !important;
	}
}
.site-header-canvas .header-right {
	gap: 28px !important;
}
.site-header-canvas .search-icon-btn {
	background: none;
	border: none;
	padding: 0;
	cursor: pointer;
}
.site-header-canvas .cart-count {
	background: #b85c38 !important;
}
/* Search overlay */
.header-search-overlay {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(255,255,255,0.98);
	z-index: 9999;
	display: flex;
	align-items: flex-start;
	justify-content: center;
	padding-top: 100px;
}
.header-search-overlay-inner {
	width: 100%;
	max-width: 600px;
	padding: 0 24px;
	position: relative;
}
.header-search-overlay-form {
	display: flex;
	border-bottom: 2px solid #1a1a1a;
	padding-bottom: 8px;
}
.header-search-overlay-input {
	flex: 1;
	border: none;
	background: transparent;
	font-size: 24px;
	color: #1a1a1a;
	outline: none;
}
.header-search-overlay-input::placeholder {
	color: #999;
}
.header-search-overlay-btn {
	background: none;
	border: none;
	color: #1a1a1a;
	cursor: pointer;
	padding: 0 8px;
}
.header-search-overlay-close {
	position: absolute;
	top: -60px;
	right: 24px;
	background: none;
	border: none;
	font-size: 32px;
	color: #1a1a1a;
	cursor: pointer;
	line-height: 1;
}
/* Mobile: Canvas & Co keeps same style */
@media (max-width: 991px) {
	.site-header-canvas .header-logo-center {
		position: static;
		transform: none;
		order: 0;
	}
	.site-header-canvas .header-inner {
		flex-wrap: wrap;
		justify-content: center;
	}
	.site-header-canvas .header-left {
		order: -1;
		position: absolute;
		left: 16px;
	}
	.site-header-canvas .header-right {
		order: -1;
		position: absolute;
		right: 16px;
	}
}
</style>

<!-- Mobile Menu Modal / Categories Sidebar -->
<div class="modal menu-modal fixed-right fade" id="menu-modal" tabindex="-1" aria-labelledby="menu-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-aside">
        <div class="modal-content menu-modal-content">
            <div class="modal-header menu-modal-header">
                <h5 class="modal-title" id="menu-modal-label">{{ __('All Categories') }}</h5>
                <button type="button" class="close menu-modal-close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modal-body menu-modal-body">
                <ul class="list-group list-group-flush menu-categories-list">
                    @php
                    function renderCategoryList($categories, $level = 0) {
                        foreach ($categories as $category) {
                            $isParent = $level === 0;
                            $activeClass = request()->slug == $category->slug ? 'active' : '';
                            $hasChildren = $category->children->isNotEmpty();
                            $itemClass = 'menu-cat-item ' . ($isParent ? 'menu-cat-parent' : 'menu-cat-child');

                            echo '<li class="list-group-item ' . $itemClass . '">';
                            echo '<a href="' . route('website.products', ['slug' => $category->slug]) . '" class="menu-cat-link ' . $activeClass . '">';
                            echo '<span class="menu-cat-name">' . e(Str::limit(_local($category->name, $category->local_name), 32)) . '</span>';
                            echo '</a>';
                            echo '</li>';

                            if ($hasChildren) {
                                renderCategoryList($category->children, $level + 1);
                            }
                        }
                    }
                    renderCategoryList(\App\Models\Category::whereNull('parent_id')->get());
                    @endphp
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<section class="toast-alert">
    <div id="toast" class="toast" role="alert" aria-live="polite" aria-atomic="true" data-delay="1000"
         style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(15px); border: 1px solid #e5e7eb; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border-radius: 12px; color: #212529;">
        <div class="toast-body d-flex align-items-center p-3" style="color: #212529;">
            <span class="success mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="9" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
            </span>
            <span class="warning error mr-3 d-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </span>
            <span class="text" style="font-weight: 600; color: #212529;"></span>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search overlay toggle
    var searchBtn = document.querySelector('[data-toggle="search-overlay"]');
    var searchOverlay = document.getElementById('header-search-overlay');
    var searchClose = document.querySelector('[data-dismiss="search-overlay"]');
    if (searchBtn && searchOverlay) {
        searchBtn.addEventListener('click', function() {
            searchOverlay.style.display = 'flex';
            var input = searchOverlay.querySelector('input');
            if (input) { input.focus(); }
        });
    }
    if (searchClose && searchOverlay) {
        searchClose.addEventListener('click', function() {
            searchOverlay.style.display = 'none';
        });
    }
    if (searchOverlay) {
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) searchOverlay.style.display = 'none';
        });
    }
});
</script>
@if (Auth::check())
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.querySelector('.header-user-dropdown');
    const userIcon = userDropdown ? userDropdown.querySelector('.user-icon') : null;
    const userMenu = document.querySelector('.header-user-menu');
    
    if (userIcon && userMenu) {
        // Toggle dropdown on click
        userIcon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isVisible = userMenu.style.display === 'block';
            userMenu.style.display = isVisible ? 'none' : 'block';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (userDropdown && !userDropdown.contains(e.target)) {
                userMenu.style.display = 'none';
            }
        });
        
        // Close dropdown on menu item click
        const menuItems = userMenu.querySelectorAll('.header-user-menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                userMenu.style.display = 'none';
            });
        });
    }
});
</script>
@endif
