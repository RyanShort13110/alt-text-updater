<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 * @author     Ryan S. <youremail@example.com>
 */
class Alt_Tag_Updater_Deactivator {

    /**
     * Runs during plugin deactivation.
     *
     * This function can be used to clean up settings or temporary data if needed.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Optional: Uncomment the line below if you want to remove the plugin's settings upon deactivation.
        // delete_option('alt_tag_updater_keywords');
    }
}
