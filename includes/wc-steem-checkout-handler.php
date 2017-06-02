<?php
/**
 * WC_Steem_Checkout_Handler
 *
 * @package WooCommerce Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Steem_Checkout_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('wp_enqueue_scripts', array($instance, 'enqueue_scripts'));
	}

	public static function enqueue_scripts() {

		// Plugin
		wp_enqueue_script('wc-steem', WC_STEEM_DIR_URL . '/assets/js/plugin.js', array('jquery'), WC_STEEM_VERSION);

		// Localize plugin script data
		wp_localize_script('wc-steem', 'wc_steem', array(
			'cart' => array(
				'base_currency' => wc_steem_get_base_fiat_currency(),
				'amounts' => WC_Steem::get('amounts'),
			),
		));
	}
}

WC_Steem_Checkout_Handler::init();