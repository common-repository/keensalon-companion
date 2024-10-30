<?php

defined( 'ABSPATH' ) || exit();

class KeenSalon_Companion_Enqueue {

	public $version;

	public $min;

	function __construct() {
		$this->version = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : KEENSALON_COMPANION_VERSION;
		$this->min     = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
	}

	function frontend_scripts() {
		wp_enqueue_style( 'keensalon-companion', KEENSALON_COMPANION_ASSETS . "/css/frontend{$this->min}.css", [], $this->version, 'all' );

		wp_enqueue_script( 'keensalon-companion', KEENSALON_COMPANION_ASSETS . "/js/frontend{$this->min}.js", [ 'jquery' ], $this->version, true );

	}

	function admin_scripts() {

		wp_enqueue_style( 'keensalon-companion', KEENSALON_COMPANION_ASSETS . "/css/admin{$this->min}.css", [], $this->version );

		wp_enqueue_script( 'keensalon-companion', KEENSALON_COMPANION_ASSETS . "/js/admin{$this->min}.js", [ 'jquery' ], $this->version, true );

		wp_localize_script( 'keensalon-companion', 'KEENSALON_COMPANION_uploader', array(
			'upload' => __( 'Upload', 'keensalon-companion' ),
			'change' => __( 'Change', 'keensalon-companion' ),
			'msg'    => __( 'Please upload a valid image file.', 'keensalon-companion' )
		) );

	}

}

new KeenSalon_Companion_Enqueue();