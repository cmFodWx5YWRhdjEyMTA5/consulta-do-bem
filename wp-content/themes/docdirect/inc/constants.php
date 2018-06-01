<?php
/**
 *  Contants
 */
 
if (!function_exists('docdirect_prepare_constants')) {
    function docdirect_prepare_constants() {
		
		$user_status = 'false';	
		if ( is_user_logged_in() ){ 
			$user_status = 'true';	
		}
		
		$calendar_locale    = 'en';
		$calendar_format	= 'Y-m-d';
		$site_rtl 			= 'false';
		$loading_duration	= 500;
		$sub_category 			= 'disable';
		
		if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){
			$sub_category 			= 'enable';
		}
		
		if (function_exists('fw_get_db_settings_option')) {
			$site_key = fw_get_db_settings_option('site_key');
			$dir_cluster_marker = fw_get_db_settings_option('dir_cluster_marker');
			$dir_map_marker = fw_get_db_settings_option('dir_map_marker');
			$dir_cluster_color = fw_get_db_settings_option('dir_cluster_color');
			$dir_map_type = fw_get_db_settings_option('dir_map_type');
			$dir_zoom = fw_get_db_settings_option('dir_zoom');
			$dir_longitude = fw_get_db_settings_option('dir_longitude');
			$dir_latitude = fw_get_db_settings_option('dir_latitude');
			$dir_datasize = fw_get_db_settings_option('dir_datasize');
			$dir_map_scroll = fw_get_db_settings_option('dir_map_scroll');
			$map_styles = fw_get_db_settings_option('map_styles');
			$country_restrict = fw_get_db_settings_option('country_restrict');
			$dir_close_marker = get_template_directory_uri().'/images/close.gif';
			$captcha_settings = fw_get_db_settings_option('captcha_settings');
			$preloader = fw_get_db_settings_option('preloader');
			$speciality_limit = fw_get_db_settings_option('speciality_limit');
			
			$calendar_format    = fw_get_db_settings_option('calendar_format');
			$calendar_locale    = fw_get_db_settings_option('calendar_locale');
			
			$calendar_format	= !empty( $calendar_format ) ?  $calendar_format : 'Y-m-d';
			
			$loading_duration	= !empty( $preloader['enable']['loader_speed'] ) ? $preloader['enable']['loader_speed'] : 500;
			
			if( is_rtl() ){
				$site_rtl = 'true';
			} else{
				$site_rtl = 'false';
			}
			
			if( !empty( $country_restrict['gadget'] ) && $country_restrict['gadget'] === 'enable' && !empty( $country_restrict['enable']['country_code'] ) ){
				$country_restrict	= $country_restrict['enable']['country_code'];
			} else{
				$country_restrict	= '';
			}
			
			if( !empty( $dir_cluster_marker ) ){
				$dir_cluster_marker = $dir_cluster_marker['url'];
			} else{
				$dir_cluster_marker = get_template_directory_uri().'/images/cluster.png';
			}
			
			if( empty( $dir_map_marker ) ){
				$dir_map_marker = get_template_directory_uri().'/images/masrker.png';
			}
			
			if( empty( $dir_cluster_color ) ){
				$dir_cluster_color = '#7dbb00';
			}
			
			if( empty( $dir_map_type ) ){
				$dir_map_type = 'ROADMAP';
			}
			
			if( empty( $dir_zoom ) ){
				$dir_zoom = '12';
			}
			
			if( empty( $dir_longitude ) ){
				$dir_longitude = '-0.1262362';
			}
			
			if( empty( $dir_latitude ) ){
				$dir_latitude = '51.5001524';
			}
			
			if( empty( $dir_datasize ) ){
				$dir_datasize = '5242880';
			} 
			
			if( empty( $dir_map_scroll ) ){
				$dir_map_scroll = 'false';
			}      
			
		} else{
			$site_key = '';
			$dir_cluster_marker = get_template_directory_uri().'/images/cluster.png';
			$dir_map_marker = get_template_directory_uri().'/images/marker.png';
			$dir_cluster_color = '#7dbb00';
			$dir_map_type = 'ROADMAP';
			$dir_zoom = '12';
			$dir_longitude = '-0.1262362';
			$dir_latitude = '51.5001524';
			$dir_datasize = '5242880';
			$dir_map_scroll = 'false';
			$map_styles = 'none';
			$country_restrict = '';
			$dir_close_marker = get_template_directory_uri().'/images/close.gif';
			$captcha_settings = '';
			$loading_duration	= 500;
		}
		
		$speciality_limit	= !empty( $speciality_limit ) ? $speciality_limit : '50';
		
		if( $dir_datasize >= 1024 ){
			$dir_datasize		= trim($dir_datasize);
			$data_size_in_kb	= $dir_datasize / 1024;
		}else{
			$data_size_in_kb    = '5120';
		}
		
		
		wp_localize_script('docdirect_user_profile', 'scripts_vars', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'theme_path_uri' => get_template_directory_uri(),
			'theme_path' => get_template_directory(),
			'award_name' => esc_html__('Award Name','docdirect'),
			'award_date' => esc_html__('Award Date','docdirect'),
			'award_description' => esc_html__('Award Description','docdirect'),
			'file_upload_title' => esc_html__('Avatar Upload','docdirect'),
			'delete_message' => esc_html__('Are you sure you want to delete your account?','docdirect'),
			'deactivate' => esc_html__('Are you sure you want to deactivate your account?','docdirect'),
			'delete_title' => esc_html__('Delete account?','docdirect'),
			'deactivate_title' => esc_html__('Deactivate account?','docdirect'),
			'file_upload_title' => esc_html__('Avatar Upload','docdirect'),
			'docdirect_upload_nounce' => wp_create_nonce ( 'docdirect_upload_nounce' ),
			
			'dir_close_marker' => $dir_close_marker,
			'dir_cluster_marker' => $dir_cluster_marker,
			'dir_map_marker' => $dir_map_marker,
			'dir_cluster_color' => $dir_cluster_color,
			'dir_map_type' => $dir_map_type,
			'dir_zoom' => $dir_zoom,
			'dir_longitude' => $dir_longitude,
			'dir_latitude' => $dir_latitude,
			'dir_datasize' => $dir_datasize,
			'data_size_in_kb' => $data_size_in_kb.'kb',
			'dir_map_scroll' => $dir_map_scroll,
			'map_styles' => $map_styles,
			'site_key' => $site_key,
			
			'rating_1' => esc_html__('Not Satisfied','docdirect'),
			'rating_2' => esc_html__('Satisfied','docdirect'),
			'rating_3' => esc_html__('Good','docdirect'),
			'rating_4' => esc_html__('Very Good','docdirect'),
			'rating_5' => esc_html__('Excellent','docdirect'),
			
			'delete_award' => esc_html__('Delete Award','docdirect'),
			'delete_award_message' => esc_html__('Are you sure, you want to delete this award?','docdirect'),
			'delete_education' => esc_html__('Delete Degree','docdirect'),
			'delete_education_message' => esc_html__('Are you sure, you want to delete this Degree?','docdirect'),
			
			'delete_experience' => esc_html__('Delete Experience','docdirect'),
			'delete_experience_message' => esc_html__('Are you sure, you want to delete this experience?','docdirect'),
			
			'delete_prices' => esc_html__('Delete Price/Service','docdirect'),
			'delete_prices_message' => esc_html__('Are you sure, you want to delete this price/service item?','docdirect'),
			
			'delete_category' => esc_html__('Delete Category','docdirect'),
			'delete_category_message' => esc_html__('Are you sure, you want to delete this category?','docdirect'),
			
			'delete_service' => esc_html__('Delete Service','docdirect'),
			'delete_service_message' => esc_html__('Are you sure, you want to delete this service?','docdirect'),
			
			'delete_slot' => esc_html__('Delete Slot','docdirect'),
			'delete_slot_message' => esc_html__('Are you sure, you want to delete this slot?','docdirect'),
			
			'delete_slot_date' => esc_html__('Delete Slot Date','docdirect'),
			'delete_slot_date_message' => esc_html__('Are you sure, you want to delete this slot date?','docdirect'),
			
			'approve_appointment' => esc_html__('Approve Appointment?','docdirect'),
			'approve_appointment_message' => esc_html__('Are you sure, you want to approve this appointment?','docdirect'),
			'cancel_appointment' => esc_html__('Cancel Appointment?','docdirect'),
			'cancel_appointment_message' => esc_html__('Are you sure, you want to cancel this appointment?','docdirect'),
			
			'delete_teams' => esc_html__('Delete Member','docdirect'),
			'delete_teams_message' => esc_html__('Are you sure, you want to delete this team member?','docdirect'),
			
			'booking_time' => esc_html__('Please select booking time.','docdirect'),
			'gmap_norecod' => esc_html__('No Record Found.','docdirect'),
			'fav_message' => esc_html__('Please login first.','docdirect'),
			'fav_nothing' => esc_html__('Nothing found.','docdirect'),
			'empty_category' => esc_html__('Please add category name.','docdirect'),
			'complete_fields' => esc_html__('Please fill all the fields.','docdirect'),
			'system_error' => esc_html__('Some error occur, please try again later.','docdirect'),
			'valid_email' => esc_html__('Please add valid email address.','docdirect'),
			'user_status' => $user_status,
			'custom_slots_dates' => esc_html__('Atleast one date! start or end date is required.','docdirect'),
			'finish' => esc_html__('Finish','docdirect'),
			'with_in' => esc_html__('within ','docdirect'),
			'kilometer' => esc_html__(' km','docdirect'),
			'select_language' => esc_html__(' Select Language','docdirect'),
			'add_now' => esc_html__('Add Now','docdirect'),
			'view_profile' => esc_html__('View Full Profile','docdirect'),
			'no_team' => esc_html__('No team members, Add your teams now.','docdirect'),
			'country_restrict' => $country_restrict,
			'captcha_settings' => $captcha_settings,
			'site_rtl' => $site_rtl,
			'calendar_format' => $calendar_format,
			'calendar_locale' => $calendar_locale,
			'yes' => esc_html__('Yes','docdirect'),
			'no' => esc_html__('No','docdirect'),
			'account_verification' => esc_html__('Your account has verified.','docdirect'),
			'order' => esc_html__('Process order?','docdirect'),
			'order_message' => esc_html__('Are you sure you want to process this order.','docdirect'),
			
			'select_options' => esc_html__('Select Options','docdirect'),
			'no_results_text' => esc_html__('No results match','docdirect'),
			'order' => esc_html__('Process','docdirect'),
			'order' => esc_html__('Process','docdirect'),
			
			'days' => esc_html__('Days','docdirect'),
			'hours' => esc_html__('Hours','docdirect'),
			'minutes' => esc_html__('Minutes','docdirect'),
			'expired' => esc_html__('EXPIRED','docdirect'),
			'min_and' => esc_html__('Minutes and','docdirect'),
			'seconds' => esc_html__('Seconds','docdirect'),
			'loading_duration' => $loading_duration,
			'speciality_limit' => $speciality_limit,
			'sub_category' => $sub_category,
			'speciality_limit_message' => esc_html__('Opps! You reached to maximum speciality limit. Please contact to administrator to add more.','docdirect'),

		));
	}
	add_action('wp_enqueue_scripts', 'docdirect_prepare_constants');
}