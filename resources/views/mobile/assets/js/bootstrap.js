const { css } = require('jquery');
const { first } = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');
    window.bootstrap = require('bootstrap');
    window.Popper = require('popper.js').default;
    require('owl.carousel');
    require('jquery-circle-progress');
    window.party = require('party-js');
    window.Hammer = require('hammerjs');

    window.PhotoSwipe = require('photoswipe');
    window.PhotoSwipeUI_Default = require('photoswipe/dist/photoswipe-ui-default');
 
} catch (e) {}

"use strict";
 
(function( $ ) {

    var localStorage = window.localStorage;

    var fcm = localStorage.getItem('__fcm');
 
    var vibratePattern = [100];

    var gallery = false;
 
    $.fn.act = function(options) {
        var act = {};
 
        var decimalPlace = localStorage.getItem('__decimal_place');
 
        if (!decimalPlace) { 
            var decimalPlace = 2;
        }
        
        var settings = $.extend({ 
            respond : function(act, element, json){},
            init : function(act, element, json){}
        }, options);

        act.loading = function (element, status){
            if(status === true){

                $(element).addClass('processing');
                $('#loader').show();
                $('[type="submit"]', element).prop('disabled', true);

            }else{

                $(element).removeClass('processing');
                $('#loader').hide();
                $('[type="submit"]', element).prop('disabled', false);
            }
        }

        act.respond = function(element, json){

           if (typeof StatusBar !== 'undefined' && json.statusbar) {
                if(json.statusbar.color){
                   StatusBar.backgroundColorByHexString(json.statusbar.color);
                }

                if(json.statusbar.lightcontent){
                   StatusBar.styleLightContent();
                }
                if(json.statusbar.hide){
                   StatusBar.hide();
                }
                if(json.statusbar.show){
                   StatusBar.show();
                }
           }

            if (json.errors) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $.each(json.errors, function (name, errors) {
                    var nameSplit = name.split('.');
                    var field = nameSplit.length == 1 ? name : (nameSplit[0] + '[' + nameSplit.splice(1).join('][') + ']');
                    $("[name='" + field + "']", element).addClass("is-invalid");
                    $("[name='" + field + "']", element).closest('.form-group').append( $('<div/>').addClass('invalid-feedback').html(errors[0]));
                    $("[name='" + field + "']", element).closest('.form-group').find('.invalid-feedback').show();
                    $("[name='" + field + "']", element).on('change', function(){
                        $(this).removeClass('is-invalid').closest('.form-group').find('.invalid-feedback').remove();
                    });
                });
            }
 
            if (json.jquery) {
                if (json.jquery.element && json.jquery.method) {
                  if (json.jquery.value) {
                    if (Array.isArray(json.jquery.value)) {
                      $(json.jquery.element)[json.jquery.method](...json.jquery.value);
                    } else {
                      $(json.jquery.element)[json.jquery.method](json.jquery.value);
                    }
                  } else {
                    $(json.jquery.element)[json.jquery.method]();
                  }
                } else {
                  $.each(json.jquery, function (index, uiTarget) {
                    if (uiTarget.element && uiTarget.method) {
                      if (uiTarget.value) {
                        if (Array.isArray(uiTarget.value)) {
                          $(uiTarget.element)[uiTarget.method](...uiTarget.value);
                        } else {
                          $(uiTarget.element)[uiTarget.method](uiTarget.value);
                        }
                      } else {
                        $(uiTarget.element)[uiTarget.method]();
                      }
                    }
                  });
                }
            }
 
            settings.respond(act, element, json);



            if (json.script) {
                try { 
                    $.getScript(json.script);
                }
                catch(err) {}
            }

 
            if (json.vibrate) {
                try { 
                    navigator.vibrate(vibratePattern);
                }
                catch(err) {}
            }

            if (json.reload) {
                if(json.reload == 'page'){
                    $('#page').trigger('load')
                }
                if(json.reload == 'app'){
                    location.reload();
                }   
            }
 
            if (json.signout) {
                localStorage.clear();
            }

            if (json.language) {
                localStorage.setItem('__language', json.language);
            }

            if (json.scrolltop) {

                if(json.scrolltop == 'animate'){
                    $('html, body').animate({ scrollTop: 0 }, 500);
                }else{
                    document.body.scrollTop=0;
                    document.documentElement.scrollTop=0;
                }             
            }

            if (json.authorization) {
                localStorage.setItem('__authorization', 'Bearer ' +  json.authorization);
            }
            
            if (json.cart) {
                localStorage.setItem('__cart', JSON.stringify(json.cart));
            }

            if (json.intro) {
                localStorage.setItem('__intro', json.intro);
            }

            if (json.fcm) {
                localStorage.setItem('__fcm', json.fcm);
            }
 
            if (json.decimal_place) {
                localStorage.setItem('__decimal_place', json.decimal_place);
                decimalPlace = json.decimal_place;
            }
 
            if (json.init) {
                $.each(json.init, function (index, element) {
                    $(element).act(settings);
                });
            }

            if (json.redirect) {
                act.request(element, json.redirect, null);
            }

            if (json.toast) {
                $('#toast').addClass('show');
                $('#toast .text').html(json.toast);
            
                setTimeout(() => {
                    $('#toast').removeClass('show');
                    $('#toast .text').html('');
                }, 3000);
            }

            if (json.photoswipe) {
                var pswpElement = document.querySelectorAll('.pswp')[0];
                var options = {
                    index: parseInt(json.photoswipe.index),
                    history: false,
                    focus: false,
                    shareEl:false,
                    showAnimationDuration: 0,
                    hideAnimationDuration: 0
                };
                
               gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, json.photoswipe.items, options);
               
                gallery.listen('gettingData', function(index, item) {
                    if (item.w < 1 || item.h < 1) {
                        var img = new Image(); 
                        img.onload = function() { 
                        item.w = this.width;
                        item.h = this.height;
                        gallery.invalidateCurrItems();
                        gallery.updateSize(true);
                        }
                        img.src = item.src;
                    }
                });

                gallery.init();
            }
 

            if (json.party) {
                party.element(document.querySelector(json.party), {
                    count: party.variation(50, 0.5),
                    angleSpan: party.minmax(60, 120)
                });
            }
 
            if (json.razorpay) {
                if (json.razorpay.option) {
                  if (json.razorpay.element && json.razorpay.response) {
                    json.razorpay.option['handler'] = function (response) {
                      var data = new FormData();
                      data.append('razorpay_payment_id', response.razorpay_payment_id);
                      data.append('razorpay_order_id', response.razorpay_order_id);
                      data.append('razorpay_signature', response.razorpay_signature);
                      act.request(json.razorpay.element, json.razorpay.response, data);
                    };
                  }

                  if(build == 'pwa'){
                    var RazorpayCheckout = new Razorpay(json.razorpay.option);
                  }

                  RazorpayCheckout.open();
                }
            }
        };

        act.request = function (element, url, data) {

            if(!$(element).attr('act-loader') &&  $(element).attr('act-loader') != 'false'){
                act.loading(element, true);
            }else{
                $(element).removeAttr('act-loader')
            }

            var language = localStorage.getItem('__language');

            if(language == null){
                language = 'en';
            }

            var version = '1.0.0';

            var headers = {
                'Accept': 'application/json',
                'Language' : language,
                'App-Version' : version,
                'Build' : build,
            };

            var authorization = localStorage.getItem('__authorization');

            if(authorization){
                headers['Authorization'] = authorization;
            }
  
            if(localStorage.getItem('__fcm') != fcm){
                headers['Fcm'] = fcm;
            }

            if(localStorage.getItem('__intro') == null){
                headers['Intro'] = 'show';
            }

            var cart = localStorage.getItem('__cart');
 
            if(cart){

                if(!data){
                    data = new FormData();
                }

                data.append('cart', cart);
            }
            
            $.ajax({
                headers : headers, 
                url: url,
                type: 'post',
                data: data,
                contentType: false,
                processData: false
            }).done(function (json) {

                act.respond(element, json);

                act.loading(element, false);

            }).fail(function (jqXHR, textStatus) {  

                if(jqXHR.status == 422){
                    act.respond(element, jqXHR.responseJSON);
                }else if(jqXHR.status == 401){
                    $('#page').html($('#unauthorized').html());        
                    $('#page').act(settings);
                }else{

                    if($(element).attr("act-respond") && $(element).attr("act-request")){ 
                        var respond = $(element).attr('act-respond');  
                        try {
                            act.respond(element, JSON.parse(respond));
                        }catch(err) {
                            if($(element).attr('name')){
                                console.error('Invalid input ' + $(element).attr('name') + ' act-respond json');
                                console.error(err);
                            }else{
                                console.error(err);
                            }
                        }
                    }else{
                        $('#page').html($('#offline').html());        
                        $('#page').act(settings);
                    }   
                }
 
                act.loading(element, false);
            });
        };

        act.on = function(element, action){
            var data = false;
            if(action == 'submit'){
                data = new FormData(element);
                
            }else{
                data = new FormData();

                if($(element).val()){
                    data.append('value', $(element).val());
                }
            }

            $.each(element.attributes, function() {
                data.append(this.name, this.value);
            });

            if($(element).attr("act-with")){  
                
                var actWith = $(element).attr("act-with");

                $(element).closest('[act-group="' + actWith + '"]').find('[act-related="' + actWith + '"]').each(function(){
                    if( $(element).attr('name') != $(this).attr('name') ){
                        data.append('related[' + $(this).attr('name') + ']', $(this).val());
                    }
                });
            }

            if($(element).attr("act-request")){     
                var request = $(element).attr('act-request');
                act.request(element, request, data)
            }

            if($(element).attr("act-respond") && !$(element).attr("act-request")){      
                var respond = $(element).attr('act-respond');  
                try {
                    act.respond(element, JSON.parse(respond));
                }catch(err) {
                    if($(element).attr('name')){
                        console.error('Invalid input ' + $(element).attr('name') + ' act-respond json');
                        console.error(err);
                    }else{
                        console.error(err);
                    }
                }
            }
        }

        $('[type="submit"]', this).prop('disabled', false);

        $('[act-on]', this).each(function(){
            var action =  $(this).attr('act-on');
 
            if(action == 'load'){
                act.on(this, action)
            }

            $(this).on(action, function(event){
                event.preventDefault();
                act.on(this, action)       
            });
        });

        $('a:not(.external)', this).click(function (e) {
            e.preventDefault();

            if($('body').hasClass('modal-open')){
                $('.modal').modal('hide');
                if($('body').hasClass('modal-open')){
                    $('.modal').removeClass('show').addClass('fade').removeAttr('aria-modal').attr('aria-hidden', false).hide();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                }
            }

            var request = $(this).attr('href');
            if(request != '#' && request != ''){
                act.request(this, request, null);
            }
        });

        $('.carousel-full', this).on({  
            'initialized.owl.carousel': function () {
                $('.owl-item.cloned', this).find('a:not(.external)').click(function (e) {
                    e.preventDefault();
                    var request = $(this).attr('href');
                    if(request != '#' && request != ''){
                        act.request(this, request, null);
                    }
                });
            } 
        }).owlCarousel({
            loop: false,
            margin: 0,
            nav: false,
            items: 1,
            dots: true,
            autoplay:true,
            autoplayTimeout:5000,
            autoplayHoverPause:true
        });

        $('.carousel-banner', this).on({  
            'initialized.owl.carousel': function () {
                $('.owl-item.cloned', this).find('a:not(.external)').click(function (e) {
                    e.preventDefault();
                    var request = $(this).attr('href');
                    if(request != '#' && request != ''){
                        act.request(this, request, null);
                    }
                });
            } 
        }).owlCarousel({
            loop: true,
            margin: 0,
            nav: false,
            items: 1,
            dots: true,
            autoplay:true,
            autoplayTimeout:5000,
            autoplayHoverPause:true
        });

        $('.carousel-multiple', this).on({  
            'initialized.owl.carousel': function () {
                $('.owl-item.cloned', this).find('a:not(.external)').click(function (e) {
                    e.preventDefault();
                    var request = $(this).attr('href');
                    if(request != '#' && request != ''){
                        act.request(this, request, null);
                    }
                });
            } 
        }).owlCarousel({
            stagePadding: 32,
            loop: true,
            margin: 16,
            nav: false,
            items: 3,
            dots: false,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                },
                768: {
                    items: 5,
                }
            }
        });

        $('.carousel-text', this).each(function() {

            var startPosition = $('.start-position', this).index();
            
            $(this).owlCarousel({
                stagePadding: 40,
                nav:true,
                dots: false,
                autoWidth:true,
                startPosition: startPosition,
            });
        });

 
        $(".toggle-searchbox", this).on('click', function () {
            var a = $("#search").hasClass("show");
            if (a) {
                $("#search").removeClass("show");
            }
            else {
                $("#search").addClass("show");
                $("#search .form-control").focus();
            }
        });


        act.decimalFix = function(value){
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

        $('.steper-btn-text, .steper-btn-symbol', this).on('click', function(){
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
                var cartJSON = $.parseJSON( localStorage.getItem('__cart') );
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
                $('.item-product-' + id + ' .details .unit').html( act.decimalFix(value) );
                $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
            }

            var total = 0;
            Object.entries(cart.products).forEach( ([id, data]) =>  
                total = total + data.total_selling_price
            );
 
            $('.cart-item-count').html( Object.keys( cart.products ).length );
            $('.cart-total-amount').html(total.toFixed(decimalPlace));

            localStorage.setItem('__cart', JSON.stringify(cart));

            if(build == 'ios'){
                try { 
                    Vibrate.vibrate('rigid');
                }
                catch(err) {}
            }else{
                try { 
                    navigator.vibrate(vibratePattern);
                }
                catch(err) {}
            }
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
                var cartJSON = $.parseJSON( localStorage.getItem('__cart') );
                if(cartJSON && cartJSON.products){
                    cart = cartJSON;
                }
            }
            catch(err) {  }

            var newValue = parseFloat(value) - parseFloat(steper);
            if(parseFloat(min) <= newValue ){
                $(this).closest('.steper-btn').find('.steper-btn-value').html( act.decimalFix(newValue) ); 
 
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
                    $('.item-product-' + id + ' .details .unit').html( act.decimalFix(newValue) );
                    $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                    $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
                }
 
                var total = 0;
                Object.entries(cart.products).forEach( ([id, data]) =>  
                    total = total + data.total_selling_price
                );
 
    
                $('.cart-item-count').html( Object.keys( cart.products ).length );
                $('.cart-total-amount').html(total.toFixed(decimalPlace));
    
                localStorage.setItem('__cart', JSON.stringify(cart));
                
            }else{

                if(clear == 'true'){
                    $(this).closest('.steper-btn').addClass('empty'); 
                    $(this).closest('.steper-btn').find('.steper-btn-value').html( act.decimalFix(min) ); 
    
                    delete cart.products[id];

                    $('.item-product-message-' + id ).remove();
                    
                    $('.item-product-' + id + ' .details .unit').html( act.decimalFix(min) );
                    $('.item-product-' + id + ' .price .selling').html( ( parseFloat(sellingPrice) * ( parseFloat(min) / parseFloat(steper) ) ).toFixed(decimalPlace)  );   
                    $('.item-product-' + id + ' .price .orginal').html( ( parseFloat(price) * ( parseFloat(min) / parseFloat(steper) ) ).toFixed(decimalPlace)  );    
 

                    var total = 0;
                    Object.entries(cart.products).forEach( ([id, data]) =>  
                        total = total + data.total_selling_price
                    );
        
                    $('.cart-item-count').html( Object.keys( cart.products ).length );
                    $('.cart-total-amount').html(total.toFixed(decimalPlace));
        
                    localStorage.setItem('__cart', JSON.stringify(cart));
                }else{

                    $(this).closest('.steper-btn').addClass('shake');
                    setTimeout(() => {
                        $(this).closest('.steper-btn').removeClass('shake');
                    }, 1000);
                }
            }

            if(build == 'ios'){
                try { 
                    Vibrate.vibrate('rigid');
                }
                catch(err) {}
            }else{
                try { 
                    navigator.vibrate(vibratePattern);
                }
                catch(err) {}
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
                var cartJSON = $.parseJSON( localStorage.getItem('__cart') );
                if(cartJSON && cartJSON.products){
                    cart = cartJSON;
                }
            }
            catch(err) {  }
 
            var newValue = parseFloat(value) + parseFloat(steper);

            if(max){
                if(parseFloat(max) >= newValue ){
                    $(this).closest('.steper-btn').find('.steper-btn-value').html( act.decimalFix(newValue) ); 
 
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
                        $('.item-product-' + id + ' .details .unit').html( act.decimalFix(newValue) );
                        $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                        $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
                    }
                    
                    var total = 0;
                    Object.entries(cart.products).forEach( ([id, data]) =>  
                        total = total + data.total_selling_price
                    );
 
                    $('.cart-item-count').html( Object.keys( cart.products ).length );
                    $('.cart-total-amount').html(total.toFixed(decimalPlace));
        
                    localStorage.setItem('__cart', JSON.stringify(cart));

                    if(build == 'ios'){
                        try { 
                            Vibrate.vibrate('rigid');
                        }
                        catch(err) {}
                    }else{
                        try { 
                            navigator.vibrate(vibratePattern);
                        }
                        catch(err) {}
                    }
                }else{

                    $(this).closest('.steper-btn').addClass('shake');
                    setTimeout(() => {
                        $(this).closest('.steper-btn').removeClass('shake');
                    }, 1000);

                }
                
            }else{
                $(this).closest('.steper-btn').find('.steper-btn-value').html( act.decimalFix(newValue) ); 
 
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
                    $('.item-product-' + id + ' .details .unit').html( act.decimalFix(newValue) );
                    $('.item-product-' + id + ' .price .selling').html( cart.products[id].total_selling_price.toFixed(decimalPlace) );
                    $('.item-product-' + id + ' .price .orginal').html( cart.products[id].total_price.toFixed(decimalPlace) );    
                }
                
                var total = 0;
                Object.entries(cart.products).forEach( ([id, data]) =>  
                    total = total + data.total_selling_price
                );

    
                $('.cart-item-count').html( Object.keys( cart.products ).length );
                $('.cart-total-amount').html(total.toFixed(decimalPlace));
    
                localStorage.setItem('__cart', JSON.stringify(cart));

                if(build == 'ios'){
                    try { 
                        Vibrate.vibrate('rigid');
                    }
                    catch(err) {}
                }else{
                    try { 
                        navigator.vibrate(vibratePattern);
                    }
                    catch(err) {}
                }
            }
 
        });

        $('.back', this).on('click', function(){
            $('#page').trigger('load');
        });

        $('.clear-input', this).click(function () {
            $(this).closest('.input-wrapper').find('.form-control').focus();
            $(this).closest('.input-wrapper').find('.form-control').val('');
            $(this).closest('.input-wrapper').removeClass('not-empty');
        });
 
        $('.form-group .form-control', this).focus(function () {
            $(this).closest('.input-wrapper').addClass('active');
        }).blur(function () {
            $(this).closest('.input-wrapper').removeClass('active');
        })
 
        $('.form-group .form-control', this).keyup(function () {
            var inputCheck = $(this).val().length;
            if (inputCheck > 0) {
                $(this).closest('.input-wrapper').addClass('not-empty');
            }
            else {
                $(this).closest('.input-wrapper').removeClass('not-empty');
            }
        });
 
        $('.share-btn', this).click(function () {
            if (navigator.share) { 
                navigator.share({
                   title: $(this).attr('data-title'),
                   text: $(this).attr('data-text'),
                   url: $(this).attr('data-url')
                 }).then(() => { })
                .catch(console.error);
            }
        });
 
        settings.init(act, this);
    };

    
    if(build == 'ios'){
        var page = document.querySelector('body', this);

        Hammer(page).on('swiperight', function(e) {

            var endPoint = e.pointers[0].pageX;
            var distance = e.distance;
            var origin = endPoint - distance;

            if(origin > 10 && origin < 50 && distance > 150){
                if( $('#page').attr('page-name') != 'home'){
                    $('#page').trigger('load')
                }   
            }
        });
    }

 
    document.addEventListener("deviceready", function() { 

        document.addEventListener('backbutton', function(e) {
            e.preventDefault();
            if( $('#page').attr('page-name') != 'home'){
                if(gallery){
                    gallery.close();
                    gallery = false;
                }else{
                    $('#page').trigger('load');
                }   
            }else{
                navigator.app.exitApp();
            }
        }, false); 

        try {
            push = PushNotification.init({
                android: {
                },
                ios: {
                    alert: "true",
                    badge: "true",
                    sound: "true"
                },
                });
            
                push.on('registration', (data) => {
            
                    if(data.registrationId != fcm){
                        fcm = data.registrationId;
                    }
            
                });
            
                push.on('notification', (response) => {
             
                    if (response.additionalData.coldstart) {
                    if(response.additionalData.redirect){
                        $('#page').attr('act-request', response.additionalData.redirect);
                        $('#page').trigger('load');
                    }
                    }else{
                        if(response.additionalData.toast){
                        if (response.message) {
                            $('#toast').addClass('show');
                            $('#toast .text').html(response.message);
                        
                            setTimeout(() => {
                                $('#toast').removeClass('show');
                                $('#toast .text').html('');
                            }, 3000);
                        }
                        }
                    }
                });
          }
          catch(err) { console.log(err) }


    }  , false); 


    if(build == 'pwa'){
        if (window.navigator.userAgent && window.history && window.history.pushState) {

            history.pushState(null, null, location.href);


            var count = 0
            var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);


            $(window).on('popstate', function() {
                count++;
                history.go(1);
                
                if (count % 2 === 1 && (isSafari || count > 2)) {
                    if( $('#page').attr('page-name') != 'home'){
                        if(gallery){
                            gallery.close();
                            gallery = false;
                        }else{
                            $('#page').trigger('load');
                        }   
                    }
                }
                
            });
        }
    }
     
}( jQuery ));