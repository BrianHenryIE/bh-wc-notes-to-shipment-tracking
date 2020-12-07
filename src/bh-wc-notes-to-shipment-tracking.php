<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           BH_WC_Notes_To_Shipment_Tracking
 *
 * @wordpress-plugin
 * Plugin Name:       Notes to Shipment Tracking
 * Plugin URI:        http://github.com/BrianHenryIE/bh-wc-notes-to-shipment-tracking/
 * Description:       Parses order notes for DHL tracking information and adds it to WooCommerce Shipment Tracking.
 * Version:           1.0.0
 * Author:            Brian Henry
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bh-wc-notes-to-shipment-tracking
 * Domain Path:       /languages
 */

namespace BH_WC_Notes_To_Shipment_Tracking;

use BH_WC_Notes_To_Shipment_Tracking\includes\BH_WC_Notes_To_Shipment_Tracking;
use BH_WC_Notes_To_Shipment_Tracking\BrianHenryIE\WPPB\WPPB_Loader;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BH_WC_NOTES_TO_SHIPMENT_TRACKING_VERSION', '1.0.0' );


/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function instantiate_bh_wc_notes_to_shipment_tracking() {

	$loader = new WPPB_Loader();
	$plugin = new BH_WC_Notes_To_Shipment_Tracking( $loader );

	return $plugin;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and frontend-facing site hooks.
 */
$GLOBALS['bh_wc_notes_to_shipment_tracking'] = $bh_wc_notes_to_shipment_tracking = instantiate_bh_wc_notes_to_shipment_tracking();
$bh_wc_notes_to_shipment_tracking->run();
