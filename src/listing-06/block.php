<?php 

ob_start();

require_once MYTICKET_PATH.'src/commonComponents/container/container-cont.php';

$ajaxurl = '';
if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
	$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
} else{
	$ajaxurl .= admin_url( 'admin-ajax.php');
}

$carturl = '';
$checkouturl = '';
$general_price = '';
$product = '';
if ( class_exists( 'WooCommerce' ) ){

	$product = wc_get_product( $attributes['eventID'] );
	$carturl = wc_get_cart_url();
	$checkouturl = wc_get_checkout_url();
}

global $myticket_args;
global $myticket_pagination;
global $myticket_pagenum_link;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$myticket_args = array(
	'posts_per_page' => $attributes['per_page'],
	'post_type' => array('product'), //, 'product_variation'
	'post_status' => 'publish',
	'ignore_sticky_posts' => 1,
	'meta_query' => array(),
	'paged' => $paged,
	'tax_query' => array('relation'=>$attributes['relation']), 
	'meta_key' => 'myticket_datetime_start',
	'orderby' => 'meta_value_num',
	'order' => 'ASC',      
);

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

//category filter
$myticket_category_list_arr = explode(",", $_COOKIE['product_category_list']);
if ( strlen( $attributes['category'] ) > 1 )
	array_push($myticket_category_list_arr, $attributes['category']);

$myticket_category_list_arr = array_unique($myticket_category_list_arr);
foreach($myticket_category_list_arr as $category){

	$product_cat_arr = array(
		'taxonomy'     => 'product_cat',
		'field'        => 'name',
		'terms'        => esc_attr( $category )
	);
	if ( strlen($category) > 0 )
		array_push( $myticket_args['tax_query'], $product_cat_arr );
}

//custom string search
if ( $_GET['event'] != '' ){
	$myticket_args['s'] = sanitize_text_field($_GET['event']);
}

//product order sorting
switch ( $attributes['order'] ) {
	case 'lowestprice':
		$myticket_args['meta_key'] = '_price';  
		$myticket_args['orderby'] = array( 'meta_value_num' => 'ASC' );  
	break;
	case 'highestprice':
		$myticket_args['meta_key'] = '_price';  
		$myticket_args['orderby'] = array( 'meta_value_num' => 'DESC' );  
	break;
	case 'newest':
		$myticket_args['orderby'] = array( 'date' => 'DESC' );   
	break;
	case 'popularity':
		$myticket_args['orderby'] = 'meta_value_num';  
		$myticket_args['order'] = 'DESC';
		$myticket_args['orderby_meta_key'] = 'total_sales';
	break;
	case 'rating':
		$myticket_args['orderby'] = 'meta_value_num';  
		$myticket_args['order'] = 'DESC';
		$myticket_args['orderby_meta_key'] = '_wc_average_rating';
	break;
	case 'alphabetical':
		$myticket_args['orderby'] = 'title';
		$myticket_args['order'] = 'ASC';
	break;
}
$myticket_pagination = $attributes['pagination'];
$myticket_pagenum_link = get_pagenum_link(999999999);

require plugin_dir_path(__FILE__) . 'block-ajax.php'; ?>
	
<?php 

$buffer = ob_get_clean();
return $buffer;