<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('TGFlickr')) {

    class TGFlickr extends WP_Widget {

        /**
         * @init Flickr Widget
         *
         *
         */
        public function __construct() {
            $widget_ops = array('classname' => 'tg-widget-flickr', 'description' => 'To Displays Flickr Gallery.');
            $control_ops = array('width' => 300, 'height' => 250, 'id_base' => 'tg_flickr');
            parent::__construct('tg_flickr', esc_html__('Flickr Widget | DocDirect', 'docdirect'), $widget_ops, $control_ops);
        }

        /**
         * @Social Media form
         *
         *
         */
        public function form($instance) {
            if (isset($instance['title']) && isset($instance['username'])) {
                $title = $instance['title'];
                $username = $instance['username'];
				$no_of_photos = $instance['no_of_photos'];
            } else {
                $title  = esc_html__('Flickr Photos', 'docdirect');
                $username = esc_html__('', 'docdirect');
				$no_of_photos 	= 8;
            }
            ?>

            <p>
                <label for="title">
                    <?php esc_html_e('Title', 'docdirect'); ?>
                </label>
                <input id="title" class="widefat" name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                       type="text" value="<?php echo esc_attr($title); ?>"/>
            </p>
            <p>
                <label for="username">
                    <?php esc_html_e('User Name', 'docdirect'); ?>
                </label>
                <input id="title" class="widefat" name="<?php echo esc_attr($this->get_field_name('username')); ?>"
                       type="text" value="<?php echo esc_attr($username); ?>"/>
            </p>
			<p>
                <label for="no_of_photos">
                    <?php esc_html_e('No of Photos', 'docdirect'); ?>
                </label>
                <input id="no_of_photos" class="widefat" name="<?php echo esc_attr($this->get_field_name('no_of_photos')); ?>"
                       type="number" value="<?php echo esc_attr($no_of_photos); ?>"/>
            </p>
            <?php
        }

        /**
         * @Update Social Media
         *
         *
         */
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
            $instance['username'] = (!empty($new_instance['username']) ) ? $new_instance['username'] : '';
			$instance['no_of_photos'] = (!empty($new_instance['no_of_photos']) ) ? $new_instance['no_of_photos'] : '8';
            return $instance;
        }

        /**
         * @Display Social Media
         *
         *
         */
        public function widget($args, $instance) {
            extract($args);
			
			$title  = $instance['title'];
            $username = $instance['username'];
			$no_of_photos = $instance['no_of_photos'];
			
			$apiKey = '';
			$apiSecret = '';
			$return	= '';
			$no_of_photos    = isset($no_of_photos) && !empty($no_of_photos) ? $no_of_photos : '10';
            $fliker_username = isset($username) && !empty($username) ? $username : 'envato';
			
            echo ($args['before_widget']);
            echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			
            if (function_exists('fw_get_db_settings_option')) {
                $apiKey = fw_get_db_settings_option('flickr_key', $default_value = null);
				$apiSecret = fw_get_db_settings_option('flickr_secret', $default_value = null);
            }
			
			if ($apiKey <> '') {
                // Getting transient
                $cachetime       = 86400;
                $transient       = 'flickr_gallery_data';
                $check_transient = get_transient($transient);
                $check_transient = false;
                
                // Get Flickr Gallery saved data
                $saved_data      = get_option('flickr_gallery_data');
                $db_apiKey       = '';
                $db_user_name    = '';
                $db_total_photos = '';
                if ($saved_data <> '') {
                    $db_apiKey       = isset($saved_data['api_key']) ? $saved_data['api_key'] : '';
                    $db_user_name    = isset($saved_data['user_name']) ? $saved_data['user_name'] : '';
                    $db_total_photos = isset($saved_data['total_photos']) ? $saved_data['total_photos'] : '';
                }
                if ($check_transient === false || ($apiKey <> $db_apiKey || $username <> $db_user_name || $no_of_photos <> $db_total_photos)) {
                    $user_id = "https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=" . $apiKey . "&username=" . $username . "&format=json&nojsoncallback=1";

                    $response = wp_remote_post($user_id , array (
								 'method'      => 'POST' ,
								 'timeout'     => 45 ,
								 'redirection' => 5 ,
								 'httpversion' => '1.0' ,
								 'blocking'    => true ,
								 'headers'     => array () ,
								 'cookies'     => array ()
                            )
                    );

                    $result = wp_remote_retrieve_body($response);

                    $user_info = json_decode($result , true);

                    if ($user_info['stat'] == 'ok') {
                        $user_get_id                   = $user_info['user']['id'];
                        $get_flickr_array['api_key']   = $apiKey;
                        $get_flickr_array['user_name'] = $username;
                        $get_flickr_array['user_id']   = $user_get_id;
                        $url                           = "https://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=" . $apiKey . "&user_id=" . $user_get_id . "&per_page=" . $no_of_photos . "&format=json&nojsoncallback=1";

                        $response = wp_remote_post($url , array (
                                    'method'      => 'POST' ,
                                    'timeout'     => 45 ,
                                    'redirection' => 5 ,
                                    'httpversion' => '1.0' ,
                                    'blocking'    => true ,
                                    'headers'     => array () ,
                                    'cookies'     => array ()
                                )
                        );

                        $result  = wp_remote_retrieve_body($response);
                        $content = json_decode($result , true);

                        if (is_wp_error($response)) {
                            $error_message = $response->get_error_message();
                            echo "Something went wrong: $error_message";
                        } else {
                            $counter = 0;
                            $return	.= '<ul class="flicker-img-list">';
                            foreach ((array) $content['photos']['photo'] as $single_photo) {
                                $title      = $single_photo['title'];
                                $farm_id    = $single_photo['farm'];
                                $server_id  = $single_photo['server'];
                                $photo_id   = $single_photo['id'];
                                $secret_id  = $single_photo['secret'];
                                $size       = 's';
                                $image_file = 'http://farm' . $farm_id . '.staticflickr.com/' . $server_id . '/' . $photo_id . '_' . $secret_id . '_' . $size . '.' . 'jpg';

                                if (isset($image_file) && !empty($image_file) ) {
                                    $return	.= '<li>';
                                    $return	.= '<a target="_blank" class="flaticon-plus79" title="'.$single_photo['title'] .'" href="https://www.flickr.com/photos/'.$single_photo['owner']."/".$single_photo['id'].'">
													<img alt="'.esc_attr( $single_photo['title'] ).'" src="'.esc_url( $image_file ) . '">
												</a>';
								    $return	.=  '</li>';
                                    $counter++;
                                    $get_flickr_array['photo_src'][]   = $image_file;
                                    $get_flickr_array['photo_title'][] = $single_photo['title'];
                                    $get_flickr_array['photo_owner'][] = $single_photo['owner'];
                                    $get_flickr_array['photo_id'][]    = $single_photo['id'];
                                }
                            }
                            $return	.=  '</ul>';
							
                            echo force_balance_tags( $return );
                            
							$get_flickr_array['total_photos'] = $counter;
                            // Setting Transient
                            set_transient($transient , true , $cachetime);
                            update_option('flickr_gallery_data' , $get_flickr_array);
                            if ($counter == 0)
                                esc_html_e('No result found.' , 'docdirect');
                        }
                    } else {
                        echo esc_html__('Error:' , 'docdirect') . $user_info['code'] . ' - ' . $user_info['message'];
                    }
                } else {
                    if (get_option('flickr_gallery_data') <> '') {
                        $flick_data = get_option('flickr_gallery_data');
                        $return	.= '<ul class="img-list">';
                        if (isset($flick_data['photo_src'])):
                            $i = 0;
                            foreach ($flick_data['photo_src'] as $ph) {
                                
								$return	.= '<li>';
								$return	.= '<a target="_blank" class="flaticon-plus79" title="'.$flick_data['photo_title'][$i] .'" href="https://www.flickr.com/photos/'.$flick_data['photo_owner'][$i]."/".$flick_data['photo_id'][$i].'">
												<img alt="'.esc_attr( $flick_data['photo_title'][$i] ).'" src="'.esc_url( $flick_data['photo_src'][$i]) . '">
											</a>';
								$return	.=  '</li>';
                                $i++;
                            }
                        endif;
                        $return	.= '</ul>';
					    echo force_balance_tags( $return );
                    } else {
                        esc_html_e('No result found.' , 'docdirect');
                    }
                }
            } else {
                esc_html_e('Please set API key first. Please go to Plugin Options' , 'docdirect');
            }
            echo ($args['after_widget']);
        }

    }

}
add_action('widgets_init', create_function('', 'return register_widget("TGFlickr");'));
?>