<?php
/**
 * Plugin Name: Muambator Webhooks para WooCommerce
 * Plugin URI: https://www.muambator.com.br/
 * Description: Integração de webhooks do Muambator para entregas no WooCommerce.
 * Version: 1.1.0
 * Author: Muambator
 * Author URI: https://www.bode.io/
 * WC requires at least: 3.4.3
 * WC tested up to: 3.4.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Check if WooCommerce and WooCommerce Correios is active
 **/
$WCActive = in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option( 'active_plugins')) );
if ( $WCActive ) {
	if ( ! defined( 'MBWH_PLUGIN_FILE' ) ) {
		define( 'MBWH_PLUGIN_FILE', __FILE__ );
	}
	if ( ! class_exists( 'WC_MBWebhook' ) ) {
        include('WC_MBWebhook_class.php');
    }
}
