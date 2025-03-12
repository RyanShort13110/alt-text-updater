<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 * @author     Ryan S. <email@example.com>
 */
class Alt_Tag_Updater_i18n {

    /**
     * Constructor to initialize i18n functionality.
     *
     * @since    1.0.0
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'alt-tag-updater',
            false,
            plugin_basename(dirname(__DIR__)) . '/languages/'
        );
    }
}
