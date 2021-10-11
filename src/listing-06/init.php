<?php function myticket_events_listing_06() {

	require MYTICKET_PATH.'src/commonComponents/container/container-var.php';

	// Register block PHP
	$attributes = array(
		'align' => array(
			'type'    => 'string',
			'default' => '',
		),
		'borderRadius' => array(
			'type'    => 'number',
			'default' => 5,
		),
		'low_stock' => array(
			'type'    => 'number',
			'default' => 4,
		),
		'order' => array(
			'type'    => 'string',
			'default' => "",
		),
		'type' => array(
			'type' => 'string',	
			'default' => ''
		),
		'img1' => array(
			'type'    => 'string',
			'default' => MYTICKET_URL."assets/location.svg",
		),
		'serverSide'  => array(
			'type'    => 'boolean',
			'default' => false,
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
			'default' => 5,
		),
		'textColor' => array(
			'type' => 'string',	
			'default' => '#333'
		),
		'mainColor' => array(
			'type' => 'string',	
			'default' => '#9376df'
		),
		'subColor' => array(
			'type' => 'string',	
			'default' => '#e04242'
		),
		'backgroundColor' => array(
			'type' => 'string',	
			'default' => '#ffffff'
		),
		'containerMaxWidth' => array(
			'type'    => 'number',
			'default' => 2000,
		),
		'popularity' => array(
			'type'    => 'string',
			'default' => "",
		),
		'preview' => array(
			'type' => 'boolean',
			'default' => false
		),

	);

	// Register block PHP
	register_block_type( 'myticket-events/listing-06', array(
		'attributes'      => array_merge($contAttributes, $attributes),
		'render_callback' => 'myticket_events_rendering_06',
	) );	

	//backend rendering function
	function myticket_events_rendering_06( $attributes ) {

		return require_once 'block.php';
	}
}
add_action( 'init', 'myticket_events_listing_06' ); ?>