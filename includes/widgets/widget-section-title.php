<?php

defined( 'ABSPATH' ) || exit();


/**
 * Adds KeenSalon_Companion_Section_Title_Widget widget.
 */
class KeenSalon_Companion_Section_Title_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'keensalon_section_title_widget', // Base ID
			__( 'Keensalon: Section Title', 'keensalon-companion' ), // Name
			array( 'description' => __( 'A Section Title Widget.', 'keensalon-companion' ), ) // Args
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

		$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';

		// echo $args['before_widget'];
		ob_start();
		?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="section-title text-center">
					<?php if ( $title ): ?>
                        <h2 class="title separator center"><?php echo $title; ?></h2>
					<?php endif ?>

					<?php if ( $description ): ?>
						<?php echo wpautop( wp_kses_post( $description ) ); ?>
					<?php endif ?>
                </div>
            </div>
        </div><!-- /row -->
		<?php
		$html = ob_get_clean();
		echo apply_filters( 'KEENSALON_COMPANION_section_title_widget_filter', $html, $args, $instance );
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

		$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		?>

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

		$instance['title']       = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['description'] = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';

		return $instance;
	}

}

// register keensalon_register_section_title_widget widget
function keensalon_register_section_title_widget() {
	register_widget( 'KeenSalon_Companion_Section_Title_Widget' );
}

add_action( 'widgets_init', 'keensalon_register_section_title_widget' );
