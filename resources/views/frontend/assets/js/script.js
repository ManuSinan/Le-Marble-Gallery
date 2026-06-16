require('./bootstrap');
window.app = {};
"use strict";
const byteSize = str => new Blob([str]).size;

$( document ).ready(function() {

    var config = {
        headers : {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init : function(element){
            
        },
        respond : function(element, json){

 
            if (json.errors) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $.each(json.errors, function (name, errors) {
                    var nameSplit = name.split('.');
                    var field = nameSplit.length == 1 ? name : (nameSplit[0] + '[' + nameSplit.splice(1).join('][') + ']');
                    $("[name='" + field + "']", element).addClass("is-invalid");
                    $("[name='" + field + "']", element).closest('div').append( $('<div/>').addClass('invalid-feedback').html(errors[0]));
                    $("[name='" + field + "']", element).closest('div').find('.invalid-feedback').show();
                    $("[name='" + field + "']", element).on('change', function(){
                        $(this).removeClass('is-invalid').closest('div').find('.invalid-feedback').remove();
                    });
                });
            }

        }
    }

    app.act = $('html').act(config);

    var decimalPlace = 2;

    decimalFix = function(value){
        var valueString = String(value);
        if (valueString.includes('.')) {
            if(valueString.split('.')[1].length == 1){
                return parseFloat(value).toFixed(1);
            }else{
                return parseFloat(value).toFixed(decimalPlace);
            }
        }; 
        return value;
    }

    $('.steper-btn-text', this).on('click', function(){

        if(4096 <= byteSize(document.cookie)){
            // alert('Cart has touched the max limit. Please delete existing cart items to add a new item.');
            $(this).closest('.steper-btn').addClass('shake');
            setTimeout(() => {
                $(this).closest('.steper-btn').removeClass('shake');
            }, 1000);
            return false;
        }

        $(this).closest('.steper-btn').removeClass('empty'); 

        var id = $(this).closest('.steper-btn').attr('data-id');
        var sellingPrice = $(this).closest('.steper-btn').attr('data-selling-price');
        var price = $(this).closest('.steper-btn').attr('data-price');
        var value = $(this).closest('.steper-btn').find('.steper-btn-value').html();
        var steper = $(this).closest('.steper-btn').attr('data-steper');
        
        var cart = {
            "products": {},
        }

        try {
            var cartJSON = $.parseJSON( cookies.get('__cart') , { expires: 360, path: '/' });
            if(cartJSON && cartJSON.products){
                cart = cartJSON;
            }
        }
        catch(err) {  }

        cart.products[id] = {         
            'price' : price,     
            'selling_price' : sellingPrice,
            'quantity' : parseFloat(value),
            'steper' : steper,
            'total_price' : parseFloat(price) * ( parseFloat(value) / parseFloat(steper) ),
            'total_selling_price' : parseFloat(sellingPrice) * ( parseFloat(value) / parseFloat(steper) ),
            'message' : ''
        };

        $('.item-product-message-' + id ).remove();

        if(cart.products[id]){
            $('.item-product-' + id + ' .details .unit').html( decimalFix(value) );
            $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
            $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
        }

        var total = 0;
        Object.entries(cart.products).forEach( ([id, data]) =>  
            total = total + data.total_selling_price
        );

        $('.cart-item-count').html( Object.keys( cart.products ).length );
        $('.cart-total-amount').html(total.toFixed(decimalPlace));

        cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });

        var cartUrl = $('meta[name="cart-url"]').attr('content');
        if (cartUrl) window.location.href = cartUrl;
    });
    
    $('.steper-btn-minus', this).on('click', function(){
        var id = $(this).closest('.steper-btn').attr('data-id');
        var sellingPrice = $(this).closest('.steper-btn').attr('data-selling-price');
        var price = $(this).closest('.steper-btn').attr('data-price');
        var steper = $(this).closest('.steper-btn').attr('data-steper');
        var value = $(this).closest('.steper-btn').find('.steper-btn-value').html();
        var min = $(this).closest('.steper-btn').attr('data-min');
        
        var clear = $(this).closest('.steper-btn').attr('data-clear');

        var cart = {
            "products": {},
        }

        try {
            var cartJSON = $.parseJSON( cookies.get('__cart') , { expires: 360, path: '/' });
            if(cartJSON && cartJSON.products){
                cart = cartJSON;
            }
        }
        catch(err) {  }

        var newValue = parseFloat(value) - parseFloat(steper);
        if(parseFloat(min) <= newValue ){
            $(this).closest('.steper-btn').find('.steper-btn-value').html( decimalFix(newValue) ); 

            cart.products[id] = {         
                'price' : price,     
                'selling_price' : sellingPrice,
                'quantity' : parseFloat(newValue),
                'steper' : steper,
                'total_price' : parseFloat(price) * ( parseFloat(newValue) / parseFloat(steper) ),
                'total_selling_price' : parseFloat(sellingPrice) * ( parseFloat(newValue) / parseFloat(steper) ),
                'message' : ''
            };

            $('.item-product-message-' + id ).remove();

            if(cart.products[id]){
                $('.item-product-' + id + ' .details .unit').html( decimalFix(newValue) );
                $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
            }

            var total = 0;
            Object.entries(cart.products).forEach( ([id, data]) =>  
                total = total + data.total_selling_price
            );


            $('.cart-item-count').html( Object.keys( cart.products ).length );
            $('.cart-total-amount').html(total.toFixed(decimalPlace));

            cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });
            
        }else{

            if(clear == 'true'){
                $(this).closest('.steper-btn').addClass('empty'); 
                $(this).closest('.steper-btn').find('.steper-btn-value').html( decimalFix(min) ); 

                delete cart.products[id];

                $('.item-product-message-' + id ).remove();
                
                $('.item-product-' + id + ' .details .unit').html( decimalFix(min) );
                $('.item-product-' + id + ' .price .selling').html( ( parseFloat(sellingPrice) * ( parseFloat(min) / parseFloat(steper) ) ).toFixed(decimalPlace)  );   
                $('.item-product-' + id + ' .price .orginal').html( ( parseFloat(price) * ( parseFloat(min) / parseFloat(steper) ) ).toFixed(decimalPlace)  );    


                var total = 0;
                Object.entries(cart.products).forEach( ([id, data]) =>  
                    total = total + data.total_selling_price
                );
    
                $('.cart-item-count').html( Object.keys( cart.products ).length );
                $('.cart-total-amount').html(total.toFixed(decimalPlace));
    
                cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });
            }else{

                $(this).closest('.steper-btn').addClass('shake');
                setTimeout(() => {
                    $(this).closest('.steper-btn').removeClass('shake');
                }, 1000);
            }
        }

    });
    
    $('.steper-btn-plus', this).on('click', function(){

        var id = $(this).closest('.steper-btn').attr('data-id');
        var sellingPrice = $(this).closest('.steper-btn').attr('data-selling-price');
        var price = $(this).closest('.steper-btn').attr('data-price');
        var steper = $(this).closest('.steper-btn').attr('data-steper');
        var value = $(this).closest('.steper-btn').find('.steper-btn-value').html();
        var max = $(this).closest('.steper-btn').attr('data-max');

        var cart = {
            "products": {},
        }

        try {
            var cartJSON = $.parseJSON( cookies.get('__cart') , { expires: 360, path: '/' });
            if(cartJSON && cartJSON.products){
                cart = cartJSON;
            }
        }
        catch(err) {  }

        var newValue = parseFloat(value) + parseFloat(steper);

        if(max){
            if(parseFloat(max) >= newValue ){
                $(this).closest('.steper-btn').find('.steper-btn-value').html( decimalFix(newValue) ); 

                cart.products[id] = {         
                    'price' : price,     
                    'selling_price' : sellingPrice,
                    'quantity' : parseFloat(newValue),
                    'steper' : steper,
                    'total_price' : parseFloat(price) * ( parseFloat(newValue) / parseFloat(steper) ),
                    'total_selling_price' : parseFloat(sellingPrice) * ( parseFloat(newValue) / parseFloat(steper) ),
                    'message' : ''
                };

                $('.item-product-message-' + id ).remove();
                
                if(cart.products[id]){
                    $('.item-product-' + id + ' .details .unit').html( decimalFix(newValue) );
                    $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                    $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
                }
                
                var total = 0;
                Object.entries(cart.products).forEach( ([id, data]) =>  
                    total = total + data.total_selling_price
                );

                $('.cart-item-count').html( Object.keys( cart.products ).length );
                $('.cart-total-amount').html(total.toFixed(decimalPlace));
    
                cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });

            }else{

                $(this).closest('.steper-btn').addClass('shake');
                setTimeout(() => {
                    $(this).closest('.steper-btn').removeClass('shake');
                }, 1000);

            }
            
        }else{
            $(this).closest('.steper-btn').find('.steper-btn-value').html( decimalFix(newValue) ); 

            cart.products[id] = {         
                'price' : price,     
                'selling_price' : sellingPrice,
                'quantity' : parseFloat(newValue),
                'steper' : steper,
                'total_price' : parseFloat(price) * ( parseFloat(newValue) / parseFloat(steper) ),
                'total_selling_price' : parseFloat(sellingPrice) * ( parseFloat(newValue) / parseFloat(steper) ),
                'message' : ''
            };

            $('.item-product-message-' + id ).remove();
            
            if(cart.products[id]){
                $('.item-product-' + id + ' .details .unit').html( decimalFix(newValue) );
                $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
            }
            
            var total = 0;
            Object.entries(cart.products).forEach( ([id, data]) =>  
                total = total + data.total_selling_price
            );


            $('.cart-item-count').html( Object.keys( cart.products ).length );
            $('.cart-total-amount').html(total.toFixed(decimalPlace));

            cookies.set('__cart', JSON.stringify(cart), { expires: 360, path: '/' });
        }

    });

    // Homepage banner carousel: auto-slide with smooth transition and pause-on-hover
    $('.banner-carousel').owlCarousel({
        items: 1,
        loop: true,                    // Infinite looping: after last slide, continue from first
        autoplay: true,                // Enable automatic sliding
        autoplayTimeout: 2000,         // Slide interval: 2 seconds
        autoplayHoverPause: true,      // Pause auto-slide when user hovers over banner; resume on mouse leave
        smartSpeed: 600,               // Duration of slide transition (ms) for smooth effect
        nav: false,                    // No prev/next arrows (banner has none in markup)
        dots: false,                   // No indicator dots on banner
        thumbs: false,                 // No thumbnails on banner; avoids conflict with thumbs plugin
        responsive: {
            0: { items: 1 },           // Mobile: 1 slide
            768: { items: 1 },         // Tablet: 1 slide
            992: { items: 1 }          // Desktop: 1 slide
        }
    });

    $('.promo-banner-carousel').owlCarousel({
        loop: true,
        items: 1,
        margin: 0,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true
    });

    // Home Appliances spotlight: one product per slide, changes every 5 seconds
    if ($('.home-appliances-spotlight-carousel').length) {
        $('.home-appliances-spotlight-carousel').owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 400,
            nav: false,
            dots: false
        });
    }

    $('.category-carousel').owlCarousel({
        loop: false,
        items: 14,
        autoplay: false,
        nav:false,
        dots:false,
        thumbs: true,
        thumbImage: false,
        thumbsPrerendered: true,
        thumbContainerClass: 'thumbs-wrap',
        thumbItemClass: 'item-thumb'
    });



    $('.band-carousel').owlCarousel({
        loop: true,
        items: 6,
        margin: 20, // 16–24px gap between brand cards
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        nav: true,
        dots: false,
        responsive: {
            0: { items: 3 },
            600: { items: 4 },
            1000: { items: 6 }
        }
    });


    $('.nav-carousel').owlCarousel({
        margin:10,
        loop:true,
        mouseDrag:true,
        touchDrag:true,
        autoWidth:true,
        autoplay: true,
        dots:false,
        autoplayTimeout:1500,
    });



    $('.zoom').each(function() {
        $(this)
        .wrap('<span style="display:inline-block"></span>')
        .css('display', 'block')
        .parent()
        .zoom({url: $(this).attr('zoom-src'), magnify : 1.2 });    
    });

    $('.popup .popup-close-btn').click(function(){
        $('.popup').remove();
        document.cookie = "__popup=true; path=/; Secure; expires=" + new Date(new Date().setFullYear(new Date().getFullYear() + 1)).toUTCString();
    });

    // 24-hour deal countdown (auto-reset, localStorage)
    (function initDealCountdown() {
        var DEAL_DURATION = 24 * 60 * 60 * 1000;
        var STORAGE_PREFIX = 'dealStartTime_';

        function pad2(n) {
            return (n < 10 ? '0' : '') + n;
        }

        function getStartTime(id) {
            var key = STORAGE_PREFIX + id;
            var stored = localStorage.getItem(key);
            if (stored === null) {
                var now = Date.now();
                localStorage.setItem(key, String(now));
                return now;
            }
            return parseInt(stored, 10);
        }

        function setStartTime(id) {
            var now = Date.now();
            localStorage.setItem(STORAGE_PREFIX + id, String(now));
            return now;
        }

        function getRemaining(startTime) {
            var elapsed = Date.now() - startTime;
            var remaining = DEAL_DURATION - elapsed;
            if (remaining <= 0) {
                return null;
            }
            return remaining;
        }

        function render(ms) {
            var totalSec = Math.floor(ms / 1000);
            var h = Math.floor(totalSec / 3600);
            var m = Math.floor((totalSec % 3600) / 60);
            var s = totalSec % 60;
            return { h: pad2(h), m: pad2(m), s: pad2(s) };
        }

        var containers = document.querySelectorAll('.js-deal-countdown');
        var byId = {};
        containers.forEach(function (el) {
            var id = el.getAttribute('data-deal-countdown-id') || 'default';
            if (!byId[id]) byId[id] = [];
            byId[id].push(el);
        });

        Object.keys(byId).forEach(function (id) {
            var startTime = getStartTime(id);
            var list = byId[id];

            function tick() {
                var remaining = getRemaining(startTime);
                if (remaining === null) {
                    startTime = setStartTime(id);
                    remaining = DEAL_DURATION;
                }
                var parts = render(remaining);
                list.forEach(function (container) {
                    var hEl = container.querySelector('.js-deal-hours');
                    var mEl = container.querySelector('.js-deal-minutes');
                    var sEl = container.querySelector('.js-deal-seconds');
                    if (hEl) hEl.textContent = parts.h;
                    if (mEl) mEl.textContent = parts.m;
                    if (sEl) sEl.textContent = parts.s;
                });
            }

            tick();
            setInterval(tick, 1000);
        });
    })();
});