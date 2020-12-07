<?php
/**
 * Parses order notes for shipping info.
 *
 * @package    BH_WC_Notes_To_Shipment_Tracking
 * @subpackage BH_WC_Notes_To_Shipment_Tracking/woocommerce
 */

namespace BH_WC_Notes_To_Shipment_Tracking\woocommerce;

use WC_Order;
use WC_Shipment_Tracking;

/**
 * Runs a regex on each order note as it is added, if there is a match it adds the tracking data to WooCommerce Shipment Tracking.
 *
 * Class Order
 *
 * @package BH_WC_Notes_To_Shipment_Tracking\woocommerce
 */
class Order {

	/**
	 * Runs preg_match on the order note and if it matches, adds the tracking number to WooCommerce Shipment Tracking.
	 *
	 * @hooked woocommerce_order_note_added
	 * @see WC_Order::add_order_note()
	 *
	 * @see https://www.phpliveregex.com/p/yf3
	 *
	 * @param int      $comment_id Id number of the WordPress comment.
	 * @param WC_Order $order      WooCommerce order the note was added to.
	 */
	public function parse_shipment_tracking_from_note( $comment_id, $order ): void {

		if ( ! class_exists( WC_Shipment_Tracking::class ) ) {

			return;
		}

		$comment = get_comment( $comment_id );

		$note = $comment->comment_content;

		if ( 1 === preg_match( '/^This order has been shipped with:\s(?<name>.*)\. The Tracking Number is:\s(?<number>.*)\. The Url is: (?<url>.*)$/U', $note, $matches ) ) {

			$tracking_number = $matches['number'];
			$provider        = $matches['name'];
			$custom_url      = $matches['url'];

			wc_st_add_tracking_number( $order->get_id(), $tracking_number, $provider, null, $custom_url );
		}

	}
}
