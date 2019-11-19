<?php 

function myticket_hexToRgb_01($hex, $alpha = 0.5){
	$hex      = str_replace('#', '', $hex);
	$length   = strlen($hex);
	$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
	$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
	$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
	$rgb['a'] = $alpha;
	return 'rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].','.$alpha.')';
}

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

	?><img src="<?php echo plugins_url( 'assets/block_preview.jpg', __FILE__ ); ?>" alt="<?php echo esc_attr__('block preview', 'myticket-events'); ?>" />
	<div style="font-size:11px;">
		<div><?php echo esc_html__('Note: Adjust listing settings from the right pane; Click Update to preview changes on your website frontend. To add events go to Products > Add New section and populate fields including MyTicket Extra Details box.', 'myticket-events'); ?></div>
	</div><?php 
	
}else{ ?>

	<div class="kp-mytcont <?php if($attributes['align']) echo "align".$attributes['align']." "; if($attributes['autoPadding']){ echo ' autoPadding '; } if(isset($attributes['className'])) echo esc_attr($attributes['className'])." "; ?>" style="--borderRadius:<?php echo esc_attr($attributes['borderRadius']); ?>px;--mc:<?php echo esc_attr($attributes['mainColor']); ?>;--tc:<?php echo esc_attr($attributes['textColor']); ?>;--cc:<?php echo esc_attr($attributes['textColor2']); ?>;--tc2:<?php echo esc_attr(myticket_hexToRgb_01($attributes['textColor'],0.3)); ?>;--tc3:<?php echo esc_attr(myticket_hexToRgb_01($attributes['textColor'],0.5)); ?>;<?php echo ($kenzapStyles);//escaped in src/commonComponents/container/container-cont.php ?>">

		<?php if( $attributes['checkFilter'] ) : ?>

			<div class="refine-search">
				<div class="kenzap-container <?php echo esc_attr($kenzapSize); ?>" style="max-width:<?php echo esc_attr($attributes['containerMaxWidth']);?>px">
					<div class="kenzap-row">
						<form>
							<div class="keyword kenzap-col-4">
								<label><?php esc_html_e( 'Search Keyword', 'myticket-events' ) ?></label>
								<input type="text" class="form-control hasclear myticket-search-value" placeholder="<?php esc_attr_e( 'Search', 'myticket-events' ) ?>" value="<?php echo esc_attr(sanitize_text_field($_GET['event'])); ?>">
								<span class="clearer"><img src="<?php echo MYTICKET_URL; ?>/src/listing-01/assets/clear.svg" alt="<?php esc_attr_e( 'Remove search text', 'myticket-events' ) ?>"></span>
							</div>
							<?php global $wpdb;
							if ( strlen($attributes['filterLocations'])>0 ) {

								$states = explode(",", $attributes['filterLocations']);
								if ( count($states) ) {  ?>
									<div class="location kenzap-col-3">
										<label><?php esc_html_e( 'Location', 'myticket-events' ) ?></label>
										<select id="myticket-location" >
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
										<select id="myticket-location" >
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
									<select id="myticket-time" >
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
								<input type="submit" class="myticket-search-btn" value="<?php esc_attr_e( 'Search', 'myticket-events' ) ?>">
							</div>
						</form>
					</div>
				</div>
			</div>

		<?php endif; ?>

		<div class="search-content <?php echo esc_attr($attributes['className']); ?>" >
			<div class="kenzap-container <?php echo esc_attr($kenzapSize); ?>" style="max-width:<?php echo esc_attr($attributes['containerMaxWidth']);?>px">
				<div class="kenzap-row">
			
					<?php if ( $attributes['relation'] != 'AND' && $attributes['relation'] != 'OR' )
						$attributes['relation'] = 'AND';
					
					if ( $attributes['checkSidebar'] && $attributes['sidebar'] == 'left' ) : ?>

						<div class="kenzap-col-3">
							<div class="search-filter">

								<?php myticket_sidebar( $attributes ); ?>

							</div>
						</div>

					<?php endif; ?>

					<div class="<?php if ( !$attributes['checkSidebar'] ) { echo 'kenzap-col-12'; }else{ echo 'kenzap-col-9'; } ?> myticket-content" data-paged="<?php echo $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>" data-pagenum_link="<?php echo get_pagenum_link(999999999); ?>" data-events_per_page="<?php echo esc_attr( $attributes['events_per_page'] ); ?>" data-pagination="<?php echo esc_attr( $attributes['pagination'] ); ?>" data-list_style="<?php echo esc_attr( $attributes['list_style'] );?>" data-type="<?php echo esc_attr( $attributes['type'] ); ?>" data-category="<?php echo esc_attr( $attributes['category'] ); ?>" data-relation="<?php echo esc_attr( $attributes['relation'] ); ?>" data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" >
						
						<?php if( $attributes['checkFilter2'] ){ ?>
							<div class="search-result-header">
								<div class="kenzap-row">
									<div class="kenzap-col-7">
										<div id="myticket-sri-cont" class="all-records" data-all="<?php esc_attr_e( 'Showing all records', 'myticket-events' ) ?>" data-search="<?php esc_attr_e( 'Search results for', 'myticket-events' ) ?>"> </div>
										<span id="myticket-search-numbers-info"><?php esc_html_e( 'Showing', 'myticket-events' ) ?> <span id="myticket_prr" ></span> <?php esc_html_e( 'of', 'myticket-events' ) ?> <span id="myticket_pcr" ></span> <?php esc_html_e( 'Results', 'myticket-events' ) ?></span>
									</div>
									<div class="kenzap-col-5">
										<label><?php esc_html_e( 'Sort By:', 'myticket-events' ) ?></label>
										<select id="myticket-sorting" class="cat" data-active="<?php if ( isset($_COOKIE['product_order']) ){ $attributes['orderby'] = $_COOKIE['product_order']; } echo esc_attr( $attributes['orderby'] );?>">
											<!-- <option value=""><?php esc_html_e( 'Default', 'myticket-events' ) ?></option> -->
											<option <?php if ( $attributes['orderby'] == 'newest' ){ echo "selected"; } ?> value="newest"><?php esc_html_e( 'Closest', 'myticket-events' ) ?></option>
											<option <?php if ( $attributes['orderby'] == 'alphabetical' ){ echo "selected"; } ?> value="alphabetical"><?php esc_html_e( 'Alphabetically', 'myticket-events' ) ?></option>
											<option <?php if ( $attributes['orderby'] == 'popularity' ){ echo "selected"; } ?> value="popularity"><?php esc_html_e( 'Popularity', 'myticket-events' ) ?></option>
											<option <?php if ( $attributes['orderby'] == 'rating' ){ echo "selected"; } ?> value="rating"><?php esc_html_e( 'Rating', 'myticket-events' ) ?></option>
											<option <?php if ( $attributes['orderby'] == 'lowestprice' ){ echo "selected"; } ?> value="lowestprice"><?php esc_html_e( 'Lowest Price', 'myticket-events' ) ?></option>
											<option <?php if ( $attributes['orderby'] == 'highestprice' ){ echo "selected"; } ?> value="highestprice"><?php esc_html_e( 'Highest Price', 'myticket-events' ) ?></option>
										</select>
									</div>
								</div>
							</div>
						<?php } ?>

						<div class="search-result-cont">
							<?php 
							// global $myticket_args;
							// global $myticket_pagination;
							// global $myticket_pagenum_link;

							// $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
							// $myticket_args = array(
							// 	'posts_per_page' => $attributes['per_page'],
							// 	'post_type' => array('product'), //, 'product_variation'
							// 	'post_status' => 'publish',
							// 	'ignore_sticky_posts' => 1,
							// 	'meta_query' => array(),
							// 	'paged' => $paged,
							// 	'tax_query' => array('relation'=>$attributes['relation']), 
							// 	'meta_key' => 'myticket_datetime_start',
							// 	'orderby' => 'meta_value_num',
							// 	'order' => 'ASC',      
							// );

							// //select event period
							// switch ( $attributes['type'] ){

							// 	case "upcomming":

							// 		$temp =  array(
							// 			'key'       => 'myticket_datetime_start',
							// 			'compare'   => '>',
							// 			'value'     => time(),
							// 			'type'      => 'NUMERIC'
							// 		);
							// 		array_push( $myticket_args['meta_query'], $temp ); 

							// 	break; 
							// 	case "past":

							// 		$temp =  array(
							// 			'key'       => 'myticket_datetime_start',
							// 			'compare'   => '<',
							// 			'value'     => time(),
							// 			'type'      => 'NUMERIC'
							// 		);
							// 		array_push( $myticket_args['meta_query'], $temp ); 

							// 	break; 
							// }

							// //category filter
							// $myticket_category_list_arr = explode(",", $_COOKIE['product_category_list']);
							// if ( strlen( $attributes['category'] ) > 1 )
							// 	array_push($myticket_category_list_arr, $attributes['category']);

							// $myticket_category_list_arr = array_unique($myticket_category_list_arr);
							// foreach($myticket_category_list_arr as $category){

							// 	$product_cat_arr = array(
							// 		'taxonomy'     => 'product_cat',
							// 		'field'        => 'name',
							// 		'terms'        => esc_attr( $category )
							// 	);
							// 	if ( strlen($category) > 0 )
							// 		array_push( $myticket_args['tax_query'], $product_cat_arr );
							// }
					
							// //custom string search
							// if ( $_GET['event'] != '' ){
							// 	$myticket_args['s'] = sanitize_text_field($_GET['event']);
							// }

							// //product order sorting
							// switch ( $attributes['orderby'] ) {
							// 	case 'lowestprice':
							// 		$myticket_args['meta_key'] = '_price';  
							// 		$myticket_args['orderby'] = array( 'meta_value_num' => 'ASC' );  
							// 	break;
							// 	case 'highestprice':
							// 		$myticket_args['meta_key'] = '_price';  
							// 		$myticket_args['orderby'] = array( 'meta_value_num' => 'DESC' );  
							// 	break;
							// 	case 'newest':
							// 		$myticket_args['orderby'] = array( 'date' => 'DESC' );   
							// 	break;
							// 	case 'popularity':
							// 		$myticket_args['orderby'] = 'meta_value_num';  
							// 		$myticket_args['order'] = 'DESC';
							// 		$myticket_args['orderby_meta_key'] = 'total_sales';
							// 	break;
							// 	case 'rating':
							// 		$myticket_args['orderby'] = 'meta_value_num';  
							// 		$myticket_args['order'] = 'DESC';
							// 		$myticket_args['orderby_meta_key'] = '_wc_average_rating';
							// 	break;
							// 	case 'alphabetical':
							// 		$myticket_args['orderby'] = 'title';
							// 		$myticket_args['order'] = 'ASC';
							// 	break;
							// }
							// $myticket_pagination = $attributes['pagination'];
							// $myticket_pagenum_link = get_pagenum_link(999999999);
							
							//require plugin_dir_path(__FILE__) . 'block-ajax.php'; 
							?>
						</div>
					</div>

					<?php if ( $attributes['checkSidebar'] && $attributes['sidebar'] == 'right' ) : ?>

						<div class="kenzap-col-3">
							<div class="search-filter">

								<?php myticket_sidebar($attributes); ?>

							</div>
						</div>

					<?php endif; ?>
				
				</div>
			</div>
		</div>
	</div>
<?php }

function myticket_sidebar($attributes){ ?>

		<?php if ( $attributes['sidebarTitle'] !='' ) : ?>

			<div class="search-event-title">
				<h2><span><?php esc_html_e( $attributes['sidebarTitle']); ?></span> <?php esc_html_e( $attributes['sidebarSubTitle']); ?></h2>
			</div>

		<?php endif; ?>

		<?php if ( $attributes['sidebarCat1List'] !='' ) : ?>

			<?php $categories = explode( ",", $attributes['sidebarCat1List'] );
			$product_category_list = explode( ",", $_COOKIE['product_category_list'] ); ?>

			<div class="search-filter-delivery">

				<h3><?php echo esc_html( $attributes['sidebarCat1Title'] ); ?></h3>
				<div class="kenzap-checkbox">
					<input id="delivery0" class="styled" type="checkbox" checked="">
					<label for="delivery0"><?php esc_html_e( 'All', 'myticket-events'); ?></label>
				</div>

				<?php if ( sizeof($categories) > 0 ) : $i = 1;
					foreach ( $categories as $category ) : ?>

						<div class="kenzap-checkbox">
							<input id="delivery<?php echo esc_attr($i);?>" <?php if ( in_array(trim($category),$product_category_list) ){ echo 'checked';} ?> class="styled myticket-widget-category-checkbox" data-category="<?php echo esc_attr( trim($category) ); ?>" type="checkbox">
							<label for="delivery<?php echo esc_attr($i);?>"><?php echo esc_html( trim($category) ); ?></label>
						</div>
 
					<?php $i++; endforeach;
				endif; ?>
			</div>

		<?php endif; ?>

		<?php if ( $attributes['sidebarCat2List'] !='' ) : ?>
		
			<?php $categories = explode( ",", $attributes['sidebarCat2List'] );
			$product_category_list = explode( ",", $_COOKIE['product_category_list'] ); ?>

			<div class="search-filter-delivery">

				<h3><?php echo esc_html( $attributes['sidebarCat2Title'] ); ?></h3>
				<div class="kenzap-checkbox">
					<input class="styled" type="checkbox" checked="">
					<label><?php esc_html_e( 'All', 'myticket-events'); ?></label>
				</div>
				<?php if ( sizeof($categories) > 0 ) : $i = 1;
					foreach ( $categories as $category ) : ?>

						<div class="kenzap-checkbox">
							<input id="features<?php echo esc_attr($i);?>" <?php if ( in_array(trim($category),$product_category_list) ){ echo 'checked';} ?> class="styled myticket-widget-category-checkbox" data-category="<?php echo esc_attr( trim($category) ); ?>" type="checkbox">
							<label for="features<?php echo esc_attr($i);?>"><?php echo esc_html( trim($category) ); ?></label>
						</div>

					<?php $i++; endforeach;
				endif; ?>
			</div>

		<?php endif; ?>

		<?php if ( $attributes['pricingFilter'] !='' ) : ?>
		
			<div class="search-filter-price">

				<h3><?php echo esc_html( $attributes['pricingFilterTitle'] ); ?></h3>
				<input id="price-range" type="text" class="span2" data-slider-min="0" data-currency="<?php echo esc_attr( $attributes['currencysymbol'] ); ?>" data-slider-max="<?php echo esc_attr( $attributes['pricingFilterMax'] ); ?>" data-slider-step="<?php echo esc_attr( $attributes['pricingFilterMax']/50 ); ?>" data-slider-value="[0,<?php echo esc_attr( $attributes['pricingFilterMax'] ); ?>]"/>

			</div>

		<?php endif; ?>

<?php }

if ( ! function_exists( 'myticket_pagination_gallery' ) ) :

	function myticket_pagination_gallery($class, $recentPosts, $pagenum_link){
	
		echo '<div class="'.esc_attr( $class ).'">';
		$big = 999999999; // need an unlikely integer
		$pagination = paginate_links( array(
											'base' => str_replace( $big, '%#%', esc_url( $pagenum_link ) ),
											'format' => '?paged=%#%',
											'current' => max( 1, get_query_var('paged') ),
											'total' => $recentPosts->max_num_pages,
											'type' => 'array',
											'prev_next'  => TRUE,
											'prev_text'     => '<span aria-hidden="true"> '.esc_html__( 'Previous', 'myticket-events' ).'</span>',
											'next_text'     => '<span aria-hidden="true">'.esc_html__( 'Next', 'myticket-events' ).' </span>'
											) );
											if( is_array( $pagination ) ) {
												$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
												echo '<ul class="kp-pagination">';
												foreach ( $pagination as $page ) {
													echo "<li>$page</li>";
												}
												echo '</ul>';
											}
	
		echo '</div>';
	}
	
endif;

$buffer = ob_get_clean();
return $buffer;