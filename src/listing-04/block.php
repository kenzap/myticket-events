<?php 

/* Ajax urls */
$ajaxurl = '';
if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
	$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
} else{
	$ajaxurl .= admin_url( 'admin-ajax.php');
}

ob_start();

require_once MYTICKET_PATH.'src/commonComponents/container/container-cont.php';

if ( $attributes['serverSide'] ){

	?><img src="<?php echo MYTICKET_URL . 'assets/block_preview-04.jpg'; ?>" alt="<?php echo esc_attr__('block preview', 'myticket-events'); ?>" />
	<div style="font-size:11px;">
		<?php if ( class_exists( 'WooCommerce' ) ){ ?>
			<div><?php echo esc_html__('Note: Adjust listing settings from the right pane; Click Update to preview changes on your website frontend. To add events go to Products > Add New section and populate fields including MyTicket Extra Details box.', 'myticket-events'); ?></div>
		<?php }else{ ?>
			<div><?php echo esc_html__('Important! Please make sure that WooCommerce and CMB2 plugins are installed and activated.', 'myticket-events'); ?></div>
		<?php } ?>
	</div><?php 
	
}else{ ?>

<div class="kpfes <?php if($attributes['align']) echo "align".$attributes['align']." "; if($attributes['autoPadding']){ echo ' autoPadding '; } if(isset($attributes['className'])) echo esc_attr($attributes['className'])." "; ?>" style="--mc:<?php echo esc_attr($attributes['mainColor']); ?>;--tc:<?php echo esc_attr($attributes['textColor']); ?>; <?php echo ($kenzapStyles);//escaped in src/commonComponents/container/container-cont.php ?>" >

	<?php if( $attributes['checkFilter'] ) : ?>

		<div class="refine-search">
			<div class="kenzap-container <?php echo esc_attr($kenzapSize); ?>" style="max-width:<?php echo esc_attr($attributes['containerMaxWidth']);?>px">
				<div class="kenzap-row">
					<form>
						<div class="keyword kenzap-col-4">
							<label><?php esc_html_e( 'Search Keyword', 'myticket-events' ) ?></label>
							<input type="text" class="form-control hasclear myticket-search-value" placeholder="<?php esc_attr_e( 'Search', 'myticket-events' ) ?>" value="<?php echo esc_attr(sanitize_text_field($_GET['event'])); ?>">
							<span class="clearer-04"><img src="<?php echo MYTICKET_URL; ?>/src/listing-01/assets/clear.svg" alt="<?php esc_attr_e( 'Remove search text', 'myticket-events' ) ?>"></span>
						</div>
						<?php global $wpdb;
						if ( strlen($attributes['filterLocations'])>0 ) {

							$states = explode(",", $attributes['filterLocations']);
							if ( count($states) ) {  ?>
								<div class="location kenzap-col-3">
									<label><?php esc_html_e( 'Location', 'myticket-events' ) ?></label>
									<select id="myticket-location-04" >
										<option value=""><?php esc_html_e( 'All Locations', 'myticket-events' ) ?></option>
										<?php $ee=0; foreach ($states as $state) { 
											echo '<option value="'.trim(esc_attr($states[$ee])).'">' . trim(esc_html($states[$ee])) . '</option>';
											$ee++;
										} ?>
									</select>
								</div>
							<?php }

						}else{
							
							$states = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_value"
									,'myticket_title'
								)
							);

							if ( count($states) ) {  ?>
								<div class="location kenzap-col-3">
									<label><?php esc_html_e( 'Location', 'myticket-events' ) ?></label>
									<select id="myticket-location-04" >
										<option value=""><?php esc_html_e( 'All Locations', 'myticket-events' ) ?></option>
										<?php foreach ($states as $state) { 
											print '<option value="'.trim(esc_attr($state->meta_value)).'">' . trim(esc_html($state->meta_value)) . '</option>';
										} ?>
									</select>
								</div>
							<?php }
						}

						$states = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_value"
								,'myticket_datetime_start'
							)
						);

						if (count($states)) {  ?>
							<div class="event-date kenzap-col-3">
								<label><?php esc_html_e( 'Event Month', 'myticket-events' ) ?></label>
								<select id="myticket-time-04" >
									<option value=""><?php esc_html_e( 'All Dates', 'myticket-events' ) ?></option>
									<?php $dates = array();
									foreach ($states as $state) { 

										switch ( $attributes['type'] ){

											case "upcomming":

												if ( time() < intval( $state->meta_value ) ){

													$date = date_i18n( 'M Y', intval( $state->meta_value ) );
													if ( !in_array($date, $dates, true) ){
														print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . esc_html($date) . '</option>';
														array_push($dates, $date);
													}
												}
						
											break; 
											case "past":

												if ( time() > intval( $state->meta_value ) ){

													$date = date_i18n( 'M Y', intval( $state->meta_value ) );
													if ( !in_array($date, $dates, true) ){
														print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . esc_html($date) . '</option>';
														array_push($dates, $date);
													}
												}

											break; 
											default:
													$date = date_i18n( 'M Y', intval( $state->meta_value ) );
													if ( !in_array($date, $dates, true) ){
														print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . esc_html($date) . '</option>';
														array_push($dates, $date);
													}
											break;
										}

									} ?>
								</select>
							</div>
						<?php } ?>

						<div class="search-btn kenzap-col-2">
							<label>&nbsp;</label>
							<input type="submit" class="myticket-search-btn-04" value="<?php esc_attr_e( 'Search', 'myticket-events' ) ?>">
						</div>
					</form>
				</div>
			</div>
		</div>

	<?php endif; ?>

	<div class="search-result-cont schedule-content" data-pagenum_link="<?php echo get_pagenum_link(999999999); ?>" data-events_per_page="<?php echo esc_attr( $attributes['per_page'] ); ?>" data-pagination="<?php echo esc_attr( $attributes['pagination'] ); ?>"  data-type="<?php echo esc_attr( $attributes['type'] ); ?>" data-category="<?php echo esc_attr( $attributes['category'] ); ?>" data-relation="<?php echo esc_attr( $attributes['relation'] ); ?>" data-sizes="<?php echo esc_attr($kenzapSize); ?>" data-maxwidth="<?php echo esc_attr($attributes['containerMaxWidth']);?>" data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>">

		<div style="text-align:center;letter-spacing:0.1em;margin-top:20px;"><?php echo esc_html('Loading..', 'myticket-events');?></div>

	</div>

</div>
<?php } 

$buffer = ob_get_clean();
return $buffer;