<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// load body class
function myticket_events_body_class( $classes ) {

	$kp_thankyou = '';
	if ( get_theme_mod('myticket_btn_master', 1) ){ $kp_thankyou = 'kp_thankyou' ; }
	if ( is_array($classes) ){ $classes[] = 'kenzap'; $classes[] = $kp_thankyou;  }else{ $classes.=' kenzap'.' '.$kp_thankyou; }
	return $classes;
}
add_filter( 'body_class', 'myticket_events_body_class' );
add_filter( 'admin_body_class', 'myticket_events_body_class' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function myticket_events_cgb_block_assets() { // phpcs:ignore
	

	// get header page id 
	$th = get_theme_mod( 'template-header', '' );
	$post = get_post( $th );
	$hid = $post->ID;

	// get footer page id
	$th = get_theme_mod( 'template-footer', '' );
	$post = get_post( $th );
	$fid = $post->ID;

	$body_classes = get_body_class();
	$pathToPlugin = plugins_url( 'build/', dirname( __FILE__ ) );

	/* We only add JS/CSS dependencies when block is present on the page */
	if(is_singular() || is_home() || is_archive() || is_404()){

        // under frontend we only load scripts and styles for each block individually
        $id = get_the_ID();
        if (has_block( 'myticket-events/listing-01',$id)){

			wp_enqueue_script( 'bootstrap-slider', plugins_url( 'assets/bootstrap-slider.min.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION  );
			wp_enqueue_script( 'myticket-events-listing-01', plugins_url( 'assets/listing-01-script.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION );
        }

        if (has_block( 'myticket-events/listing-02',$id)){

			// Include owl carousel script
			wp_enqueue_script( 
				'owl-carousel', 
				plugins_url( 'assets/owl.carousel.min.js', dirname( __FILE__ ) ),
				array( 'jquery')
			);

			// Include owl carousel styles
			wp_enqueue_style(
				'owl-carousel', 
				plugins_url( 'assets/owl.carousel.min.css', dirname( __FILE__ ) )
			);

			wp_enqueue_script( 'myticket-events-listing-02', plugins_url( 'assets/listing-02-script.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION );
        }

        if (has_block( 'myticket-events/listing-03',$id)){

			wp_enqueue_script( 'myticket-events-listing-03', plugins_url( 'assets/listing-03-script.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION );
        }

        if (has_block( 'myticket-events/listing-04',$id)){

			// Include owl carousel script
			wp_enqueue_script( 
				'owl-carousel', 
				plugins_url( 'assets/owl.carousel.min.js', dirname( __FILE__ ) ),
				array( 'jquery')
			);

			// Include owl carousel styles
			wp_enqueue_style(
				'owl-carousel', 
				plugins_url( 'assets/owl.carousel.min.css', dirname( __FILE__ ) )
			);

			wp_enqueue_script( 'myticket-events-listing-04', plugins_url( 'assets/listing-04-script.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION );
        }

        if (has_block( 'myticket-events/listing-05',$id)){

			wp_enqueue_script( 'myticket-events-listing-05', plugins_url( 'assets/listing-05-script.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION );
        }
	}

	/* Ajax urls */
	$ajaxurl = '';
	if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
		$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
	} else{
		$ajaxurl .= admin_url( 'admin-ajax.php');
	}

	// jQuery calendar
	if ( '1' == get_theme_mod('myticket_calendar', '0') ){

		wp_enqueue_script( 'myticket-events-calendar', plugins_url( 'assets/jquery-ui.min.js', dirname( __FILE__ ) ), array('jquery'), MYTICKET_VERSION  );
		wp_enqueue_style( 'myticket-events-calendar', plugins_url( 'assets/jquery-ui.min.css', dirname( __FILE__ ) ) );
	}

	// Styles.
	wp_enqueue_style(
		'myticket-events-style',
		plugins_url( 'build/style-index.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
	);
	
	wp_add_inline_script( 'wp-blocks', 'var kenzap_ajax_path = "' .$ajaxurl.'"', 'before');
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'myticket_events_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function myticket_events_cgb_editor_assets() { // phpcs:ignore

	// Scripts.
	wp_enqueue_script(
		'myticket-events-editor', // Handle.
		plugins_url( 'build/index.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		MYTICKET_VERSION,
	);

	// embed only while in customizer
	if ( class_exists( 'WP_Customize_Control' ) ) {

		wp_enqueue_style( 'customizer-repeater-admin-stylesheet', plugins_url( 'assets/customizer-repeater.css', dirname( __FILE__ ) ), array(), MYTICKET_VERSION );

		wp_enqueue_script( 'customizer-repeater-script', plugins_url( 'assets/customizer-repeater.js', dirname( __FILE__ ) ), array('jquery', 'jquery-ui-draggable', 'wp-color-picker' ), MYTICKET_VERSION, true  );
	}

	// This is only available in WP5.
	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'myticket-events-editor', 'myticket-events', MYTICKET_PATH . '/languages/' );
	}
	
	// $pathToPlugin = plugins_url( dirname( __FILE__ ) );
	wp_add_inline_script( 'wp-blocks', 'var kenzap_myticket_path = "' .MYTICKET_URL.'"', 'before');
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'myticket_events_cgb_editor_assets' );

/**
 * Adds the `hasCustomCSS` and `customCSS` attributes to all blocks.
 *
 * @hooked wp_loaded,100    This might not be late enough for all blocks, I don't know when blocks are supposed to be registered.
 * @link https://github.com/Codeinwp/blocks-css/pull/5/files
 */

//register blocks
require_once 'listing-01/init.php';
require_once 'listing-02/init.php';
require_once 'listing-03/init.php';
require_once 'listing-04/init.php';
require_once 'listing-05/init.php';
require_once 'listing-06/init.php';

//register ajax calls
require_once 'listing-01/block-ajax-init.php';
require_once 'listing-04/block-ajax-init.php';
require_once 'listing-05/block-ajax-init.php';