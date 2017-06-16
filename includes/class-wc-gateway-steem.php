<?php
/**
 * WC_Gateway_Steem
 *
 * @package WooCommerce Steem
 * @category Class
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Gateway_Steem extends WC_Payment_Gateway {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id                 = 'wc_steem';
		$this->has_fields         = true;
		$this->order_button_text  = __('Proceed to Steem', 'wc-steem');
		$this->method_title       = __('Steem', 'wc-steem' );
		$this->method_description = sprintf(__('Process payments via Steem.', 'wc-steem'), '<a href="' . admin_url('admin.php?page=wc-status') . '">', '</a>');
		$this->supports           = array(
			'products',
			'refunds'
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option('title');
		$this->description    = $this->get_option('description');
		$this->payee          = $this->get_option('payee');

		// WordPress hooks
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
	}


	# Backend

	/**
	 * Backend form settings
	 *
	 * @since 1.0.0
	 */
	public function init_form_fields() {

		if ($accepted_currencies = wc_steem_get_currencies()) {
			foreach ($accepted_currencies as $accepted_currency_key => $accepted_currency) {
				$accepted_currencies[$accepted_currency_key] = sprintf('%1$s (%2$s)', $accepted_currency, $accepted_currency_key);
			}
		}

		$this->form_fields = array(
			'enabled' => array(
				'title'   => __('Enable/Disable', 'wc-steem'),
				'type'    => 'checkbox',
				'label'   => __('Enable WooCommerce Steem', 'wc-steem'),
				'default' => 'yes'
			),
			'title' => array(
				'title'       => __('Title', 'wc-steem'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'wc-steem'),
				'default'     => __('Steem', 'wc-steem' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __('Description', 'wc-steem'),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => __('This controls the description which the user sees during checkout.', 'wc-steem'),
				'default'     => __('Pay via Steem', 'wc-steem')
			),
			'payee' => array(
				'title'       => __('Payee', 'wc-steem'),
				'type'        => 'text',
				'description' => __('This is your Steem username where your customers will pay you.', 'wc-steem'),
				'default'     => '',
				'desc_tip'    => true,
			),
			'accepted_currencies' => array(
				'title'       => __('Accepted Currencies', 'wc-steem'),
				'type'        => 'multiselect',
				'description' => __('Select the Steem currencies you will accept.', 'wc-steem'),
				'default'     => '',
				'desc_tip'    => true,
				'options'     => $accepted_currencies,
				'select_buttons' => true,
			),
			'show_insightful' => array(
				'title'   => __('Enable insightful prices on products', 'wc-steem'),
				'type'    => 'checkbox',
				'label'   => __('Shows an insightful prices on products that displays the accepted currencies such as SBD and/or STEEM rates converted from the product price.', 'wc-steem'),
				'default' => 'no'
			),
		);
	}


	# Frontend

	/**
	 * Frontend payment method fields
	 *
	 * @since 1.0.0
	 */
	public function payment_fields() {

		if ( ! $this->payee) {
			if (is_super_admin()) {
				_e('Please set your Steem username at the WooCommerce Settings to get paid via Steem.', 'wc-steem');
			}
			else {
				_e('Sorry, Steem payments is not available right now.', 'wc-steem');
			}
		}
		elseif ( ! wc_steem_get_accepted_currencies()) {
			if (is_super_admin()) {
				_e('Please set one or more accepted currencies at the WooCommerce Settings to get paid via Steem.', 'wc-steem');
			}
			else {
				_e('Sorry, Steem payments is not available right now.', 'wc-steem');
			}
		} else {
			$description = $this->get_description();

			if ($description) {
				echo wpautop(wptexturize(trim($description)));
			}

			if ( $this->supports( 'tokenization' ) && is_checkout() ) {
				$this->tokenization_script();
				$this->saved_payment_methods();
				$this->form();
				$this->save_payment_method_checkbox();
			} else {
				$this->form();
			}
		}
	}
	
	/**
	 * Frontend payment method form
	 *
	 * @since 1.0.0
	 */
	public function form() {

		$amount_currencies_html = '';

		if ($currencies = wc_steem_get_currencies()) {
			foreach ($currencies as $currency_symbol => $currency) {
				if (wc_steem_is_accepted_currency($currency_symbol)) {
					$amount_currencies_html .= sprintf('<option value="%s">%s</option>', $currency_symbol, $currency);
				}
			}
		}

		$default_fields = array(
			'amount' => '<p class="form-row form-row-wide">
				<label for="' . $this->field_id('amount') . '">' . esc_html__( 'Amount', 'wc-steem' ) . '</label>
				<span id="' . $this->field_id('amount') . '">' . WC_Steem::get_amount() . '</span>
			</p>',
			'amount_currency' => '<p class="form-row form-row-wide">
				<label for="' . $this->field_id('amount-currency') . '">' . esc_html__( 'Currency', 'wc-steem' ) . '</label>
				<select id="' . $this->field_id('amount-currency') . '"' . $this->field_name('amount_currency') . '>' . $amount_currencies_html . '</select>
			</p>',
		);

		$fields = wp_parse_args($fields, apply_filters('wc_steem_form_fields', $default_fields, $this->id)); ?>

		<fieldset id="<?php echo esc_attr($this->id); ?>-steem-form" class='wc-steem-form wc-payment-form'>
			<?php do_action('wc_steem_form_start', $this->id); ?>

			<?php foreach ($fields as $field) : ?>
					<?php echo $field; ?>
			<?php endforeach; ?>

			<?php do_action('wc_steem_form_end', $this->id); ?>

			<div class="clear"></div>
		</fieldset><?php
	}


	# Helpers

	/**
	 * Output field name HTML
	 *
	 * Gateways which support tokenization do not require names - we don't want the data to post to the server.
	 *
	 * @since 1.0.0
	 * @param string $name
	 * @return string
	 */
	public function field_name($name) {
		return $this->supports('tokenization') ? '' : ' name="' . $this->field_id($name) . '" ';
	}

	/**
	 * Construct field identifier
	 *
	 * @since 1.0.0
	 * @param string $key
	 * @return string
	 */
	public function field_id($key) {
		return esc_attr(sprintf('%s-%s', $this->id, $key));
	}


	/**
	 * Get gateway icon.
	 * @return string
	 */
	public function get_icon() {
		$icon_html = '';
		$icon      = apply_filters('wc_steem_icon', WC_STEEM_DIR_URL . '/assets/img/steem-64.png');

		$icon_html .= '<img src="' . esc_attr($icon) . '" alt="' . esc_attr__('Steem acceptance mark', 'wc-steem') . '" />';

		return apply_filters('woocommerce_gateway_icon', $icon_html, $this->id);
	}


	# Handlers

	/**
	 * Process payment
	 *
	 * Validation takes place by querying transactions to Steemful API
	 *
	 * @since 1.0.0
	 * @param int $order_id
	 * @return array $response
	 */
	public function process_payment($order_id) {
		$response = null;

		$order = new WC_Order($order_id);

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		WC()->cart->empty_cart();

		if (empty(get_post_meta($order_id, '_wc_steem_memo', true))) {
			update_post_meta($order_id, '_wc_steem_payee', WC_Steem::get_payee());
			update_post_meta($order_id, '_wc_steem_amount', WC_Steem::get_amount());
			update_post_meta($order_id, '_wc_steem_amount_currency', WC_Steem::get_amount_currency());
			update_post_meta($order_id, '_wc_steem_memo', WC_Steem::get_memo());

			update_post_meta($order->id, '_wc_steem_status', 'pending');

			WC_Steem::reset();
		}

		$response = array(
			'result' => 'success',
			'redirect' => $this->get_return_url($order)
		);

		return $response;
	}

	/**
	 * Validate frontend fields
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function validate_fields() {

		$amount_currency = isset($_POST[$this->field_id('amount_currency')]) ? $_POST[$this->field_id('amount_currency')] : 'STEEM';
		$from_currency_symbol = wc_steem_get_base_fiat_currency();

		if (wc_steem_is_accepted_currency($amount_currency)) {
			WC_Steem::set_amount_currency($amount_currency);

			if ($amounts = WC_Steem::get('amounts')) {
				if (isset($amounts[WC_Steem::get_amount_currency() . '_' . $from_currency_symbol])) {
					WC_Steem::set_amount($amounts[WC_Steem::get_amount_currency() . '_' . $from_currency_symbol]);
				}
			}
		}

		if (empty(WC_Steem::get_memo())) {
			WC_Steem::set_memo();
		}

		WC_Steem::set_payee($this->payee);
		
		return true;
	}

	/**
	 * Cannot be refunded
	 *
	 * @since 1.0.0
	 * @param WC_Order $order
	 * @return boolean
	 */
	public function can_refund_order($order) {
		return get_post_meta($order->id, '_payment_method', true) == 'wc_steem' && false;
	}
}
