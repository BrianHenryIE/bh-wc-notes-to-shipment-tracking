<?php
/**
 * Tests for BH_WC_Notes_To_Shipment_Tracking main setup class. Tests the actions are correctly added.
 *
 * @package BH_WC_Notes_To_Shipment_Tracking
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Notes_To_Shipment_Tracking\includes;

use BH_WC_Notes_To_Shipment_Tracking\woocommerce\Order;

/**
 * Class Develop_Test
 */
class BH_WC_Notes_To_Shipment_Tracking_Integration_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Verify action to call load textdomain is added.
	 */
	public function test_action_plugins_loaded_load_plugin_textdomain() {

		$action_name       = 'plugins_loaded';
		$expected_priority = 10;
		$class_type        = I18n::class;
		$method_name       = 'load_plugin_textdomain';

		$function_is_hooked = $this->is_function_hooked_on_action( $class_type, $method_name, $action_name, $expected_priority );

		$this->assertNotFalse( $function_is_hooked );
	}

	/**
	 *
	 */
	public function test_action_woocommerce_order_note_added() {

		$action_name       = 'woocommerce_order_note_added';
		$expected_priority = 10;
		$class_type        = Order::class;
		$method_name       = 'parse_shipment_tracking_from_note';

		$function_is_hooked = $this->is_function_hooked_on_action( $class_type, $method_name, $action_name, $expected_priority );

		$this->assertNotFalse( $function_is_hooked );
	}


	protected function is_function_hooked_on_action( $class_type, $method_name, $action_name, $expected_priority = 10 ) {

		global $wp_filter;

		$this->assertArrayHasKey( $action_name, $wp_filter, "$method_name definitely not hooked to $action_name" );

		$actions_hooked = $wp_filter[ $action_name ];

		$this->assertArrayHasKey( $expected_priority, $actions_hooked, "$method_name definitely not hooked to $action_name priority $expected_priority" );

		$hooked_method = null;
		foreach ( $actions_hooked[ $expected_priority ] as $action ) {
			$action_function = $action['function'];
			if ( is_array( $action_function ) ) {
				if ( $action_function[0] instanceof $class_type ) {
					if( $method_name === $action_function[1] ) {
						$hooked_method = $action_function[1];
						break;
					}
				}
			}
		}

		$this->assertNotNull( $hooked_method, "No methods on an instance of $class_type hooked to $action_name" );

		$this->assertEquals( $method_name, $hooked_method, "Unexpected method name for $class_type class hooked to $action_name" );

		return true;
	}
}
