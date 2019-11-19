/**
 * MyTicket created by Kenzap on 17/11/2018.
 */

jQuery(function ($) {
    "use strict";
	
	if($(".kpfes").length){
		loadFilteredProducts(true);
	}
	
	function refresh_owl(){

		$(".kpfes .kenzap-lg-carousel").owlCarousel({
				autoplay: false,
				margin: 0,
				nav: true,
				mouseDrag: false,
				dots: false,
				responsive: {
					0:{
						items:1,
						slideBy: 1
					}, 
					320:{
						items:1,
						slideBy: 1
					},
					480:{
						items:2,
						slideBy: 2
					}, 
					600:{
						items:3,
						slideBy: 3
					},            
					768:{
						items:4,
						slideBy: 4
					},
					991:{
						items:6,
						slideBy: 6
					},
					1200:{
						items:7,
						slideBy: 7
					}
				}
			});
		
		$(".kpfes .kenzap-md-carousel").owlCarousel({
			autoplay: false,
			margin: 0,
			nav: true,
			mouseDrag: false,
			dots: false,
			responsive: {
				0:{
					items:1,
					slideBy: 1
				}, 
				320:{
					items:1,
					slideBy: 1
				},
				480:{
					items:2,
					slideBy: 2
				}, 
				600:{
					items:3,
					slideBy: 3
				},            
				768:{
					items:4,
					slideBy: 4
				},
				991:{
					items:6,
					slideBy: 6
				},
			}
		});
		
		$(".kpfes .kenzap-sm-carousel").owlCarousel({
			autoplay: false,
			margin: 0,
			nav: true,
			mouseDrag: false,
			dots: false,
			responsive: {
				0:{
					items:1,
					slideBy: 1
				}, 
				480:{
					items:2,
					slideBy: 2
				}
			}
		});
		
		$(".kpfes .kenzap-xs-carousel").owlCarousel({
			autoplay: false,
			margin: 0,
			nav: true,
			mouseDrag: false,
			dots: false,
			responsive: {
				0:{
					items:1,
					slideBy: 1
				}, 
				320:{
					items:1,
					slideBy: 1
				}
			}
		});
	
		// relocate elements to proper hierarhy
		$(".tab-pane").each(function( index ) {

			var first = true;

			var col3 = '';
			$(this).find(".kenzap-col-3").each(function( index ) { col3 += $( this ).find('ul').html(); if(!first) $( this ).remove(); first = false;  });
			$(this).find(".kenzap-col-3:first-child ul").html(col3);

			var col9 = ''; first = true;
			$(this).find(".kenzap-col-9").each(function( index ) { col9 += $( this ).html(); if(!first) $( this ).remove(); first = false; });
			$(this).find(".kenzap-col-9").html(col9);
		});

		$('.kpfes .event-tabs li:first').addClass('active');
		$('.kpfes .event-tab-content .tab-pane:first').show();
		
		$('.kpfes .event-tabs li').click(function(){
			$('.kpfes .event-tabs li').removeClass('active');
			$(this).addClass('active');
			$('.kpfes .event-tab-content > .tab-pane').hide();
			$('.kpfes .schedule-tab-content > .tab-pane').hide();
			$('.kpfes .schedule-tab-content .tab-pane:first-child').show();
			$('.kpfes .schedule-tabs > li').removeClass('active');
			$('.kpfes .schedule-tabs li:first-child').addClass('active');
			
			var activeTab = $(this).find('a').attr('href');
			$(activeTab).show();
			return false;
		});

		
		$('.kpfes .schedule-tabs li:first-child').addClass('active');
		$('.kpfes .schedule-tab-content .tab-pane:first-child').show();
		
		$('.kpfes .schedule-tabs > li').click(function(){

			$('.kpfes .schedule-tabs > li').removeClass('active');
			$(this).addClass('active');
			$('.kpfes .schedule-tab-content > .tab-pane').hide();
			
			var activeTab = $(this).find('a').attr('href');
			$(activeTab).show();
			return false;
		});
	}
	
	$("a,section,div,span,li,input[type='text'],input[type='button'],input[type='submit'],tr,button").on("click", function(){
           
		if ($(this).hasClass("clearer-04")) { 
		   $(this).prev('input').val('').focus();
		   $(this).hide();
		   loadFilteredProducts(true);
		}

		if ($(this).hasClass("myticket-search-btn-04")) { 
	   
		   loadFilteredProducts(true);
		   return false;
		}
	});   

	//select event binding
	$("select, button").on('change',function(e){

		if ( $(this).is("#myticket-location-04") ) {
			loadFilteredProducts(true);
		}else if ( $(this).is("#myticket-time-04") ) {
			loadFilteredProducts(true);
		}
	});

	// Textbox Clear
	$( ".hasclear" ).on('keyup', function( event ){
		var t = $(this);
		t.next('span').toggle(Boolean(t.val()));
	});

	$(".clearer-04").hide($(this).prev('input').val());

		  
	$('.clearer-04').click(function(){
		$(this).prev('input').val('').focus();
			$(this).hide();
	});
	
	var product_list = "list", per_page = 10, product_category = "", product_category_list = "", product_category2 = "", product_tag = "", product_calories_low = 0, product_calories_high = 100000000000000,  product_pricing_low = 0, product_pricing_high = 100000000000000, inRequest = false, product_columns = 4, product_order = "", pagenum_link = "";// product_cat = "";
	function loadFilteredProducts(clear_contents){

	   inRequest = true;
	   var $content = $('.schedule-content');
	   var $loader = $('.search-result-cont');
	   //var ppp = per_page, color = 'red', size = 'S', price_low = '10', price_high = '1000';
	   var offset = $('.grid-product-content .row').find('.item').length;
	   pagenum_link = $content.data('pagenum_link');
	   var pagination = $content.data('pagination');
	   product_list = $content.data('list_style');
		 $content.fadeTo( "normal", 0.33 );
		 var noposts = "Nothing to display";

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
			 'events_per_page': $content.data('events_per_page'),
			 'events_relation': $content.data('relation'),
			 'search_value': $('.myticket-search-value').val(),
			 'search_location': $("#myticket-location-04").val(),
			 'search_time': $("#myticket-time-04").val(),
			 'pagenum_link': pagenum_link,
			 'pagination': $content.data('pagination'),
			 'sizes': $content.data('sizes'),
			 'maxwidth': $content.data('maxwidth'),
			 'action': 'myticket_filter_list_ajax2'
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
			 refresh_owl();
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

});