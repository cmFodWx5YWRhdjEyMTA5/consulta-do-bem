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

if (!class_exists('Docdirect_RecentQuestion')) {

    class Docdirect_RecentQuestion extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                    'docdirect_recentquestions' , // Base ID
                    esc_html__('Recent Questions | Docdirect' , 'docdirect') , // Name
                array (
                	'classname' => 'tg-burfats questions-widgets',
					'description' => esc_html__('Docdirect Recent Questions' , 'docdirect') , 
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
			$number_of_posts = isset($instance['number_of_posts']) && !empty($instance['number_of_posts']) ? $instance['number_of_posts'] : -1;
			$title = isset($instance['title']) && !empty($instance['title']) ? $instance['title'] : '';
			  
            $before	= ($args['before_widget']);
			$after	 = ($args['after_widget']);
			
			echo ($before);
			if (function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
				$query_args = array(
					'posts_per_page' => $number_of_posts,
					'post_type' => 'sp_questions',
					'order' => 'DESC',
					'post_status' => 'publish',
					'orderby' => 'ID',
					'suppress_filters' => false,
					'ignore_sticky_posts' => 1
				);
	
				if (!empty($title) ) {
					echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
				}
	
				$q_query = new WP_Query($query_args);
				if( $q_query->have_posts() ) {
				?>
				<div id="tg-content" class="tg-content tg-companyfeaturebox">
				<?php 
					while ($q_query->have_posts()) : $q_query->the_post();
						global $post;
						$question_by = get_post_meta($post->ID, 'question_by', true);
						$question_to = get_post_meta($post->ID, 'question_to', true);
						$category 	 = get_post_meta($post->ID, 'question_cat', true);
			
						$category_icon = '';
						if (function_exists('fw_get_db_post_option') && !empty( $category )) {
							$category_icon = fw_get_db_post_option($category, 'dir_icon', true);
						}
						?>
						<div class="tg-question">
							<div class="tg-questioncontent">
								<div class="tg-answerholder">
									<?php if (!empty($category_icon)) {?>
										<figure class="tg-docimg"><span class="<?php echo esc_attr($category_icon); ?> tg-categoryicon"></span></figure>
									<?php }?>
		
									<h4><a href="<?php echo esc_url(get_permalink()); ?>"> <?php echo esc_attr(get_the_title()); ?> </a></h4>
									<div class="tg-questionbottom">
										<?php fw_ext_get_total_votes_and_answers_html($post->ID);?>
									</div>
								</div>
							</div>
						</div>
					<?php 
					endwhile;
					wp_reset_postdata(); ?>
				</div>
				<?php
				}
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
            $title           = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Questions Posted' , 'docdirect');
            $number_of_posts = !empty($instance['number_of_posts']) ? $instance['number_of_posts'] : 3;
            ?>
			<p>
                <label for="<?php echo ( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:','docdirect'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('title') ); ?>" name="<?php echo ( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo ( $this->get_field_id('number_of_posts') ); ?>"><?php esc_html_e('Number of Questions to show:','docdirect'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('number_of_posts') ); ?>" name="<?php echo ( $this->get_field_name('number_of_posts')); ?>" type="number" min="1" value="<?php echo esc_attr($number_of_posts); ?>">
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
            $instance['number_of_posts'] = (!empty($new_instance['number_of_posts']) ) ? strip_tags($new_instance['number_of_posts']) : '';

            return $instance;
        }

    }

}
//register widget
add_action('widgets_init', create_function('', 'return register_widget("Docdirect_RecentQuestion");'));