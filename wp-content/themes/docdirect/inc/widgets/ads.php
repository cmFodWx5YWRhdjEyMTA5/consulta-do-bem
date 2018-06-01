<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('TG_Ads_Widget')) {

    class TG_Ads_Widget extends WP_Widget {
        /*
         * @init About Doctor Directorys
         * 
         */

        public function __construct() {
            $widget_ops = array('classname' => 'address-column', 'description' => 'Display Ads Widget');
            $control_ops = array('width' => 300, 'height' => 250, 'id_base' => 'ads_widget');
            parent::__construct('ads_widget', esc_html__('Ads Script | DocDirect', 'docdirect'), $widget_ops, $control_ops);
        }

        /**
         * About Doctor Directorys form
         *
         */
        public function form($instance) {
           $title 		 	= isset( $instance['title'] ) ? esc_attr($instance['title']) : '';
		   $add_code 		= isset( $instance['add_code'] ) ? esc_attr($instance['add_code']) : '';
		  
		  ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'docdirect'); ?></label>
                <input type="text" id="title" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widefat" />
            </p>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id('add_code')); ?>"><?php esc_html_e('Add Script', 'docdirect'); ?></label>
                <textarea id="add_code" name="<?php echo esc_attr($this->get_field_name('add_code')); ?>" class="widefat"><?php echo esc_attr($add_code); ?></textarea>
            </p>
          <?php
        }

        /**
         * @Update About Doctor Directorys 
         *
         */
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
            $instance['add_code'] = (!empty($new_instance['add_code']) ) ? $new_instance['add_code'] : '';

            return $instance;
        }

        /**
         * @Display About Doctor Directorys 
         *
         *
         */
        public function widget($args, $instance) {
            extract($args);
            $title = empty($instance['title']) ? '' : $instance['title'];
            $add_code = empty($instance['add_code']) ? '' : $instance['add_code'];
            echo ($args['before_widget']);
 
			if (!empty($title) ) {
				echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			}
			
			?>
			<div class="tg-add-wrapper">
            	<?php echo force_balance_tags( $add_code );?>
            </div>
			<?php
            echo ($args['after_widget']);
        
		}

    }

}
add_action('widgets_init', create_function('', 'return register_widget("TG_Ads_Widget");'));