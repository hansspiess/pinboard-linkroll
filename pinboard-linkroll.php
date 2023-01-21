<?php

/**
 * Pinboard Linkroll
 *
 * @package   Pinboard Linkroll 1
 * @author    Hans Spieß <info@hansspiess.de>
 * @license   GPL-2.0+
 * @link      http://www.hansspiess.de
 * @copyright 2014-2016 Hans Spieß
 *
 * @wordpress-plugin
 * Plugin Name:       Pinboard Linkroll
 * Plugin URI:        https://github.com/hansspiess/pinboard-linkroll
 * Description:       Showcase a list of https://pinboard.in links.
 * Version:           1.0.3
 * Author:            Hans Spieß
 * Author URI:        https://github.com/hansspiess
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pinboard-linkroll
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/hansspiess/pinboard-linkroll
 */
if ( defined( 'ABSPATH' ) && ! class_exists( 'Pinboard_Linkroll' ) ) {

  class Pinboard_Linkroll {

    public static $pinboard_linkroll = 'pinboard-linkroll';
    public static $version = '1.0.3';

    public function __construct() {

      $this->load_widget_class();

      add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
      add_action( 'widgets_init', function() {
        return register_widget("Pinboard_Linkroll_Widget");
      });

    }

    public function load_textdomain() {
      load_plugin_textdomain( 
        $this->get_pinboard_linkroll(), 
        false, 
        $this->get_pinboard_linkroll() . '/languages'
      );
    }

    public function get_pinboard_linkroll() {
      return self::$pinboard_linkroll;
    }

    /**
     * Load the widget class that handles plugin functionality.
     */
    public function load_widget_class() {
      require plugin_dir_path( __FILE__ ) . 'widget/class-pinboard-linkroll-widget.php';
    }

  }

  // Initialize and load the plugin.
  $Pinboard_Linkroll = new Pinboard_Linkroll();

}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pinboard-linkroll-activator.php
 */
function activate_pinboard_linkroll() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-pinboard-linkroll-activator.php';
  Pinboard_Linkroll_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pinboard-linkroll-deactivator.php
 */
function deactivate_pinboard_linkroll() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-pinboard-linkroll-deactivator.php';
  Pinboard_Linkroll_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pinboard_linkroll' );
register_deactivation_hook( __FILE__, 'deactivate_pinboard_linkroll' );
