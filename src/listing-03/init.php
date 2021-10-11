<?php function myticket_events_listing_03() {

	require MYTICKET_PATH.'src/commonComponents/container/container-var.php';

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
		'containerMaxWidth' => array(
			'type'    => 'number',
			'default' => 1170,
		),
		'redirect' => array(
			'type'    => 'string',
			'default' => "",
		),
		'mainColor' => array(
			'type' => 'string',	
			'default' => '#ff6600'
		),
		'backgroundColor' => array(
			'type' => 'string',	
			'default' => '#ffffff'
		),
		'subColor' => array(
			'type' => 'string',
			'default' => '#8ed1fc'
		),
		'title' => array(
			'type' => 'array',
			'default' => ''
		),
		'preview' => array(
			'type' => 'boolean',
			'default' => false
		),
	);

	// Register block PHP
	register_block_type( 'myticket-events/listing-03', array(
		'attributes'      => array_merge($contAttributes, $attributes),
		'render_callback' => 'myticket_events_rendering_03',
	) );

	//backend rendering function
	function myticket_events_rendering_03( $attributes ) {

		return require_once 'block.php';
	}
}
add_action( 'init', 'myticket_events_listing_03' ); ?>