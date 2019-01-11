<?php

namespace Pronamic\WordPress\Pay\Extensions\Jigoshop;

use jigoshop_order;
use Pronamic\WordPress\Pay\Payments\PaymentData as Pay_PaymentData;
use Pronamic\WordPress\Pay\Payments\Item;
use Pronamic\WordPress\Pay\Payments\Items;

/**
 * Title: Jigoshop iDEAL payment data
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.1
 * @since   1.0.0
 */
class PaymentData extends Pay_PaymentData {
	/**
	 * Order
	 *
	 * @see plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php
	 * @var jigoshop_order
	 */
	private $order;

	/**
	 * Construct and intializes an Jigoshop iDEAL data proxy
	 *
	 * @param jigoshop_order $order Jigoshop order.
	 */
	public function __construct( $order ) {
		parent::__construct();

		$this->order = $order;
	}

	/**
	 * Get source indicatir
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'jigoshop';
	}

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L50
		return sprintf(
			/* translators: %s: order id */
			__( 'Order %s', 'pronamic_ideal' ),
			$this->order->id
		);
	}

	/**
	 * Get order ID
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L50
		return $this->order->id;
	}

	/**
	 * Get items
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_items()
	 * @return Items
	 */
	public function get_items() {
		// Items.
		$items = new Items();

		// Item.
		// We only add one total item, because iDEAL cant work with negative price items (discount).
		$item = new Item();
		$item->set_number( $this->order->id );
		$item->set_description(
			sprintf(
				/* translators: %s: order id */
				__( 'Order %s', 'pronamic_ideal' ),
				$this->order->id
			)
		);
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L98
		// @link https://github.com/jigoshop/jigoshop/blob/dev/classes/jigoshop_order.class.php#L124
		$item->set_price( $this->order->order_total );
		$item->set_quantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	/**
	 * Get currency alphabetic code.
	 *
	 * @return bool|string
	 */
	public function get_currency_alphabetic_code() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/admin/jigoshop-admin-settings-options.php#L421
		// @link https://github.com/jigoshop/jigoshop/blob/1.12/jigoshop.php#L1247-L1255
		return Jigoshop::get_option( 'jigoshop_currency' );
	}

	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function get_email() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L71
		return $this->order->billing_email;
	}

	/**
	 * Get first name.
	 *
	 * @return string
	 */
	public function get_first_name() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L62
		return $this->order->billing_first_name;
	}

	/**
	 * Get last name.
	 *
	 * @return string
	 */
	public function get_last_name() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L62
		return $this->order->billing_last_name;
	}

	/**
	 * Get customer name.
	 *
	 * @return string
	 */
	public function get_customer_name() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L62
		return $this->order->billing_first_name . ' ' . $this->order->billing_last_name;
	}

	/**
	 * Get address.
	 *
	 * @return null
	 */
	public function get_address() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L65
		return $this->order->billing_address_1;
	}

	/**
	 * Get city.
	 *
	 * @return null
	 */
	public function get_city() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L67
		return $this->order->billing_city;
	}

	/**
	 * Get zip.
	 *
	 * @return null
	 */
	public function get_zip() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L68
		return $this->order->billing_postcode;
	}

	/**
	 * Get normal return url.
	 *
	 * @return string
	 */
	public function get_normal_return_url() {
		return add_query_arg(
			array(
				'key'   => $this->order->order_key,
				'order' => $this->order->id,
			),
			// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/jigoshop.php#L442
			get_permalink( jigoshop_get_page_id( 'view_order' ) )
		);
	}

	/**
	 * Get cancel url.
	 *
	 * @return mixed|string|void
	 */
	public function get_cancel_url() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L320
		return $this->order->get_cancel_order_url();
	}

	/**
	 * Get success url.
	 *
	 * @return string
	 */
	public function get_success_url() {
		return add_query_arg(
			array(
				'key'   => $this->order->order_key,
				'order' => $this->order->id,
			),
			// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/jigoshop.php#L442
			get_permalink( jigoshop_get_page_id( 'thanks' ) )
		);
	}

	/**
	 * Get error url.
	 *
	 * @return string
	 */
	public function get_error_url() {
		// @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.1.1/classes/jigoshop_order.class.php#L309
		return $this->order->get_checkout_payment_url();
	}
}
