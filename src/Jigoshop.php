<?php

namespace Pronamic\WordPress\Pay\Extensions\Jigoshop;

use Jigoshop_Base;

/**
 * Title: Jigoshop
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Jigoshop {
	/**
	 * Order status pending
	 *
	 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L269
	 * @var string
	 */
	const ORDER_STATUS_PENDING = 'pending';

	/**
	 * Order status on-hold
	 *
	 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L270
	 * @var string
	 */
	const ORDER_STATUS_ON_HOLD = 'on-hold';

	/**
	 * Order status processing
	 *
	 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L271
	 * @var string
	 */
	const ORDER_STATUS_PROCESSING = 'processing';

	/**
	 * Order status completed
	 *
	 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L272
	 * @var string
	 */
	const ORDER_STATUS_COMPLETED = 'completed';

	/**
	 * Order status refunded
	 *
	 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L273
	 * @var string
	 */
	const ORDER_STATUS_REFUNDED = 'refunded';

	/**
	 * Order status cancelled
	 *
	 * @link https://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L274
	 * @var string
	 */
	const ORDER_STATUS_CANCELLED = 'cancelled';

	/**
	 * Check if Jigoshop is active (Automattic/developer style)
	 *
	 * @link https://github.com/jigoshop/jigoshop/blob/1.8/jigoshop.php#L45
	 * @link https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'JIGOSHOP_VERSION' );
	}

	/**
	 * Get Jigoshop option
	 *
	 * Jigoshop did some changes on how to retrieve options.
	 *
	 * @param string $key Option key.
	 *
	 * @return boolean|string
	 */
	public static function get_option( $key ) {
		if ( method_exists( 'Jigoshop_Base', 'get_options' ) ) {
			$options = Jigoshop_Base::get_options();

			if ( method_exists( $options, 'get' ) ) {
				// @since Jigoshop v1.12
				// @link https://github.com/jigoshop/jigoshop/blob/1.12/gateways/bank_transfer.php
				$value = $options->get( $key );
			} elseif ( method_exists( $options, 'get_option' ) ) {
				// @since Jigoshop v1.3
				// @link https://github.com/jigoshop/jigoshop/blob/1.3/gateways/bank_transfer.php
				$value = $options->get_option( $key );
			} else {
				// @since Jigoshop v1.2
				// @link https://github.com/jigoshop/jigoshop/blob/1.2/gateways/bank_transfer.php
				$value = get_option( $key );
			}
		} else {
			$value = get_option( $key );
		}

		return $value;
	}
}
