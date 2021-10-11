/**
 * MyTicket created by Kenzap on 12/03/2019.
 */

jQuery(function ($) {
    "use strict";


	$(function() {

		if($(".kp-mchmt").length){

			// if product just added redirect to cart
			if(window.location.search.indexOf("add-to-cart") !== -1){

				var href = $(".kp-mchmt").data('url');
				if(href!='') location.href = href;
			}
		}
	});
});