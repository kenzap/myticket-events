<?php 

// add woocommerce support
add_theme_support('myticket-events');

// set checkout custom ticket price
function myticket_set_custom_price( $cart_object ){

    $user_id = sanitize_text_field($_COOKIE['myticket_user_id']);

    if(isset($user_id)){

        foreach ( $cart_object->get_cart() as $key => $value ) {

            if($value['myticket_price']!='') $value['data']->set_price(floatVal($value['myticket_price']));
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'myticket_set_custom_price' );

// Store the custom field
function myticket_add_cart_item_custom_data( $cart_item_meta, $product_id ) {
    global $woocommerce;
  
    $myticket_seat_id = get_post_meta( $product_id, 'myticket_seat_id', '');
    $myticket_time = get_post_meta( $product_id, 'myticket_datetime_start', '');
    $myticket_length = get_post_meta( $product_id, 'myticket_event_length', '');
    $myticket_venue = get_post_meta( $product_id, 'myticket_title', '');
    $myticket_address = get_post_meta( $product_id, 'myticket_address', '');
    if(sizeof($myticket_time)>0){

        $myticket_date = date_i18n( get_option( 'date_format' ), intval( $myticket_time[0] ) );
        $myticket_time = date_i18n( get_option( 'time_format' ), intval( $myticket_time[0] ) );
        
        $cart_item_meta['myticket_date'] = (isset($_POST['myticket_date'])) ? sanitize_text_field( $_POST['myticket_date'] ): $myticket_date;
        $cart_item_meta['myticket_time'] = (isset($_POST['myticket_time'])) ? sanitize_text_field( $_POST['myticket_time'] ): $myticket_time;
    }

    // pays number of days for cart calendar
    if(sizeof($myticket_length)>0){

        if($myticket_length[0]!=''){

            $cart_item_meta['myticket_length'] = (isset($_POST['myticket_length'])) ? sanitize_text_field( $_POST['myticket_length'] ): $myticket_length[0];
        }
    }

    if(sizeof($myticket_seat_id)>0)
    $cart_item_meta['myticket_seat_id'] = (isset($_POST['myticket_seat_id'])) ? sanitize_text_field( $_POST['myticket_seat_id'] ): $myticket_seat_id[0];
    
    if(sizeof($myticket_venue)>0)
    $cart_item_meta['myticket_venue'] = (isset($_POST['myticket_venue'])) ? sanitize_text_field( $_POST['myticket_venue'] ): $myticket_venue[0];

    if(sizeof($myticket_address)>0)
    $cart_item_meta['myticket_address'] = (isset($_POST['myticket_address'])) ? sanitize_text_field( $_POST['myticket_address'] ): $myticket_address[0];

    $cart_item_meta['myticket_cal'] = (isset($_POST['myticket_cal'])) ? sanitize_email( $_POST['myticket_cal'] ): "";
    $cart_item_meta['myticket_seat_id'] = (isset($_POST['myticket_seat_id'])) ? sanitize_email( $_POST['myticket_seat_id'] ): "";

    $fields_js = myticket_get_fields();
    foreach ( $fields_js as $key => $val ){

        if ( isset($cart_item_meta[$val['fields']['key']['value']]) )
            $cart_item_meta[$val['fields']['key']['value']] = (isset($_POST[$val['fields']['key']['value']])) ? sanitize_text_field( $_POST[$val['fields']['key']['value']] ): "";
    }
    
    return $cart_item_meta; 
}
add_filter( 'woocommerce_add_cart_item_data', 'myticket_add_cart_item_custom_data', 10, 2 );

// define the woocommerce_before_cart_contents callback 
function myticket_before_cart_contents() {

    // "mm/dd/yyyy"
    $dformat = "mm/dd/yyyy";

    if ( '1' == get_theme_mod('myticket_calendar', '0') ){
    ?>
    <script>
        
        var tm;
        // dom change listener. Sometimes WC does partial cart updates 
        jQuery( ".woocommerce" ).bind('DOMSubtreeModified', function(){

            if(tm!=null) clearTimeout(tm);
            tm = setTimeout(function(){ initCalendar(); },100);
        });

        function initCalendar(){

            jQuery( ".cart_cal" ).datepicker({
                
                onSelect: function () {
                    
                    var date = jQuery(this).val();
                    var datep = new Date(date);
					var dates = (datep.getMonth()+1)+'/'+datep.getDate()+'/'+datep.getFullYear();
                    datep.setDate(datep.getDate() + (jQuery(this).data('days')-1));
                    jQuery(this).val(dates + " - " + (datep.getMonth()+1)+'/'+datep.getDate()+'/'+datep.getFullYear()).trigger('keydown');

                },
                changeMonth: true,
                changeYear: true,
                nextText: 'Next',
                prevText: 'Previous',
                inline: true, 
                minDate: new Date(), 
                format:'<?php echo $dformat; ?>' });

            jQuery( "button[name='update_cart']" ).removeAttr('disabled');

            jQuery('.checkout-button').off('click');
            jQuery('.checkout-button').on('click',function(){

                revalidate();
                checkIfUpdated();

                if(!calUpdated){
                    alert(jQuery(".update").data('warning3'));
                    return false;
                }
            });

        }

    </script>
    <?php } ?>

        <script>
        var calUpdated = true;
        jQuery(function ($) {

            jQuery('.checkout-button').off('click');
            $('.checkout-button').on('click',function(){

                // some required fields are not entered
                if(!revalidate()){
                    alert($(".update").data('warning'));
                    return false;
                }

                // fields are not saved 
                if(!checkIfUpdated()){
                    alert($(".update").data('warning2'));
                    return false;
                }

                // calendar date not picked up
                if(!calUpdated){
                    alert($(".update").data('warning3'));
                    return false;
                }
            });
        });

        // checkout-button
        function revalidate(){

            let allowCheckout = true;
            jQuery('.cart_pers_n').each(function(index){

                jQuery(this).css('border','');
                let required = jQuery(this).attr('required');
                if (typeof required !== 'undefined' && required !== false) {

                    if(jQuery(this).val() === ""){ allowCheckout = false; jQuery(this).css('border','1px solid red'); }
                }
            });

            return allowCheckout;
        }

        function checkIfUpdated(){

            let isUpdated = true;
            jQuery('.cart_pers_n,.cart_pers_e,.cart_cal').each(function(index){

                if(jQuery(this).data('value') === ""){ isUpdated = false; }
            });

            return isUpdated;
        }

        function validateEmail(mail){ if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) return (true) }

        </script>

    <?php 
}; 
add_action( 'wp_footer', 'myticket_before_cart_contents', 10, 0 ); 

// Display custom data on cart and checkout page. https://stackoverflow.com/questions/47865226/add-custom-fields-as-cart-item-meta-and-order-item-meta-in-woocommerce
add_filter( 'woocommerce_get_item_data', 'myticket_get_item_data' , 25, 2 );
function myticket_get_item_data ( $cart_data, $cart_item ) {

    $enabled = true;
    $classes = get_body_class();
    if (in_array('woocommerce-checkout',$classes)) { $enabled = false; }

    // output fields
    $fields_js = myticket_get_fields();
    foreach ( $fields_js as $key => $val ){
        switch($val['fields']['type']['value']){
            
            case 'checkbox': echo '<div '.(!$enabled?'style="display:none;"':'').' class="cfield"><input  '.(isset($_POST['cart'][$cart_item['key']][$val['fields']['key']['value']])?'checked="checked"':'').' class="cart_pers cart_pers_n" type="checkbox" name="cart['.esc_attr($cart_item['key']).']['.esc_attr($val['fields']['key']['value']).']" data-value="'.esc_attr($val['fields']['key']['value']).'" value="'.esc_attr($val['fields']['key']['value']).'" placeholder="'.esc_attr( $val['fields']['title']['value'], 'myticket-events' ).'" '.($val['fields']['required']['value'] == 1 ? 'required':'').' /><span>'.esc_html($val['fields']['title']['value']).'</span></div>'; break;
            case 'textarea': echo '<div class="cfield"><textarea '.(!$enabled?'disabled="disabled"':'').' class="cart_pers cart_pers_n" name="cart['.esc_attr($cart_item['key']).']['.esc_attr($val['fields']['key']['value']).']" data-value="'.esc_attr($cart_item[$val['fields']['key']['value']]).'" value="'.esc_attr($cart_item[$val['fields']['key']['value']]).'" placeholder="'.esc_attr( $val['fields']['title']['value'], 'myticket-events' ).'" '.($val['fields']['required']['value'] == 1 ? 'required':'').' >'.esc_html($cart_item[$val['fields']['key']['value']]).'</textarea></div>'; break;
            case 'note': echo '<div class="cfield"><span>'.$val['fields']['title']['value'].'</span></div>'; break;
            default: echo '<div class="cfield"><input '.(!$enabled?'disabled="disabled"':'').' class="cart_pers cart_pers_n" type="text" name="cart['.esc_attr($cart_item['key']).']['.esc_attr($val['fields']['key']['value']).']" data-value="'.esc_attr($cart_item[$val['fields']['key']['value']]).'" value="'.esc_attr($cart_item[$val['fields']['key']['value']]).'" placeholder="'.esc_attr( $val['fields']['title']['value'], 'myticket-events' ).'" '.($val['fields']['required']['value'] == 1 ? 'required':'').' /></div>';
        }
        $warning = true;
    }

    // enable calendar date picker
    if ( '' != $cart_item['myticket_length'] && '1' == get_theme_mod('myticket_calendar', '0') ){

        echo '<br><div class="cal2"><img src="'.MYTICKET_URL.'assets/calendar2.png"/> <input id="datepicker'.esc_attr($cart_item['key']).'" class="cart_pers cart_cal" type="text" name="cart['.esc_attr($cart_item['key']).'][myticket_cal]" data-days="'.$cart_item['myticket_length'].'" data-value="'.esc_attr($cart_item['myticket_cal']).'" value="'.esc_attr($cart_item['myticket_cal']).'" placeholder="'.esc_attr( 'Choose Days', 'myticket-events' ).'" required /></div>';
        $warning = true;
    }

    // update cart button notice
    if ( $warning ){

        echo '<div class="update" data-warning="'.esc_attr__( 'Please enter required fields!', 'myticket-events' ).'" data-warning2="'.esc_attr__( 'Please click on update button first!', 'myticket-events' ).'" data-warning3="'.esc_attr__( 'Please pick up the date first!', 'myticket-events' ).'">'.esc_html__( '*Update cart after changes', 'myticket-events' ).'</div>';
    }
   
    print_r("<br><b>".esc_html__( "Date", "myticket-events").":</b> ".esc_html( $cart_item['myticket_date'] ));
    print_r("<br><b>".esc_html__( "Time", "myticket-events").":</b> ".esc_html( $cart_item['myticket_time'] ));

    // $cart_data[] = array(
    //     'name'    => esc_html__( "Days", "myticket-events"),
    //     'display' => esc_html($cart_item['myticket_length'])
    // );

    $cart_data[] = array(
        'name'    => esc_html__( "Venue", "myticket-events"),
        'display' => esc_html($cart_item['myticket_venue'])
    );

    if(isset($_COOKIE['myticket_user_id'])){

        $cart_data[] = array(
            'name'    => esc_html__( "Seat", "myticket-events"),
            'display' => esc_html($cart_item['myticket_seats'])
        );

        $cart_data[] = array(
            'name'    => esc_html__( "Row", "myticket-events"),
            'display' => esc_html($cart_item['myticket_row'])
        );

        $cart_data[] = array(
            'name'    => esc_html__( "Sector", "myticket-events"),
            'display' => esc_html($cart_item['myticket_zone'])
        );
    }

    return $cart_data;
}

// Get it from the session and add it to the cart variable
function myticket_get_cart_items_from_session( $item, $values, $key ) {

    if ( array_key_exists( 'myticket_seat_id', $values ) )
    $item[ 'myticket_seat_id' ] = $values['myticket_seat_id'];
    if ( array_key_exists( 'myticket_date', $values ) )
    $item[ 'myticket_date' ] = $values['myticket_date'];
    if ( array_key_exists( 'myticket_length', $values ) )
    $item[ 'myticket_length' ] = $values['myticket_length'];
    if ( array_key_exists( 'myticket_time', $values ) )
    $item[ 'myticket_time' ] = $values['myticket_time'];
    if ( array_key_exists( 'myticket_venue', $values ) )
    $item[ 'myticket_venue' ] = $values['myticket_venue'];
    if ( array_key_exists( 'myticket_address', $values ) )
    $item[ 'myticket_address' ] = $values['myticket_address'];

    // such as myticket name and email and other custom defined fileds via customizer > myticket > checkout > fields
    if(isset($_POST['cart'])){

        $fields_js = myticket_get_fields();
        foreach ( $fields_js as $key => $val ){

            if ( array_key_exists( $val['fields']['key']['value'], $_POST['cart'][$item['key']] ) )
                $item[ $val['fields']['key']['value'] ] = sanitize_text_field( $_POST['cart'][$item['key']][$val['fields']['key']['value']] );
        }
    }

    if(isset($_POST['cart']))
        if ( array_key_exists( 'myticket_cal', $_POST['cart'][$item['key']] ) )
            $item[ 'myticket_cal' ] = sanitize_text_field( $_POST['cart'][$item['key']]['myticket_cal'] );

    return $item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'myticket_get_cart_items_from_session', 1, 3 );

//pass custom cart field to checkout
function myticket_add_order_item_meta($item_id, $cart_item, $order_id) {
    
    if ( !empty( $cart_item->legacy_values['myticket_date'] ) ) {
        wc_add_order_item_meta($item_id, 'date', $cart_item->legacy_values['myticket_date']);
    } 

    if ( !empty( $cart_item->legacy_values['myticket_time'] ) ) {
        wc_add_order_item_meta($item_id, 'time', $cart_item->legacy_values['myticket_time']);
    } 

    if ( !empty( $cart_item->legacy_values['myticket_length'] ) ) {
        wc_add_order_item_meta($item_id, 'days', $cart_item->legacy_values['myticket_length']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_venue'] ) ) {
        wc_add_order_item_meta($item_id, 'venue', $cart_item->legacy_values['myticket_venue']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_address'] ) ) {
        wc_add_order_item_meta($item_id, 'address', $cart_item->legacy_values['myticket_address']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_cal'] ) ) {
        wc_add_order_item_meta($item_id, 'calendar', $cart_item->legacy_values['myticket_cal']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_seats'] ) ) {
        wc_add_order_item_meta($item_id, 'seat', $cart_item->legacy_values['myticket_seats']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_zone'] ) ) {
        wc_add_order_item_meta($item_id, 'zone', $cart_item->legacy_values['myticket_zone']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_row'] ) ) {
        wc_add_order_item_meta($item_id, 'row', $cart_item->legacy_values['myticket_row']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_price'] ) ) {
        wc_add_order_item_meta($item_id, 'price', $cart_item->legacy_values['myticket_price']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_seat_id'] ) ) {
        wc_add_order_item_meta($item_id, 'seat_id', $cart_item->legacy_values['myticket_seat_id']);
    }

    $fields_js = myticket_get_fields();
    foreach ( $fields_js as $key => $val ){

        if ( !empty( $cart_item->legacy_values[$val['fields']['key']['value']] ) ) 
            wc_add_order_item_meta($item_id, str_replace("myticket_", "", $val['fields']['key']['value']), $cart_item->legacy_values[$val['fields']['key']['value']]);
    }
}
add_action('woocommerce_new_order_item','myticket_add_order_item_meta', 10, 3);

/** 
 * Register new status
 * Tutorial: http://www.sellwithwp.com/woocommerce-custom-order-status-2/
 **/
function myticket_register_custom_order_status() {
    register_post_status( 'wc-validated', array(
        'label'                     => 'Validated',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Validated <span class="count">(%s)</span>', 'Validated <span class="count">(%s)</span>', 'myticket-events' )
    ) );

    register_post_status( 'wc-expired', array(
        'label'                     => 'Expired',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'myticket-events' )
    ) );
}
add_action( 'init', 'myticket_register_custom_order_status' );

// Add to list of WC Order statuses
function myticket_add_custom_order_status( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-validated'] = 'Validated';
        }

        if ( 'wc-expired' === $key ) {
            $new_order_statuses['wc-expired'] = 'Expired';
        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'myticket_add_custom_order_status' );

//split cart items if quntity for the same item is > 1
function myticket_split_multiple_quantity_products_to_separate_cart_items( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

    // If product has more than 1 quantity
    if ( $quantity > 1 ) {

        // Keep the product but set its quantity to 1
        WC()->cart->set_quantity( $cart_item_key, 1 );

        // Run a loop 1 less than the total quantity
        for ( $i = 1; $i <= $quantity -1; $i++ ) {
            /**
             * Set a unique key.
             * This is what actually forces the product into its own cart line item
             */
            $cart_item_data['unique_key'] = md5( microtime() . rand() . "Kenzap" );

            // Add the product as a new line item with the same variations that were passed
            WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variation, $cart_item_data );
        }
    }         
}

function myticket_split_product_individual_cart_items( $cart_item_data, $product_id ){

    $unique_cart_item_key = uniqid();
    $cart_item_data['unique_key'] = $unique_cart_item_key;
    $user_id = sanitize_text_field($_COOKIE['myticket_user_id']);

    if(isset($user_id)){

        // get seat one by one
        $reservations = json_decode(get_option("myticket_".$product_id, '[]'), true);

        // remove previous reservations for current user
        foreach ($reservations as $key => $value) {

            // clear not booked reservations older than 30 mins | Ex.: abandoned cart case
            if($reservations[$key]['time']<time()-1800 && $reservations[$key]['type'] < 3){
                unset($reservations[$key]); 
            }

            if($reservations[$key]['user']==$user_id && $reservations[$key]['type']==1){

                $reservations[$key]['type'] = 2;
                $cart_item_data['myticket_seat_id'] = $key;
                $cart_item_data['myticket_seats'] = $reservations[$key]['ticket_text'];
                $cart_item_data['myticket_zone'] = $reservations[$key]['zone_text'];
                $cart_item_data['myticket_row'] = $reservations[$key]['ticket_row'];
                $cart_item_data['myticket_price'] = $reservations[$key]['ticket_price'];

                update_option("myticket_".$product_id, json_encode($reservations));

                break;
            }
        }
    }
    return $cart_item_data;
}

function myticket_limit_cart_item_quantity( $cart_item_key, $quantity, $old_quantity, $cart ){

    // Here the quantity limit
    $limit = 1;
    $orders_added = $quantity - $limit;

    if( $quantity > $limit ){

        $cart->cart_contents[ $cart_item_key ]['quantity'] = $limit;
        $product_id = $cart->cart_contents[ $cart_item_key ][ 'product_id' ];

        for( $i = 0; $i< $orders_added; $i++ ){

            $unique_cart_item_key = uniqid();
            $cart_item_data = array();
            $cart_item_data['unique_key'] = $unique_cart_item_key;
            $cart->add_to_cart( $product_id, 1, 0, 0, $cart_item_data );
        }

        // Add a custom notice
        wc_add_notice( __('We Split out quantities of more than one into invididual line items for tracking purposes, please update quantities as needed'), 'notice' );
    }
}

if ( get_theme_mod('myticket_combine','') != 1 ) { 
    
    add_action( 'woocommerce_add_to_cart', 'myticket_split_multiple_quantity_products_to_separate_cart_items', 10, 6 );
    add_filter( 'woocommerce_add_cart_item_data', 'myticket_split_product_individual_cart_items', 10, 2 );  
    add_action( 'woocommerce_after_cart_item_quantity_update', 'myticket_limit_cart_item_quantity', 20, 4 );
}else{

    add_action( 'woocommerce_add_cart_item_data', 'myticket_split_product_individual_cart_items', 10, 6 );
}

// https://rudrastyh.com/woocommerce/thank-you-page.html
function myticket_add_content_thankyou( $order_id ) {

    $order = new WC_Order( $order_id );
    $meta = get_post_meta( $order->get_id() );

    $ticket_name = '';
    $_product = '';
    if ( sizeof( $order->get_items() ) > 0 ) {
        foreach( $order->get_items() as $item ) {
            $_product     = apply_filters( 'woocommerce_order_item_product', wc_get_product( $item['product_id'] ), $item );
            $item_meta    = new WC_Order_Item_Product( $item['item_meta'], $_product );
            $ticket_name = $item['name'].", ";
        }
        $ticket_name = trim($ticket_name," ");
        $ticket_name = trim($ticket_name,",");
    }

    // combined ticket download button
    if(get_theme_mod('myticket_btn_master', 1)){

        echo '<div class="kenzap-download-ticket-cont" >';
            echo '<div class="kenzap-download-ticket" style="background-image:url('.MYTICKET_URL.'assets/download-ticket-bg.png);">
                    <div class="kenzap-container" style="max-width:1170px">
                        <img src="'.MYTICKET_URL.'assets/download-ticket-img.png" alt="'.esc_attr__('Download image','myticket-events').'">
                        <p>'.esc_html__('Thanks for purchasing','myticket-events').' "<strong>'.esc_html($ticket_name).'</strong>" '.esc_html__('ticket','myticket-events').'. </p>';
                        
                            echo '<p>'.esc_html__('You can directly download ticket by clicking','myticket-events').' <span>'.esc_html__('Download','myticket-events').'</span> '.esc_html__('button below','myticket-events').'.</p>';
                            $order->payment_complete();
                            echo do_shortcode( '[myticket-download-invoice class="primary-link" title="'.esc_attr__('Download Your Ticket','myticket-events').'" order_id="' . esc_attr($order->get_id()) . '"]' );
                        
                        echo '
                    </div>';
            echo '</div>';

        }

        // separate ticket download button
        if ( sizeof( $order->get_items() ) > 0 && get_theme_mod('myticket_combine','') != 1 ) { ?>

            <div class="row">   
                <div class="section-download-ticket-multi">

                    <h4><?php esc_html_e('Or download each ticket individually','myticket-events');?></h4>

                    <?php $i=1; foreach( $order->get_items() as $item ) {

                        echo do_shortcode( '[myticket-download-invoice-multi title="'.esc_html__('Download ticket','myticket-events').' #'.esc_attr($i).'" order_id="' . $order->get_id() . '" item_id="' . $item->get_id() . '" ticket_id="' . $i . '" ]' ); ?>

                    <?php $i++; } ?>

                </div>
            </div>
        <?php }

    echo '</div>';

    $product_id = $_product->get_id();
    $user_id = sanitize_text_field($_COOKIE['myticket_user_id']);

    if(isset($user_id)){

        // get seat one by one
        $reservations = json_decode(get_option("myticket_".$product_id, '[]'), true);

        // remove previous reservations for current user
        foreach ($reservations as $key => $value) {

            // reserve only those tickets that were actually booked 
            if($reservations[$key]['user']==$user_id && $reservations[$key]['type'] < 3){

                foreach( $order->get_items() as $item ) {

                    if($key==$item['item_meta']['seat_id']){

                        $reservations[$key]['type'] = 3;
                    }
                }
            }
        }

        // reset ticket seat reservation cookie after successful checkout 
        setcookie("myticket_user_id", "", time()-10, "/");
        setcookie("tickets", "", time()-10, "/");
        update_option("myticket_".$product_id, json_encode($reservations));
    }
}
add_action( 'woocommerce_thankyou', 'myticket_add_content_thankyou', 1, 1 );

if ( ! function_exists( 'myticket_get_order_data_ajax' ) ) {
    function myticket_get_order_data_ajax() {
        global $woocommerce;

        $id       = (isset($_POST['id'])) ? sanitize_text_field($_POST['id']) : "";
        $item_id  = (isset($_POST['item_id'])) ? sanitize_text_field($_POST['item_id']) : "";
        $token    = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : "";

        if ( !is_numeric($id) ) $id = "";
        if ( !is_numeric($item_id) ) $item_id = "";

        if ( $id == "" ){

            ob_start();
            $output['success'] = false;
            $output['code'] = 401;
            $output['reason'] = 'Wrong arguments';
            echo json_encode($output);   
            $buffer = ob_get_clean();
            wp_reset_postdata();
            wp_die($buffer); 
        }

        // check if backend is restricted to certain app IDs only. Customizer > MyTicket section
        $myticket_app_private = get_theme_mod( 'myticket_app_private', 0 );
        $myticket_app_ids = get_theme_mod( 'myticket_app_ids', '' );
        if( 1 == $myticket_app_private ):
            $pos = strrpos($myticket_app_ids, $token);
            if ($pos === false) {
                
                ob_start();
                $output['success'] = false;
                $output['code'] = 401;
                $output['reason'] = 'Unauthorized';
                echo json_encode($output);   
                $buffer = ob_get_clean();
                wp_reset_postdata();
                wp_die($buffer); 
            }
        endif;

        $order = new WC_Order( $id );
 
        ob_start();
        $output = [];
        $output['success'] = true;
        $output['version'] = 2;

        // order ids
        $output['id'] = $id;
        $output['item_id'] = $item_id;

        // order data
        $output['order_status'] = $order->get_status();
        $output['order_total'] = $order->get_total(); 
        $output['type'] = 'general';

        if($item_id!=""){ $output['order_status'] = wc_get_order_item_meta( $item_id, 'status', true ); $output['type'] = "individual"; }
        if($output['order_status'] == "") $output['order_status'] = $order->get_status();

        // scanner button defaults
        $output['btn']['color'] = "#008000";
        // $output['btn']['note'] = esc_html__('• click to update status','myticket-events');
        $output['btn']['note'] = esc_html__('• current status','myticket-events') . " " . $output['order_status'];
        $output['btn']['txt'] = esc_html__('Validate','myticket-events');;
        $output['btn']['status_next'] = "validated";
        $output['btn']['enabled'] = true;
        $output['btn']['visible'] = true;

        // override default popup window with any custom message
        $output['msg'] = "";

        // detect next status for app button click
        switch($output['order_status']){
            case 'processed': $output['btn']['status_next'] = "validated"; break;
            case 'validated':
                
                $output['btn']['status_next'] = "completed"; 
                $output['btn']['txt'] = esc_html__('Unvalidate','myticket-events');
                $output['btn']['color'] = "#3254a8";
                break;
        }
        $output['order_meta'] = get_post_meta( sanitize_text_field($_POST['id']) );
        $output['order_items'] = [];

        $calendar = "";

        // check if order expired
        foreach ($order->get_items() as $key => $lineItem) {
            $order_items = array('name' => $lineItem['name'], 'id' => $lineItem->get_id(), 'quantity' => $lineItem['quantity'], 'meta_data' => $lineItem->get_meta_data());

            // find calendar
            $calendar = wc_get_order_item_meta( $lineItem->get_id(), "calendar");
            if(strlen($calendar)>0){

                list($date_start, $date_end) = explode(" - ", $calendar);
            }
            array_push( $output['order_items'], $order_items );    
        }
        
        // allowed order item keys to display in the app
        $output['order_items_keys']['time']['style'] = '';
        $output['order_items_keys']['venue']['style'] = '';
        $output['order_items_keys']['name']['style'] = '';
        $output['order_items_keys']['email']['style'] = '';
        // $output['order_items_keys']['status']['style'] = '';

        // in case someone whats to test dates manually
        // $date_start = "11/12/2020";
        // $date_end = "01/11/2020";
    
        // load status template
        $override_template_path = get_template_directory() . '/' . MYTICKET_SLUG . '/ticket-status-'.$output['type'].'/index.php';
        if ( file_exists($override_template_path) ){
            include $override_template_path;
        }else{
            include MYTICKET_PATH . 'templates/ticket-status-'.$output['type'].'/index.php';
        }

        // checks if calendar is enabled
        if($calendar!=''){

            $output['order_items_keys']['calendar']['style'] = '';
            
            // too early
            if(strtotime($date_start) > strtotime(date("m/d/Y"))){

                $output['btn']['color'] = "#cc0000";
                $output['btn']['note'] = esc_html__('• not valid, wrong date (too early)','myticket-events');
                $output['btn']['txt'] = esc_html__('Not Valid','myticket-events');
                $output['btn']['enabled'] = false;
                $output['btn']['visible'] = true;
            }

            // too late
            if(strtotime($date_end) < strtotime(date("m/d/Y"))){

                $output['btn']['color'] = "#cc0000";
                $output['btn']['note'] = esc_html__('• not valid, wrong date (expired)','myticket-events');
                $output['btn']['txt'] = esc_html__('Not Valid','myticket-events');
                $output['btn']['enabled'] = false;
                $output['btn']['visible'] = true;
                $order->update_status("expired", 'Updated by admin');
            }

            // disable unvalidation
            // if($output['order_status'] == "validated"){
				
			// 	$output['btn']['status_next'] = "validated"; 
            //     $output['btn']['txt'] = esc_html__('Validated','myticket-events');
            //     $output['btn']['color'] = "#3254a8";
			// }
        }

        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}
add_action('wp_ajax_nopriv_myticket_get_order_data_ajax', 'myticket_get_order_data_ajax');
add_action('wp_ajax_myticket_get_order_data_ajax', 'myticket_get_order_data_ajax');

if ( ! function_exists( 'myticket_set_order_status_ajax' ) ) {
    function myticket_set_order_status_ajax() {
        global $woocommerce;

        //check if backend is restricted to certain apps only.
        $myticket_app_private = get_theme_mod( 'myticket_app_private', '' );

        $output   = [];
        $id       = (isset($_POST['id'])) ? sanitize_text_field($_POST['id']) : "";
        $item_id  = (isset($_POST['item_id'])) ? sanitize_text_field($_POST['item_id']) : "";
        $status   = (isset($_POST['status'])) ? sanitize_text_field($_POST['status']) : "";
        $token    = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : "";

        $order = new WC_Order($id);
        $validated = true;
        if($item_id==""){

            //combined ticket
            $order->update_status($status, 'Updated from MyTicket Scanner App');
            $output['order_status'] = $order->get_status();

            //validate all individual tickets automatically
            foreach ($order->get_items() as $key => $lineItem) {

                wc_update_order_item_meta($lineItem->get_id(), "status", $status);
                wc_update_order_item_meta($lineItem->get_id(), "device ID", $token);
            }
        }else{

            //split ticket
            wc_update_order_item_meta($item_id, "status", $status);
            wc_update_order_item_meta($item_id, "device ID", $token);
            $output['order_status'] = $status;

            //check if all separately sold tickets are validated
            foreach ($order->get_items() as $key => $lineItem) {

                $custom_field = wc_get_order_item_meta( $lineItem->get_id(), 'status', true ); 
                if($custom_field!='validated'){
                    $validated = false;
                }
            }

            //mark all order as validated if all separate tickets are validated
            if($validated){
                $order->update_status($status, 'Updated from MyTicket App ('.$token.')');
            }else{
                if($status!='validated')
                    $order->update_status($status, 'Updated from MyTicket App ('.$token.')');
            }
        }

        ob_start();
        
        $output['success'] = true;
        $output['id'] = $id;
        $output['validated'] = $validated;
        $output['item_id'] = $item_id;
        
        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}
add_action('wp_ajax_nopriv_myticket_set_order_status_ajax', 'myticket_set_order_status_ajax');
add_action('wp_ajax_myticket_set_order_status_ajax', 'myticket_set_order_status_ajax');

//set woocommerce order status change listener to restore tickets in case order is cancelled, failed or refunded
function myticket_woocommerce_order_status_changed( $id, $status_from, $status_to, $instance ) { 

    // clear booking amount
    $change = 0;
    $to = array('cancelled', 'failed', 'refunded');

    // decrease booking
    if ( in_array($status_to, $to) && !in_array($status_from, $to) ){
        $change = -1;
    }
    
    // increase booking
    if ( !in_array($status_to, $to) && in_array($status_from, $to) ){
        $change = 1;
    }
    
    // only save in case booking amounts should be updated
    if ( $change != 0 ){

        $line_items = $instance->get_items( 'line_item' );

        foreach ( $line_items as $item_id => $item ) {

            $product_id = $item->get_product_id();
            $reservations = json_decode(get_option("myticket_".$product_id, '[]'), true);
            $seat_id = wc_get_order_item_meta($item_id, "seat_id", true); 

            if(isset($reservations[$seat_id])){

                if($change == -1){
                    $reservations[$seat_id]['type'] = 1;
                    unset($reservations[$seat_id]);
                }

                update_option("myticket_".$product_id, json_encode($reservations));
            }
        }
    }
};
add_action( 'woocommerce_order_status_changed', 'myticket_woocommerce_order_status_changed', 10, 4 ); 

/**
 * Initializes strings to be translated under myticket_events_specific_order_item_meta_data
 *
 * @param  array  $meta
 * @return array
 */
function myticket_events_init_loco_strings(){

    $temp = esc_attr__("date", 'myticket-events');
    $temp = esc_attr__("time", 'myticket-events');
    $temp = esc_attr__("days", 'myticket-events');
    $temp = esc_attr__("venue", 'myticket-events');
    $temp = esc_attr__("address", 'myticket-events');
    $temp = esc_attr__("name", 'myticket-events');
    $temp = esc_attr__("email", 'myticket-events');
    $temp = esc_attr__("calendar", 'myticket-events');
    $temp = esc_attr__("seat", 'myticket-events');
    $temp = esc_attr__("zone", 'myticket-events');
    $temp = esc_attr__("row", 'myticket-events');
}

/**
 * Hide container meta in emails.
 *
 * @param  array  $meta
 * @return array
 */
function myticket_events_specific_order_item_meta_data($formatted_meta, $item){

    // Only on emails notifications
    if( is_wc_endpoint_url() ) 
        return $formatted_meta;

    foreach( $formatted_meta as $key => $meta ){
        
        // localize display keys such as date, time, venue, days, address, seat, row, zone..
        $formatted_meta[$key]->display_key = esc_attr__($formatted_meta[$key]->key, 'myticket-events');
        
        // hide seat ids.
        if( in_array( $meta->key, array('id_seat', 'seat_id') ) )
            unset($formatted_meta[$key]);
    }

    return $formatted_meta;
}
add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'myticket_events_specific_order_item_meta_data', 10, 2);

/**
 * Return custom checkout fields.
 *
 * @return array
 */
function myticket_get_fields(){

    $fields = get_theme_mod('myticket_fields', '[]');
    $fields_js = json_decode($fields, true);

    if ( '1' == get_theme_mod('myticket_participants', '0') ){

        array_unshift($fields_js, array('id' => 'myticket_email', 'fields'  => array('title' => array('value' => 'Ticket Holder Email'), 'key' => array('value' => 'myticket_email'), 'type' => array('value' => 'text'), 'required' => array('value' => '1' ) ) ) );
        array_unshift($fields_js, array('id' => 'myticket_name', 'fields'  => array('title' => array('value' => 'Ticket Holder Name'), 'key' => array('value' => 'myticket_name'), 'type' => array('value' => 'text'), 'required' => array('value' => '1' ) ) ) );
    }

    return $fields_js;
}