/**
 * MyTicket Hall Layout created by Kenzap on 14/02/20189.
 */

jQuery(function ($) {
	"use strict";
	
	var kenzap_hall_layout_js = '';
	var tickets_global = [];
	var myticketUserId = "";
	var current_zone_id = -1;

	// init ajax call timer
	var myticketCalls = "";

	$(function() {

		if($(".kenzap-hall-layout").length){

			myticketCalls = setInterval(checkReservations, 10000, true);

			// if product just added redirect to cart
			if(window.location.search.indexOf("add-to-cart") !== -1){

				var href = $(".kenzap-hall-layout").data('checkouturl');
				location.href = href;
			}
			
			// add tickets to cart listener
			$(".kp-btn-reserve").on("click", function(){

				setReservations();
				return false;
			});

			// generate unique user id valid during booking only
			if(getCookie("myticket_user_id")!=''){
				myticketUserId = getCookie("myticket_user_id");
			}else{
				myticketUserId = makeid();
				createCookie("myticket_user_id",myticketUserId,1);
			}

			// reserved but not yet booked ticket list
			if(getCookie("tickets")!='')
				tickets_global = JSON.parse(getCookie("tickets"));

			// get layout code from html sript
			kenzap_hall_layout_js = JSON.parse(kenzap_hall_layout);
			var kp_svg = $(".kp_svg");
			var i = 0;

			// get backend stored data
			checkReservations();

			// set layout picture
			$("#myticket_img").attr("src",kenzap_hall_layout_js.img);
			$("#myticket_img").load(function() {

				// overlay image with polygons
				var cp = kenzap_hall_layout_js.areas.map(function(item) {

					// generate DOM elements
					var g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
					var polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');

					// find scale factor
					var polygon_scale = kenzap_hall_layout_js.img_width / parseInt($(".kenzap-hall-layout").data("dwidth"));

					// set up layout proportions with the browsers screen
					var mwidth = $("#myticket_img").width();
					var mheight = $("#myticket_img").height();
					$("#kp_image").css("width", mwidth);
					$("#svg").css("width", mwidth);
					$("#svg").css("height", mheight);
					polygon_scale = kenzap_hall_layout_js.img_width / parseInt(mwidth);

					// draw zone overlay polygon 
					polygon.setAttribute('points', item.coords.points.map(function(item) { return item.x / polygon_scale + " " + item.y / polygon_scale;}));
					polygon.setAttribute('data-index', i);
					polygon.setAttribute('id', "pl"+i);
					g.appendChild(polygon);
					kp_svg.append(g);
					i++;
			
					return item;
				});

				// add layout zone seat preview event
				$("polygon").on("click", function(){

					showSeatSelection($(this).data("index"), kenzap_hall_layout_js);
				});

				refreshSelectedTicket(tickets_global, kenzap_hall_layout_js, -1, -1);

			});
		}
	});

	function showSeatSelection(index, halls){

		$("body").prepend($("#seat_mapping").clone().addClass("seat_mapping_temp"));

		var svg_mapping = $("#svg_mapping");
		var svg_width = $(window).width()-200;
		var svg_height = $(window).height()-200;
		var svg_min_width = parseInt($(".kenzap-hall-layout").data("sminwidth"));
		var svg_max_height = parseInt($(".kenzap-hall-layout").data("smaxwidth"));

		if(svg_width<svg_min_width)
			svg_width = svg_min_width;
		if(svg_width>svg_max_height)
			svg_width = svg_max_height;
		if(svg_height<600)
			svg_height = 600;
		
		//svg_width = 1000;
		current_zone_id = index;

		$("#seat_mapping").fadeIn();
		svg_mapping.html("");

		// get central point 
		var x = 0, y = 0, xc = 0, yc = 0, i = 0;
		var cp = halls.areas[index].coords.points.map(function(item) {

			i++; x += item.x; y += item.y;
			return item;
		});

		// calc all x and y coords separately. Divide by the total amount of coords to find central point
		xc = x / i;
		yc = y / i;

		// get relative distance from coords to center point
		var il = 0, yl = 0, xl = 0, max_times = 1;
		halls.areas[index].coords.points_rel = [];
		cp = halls.areas[index].coords.points.map(function(item) {

			var temp = Math.abs(xc - item.x);

			// find longest coordinates
			temp = Math.abs(xc - item.x);
			xl = temp > xl ? temp : xl;
			temp = Math.abs(yc - item.y);
			yl = temp > yl ? temp : yl;
			
			// store central points
			halls.areas[index].coords.points_rel.push({x : item.x - xc, y : item.y - yc});
		}); 

		// detect how many times original poligon can be enlarger
		svg_mapping.css("width",svg_width);
		svg_mapping.css("height",svg_height);

		// get max scalability index
		var max_x = (svg_width/2) / xl;
		var max_y = (svg_height/2) / yl;
		max_times = max_x < max_y ? max_x : max_y; 

		// generate scaled polygon points
		max_x = 0; max_y = 0;
		var max_x_prev = 0, max_y_prev = 0, max_first = true;
		var polygonPointsAttrValue = halls.areas[index].coords.points_rel.map(function(item) {

			var px = item.x * max_times + (svg_width/2);
			var py = item.y * max_times + (svg_height/2);

			if ( !max_first ){
				max_x += max_x_prev * item.y * max_times;
				max_y += max_y_prev * item.x * max_times;
			}

			max_x_prev = item.x * max_times;
			max_y_prev = item.y * max_times;

			max_first = false;
			return px + " " + py;
		}).join(' ');

		// generate DOM elements
		var g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
		var polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
		polygon.setAttribute('points', polygonPointsAttrValue);
		g.appendChild(polygon);
		svg_mapping.append(g);

		// calculate polygon square footage https://www.wikihow.com/Calculate-the-Area-of-a-Polygon
		var sf = Math.round(Math.abs(max_y - max_x)) / 2;

		// generate draggable seats with accordance to square footage size
		var tws = 0;
		if (halls.areas[index].seats){
			tws = halls.areas[index].seats.tws;
			var height = Math.sqrt(sf / tws);
			var height_slider = halls.areas[index].seats.height;
			height *= (parseInt(height_slider) / 100);

			var li = "";
			i = 0;
			while (i < tws){

				var g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
				g.setAttribute('id', "dc"+i);
				g.setAttribute('data-index', i);
				var circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
				circle.setAttribute('id', "cr"+i);
				circle.setAttribute('cx', height/2);
				circle.setAttribute('cy', height/2);
				circle.setAttribute('r', height/2);
				circle.setAttribute('class', "cr");
				circle.setAttribute('data-index', i);


				var text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
				text.setAttribute('id', "tx"+i);
				text.setAttribute('x', height/2);
				text.setAttribute('y', height/2);
				text.setAttribute('dy', ".3em");
				text.setAttribute('stroke-width', "1px");
				text.setAttribute('text-anchor', "middle");
				text.setAttribute('stroke', "#000");
				text.setAttribute('class', "tx");
				text.setAttribute('data-index', i);

				text.setAttribute('data-toggle', "popover");
				text.setAttribute('title', "Seat Settings");
				text.setAttribute('data-content', "test");

				text.innerHTML = i+1;

				// get saved seat records and align them accordingly
				if ( !halls.areas[index].seats.points ){
					halls.areas[index].seats.points = [];
				}

				if ( halls.areas[index].seats.points[i] ){

					var x = (halls.areas[index].seats.points[i].x) * max_times + (svg_width/2);
					var y = (halls.areas[index].seats.points[i].y) * max_times + (svg_height/2);
					circle.setAttribute('cx', x);
					circle.setAttribute('cy', y);

					text.setAttribute('x', x);
					text.setAttribute('y', y);

					if(halls.areas[index].seats.points[i].t)
						text.innerHTML = halls.areas[index].seats.points[i].t;
				}

				g.appendChild(circle);
				g.appendChild(text);
				g.obj = this;
				svg_mapping.append(g);
				i++;
			}
		}

		$("#seat_mapping_close").on("click", function(){

			$("#seat_mapping").fadeOut();
			$(".seat_mapping_temp").remove();
			current_zone_id = -1;
		});

		$(".cr, .tx").on("click", function(){

			var ticket_id = $(this).data("index");

			if($("#cr"+ticket_id).hasClass("booked"))
				return;

			// add ticket
			if(!$("#cr"+ticket_id).hasClass("reserved")){
	
				if(tickets_global.length>parseInt($(".kenzap-hall-layout").data("ticketspbooking"))){
					alert($(".kenzap-hall-layout").data("ajax_max_tickets"));
					return;
				}

				var ticket_text = parseInt(ticket_id)+1;
				var ticket_row = '';
				var zone_text = halls.areas[index].seats.title;

				if(halls.areas[index].seats.points[ticket_id].t)
					ticket_text = halls.areas[index].seats.points[ticket_id].t;
					
				if(halls.areas[index].seats.points[ticket_id].r)
					ticket_row = halls.areas[index].seats.points[ticket_id].r;

				tickets_global.push({zone_id : index, zone_text:zone_text, ticket_id : ticket_id, ticket_text: ticket_text, ticket_row: ticket_row });

			//remove ticket
			}else{

				$("#cr"+ticket_id).removeClass("reserved");
				tickets_global = jQuery.grep(tickets_global, function(value) {
					return !(value.ticket_id == ticket_id && value.zone_id == index);
				});
			}

			refreshSelectedTicket(tickets_global, halls, index, ticket_id);

			// mark reserved seats
			markReservations(halls, index);
		});

		// preload default selections
		refreshSelectedTicket(tickets_global, halls, index, -1);

		// mark reserved seats
		markReservations(halls, index);

		// scroll button listeners
		$(".kp-prev").on("click",function(){ $('#svg_mapping_cont').animate( { scrollLeft: '+=180' }, 500); });
		$(".kp-next").on("click",function(){ $('#svg_mapping_cont').animate( { scrollLeft: '-=180' }, 500); });
	}

	function markReservations(halls, zone_id){

		// mark booked seats for current zone
		$(".cr").removeClass("booked");
		zone_id = current_zone_id;
	
		var tws = 0;
		if (halls.areas[zone_id])
		if (halls.areas[zone_id].seats){
			
			tws = halls.areas[zone_id].seats.tws;
			var i = 0;
			while (i < tws){

				if(reservations[zone_id+"_"+i]){

					if(reservations[zone_id+"_"+i]["user"]!=myticketUserId){

						// mark as booked visually
						$("#cr"+i).addClass("booked");
						$("#tx"+i).addClass("booked");
						
					}
				}
				i++;
			}
		}

		// mark booked zones if no free tickets left
		for (var i = 0; i < halls.areas.length; i++) {

			var tws = 0;
			if (halls.areas[i].seats){
				tws = halls.areas[i].seats.tws;
				var e = 0, ec = 0;
				while (e < tws){

					if(reservations[i+"_"+e])
						ec++;

					e++;
				}

				if(ec==tws){
					$("#pl"+i).addClass("booked");
				}else{
					$("#pl"+i).removeClass("booked");
				}
			}
		}
	}

	function refreshSelectedTicket(tickets, halls, index, ticket_id){

		createCookie("tickets",JSON.stringify(tickets),1);
		$(".selected_seats").html("");

		var kp_ticket_rows = '';
		var output = tickets.map(function(item) {

			var ticket_row = '-';
			var price = $(".kenzap-hall-layout").data("price");
			if(!halls.areas[item.zone_id].seats)
				return "";

			if(halls.areas[item.zone_id].seats.points[item.ticket_id])
			if(halls.areas[item.zone_id].seats.points[item.ticket_id].r)
				ticket_row = halls.areas[item.zone_id].seats.points[item.ticket_id].r;

			var ticket_id = item.ticket_id;
			var ticket_text = ticket_id+1;
			if(halls.areas[item.zone_id].seats.points[ticket_id])
			if(halls.areas[item.zone_id].seats.points[ticket_id].t)
				ticket_text = halls.areas[item.zone_id].seats.points[ticket_id].t;
				
			kp_ticket_rows += '\
			<tr class="select-seat">\
				<td>'+ticket_text+' <span>'+$(".kenzap-hall-layout").data('zone')+' <b>'+halls.areas[item.zone_id].seats.title+'</b></span>\
				<span class="m-row">'+ $(".kenzap-hall-layout").data('row') + ' <b>'+ticket_row+'</b></span>\
				<span class="m-row">'+price+' '+$(".kenzap-hall-layout").data('perseat')+'</span>\
				</td>\
				<td>'+ticket_row+'</td>\
				<td>'+price+' <span>'+$(".kenzap-hall-layout").data('perseat')+'</span></td>\
				<td data-zone="'+item.zone_id+'" data-index="'+ticket_id+'" class="kp-rem-seat">&times;</td>\
			</tr>';

			if(item.zone_id == index) {

				$("#cr"+ticket_id).addClass("reserved");
				return '<span class="st" data-index="'+ticket_id+'" >' + ticket_text + '</span>';
			}else{
				return "";
			}
		
		});

		if(kp_ticket_rows==''){
			$(".kp-btn-reserve,.kp-table").fadeOut(0);
		}else{
			$(".kp-btn-reserve,.kp-table").fadeIn();
		}

		$(".selected_seats").html(output);
		if(output!=""){$(".sel_texts").fadeOut(0);}else{$(".sel_texts").fadeIn(0);}
		$(".kp-ticket-row").html(kp_ticket_rows);

		// refresh listeners
		$(".kp-rem-seat").on("click", function(){

			var indexx = $(this).data("index");
			var zone = $(this).data("zone");

			tickets_global = jQuery.grep(tickets, function(value) {
				return !(value.ticket_id == indexx && value.zone_id == zone);
			});

			refreshSelectedTicket(tickets_global, halls, indexx, -1);
		}); 
	}

	var reservations = [];
	function setReservations() {

		//perform ajax request
	    $.ajax({
			type: 'POST',
			dataType: 'json',
			url: $(".kenzap-hall-layout").data("ajax"),
			data: {
				'id': $(".kenzap-hall-layout").data("id"),
				'action': 'myticket_events_set_reservations',
				'tickets': tickets_global,
				'user_id': myticketUserId
			},
			beforeSend : function () {

			},
			success: function (data) {

				var $data = $(data);
				if ($data.length) {

					if(data.success){
						
						// check if all reservations were successfull 
						if(data.notreserved){

							alert($(".kenzap-hall-layout").data('ajax_booked'));

							for (var i = 0; i < data.notreserved.length; i++){
								tickets_global = jQuery.grep(tickets_global, function(value) {
									return !(value.zone_id +"_"+ value.ticket_id == data.notreserved[i]);
								});
							} 

							refreshSelectedTicket(tickets_global, kenzap_hall_layout_js, -1, -1);
							
							//immidiately refresh current list
							checkReservations();
						}else{

							// finalize ticket reservation
							//var href = $(".kenzap-hall-layout").data('carturl')+'?quantity='+tickets_global.length+'&add-to-cart='+$(".kenzap-hall-layout").data('id');
							var href = '?quantity='+tickets_global.length+'&add-to-cart='+$(".kenzap-hall-layout").data('id');
							location.href = href;
						}

					}else{

						alert($(".kenzap-hall-layout").data('ajax_error')+" "+(data.reason)?data.reason:"");  
					}

				}else{
		
				}
			},
			error : function (jqXHR, textStatus, errorThrown) {

				alert($(".kenzap-hall-layout").data('ajax_error'));  
			},
		});
	}

	function checkReservations() {

	    //perform ajax request
	    $.ajax({
			type: 'POST',
			dataType: 'json',
			url: $(".kenzap-hall-layout").data("ajax"),
			data: {
			'id': $(".kenzap-hall-layout").data("id"),
			'action': 'myticket_events_get_reservations',
			'user_id': myticketUserId
			},
			beforeSend : function () {

			},
			success: function (data) {
				var $data = $(data);
				if ($data.length) {

					if(data.success){

						reservations = data.data;
						markReservations(kenzap_hall_layout_js, current_zone_id);
					}
				
				} 
			},
			error : function (jqXHR, textStatus, errorThrown) {

			},
		});
		return false;
	}

	function makeid() {

		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		for (var i = 0; i < 5; i++)
		  text += possible.charAt(Math.floor(Math.random() * possible.length));
	  
		return text;
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

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
		  var c = ca[i];
		  while (c.charAt(0) == ' ') {
			c = c.substring(1);
		  }
		  if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		  }
		}
		return "";
	}

});