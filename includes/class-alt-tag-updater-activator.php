<?php

/**
 * Fired during plugin activation
 *
 * @link       http://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 * @author     Ryan S. <youremail@example.com>
 */
class Alt_Tag_Updater_Activator {

    /**
     * Activation hook.
     *
     * Runs any setup required when the plugin is activated.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Initialize default settings if needed
        if ( false === get_option( 'alt_tag_updater_keywords' ) ) {
            add_option( 'alt_tag_updater_keywords', '' );
        }

        // Store plugin version for future updates
        if ( false === get_option( 'alt_tag_updater_version' ) ) {
            add_option( 'alt_tag_updater_version', '1.0.0' );
        }

        // Optional: Error logging for debugging (useful during development)
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'Alt Tag Updater activated successfully.' );
        }
    }
}
