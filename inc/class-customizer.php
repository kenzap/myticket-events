<?php

function myticket_events_customizer_register( $wp_customize ) {

    $wp_customize->add_panel(   'myticket_master_panel' , array(
                                'title'        => esc_html__( 'MyTicket', 'myticket-events' ),
                                'priority'     => 80,
                                'capability'   => 'edit_theme_options',
                                 ) );

    $wp_customize->add_section( 'myticket_app_section', array(
                                'priority'       => 10,
                                'capability'     => 'edit_theme_options',
                                'theme_supports' => '',
                                'title'          => esc_html__('Mobile App', 'myticket-events'),
                                'description'    => esc_html__('Set up rules for MyTicket scanner app', 'myticket-events'),
                                'panel'  => 'myticket_master_panel',
                                ) );

    $wp_customize->add_section( 'myticket_ticketing_section', array(
                                'priority'       => 10,
                                'capability'     => 'edit_theme_options',
                                'theme_supports' => '',
                                'title'          => esc_html__('Ticketing', 'myticket-events'),
                                'description'    => esc_html__('Set up general rules for ticketing operations', 'myticket-events'),
                                'panel'  => 'myticket_master_panel',
                                ) );

    $wp_customize->add_section( 'myticket_email_section', array(
                                'priority'       => 10,
                                'capability'     => 'edit_theme_options',
                                'theme_supports' => '',
                                'title'          => esc_html__('Emails', 'myticket-events'),
                                'description'    => esc_html__('Set up rules for Email triggering', 'myticket-events'),
                                'panel'  => 'myticket_master_panel',
                                ) );

    $wp_customize->add_section( 'myticket_checkout_section', array(
                                'priority'       => 10,
                                'capability'     => 'edit_theme_options',
                                'theme_supports' => '',
                                'title'          => esc_html__('Checkout', 'myticket-events'),
                                'description'    => esc_html__('Set up checkout settings and views', 'myticket-events'),
                                'panel'  => 'myticket_master_panel',
                                ) );

    //email 0
    $wp_customize->add_setting( 'myticket_email_0', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );

    $wp_customize->add_control( 'myticket_email_0', array(
                                'label'     => esc_html__( 'PDF Ticket', 'myticket-events' ),
                                'section'   => 'myticket_email_section',
                                'priority'  => 10,
                                'description' => esc_html__('Attach general ticket in PDF format to WooCommerce emails. Go to WooCommerce > Settings > Emails to adjust email trigger settings. To override this ticket template go to your theme folder and find /myticket-events/general-ticket/ folder.', 'myticket-events'),
                                'type'      => 'checkbox'
                                ) );

    //email 1
    $wp_customize->add_setting( 'myticket_email_1', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );

    $wp_customize->add_control( 'myticket_email_1', array(
                                'label'     => esc_html__( 'Participant PDF Ticket (New Order)', 'myticket-events' ),
                                'section'   => 'myticket_email_section',
                                'priority'  => 10,
                                'description' => esc_html__('Attach tickets to new orders. (Works only with "Participant Data" feature enabled under MyTicket > Ticketing section of customizer. Works in parallel with WooCommerce emails. To override this ticket template go to your theme folder and find /myticket-events/individual-ticket/ folder.)', 'myticket-events'),
                                'type'      => 'checkbox'
                                ) );

    //email 2
    $wp_customize->add_setting( 'myticket_email_2', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );

    $wp_customize->add_control( 'myticket_email_2', array(
                                'label'     => esc_html__( 'Participant PDF Ticket (Status Completed)', 'myticket-events' ),
                                'section'   => 'myticket_email_section',
                                'priority'  => 10,
                                'description' => esc_html__('Attach tickets to emails when order status changes to Completed. (Works only with "Participant Data" feature enabled under MyTicket > Ticketing section of customizer. Works in parallel with WooCommerce emails. To override this ticket template go to your theme folder and find /myticket-events/individual-ticket/ folder.)', 'myticket-events'),
                                'type'      => 'checkbox'
                                ) );

    //email 3
    $wp_customize->add_setting( 'myticket_email_3', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );

    $wp_customize->add_control( 'myticket_email_3', array(
                                'label'     => esc_html__( 'Participant PDF Ticket (Payment Received)', 'myticket-events' ),
                                'section'   => 'myticket_email_section',
                                'priority'  => 10,
                                'description' => esc_html__('Attach tickets to emails after payment received. (Works only with "Participant Data" feature enabled under MyTicket > Ticketing section of customizer. Works in parallel with WooCommerce emails. To override this ticket template go to your theme folder and find /myticket-events/individual-ticket/ folder.)', 'myticket-events'),
                                'type'      => 'checkbox'
                                ) );

    //combine tickets into single file
    $wp_customize->add_setting( 'myticket_btn_master', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                'default' => 1
                                ) );

    $wp_customize->add_control( 'myticket_btn_master', array(
                                'label'     => esc_html__( 'MyTicket checkout', 'myticket-events' ),
                                'section'   => 'myticket_checkout_section',
                                'priority'  => 10,
                                'description' => esc_html__( 'Add general ticket download button with MyTicket checkout style.', 'myticket-events' ),
                                'type'      => 'checkbox'
                                ) );

    //combine tickets into single file
    $wp_customize->add_setting( 'myticket_combine', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_combine', array(
                                'label'     => esc_html__( 'Combine tickets', 'myticket-events' ),
                                'section'   => 'myticket_checkout_section',
                                'priority'  => 10,
                                'description' => esc_html__('Upon cart and checkout combine events into a single record if quantity is more than 1 ticket. By enabling this option you will also have only one invoice per order with no option to split qr-code generated invoices for each attendee.', 'myticket-events' ),
                                'type'      => 'checkbox'
                                ) );


    //combine tickets into single file
    $wp_customize->add_setting( 'myticket_participants', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );

    $wp_customize->add_control( 'myticket_participants', array(
                                'label'     => esc_html__( 'Participant data', 'myticket-events' ),
                                'section'   => 'myticket_checkout_section',
                                'priority'  => 10,
                                'description' => esc_html__('Upon cart page force users to enter ticket holder email and full name to use it for QR-code scanning.', 'myticket-events' ),
                                'type'      => 'checkbox'
                                ) );
                                

    //checkout thank you page popup
    $wp_customize->add_setting( 'myticket_app_private', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_app_private', array(
                                'label'     => esc_html__( 'Private access', 'myticket-events' ),
                                'section'   => 'myticket_app_section',
                                'priority'  => 10,
                                'description' => esc_html__( 'Restrict access to apps with IDs listed below.', 'myticket-events' ),
                                'type'      => 'checkbox'
                                ) );

    //products per page
    $wp_customize->add_setting( 'myticket_app_ids', array(
                                'sanitize_callback' => 'myticket_events_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_app_ids', array(
                                'label'     => esc_html__( 'Allowed app IDs', 'myticket-events' ),
                                'section'   => 'myticket_app_section',
                                'priority'  => 10,
                                'description' => esc_html__( 'Open MyTicket app > Settings > Scroll down to find its unique ID number and provide it below. Specify only one ID per line. Ex.: E4RR5R6R.', 'myticket-events' ),
                                'type'      => 'textarea'
                                ) );

}
add_action( 'customize_register', 'myticket_events_customizer_register' );

function myticket_events_sanitize_text( $str ) {
    return wp_kses( $str, array( 
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
    ) );
}

function myticket_events_sanitize_textarea( $str ) {

    return wp_kses( $str, array( 
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'b' => array(),
        'tr' => array(),
        'th' => array(),
        'td' => array(),
        'em' => array(),
        'span' => array(
            'id' => array(),
            'class' => array(),),
        'i' => array( 
            'id' => array(),
            'class' => array(),),
        'strong' => array(),
        'span' => array(
            'href' => array(),
            'class' => array(),
        ),
        'div' => array(
            'id' => array(),
            'class' => array(),
        ),
    ) );
}

?>