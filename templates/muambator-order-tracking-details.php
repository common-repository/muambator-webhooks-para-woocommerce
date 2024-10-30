<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'admin_menu', 'mbwebhook_tracking_detail_register' );

function mbwebhook_tracking_detail_register() {
	add_submenu_page(
		'edit.php?post_type=shop_order',
		'Detalhes de Ratreios',
		'Detalhes de Ratreios',
		'manage_options',
		'mb-order-tracking',
		'mbwebhook_tracking_render'
	);
}

function mbwebhook_tracking_render() {
	$orderId = $_GET['order'];
	$order = wc_get_order($orderId);
	$mbwh = new WC_MBWebhook;
	$trackings = $mbwh->order_packages($orderId);

	if ($order) {
	    $address = $order->get_formatted_shipping_address();
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">Pedido #<?php echo $orderId; ?></h1>
            <a href="<?php echo get_edit_post_link( $orderId); ?>" class="page-title-action">Detalhes/Editar</a>
            <?php if ($address) {?><dl>
                <dt><strong>Endereço de Envio:</strong></dt>
                <dd>
	                <address><?php echo $order->get_formatted_shipping_address(); ?></address>
                </dd>
            </dl><?php }; ?>

            <h3>Pacotes:</h3>

			<?php if($trackings) {
			    foreach ($trackings as $tracking) {
			        mbwebhook_render_package_tracking($tracking);
                }
            } else { ?>
                <h3>Nenhuma informação de rastreio disponível no momento.</h3>
			<?php }; ?>
		</div>
		<?php
	} else {
        echo '<div class="wrap"><h1>Pedido não encontrado</h1></div>';
		die;
	}
}

function mbwebhook_render_package_tracking($tracking) {
    $list = json_decode($tracking->tracking);
    ?>
    <table class="widefat striped" style="margin-bottom: 15px;">
        <thead>
            <tr><th colspan="2"><strong><?php echo $tracking->code; ?></strong></th></tr>
        </thead>
        <tbody>
    <?php
        if (count($list) > 0) {
            foreach ($list as $item) {
                $color = isset($item->icone) ? $item->icone : 'black';
                $icon = plugins_url( 'assets/img/'.$color.'.png', dirname(__FILE__) );
        ?>
            <tr>
                <td style="vertical-align:middle; width: 60px;">
                    <img style="display: block; margin: 5px auto;" src="<?php echo $icon; ?>" width="35">
                </td>
                <td style="vertical-align:middle;" >
	                <?php
                        if(isset($item->datahora)) {echo '<small>'.$item->datahora.'</small><br>';}
                        if (isset($item->situacao)) {
                            echo '<strong>'.$item->situacao.'</strong><br>'.$item->local;
                        } else {
                            echo '<strong>Informações não disponíveis no momento!</strong><br>'.
                                 'O Muambator ainda não tem informações sobre este pacote. Isto ocorre geralmente nas primeiras horas para pacotes nacionais ou por alguns dias para pacotes internacionais.';
                        }
                        ?>
                </td>
            </tr>
    <?php }} else { ?>
            <tr>
                <td style="vertical-align:middle; width: 60px;">
                    <img style="display: block; margin: 5px auto;" src="<?php echo plugins_url( 'assets/img/black.png', dirname(__FILE__) ); ?>" width="35">
                </td>
                <td style="vertical-align:middle;" >
                    <strong>Informações não disponíveis no momento!</strong><br>
                    O Muambator ainda não tem informações sobre este pacote. Isto ocorre geralmente nas primeiras horas para pacotes nacionais ou por alguns dias para pacotes internacionais.
                </td>
            </tr>
    <?php }; ?>
        </tbody>
    </table>
    <?php
};