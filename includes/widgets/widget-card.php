<?php

defined('ABSPATH') || exit();

/**
 * Adds KeenSalon_Companion_Card_Widget widget.
 */
class KeenSalon_Companion_Card_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'keensalon_card_widget', // Base ID
			__( 'Keensalon: Card', 'keensalon-companion' ), // Name
			array( 'description' => __( 'A Card Widget.', 'keensalon-companion' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * @see WP_Widget::widget()
	 *
	 */
	public function widget( $args, $instance ) {
		$image        = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$title        = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$description  = ! empty( $instance['description'] ) ? $instance['description'] : '';
		$button_label = ! empty( $instance['button_label'] ) ? $instance['button_label'] : '';
		$button_link  = ! empty( $instance['button_link'] ) ? $instance['button_link'] : '';

		$imgSrc = get_theme_file_uri( '/assets/images/hair-cutting.jpg' );

		if ( $image ) {
			$attachment_id = $image;

			$imgSrc = wp_get_attachment_image_src( $attachment_id, 'full', false );

			$imgSrc = $imgSrc[0];
		}

		// echo $args['before_widget'];
		ob_start();

		?>
        <div class="special-card align-center-v col-md-6 col-sm-6 col-xs-12"
             style="background-image: url(<?php echo $imgSrc; ?>);">
            <div class="center">
				<?php if ( $title ): ?>
                    <h2 class="title"><?php echo $title; ?></h2>
				<?php endif; ?>

				<?php if ( $description ): ?>
					<?php echo wpautop( wp_kses_post( $description ) ); ?>
				<?php endif; ?>

				<?php if ( $button_label ): ?>
                    <a href="<?php echo $button_link; ?>" class="btn text-uppercase"><?php echo $button_label; ?></a>
				<?php endif; ?>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		echo apply_filters( 'keensalon_companion_card_widget', $html, $args, $instance );
		// echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @see WP_Widget::form()
	 *
	 */
	public function form( $instance ) {
		$image        = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$title        = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$description  = ! empty( $instance['description'] ) ? $instance['description'] : '';
		$button_label = ! empty( $instance['button_label'] ) ? $instance['button_label'] : '';
		$button_link  = ! empty( $instance['button_link'] ) ? $instance['button_link'] : '';
		?>
        <p>
			<?php
			keensalon_companion_get_image_field(
				$this->get_field_id( 'image' ),
				$this->get_field_name( 'image' ),
				$image, __( 'Upload Image', 'keensalon-companion' )
			);
			?>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'keensalon-companion' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'keensalon-companion' ); ?></label>
            <textarea name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat"
                      id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php print $description; ?></textarea>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_label' ) ); ?>"><?php esc_html_e( 'Button Label', 'keensalon-companion' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_label' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'button_label' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $button_label ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_link' ) ); ?>"><?php esc_html_e( 'Button Link', 'keensalon-companion' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_link' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'button_link' ) ); ?>" type="url"
                   value="<?php echo esc_attr( $button_link ); ?>"/>
        </p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 * @see WP_Widget::update()
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['image']        = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
		$instance['title']        = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['description']  = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';
		$instance['button_label'] = ! empty( $new_instance['button_label'] ) ? sanitize_text_field( $new_instance['button_label'] ) : '';
		$instance['button_link']  = ! empty( $new_instance['button_link'] ) ? sanitize_text_field( $new_instance['button_link'] ) : '';

		return $instance;
	}

}

// register keensalon_register_card_widget widget
function keensalon_register_card_widget() {
	register_widget( 'KeenSalon_Companion_Card_Widget' );
}

add_action( 'widgets_init', 'keensalon_register_card_widget' );