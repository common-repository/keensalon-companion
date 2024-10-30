<?php

defined( 'ABSPATH' ) || exit();

class KeenSalon_Companion_Admin {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'metabox' ], 10, 2 );

	}

	public function metabox() {
		add_meta_box( 'keensalon_package', __( 'Package and Plan', 'keensalon-companion' ), [
			$this,
			'metabox_content'
		], 'package', 'normal', 'high' );
	}

	public function metabox_content() {
		global $post;
		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'keensalon_package_metabox_nonce', 'meta_box_nonce' );

		?>
        <div id="package_meta_box">

			<?php

			//get the saved meta as an array
			$packages = get_post_meta( $post->ID, 'package', false );
			$i        = 0;

			if ( ! empty( $packages ) ) {
				foreach ( $packages as $package ) {
					foreach ( $package as $pack ) {
						if ( isset( $pack['title'] ) || isset( $pack['track'] ) ) {
							printf( '<p><label for="package_title">Title<input type="text" name="package[%1$s][title]" value="%2$s" id="package_title"></label><label for="package_price">Price<input type="text" name="package[%1$s][price]" value="%3$s" id="package_price"></label><span class="button button-danger remove">%4$s</span></p>', $i, $pack['title'], $pack['price'], __( 'Remove', 'keensalon-companion' ) );
							$i ++;
						}
					}
				}
			}

			?>

            <span id="here"></span>

            <span class="button button-primary add"><?php _e( 'Add New Plan', 'keensalon-companion' ); ?></span>

	        <script>
                (function ($) {
                    $(document).ready(function () {
                        var count = <?php echo $i; ?>;
                        $(".add").click(function () {
                            count = count + 1;
                            $('#here').append('<p><label for="package_title">Title<input type="text" name="package[' + count + '][title]" value="" id="package_title"></label><label for="package_price">Price<input type="text" name="package[' + count + '][price]" value="" id="package_price"></label><span class="button button-danger remove">Remove</span></p>');
                            return false;
                        });

                        $(".remove").live('click', function () {
                            $(this).parent().remove();
                        });

                    });
                })(jQuery);
            </script>
        </div>

		<?php
	}

}

new KeenSalon_Companion_Admin();