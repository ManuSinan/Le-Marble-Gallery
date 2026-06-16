<style>
/* Modern Product Card Styles */
.card-product-grid-modern {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
    border: none;
}

.card-product-grid-modern:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.img-wrap-modern {
    position: relative;
    height: 220px;
    overflow: hidden;
    border-radius: 16px 16px 0 0;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.img-wrap-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sale-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #16a34a;
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 10;
    transform: rotate(-2deg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.info-wrap-modern {
    padding: 16px;
}

.product-brand {
    font-size: 12px;
    color: #969696;
    margin-bottom: 4px;
    font-weight: 400;
}

.product-title-modern {
    font-size: 15px;
    font-weight: 600;
    color: #212529;
    text-decoration: none;
    display: block;
    margin-bottom: 8px;
    line-height: 1.4;
    min-height: 42px;
}

.product-title-modern:hover {
    color: #2874f0;
    text-decoration: none;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars .star {
    font-size: 14px;
    color: #ffc107;
}

.rating-stars .star.half {
    color: #ffc107;
    opacity: 0.5;
}

.rating-value {
    font-size: 13px;
    color: #545454;
    font-weight: 500;
}

.rating-icon {
    width: 16px;
    height: 16px;
    color: #969696;
    margin-left: 2px;
}

.price-modern {
    margin-bottom: 14px;
}

.price-current {
    font-size: 18px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 4px;
}

.price-original {
    font-size: 14px;
    color: #969696;
    text-decoration: line-through;
    display: inline-block;
    margin-right: 8px;
}

.price-savings {
    font-size: 13px;
    color: #00b517;
    font-weight: 500;
    display: inline-block;
}

.product-actions {
    display: flex;
    gap: 8px;
    align-items: stretch;
}

.cart-btn-modern {
    flex: 1;
}

.steper-btn-modern {
    background: #2874f0;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s ease;
    position: relative;
    width: 100%;
}

.steper-btn-modern:hover {
    background: #1a5dc1;
}

/* Always hide count and +/- buttons, always show Add to Cart text */
.steper-btn-minus-modern,
.steper-btn-value-modern,
.steper-btn-plus-modern {
    display: none !important;
}

.steper-btn-text-modern {
    display: inline-block !important;
}

.cart-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.steper-btn-text-modern {
    font-weight: 500;
}

.steper-btn-minus-modern,
.steper-btn-plus-modern {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.steper-btn-minus-modern:hover,
.steper-btn-plus-modern:hover {
    background: rgba(255, 255, 255, 0.3);
}

.steper-btn-value-modern {
    min-width: 24px;
    text-align: center;
    font-weight: 600;
}

.fav-btn-modern {
    width: 44px;
    height: 44px;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.fav-btn-modern:hover {
    background: #eeeeee;
    border-color: #d0d0d0;
}

.fav-btn-modern.active {
    background: #fff5f5;
    border-color: #ff6b6b;
}

.fav-btn-modern.active svg {
    fill: #ff6b6b;
    color: #ff6b6b;
}

.fav-btn-modern svg {
    width: 20px;
    height: 20px;
    color: #212529;
    transition: all 0.2s ease;
}

.notify-btn-modern {
    flex: 1;
    background: #f5f5f5;
    color: #212529;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 10px 16px;
    text-align: center;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.notify-btn-modern:hover {
    background: #eeeeee;
}

.notify-btn-modern.active {
    background: #2874f0;
    color: white;
    border-color: #2874f0;
}

/* Offers & Deals styles live in main stylesheet (style.scss) so they apply on home page */

/* Home page horizontal product cards – wide landscape (2:1), light gray, 2 per row */
.home-page-product-listing .product-grid-home {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}
@media (max-width: 767px) {
    .home-page-product-listing .product-grid-home {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
@media (max-width: 575px) {
    .home-page-product-listing .product-grid-home {
        gap: 0.875rem;
    }
}

.card-product-home-horizontal {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    background:rgb(255, 255, 255);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: box-shadow 0.3s ease;
    height: 260px;
    min-height: 260px;
}
.card-product-home-horizontal:hover {
    color: inherit;
    text-decoration: none;
}
.card-product-home-horizontal:hover .card-home-image img {
    transform: scale(1.12);
    box-shadow: none;
}

.card-home-info {
    flex: 1;
    padding: 16px 18px;
    min-width: 0;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.card-home-brand {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 4px;
}
.card-home-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 6px 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-home-feature {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.4;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-home-price {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.card-home-price-current {
    font-size: 17px;
    font-weight: 700;
    color: #111827;
}
.card-home-price-original {
    font-size: 14px;
    color: #9ca3af;
    text-decoration: line-through;
}

.card-home-image {
    flex-shrink: 0;
    width: 220px;
    height: 260px;
    display: flex;
    align-items: center;
    justify-content: center;
    background:rgb(255, 255, 255);
    padding: 20px;
}
.card-home-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
    box-shadow: none;
}
@media (max-width: 767px) {
    .card-product-home-horizontal {
        height: 240px;
        min-height: 240px;
    }
    .card-home-image {
        width: 200px;
        height: 240px;
        padding: 16px;
    }
}
@media (max-width: 575px) {
    .card-product-home-horizontal {
        height: 220px;
        min-height: 220px;
    }
    .card-home-info {
        padding: 12px 14px;
    }
    .card-home-title {
        font-size: 16px;
    }
    .card-home-feature {
        font-size: 13px;
    }
    .card-home-price-current {
        font-size: 15px;
    }
    .card-home-image {
        width: 180px;
        height: 220px;
        padding: 14px;
    }
}
</style>

<script>
// Handle modern stepper buttons - Add to Cart functionality
// Wait for DOM and scripts to be ready
(function() {
    function initCartButtons() {
        // Check if jQuery is available
        if (typeof jQuery === 'undefined' || typeof window.$ === 'undefined') {
            setTimeout(initCartButtons, 100);
            return;
        }
        
        var $ = window.jQuery || window.$;
        
        // Wait a bit more for main script to initialize
        setTimeout(function() {
        
        // Helper function for decimal formatting
        function getDecimalFix() {
            if (typeof window.decimalFix === 'function') {
                return window.decimalFix;
            }
            return function(value) {
                var valueString = String(value);
                if (valueString.includes('.')) {
                    if(valueString.split('.')[1].length == 1){
                        return parseFloat(value).toFixed(1);
                    }else{
                        return parseFloat(value).toFixed(2);
                    }
                }
                return value;
            };
        }
        
        // Get cookies helper
        function getCookies() {
            if (typeof window.cookies !== 'undefined') {
                return window.cookies;
            } else if (typeof cookies !== 'undefined') {
                return cookies;
            }
            return null;
        }
        
        // Get decimal place
        var decimalPlace = 2;
        if (typeof window.decimalPlace !== 'undefined') {
            decimalPlace = window.decimalPlace;
        }
        
        // Check if buttons exist and log for debugging
        var buttonCount = $('.steper-btn-modern').length;
        console.log('Modern cart buttons initialized. Found ' + buttonCount + ' buttons');
        
        // Handle modern stepper buttons - Add to Cart functionality
        $(document).on('click', '.steper-btn-modern', function(e){
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Add to Cart button clicked'); // Debug log
            
            var $btn = $(this);
            var cookiesHelper = getCookies();
            var decimalFix = getDecimalFix();
            
            if (!cookiesHelper) {
                console.error('Cookies helper not available');
                // Try to get it one more time
                cookiesHelper = window.cookies || cookies;
                if (!cookiesHelper) {
                    alert('Unable to add to cart. Please refresh the page.');
                    return false;
                }
            }
            
            var id = $btn.attr('data-id');
            var sellingPrice = $btn.attr('data-selling-price');
            var price = $btn.attr('data-price');
            var steper = $btn.attr('data-steper');
            var min = $btn.attr('data-min');
            var max = $btn.attr('data-max');
            
            if (!id || !sellingPrice || !price || !steper) {
                console.error('Missing product data');
                return false;
            }
            
            var cart = {
                "products": {},
            }
            
            try {
                var cartData = cookiesHelper.get('__cart');
                if (cartData) {
                    var cartJSON = $.parseJSON(cartData);
                    if(cartJSON && cartJSON.products){
                        cart = cartJSON;
                    }
                }
            }
            catch(err) {
                console.error('Error parsing cart:', err);
            }
            
            // Check if product already in cart
            var currentQuantity = 0;
            if(cart.products[id]) {
                currentQuantity = parseFloat(cart.products[id].quantity);
            }
            
            // Calculate new quantity - if not in cart, use minimum quantity
            var newQuantity = currentQuantity > 0 ? currentQuantity + parseFloat(steper) : parseFloat(min);
            
            // Check max limit
            if(max && parseFloat(newQuantity) > parseFloat(max)) {
                $btn.addClass('shake');
                setTimeout(function() {
                    $btn.removeClass('shake');
                }, 1000);
                return false;
            }
            
            // Add or update product in cart
            cart.products[id] = {         
                'price' : price,     
                'selling_price' : sellingPrice,
                'quantity' : parseFloat(newQuantity),
                'steper' : steper,
                'total_price' : parseFloat(price) * ( parseFloat(newQuantity) / parseFloat(steper) ),
                'total_selling_price' : parseFloat(sellingPrice) * ( parseFloat(newQuantity) / parseFloat(steper) ),
                'message' : ''
            };
            
            $('.item-product-message-' + id ).remove();
            
            if(cart.products[id]){
                $('.item-product-' + id + ' .details .unit').html( decimalFix(newQuantity) );
                $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(2) );
                $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(2) );    
            }
            
            var total = 0;
            Object.entries(cart.products).forEach( function(entry) {
                var productId = entry[0];
                var data = entry[1];
                total = total + data.total_selling_price;
            });
        
            $('.cart-item-count').html( Object.keys( cart.products ).length );
            if ($('.cart-total-amount').length) {
                $('.cart-total-amount').html(total.toFixed(2));
            }
            
            try {
                cookiesHelper.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });
            } catch(err) {
                console.error('Error saving cart:', err);
                alert('Error adding to cart. Please try again.');
                return false;
            }
            
            // Redirect to cart page
            var cartUrl = $('meta[name="cart-url"]').attr('content');
            if (cartUrl) {
                window.location.href = cartUrl;
            } else {
                var appUrl = $('meta[name="app-url"]').attr('content');
                if (appUrl) {
                    window.location.href = appUrl + '/cart';
                } else {
                    window.location.href = '/cart';
                }
            }
            
            return false;
        });
        }, 500); // Wait 500ms for main script to initialize
    }
    
    // Start initialization - wait for both DOM and window load
    function startInit() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initCartButtons, 800); // Wait longer for main script
            });
        } else {
            // DOM already loaded, wait for window load
            if (document.readyState === 'complete') {
                setTimeout(initCartButtons, 800);
            } else {
                window.addEventListener('load', function() {
                    setTimeout(initCartButtons, 500);
                });
            }
        }
    }
    
    startInit();
})();
</script>