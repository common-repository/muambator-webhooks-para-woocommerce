<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WC_MBWebhook {

    public function __construct() {
        add_action( 'woocommerce_init', array( $this, 'woocommerce_loaded' ) );
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
    }

    public function woocommerce_loaded() {
	    /**
	     * MB Webhooks DB start
	     */
	    include('includes/mb-webhook-db.php');
	    /**
	     * Endpoint and process to receive Webhook infos
	     */
	    include('includes/mb-webhook-process.php');
    }

    public function plugins_loaded() {
	    /**
	     * Templates to load the MB Webhook form
	     */
	    include('templates/muambator-webhook.php');
	    /**
	     * Templates to load the MB Webhook CSV export
	     */
	    include('templates/mbwebhooks-csv-export.php');
	    /**
	     * Block of informations in order
	     */
	    include ('templates/mbwebhooks-order-block.php');
	    /**
	     * Page with order trackings details
	     */
	    include('templates/muambator-order-tracking-details.php');
    }

    /**
     * Get Muambator Webhooks Settings
     */
    public function settings() {
    	return (array) get_option( 'mbwebhooks-settings' );
    }

	/**
	 * Check if Woocommerce Correios Plugin is active
	 */
    public function correios_active() {
    	return in_array('woocommerce-correios/woocommerce-correios.php', apply_filters('active_plugins', get_option( 'active_plugins')) );
    }

	/**
	 * Returns array of orders/tracking codes that are not imported
	 * Returned array: tracking_code, order_id, order_total, shipping_postcode, client_email (optional)
	 * Orders used: in status 'completed' or 'processing' and create within the last 4 months
	 *
	 * @return array
	 */
    public function untracked_orders_packages() {
    	global $wpdb;
	    $wEmail = $this->settings()['mb-additional-email'];

	    $settingsCsvMonths = $this->settings()['mb-csv-months-track-email'];
	    $csvMonths = isset($settingsCsvMonths) ? (integer)$settingsCsvMonths : 4;
	    $settingsCsvStatuses = $this->settings()['mb-csv-status-check'];
        $csvStatuses = isset($settingsCsvStatuses) ? $settingsCsvStatuses : array('wc-processing', 'wc-completed');

        if ($this->correios_active() and !$this->settings()['mb-trackings-metafield']) {
            $metaKey = '_correios_tracking_code';
        } else if ($this->settings()['mb-trackings-metafield']) {
            $metaKey = $this->settings()['mb-trackings-metafield'];
        } else {
        	return array();
        }

	    $args = array(
		    'status' => $csvStatuses,
		    'date_created' => '>'. ( time() - ($csvMonths * MONTH_IN_SECONDS) )
	    );
	    $orders = wc_get_orders( $args );

	    $importedOrders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mbwebhook_order_package_tracking;");

	    $untrackedOrders = array();

	    foreach ($orders as $order) {
	    	$codes = explode( ',', $order->get_meta( $metaKey ));
	    	if (count($codes) > 0) {
		        foreach ($codes as $key=>$code) {
		            if($code and !$this->order_code_imported($order->get_order_number(), $code, $importedOrders)) {
					    $new = array(
						    $code,
						    $order->get_order_number().'-'.(string)$key,
						    $order->get_total(),
						    $order->get_shipping_postcode()
					    );
					    if ($wEmail) {
						    array_push($new, $order->get_billing_email());
					    }
				        array_push($untrackedOrders, $new);
				    };
			    };
		    }
	    }

	    return $untrackedOrders;
    }

	/**
	 * Receives order number, tracking code and all codes in MBWebhooks table
	 * Returns if order/package is contained in the table (is imported)
	 *
	 * @param $orderId integer
	 * @param $code string
	 * @param $importedOrders array
	 * @return boolean
	 */
    private function order_code_imported($orderId, $code, $importedOrders) {
    	foreach ($importedOrders as $row) {
    		if ($row->order_id == $orderId and $row->code == $code) {
    	        return true;
		    }
	    }
	    return false;
    }

	/**
	 * @param $orderId
	 *
	 * @return array|null|object
	 */
    public function order_packages($orderId) {
    	global $wpdb;
	    $packages = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mbwebhook_order_package_tracking WHERE order_id = {$orderId};");
	    return $packages;
    }
}

$GLOBALS['wc_mbwebhook'] = new WC_MBWebhook();