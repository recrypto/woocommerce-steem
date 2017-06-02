<?php
/**
 * WC_Steem_Cart_Handler
 *
 * @package WooCommerce Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Steem_Cart_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('woocommerce_after_calculate_totals', array($instance, 'calculate_totals'), 1000);
	}

	public static function calculate_totals($cart) {
		if (empty($cart) || is_wp_error($cart)) {
			return;
		}

		$amounts = array();
		$from_currency_symbol = wc_steem_get_base_fiat_currency();

		if ($currencies = wc_steem_get_currencies()) {
			foreach ($currencies as $to_currency_symbol => $currency) {
				$amount = wc_steem_rate_convert($cart->total, $from_currency_symbol, $to_currency_symbol);

				if ($amount <= 0) {
					continue;
				}

				if (WC_Steem::get('amount_currency') == $to_currency_symbol) {
					WC_Steem::set('amount', $amount);
				}

				$amounts["{$to_currency_symbol}_{$from_currency_symbol}"] = $amount;
			}

			foreach ($currencies as $to_currency_symbol => $currency) {
				if ( ! isset($amounts["{$to_currency_symbol}_{$from_currency_symbol}"])) {
					$amounts["{$to_currency_symbol}_{$from_currency_symbol}"] = $cart->total;
					WC_Steem::set('amount', $cart->total);
				}
			}

			WC_Steem::set('amounts', $amounts);
		}
	}
}

WC_Steem_Cart_Handler::init();