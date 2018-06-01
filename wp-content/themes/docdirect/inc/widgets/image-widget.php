<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('TG_Image')) {

    class TG_Image extends WP_Widget {
        /*
         * @init About Doctor Directorys
         * 
         */

        public function __construct() {
            $widget_ops = array('classname' => 'img-column', 'description' => 'Display Image');
            $control_ops = array('width' => 300, 'height' => 250, 'id_base' => 'tg_image');
            parent::__construct('tg_image', esc_html__('Image Widget | DocDirect', 'docdirect'), $widget_ops, $control_ops);
        }

        /**
         * About Doctor Directorys form
         *
         */
        public function form($instance) {
            $title 		 = isset($instance['title']) ? $instance['title'] : '';
            $image 		 = isset( $instance['image'] ) ? $instance['image'] : '';
			$description 		 = isset($instance['description']) ? $instance['description'] : '';
		   ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'docdirect'); ?></label>
                <input type="text" id="title" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widefat" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('image')); ?>"><?php esc_html_e('Image:', 'docdirect'); ?></label>
                <input type="text" id="image" name="<?php echo esc_attr($this->get_field_name('image')); ?>" value="<?php echo esc_attr($image); ?>" class="widefat" />
            </p>
			 <p>
                <label for="description"><?php esc_html_e('Description:', 'docdirect'); ?></label>

                <textarea id="description"  rows="8" cols="10" name="<?php echo esc_attr($this->get_field_name('description')); ?>" class="widefat"><?php echo force_balance_tags($description); ?></textarea>
                <span><?php esc_html_e('Shortcodes And HTML Tags are allowed.', 'docdirect'); ?></span>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['show_social'], 'on'); ?> id="show_social" name="<?php echo esc_attr($this->get_field_name('show_social')); ?>" /> 
                <label for="show_social"><?php esc_html_e('Display Social Icons.', 'docdirect'); ?></label>
                <span><?php esc_html_e('For Social Settings Go to Theme Options Settings', 'docdirect'); ?></span>
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
            $instance['image'] = (!empty($new_instance['image']) ) ? $new_instance['image'] : '';
			$instance['description'] = (!empty($new_instance['description']) ) ? $new_instance['description'] : '';
            $instance['show_social'] = $new_instance['show_social'];
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
            $image = empty($instance['image']) ? '' : $instance['image'];
			$description = empty($instance['description']) ? '' : $instance['description'];
            $social = $instance['show_social'];
			
            echo ($args['before_widget']);
			if (!empty($title) && $title !='') {
				echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			}
            ?>
			<?php if (!empty($image)) { ?>
				<strong class="logo">
					<a href="javascript:;">
						<img src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e('Image','docdirect');?>">
					</a>
				</strong>
			<?php }?>
			<?php if(isset($description) && !empty($description)){?>
			<div class="tg-description">
				<p><?php echo esc_attr($description); ?></p>
			</div>
			<?php }?>
			<?php
			if (isset($social) && $social == 'on') {
					$social_icons = '';
					if (function_exists('fw_get_db_settings_option')) {
						$social_icons = fw_get_db_settings_option('social_icons', $default_value = null);
						
					}
            ?>
			<ul class="tg-socialicon tg-haslayout">
            <?php 
                if(isset($social_icons) && !empty($social_icons)){
                    foreach($social_icons as $social){
                        ?>
                        <li>
                            <?php
                            $url = '';
                            if(isset($social['social_url']) && !empty($social['social_url'])){
                                $url = 'href="'.esc_url( $social['social_url'] ).'"';
                            }else{
                                $url = 'href="#"';
                            } 
                            ?>
                            <a <?php echo ($url); ?>>
                                <?php if(isset($social['social_icons_list']) && !empty($social['social_icons_list'])) { ?>
                                <i class="<?php echo esc_attr($social['social_icons_list']); ?>"></i>
                                <?php } ?>
                            </a>
                        </li>
                        <?php
                    }
                }
            ?>  
            </ul>			
			
			<?php
			}
            echo ($args['after_widget']);
        }

    }

}
add_action('widgets_init', create_function('', 'return register_widget("TG_Image");'));