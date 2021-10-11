<?php defined( 'ABSPATH' ) or exit;

//send email to each participant
$order = new WC_Order( $order_id );
if ( sizeof( $order->get_items() ) > 0 ) {
    foreach( $order->get_items() as $item ) {
        
        $output   = [];
        $order_item_id = $item->get_id();

        $time = wc_get_order_item_meta( $item->get_id(), "time", true );
        $venue = wc_get_order_item_meta( $item->get_id(), "venue", true );
        $address = wc_get_order_item_meta( $item->get_id(), "address", true );
        $name = wc_get_order_item_meta( $item->get_id(), "name", true );
        $email = wc_get_order_item_meta( $item->get_id(), "email", true );

        $to = $email;
        $subject = $item->get_name();
        $body = "<b>".esc_html__( 'Ticket for:', 'myticket-events' )."</b> ".esc_html($item->get_name())."<br>";
        $body .= "<b>".esc_html__( 'Venue:', 'myticket-events' )."</b> ".esc_html($venue)."<br>";
        $body .= "<b>".esc_html__( 'Address:', 'myticket-events' )."</b> ".esc_html($address)."<br>";
        $body .= "<b>".esc_html__( 'Time: ', 'myticket-events' )."</b> ".esc_html($time)."<br>";
        $body .= "<b>".esc_html__( 'Ticket Holder: ', 'myticket-events' )."</b> ".esc_html($name)."<br>";
        $body .= "<b>".esc_html__( 'Please use the QR-code below to enter the event.', 'myticket-events' )."</b><br><br>";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        $ticketPath = MyTicket_Events::generate_general_ticket_single( $order_id, $order_item_id, true );

        $mail = wp_mail( $to, $subject, $body, $headers, $ticketPath );
        $output['mail'] = $mail;
    }
}
