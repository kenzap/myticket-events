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

function myticket_events_load_textdomain() {

    $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
    $locale = apply_filters( 'plugin_locale', $locale, 'myticket-events' );

    unload_textdomain( 'myticket-events' );
    load_textdomain( 'myticket-events', __DIR__ . '/languages/myticket-events-' . $locale . '.mo' );
    load_plugin_textdomain( 'myticket-events', false, __DIR__ . '/languages' );
}
add_action( 'init', 'myticket_events_load_textdomain' );

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
	
	/* Ajax urls */
	$ajaxurl = '';
	if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
		$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
	} else{
		$ajaxurl .= admin_url( 'admin-ajax.php');
	}

	// Include owl carousel script
	wp_enqueue_script( 
		'owl-carousel', 
		plugins_url( 'dist/assets/owl.carousel.min.js', dirname( __FILE__ ) ),
		array( 'jquery')
	);

	// Include owl carousel styles
	wp_enqueue_style( 
		'owl-carousel', 
		plugins_url( 'dist/assets/owl.carousel.min.css', dirname( __FILE__ ) )
	);

	// Include owl carousel styles
	wp_enqueue_style( 
		'myticket-general', 
		plugins_url( 'dist/assets/myticket.css', dirname( __FILE__ ) )
	);
	
	wp_enqueue_script( 'myticket-events-script2', plugins_url( 'listing-02/script.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'myticket-events-script3', plugins_url( 'listing-03/script.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'myticket-events-script4', plugins_url( 'listing-04/script.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'myticket-events-script5', plugins_url( 'listing-05/script.js', __FILE__ ), array('jquery') );
	// wp_localize_script( 'myticket-events-script', 'myticketEvents', array(
	// 	'expand'   => esc_html__( 'expand child menu', 'myticket' ),
	// 	'prev'  => esc_html__('Prev', 'myticket'),
	// 	'next'  => esc_html__('Next', 'myticket'),
	// 	'collapse' => esc_html__( 'collapse child menu', 'myticket' ),
	// 	'ajaxurl'  => $ajaxurl,
	// 	'noposts'  => esc_html__('No records found', 'myticket'),
	// 	'loadmore' => esc_html__('Load more', 'myticket')
	// ) );

	// Styles.
	wp_enqueue_style(
		'myticket-events-style', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
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
		plugins_url( 'dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// This is only available in WP5.
	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'myticket-events-editor', 'myticket-events', MYTICKET_PATH . '/languages/' );
	}
	
	// Styles.
	wp_enqueue_style(
		'myticket-events-editor', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	$pathToPlugin = plugins_url( 'dist/', dirname( __FILE__ ) );
	wp_add_inline_script( 'wp-blocks', 'var kenzap_myticket_path = "' .wp_parse_url($pathToPlugin)['path'].'"', 'before');
	//wp_add_inline_script( 'wp-blocks', 'var kenzap_ajax_path = "' .$ajaxurl.'"', 'before');
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'myticket_events_cgb_editor_assets' );


function myticket_events_add_specific_features( $post_object ) {
    if(!function_exists('has_blocks') || !function_exists('parse_blocks'))
        return;

    if ( has_blocks( $post_object ) ) {
        $pathToPlugin = plugins_url( 'dist/', dirname( __FILE__ ) );
        $blocks = parse_blocks( $post_object ->post_content );
        foreach ($blocks as $block) {
            if ($block['blockName'] == 'myticket-events/listing-01') {
              
				wp_enqueue_script( 'bootstrap-slider', plugins_url( '/dist/assets/bootstrap-slider.min.js', dirname( __FILE__ ) ), array('jquery') );
				wp_enqueue_script( 'myticket-events-script', plugins_url( 'listing-01/script.js', __FILE__ ), array('jquery') );
            }
        }
    }
}
add_action( 'the_post', 'myticket_events_add_specific_features' );


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