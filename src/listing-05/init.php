<?php function myticket_events_listing_05() {

	require MYTICKET_PATH.'src/commonComponents/container/container-var.php';

	// Register block PHP
	$attributes = array(
		'align' => array(
			'type'    => 'string',
			'default' => '',
		),
		'ticketsPerBooking' => array(
			'type' => 'string',	
			'default' => '10'
		),
		'renderType' => array(
			'type'=> 'string',
			'default'=> '1'
		),
		'seatMode' => array(
			'type'=> 'string',
			'default'=> 'circle'
		),
		'hideNumbers' => array(
			'type'=> 'boolean',
			'default'=> false
		),
		'snSize' => array(
			'type'=> 'number',
			'default'=> 12
		),
		'numOpacity' => array(
			'type'=> 'number',
			'default'=> 50
		),
		'numOpacity2' => array(
			'type'=> 'number',
			'default'=> 50
		),
		'availableColor' => array(
			'type' => 'string',	
			'default' => '#b1e2a5'
		),
		'soldoutColor' => array(
			'type' => 'string',	
			'default' => '#afc3e5'
		),
		'selectedColor' => array(
			'type' => 'string',	
			'default' => '#f78da7'
		),
		'seatsColor' => array(
			'type' => 'string',	
			'default' => '#333333'
		),
		'dwidth' => array(
			'type' => 'string',	
			'default' => '640'
		),
		'mwidth' => array(
			'type' => 'string',	
			'default' => '400'
		),
		'smaxwidth' => array(
			'type' => 'string',	
			'default' => '640'
		),
		'sminwidth' => array(
			'type' => 'string',	
			'default' => '400'
		),
		'showArrows' => array(
			'type' => 'boolean',	
			'default' => false
		),
		'title' => array(
			'type'    => 'string',
			'default' => '',
		),
		'subtitle' => array(
			'type'    => 'string',
			'default' => '',
		),
		'desc' => array(
			'type'    => 'string',
			'default' => '',
		),
		'note' => array(
			'type'    => 'string',
			'default' => 'Move your cursor over a seat to view how the stage looks from that position. Click on the seat to place the relevant ticket in your cart.',
		),
		'cta' => array(
			'type'    => 'string',
			'default' => 'Add to Cart',
		),

		'serverSide'  => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'eventID' => array(
			'type'    => 'string',
			'default' => '',
		),
		
		'mainColor' => array(
			'type' => 'string',	
			'default' => '#ff6600'
		),
		'backgroundColor' => array(
			'type' => 'string',	
			'default' => '#ffffff'
		),
		'checkFilter' => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'filterLocations' => array(
			'type' => 'string',	
			'default' => ''
		),
		'preview' => array(
			'type' => 'boolean',
			'default' => false
		),
	);

	// Register block PHP
	register_block_type( 'myticket-events/listing-05', array(
		'attributes'      => array_merge($contAttributes, $attributes),
		'render_callback' => 'myticket_events_rendering_05',
	) );

	//backend rendering function
	function myticket_events_rendering_05( $attributes ) {

		return require_once 'block.php';
	}
}
add_action( 'init', 'myticket_events_listing_05' ); ?>