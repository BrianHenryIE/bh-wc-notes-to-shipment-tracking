<?php
/**
 * Tests the function returns quickly when WooCommerce Shipment Tracking is absent.
 *
 * @package BH_WC_Notes_To_Shipment_Tracking
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Notes_To_Shipment_Tracking\woocommerce;



use WC_Order;
use WP_Comment;

/**
 * Class Plugin_WP_Mock_Test
 */
class Order_Unit_Test extends \Codeception\Test\Unit {

	protected function _before() {
		\WP_Mock::setUp();
	}

	/**
	 * Verify get_comment is never called.
	 */
	public function test_early_return() {

		\WP_Mock::userFunction(
			'get_comment',
			array(
				'args'   => array( 123 ),
				'times'  => 0,
				'return' => new \stdClass(),
			)
		);

		$order = new Order();

		$order->parse_shipment_tracking_from_note( 123, null );

	}

	public function test_happy_path() {

		global $project_root_dir;

		if( ! defined('WC_ABSPATH') ){
			define( 'WC_ABSPATH', "{$project_root_dir}/wp-content/plugins/woocommerce/" );
		}

		require_once "{$project_root_dir}/wp-content/plugins/woocommerce/includes/abstracts/abstract-wc-data.php";
		require_once "{$project_root_dir}/wp-content/plugins/woocommerce/includes/legacy/abstract-wc-legacy-order.php";
		require_once "{$project_root_dir}/wp-content/plugins/woocommerce/includes/traits/trait-wc-item-totals.php";
		require_once "{$project_root_dir}/wp-content/plugins/woocommerce/includes/abstracts/abstract-wc-order.php";
		require_once "{$project_root_dir}/wp-content/plugins/woocommerce/includes/class-wc-order.php";

		require_once __DIR__ . '/mock-WC_Shipment_Tracking.php';

		$note = 'This order has been shipped with: DHL Express. The Tracking Number is: 1234abc890. The Url is: http://www.dhl.com/cgi-bin/tracking.pl?AWB=1234abc890';

		$wc_order = $this->make( WC_Order::class, [
			'get_id' => 123
		]);

		// Class "WP_Comment" is declared "final" and cannot be mocked.
		$comment = new \stdClass();
		$comment->comment_content = $note;

		\WP_Mock::userFunction(
			'get_comment',
			array(
				'args'   => array( 123 ),
				'times'  => 1,
				'return' => $comment,
			)
		);

		// The real test.

		\WP_Mock::userFunction(
			'wc_st_add_tracking_number',
			array(
				'args'   => array( 123, '1234abc890', 'DHL Express', null, 'http://www.dhl.com/cgi-bin/tracking.pl?AWB=1234abc890' ),
				'times'  => 1
			)
		);


		$order = new Order();

		$order->parse_shipment_tracking_from_note( 123, $wc_order );

	}

	protected function _tearDown() {
		parent::_tearDown();
		\WP_Mock::tearDown();
	}
}
