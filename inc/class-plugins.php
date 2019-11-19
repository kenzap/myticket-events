<?php
/**
 * @package MyTicket
 * @basic setup
 */

    add_action( 'tgmpa_register', 'myticket_events_register_required_plugins' );

    function myticket_events_register_required_plugins() {

        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(

            array(
                'name'                  => 'Woocommerce', // The plugin name
                'slug'                  => 'woocommerce', // The plugin slug (typically the folder name)
                'required'              => true, // If false, the plugin is only 'recommended' instead of required
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url'          => 'https://wordpress.org/plugins/woocommerce/', // If set, overrides default API URL and points to an external URL
            ),
            array(
                'name'                  => 'CMB2', // The plugin name
                'slug'                  => 'cmb2', // The plugin slug (typically the folder name)
                'required'              => true, // If false, the plugin is only 'recommended' instead of required
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url'          => 'https://wordpress.org/plugins/cmb2/', // If set, overrides default API URL and points to an external URL
            ),
        );

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to pre-packaged plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php',            // Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'myticket-events' ),
                'menu_title'                      => __( 'Install Plugins', 'myticket-events' ),
                'installing'                      => __( 'Installing Plugin: %s', 'myticket-events' ), // %s = plugin name.
                'oops'                            => __( 'Something went wrong with the plugin API.', 'myticket-events' ),
                'notice_can_install_required'     => _n_noop(
                    'This theme requires the following plugin: %1$s.',
                    'This theme requires the following plugins: %1$s.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop(
                    'This theme recommends the following plugin: %1$s.',
                    'This theme recommends the following plugins: %1$s.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop(
                    'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                    'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_ask_to_update_maybe'      => _n_noop(
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop(
                    'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                    'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop(
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop(
                    'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                    'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                    'myticket-events'
                ), // %1$s = plugin name(s).
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'myticket-events'
                ),
                'update_link'                     => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'myticket-events'
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'myticket-events'
                ),
                'return'                          => __( 'Return to Required Plugins Installer', 'myticket-events' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'myticket-events' ),
                'activated_successfully'          => __( 'The following plugin was activated successfully:', 'myticket-events' ),
                'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'myticket-events' ),  // %1$s = plugin name(s).
                'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'myticket-events' ),  // %1$s = plugin name(s).
                'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'myticket-events' ), // %s = dashboard link.
                'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'tgmpa' ),
                'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
        );

        tgmpa( $plugins, $config );
    }

?>