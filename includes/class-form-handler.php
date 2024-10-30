<?php

defined( 'ABSPATH' ) || exit();

class KeenSaloon_Form_Handler {
	public function __construct() {
		add_action( 'save_post', [ $this, 'save_metabox' ] );
	}

	public function save_metabox( $post_id ) {

		if ( empty($_POST['meta_box_nonce']) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'keensalon_package_metabox_nonce' ) ) {
			return false;
		}

		if ( ! current_user_can( "edit_post" ) ) {
			return false;
		}

		if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( defined( "DOING_AJAX" ) && DOING_AJAX ) {
			return false;
		}

		$packages = wp_unslash( $_POST['package'] );
		update_post_meta( $post_id, 'package', $packages );
	}
}

new KeenSaloon_Form_Handler();