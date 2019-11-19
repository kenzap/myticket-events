/**
 * MyTicket created by Kenzap on 17/11/2018.
 */

jQuery(function ($) {
    "use strict";

    $(".kenzap .kenzap-container").show();
	
	$(".kpcae .kenzap-lg-carousel").owlCarousel({
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
	
	$(".kpcae .kenzap-md-carousel").owlCarousel({
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
	
	$(".kpcae .kenzap-sm-carousel").owlCarousel({
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
	
	$(".kpcae .kenzap-xs-carousel").owlCarousel({
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
	
	$('.kpcae .event-tabs li:first').addClass('active');
	$('.kpcae .tab-content .tab-pane:first').show();
	
	$('.kpcae .event-tabs li').click(function(){
		$('.kpcae .event-tabs li').removeClass('active');
		$(this).addClass('active');
		$('.kpcae .tab-content .tab-pane').hide();
	  
		var activeTab = $(this).find('a').attr('href');
		$(activeTab).show();
		return false;
    });
    
});