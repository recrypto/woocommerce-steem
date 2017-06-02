<?php
/**
 * WooCommerce Helpers
 *
 * @package WooCommerce Steem
 * @category Library
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/** WooCommerce helper functions *****************************************************************/


if ( ! function_exists('wc_get_order_user_id')) :

/**
 * Retrieve order's custom user id
 *
 * @since 1.0.0
 *
 * @param mixed $order
 * @return int $user_id
 */
function wc_get_order_user_id($order) {

	if (is_int($order)) {
		$order_id = $order;
	} elseif ($order instanceof WC_Order) {
		$order_id = $order->id;
	} elseif ($order instanceof WP_Post) {
		$order_id = $order->ID;
	}

	return get_post_meta($order_id, '_customer_user', true);
}

endif;


if ( ! function_exists('wc_get_currency_symbol')) :

/**
 * Retrieve shop's base currency symbol
 *
 * @since 1.0.1
 * @return string
 */
function wc_get_currency_symbol() {
	return apply_filters('wc_currency_symbol', get_option('woocommerce_currency', 'USD'));
}

endif;