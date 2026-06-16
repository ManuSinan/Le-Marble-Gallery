require('./bootstrap');
"use strict";
window.Sidenav = require('./sidenav.min');
$(function() {
    if(app.config == null){
        app.config = {
            respond : function(act, element, json){
          
            },
            init : function(act, element){

                $('.sidenav-open', element).click(function () {
                    sidenav.open();
                });

                $('.sidenav-close', element).click(function () {
                    sidenav.close();
                });     

            }
        }
    }
 
    window.sidenav = new Sidenav({
        content: document.getElementById("page"),
        sidenav: document.getElementById("sidebar"),
        backdrop: document.getElementById("sidebar-loader")
    });

    $('html').act(app.config);
});
 
 

