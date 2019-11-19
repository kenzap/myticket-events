<?php function myticket_events_listing_02() {

	require MYTICKET_PATH.'src/commonComponents/container/container-var.php';

	$attributes = array(
		'align' => array(
			'type'    => 'string',
			'default' => '',
		),
		'serverSide'    => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'showPostCounts'    => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'checkSidebar' => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'checkFilter' => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'sidebar' => array(
			'type'    => 'string',
			'default' => 'left',
		),
		'sidebarTitle' => array(
			'type'    => 'string',
			'default' => '',
		),
		'sidebarSubTitle' => array(
			'type'    => 'string',
			'default' => '',
		),
		'sidebarCat1Title' => array(
			'type'    => 'string',
			'default' => '',
		),
		'sidebarCat1List' => array(
			'type'    => 'string',
			'default' => '',
		),
		'category' => array(
			'type'    => 'string',
			'default' => "",
		),
		'order' => array(
			'type'    => 'string',
			'default' => "",
		),
		'relation' => array(
			'type'    => 'string',
			'default' => "OR",
		),
		'pagination' => array(
			'type'    => 'boolena',
			'default' => true,
		),
		'per_page' => array(
			'type'    => 'number',
			'default' => 50,
		),
		'pricingFilter' => array(
			'type' => 'boolean',
			'default' => false
		), 
		'pricingFilterMax' => array(
			'type' => 'number',
			'default' => 250
		), 
		'pricingFilterTitle' => array(
			'type' => 'string',	
			'default' => ''
		),
		'mainColor' => array(
			'type' => 'string',	
			'default' => '#ff6600'
		),
		'type' => array(
			'type' => 'string',	
			'default' => ''
		),
		'aspect' => array(
			'type' => 'string',	
			'default' => 'horizontal'
		),
	);

	// Register block PHP
	register_block_type( 'myticket-events/listing-02', array(
		'attributes'      => array_merge($contAttributes, $attributes),
		'render_callback' => 'myticket_events_rendering_02',
	) );

	//backend rendering function
	function myticket_events_rendering_02( $attributes ) {

		return require_once 'block.php';
	}
}
add_action( 'init', 'myticket_events_listing_02' );

?>