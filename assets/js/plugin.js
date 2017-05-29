(function($) {
	"use strict";

	function wc_get_amount($currency) {
		return (wc_steem !== 'undefined' && 'cart' in wc_steem && 'amounts' in wc_steem.cart) ? wc_steem.cart.amounts[$currency] : -1;
	}

	$(document).on('change', 'select[name="wc_steem-amount_currency"]', function(event) {
		var $currency = this.value;
		var $amount = wc_get_amount($currency);

		if ($amount > -1) {
			$('#wc_steem-amount').html($amount);
		}
	});
})(jQuery);