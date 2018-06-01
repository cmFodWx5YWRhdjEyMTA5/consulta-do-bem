<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}
if (!class_exists('TGRecentPost')) {

    class TGRecentPost extends WP_Widget {
        /*
         * @init Related Blogs
         * 
         */

        public function __construct() {
            $widget_ops_rel = array('classname' => 'tg-widget-recentposts', 'description' => 'Display Recent Posts');
            $control_ops_rel = array('width' => 300, 'height' => 250, 'id_base' => 'docdirect_recent_posts');
            parent::__construct('docdirect_recent_posts', esc_html__('Recent Posts Widget | DocDirect', 'docdirect'), $widget_ops_rel, $control_ops_rel);
        }

        /**
         *  Related Blogs Form
         *
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'docdirect');
            $number = isset($instance['number']) && !empty($instance['number']) ? $instance['number'] : '';
			$listing_view = isset($instance['view']) && !empty($instance['view']) ? $instance['view'] : 'view_1';

            ?>
            <p>
                <label for="title"><?php esc_html_e('Title:', 'docdirect'); ?></label>
                <input type="text" id="title" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widefat" />
            </p>
            <p>
                <label for="number"><?php esc_html_e('Number of posts to show:', 'docdirect'); ?></label>
                <input type="number" id="number" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" class="widefat" />
            </p>
           


            <?php
        }

        /**
         * @Update Related Blogs 
         *
         */
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['number'] = $new_instance['number'];
            return $instance;
        }

        /**
         * @Display Related Blogs
         *
         *
         */
        public function widget($args, $instance) {

            extract($args);
            $title = $instance['title'];
            $number_of_posts = $instance['number'];
            $query_args = array('post_type' => 'post', 'posts_per_page' => $number_of_posts, 'orderby' => 'post_date','order' => 'DESC');
            $loop = new WP_Query($query_args);
			
            echo ($args['before_widget']);
			echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			
			?>
			<ul>
				<?php
				while ($loop->have_posts()) : $loop->the_post();
					 global $post;
					 $post_id = $post->ID;
					 $thumbnail = docdirect_prepare_thumbnail($post_id, 75, 75);
					?>
					<li>
						<a href="<?php echo esc_url( get_the_permalink());?>"><?php the_title();?></a>
						<time datetime="<?php echo date_i18n('Y-m-d', strtotime(get_the_date('Y-m-d',$post_id))); ?>"><?php echo date_i18n(get_option('date_format'), strtotime(get_the_date('Y-m-d',$post_id))); ?></time>
					</li>
				<?php endwhile; ?>
			</ul>
            <?php echo ($args['after_widget']); ?>
            <?php
        }

    }

}

add_action('widgets_init', create_function('', 'return register_widget("TGRecentPost");'));
