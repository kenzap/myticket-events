<?php
/**
 * @package MyTicket Events Class
 */
/*
Plugin Name: MyTicket Events Class
Plugin URI: http://myticket.kenzap.com
Description: Create event listings, organize events, link events with WooCommerce orders, print PDF invoices.
Author: Kenzap
Version: 1.0.0
Author URI: http://kenzap.com
*/

if ( ! class_exists( 'MyTicket_Events' ) ) {

	final class MyTicket_Events {

		/**
		 * MyTicket_Events Constructor.
		 */
		public function __construct() {

			$this->init_hooks();
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function admin_order_items_headers( $order ){ ?>

			<th class="line_customtitle sortable" data-sort="your-sort-option">
				<?php esc_html_e('Ticket', 'myticket-events'); ?>
			</th>
	
		<?php }

		public function admin_order_item_values( $product, $item, $item_id = null ) {

			//Get what you need from $product, $item or $item_id
			?>
			<td class="line_customtitle">
				<?php if( 'line_item' == $item->get_type() ) $this->show_invoice_button_individual( __( 'View', 'myticket-events' ), $item['order_id'], $item_id, 'create_single', array( 'class="button grant_access order-page invoice"' ) ); ?>
			</td><?php
		}

		/**
		 * Initialize admin.
		 */
		public function admin_init_hooks() {

			add_action( 'admin_init', array( $this, 'admin_pdf_callback' ) );

			//WooCommerce custom admin order item columns
			add_action( 'woocommerce_admin_order_item_headers', array( $this, 'admin_order_items_headers' ) );
			add_action( 'woocommerce_admin_order_item_values', array( $this, 'admin_order_item_values' ), 19, 3 );

			add_action( 'add_meta_boxes', array( $this, 'add_admin_order_pdf_meta_box' ) );
			add_action( 'cmb2_init', array( $this, 'add_commerce_metaboxes' ) ); 


			// check for tickets folder permissions
			$uploads = wp_get_upload_dir();
			$ticketDir = $uploads['basedir']."/tickets";
			if ( !wp_mkdir_p( $ticketDir ) ) {
				add_action( 'admin_notices', array( $this, 'permission_notice' ) );
			}
		}

		function woo_custom_redirect_after_purchase() {

			if ( class_exists( 'WooCommerce' ) ){
				global $wp;
				if ( is_checkout() && !empty( $wp->query_vars['order-received'] ) ) {


					$page = get_page_by_path( 'eventcheckout' );

					if( isset( $page ) && !is_null( $page ) ) {
						if( !is_page( 'eventcheckout' ) && !is_admin() /* && $check_date stuff */ ) {
							wp_redirect( get_permalink( $page->ID ), 302 ); exit;   
						}
					}
				}

				if( is_page( 'eventcheckout' ) ) {
					require_once MYTICKET_PATH . 'event-woocommerce/thankyou.php'; exit;
				}
			}
		}

		function permission_notice(){
			?>
			<div class="notice notice-warning is-dismissible">
				<p><?php echo  __( 'Please make sure <strong>../wp-content/uploads/tickets</strong> folder exists and has writing permissions. Required to create and store PDF tickets.', 'myticket-events' ); ?></p>
			</div>
			<?php
		}

		function show_account_invoice_button( $actions, $order ) {

			$action = 'create';
			$url = wp_nonce_url( add_query_arg( array(
				'post' => $order->get_id(),
				'action' => 'edit',
				'myticket_action' => $action,
			), admin_url( 'post.php' ) ), $action, 'nonce' );

			$url = apply_filters( 'myticket_pdf_invoice_url', $url, $order_id, $action );
	
			$actions['name'] = array(
				'url'  => $url,
				'name' => __( 'PDF', 'myticket-events' ),
			);
			return $actions;
		}

		function add_admin_order_pdf_meta_box() {
			add_meta_box( 'order_page_myticket_meta', __( 'PDF Ticket', 'myticket-events' ), array(
				$this,
				'display_order_page_pdf_invoice_meta_box',
			), 'shop_order', 'side', 'high' );
		}	

		public function display_order_page_pdf_invoice_meta_box( $post ) {

			$this->show_invoice_button( __( 'View', 'myticket-events' ), $post->ID, 'create', array( 'class="button grant_access order-page invoice"' ) );
			return;
		}

		//is displayed in right metabox under woocommerce orders
		private function show_invoice_button( $title, $order_id, $action, $attributes = array() ) {

			$url = wp_nonce_url( add_query_arg( array(
				'post' => $order_id,
				'action' => 'edit',
				'myticket_action' => $action,
			), admin_url( 'post.php' ) ), $action, 'nonce' );

			$url = apply_filters( 'myticket_pdf_invoice_url', $url, $order_id, $action );

			printf( '<a href="%1$s" title="%2$s" %3$s>%4$s</a>', esc_url($url), esc_attr($title), join( ' ', $attributes ), esc_html($title) );
		}

		//is displayed in a separate column in orders item meta table under woocommerce orders 
		private function show_invoice_button_individual( $title, $order_id, $order_item_id, $action, $attributes = array() ) {

			$url = wp_nonce_url( add_query_arg( array(
				'post' => $order_id,
				'item_id' => $order_item_id,
				'action' => 'edit',
				'myticket_action' => $action,
			), admin_url( 'post.php' ) ), $action, 'nonce' );

			$url = apply_filters( 'myticket_pdf_invoice_url', $url, $order_id, $action );

			printf( '<a href="%1$s" title="%2$s" %3$s>%4$s</a>', esc_url($url), esc_attr($title), join( ' ',$attributes ), esc_html($title) ); //
		}

		public function frontend_pdf_callback() {

			if ( ! self::is_pdf_request() ) {
				return;
			}
		
			// verify nonce.
			$action = sanitize_key( $_GET['myticket_action'] );
			if ( 'create' !== $action && 'create_single' !== $action ) {
				return;
			}
		
			$nonce = sanitize_key( $_GET['nonce'] );
			if ( ! wp_verify_nonce( $nonce, $action ) ) {
				//wp_die( 'Invalid request.' );
			}

			// verify woocommerce order.
			$post_id = intval( sanitize_key( $_GET['post'] ) );
			$order = wc_get_order( $post_id );
			if ( ! $order ) {
				wp_die( 'Order not found.' );
			}

			// Get the Order meta data in an unprotected array
			$data  = $order->get_data();

			// Get the Customer ID (User ID)
			$customer_id     = $data['customer_id'];

			if ( $customer_id !==  get_current_user_id() ) {
				wp_die( 'You are not authorized to view this page.' );
			}

			$order_id = intval( sanitize_key( $_GET['post'] ) );
		
			// execute invoice action.
			switch ( $action ) {

				//TODO display cached tickets
				case 'view':
					
					break;
				case 'cancel':
					
					break;
				case 'create':
					self::generate_general_ticket( $order_id, false );
					die;
					break;
				case 'create_single':
					$item_id = intval( $_GET['item_id'] );
					self::generate_general_ticket_single( $order_id, $item_id, false );
					die;
					break;
			}
		}

		/**
		 * Initialize non-admin.
		 */
		private function frontend_init_hooks() {

			add_action( 'init', array( $this, 'frontend_pdf_callback' ) );
			// TODO myacount download button
			add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'show_account_invoice_button' ), 10, 2 );
			add_action( 'template_redirect', array( $this, 'woo_custom_redirect_after_purchase' ) );
		}
		
		private function init_hooks() {

			if ( is_admin() ) {
				$this->admin_init_hooks();
			} else {
				$this->frontend_init_hooks();
			}

			// generate individual emails if many participants allowed
			if( '1' == get_theme_mod('myticket_participants', '0') ){

				if ( '1' == get_theme_mod('myticket_email_1', '0')  )
					add_action( 'woocommerce_checkout_order_processed', array( $this, 'process_qrcode_email' ), 10, 3 );

				if ( '1' == get_theme_mod('myticket_email_2', '0') )
					add_action( 'woocommerce_order_status_completed', array( $this, 'process_qrcode_email' ), 10, 1 );

				if ( '1' == get_theme_mod('myticket_email_3', '0') )
					add_action( 'woocommerce_payment_complete', array( $this, 'process_qrcode_email' ) );

			}

			// attach general ticket to woocommerce emails
			if( '1' == get_theme_mod('myticket_email_0', '0') ){

				add_filter( 'woocommerce_email_attachments', array( $this, 'attach_tickets_to_email' ), 99, 3 );
			}

			add_shortcode( 'myticket-download-invoice', array( $this, 'download_invoice_shortcode' ) );
			add_shortcode( 'myticket-download-invoice-multi', array( $this, 'download_invoice_shortcode_multi' ) );
		}

		//shortcode for general ticket
		public function download_invoice_shortcode( $atts ) {

			if ( ! isset( $atts['order_id'] ) || 0 === intval( $atts['order_id'] ) ) {
				return;
			}

			$order_id = $atts['order_id'];
			$action = 'create';

			$url = add_query_arg( array(
				'action' => 'edit',
				'post' => $order_id,
				'myticket_action' => $action,
				'nonce' => wp_create_nonce( 'edit' ),
			) );

			$url = apply_filters( 'myticket_pdf_invoice_url', $url, $order_id, $action );

			printf( '<a target="_blank" href="%1$s">%2$s</a>', esc_attr( $url ), esc_html( $atts['title'] ) );
		}

		//shortcode for individual ticket
		public function download_invoice_shortcode_multi( $atts ) {

			if ( ! isset( $atts['order_id'] ) || 0 === intval( $atts['order_id'] ) ) {
				return;
			}

			if ( ! isset( $atts['item_id'] ) || 0 === intval( $atts['item_id'] ) ) {
				return;
			}

			if ( ! isset( $atts['ticket_id'] ) || 0 === intval( $atts['ticket_id'] ) ) {
				return;
			}

			$order_id = $atts['order_id'];
			$action = 'create_single';

			$url = add_query_arg( array(
				'action' => 'edit',
				'ticket_id' => $atts['ticket_id'],
				'item_id' => $atts['item_id'],
				'post' => $order_id,
				'myticket_action' => $action,
				'nonce' => wp_create_nonce( 'edit' ),
			) );

			$url = apply_filters( 'myticket_pdf_invoice_url', $url, $order_id, $action );

			printf( '<a target="_blank" href="%1$s">%2$s</a>', esc_attr( $url ), esc_html( $atts['title'] ) );
		}

		public function attach_tickets_to_email( $attachments, $status, $order ) {

			// only attach to emails with WC_Order object.
			if ( ! $order instanceof WC_Order ) {
				return $attachments;
			}

			$order_id = $order->get_id();
			$ticketPath = self::generate_general_ticket( $order_id, true );
			$attachments[] = $ticketPath;
				
			return $attachments;
		}

		// send out individual emails to participants
		public function process_qrcode_email( $order_id ){
		
			// load email template
			$override_template_path = get_template_directory() . "/" . MYTICKET_SLUG . "/email-individual/index.php";
			if ( file_exists($override_template_path) ){
				return include $override_template_path;
			}else{
				return include MYTICKET_PATH . 'templates/email-individual/index.php';
			}
		}

		private static function is_pdf_request() {
			return ( isset( $_GET['post'] ) && isset( $_GET['myticket_action'] ) && isset( $_GET['nonce'] ) );
		}

		/**
		 * Process admin get requests. EX.: Create, View invoices 
		 */
		public function admin_pdf_callback() {

			if ( ! self::is_pdf_request() ) {
				return;
			}

			// sanitize data and verify nonce.
			$action = sanitize_key( $_GET['myticket_action'] );
			$nonce = sanitize_key( $_GET['nonce'] );
			if ( ! wp_verify_nonce( $nonce, $action ) ) {
				wp_die( 'Invalid request.' );
			}

			// validate allowed user roles.
			$user = wp_get_current_user();
			$allowed_roles = apply_filters( 'myticket_allowed_roles_to_download_invoice', array(
				'administrator',
				'shop_manager',
			) );
			
			if ( ! array_intersect( $allowed_roles, $user->roles ) ) {
				// wp_die( 'Access denied' );
			}

			$order_id = intval( sanitize_key( $_GET['post'] ) );

			// execute invoice action.
			switch ( $action ) {

				//TODO display cached tickets
				case 'view':
					
					break;
				case 'cancel':
					
					break;
				case 'create':
					self::generate_general_ticket( $order_id, false );
					die;
					break;
				case 'create_single':
					$item_id = intval( $_GET['item_id'] );
					self::generate_general_ticket_single( $order_id, $item_id, false );
					die;
					break;
			}
		}

		public static function generate_general_ticket( $order_id, $to_file ){

			// load ticket template
			$override_template_path = get_template_directory() . "/" . MYTICKET_SLUG . "/ticket-general/index.php";
			if ( file_exists($override_template_path) ){
				return include $override_template_path;
			}else{
				return include MYTICKET_PATH . 'templates/ticket-general/index.php';
			}
		}

		public static function generate_general_ticket_single( $order_id, $order_item_id, $to_file ){

			// load ticket template
			$override_template_path = get_template_directory() . "/" . MYTICKET_SLUG . "/ticket-individual/index.php";
			if ( file_exists($override_template_path) ){
				return include $override_template_path;
			}else{
				return include MYTICKET_PATH . 'templates/ticket-individual/index.php';
			}
		}

		/**
         * Create myticket commerce specific meta box key values
         */
        public function add_commerce_metaboxes() {

            /**
             * Initiate the metabox
             */
            $cmb = new_cmb2_box( array(
                                    'id'            => 'product_metabox',
                                    'title'         => esc_html__( 'MyTicket Extra Details', 'myticket-events' ),
                                    'object_types'  => array( 'product', ), // Post type
                                    'context'       => 'normal',
                                    'priority'      => 'high',
                                    'show_names'    => true, // Show field names on the left
                                    ) );

            $cmb->add_field( array(
                                    'name' => esc_html__( 'Event Begins', 'myticket-events' ),
                                    'desc' => esc_html__( 'Select event date time and zone.', 'myticket-events' ),
                                    'id'   => 'myticket_datetime_start',
                                    'type' => 'text_datetime_timestamp',
                                    ) );

            $cmb->add_field( array(
                                    'name' => esc_html__( 'Event Ends', 'myticket-events' ),
                                    'desc' => esc_html__( 'Select event date time and zone.', 'myticket-events' ),
                                    'id'   => 'myticket_datetime_end',
                                    'type' => 'text_datetime_timestamp',
									) );

			$cmb->add_field( array(
									'name' => esc_html__( 'Event Length', 'myticket-events' ),
									'desc' => esc_html__( 'Allow users to pick specific attendance day in cart calendar. Go to Customizer > MyTicket > Checkout > Enable Calendar', 'myticket-events' ),
									'id'               => 'myticket_event_length',
									'type'             => 'select',
									'show_option_none' => true,
									'default'          => '',
									'options'          => array(
										''  => __( 'Undefined', 'myticket-events' ),
										'1' => __( '1 Day', 'myticket-events' ),
										'2' => __( '2 Days', 'myticket-events' ),
										'3' => __( '3 Days', 'myticket-events' ),
										'4' => __( '4 Days', 'myticket-events' ),
										'5' => __( '5 Days', 'myticket-events' ),
										'6' => __( '6 Days', 'myticket-events' ),
										'7' => __( '7 Days', 'myticket-events' ),
										'8' => __( '8 Days', 'myticket-events' ),
										'9' => __( '9 Days', 'myticket-events' ),
										'10' => __( '10 Days', 'myticket-events' ),
									),
									) );

            $cmb->add_field( array(
                                    'name' => esc_html__( 'Location Title', 'myticket-events' ),
                                    'desc' => esc_html__( 'Location title/Venue is used for visual representation only.', 'myticket-events' ),
                                    'id'   => 'myticket_title',
                                    'type' => 'text',
                                    ) );

            $cmb->add_field( array(
                                    'name' => esc_html__( 'Location Address', 'myticket-events' ),
                                    'desc' => esc_html__( 'Location address is used for visual representation. Map searches are only performed based on location coordinates that can be provided below.', 'myticket-events' ),
                                    'id'   => 'myticket_address',
                                    'type' => 'text',
                                    ) );

            $cmb->add_field( array(
                                    'name' => esc_html__( 'Location Coordinates', 'myticket-events' ),
                                    'desc' => esc_html__( 'Location latitude and longitude separated by comma. Ex.: 124.34343, -23.3423.', 'myticket-events' ),
                                    'id'   => 'myticket_coordinates',
                                    'type' => 'text',
                                    ) );

            $cmb->add_field( array(
                                    'name' => esc_html__( 'Custom Link', 'myticket-events' ),
                                    'desc' => esc_html__( 'Override default woocommerce product permalink.', 'myticket-events' ),
                                    'id'   => 'myticket_link',
                                    'type' => 'text_url',
                                    ) );

        }
	}

	new MyTicket_Events;
} ?>