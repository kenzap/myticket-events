<?php
function myticket_hexToRgb($hex, $alpha = 0.5)
{
	$hex      = str_replace('#', '', $hex);
	$length   = strlen($hex);
	$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
	$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
	$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
	$rgb['a'] = $alpha;
	return 'rgba(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ',' . $alpha . ')';
}

ob_start();

require_once MYTICKET_PATH . 'src/commonComponents/container/container-cont.php';

$ajaxurl = '';
if (in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins'))) {
	$ajaxurl .= admin_url('admin-ajax.php?lang=' . ICL_LANGUAGE_CODE);
} else {
	$ajaxurl .= admin_url('admin-ajax.php');
}

$eids = explode("\n",$attributes['eventID']); 
$eid = isset($_GET['eid'])?$_GET['eid']:$eids[0];
$carturl = '';
$checkouturl = '';
$general_price = '';
$product = '';
if (class_exists('WooCommerce')) {

	$product = wc_get_product($eid);
	$carturl = wc_get_cart_url();
	$checkouturl = wc_get_checkout_url();
}

// get current user
$user = wp_get_current_user();

if ($attributes['serverSide']) {

?><img src="<?php echo MYTICKET_URL . 'assets/block_preview-05.jpg'; ?>" alt="<?php echo esc_attr__('block preview', 'myticket-events'); ?>" />
	<div style="font-size:11px;">
		<?php if (class_exists('WooCommerce')) { ?>
			<div><?php echo esc_html__('Note: Image above is for representation purpose only. Click Update to preview changes on your website frontend. To create new layout please visit this page: https://kenzap.com/myticket/. Generate the layout and then paste it into Layout > Seat Code section on the right.', 'myticket-events'); ?></div>
		<?php } else { ?>
			<div><?php echo esc_html__('Important! Please make sure that WooCommerce and CMB2 plugins are installed and activated.', 'myticket-events'); ?></div>
		<?php } ?>
	</div><?php

		} else {
			
			if($eid==""){ echo esc_html__('Please provide at least one product ID under General > Product IDs input field.', 'myticket-events'); }
		?>

	<div class="kenzap-hall-layout <?php if (isset($_GET['add-to-cart'])) echo 'dn '; if ($attributes['align']) echo "align" . $attributes['align'] . " ";
									echo esc_attr($attributes['className']); ?> <?php if ($attributes['showArrows']) echo "kp-arrows"; ?>" data-time="<?php echo esc_attr($_GET['time']); ?>" data-admin="<?php echo in_array('administrator', (array) $user->roles); ?>" data-id="<?php echo esc_attr($eid); ?>" data-switch_date="<?php echo esc_attr__('When switching to other date current reservations are removed. Confirm?', 'myticket-events'); ?>" data-tickets_left="<?php echo esc_attr__('tickets left', 'myticket-events'); ?>" data-zone="<?php echo esc_attr__('Zone', 'myticket-events'); ?>" data-row="<?php echo esc_attr__('Row:', 'myticket-events'); ?>" data-perseat="<?php echo esc_attr__('per ticket', 'myticket-events'); ?>" data-cancelsel="<?php echo esc_attr__('Cancel selections?', 'myticket-events'); ?>" data-rendertype="<?php echo esc_attr($attributes['renderType']); ?>" data-seatmode="<?php echo esc_attr($attributes['seatMode']); ?>" data-snsize="<?php echo esc_attr($attributes['snSize']); ?>" data-numopacity="<?php echo esc_attr($attributes['numOpacity']); ?>" data-numopacity2="<?php echo esc_attr($attributes['numOpacity2']); ?>" data-hidenumbers="<?php echo esc_attr($attributes['hideNumbers']); ?>" data-dwidth="<?php echo esc_attr($attributes['dwidth']); ?>" data-mwidth="<?php echo esc_attr($attributes['mwidth']); ?>" data-sminwidth="<?php echo esc_attr($attributes['sminwidth']); ?>" data-ticketspbooking="<?php echo esc_attr($attributes['ticketsPerBooking']); ?>" data-smaxwidth="<?php echo esc_attr($attributes['smaxwidth']); ?>" data-cur_symb="<?php if ($product != '') {
																																																																																																																																																																																																																																																																																																																				echo get_woocommerce_currency_symbol();
																																																																																																																																																																																																																																																																																																																			} ?>" data-cur_pos="<?php if ($product != '') {
																																																																																																																																																																																																																																																																																																																																									echo get_option('woocommerce_currency_pos');
																																																																																																																																																																																																																																																																																																																																								} ?>" data-price="<?php if ($product != '') {
																																																																																																																																																																																																																																																																																																																																																																echo $product->get_price();
																																																																																																																																																																																																																																																																																																																																																															} ?>" data-ajax_max_tickets="<?php echo esc_attr__('Not allowed to add more tickets', 'myticket-events'); ?>" data-ajax_booked="<?php echo esc_attr__('Oops. Some seats were already booked. Please try again!', 'myticket-events'); ?>" data-id="<?php echo esc_attr($eid); ?>" data-carturl="<?php echo esc_url($carturl); ?>" data-checkouturl="<?php echo esc_url($checkouturl); ?>" data-ajax_error="<?php echo esc_attr__('Oops. Something went wrong. Please try again later.', 'myticket-events'); ?>" data-ajax="<?php echo esc_attr($ajaxurl); ?>" style="--mc:<?php echo esc_attr($attributes['mainColor']); ?>;--avc:<?php echo esc_attr($attributes['availableColor']); ?>;--avc2:<?php echo esc_attr(myticket_hexToRgb($attributes['availableColor'], '0.5')); ?>;--soc:<?php echo esc_attr($attributes['soldoutColor']); ?>;--soc2:<?php echo esc_attr(myticket_hexToRgb($attributes['soldoutColor'], '0.5')); ?>;--stcol:<?php echo esc_attr($attributes['seatsColor']); ?>;--sec:<?php echo esc_attr($attributes['selectedColor']); ?>;--sec2:<?php echo esc_attr(myticket_hexToRgb($attributes['selectedColor'], '0.5')); ?>; <?php echo ($kenzapStyles); //escaped in src/commonComponents/container/container-cont.php 
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			?>">

		<div id="seat_mapping" style="--mc:<?php echo esc_attr($attributes['mainColor']); ?>;--avc:<?php echo esc_attr($attributes['availableColor']); ?>;--avc2:<?php echo esc_attr(myticket_hexToRgb($attributes['availableColor'], '0.5')); ?>;--soc:<?php echo esc_attr($attributes['soldoutColor']); ?>;--soc2:<?php echo esc_attr(myticket_hexToRgb($attributes['soldoutColor'], '0.5')); ?>;--sec:<?php echo esc_attr($attributes['selectedColor']); ?>;--sec2:<?php echo esc_attr(myticket_hexToRgb($attributes['selectedColor'], '0.5')); ?>;">

			<div id="top_toolbar">
				<div id="seat_mapping_close"><?php echo esc_html__('Confirm', 'myticket-events'); ?></div>
				<div id="seat_mapping_cancel"><span class="txt"><?php echo esc_html__('back', 'myticket-events'); ?></span><span class="times">&times;</span></div>
			</div>
			<div id="seat_size" class="seat_head">
				<div class="row1">

				</div>
				<div class="row2">
					<span class="sel_texts"><?php echo esc_html__('Selected', 'myticket-events'); ?></span>
					<div class="selected_seats"></div>
				</div>
			</div>
			<!-- number of seat picker without reservation -->
			<div id="svg_number_cont">
				<div id="picker_cont" >
					<div class="picker_cont_inner" >
						<h2><?php echo esc_html__('Pick Number of Passes', 'myticket-events'); ?></h2>
						<div>
							<select type="text" class="picker_select">
								<?php for($i = 0;$i <= $attributes['ticketsPerBooking'];$i++){

									echo '<option value="'.$i.'">'.$i.'</option>';

								} ?>
							</select>
						</div>
						<p><?php echo esc_html__('Note! You are selecting dancefloor tickets without seat reservation.', 'myticket-events'); ?></p>
					</div>
				</div>
			</div>
			<!-- seat reservation inside zone -->
			<div id="svg_mapping_cont">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" id="svg_mapping" class="noselect"> </svg>
				<?php if ($attributes['showArrows']) : ?>
					<div class="kp-arrow-cont">
						<button type="button" role="presentation" class="kp-prev"></button>
						<button type="button" role="presentation" class="kp-next"></button>
					</div>
				<?php endif; ?>
			</div>

		</div>

		<div class="stage-name">
			<?php if ($attributes['title'] == '') { ?>
				<h3>
					<select type="text" class="date_select">
					<?php $ec = 0; for($i = 0; $i < sizeof($eids); $i++){

						if(strlen($eids[$i])==0) continue;
						$datetime_start = get_post_meta( $eids[$i], 'myticket_datetime_start', '');
						if($datetime_start[0]<(time()-3600*6)) continue;
						$myticket_time = date_i18n( get_option( 'time_format' ), intval( $datetime_start[0] ) );
						$myticket_date = date_i18n( get_option( 'date_format' ), intval( $datetime_start[0] ) );
						echo '<option '.($eid==$eids[$i]?'selected':'').' value="'.esc_attr($eids[$i]).'">'.esc_html($myticket_date." ".$myticket_time).'</option>';
						$ec++;
					} 
					if($ec==0) echo '<option value="">'.esc_html__('No available tickets', 'myticket-events').'</option>'; 
					?>
					</select>
				</h3>
			<?php }else{ ?>
				<h3>
					<?php echo esc_html($attributes['title']); ?></h3>
				</h3>
			<?php } ?>
			<p>
				<b><?php if ($attributes['subtitle'] == '' && $product != '') { ?><?php echo $product->get_title(); ?><?php }else{  echo esc_html($attributes['subtitle']); } ?></b><br>
				<span><?php if ($attributes['desc'] == '') { ?><?php echo esc_html(get_post_meta( $eid, 'myticket_title', '')[0]); ?><?php }else{ echo esc_html($attributes['desc']); } ?></span>
			</p>
		</div>

		<script id="kenzap-hall-layout-code" type="application/json"><?php echo $attributes['filterLocations']; ?></script>
		<div id="kp_wrapper" class="kp_wrapper">
			<?php if (in_array('administrator', (array) $user->roles)) { ?>
				<div style="text-align:center;font-size:11px;"><?php echo esc_html__('Note: During admin mode you can cancel bookings by double clicking on the seat.', 'myticket-events'); ?></div>
			<?php } ?>
			<div id="kp_image" style="opacity:0.2; display: block; max-width: <?php echo esc_attr($attributes['dwidth']); ?>px;min-width: <?php echo esc_attr($attributes['mwidth']); ?>px;" class="kp_image">
				<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="#" id="myticket_img" usemap="#map">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" id="svg" class="kp_svg noselect" style="display: block;">
					<g data-id="fTbTcx" data-title="" data-tws="0" data-tns="0" data-height="100">
				</svg>
				<map name="map"></map>
			</div>
		</div>

		<div class="seat-label">
			<ul>
				<li><?php echo esc_html__('Available', 'myticket-events'); ?></li>
				<li><?php echo esc_html__('Sold Out', 'myticket-events'); ?></li>
				<li><?php echo esc_html__('Selected', 'myticket-events'); ?></li>
			</ul>
		</div>

		<?php if ($attributes['note'] != '') { ?><p class="seat-info"><?php echo esc_html($attributes['note']); ?></p><?php } ?>

		<div class="ticket-price">

			<table class="kp-table table-hover">
				<thead>
					<tr>
						<th><?php echo esc_html__('Ticket', 'myticket-events'); ?></th>
						<th><?php echo esc_html__('Row', 'myticket-events'); ?></th>
						<th><?php echo esc_html__('Price', 'myticket-events'); ?></th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody class="kp-ticket-row">

				</tbody>
			</table>

		</div>

		<a class="kp-btn-reserve" href="#"><?php echo esc_html($attributes['cta']); ?></a>
	</div>
<?php }

		$buffer = ob_get_clean();
		return $buffer;
