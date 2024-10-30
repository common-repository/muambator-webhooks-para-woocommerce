<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'rest_api_init', 'mbwebhook_rest_route' );

function mbwebhook_rest_route() {
	register_rest_route( 'mb-webhook/v1', '/receive', array(
		'methods' => 'POST',
		'callback' => 'mb_webhook_process',
	));
}

/**
 * @param WP_REST_Request $request
 * @return array
 */
function mb_webhook_process(WP_REST_Request $request) {
	$json = $request->get_json_params();

	foreach ($json as $key=>$pacote) {
		$orderId = (int)explode('-', $pacote['nome'])[0];
		$order = wc_get_order($orderId);

		if (isset($orderId) and $order) {
			global $wpdb;
			$trackings = isset($pacote['tracking']) ? $pacote['tracking'] : array();

			$result = $wpdb->replace(
				$wpdb->prefix.'mbwebhook_order_package_tracking',
				array(
					'order_id'       => $orderId,
					'code'           => $key,
					'tracking'       => json_encode($trackings),
					'delivered'      => $pacote['entregue'] ? 1 : 0
				)
			);

			if($result === false) {
				return array('status' => 'ERROR TRYING TO INSERT/UPDATE', 'success' => False, 'message' => $wpdb->last_error);
			} else {
				return array('status' => 'Number of updated results: '.$result, 'success' => True);
			}
		} else {
			return array('status' => "No order with given number", 'success' => False);
		}
	}

	die;
}