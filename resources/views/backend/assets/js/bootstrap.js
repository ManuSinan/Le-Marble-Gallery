try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    window.bootstrap = require('bootstrap5');
    window.Swal = require('sweetalert2')
    window.Inputmask = require('inputmask');
    window.ApexCharts = require('apexcharts');
    require( 'datatables.net-bs4' );
    require( 'datatables.net-buttons-bs4' );       
    require( 'datatables.net-responsive-bs4' ); 
    require('select2');
    require('jquery.repeater');
    require('bootstrap-datepicker');
    window.Compressor = require('compressorjs');
    window.ClassicEditor = require('@ckeditor/ckeditor5-build-classic');
 
} catch (e) {}

"use strict";
(function( $ ) {
 
    $.fn.act = function(options) {
        var act = {};
        
        var settings = $.extend({ 
            headers : {}, 
            respond : function(act, element, json){},
            init : function(act, element, json){}
        }, options);

        act.loading = function (element, status){
            if(status === true){

                $(element).addClass('processing');
                $('[type="submit"]', element).prop('disabled', true);

            }else{

                $(element).removeClass('processing');
                $('[type="submit"]', element).prop('disabled', false);
            }
        }

        act.respond = function(element, json){
 
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
 
            settings.respond(act,element, json);

            if (json.alert) {
                let swalconfig = {
                    position: 'center',
                    showConfirmButton: false,
                    timer: 2000,
                }

                Object.assign(swalconfig, json.alert)

                Swal.fire(swalconfig).then(function(result) {
                    if(json.alert.redirect){
                        window.location.href = json.alert.redirect;
                    } 
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

            function compress(file) {
                return new Promise((resolve, reject) => {
                   new Compressor(file, {
                     quality: 1,
                     success: resolve,
                     error: reject
                  });
               });
            }
         
            async function asyncCall(element, url, data) {

                var imageCompress = $(element).attr('act-image-compress');
                if(imageCompress){

                    if(imageCompress.includes(', ')){
                        var images = imageCompress.split(', ');
                    }else{
                        var images = [];
                        images.push( imageCompress );
                    }

                    async function asyncForEach(array, callback) {
                        for (let index = 0; index < array.length; index++) {
                          await callback(array[index], index, array);
                        }
                    }
    
                    await asyncForEach(images, async (item, index) => {
                        var imageData = data.get(item);
                        if(imageData.name){
                            let result = await compress(imageData);
                            data.delete(item)
                            data.append(item, result, result.name);
                        }
                    })
                }
 
                act.loading(element, true);
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
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
    
                    if($(element).attr("act-respond") && $(element).attr("act-request")){ 
                        let respond = $(element).attr('act-respond');  
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
                    
                    act.loading(element, false);
                });
            }
            
            asyncCall(element, url, data);
        };

        act.on = function(element, action){
            let data = false;
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
                
                let actWith = $(element).attr("act-with");

                $(element).closest('[act-group="' + actWith + '"]').find('[act-related="' + actWith + '"]').each(function(){
                    if( $(element).attr('name') != $(this).attr('name') ){
                        data.append('related[' + $(this).attr('name') + ']', $(this).val());
                    }
                });
            }

            if($(element).attr("act-request")){     
                let request = $(element).attr('act-request');
                act.request(element, request, data)
            }

            if($(element).attr("act-respond") && !$(element).attr("act-request")){      
                let respond = $(element).attr('act-respond');  
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
                        confirmButtonColor: '#663259',
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

        settings.init(act, this);
    };
 
}( jQuery ));