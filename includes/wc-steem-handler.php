<?php
/**
 * WC_Steem_Handler
 *
 * @package WooCommerce Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Steem_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('init', array($instance, 'register_schedulers'));
	}

	public static function register_schedulers() {
		$instance = __CLASS__;

		if ( ! wp_next_scheduled('wc_steem_update_rates')) {
			wp_schedule_event(time(), '30min', 'wc_steem_update_rates');
		}

		if ( ! wp_next_scheduled('wc_steem_update_orders')) {
			wp_schedule_event(time(), '5min', 'wc_steem_update_orders');
		}

		if (empty(get_option('wc_steem_rates'))) {
			self::update_rates();
		}

		add_action('wc_steem_update_rates', array($instance, 'update_rates'));
		add_action('wc_steem_update_orders', array($instance, 'update_orders'));
	}

	public static function update_rates() {
		$rates = get_option('wc_steem_rates', array());

		$response = wp_remote_get('https://poloniex.com/public?command=returnTicker');

		if (is_array($response)) {
			$tickers = json_decode(wp_remote_retrieve_body($response), true);

			if (isset($tickers['USDT_BTC']['last'])) {
				$rates['BTC_USD'] = $tickers['USDT_BTC']['last'];

				if (isset($tickers['BTC_STEEM']['last'])) {
					$rates['STEEM_USD'] = $tickers['BTC_STEEM']['last'] * $rates['BTC_USD'];
				}

				if (isset($tickers['BTC_SBD']['last'])) {
					$rates['SBD_USD'] = $tickers['BTC_SBD']['last'] * $rates['BTC_USD'];
				}
			}
		}

		$response = wp_remote_get('http://api.fixer.io/latest?base=USD');

		if (is_array($response)) {
			$tickers = json_decode(wp_remote_retrieve_body($response), true);

			if (isset($tickers['rates']) && $tickers['rates']) {
				foreach ($tickers['rates'] as $to_currency_symbol => $to_currency_value) {
					$rates["USD_{$to_currency_symbol}"] = $to_currency_value;

					if (isset($rates['STEEM_USD'])) {
						$rates["STEEM_{$to_currency_symbol}"] = $rates['STEEM_USD'] * $to_currency_value;
					}

					if (isset($rates['SBD_USD'])) {
						$rates["SBD_{$to_currency_symbol}"] = $rates['SBD_USD'] * $to_currency_value;
					}
				}
			}
		}

		update_option('wc_steem_rates', $rates);
	}

	public static function update_orders() {

		$orders = get_posts(array(
			'post_type' => 'shop_order',
			'post_status' => 'wc-pending',
			'posts_per_page' => 20,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => '_payment_method',
					'value' => 'wc_steem',
					'compare' => '=',
				),
				array(
					'key' => '_wc_steem_transaction_transfer',
					'compare' => 'NOT EXISTS',
				),
			),
		));

		if (empty($orders) || is_wp_error($orders)) {
			return;
		}

		foreach ($orders as $order) {
			$order = wc_get_order($order);
			self::update_order($order);
		}
	}

	public static function update_order($order) {
		if (empty($order) || is_wp_error($order)) {
			return;
		}

		if ($order->payment_method != 'wc_steem') {
			return;
		}

		if ( ! empty(get_post_meta($order->id, '_wc_steem_transaction_transfer', true))) {
			return;
		}

		$transfer = WC_Steem_Transaction_Transfer::get($order);

		if ($transfer != null) {

			// Mark payment as completed
			$order->payment_complete();

			// Add intuitive order note
			$order->add_order_note(
				sprintf(
					__('WooCommerce Steem payment completed with transaction (ID: %s) and transfer (ID: %s) with the amount of %s %s by %s on %s.', 'wc-steem'), 
					$transfer['tx_id'], 
					$transfer['ID'], 
					$transfer['amount'], 
					$transfer['amount_symbol'], 
					$transfer['from'], 
					date('Y-m-d H:i:s', $transfer['timestamp'])
				)
			);

			update_post_meta($order->id, '_wc_steem_status', 'paid');
			update_post_meta($order->id, '_wc_steem_transaction_transfer', $transfer);
		}
	}
}

WC_Steem_Handler::init();
