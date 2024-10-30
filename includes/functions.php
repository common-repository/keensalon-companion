<?php

defined('ABSPATH') || exit();

function keensalon_companion_get_image_field( $id, $name, $image, $label ){
	$output = '';
	$output .= '<div class="widget-upload">';
	$output .= '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br/>';
	$output .= '<input id="' . esc_attr( $id ) . '" class="keensalon-upload" type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $image ) . '" placeholder="' . __('No file chosen', 'keensalon-companion') . '" />' . "\n";
	if ( function_exists( 'wp_enqueue_media' ) ) {
		if ( $image == '' ) {
			$output .= '<input id="upload-' . esc_attr( $id ) . '" class="keensalon-upload-button button" type="button" value="' . __('Upload', 'keensalon-companion') . '" />' . "\n";
		} else {
			$output .= '<input id="upload-' . esc_attr( $id ) . '" class="keensalon-upload-button button" type="button" value="' . __('Change', 'keensalon-companion') . '" />' . "\n";
		}
	} else {
		$output .= '<p><i>' . __('Upgrade your version of WordPress for full media support.', 'keensalon-companion') . '</i></p>';
	}

	$output .= '<div class="keensalon-screenshot" id="' . esc_attr( $id ) . '-image">' . "\n";

	if ( $image != '' ) {
		$remove = '<a class="keensalon-remove-image">'.__('Remove Image','keensalon-companion').'</a>';
		$attachment_id = $image;
		$image_url = wp_get_attachment_image_url( $attachment_id, 'full');
		$image = preg_match('/(^.*\.jpg|jpeg|png|gif|ico*)/i', $image_url);
		if ( $image ) {
			$output .= '<img src="' . esc_url( $image_url ) . '" alt="" />' . $remove;
		} else {
			// Standard generic output if it's not an image.
			$output .= '<small>' . __( 'Please upload valid image file.', 'keensalon-companion' ) . '</small>';
		}
	}
	$output .= '</div></div>' . "\n";

	echo $output;
}