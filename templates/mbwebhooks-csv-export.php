<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function mb_csv_submenu_page() {
	add_submenu_page( 'woocommerce', 'Exportar CSV para Muambator', 'CSV Muambator', 'manage_options', 'muambator-csv', 'mb_csv' );
}

function mb_csv() {
	$mbwh = new WC_MBWebhook;
	$mbSettings = $mbwh->settings();
	$wEmail = $mbSettings['mb-additional-email'];

	$settingsCsvMonths = $mbSettings['mb-csv-months-track-email'];
	$csvMonths = isset($settingsCsvMonths) ? (integer)$settingsCsvMonths : 4;
	$settingsCsvStatuses = $mbSettings['mb-csv-status-check'];

	if (isset($settingsCsvStatuses)) {
        $csvStatus = array();
        foreach ($settingsCsvStatuses as $status) {
            array_push($csvStatus, wc_get_order_status_name($status));
        }
    } else {
        $csvStatus = array(wc_get_order_status_name('wc-processing'), wc_get_order_status_name('wc-completed'));
    }

	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Exportar CSV para Muambator</h1>
        <p>Configurações para a exportação:<br>
            • Ordens com os status: <u><?php echo implode(', ', $csvStatus)?></u></br>
            • <?php if ($csvMonths == 1) { echo 'Ordens criadas nos <u>últimos 30 dias</u>';} else { echo "Ordens criadas nos <u>últimos $csvMonths meses</u>";} ?><br>
            <?php if (!$mbSettings['mb-trackings-metafield']) { ?>
                • Utilizando informações do plugin WooCommerce Correios<?php if (!$mbwh->correios_active()) { echo ' porém o plugin está desativado!'; } ?>
            <?php } else if ($mbSettings['mb-trackings-metafield']) { ?>
                • Utilizando informações do meta campo <i>"<?php echo $mbSettings['mb-trackings-metafield']; ?>"</i>
            <?php } else {?>
                • Por favor, utilize o plugin WooCommerce Correios ou insira o nome do campo meta a ser utilizado nas configurações do plugin
            <?php } ?>
        </p>
        <p>Para importar os pacotes, acesse o <a href="https://www.muambator.com.br/pacotes/importar/" target="_blank">Importador do Muambator</a>.</p>
		<?php
            try {
                $rows = $mbwh->untracked_orders_packages();
    		} catch (Exception $e) {
                echo '<p>EXCEPTION: ',  $e->getMessage(), "</p>";
                $rows = array();
            }
            if(count($rows) > 0) { ?>
		<form action="<?php echo admin_url( 'admin-post.php' ); ?>">
			<input type="hidden" name="action" value="export_mb_csv">
			<?php submit_button( 'Baixar CSV' ); ?>
		</form>
		<?php }; ?>


		<hr>

		<h4>Ordens a serem exportadas:</h4>

		<table class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th>Código</th>
					<th>Nome (Pedido #)</th>
					<th>Valor</th>
					<th>CEP</th>
					<?php if($wEmail) { echo '<th>E-mail</th>'; } ?>
				</tr>
			</thead>
			<tbody>
		<?php
			if (count($rows) > 0) {
				foreach ( $rows as $row ) {
                    echo '<tr>';
                    foreach ($row as $key=>$info) {
                        if ($key == 1) {
                            $orderId = (integer)$info;
                            echo "<td>{$orderId}</td>";
                        } else {
                            echo "<td>{$info}</td>";
                        }
                    }
                    echo '</tr>';
				}
			} else {
				$colspan = $wEmail ? '5' : '4';
				echo '<tr><td style="text-align: center;" colspan="'.$colspan.'">----</td></tr>';
			}
		?>
			</tbody>
		</table>
	</div>
	<?php
}

add_action('admin_menu', 'mb_csv_submenu_page');
/*
 * CSV Generation action
 */
function mbwebhook_generate_csv() {
	$mbwh = new WC_MBWebhook;
	$rows = $mbwh->untracked_orders_packages();
	$wEmail = $mbwh->settings()['mb-additional-email'];
	if(count($rows) > 0) {
		$filename = 'muambator-' . date( 'Y-m-d-H-i-s' ) . '.csv';
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/csv; charset=UTF-8', true );
//		ob_clean();
        ob_get_clean();
		$headers = array( 'codigo', 'nome', 'valor', 'cep_destino');
		if ($wEmail) { array_push($headers, 'email'); }
		echo implode( ';', $headers ) . "\n";
		$i = 1;
		$len = count($rows);
		foreach ( $rows as $row ) {
		    if ( $i == $len ) {
    		    echo implode( ';', $row );
            } else {
    		    echo implode( ';', $row ) . "\n";
            }
            $i++;
		}
	}
	exit;
}
add_action('admin_post_export_mb_csv', 'mbwebhook_generate_csv' );
?>
