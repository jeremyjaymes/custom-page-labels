<?php
/*
Plugin Name: Custom Page Labels
Plugin URI: https://github.com/jeremyjaymes/custom-page-labels
Description: Custom page label admin column and metabox
Version: 0.1.0
Author: jeremyjaymes
Author URI: http://jeremyjaymes.com
Text Domain: pk_custom_page_label
Domain Path: /languages

License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2014 Jeremy Vossman (email : jeremy@papertreedesign.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class PK_Custom_Page_Labels {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   0.1.0
     *
     * @var     string
     */
    const VERSION = '0.1.0';

    /**
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    0.1.0
     * @var      string
     */
    protected $plugin_slug = 'pk_custom_page_label';

    /**
     * Instance of this class.
     *
     * @since    0.1.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * Stores the directory path for this plugin.
     *
     * @since  0.5.0
     * @access private
     * @var    string
     */
    private $directory_path;

    /**
     * Stores the directory URI for this plugin.
     *
     * @since  0.5.0
     * @access private
     * @var    string
     */
    private $directory_uri;

    /**
     * Sets up for initialization
     *
     * @since  1.0.0
     * @access public
     */
    private function __construct() {
        
        //* load the plugin translation files
        add_action( 'plugins_loaded', array( $this, 'textdomain' ) );

        //* Set up our paths.
        add_action( 'plugins_loaded', array( $this, 'setup' ), 1 );

        //* Load the core functions. 
        add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );
    }

    /**
     * Defines the directory path and URI for the plugin.
     *
     * @since  0.1.0
     * @access public
     */
    public function setup() {
        $this->directory_path = trailingslashit( plugin_dir_path( __FILE__ ) );
        $this->directory_uri  = trailingslashit( plugin_dir_url( __FILE__ ) );
    }

    /**
     * Loads the core files.
     *
     * @since  0.1.0
     * @access public
     */
    public function includes() {
        require_once( $this->directory_path . 'admin/class-custom-page-labels-metabox.php' );

    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
    }

    /**
     * Load the plugin's text domain
     *
     * @since 0.1.0
     *
     */
    public function textdomain() {

        $domain = $this->plugin_slug;
        
        load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    

    /**
     * Returns the instance.
     *
     * @since  0.1.0
     * @access public
     * @return object
     */
    public static function get_instance() {

        if ( !self::$instance )
            self::$instance = new self;

        return self::$instance;
    }

}
PK_Custom_Page_Labels::get_instance();