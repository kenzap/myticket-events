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

	?><img src="<?php echo plugins_url( 'assets/block_preview.jpg', __FILE__ ); ?>" alt="<?php echo esc_attr__('block preview', 'myticket-events'); ?>" />
	<div style="font-size:11px;">
		<div><?php echo esc_html__('Note: Adjust listing settings from the right pane; Click Update to preview changes on your website frontend. To add events go to Products > Add New section and populate fields including MyTicket Extra Details box.', 'myticket-events'); ?></div>
	</div><?php 
	
}else{ ?>

	<?php if( !class_exists( 'WooCommerce' ) ) { echo esc_html__('Please activate WooCommerce plugin','myticket-events');}else{ ?>

	<div class="kpcae <?php if($attributes['align']) echo "align".$attributes['align']." "; echo esc_attr($attributes['className']); ?>" style="--mc:<?php echo esc_attr($attributes['mainColor']); ?>; <?php echo ($kenzapStyles);//escaped in src/commonComponents/container/container-cont.php ?>">
		<div class="kenzap-container <?php echo esc_attr($kenzapSize); ?>" style="max-width:<?php echo esc_attr($attributes['containerMaxWidth']);?>px">
			<div class="kenzap-row">
				<?php 
				$btn_text_arr = [];
				$btn_text_arr[0] = __( 'Book Ticket', 'myticket-events' );
				$btn_text_arr[1] = __( 'Sold Out', 'myticket-events' );
				$btn_text_arr[2] = __( 'View More', 'myticket-events' );
					
				$id = get_the_ID();
				$extra = 'class="get-ticket"';

				if ( $attributes['per_page'] == '' ){
					$attributes['per_page'] = -1;
				}
				
				$myticket_args = array(
					'posts_per_page' => $attributes['per_page'],
					'post_type' => array('product'), //, 'product_variation'
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'meta_query' => array(),
					'tax_query' => array('relation'=>'AND'),  
					'meta_key' => 'myticket_datetime_start',
					'orderby' => 'meta_value_num',
					'order' => 'ASC',
				);

				if( $attributes['category'] != '' ){

					$temp = array(
						'taxonomy' => 'product_cat',
						'field'    => 'name',
						'terms'    => $attributes['category'],
					);
					array_push( $myticket_args['tax_query'], $temp ); 
				}

				//select event period
				switch ( $attributes['type'] ){

					case "upcomming":

						$temp =  array(
							'key'       => 'myticket_datetime_start',
							'compare'   => '>',
							'value'     => time(),
							'type'      => 'NUMERIC'
						);
						array_push( $myticket_args['meta_query'], $temp ); 

					break; 
					case "past":

						$temp =  array(
							'key'       => 'myticket_datetime_start',
							'compare'   => '<',
							'value'     => time(),
							'type'      => 'NUMERIC'
						);
						array_push( $myticket_args['meta_query'], $temp ); 

					break; 
				}

				$products = new WP_Query( $myticket_args );
				$monthsyears = $states = array();
				while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() );

					array_push($states, $meta['myticket_datetime_start'][0]);

				endwhile; 
				
				if ( $attributes['checkFilter'] ) : ?>

					<?php if ( count($states) ) {  

						$monthsyears = $months = $years = array();
						foreach ( $states as $state ) { 

							$month = date_i18n( 'M', intval( $state ) );
							$year = date_i18n( 'Y', intval( $state ) );

							if ( !in_array($month.$year, $monthsyears, true) ){

								array_push($monthsyears, $month.$year);
								array_push($months, $month);
								array_push($years, $year);
							}
						} 
					} ?>
					
					<ul role="tablist" class="event-tabs owl-carousel <?php echo esc_attr($kenzapSize); ?>-carousel">
						<?php $i = 1;
						if ( count($monthsyears) ) { 
							foreach ( $monthsyears as $monthsyear ) {

								$year = $years[$i-1];
								$month = $months[$i-1]; ?>
								<li <?php if ( $i==1 ){ echo 'class="active"'; } ?>>
									<a href="#tab<?php echo esc_attr($monthsyear);?>" role="tab" data-toggle="tab"><?php echo esc_html($month); ?><span><?php echo esc_html($year); ?></span></a>
								</li>
								<?php $i++;
							} 
						} ?>
					</ul>
				
				<?php endif; ?>

				<div class="tab-content">

					<?php $monthyear = '';
					if ( $products->have_posts() ) : 

							if (!$attributes['checkFilter']){ 
								echo '<div role="tabpanel" class="tab-pane active" id="tab_all_events"><ul class="clearfix">';
							}

							while ( $products->have_posts() ) : 

								$products->the_post(); 
								$id = get_the_ID();
								$meta = get_post_meta( get_the_ID() );
								$day = date_i18n( "d", intval( $meta['myticket_datetime_start'][0] ) );
								$month = date_i18n( "M", intval( $meta['myticket_datetime_start'][0] ) );
								$year = date_i18n( "Y", intval( $meta['myticket_datetime_start'][0] ) );
								if ( $monthyear != $month.$year && $attributes['checkFilter'] ){

									if ( $monthyear != '' ){ echo '</ul></div>'; }
									echo '<div role="tabpanel" class="tab-pane '.(($monthyear == '')?"active":"").'" id="tab'.esc_attr($month.$year).'"><ul class="clearfix">';
		
									// view all events if months disabled
									$monthyear = $month.$year;
								}

								//get action lik                  
								$btn_text_arr = [];
								$btn_text_arr[0] = __( 'Get Ticket', 'myticket-events' );
								$btn_text_arr[1] = __( 'Sold Out', 'myticket-events' );
								$btn_text_arr[2] = __( 'View More', 'myticket-events' );
								?>
								
								<li>
									<div class="date">
										<a href="#">
											<span class="day"><?php echo esc_html($day); ?></span>
											<span class="month"><?php echo esc_html($month); ?></span>
											<span class="year"><?php echo esc_html($year); ?></span>
										</a>
									</div>
									<a href="<?php echo esc_url( $link );?>">

										<?php switch ($attributes['aspect']){
											case 'horizontal': the_post_thumbnail( 'myticket-horizontal', array( 'class' => 'img-responsive' ) ); break;
											case 'vertical': the_post_thumbnail( 'myticket-vertical', array( 'class' => 'img-responsive' ) ); break;
											case 'square': the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'img-responsive' ) ); break;
										} ?>

									</a>
									<div class="info">
										<p><?php the_title();?> <span><?php echo esc_html( $meta['myticket_title'][0] ); ?></span></p>

										<?php 
										//get button link
										$meta = get_post_meta( $id );

										//get button status
										if ( $meta['_stock_status'][0] == 'instock' ) :
											$btn_text = $btn_text_arr[0];
										elseif ( $meta['_stock_status'][0] == 'outofstock' ) :
											$btn_text = $btn_text_arr[1];
										endif;
									
										//if not product type simple force final page
										$_product = wc_get_product( $id );
										$link = get_permalink( $id );

										//override woocommerce product with custom link
										if ( strlen($meta['myticket_link'][0]) > 0 ){
											$link = $meta['myticket_link'][0];
											$btn_text = $btn_text_arr[2];
										} ?>
									
										<a href="<?php echo esc_url( $link ); ?>" <?php echo wp_kses( $extra, array( 
											'a' => array(
												'href' => array(),
												'title' => array()
											),
											'br' => array(),
											'b' => array(),
											'tr' => array(),
											'th' => array(),
											'td' => array(),
											'em' => array(),
											'span' => array(
												'id' => array(),
												'class' => array(),),
											'i' => array( 
												'id' => array(),
												'class' => array(),),
											'strong' => array(),
											'span' => array(
												'href' => array(),
												'class' => array(),
											),
											'div' => array(
												'id' => array(),
												'class' => array(),
											),
										) );
									
										?> ><?php echo esc_html( $btn_text ); ?></a>
									
									</div>
								</li>

							<?php endwhile; ?>
								</ul>
							</div>
									
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>


<?php }

}

$buffer = ob_get_clean();
return $buffer;