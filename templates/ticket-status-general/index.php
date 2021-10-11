<?php 
/* 
This script is responsible for general ticket status customization.
It instructs app frontend to modify statuses in specific sequence.
MyTicket Scanner App call to action button color, text and other
settings can be adjusted here.
*/

defined( 'ABSPATH' ) or exit;

// instruct app frontent
switch($output['order_status']){

    case 'processing': 
    case 'completed': 
        $output['btn']['color']         = "#008000";
        $output['btn']['status_next']   = "validated"; 
        $output['btn']['note']          = esc_html__('• current status','myticket-events') . ' ' . $output['order_status'];
        $output['btn']['txt']           = esc_html__('Validate','myticket-events');
        $output['btn']['enabled']       = true;
        $output['btn']['visible']       = true;
        break;

    case 'validated':
        $output['btn']['color']         = "#3254a8";
        $output['btn']['status_next']   = "completed"; 
        $output['btn']['note']          = esc_html__('• current status','myticket-events') . ' ' . $output['order_status'];
        $output['btn']['txt']           = esc_html__('Unvalidate','myticket-events');
        $output['btn']['enabled']       = true;
        $output['btn']['visible']       = true;
        break;

    case 'pending':
        $output['btn']['color']         = '#cc0000'; // app button color
        $output['btn']['note']          = esc_html__('Ticket pending payment','myticket-events');
        $output['btn']['txt']           = esc_html__('Not Valid','myticket-events');
        $output['btn']['enabled']       = false; // app button is clickable or not
        $output['btn']['visible']       = true; // app button visibility
        break;

    case 'on-hold':
        $output['btn']['color']         = '#cc0000'; // app button color
        $output['btn']['note']          = esc_html__('Ticket pending payment or on hold','myticket-events');
        $output['btn']['txt']           = esc_html__('Not Valid','myticket-events');
        $output['btn']['enabled']       = false; // app button is clickable or not
        $output['btn']['visible']       = true; // app button visibility
        break;

    case 'cancelled':
        $output['btn']['color']         = '#cc0000'; // app button color
        $output['btn']['note']          = esc_html__('Ticket is cancelled','myticket-events');
        $output['btn']['txt']           = esc_html__('Not Valid','myticket-events');
        $output['btn']['enabled']       = false; // app button is clickable or not
        $output['btn']['visible']       = true; // app button visibility
        break;

    case 'refunded':
        $output['btn']['color']         = '#cc0000'; // app button color
        $output['btn']['note']          = esc_html__('Ticket is refunded','myticket-events');
        $output['btn']['txt']           = esc_html__('Not Valid','myticket-events');
        $output['btn']['enabled']       = false; // app button is clickable or not
        $output['btn']['visible']       = true; // app button visibility
        break;

    case 'failed':
        $output['btn']['color']         = '#cc0000'; // app button color
        $output['btn']['note']          = esc_html__('Ticket has technical issues','myticket-events');
        $output['btn']['txt']           = esc_html__('Not Valid','myticket-events');
        $output['btn']['enabled']       = false; // app button is clickable or not
        $output['btn']['visible']       = true; // app button visibility
        break;
}