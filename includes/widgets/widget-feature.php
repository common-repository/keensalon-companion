<?php

defined('ABSPATH') || exit();
 
 /**
 * Adds KeenSalon_Companion_Feature_Widget widget.
 */
class KeenSalon_Companion_Feature_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'keensalon_feature_widget', // Base ID
            __( 'KeenSalon: Feature', 'keensalon-companion' ), // Name
            array( 'description' => __( 'Add Features for the Feature section.', 'keensalon-companion' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : '' ;               
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';

        if( $image ) {
            $attachment_id = $image;
        }
        
        // echo $args['before_widget'];
        ob_start(); 
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="featured-item">
                <?php if( $image ){ ?>
                <div class="icon pull-left">
					<?php echo wp_get_attachment_image( $attachment_id, 'full', false, array( 'class' => 'img-responsive', 'alt' => esc_attr( $title ))) ;?>
				</div>
                <?php }?>

                <div class="text">
                	<?php if ($title): ?>
						<h5 class="title"><?php echo $title; ?></h5>
                	<?php endif ?>

                	<?php if ($description): ?>
						<?php echo wpautop( wp_kses_post( $description ) ); ?>
                	<?php endif ?>
				</div>
            </div>
        </div>
        <?php 
        $html = ob_get_clean();
        echo apply_filters( 'keensalon_companion_feature_widget', $html, $args, $instance );
        // echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
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
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Feature', 'keensalon-companion' ); ?></label>
            <textarea name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php print $description; ?></textarea>
        </p>
        
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance                = array();
        
        $instance['image']       = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
        $instance['title']        = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['description'] = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';
        
        return $instance;
    }
    
}

// register keensalon_register_feature_widget widget
function keensalon_register_feature_widget(){
	register_widget( 'KeenSalon_Companion_Feature_Widget' );
}
add_action('widgets_init', 'keensalon_register_feature_widget');