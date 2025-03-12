<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option( 'alt_tag_updater_keywords' );
delete_option( 'alt_tag_updater_version' );

// Multisite support
if ( is_multisite() ) {
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		delete_option( 'alt_tag_updater_keywords' );
		delete_option( 'alt_tag_updater_version' );
		restore_current_blog();
	}
}
