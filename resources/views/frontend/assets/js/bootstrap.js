try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    window.bootstrap = require('bootstrap');
    window.Swal = require('sweetalert2')
    window.cookies = require('js-cookie');
    require('jquery-zoom');
    require('select2');
    require('owl.carousel');
    require('owl.carousel2.thumbs');
 
} catch (e) {}

"use strict";
(function( $ ) {
    $.fn.act = function(options) {
        var act = {};
 
        var settings = $.extend({ 
            headers : {}, 
            respond : function( element, json){},
            init : function(element){}
        }, options);

        act.loading = function (element, status){
            if(status === true){
                $('body').addClass('loading');
                $(element).addClass('processing');
                $('[type="submit"]', element).prop('disabled', true);

            }else{
                $('body').removeClass('loading');
                $(element).removeClass('processing');
                $('[type="submit"]', element).prop('disabled', false);
            }
        }

        act.respond = function(element, json){

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
 
            settings.respond(element, json);

            if (json.alert) {
                // $('#toast .title').html(json.alert.title);
                $('#toast .toast-body .text').html(json.alert.text);
                $('.toast-alert').addClass('show')
                $('.toast-alert .toast').addClass(json.alert.icon)
                $('#toast').toast('show');
                $('#toast').on('hidden.bs.toast', function () {
                    if(json.alert.redirect){
                        window.location.href = json.alert.redirect;
                    } 
                    $('#toast .toast-body .text').html('');
                    $('.toast-alert').removeClass('show');
                    $('.toast-alert .toast').removeClass(json.alert.icon);
                });
            }  

            if (json.init) {
                $.each(json.init, function (index, element) {
                    $(element).act(settings);
                });
            }

            if (json.redirect) {
                window.location.href = json.redirect;
            }

            if (json.reload) {
                location.reload()
            }
        };

        act.request = function (element, url, data) {

            async function asyncCall(element, url, data) {

                if(!$(element).attr('act-loader') &&  $(element).attr('act-loader') != 'false'){
                    act.loading(element, true);
                }else{
                    $(element).removeAttr('act-loader')
                }
 
                act.loading(element, true);

                $.ajax({
                    headers : settings.headers,
                    url: url,
                    type: 'post',
                    data: data,
                    cache:false,
                    contentType: false,
                    processData: false
                }).done(function (json) {
    
                    act.respond(element, json);
    
                    act.loading(element, false);
    
                }).fail(function (jqXHR, textStatus) {  
    
                    if(jqXHR.status == 422){
                        act.respond(element, jqXHR.responseJSON);
                    }

                    act.loading(element, false);
                });
            }
            
            asyncCall(element, url, data);
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

        $('[act-on]', this).each(function(){
            let action =  $(this).attr('act-on');
 
            if(action == 'load'){
                act.on(this, action)
            }

            $(this).on(action, function(event){
                event.preventDefault();

                if( $(this).attr('act-confirm') ){

                    Swal.fire({
                        title: 'Are you sure?',
                        text: $(this).attr('act-confirm'),
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#039f90',
                        cancelButtonColor: '#f64e60',
                        confirmButtonText: 'Yes, Proceed'
                      }).then((result) => {
                            if(result.isConfirmed){
                                act.on(this, action)
                            }   
                      });

                }else{
                    act.on(this, action)
                }
                
            });
        });
        
        settings.init(this);
        act.loading(this, false);
        return act;       
    };

}( jQuery ));


