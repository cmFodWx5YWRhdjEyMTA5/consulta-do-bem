<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-out-authors-widget
 *
 * @author ab
 */
 
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('Docdirect_AQ_Statics')) {

    class Docdirect_AQ_Statics extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                    'docdirect_aq_staticcs' , // Base ID
                    esc_html__('Answers & Questions Statics | Docdirect' , 'docdirect') , // Name
                array (
                	'classname' => 'tg-burfats questions-statics-widgets',
					'description' => esc_html__('Answers & Questions Statics' , 'docdirect') , 
				) // Args
            );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args , $instance) {
            // outputs the content of the widget
            extract($instance);
			$title = isset($instance['title']) && !empty($instance['title']) ? $instance['title'] : '';
			  
            $before	= ($args['before_widget']);
			$after	 = ($args['after_widget']);
			
			echo ($before);

			if (!empty($title) ) {
				echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			}
			
			if (function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
				$question_total_ans 	= fw_ext_get_total_question_answers();
				$total_questions 	   = fw_ext_get_total_questions();
				?>
				<div class="aq-statics">
					<ul class="tg-votesanswers">
						<li><a><em><?php  esc_html_e('Total Questions posted' , 'docdirect');?></em> <span><?php echo intval($total_questions); ?></span></a></li>
						<li><a><em><?php  esc_html_e('Total Queries answered' , 'docdirect');?></em> <span><?php echo intval($question_total_ans); ?></span></a></li>
					</ul>
				</div>
				<?php
			} else{?>
				<div class="tg-dashboardappointmentbox">
					<?php DoctorDirectory_NotificationsHelper::informations(esc_html__('Please activate questions and answers extension. Go to Unyson > Questions and Answers', 'docdirect')); ?>
				</div>
			<?php 	
			}
			echo ( $after );
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
			// outputs the options form on admin
			$title           = !empty($instance['title']) ? $instance['title'] : esc_html__('Statics' , 'docdirect');
			?>
			<p>
                <label for="<?php echo ( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:','docdirect'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('title') ); ?>" name="<?php echo ( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update($new_instance , $old_instance) {
            // processes widget options to be saved
            $instance                    = $old_instance;
            $instance['title']           = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
            
            return $instance;
        }

    }

}
//register widget
add_action('widgets_init', create_function('', 'return register_widget("Docdirect_AQ_Statics");'));