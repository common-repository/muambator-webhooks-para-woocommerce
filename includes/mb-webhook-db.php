<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class MBWebhook_Install {
	public static function init() {
		add_action( 'init', array(__CLASS__, 'install') );
	}

	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'mbwebhook_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'mbwebhook_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		self::create_tables();

		delete_transient( 'mbwebhook_installing' );
	}

	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'mbwebhook_order_package_tracking';

		$sql = "CREATE TABLE {$table_name} (
		  order_id BIGINT UNSIGNED NOT NULL,
		  code varchar(150) NOT NULL DEFAULT '',
		  tracking TEXT DEFAULT NULL,
		  delivered INTEGER(1) NOT NULL DEFAULT 0,
		  PRIMARY KEY (order_id, code)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		add_option( "mbwebhooks_db_version", "1.0" );
	}
}

MBWebhook_Install::init();