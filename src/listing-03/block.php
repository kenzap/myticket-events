<?php 
ob_start();

require_once MYTICKET_PATH.'src/commonComponents/container/container-cont.php';

$carturl = '';
$checkouturl = '';
$url = "";
if ( class_exists( 'WooCommerce' ) ){

	$carturl = wc_get_cart_url(); 
	$checkouturl = wc_get_checkout_url();
	switch ($attributes['redirect']){
		case "cart": $url = wc_get_cart_url();break;
		case "checkout": $url = wc_get_checkout_url();break;
	}
}


if ( $attributes['serverSide'] ){

	?><img src="<?php echo MYTICKET_URL . 'assets/block_preview-03.jpg'; ?>" alt="<?php echo esc_attr__('block preview', 'myticket-events'); ?>" />
	<div style="font-size:11px;">
		<?php if ( class_exists( 'WooCommerce' ) ){ ?>
			<div><?php echo esc_html__('Note: Adjust listing settings from the right pane; Click Update to preview changes on your website frontend. To add events go to Products > Add New section and populate fields including MyTicket Extra Details box.', 'myticket-events'); ?></div>
		<?php }else{ ?>
			<div><?php echo esc_html__('Important! Please make sure that WooCommerce and CMB2 plugins are installed and activated.', 'myticket-events'); ?></div>
		<?php } ?>
	</div><?php 
	
}else{ ?>

	<?php
	$myticket_args = array(
		'p' => $attributes['eventID'],
		'post_type' => array('product'), //, 'product_variation'
		'post_status' => 'publish',
		'posts_per_page' => '1',
	);
	
	$products = new WP_Query( $myticket_args );
	
	while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); 

		if ( !class_exists( 'WooCommerce' ) ){

			echo esc_html__('Please activate WooCommerce plugin','myticket-events');
		}else{

			if($attributes['eventID']==""){
				$attributes['eventID'] = get_the_ID();
			}
	
			global $woocommerce; ?> 
					
			<div class="kp-mchmt <?php if($attributes['align']) echo "align".$attributes['align']." "; echo esc_attr($attributes['className']); ?>"  style="--mc:<?php echo esc_attr($attributes['mainColor']); ?>;background-color:<?php echo esc_attr($attributes['backgroundColor']);?>;" data-url="<?php echo esc_url($url); ?>"  data-redirect="<?php echo esc_attr($attributes['redirect']); ?>" data-carturl="<?php echo esc_url($carturl); ?>" data-checkouturl="<?php echo esc_url($checkouturl); ?>" data-ajax_error="<?php echo esc_attr__('Oops. Something went wrong. Please try again later.','myticket-events'); ?>">
				<div class="kenzap-container <?php echo esc_attr($kenzapSize); ?>" style="max-width:<?php echo esc_attr($attributes['containerMaxWidth']);?>px">
					<ul class="ticket-nav">
						<?php $_product = wc_get_product( get_the_ID() );
						global $wp;
						$current_url = home_url(add_query_arg(array(), $wp->request));
						if( $_product->is_type( 'simple' ) ) { ?>

							<li><a href="<?php echo esc_url( $current_url.'?quantity=1&add-to-cart='.$attributes['eventID'] ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>1</span> <?php esc_html_e( ' ticket', 'myticket' ); ?></a></li>
							<li><a href="<?php echo esc_url( $current_url.'?quantity=2&add-to-cart='.$attributes['eventID'] ); ?>" data-quantity="2" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>2</span> <?php esc_html_e( ' tickets', 'myticket' ); ?></a></li>
							<li><a href="<?php echo esc_url( $current_url.'?quantity=3&add-to-cart='.$attributes['eventID'] ); ?>" data-quantity="3" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>3</span> <?php esc_html_e( ' tickets', 'myticket' ); ?></a></li>
							<li><a href="<?php echo esc_url( $current_url.'?quantity=4&add-to-cart='.$attributes['eventID'] ); ?>" data-quantity="4" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>4</span> <?php esc_html_e( ' tickets', 'myticket' ); ?></a></li>
					
						<?php } else { ?>

							<li><a href="<?php echo get_permalink( $attributes['eventID'] );?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-quantity="1" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>1</span> <?php esc_html_e( ' ticket', 'myticket' ); ?></a></li>
							<li><a href="<?php echo get_permalink( $attributes['eventID'] );?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-quantity="2" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>2</span> <?php esc_html_e( ' tickets', 'myticket' ); ?></a></li>
							<li><a href="<?php echo get_permalink( $attributes['eventID'] );?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-quantity="3" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>3</span> <?php esc_html_e( ' tickets', 'myticket' ); ?></a></li>
							<li><a href="<?php echo get_permalink( $attributes['eventID'] );?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-quantity="4" data-product_id="<?php echo esc_attr( $attributes['eventID'] ); ?>" data-product_sku="" ><span>4</span> <?php esc_html_e( ' tickets', 'myticket' ); ?></a></li>

						<?php } ?>
									
						<li><a href="<?php echo get_permalink( $attributes['eventID'] );?>">+</a></li>
					</ul>
				</div>
			</div>

		<?php }
	endwhile; ?>
	
<?php } 

$buffer = ob_get_clean();
return $buffer;