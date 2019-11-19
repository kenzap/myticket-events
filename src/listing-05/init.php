<?php function myticket_events_listing_05() {

	// Register block PHP
	register_block_type( 'myticket-events/listing-05', array(
		'attributes'   => array(
			'align' => array(
				'type'    => 'string',
				'default' => '',
			),
			'ticketsPerBooking' => array(
				'type' => 'string',	
				'default' => '10'
			),
			'availableColor' => array(
				'type' => 'string',	
				'default' => '#F3F3F3'
			),
			'soldoutColor' => array(
				'type' => 'string',	
				'default' => '#AFC3E5'
			),
			'selectedColor' => array(
				'type' => 'string',	
				'default' => '#B1E2A5'
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
				'default' => 'Rangers Centre',
			),
			'subtitle' => array(
				'type'    => 'string',
				'default' => 'Roma, Italy',
			),
			'desc' => array(
				'type'    => 'string',
				'default' => 'Concert Seating',
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
		),
		'render_callback' => 'myticket_events_rendering_05',
	) );

	//backend rendering function
	function myticket_events_rendering_05( $attributes ) {

		return require_once 'block.php';
	}
}
add_action( 'init', 'myticket_events_listing_05' ); ?>