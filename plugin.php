<?php
/**
 * Plugin Name: MyTicket Events
 * Plugin URI: https://github.com/kenzap/myticket-events-gutenberg-blocks
 * Description: Create event listings, organize events, link events with WooCommerce orders, print PDF invoices.
 * Author: Kenzap
 * Author URI: https://kenzap.com/
 * Version: 1.1.4
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: myticket-events
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MYTICKET_PATH', plugin_dir_path( __FILE__ ) );
define( 'MYTICKET_URL', plugins_url( '/', __FILE__ ) );
define( 'MYTICKET_SLUG', 'myticket-events' );

//Check plugin requirements
if ( version_compare(PHP_VERSION, '5.6', '<') || !function_exists('register_block_type') ) {
    if (! function_exists('myticket_events_disable_plugin')) {
        /**
         * Disable plugin
         *
         * @return void
         */
        function myticket_events_disable_plugin(){

            if (current_user_can('activate_plugins') && is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(__FILE__);
                unset($_GET['activate']);
            }
        }
    }

    if (! function_exists('myticket_events_show_error')) {
        /**
         * Show error
         *
         * @return void
         */
        function myticket_events_show_error(){

            echo '<div class="error"><p><strong>MyTicket Events</strong> needs at least PHP 5.6 version and WordPress 5.0, please update before installing the plugin.</p></div>';
        }
	}
	
    //Add actions
    add_action('admin_init', 'myticket_events_disable_plugin');
    add_action('admin_notices', 'myticket_events_show_error');

    //Do not load anything more
    return;
}

add_image_size( 'myticket-horizontal', 390, 280, true );
add_image_size( 'myticket-vertical', 390, 542, true );
add_image_size( 'myticket-schedule', 836, 320, true );

//load PDF invoices
require_once __DIR__ . '/inc/mpdf/vendor/mpdf/mpdf/mpdf.php';

//load MyTicket Events class
require_once __DIR__ . '/inc/class-myticket-events.php';

//load WooCommerce class
require_once __DIR__ . '/inc/class-woocommerce.php';

//load Customizer settings
require_once __DIR__ . '/inc/class-customizer.php';

//load admin scripts
if ( is_admin() ) {

	//load dependencies
	require_once __DIR__ . '/inc/class-tgm-plugin-activation.php';
	require_once __DIR__ . '/inc/class-plugins.php';
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';