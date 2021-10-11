<?php function myticket_events_listing_04() {

	require MYTICKET_PATH.'src/commonComponents/container/container-var.php';

	// Register block PHP
	$attributes = array(
		'align' => array(
			'type'    => 'string',
			'default' => '',
		),
		'serverSide'  => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'showPostCounts' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'eventID' => array(
			'type'    => 'string',
			'default' => '',
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
		'containerMaxWidth' => array(
			'type'    => 'number',
			'default' => 1170,
		),
		'relation' => array(
			'type'    => 'string',
			'default' => "",
		),
		'popularity' => array(
			'type'    => 'string',
			'default' => "",
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
		'subColor' => array(
			'type' => 'string',
			'default' => '#8ed1fc'
		),
		'backgroundColor' => array(
			'type' => 'string',	
			'default' => '#ffffff'
		),
		'filterLocations' => array(
			'type' => 'string',	
			'default' => ''
		),
		'sidebarCat2Title' => array(
			'type' => 'string',	
			'default' => ''
		),
		'sidebarCat2List' => array(
			'type' => 'string',	
			'default' => ''
		),
		'widget1' => array(
			'type' => 'string',	
			'default' => ''
		),
		'checkFilter' => array(
			'type' => 'boolean',
			'default' => true
		),
		'preview' => array(
			'type' => 'boolean',
			'default' => false
		),
	);

	// Register block PHP
	register_block_type( 'myticket-events/listing-04', array(
		'attributes'      => array_merge($contAttributes, $attributes),
		'render_callback' => 'myticket_events_rendering_04',
	) );

	//backend rendering function
	function myticket_events_rendering_04( $attributes ) {

		return require_once 'block.php';
	}
}
add_action( 'init', 'myticket_events_listing_04' ); ?>