<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('TGAccordion')) {

    class TGAccordion extends WP_Widget {
        /*
         * @init About Constructioners
         * 
         */

        public function __construct() {
            $widget_ops = array('classname' => 'tg-widget-accordions', 'description' => 'Display About Text With Social Icons');
            $control_ops = array('width' => 300, 'height' => 250, 'id_base' => 'tg_accordion');
            parent::__construct('tg_accordion', esc_html__('Accordion | DocDirect', 'docdirect'), $widget_ops, $control_ops);
        }

        /**
         * About Constructioners form
         *
         */
        public function form($instance) {
             $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Accordion', 'docdirect');
			 $active = !empty($instance['active']) ? $instance['active'] : '';
			 
			?>
            <p>
                <label for=""><?php esc_html_e('Title:', 'docdirect'); ?></label>
                <input type="text" id="title" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widefat" />
            </p>
			 <p>
                <input class="checkbox" type="checkbox" <?php checked($active, 'on'); ?> id="active" name="<?php echo esc_attr($this->get_field_name('active')); ?>" /> 
                <label for="show_social"><?php esc_html_e('Active First Tab', 'docdirect'); ?></label>
            </p>

			<fieldset class="accordion_html">
				<?php 
					$data	= isset( $instance['heading'] ) ? count( $instance['heading'] ) : '';
					if( isset( $data ) && !empty( $data ) && $data > 0 && !empty( $instance['heading'] ) ) {
						for( $i=0; $i < $data; $i++ ) {
							echo '<div class="data-services"><p>
									<label for="">'.esc_html__('Heading', 'docdirect').'</label>
									<input type="text" id="heading" name="widget-tg_accordion['.intval( $this->number ).'][heading][]" value="'.$instance['heading'][$i].'" class="widefat" />
								  </p> 
								  <p>
								  <label for="description">'.esc_html__('Description', 'docdirect').'</label>
								  <textarea id="description"  rows="8" cols="10" name="widget-tg_accordion['.intval( $this->number ).'][description][]" class="widefat">'.$instance['description'][$i].'</textarea>
								  </p><p class="data-del"><a href="javascript:;" class="delete-me"><i class="fa fa-times"></i></a></p></div>';
						}
					}
				?>
			</fieldset>
           <p class="accordion_link"><a href="javascript:;" data-widget="<?php echo intval( $this->number );?>" class="add_more_accordion"><?php esc_html_e('Add Tabs','docdirect');?></a></p>
		   
            <?php
        }

        /**
         * @Update About Constructioners 
         *
         */
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
            $instance['description'] = (!empty($new_instance['description']) ) ? $new_instance['description'] : '';
			$instance['heading'] = (!empty($new_instance['heading']) ) ? $new_instance['heading'] : '';
            $instance['active'] 	 = $new_instance['active'];
            return $instance;
        }

        /**
         * @Display About Constructioners 
         *
         *
         */
        public function widget($args, $instance) {
            extract($args);
            $title = empty($instance['title']) ? '' : $instance['title'];
            $active = $instance['active'];
            
			echo ($args['before_widget']);
            echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);

			$data	= count( $instance['heading'] );
			if( isset( $data ) && !empty( $data ) && $data > 0 ) {
				
            ?>
			<ul class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php for( $i=0; $i < $data; $i++ ) {
					
					if( isset( $instance['heading'][$i] ) && !empty( $instance['heading'][$i] ) ) {
						$activeTab	= '';
						$collapse	= '';
						if( $active == 'on' && $i == 0 ) {
							$activeTab	= 'actives';
							$collapse	= 'in';
						}
					?>
					<li class="panel panel-default <?php echo esc_attr( $activeTab );?>">
						<div class="tg-panel-heading" role="tab" id="heading<?php echo intval( $i );?>">
							<h3 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo intval( $i );?>" aria-expanded="true" aria-controls="collapse<?php echo intval( $i );?>"><?php echo esc_attr( $instance['heading'][$i] );?></a>
							</h3>
						</div>
						<div id="collapse<?php echo intval( $i );?>" class="panel-collapse collapse <?php echo esc_attr( $collapse );?>" role="tabpanel" aria-labelledby="heading<?php echo intval( $i );?>">
							<div class="panel-body">
								<p><?php echo do_shortcode( $instance['description'][$i] );?></p>
							</div>
						</div>
					</li>
				<?php }}?>
			</ul>
			<?php }?>
			<?php
            echo ($args['after_widget']);
        }

    }

}
add_action('widgets_init', create_function('', 'return register_widget("TGAccordion");'));