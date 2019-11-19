<?php
/**
 * Custom template tags for this plugin.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myticket
 */

add_action('wp_ajax_nopriv_myticket_events_get_reservations', 'myticket_events_get_reservations');
add_action('wp_ajax_myticket_events_get_reservations', 'myticket_events_get_reservations');
if ( ! function_exists( 'myticket_events_get_reservations' ) ) {
    function myticket_events_get_reservations() {
        $id = (isset($_POST['id'])) ? sanitize_text_field($_POST['id']) : '';

        $output = [];
        $output['success'] = true;
        $output['data'] = json_decode(get_option("myticket_".$id, '[]'));

        echo json_encode($output);
        
        wp_reset_postdata();
        wp_die();
    }
}

// get woocommerce product by id
add_action('wp_ajax_nopriv_myticket_events_set_reservations', 'myticket_events_set_reservations');
add_action('wp_ajax_myticket_events_set_reservations', 'myticket_events_set_reservations');
if ( ! function_exists( 'myticket_events_set_reservations' ) ) {
    function myticket_events_set_reservations() {

        $id                       = (isset($_POST['id'])) ? sanitize_text_field($_POST['id']) : '';
        $user_id                  = (isset($_POST['user_id'])) ? sanitize_text_field($_POST['user_id']) : '';

        $output = [];
        $tickets = [];

        // clear cart to make sure tickets not added twice
        WC()->cart->empty_cart(true);
        WC()->session->set('cart', array());

        $reservations = json_decode(get_option("myticket_".$id, '[]'), true);

        // remove previous reservations for current user
        foreach ($reservations as $key => $value) {

            //clear not booked reservations older than 30 mins | Ex.: abandoned cart case
            if($reservations[$key]['time']<time()-1800 && $reservations[$key]['type'] < 3){
                unset($reservations[$key]); 
            }

            if($reservations[$key]['user']==$user_id){
                unset($reservations[$key]); 
            }
        }

        // add reservation for current user
        foreach ($_POST['tickets'] as $key => $value){

            $zone_id = sanitize_text_field($_POST['tickets'][$key]['zone_id']);
            $ticket_id = sanitize_text_field($_POST['tickets'][$key]['ticket_id']);
            $zone_text = sanitize_text_field($_POST['tickets'][$key]['zone_text']);
            $ticket_text = sanitize_text_field($_POST['tickets'][$key]['ticket_text']);
            $ticket_row = sanitize_text_field($_POST['tickets'][$key]['ticket_row']);

            $allow = true;

            //check if not reserved by others
            if(isset($reservations[$zone_id."_".$ticket_id])){
                if($reservations[$zone_id."_".$ticket_id]["user"] != $user_id){

                    // report frontend if seats already taken
                    $output['notreserved'][] = $zone_id."_".$ticket_id;
                    $allow = false;
                }
            }

            //type 1 = reserved | type 2 = reserved + checkout | type 3 = booked
            if($allow){

                // track successfull reservations
                $output['reserved'][] = $zone_id."_".$ticket_id;
                $temp = array("type"=>1,"user"=>$user_id,"time"=>time(),"zone_text"=>$zone_text,"ticket_text"=>$ticket_text,"ticket_row"=>$ticket_row);
                $reservations[$zone_id."_".$ticket_id] = $temp;
            }
        }

        update_option("myticket_".$id, json_encode($reservations));

        $output['success'] = true;
        $output['reservations'] = $reservations;

        echo json_encode($output);
        
        wp_reset_postdata();
        wp_die();
    }
}