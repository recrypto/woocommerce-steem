<?php
/**
 * WordPress Helpers
 *
 * @package WooCommerce Steem
 * @category Library
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/** WordPress helper functions *****************************************************************/


/**
 * Register new WordPress schedules
 *
 * @since 1.0.0
 * @param array $schedules
 * @return array $schedules
 */
function wc_steem_register_schedules($schedules) {

	for ($minutes = 5; $minutes < 55; $minutes += 5) {
		if ( ! isset($schedules["{$minutes}min"])) {
			$schedules["{$minutes}min"] = array(
				'interval' => $minutes * 60,
				'display' => __(sprintf('Once every %d minutes', $minutes), 'wordpress'),
			);
		}
	}

	return $schedules;
}
add_filter('cron_schedules', 'wc_steem_register_schedules');