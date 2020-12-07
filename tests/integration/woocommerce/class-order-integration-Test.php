<?php
/**
 *
 *
 * @package BH_WC_Notes_To_Shipment_Tracking
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Notes_To_Shipment_Tracking\woocommerce;

use WC_Order;

/**
 *
 */
class Order_Integration_Test extends \Codeception\TestCase\WPTestCase {


	/**
	 * When an order is marked paid, with accompanying DHL note, check the shipment tracking is added to the email.
	 */
	public function test_happy_path() {

		$order = new WC_Order();
		$order->save();

		$note = 'This order has been shipped with: DHL Express. The Tracking Number is: 1234abc890. The Url is: http://www.dhl.com/cgi-bin/tracking.pl?AWB=1234abc890';

		$tracking_present_in_email = false;

		/**
		 * @param array $args A compacted array of wp_mail() arguments, including the "to" email,
		 *                    subject, message, headers, and attachments values.
		 *
		 * @return array
		 */
		$check_mail = function( array $args ) use (&$tracking_present_in_email) {

			$tracking_present_in_email = (false !== strstr( $args['message'],'1234abc890' ));

			return $args;
		};

		add_filter( 'wp_mail', $check_mail, 10, 1 );

		$order->update_status('completed', $note );

		$this->assertTrue( $tracking_present_in_email );

	}

}
