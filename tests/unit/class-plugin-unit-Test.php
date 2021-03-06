<?php
/**
 * Tests for the root plugin file.
 *
 * @package BH_WC_Notes_To_Shipment_Tracking
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Notes_To_Shipment_Tracking;

use BH_WC_Notes_To_Shipment_Tracking\includes\BH_WC_Notes_To_Shipment_Tracking;

/**
 * Class Plugin_WP_Mock_Test
 */
class Plugin_Unit_Test extends \Codeception\Test\Unit {

	/**
	 * @var callable An autoloader to prevent BH_WC_REST_Change_Units class being loaded.
	 */
	protected $autoloader;

	protected function _before() {
		\WP_Mock::setUp();

		$this->autoloader = function ( $classname ) {
			$autoload_classmap = array(
				'BH_WC_Notes_To_Shipment_Tracking\\includes\\BH_WC_Notes_To_Shipment_Tracking' => __DIR__ . "/mock.php",
			);
			if ( array_key_exists( $classname, $autoload_classmap ) && file_exists( $autoload_classmap[ $classname ] ) ) {
				require_once $autoload_classmap[ $classname ];
			}
		};

		spl_autoload_register( $this->autoloader );

	}

	/**
	 * Verifies the plugin initialization.
	 *
	 */
	public function test_plugin_include() {


		$plugin_root_dir = dirname( __DIR__, 2 ) . '/src';

		\WP_Mock::userFunction(
			'plugin_dir_path',
			array(
				'args'   => array( \WP_Mock\Functions::type( 'string' ) ),
				'return' => $plugin_root_dir . '/',
			)
		);

		\WP_Mock::userFunction(
			'register_activation_hook'
		);

		\WP_Mock::userFunction(
			'register_deactivation_hook'
		);

		require_once $plugin_root_dir . '/bh-wc-notes-to-shipment-tracking.php';

		$this->assertArrayHasKey( 'bh_wc_notes_to_shipment_tracking', $GLOBALS );

		$this->assertInstanceOf( BH_WC_Notes_To_Shipment_Tracking::class, $GLOBALS['bh_wc_notes_to_shipment_tracking'] );

	}


	/**
	 * Verifies the plugin does not output anything to screen.
	 */
	public function test_plugin_include_no_output() {

		$plugin_root_dir = dirname( __DIR__, 2 ) . '/src';

		\WP_Mock::userFunction(
			'plugin_dir_path',
			array(
				'args'   => array( \WP_Mock\Functions::type( 'string' ) ),
				'return' => $plugin_root_dir . '/',
			)
		);

		\WP_Mock::userFunction(
			'register_activation_hook'
		);

		\WP_Mock::userFunction(
			'register_deactivation_hook'
		);

		ob_start();

		require_once $plugin_root_dir . '/bh-wc-notes-to-shipment-tracking.php';

		$printed_output = ob_get_contents();

		ob_end_clean();

		$this->assertEmpty( $printed_output );

	}

	protected function _tearDown() {
		parent::_tearDown();

		spl_autoload_unregister( $this->autoloader );
	}

}
