<?php

defined('ABSPATH') || exit();

/**
 * Package & Plan Widget
 *
 * @package KEENSALON_COMPANION
 */
 
 /**
 * Adds KeenSalon_Companion_Package_Widget widget.
 */
class KeenSalon_Companion_Package_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'keensalon_package_widget', // Base ID
			__( 'Keensalon: Package & Plan', 'keensalon-companion' ), // Name
			array( 'description' => __( 'A Package & Plan Widget.', 'keensalon-companion' ), ) // Args
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

		$terms    = get_term_by( 'package_category', array( 'hide_empty' => true ) );
		$packages = ! empty( $instance['packages'] ) ? $instance['packages'] : '';

		$post_number = ! empty( $instance['post_number'] ) ? $instance['post_number'] : - 1;

		// echo $args['before_widget'];
		ob_start();

		if ( $packages ):
			?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="package-plan-tab">
                        <ul class="nav nav-tabs">
							<?php
							$i = 1;
							foreach ( $packages as $slug ) {
								$counter = $i == 1 ? 'active' : '';
								$package = get_term_by( 'slug', $slug, 'package_category' );
								printf( '<li class="%s"><a href="#%s" role="tab" data-toggle="tab">%s</a></li>', $counter, $slug, $package->name );
								$i ++;
							}
							?>
                        </ul>


                        <div class="tab-content">
							<?php
							$i = 1;
							foreach ( $packages as $slug ):
								?>
                                <div class="tab-pane fade in <?php if ( $i == 1 ) {
									echo 'active';
								} ?>" id="<?php echo $slug; ?>">
                                    <div class="row eq-height">
										<?php

										$args = array(
											'post_type'      => 'package',
											'post_status'    => 'publish',
											'posts_per_page' => $post_number,
											'tax_query'      => array(
												array(
													'taxonomy' => 'package_category',
													'field'    => 'slug',
													'terms'    => $slug,
												)
											),
										);

										$services = get_posts( $args );

										foreach ( $services as $service ):
											setup_postdata( $service );
											$keensalon_package = get_post_meta( $service->ID, 'package', true );
											?>

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="package-plan-item">
                                                    <h2 class="title separator"><?php echo $service->post_title ?></h2>

													<?php if ( $keensalon_package ): ?>
                                                        <ul class="list-unstyled">
															<?php foreach ( $keensalon_package as $pack ): ?>
                                                                <li>
																	<?php if ( $pack['title'] ): ?>
                                                                        <span class="text"><?php echo $pack['title']; ?></span>
																	<?php endif; ?>

																	<?php if ( $pack['price'] ): ?>
                                                                        <span class="price"><?php echo $pack['price']; ?></span>
																	<?php endif; ?>
                                                                </li>
															<?php endforeach; ?>
                                                        </ul>
													<?php endif; ?>
                                                </div>
                                            </div><!-- /package-plan-item -->
										<?php endforeach; ?>
                                    </div><!-- /row -->
                                </div><!-- /tab-pane -->
								<?php $i ++; endforeach;
							wp_reset_postdata(); ?>
                        </div><!-- /tab-content -->
                    </div><!-- /package-plan-tab -->
                </div><!-- /column -->
            </div><!-- /row -->
		<?php
		endif;
		$html = ob_get_clean();
		echo apply_filters( 'KEENSALON_COMPANION_package_widget_filter', $html, $args, $instance );
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

		$terms    = get_terms( 'package_category', array( 'hide_empty' => true ) );
		$packages = ! empty( $instance['packages'] ) ? $instance['packages'] : '';

		$post_number = ! empty( $instance['post_number'] ) ? $instance['post_number'] : '';
		?>
        <p class="package_category">
            <label for="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>"><?php esc_html_e( 'Select Category', 'keensalon-companion' ); ?></label>

			<?php if ( $terms ): foreach ( $terms as $term ): ?>
                <label for="<?php echo $term->slug; ?>"><input type="checkbox" id="<?php echo $term->slug; ?>"
                                                               name="<?php echo esc_attr( $this->get_field_name( 'packages' ) ); ?>[]"
                                                               value="<?php echo $term->slug; ?>" <?php if ( ! empty( $packages ) ) {
						echo in_array( $term->slug, $packages ) ? "checked" : '';
					}; ?>><?php echo $term->name; ?></label>
			<?php endforeach;
			else:
				_e( 'No Package Categories select yet!', 'keensalon-companion' );
			endif; ?>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>"><?php esc_html_e( 'Number of Services', 'keensalon-companion' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'post_number' ) ); ?>" type="number"
                   value="<?php echo esc_attr( $post_number ); ?>"/>
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

		$instance['packages'] = $new_instance['packages'];

		$instance['post_number'] = ! empty( $new_instance['post_number'] ) ? sanitize_text_field( $new_instance['post_number'] ) : '';

		return $instance;
	}

}

// register keensalon_register_package_widget widget
function keensalon_register_package_widget(){
	register_widget( 'KeenSalon_Companion_Package_Widget' );
}

add_action('widgets_init', 'keensalon_register_package_widget');