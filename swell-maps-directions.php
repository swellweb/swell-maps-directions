<?php

/**
 * Plugin name: Swell Maps Directions
 * Text Domain: swell-maps-directions
 * Description: A plugin for integrate Maps with directions, with this plugin you can calculate the route from Point A to point B and add the whaypoints route between origin and end.
 * Author: Marco Caciotti
 * Author URI: https://www.swellweb.it
 * Domain Path: /languages/
 * Version: 1.0.0
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}


define  ("SWMAPS_PLUGIN_URL", plugin_dir_url(__FILE__) );
define  ("PLUGIN_PATH", plugin_dir_path(__FILE__) );
define( 'SWMAPS_PLUGIN', __FILE__ );
define( 'SWMAPS_PLUGIN_BASENAME', plugin_basename( SWMAPS_PLUGIN ) );
define( 'SWMAPS_PLUGIN_DIR', untrailingslashit( dirname( SWMAPS_PLUGIN ) ) );
require (SWMAPS_PLUGIN_DIR."/include/functions.php");
require (SWMAPS_PLUGIN_DIR."/include/type-itineraries.php");
require (SWMAPS_PLUGIN_DIR."/include/metabox-itineraries.php");
require (SWMAPS_PLUGIN_DIR."/include/shortcode.php");
require (SWMAPS_PLUGIN_DIR . "/include/admin/init.php");

$swMaps = new SwMapsInit();


?>