<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Link to settings at Plugins Pages
 *
 * @param array
 * @return array
 */
function add_mbwebhook_settings_link( $links ) {
	$action_links = array(
		'settings' => '<a href="'.admin_url( 'options-general.php?page=muambator-webhooks' ).'">'.__( 'Settings').'</a>',
	);
	return array_merge( $action_links, $links );
}
$plugin = plugin_basename( MBWH_PLUGIN_FILE );
add_filter( "plugin_action_links_$plugin", 'add_mbwebhook_settings_link' );

/**
 * Admin screen with help for Muambator Webhook settings and custom email infos
 */
add_action( 'admin_menu', 'mbwebhooks_menu' );
function mbwebhooks_menu() {
	add_options_page(
        __('Muambator Webhooks', 'mb-webhook-woocommerce' ),
        __('Muambator Webhooks', 'mb-webhook-woocommerce' ),
        'manage_options',
        'muambator-webhooks',
        'mbwebhooks_page'
    );
}
add_action( 'admin_init', 'mbwebhooks_menu_init' );

/**
 * Init
 */
function mbwebhooks_menu_init() {
	register_setting(
        'mbwebhooks-settings-group',
        'mbwebhooks-settings'
    );

	// Emails
	add_settings_section(
        'custom-emails',
        __( 'Emails Personalizados', 'mb-webhook-woocommerce' ),
		'mbwebhook_custom_emails_callback',
        'muambator-webhooks'
    );
	add_settings_field(
        'mb-additional-email',
        __( 'Ativar envio de atualizações de rastreio para meus clientes', 'mb-webhook-woocommerce' ),
		'mbwebhook_additional_email_callback',
        'muambator-webhooks',
        'custom-emails'
    );

	// General Settings
	add_settings_section(
	        'mb-plugin-options',
            __('Opções Gerais do Plugin', 'mb-webhook-woocommerce'),
            'mbwebhook_plugin_options_callback',
            'muambator-webhooks'
    );
	add_settings_field(
		'mb-trackings-metafield',
		__( 'Campo Meta a ser utilizado', 'mb-webhook-woocommerce' ),
		'mbwebhook_tracking_metafield_callback',
		'muambator-webhooks',
		'mb-plugin-options'
	);

	// CSV
	add_settings_section(
		'mb-csv-options',
		__( 'Exportação de CSVs', 'mb-webhook-woocommerce' ),
		'mbwebhook_csv_options_callback',
		'muambator-webhooks'
	);
	add_settings_field(
		'mb-csv-status-check',
		__( 'Status das ordens', 'mb-webhook-woocommerce' ),
		'mbwebhook_csv_status_callback',
		'muambator-webhooks',
		'mb-csv-options'
	);
	add_settings_field(
		'mb-csv-months-track-email',
		__( 'Período de meses', 'mb-webhook-woocommerce' ),
		'mbwebhook_csv_months_callback',
		'muambator-webhooks',
		'mb-csv-options'
	);
}

/**
 * Page Render
 */
function mbwebhooks_page() {
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php _e('Muambator Webhooks', 'mb-webhook-woocommerce'); ?></h1>

		<p><?php _e('Com o Webhook do Muambator, todos os pacotes registrados com o plugin “WooCommerce Correios” serão inseridos em um CSV para você importar no Muambator e receber informações sempre que houver uma atualização.', 'mb-webhook-woocommerce'); ?></p>

		<h2><?php _e('Configurar Webhook', 'mb-webhook-woocommerce'); ?></h2>

		<p><?php _e('Para configurar o webhook, basta inserir a URL abaixo nas <a href="https://www.muambator.com.br/perfil/minha-conta/webhooks/" target=“_blank”>Configurações de Webhook do seu usuário no Muambator</a>:','mb-webhook-woocommerce'); ?></p>

		<input title="URL Webhook" type="text" value="<?php echo esc_url_raw( rest_url( 'mb-webhook/v1/receive' )); ?>" class="regular-text" readonly>

		<form action="options.php" method="POST">
			<?php settings_fields('mbwebhooks-settings-group'); ?>
			<?php do_settings_sections('muambator-webhooks'); ?>
			<?php submit_button(); ?>
		</form>

	</div>
<?php }

/**
 * Custom Email Section
 */
function mbwebhook_custom_emails_callback() {
	_e( 'Ao importar os seus pacotes no Muambator você pode enviar automaticamente as atualizações de rastreio para seus clientes utilizando o email personalizado do Muambator.', 'mb-webhook-woocommerce' );
    _e( '<br>Para mais informações, veja nosso <a href="https://medium.com/muambator/envio-de-e-mails-personalizados-o-novo-recurso-do-muambator-pro-e67a8f523f9" target=“_blank”>texto no Medium</a>! :)','mb-webhook-woocommerce');
	_e( '<br>Para ativar este recurso, confirme a opção abaixo:', 'mb-webhook-woocommerce' );
}

/**
 * Custom email fields
 */
function mbwebhook_additional_email_callback() {
	$settings = (array) get_option( 'mbwebhooks-settings' );
	$field = "mb-additional-email";
	$value = esc_attr( $settings[$field] ) ? 'checked' : '';

	echo "<input type='checkbox' name='mbwebhooks-settings[$field]' $value/>";
}

/**
 * General Plugin Settings
 */
function mbwebhook_plugin_options_callback() {
	_e('Este plugin foi desenvolvido primariamente para utilizar as informações do plugin WooCommerce Correios, porém aceitamos também o processamento dos códigos contidos em um campo meta dos seus pedidos.', 'mb-webhook-woocommerce');
	_e('<br>Para utilizar um campo meta, apenas adicione o nome do campo meta que irá possuir os códigos de rastreio separados por vírgula.', 'mb-webhook-woocommerce');
	_e('<br>Se o campo estiver vazio, o plugin irá procurar pelas informações do plugin WooCommerce Correios.', 'mb-webhook-woocommerce');
	$mbwh = new WC_MBWebhook();
	$correiosAtivo = $mbwh->correios_active() ? 'Ativado' : 'Desativado';
	_e('<h4>Estado do plugin WooCommerce Correios: '.$correiosAtivo.'</h4>', 'mb-webhook-woocommerce');
}

function mbwebhook_tracking_metafield_callback() {
	$settings = (array) get_option( 'mbwebhooks-settings' );
	$field = "mb-trackings-metafield";
	$value = esc_attr( $settings[$field] );

	echo "<input type='text' name='mbwebhooks-settings[$field]' value='$value'/>";
}

/**
 * CSV Export settings Section
 */
function mbwebhook_csv_options_callback() {
    $csvUrl = admin_url('admin.php?page=muambator-csv');
    _e('Para inserir os códigos de rastreio para o Muambator, selecione abaixo os status das ordens e o periodo em meses que gostaria de exportar para o CSV.', 'mb-webhook-woocommerce');
    _e("<br>Na página <a href='$csvUrl'>Exportar CSV para Muambator</a> você poderá verificar quais ordens são exportadas e baixar o CSV.", 'mb-webhook-woocommerce');
}

/**
 * CSV Export settings fields
 */
function mbwebhook_csv_status_callback() {
	$settings = (array) get_option( 'mbwebhooks-settings' );
	$field = "mb-csv-status-check";
	$statuses = wc_get_order_statuses();
	$selected = isset($settings[$field]) ? $settings[$field] : array('wc-processing', 'wc-completed');

	foreach ($statuses as $key=>$status) {
        $checked = in_array($key, $selected, true) ? 'checked' : '';
	    echo "<label><input type='checkbox' name='mbwebhooks-settings[$field][]' value='$key' $checked/> $status</label><br>";
    }
}

function mbwebhook_csv_months_callback() {
	$settings = (array) get_option( 'mbwebhooks-settings' );
	$field = "mb-csv-months-track-email";
	$value = $settings[$field] ? $settings[$field] : '4';

	echo "<input type='number' name='mbwebhooks-settings[$field]' value='$value'/>";
}