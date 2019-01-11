<?php

namespace Pronamic\WordPress\Pay\Extensions\Jigoshop;

use jigoshop_order;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Jigoshop WordPress pay extension
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Extension {
	/**
	 * Slug
	 *
	 * @var string
	 */
	const SLUG = 'jigoshop';

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'init', array( __CLASS__, 'init' ) );

		/*
		 * Gateways need to add themselves to this filter -prior- to the 'init' action hook.
		 *
		 * @link https://github.com/jigoshop/jigoshop/blob/1.8/gateways/gateways.class.php#L34
		 */
		add_filter( 'jigoshop_payment_gateways', array( __CLASS__, 'payment_gateways' ) );
	}

	/**
	 * Initialize
	 */
	public static function init() {
		if ( ! Jigoshop::is_active() ) {
			return;
		}

		$slug = self::SLUG;

		add_action( 'pronamic_payment_status_update_' . $slug, array( __CLASS__, 'update_status' ), 10, 2 );
		add_filter( 'pronamic_payment_source_text_' . $slug, array( __CLASS__, 'source_text' ), 10, 2 );
		add_filter( 'pronamic_payment_source_description_' . self::SLUG, array( __CLASS__, 'source_description' ), 10, 2 );
		add_filter( 'pronamic_payment_source_url_' . self::SLUG, array( __CLASS__, 'source_url' ), 10, 2 );
	}

	/**
	 * Add the gateway to Jigoshop
	 *
	 * @param array $methods Payment gateways.
	 *
	 * @return array
	 */
	public static function payment_gateways( $methods ) {
		$methods[] = 'Pronamic\WordPress\Pay\Extensions\Jigoshop\IDealGateway';

		return $methods;
	}

	/**
	 * Update lead status of the specified payment
	 *
	 * @param Payment $payment      Payment.
	 * @param bool    $can_redirect Can redirect.
	 */
	public static function update_status( Payment $payment, $can_redirect = false ) {
		$id = $payment->get_source_id();

		$order = new jigoshop_order( (int) $id );
		$data  = new PaymentData( $order );

		$should_update = ! in_array(
			$order->status,
			array(
				Jigoshop::ORDER_STATUS_COMPLETED,
				Jigoshop::ORDER_STATUS_PROCESSING,
			),
			true
		);

		if ( $should_update ) {
			$url = $data->get_normal_return_url();

			switch ( $payment->status ) {
				case Statuses::CANCELLED:
					$order->update_status( Jigoshop::ORDER_STATUS_CANCELLED, __( 'iDEAL payment cancelled.', 'pronamic_ideal' ) );

					$url = $data->get_cancel_url();

					break;
				case Statuses::EXPIRED:
					/*
					 * Jigoshop PayPal gateway uses 'on-hold' order status for an 'expired' payment.
					 *
					 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/gateways/paypal.php#L430
					 */
					$order->update_status( Jigoshop::ORDER_STATUS_ON_HOLD, __( 'iDEAL payment expired.', 'pronamic_ideal' ) );

					break;
				case Statuses::FAILURE:
					/*
					 * Jigoshop PayPal gateway uses 'on-hold' order status for an 'failure' in the payment.
					 *
					 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/gateways/paypal.php#L431
					 */
					$order->update_status( 'failed', __( 'iDEAL payment failed.', 'pronamic_ideal' ) );

					break;
				case Statuses::SUCCESS:
					// Payment completed.
					$order->add_order_note( __( 'iDEAL payment completed.', 'pronamic_ideal' ) );
					$order->payment_complete();

					$url = $data->get_success_url();

					break;
				case Statuses::OPEN:
					$order->add_order_note( __( 'iDEAL payment open.', 'pronamic_ideal' ) );

					break;
				default:
					$order->add_order_note( __( 'iDEAL payment unknown.', 'pronamic_ideal' ) );

					break;
			}

			if ( $url && $can_redirect ) {
				wp_redirect( $url );

				exit;
			}
		}
	}

	/**
	 * Source text.
	 *
	 * @param string  $text    Source text.
	 * @param Payment $payment Payment.
	 *
	 * @return string
	 */
	public static function source_text( $text, Payment $payment ) {
		$text = __( 'Jigoshop', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->get_source_id() ),
			sprintf(
				/* translators: %s: payment source id */
				__( 'Order #%s', 'pronamic_ideal' ),
				$payment->get_source_id()
			)
		);

		return $text;
	}

	/**
	 * Source description.
	 *
	 * @param string  $description Source description.
	 * @param Payment $payment     Payment.
	 *
	 * @return string
	 */
	public static function source_description( $description, Payment $payment ) {
		return __( 'Jigoshop Order', 'pronamic_ideal' );
	}

	/**
	 * Source URL.
	 *
	 * @param string  $url     Source URL.
	 * @param Payment $payment Payment.
	 *
	 * @return null|string
	 */
	public static function source_url( $url, Payment $payment ) {
		return get_edit_post_link( $payment->get_source_id() );
	}
}
