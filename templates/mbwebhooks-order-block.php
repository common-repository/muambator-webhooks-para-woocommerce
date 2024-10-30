<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


add_action( 'add_meta_boxes', 'register_mbwebhook_metabox' );

/**
 * Register tracking code metabox.
 */
function register_mbwebhook_metabox() {
	add_meta_box(
		'mb_tracking',
		'Muambator',
		'mbwebhook_tracking_list',
		'shop_order',
		'side',
		'default'
	);
}

/**
 * @param $post
 */
function mbwebhook_tracking_list( $post ) {
	$mbwh = new WC_MBWebhook();
	$packages = $mbwh->order_packages($post->ID);

	if (count($packages) > 0) {
		echo '<table class="widefat striped" style="margin-bottom: 15px;">';
		foreach ($packages as $item) {
			$tracking = json_decode($item->tracking);
			$last = $tracking[0];
			$iconfile = isset($last->icone) ? $last->icone.'.png' : 'black.png';
			$icone = plugins_url( 'assets/img/'.$iconfile, dirname(__FILE__) );
			$situacao = isset($last->situacao) ? $last->situacao : 'Informações não disponíveis no momento';
			echo '<tr><td colspan="2"><strong>'.$item->code.'</strong></td></tr>'.
			     '<tr><td style="vertical-align: middle;"><img src="'.$icone.'" width="20"></td>'.
			     '<td style="vertical-align: middle;">'.$situacao.'</td></tr>';
		}
		echo '</table>';
		$detailURL = add_query_arg( 'order', $post->ID, menu_page_url('mb-order-tracking', false));
		echo '<div style="text-align:right;"><a href="'.$detailURL.'" class="button">Detalhes</a></div>';
	} else {
		echo '<p>Ainda não há informações de rastreios.</p>';
	}
}
