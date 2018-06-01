<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('TG_Address_Widget')) {

    class TG_Address_Widget extends WP_Widget {
        /*
         * @init About Doctor Directorys
         * 
         */

        public function __construct() {
            $widget_ops = array('classname' => 'address-column', 'description' => 'Display Address Widget');
            $control_ops = array('width' => 300, 'height' => 250, 'id_base' => 'address_widget');
            parent::__construct('address_widget', esc_html__('Address Widget| DocDirect', 'docdirect'), $widget_ops, $control_ops);
        }

        /**
         * About Doctor Directorys form
         *
         */
        public function form($instance) {
           $title 		 	= isset( $instance['title'] ) ? esc_attr($instance['title']) : '';
		   $address 		= isset( $instance['address'] ) ? esc_attr($instance['address']) : '';
		   $email 		 	= isset( $instance['email'] ) ? esc_attr($instance['email']) : '';
		   $phone 		 	= isset( $instance['phone'] ) ? esc_attr($instance['phone']) : '';
		   $fax 		 	= isset( $instance['fax'] ) ? esc_attr($instance['fax']) : '';
		   $image 		    = isset( $instance['image'] ) ? $instance['image'] : '';
		   $description 	= isset( $instance['description']) ? $instance['description'] : '';
		  
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
                <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'docdirect'); ?></label>
                <textarea id="address" name="<?php echo esc_attr($this->get_field_name('address')); ?>"  class="widefat" ><?php echo esc_attr($address); ?></textarea>
            </p>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email:', 'docdirect'); ?></label>
                <textarea id="email" name="<?php echo esc_attr($this->get_field_name('email')); ?>" class="widefat" ><?php echo esc_attr($email); ?></textarea>
            </p>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:', 'docdirect'); ?></label>
                <textarea id="phone" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" class="widefat"><?php echo esc_attr($phone); ?></textarea>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('fax')); ?>"><?php esc_html_e('Fax:', 'docdirect'); ?></label>
                <textarea id="fax" name="<?php echo esc_attr($this->get_field_name('fax')); ?>" class="widefat"><?php echo esc_attr($fax); ?></textarea>
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
            $instance['address'] = (!empty($new_instance['address']) ) ? $new_instance['address'] : '';
			$instance['email'] = (!empty($new_instance['email']) ) ? $new_instance['email'] : '';
            $instance['phone'] = (!empty($new_instance['phone']) ) ? $new_instance['phone'] : '';
			 $instance['fax'] = (!empty($new_instance['fax']) ) ? $new_instance['fax'] : '';
			$instance['image'] = (!empty($new_instance['image']) ) ? $new_instance['image'] : '';
			$instance['description'] = (!empty($new_instance['description']) ) ? $new_instance['description'] : '';

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
            $address = empty($instance['address']) ? '' : $instance['address'];
			$email = empty($instance['email']) ? '' : $instance['email'];
            $phone = empty($instance['phone']) ? '' : $instance['phone'];
			$fax = empty($instance['fax']) ? '' : $instance['fax'];
			$image = empty($instance['image']) ? '' : $instance['image'];
			$description = empty($instance['description']) ? '' : $instance['description'];
			
            echo ($args['before_widget']);
 
			if (!empty($title) && $title !='') {
				echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			}
			?>
			<?php if (!empty($image)) { ?>
				<strong class="logo">
					<img src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e('logo','docdirect');?>">
				</strong>
			<?php }?>
			<?php if(isset($description) && !empty($description)){?>
				<div class="tg-description">
					<p><?php echo esc_attr($description); ?></p>
				</div>
			<?php }?>
			<?php if ( !empty($address) || !empty($email) || !empty($phone) ) {?>
			<ul class="tg-info">
				<?php if ( !empty($address) ) {?>
				<li>
					<i class="fa fa-home"></i>
					<address><?php echo force_balance_tags( $address );?></address>
				</li>
				<?php } ?>
				<?php if ( !empty($phone) ) {?>
				<li>
					<i class="fa fa-phone"></i>
					<em><a href="tel:<?php echo esc_attr( $phone );?>"><?php echo force_balance_tags( $phone );?></a></em>
				</li>
				<?php } ?>
				<?php if ( !empty($email) ) {?>
					<li>
						<i class="fa fa-envelope"></i>
						<em><a href="mailto:<?php echo esc_attr( $email );?>"?subject=<?php esc_html_e('Hello','docdirect');?>><?php echo esc_attr( $email );?></a></em>
					</li>
				<?php } ?>
				
                <?php if ( !empty($fax) ) {?>
				<li>
					<i class="fa fa-fax"></i>
					<em><a href="javascript:;"><?php echo force_balance_tags( $fax );?></a></em>
				</li>
				<?php } ?>
			</ul>
			<?php } ?>
			<?php
            echo ($args['after_widget']);
        }

    }

}
add_action('widgets_init', create_function('', 'return register_widget("TG_Address_Widget");'));