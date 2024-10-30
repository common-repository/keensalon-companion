<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class KeenSalon_Companion_Install
 * Do the activate stuffs
 */
class KeenSalon_Companion_Install {

	public static function activate() {
		$key = sanitize_key( keensalon_companion()->name );
		update_option( $key . '_version', keensalon_companion()->version );
		update_option( 'keensalon_companion_flush_rewrite_rules', true );
		update_option( 'keensalon_companion_install_date', current_time( 'timestamp' ) );
	}

}