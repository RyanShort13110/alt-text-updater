<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/admin
 * @author     Ryan S. <email@example.com>
 */
class Alt_Tag_Updater_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name    The name of this plugin.
     * @param    string    $version        The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        $css_file = plugin_dir_path( __FILE__ ) . 'css/alt-tag-updater-admin.css';
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/alt-tag-updater-admin.css',
            array(),
            filemtime( $css_file ) // Cache busting with file modification time
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        $js_file = plugin_dir_path( __FILE__ ) . 'js/alt-tag-updater-admin.js';
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/alt-tag-updater-admin.js',
            array( 'jquery' ),
            filemtime( $js_file ), // Cache busting with file modification time
            true // Load in footer for better performance
        );
    }
}
