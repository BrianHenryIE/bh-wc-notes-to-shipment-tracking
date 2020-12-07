<?php
/**
 * Class Plugin_Test. Tests the root plugin setup.
 *
 * @package BH_WC_Notes_To_Shipment_Tracking
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Notes_To_Shipment_Tracking;

use BH_WC_Notes_To_Shipment_Tracking\includes\BH_WC_Notes_To_Shipment_Tracking;

/**
 * Verifies the plugin has been instantiated and added to PHP's $GLOBALS variable.
 */
class Plugin_Integration_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Test the main plugin object is added to PHP's GLOBALS and that it is the correct class.
	 */
	public function test_plugin_instantiated() {

		$this->assertArrayHasKey( 'bh_wc_notes_to_shipment_tracking', $GLOBALS );

		$this->assertInstanceOf( BH_WC_Notes_To_Shipment_Tracking::class, $GLOBALS['bh_wc_notes_to_shipment_tracking'] );
	}

}
