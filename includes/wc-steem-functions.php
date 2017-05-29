<?php
/**
 * WooCommerce Steem Helpers
 *
 * @package WooCommerce Steem
 * @category Helper
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Retrieve Steem currencies
 *
 * @since 1.0.0
 * @return array
 */
function wc_steem_get_currencies() {
	return apply_filters('wc_steem_currencies', array(
		'STEEM' => 'Steem',
	));
}

/**
 * Retrieve payment method settings
 *
 * @since 1.0.0
 * @return array
 */
function wc_steem_get_settings() {
	return get_option('woocommerce_wc_steem_settings', array());
}

/**
 * Retrieve single payment method settings
 *
 * @since 1.0.0
 * @return mixed
 */
function wc_steem_get_setting($key) {
	$settings = wc_steem_get_settings();

	return isset($settings[$key]) ? $settings[$key] : null;
}

/**
 * Retrieve Steem accepted currencies
 *
 * @since 1.0.0
 * @return array
 */
function wc_steem_get_accepted_currencies() {
	$accepted_currencies = wc_steem_get_setting('accepted_currencies');

	return apply_filters('wc_steem_accepted_currencies', $accepted_currencies ? $accepted_currencies : array());
}

/**
 * Check if the Steem payment method settings has accepted currencies
 *
 * @since 1.0.0
 * @return array
 */
function wc_steem_has_accepted_currencies() {
	return ( ! empty(wc_steem_get_accepted_currencies()));
}

/**
 * Check currency is accepted on Steem payment method
 *
 * @since 1.0.0
 * @param string $currency_symbol
 * @return boolean
 */
function wc_steem_is_accepted_currency($currency_symbol) {
	$currencies = wc_steem_get_accepted_currencies();
	return in_array($currency_symbol, $currencies);
}


# Rates

/**
 * Retrieve Steem rates in USD
 *
 * @since 1.0.0
 * @return array
 */
function wc_steem_get_rates() {
	return get_option('wc_steem_rates', array());
}

/**
 * Retrieve rate
 *
 * @since 1.0.0
 * @return float
 */
function wc_steem_get_rate($currency_symbol) {
	$rates = wc_steem_get_rates();
	return apply_filters('wc_steem_rate', isset($rates[$currency_symbol]) ? $rates[$currency_symbol] : null, $currency_symbol);
}

/**
 * Convert the amount in USD to crypto amount
 *
 * @since 1.0.0
 * @return float
 */
function wc_steem_rate_convert($amount, $currency_symbol) {
	$rate = wc_steem_get_rate($currency_symbol);
	return apply_filters('wc_steem_rate_convert', $rate > 0 ? $amount / $rate : 0, $amount, $currency_symbol);
}


# Order functions

/**
 * Retrieve order's Steem payee username
 *
 * @since 1.0.0
 * @param int $order_id
 * @return string
 */
function wc_order_get_steem_payee($order_id) {
	return apply_filters('wc_order_steem_payee', get_post_meta($order_id, '_wc_steem_payee', true), $order_id);
}

/**
 * Retrieve order's Steem memo
 *
 * @since 1.0.0
 * @param int $order_id
 * @return string
 */
function wc_order_get_steem_memo($order_id) {
	return apply_filters('wc_order_steem_memo', get_post_meta($order_id, '_wc_steem_memo', true), $order_id);
}

/**
 * Retrieve order's Steem amount
 *
 * @since 1.0.0
 * @param int $order_id
 * @return string
 */
function wc_order_get_steem_amount($order_id) {
	return apply_filters('wc_order_steem_amount', get_post_meta($order_id, '_wc_steem_amount', true), $order_id);
}

/**
 * Retrieve order's Steem amount currency
 *
 * @since 1.0.0
 * @param int $order_id
 * @return string
 */
function wc_order_get_steem_amount_currency($order_id) {
	return apply_filters('wc_order_steem_amount_currency', get_post_meta($order_id, '_wc_steem_amount_currency', true), $order_id);
}

/**
 * Retrieve order's Steem status
 *
 * @since 1.0.0
 * @param int $order_id
 * @return string
 */
function wc_order_get_steem_status($order_id) {
	return apply_filters('wc_order_steem_status', get_post_meta($order_id, '_wc_steem_status', true), $order_id);
}