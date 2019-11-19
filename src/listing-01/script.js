/**
 * MyTicket created by Kenzap on 17/11/2018.
 */

jQuery(function ($) {
    "use strict";
    
 
        $(".kenzap .kenzap-container").show();	
    
        // Textbox Clear
        $( ".hasclear" ).on('keyup', function( event ){
            var t = $(this);
            t.next('span').toggle(Boolean(t.val()));
        });

        $(".clearer").hide($(this).prev('input').val());

        // Price Range Slider
        var price_range = $("#price-range"); 
        var price_range_first = Math.round(+new Date()/1000);
        var product_pricing_low = 0;
        var product_pricing_high = 0
        if ( price_range.length ){

            product_pricing_low = price_range.data('slider-max');
            product_pricing_high = price_range.data('slider-min');

            price_range.slider({
                tooltip: 'always',
                tooltip_split: true,
                formatter: function(value) {

                    // looks like price is changing
                    if ( (Math.round(+new Date()/1000)-price_range_first) > 1){

                        // only refresh if price has new value, prevents mobile screen tap accidential refresh.
                        var temp = price_range.val().split(',');
                        if(temp[0]!=product_pricing_low || temp[1]!=product_pricing_high){

                            product_pricing_low = temp[0];
                            product_pricing_high = temp[1];
                            refreshProductPricing();
                        }
                    }
                    var c = "";
                    if ( $("#price-range").data('currency') )
                        var c = $("#price-range").data('currency') + ' ';

                    return c + value;
                },
            });
        }	
    
        // separating this listener
        function ajaxPagination(){

            $(".page-numbers").off();
            $(".page-numbers").on("click", function(){

                // pagination
                if ($(this).hasClass("page-numbers") && !$(this).hasClass("current")) { 
    
                    $(".page-numbers").removeClass("current");
                    $(this).addClass("current");
                    paged = $(this).data("page");
                    loadFilteredProducts(true);
                }
            });
        }

        $("a,section,div,span,li,input[type='text'],input[type='button'],input[type='checkbox'],input[type='submit'],tr,button").on("click", function(){
            
            // clean input fields
            if ($(this).hasClass("clearer")) { 
                $(this).prev('input').val('').focus();
                $(this).hide();
                loadFilteredProducts(true);
            }

            // button click
            if ($(this).hasClass("myticket-search-btn")) { 
        
                loadFilteredProducts(true);
                return false;
            }

            // sidebar category checkbox change
            if ($(this).hasClass("myticket-widget-category-checkbox")) { 
                loadFilteredProducts(true);
                return true;
            }
        });   
        
        if( $("#myticket-sorting").length > 0 ) {
            product_order = $("#myticket-sorting").data('active');
        }

        //select event binding
        $("select, button").on('change',function(e){

            if ( $(this).is("#myticket-sorting") ) {
                product_order = $(this).val();
                loadFilteredProducts(true);
            }else if ( $(this).is("#product-records") ) {
                per_page = parseInt($(this).val());
                loadFilteredProducts(true);
            }else if ( $(this).is("#myticket-location") ) {
                loadFilteredProducts(true);
            }else if ( $(this).is("#myticket-time") ) {
                loadFilteredProducts(true);
            }
            //alert("change");
        });

        refresh_rp_counters();

        var priceTimeout = null;
        function refreshProductPricing(){
   
            if( priceTimeout!= null ){
                clearTimeout( priceTimeout );
            }
            
            clearTimeout( priceTimeout );
            priceTimeout = window.setTimeout( 
                function() {   
                    loadFilteredProducts(true);
   
                }, 1000);

            //alert("price");
        }
       
        function refresh_rp_counters(){
            
            // refresh header counters	          
            if( $("#myticket_post_count").length > 0 ){

                $("#myticket_pcr").html($("#myticket_post_count").val());  	
                var to = parseInt($("#myticket_current_page").val()) * parseInt($("#myticket_max_page_records").val());
                var from = to - parseInt($("#myticket_max_page_records").val()) + 1;
                if ( to > $("#myticket_post_count").val() )
                    to = ( $("#myticket_post_count").val() );
                $("#myticket_prr").html( from + ' - ' + to );  
            }

            //refresh header text
            if( $("#myticket-sri-cont").length > 0 ){

                if( $(".myticket-search-value").length > 0 ){

                    var q = $(".myticket-search-value").val();
                    var l = $("#myticket-location").val();
                    $("#myticket-sri-cont").html( $("#myticket-sri-cont").data('search') + ' ' + q + ' ' + l );
                    if ( q == '' && l == '' ){
                        $("#myticket-sri-cont").html( $("#myticket-sri-cont").data('all') );
                    }
                }else{
                    $("#myticket-sri-cont").html( $("#myticket-sri-cont").data('all') );
                }
            }

            if( $("#myticket_post_count").length == 0 ){

                $("#myticket_prr").html( '0' );  
                $("#myticket_pcr").html( '0' );  
            }
        }

        setTimeout(function(){ loadFilteredProducts(true); },1000);

        var product_list = "list", paged = 1, per_page = 10, product_category = "", product_category_list = "", product_category2 = "", product_tag = "", product_calories_low = 0, product_calories_high = 100000000000000,  product_pricing_low = 0, product_pricing_high = 100000000000000, inRequest = false, product_columns = 4, product_order = "", pagenum_link = "";// product_cat = "";
        function loadFilteredProducts(clear_contents){
   
           inRequest = true;
           var $content = $('.myticket-content');
           var $loader = $('.search-result-cont');
           var ppp = per_page, color = 'red', size = 'S', price_low = '10', price_high = '1000';
           var offset = $('.grid-product-content .row').find('.item').length;
           pagenum_link = $content.data('pagenum_link');
           var pagination = $content.data('pagination');
           product_list = $content.data('list_style');
           $content.fadeTo( "normal", 0.33 );
           var noposts = "Nothing to display";
   
           if ( price_range.length ){
               var temp = price_range.val().split(',');
               product_pricing_low = temp[0];
               product_pricing_high = temp[1];
           }
   
           var product_category_list = '';
           $( ".myticket-widget-category-checkbox" ).each(function( index ) {
               
               if ($(this).is(':checked'))
                   product_category_list += $( this ).data('category')+",";
           });
   
           if (typeof $content.data('category') != 'undefined')
               if ( $content.data('category').length > 0 )
                   product_category_list += $content.data('category')+",";
   
           //cache settings if page will be refreshed
           createCookie("product_category_list", product_category_list, 1);
           createCookie("events_per_page", $content.data('events_per_page'), 1);
           createCookie("events_relation", $content.data('relation'), 1);
           createCookie("offset", offset, 1);
           createCookie("product_list", product_list, 1);
           createCookie("product_order", product_order, 1);
           createCookie("search_value", $('.myticket-search-value').val(), 1);
           createCookie("search_location", $("#myticket-location").val(), 1);
           createCookie("product_tag", product_tag, 1);
           createCookie("product_calories_low", product_calories_low, 1);
           createCookie("product_calories_high", product_calories_high, 1);
           createCookie("product_pricing_low", product_pricing_low, 1);
           createCookie("product_pricing_high", product_pricing_high, 1);
           createCookie("product_columns", product_columns, 1);
           createCookie("pagenum_link", pagenum_link, 1);
   
           //perform ajax request
           $.ajax({
               type: 'POST',
               dataType: 'html',
               url: $content.data('ajaxurl'),
               data: {
                 'cat': product_category,
                 'product_category_list': product_category_list,
                 'offset': offset,
                 'product_order': product_order,
                 'product_type': $content.data('type'),
                 'paged': paged,
                 'pagenum_link': $content.data('pagenum_link'),
                 'events_per_page': $content.data('events_per_page'),
                 'events_relation': $content.data('relation'),
                 'search_value': $('.myticket-search-value').val(),
                 'search_location': $("#myticket-location").val(),
                 'search_time': $("#myticket-time").val(),
                 'product_list': product_list,
                 'product_tag': product_tag,
                 'product_pricing_low': product_pricing_low,
                 'product_pricing_high': product_pricing_high,
                 'product_columns': product_columns,
                 'pagenum_link': pagenum_link,
                 'pagination': $content.data('pagination'),
                 'action': 'myticket_filter_list_ajax'
               },
               beforeSend : function () {
   
               },
               success: function (data) {
                 var $data = $(data);
                 if(clear_contents) {
                     $loader.empty();
                 }
                 if ($data.length) {
                   var $newElements = $data.css({ opacity: 0 });
                   $loader.append($newElements);
                   $newElements.animate({ opacity: 1 });
                   $('.load-more-loader').hide(); 
                   $('.btn-load-more').show(); 
                 } else {
                   $loader.html('<div style="text-align:center;letter-spacing:0.1em;margin-top:20px;">'+noposts+'</div>');
                   $('.load-more-loader').hide(); 
                   
                 }
                 inRequest = false;
                 $content.fadeTo( "fast", 1 );
                 refresh_rp_counters();
                 ajaxPagination();

                 return false;
               },
               error : function (jqXHR, textStatus, errorThrown) {
                 $('.load-more-loader').hide(); 
                 $('.btn-load-more').show();
                 inRequest = false;
                 $content.fadeTo( "fast", 1 );
               },
           });
           return false;
        }  

        function createCookie(name, value, days) {
            var expires;
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            } else {
                expires = "";
            }
            document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
        }
   
        function readCookie(name) {

            var nameEQ = encodeURIComponent(name) + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        }
});