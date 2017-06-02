<?php
/**
 * Plugin Name: WooCommerce Steem
 * Plugin URI: https://github.com/recrypto/woocommerce-steem
 * Description: Accept Steem payments directly to your shop (Currencies: STEEM, SBD).
 * Version: 1.0.2
 * Author: ReCrypto
 * Author URI: https://steemit.com/@recrypto
 * Requires at least: 4.1
 * Tested up to: 4.7.5
 *
 * Text Domain: wc-steem
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define('WC_STEEM_VERSION', '1.0.2');
define('WC_STEEM_DIR_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('WC_STEEM_DIR_URL', trailingslashit(plugin_dir_url(__FILE__)));

register_activation_hook(__FILE__, 'wc_steem_activate');
register_deactivation_hook(__FILE__, 'wc_steem_deactivate');

/** 
 * Plugin activation
 *
 * @since 1.0.0
 */
function wc_steem_activate() {
	do_action('wc_steem_activated');

	$settings = get_option('woocommerce_wc_steem_settings', array());

	if ( ! isset($settings['accepted_currencies'])) {
		$settings['accepted_currencies'] = array(
			'STEEM',
			'SBD',
		);
	}

	update_option('woocommerce_wc_steem_settings', $settings);

	// Make sure to have fresh currency rates
	update_option('wc_steem_rates', array());
}

/**
 * Plugin deactivation
 *
 * @since 1.0.0
 */
function wc_steem_deactivate() {
	do_action('wc_steem_deactivated');

	// Make sure to have fresh currency rates
	update_option('wc_steem_rates', array());
}

/**
 * Plugin init
 * 
 * @since 1.0.0
 */
function wc_steem_init() {

	/**
	 * Fires before including the files
	 *
	 * @since 1.0.0
	 */
	do_action('wc_steem_pre_init');

	require_once(WC_STEEM_DIR_PATH . 'libraries/wordpress.php');
	require_once(WC_STEEM_DIR_PATH . 'libraries/woocommerce.php');

	require_once(WC_STEEM_DIR_PATH . 'includes/wc-steem-functions.php');
	require_once(WC_STEEM_DIR_PATH . 'includes/class-wc-steem.php');
	require_once(WC_STEEM_DIR_PATH . 'includes/class-wc-steem-transaction-transfer.php');

	require_once(WC_STEEM_DIR_PATH . 'includes/class-wc-gateway-steem.php');

	require_once(WC_STEEM_DIR_PATH . 'includes/wc-steem-handler.php');
	require_once(WC_STEEM_DIR_PATH . 'includes/wc-steem-cart-handler.php');
	require_once(WC_STEEM_DIR_PATH . 'includes/wc-steem-checkout-handler.php');
	require_once(WC_STEEM_DIR_PATH . 'includes/wc-steem-order-handler.php');

	/**
	 * Fires after including the files
	 *
	 * @since 1.0.0
	 */
	do_action('wc_steem_init');
}
add_action('plugins_loaded', 'wc_steem_init');



/**
 * Register "WooCommerce Steem" as payment gateway in WooCommerce
 *
 * @since 1.0.0
 *
 * @param array $gateways
 * @return array $gateways
 */
function wc_steem_register_gateway($gateways) {
	$gateways[] = 'WC_Gateway_Steem';

	return $gateways;
}
add_filter('woocommerce_payment_gateways', 'wc_steem_register_gateway');
