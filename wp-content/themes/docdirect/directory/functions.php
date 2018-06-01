<?php
/**
 * @Update Schedules
 * @return {}
 */

if ( ! function_exists( 'docdirect_update_schedules' ) ) {
	function docdirect_update_schedules(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	= array();
		
		$schedules	= docdirect_sanitize_array($_POST['schedules']);
		update_user_meta( $user_identity, 'schedules', $schedules );
		
		//Time Formate
		if( !empty( $_POST['time_format'] ) ){
			update_user_meta( $user_identity, 'time_format', esc_attr( $_POST['time_format'] ) );
		}
		
		$json['type']	= 'success';
		$json['message']	= esc_html__('Schedules Updated.','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_update_schedules','docdirect_update_schedules');
	add_action( 'wp_ajax_nopriv_docdirect_update_schedules', 'docdirect_update_schedules' );
}

/**
 * @Validaet Email
 * @return {}
 */
if ( ! function_exists( 'docdirect_save_privacy_settings' ) ) {
	function docdirect_save_privacy_settings(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	= array();

		update_user_meta( $user_identity, 'privacy', docdirect_sanitize_array( $_POST['privacy'] ) );
		
		//update privacy for search
		if( !empty( $_POST['privacy'] ) ) {
			foreach( $_POST['privacy'] as $key => $value ) {
				update_user_meta( $user_identity, $key, esc_attr( $value ) );
			}
		}
		
		$json['type']	= 'success';
		$json['message']	= esc_html__('Privacy Settings Updated.','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_save_privacy_settings','docdirect_save_privacy_settings');
	add_action( 'wp_ajax_nopriv_docdirect_save_privacy_settings', 'docdirect_save_privacy_settings' );
}

/**
 * @Account Settings
 * @return {}
 */
if ( ! function_exists( 'docdirect_account_settings' ) ) {
	function docdirect_account_settings(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;

		//Update Socials
		if( isset( $_POST['socials'] ) && !empty( $_POST['socials'] ) ){
			foreach( $_POST['socials'] as $key=>$value ){
				update_user_meta( $user_identity, $key, esc_attr( $value ) );
			}
		}
		
		//Update Basics
		if( !empty( $_POST['basics'] ) ){
			foreach( $_POST['basics'] as $key => $value ){
				update_user_meta( $user_identity, $key, esc_attr( $value ) );
			}
		}

		//Professional Statements
		if( !empty( $_POST['professional_statements'] ) ){
			$professional_statements	= docdirect_sanitize_wp_editor($_POST['professional_statements']);
			update_user_meta( $user_identity, 'professional_statements', $professional_statements);
		}
		
		//update username
		$full_name = docdirect_get_username($user_identity);
		update_user_meta( $user_identity, 'full_name', esc_attr( $full_name ) );
		update_user_meta( $user_identity, 'username', esc_attr( $full_name ) );
		
		//Update General settings
		
		update_user_meta( $user_identity, 'video_url', esc_url( $_POST['video_url'] ) );
		wp_update_user( array( 'ID' => $user_identity, 'user_url' => esc_url($_POST['basics']['user_url']) ) );
		
		//Awards
		$awards	= array();
		if( !empty( $_POST['awards'] ) ){
			
			$counter	= 0;
			foreach( $_POST['awards'] as $key=>$value ){
				$awards[$counter]['name']	= esc_attr( $value['name'] ); 
				$awards[$counter]['date']	= esc_attr( $value['date'] );
				$awards[$counter]['date_formated']	= date_i18n('d M, Y',strtotime(esc_attr( $value['date'])));  
				$awards[$counter]['description']	  = esc_attr( $value['description'] ); 
				$counter++;
			}
			$json['awards']	= $awards;
		}
		update_user_meta( $user_identity, 'awards', $awards );
		
		//Gallery
		$user_gallery	= array();
		if( !empty( $_POST['user_gallery'] ) ){
			$counter	= 0;
			foreach( $_POST['user_gallery'] as $key=>$value ){
				$user_gallery[$value['attachment_id']]['url']	= esc_url( $value['url'] ); 
				$user_gallery[$value['attachment_id']]['id']	= esc_attr( $value['attachment_id']); 
				$counter++;
			}

		}
		update_user_meta( $user_identity, 'user_gallery', $user_gallery );
		
		//Specialities
		$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);
		if( isset( $db_directory_type ) && !empty( $db_directory_type ) ) {
			$specialities_list	 = docdirect_prepare_taxonomies('directory_type','specialities',0,'array');
		}
		
		$specialities	= array();
		$submitted_specialities	= docdirect_sanitize_array($_POST['specialities']);
		
		//limit specialities
		if (function_exists('fw_get_db_settings_option')) {
			$speciality_limit 		= fw_get_db_settings_option('speciality_limit');
		}
		$speciality_limit	= !empty( $speciality_limit ) ? $speciality_limit : '50';
		$submitted_specialities	= array_slice($submitted_specialities, 0, $speciality_limit);
		
		if( isset( $specialities_list ) && !empty( $specialities_list ) ){
			$counter	= 0;
			foreach( $specialities_list as $key => $speciality ){
				if( isset( $submitted_specialities ) 
				   	&& is_array( $submitted_specialities ) 
				    && in_array( $speciality->slug, $submitted_specialities ) 
				 ){
					update_user_meta( $user_identity, $speciality->slug, esc_attr( $speciality->slug ) );
					$specialities[$speciality->slug]	= $speciality->name;
				}else{
					update_user_meta( $user_identity, $speciality->slug, '' );
				}
				
				$counter++;
			}
		}
		
		update_user_meta( $user_identity, 'user_profile_specialities', $specialities );
		
		//Education
		$educations	= array();
		if( !empty( $_POST['education'] ) ){
			$counter	= 0;
			foreach( $_POST['education'] as $key=>$value ){
				if( !empty( $value['title'] ) && !empty( $value['institute'] ) ) {
					$educations[$counter]['title']		 = esc_attr( $value['title'] ); 
					$educations[$counter]['institute']	 = esc_attr( $value['institute'] ); 
					$educations[$counter]['start_date']	 = esc_attr( $value['start_date'] ); 
					$educations[$counter]['end_date']	 = esc_attr( $value['end_date'] ); 
					$educations[$counter]['start_date_formated']  = date_i18n('M,Y',strtotime(esc_attr( $value['start_date']))); 
					$educations[$counter]['end_date_formated']	= date_i18n('M,Y',strtotime(esc_attr( $value['end_date']))); 
					$educations[$counter]['description']		= esc_attr( $value['description'] ); 
					$counter++;
				}
			}
			$json['education']	= $educations;
		}
		update_user_meta( $user_identity, 'education', $educations );
		
		//Experience
		$experiences	= array();
		if( !empty( $_POST['experience'] ) ){
			$counter	= 0;
			foreach( $_POST['experience'] as $key=>$value ){
				if( !empty( $value['title'] ) && !empty( $value['company'] ) ) {
					$experiences[$counter]['title']			= esc_attr( $value['title'] ); 
					$experiences[$counter]['company']	 	= esc_attr( $value['company'] ); 
					$experiences[$counter]['start_date']	= esc_attr( $value['start_date'] ); 
					$experiences[$counter]['end_date']	  	= esc_attr( $value['end_date'] ); 
					$experiences[$counter]['start_date_formated']   = date_i18n('M,Y',strtotime(esc_attr( $value['start_date']))); 
					$experiences[$counter]['end_date_formated']		= date_i18n('M,Y',strtotime(esc_attr( $value['end_date']))); 
					$experiences[$counter]['description']			= esc_attr( $value['description'] ); 
					$counter++;
				}
			}
			$json['experience']	= $experiences;
		}
		update_user_meta( $user_identity, 'experience', $experiences );
		
		//Experience
		$prices	= array();
		if( !empty( $_POST['prices'] ) ){
			$counter	= 0;
			foreach( $_POST['prices'] as $key=>$value ){
				if( !empty( $value['title'] ) ) {
					$prices[$counter]['title']	= 	esc_attr( $value['title'] ); 
					$prices[$counter]['price']	 = 	esc_attr( $value['price'] ); 
					$prices[$counter]['description']	= 	esc_attr( $value['description'] ); 
					$counter++;
				}
			}
			$json['prices_list']	= $prices;
		}
		
		update_user_meta( $user_identity, 'prices_list', $prices );
		
		//Languages
		$languages	= array();
		if( isset( $_POST['language'] ) && !empty( $_POST['language'] ) ){
			$counter	= 0;
			foreach( $_POST['language'] as $key=>$value ){
				$db_value	 = esc_attr($value);
				$languages[$db_value]	= 	$db_value; 
				$counter++;
			}
		}
		update_user_meta( $user_identity, 'languages', $languages );
		
		
		//Insurance
		$insurance	= array();

		if( isset( $_POST['insurance'] ) && !empty( $_POST['insurance'] ) ){
			$counter	= 0;
			foreach( $_POST['insurance'] as $key => $value ){
				$db_value	 = esc_attr($value);
				$insurance[$db_value]	= $db_value; 
				$counter++;
			}
			
			$insurance	= array_filter($insurance);
		}
		
		update_user_meta( $user_identity, 'insurance', $insurance );
		
		//Update sub categories
		if(!empty($_POST['subcategory']) ){
			$subcategories	= array();
			$counter	= 0;
			foreach( $_POST['subcategory'] as $key => $value ){
				$db_value	 				= esc_attr($value);
				$subcategories[$db_value]	= $db_value; 
				$counter++;
			}

			$subcategories	= array_filter($subcategories);
			update_user_meta( $user_identity, 'doc_sub_categories', $subcategories );
		}
		
		update_user_meta( $user_identity, 'show_admin_bar_front', false );
		
		do_action('docdirect_do_update_profile_settings',$_POST); //Save custom data
		
		$json['type']	= 'success';
		$json['message']	= esc_html__('Settings saved.','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_account_settings','docdirect_account_settings');
	add_action( 'wp_ajax_nopriv_docdirect_account_settings', 'docdirect_account_settings' );
}

/**
 * @Delete Avatar
 * @return {}
 */
if ( ! function_exists( 'docdir_delete_avatar' ) ) {
	function docdir_delete_avatar() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	=  array();
		
		/*-----------------------------Demo Restriction-----------------------------------*/
		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'themographics.com' ){
			$json['type']	   =  "error";
			$json['message']	=  esc_html__("Sorry! you are restricted to perform this action on our demo.",'docdirect' );
			echo json_encode( $json );
			exit();
		}
		/*-----------------------------Demo Restriction END--------------------------------*/
			
		$update_avatar = update_user_meta($user_identity, 'userprofile_media', '');
		if($update_avatar){
			$json['avatar'] = get_template_directory_uri().'/images/user270x270.jpg';
			$json['type']		=  'success';	
			$json['message']		= esc_html__('Avatar deleted.','docdirect');	
		} else {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Some error occur, please try again later.','docdirect');	
		}
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdir_delete_avatar', 'docdir_delete_avatar');
	add_action('wp_ajax_nopriv_docdir_delete_avatar', 'docdir_delete_avatar');
}

/**
 * @Delete banner
 * @return {}
 */
if ( ! function_exists( 'docdir_delete_user_banner' ) ) {
	function docdir_delete_user_banner() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	=  array();
		
		/*-----------------------------Demo Restriction-----------------------------------*/
		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'themographics.com' ){
			$json['type']	   =  "error";
			$json['message']	=  esc_html__("Sorry! you are restricted to perform this action on our demo.",'docdirect' );
			echo json_encode( $json );
			exit();
		}
		/*-----------------------------Demo Restriction END--------------------------------*/
		
		$update_avatar = update_user_meta($user_identity, 'userprofile_banner', '');
		if($update_avatar){
			$json['avatar'] = get_template_directory_uri().'/images/user270x270.jpg';
			$json['type']		=  'success';	
			$json['message']		= esc_html__('Banner deleted.','docdirect');	
		} else {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Some error occur, please try again later.','docdirect');	
		}
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdir_delete_user_banner', 'docdir_delete_user_banner');
	add_action('wp_ajax_nopriv_docdir_delete_user_banner', 'docdir_delete_user_banner');
}

/**
 * @Delete Email Logo
 * @return {}
 */
if ( ! function_exists( 'docdir_delete_email_logo' ) ) {
	function docdir_delete_email_logo() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	=  array();
		
		$update_avatar = update_user_meta($user_identity, 'email_media', '');
		
		if($update_avatar){
			$json['avatar'] = get_template_directory_uri().'/images/user150x150.jpg';
			$json['type']		=  'success';	
			$json['message']		= esc_html__('Logo deleted.','docdirect');	
		} else {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Some error occur, please try again later.','docdirect');	
		}
		
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdir_delete_email_logo', 'docdir_delete_email_logo');
	add_action('wp_ajax_nopriv_docdir_delete_email_logo', 'docdir_delete_email_logo');
}

/**
 * @Delete Email Logo
 * @return {}
 */
if ( ! function_exists( 'docdir_update_booking_settings' ) ) {
	function docdir_update_booking_settings() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		update_user_meta( $user_identity, 'confirmation_title', sanitize_text_field( $_POST['confirmation_title'] ) );
		update_user_meta( $user_identity, 'approved_title', sanitize_text_field( $_POST['approved_title'] ) );
		update_user_meta( $user_identity, 'cancelled_title', sanitize_text_field( $_POST['cancelled_title'] ) );
		update_user_meta( $user_identity, 'currency', sanitize_text_field( $_POST['currency'] ) );
		update_user_meta( $user_identity, 'currency_symbol', sanitize_text_field( $_POST['currency_symbol'] ) );

		update_user_meta( $user_identity, 'thank_you', docdirect_sanitize_wp_editor( $_POST['thank_you'] ) );
		update_user_meta( $user_identity, 'schedule_message', docdirect_sanitize_wp_editor( $_POST['schedule_message'] ) );
		update_user_meta( $user_identity, 'booking_cancelled', docdirect_sanitize_wp_editor( $_POST['booking_cancelled'] ) );
		update_user_meta( $user_identity, 'booking_confirmed', docdirect_sanitize_wp_editor( $_POST['booking_confirmed'] ) );
		update_user_meta( $user_identity, 'booking_approved', docdirect_sanitize_wp_editor( $_POST['booking_approved'] ) );
		
		update_user_meta( $user_identity, 'paypal_enable', sanitize_text_field( $_POST['paypal_enable'] ) );
		update_user_meta( $user_identity, 'paypal_email_id', sanitize_text_field( $_POST['paypal_email_id'] ) );
		update_user_meta( $user_identity, 'stripe_enable', sanitize_text_field( $_POST['stripe_enable'] ) );
		update_user_meta( $user_identity, 'stripe_secret', sanitize_text_field( $_POST['stripe_secret'] ) );
		update_user_meta( $user_identity, 'stripe_publishable', sanitize_text_field( $_POST['stripe_publishable'] ) );
		update_user_meta( $user_identity, 'stripe_site', sanitize_text_field( $_POST['stripe_site'] ) );
		update_user_meta( $user_identity, 'stripe_decimal', sanitize_text_field( $_POST['stripe_decimal'] ) );
		
		$json['type']		=  'success';	
		$json['message']		= esc_html__('Booking settings updated.','docdirect');	

		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdir_update_booking_settings', 'docdir_update_booking_settings');
	add_action('wp_ajax_nopriv_docdir_update_booking_settings', 'docdir_update_booking_settings');
}

/**
 * @UPdate Avatar
 * @return {}
 */
if ( ! function_exists( 'docdirect_image_uploader' ) ) {
	function docdirect_image_uploader() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		/*-----------------------------Demo Restriction-----------------------------------*/
		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'themographics.com' ){
			$json['type']	   =  "error";
			$json['message']	=  esc_html__("Sorry! you are restricted to perform this action on our demo.",'docdirect' );
			echo json_encode( $json );
			exit();
		}
		/*-----------------------------Demo Restriction END--------------------------------*/
		
		$nonce = $_REQUEST[ 'nonce' ];
		$type = $_REQUEST[ 'type' ];
		
		if ( ! wp_verify_nonce( $nonce, 'docdirect_upload_nounce' ) ) {
			$ajax_response = array(
				'success' => false,
				'reason' => 'Security check failed!',
			);
			echo json_encode( $ajax_response );
			die;
		}
		
		$submitted_file = $_FILES[ 'docdirect_uploader' ];
		$uploaded_image = wp_handle_upload( $submitted_file, array( 'test_form' => false ) ); 

		if ( isset( $uploaded_image[ 'file' ] ) ) {
			$file_name = basename( $submitted_file[ 'name' ] );
			$file_type = wp_check_filetype( $uploaded_image[ 'file' ] );

			// Prepare an array of post data for the attachment.
			$attachment_details = array(
				'guid' => $uploaded_image[ 'url' ],
				'post_mime_type' => $file_type[ 'type' ],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment_details, $uploaded_image[ 'file' ] ); 
			$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_image[ 'file' ] ); 
			wp_update_attachment_metadata( $attach_id, $attach_data );                                    
			
			//Image Size
			$image_size	= 'thumbnail';
			if( isset( $type ) && $type === 'profile_image' ){
				$image_size	= 'docdirect_user_profile';
			} if( isset( $type ) && $type === 'profile_banner' ){
				$image_size	= 'docdirect_user_banner';
				docdirect_get_profile_image_url( $attach_data,$image_size ); //get image url
				$image_size	= 'docdirect_user_profile';
			} else if( isset( $type ) && $type === 'user_gallery' ){
				$image_size	= 'thumbnail';
			}
			
			
			$thumbnail_url = docdirect_get_profile_image_url( $attach_data,$image_size ); //get image url

			if( isset( $type ) && $type === 'profile_image' ){
				update_user_meta($user_identity, 'userprofile_media', $attach_id);
			} if( isset( $type ) && $type === 'profile_banner' ){
				update_user_meta($user_identity, 'userprofile_banner', $attach_id);
			} else if( isset( $type ) && $type === 'email_image' ){
				update_user_meta($user_identity, 'email_media', $attach_id);
			} else if( isset( $type ) && $type === 'user_gallery' ){
				//
			}
			
			$ajax_response = array(
				'success' => true,
				'url' => $thumbnail_url,
				'attachment_id' => $attach_id
			);

			echo json_encode( $ajax_response );
			die;

		} else {
			$ajax_response = array( 'success' => false, 'reason' => 'Image upload failed!' );
			echo json_encode( $ajax_response );
			die;
		}
	}
	add_action('wp_ajax_docdirect_image_uploader', 'docdirect_image_uploader');
	add_action('wp_ajax_nopriv_docdirect_image_uploader', 'docdirect_image_uploader');
}

/**
 * Delete Gallery Image
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdir_delete_gallery_image' ) ) {
	function docdir_delete_gallery_image() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$id	= 
		$json	=  array();
		
		$image_id	= intval( $_POST['id'] );
		$gallery	= get_the_author_meta('user_gallery',$user_identity);
		if( isset( $image_id ) && isset( $gallery[$image_id] ) ){
			unset($gallery[$image_id]);
			
			update_user_meta( $user_identity, 'user_gallery', $gallery );
			
			$json['type']		=  'success';	
			$json['message']		= esc_html__('Image deleted.','docdirect');	
		} else {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Some error occur, please try again later.','docdirect');	
		}
		
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdir_delete_gallery_image', 'docdir_delete_gallery_image');
	add_action('wp_ajax_nopriv_docdir_delete_gallery_image', 'docdir_delete_gallery_image');
}

/**
 * Update User Password
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdir_change_user_password' ) ) {
	function docdir_change_user_password() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	=  array();
		
		$user = wp_get_current_user(); //trace($user);
		$old_passowrd	= sanitize_text_field( $_POST['old_passowrd'] );
		$new_passowrd	= sanitize_text_field( $_POST['new_passowrd'] );
		$confirm_password	= sanitize_text_field( $_POST['confirm_password'] );
		
    	$is_password = wp_check_password( $old_passowrd, $user->user_pass, $user->data->ID );
		
		if( $is_password ){
		
			if ( empty( $new_passowrd ) || empty( $confirm_password ) ) {
				$json['type']		=  'error';	
				$json['message']		= esc_html__('Please add your new password.','docdirect');	
				echo json_encode($json);
				exit;
			}
			
			if ( esc_attr( $new_passowrd )  == esc_attr( $confirm_password ) ) {
				wp_update_user( array( 'ID' => $user_identity, 'user_pass' => esc_attr( $new_passowrd ) ) );
				$json['type']		=  'success';	
				$json['message']		= esc_html__('Password Updated.','docdirect');	
			} else {
				$json['type']		=  'error';	
				$json['message']		= esc_html__('The passwords you entered do not match. Your password was not updated', 'docdirect');
			}
		} else{
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Old Password doesn\'t match the existing password', 'docdirect');
		}
		
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdir_change_user_password', 'docdir_change_user_password');
	add_action('wp_ajax_nopriv_docdir_change_user_password', 'docdir_change_user_password');
}

/**
 * Get Month Array
 *
 * @param numeric value
 * @return string
 */
if ( ! function_exists( 'docdirect_get_month_array' ) ) {
	function docdirect_get_month_array() {
		return array(
			'01'	=> esc_html__('January','docdirect'),
			'02'	=> esc_html__('February','docdirect'),
			'03'	=> esc_html__('March','docdirect'),
			'04'	=> esc_html__('April','docdirect'),
			'05'	=> esc_html__('May','docdirect'),
			'06'	=> esc_html__('June','docdirect'),
			'07'	=> esc_html__('July','docdirect'),
			'08'	=> esc_html__('August','docdirect'),
			'09'	=> esc_html__('September','docdirect'),
			'10'	=> esc_html__('October','docdirect'),
			'11'	=> esc_html__('November','docdirect'),
			'12'	=> esc_html__('December','docdirect'),
		);
	}
}

/**
 * Get Week Array
 *
 * @param numeric value
 * @return string
 */
if ( ! function_exists( 'docdirect_get_week_array' ) ) {
	function docdirect_get_week_array() {
		return array(
			'mon'	=> esc_html__('Monday','docdirect'),
			'tue'	=> esc_html__('Tuesday','docdirect'),
			'wed'	=> esc_html__('Wednesday','docdirect'),
			'thu'	=> esc_html__('Thursday','docdirect'),
			'fri'	=> esc_html__('Friday','docdirect'),
			'sat'	=> esc_html__('Saturday','docdirect'),
			'sun'	=> esc_html__('Sunday','docdirect'),
		);
	}
}

/**
 * Update User Password
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_do_process_subscription' ) ) {
	function docdirect_do_process_subscription() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	=  array();
		
		/*-----------------------------Demo Restriction-----------------------------------*/
		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'themographics.com' ){
			$json['type']	   =  "error";
			$json['message']	=  esc_html__("Payments are disbaled at demo",'docdirect' );
			echo json_encode( $json );
			exit();
		}
		/*-----------------------------Demo Restriction END--------------------------------*/
		
		$user = wp_get_current_user(); //trace($user);
		
		$do_check = check_ajax_referer( 'docdirect_renew_nounce', 'renew-process', false );
		if( $do_check == false ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('No kiddies please!','docdirect');	
			$json['payment_type']  = 'gateway';
			echo json_encode($json);
			die;
		}
		
		//Account de-activation			
		$packs_id	= sanitize_text_field($_POST['packs']);
		$gateway	= sanitize_text_field($_POST['gateway']);
		
		if ( empty( $packs_id ) ) {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Please select a plan to subscribe.','docdirect');	
			$json['payment_type']  = 'gateway';
			echo json_encode($json);
			exit;
		} else if ( empty( $gateway ) ) {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Please select a payment gateway to subscribe.','docdirect');	
			$json['payment_type']  = 'gateway';
			echo json_encode($json);
			exit;
		} else if ( empty( $packs_id ) ||  empty( $gateway ) ) { 
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Some error occur, please try again later.','docdirect');
			$json['payment_type']  = 'gateway';	
			echo json_encode($json);
			exit;
		}
		
		
		
		$pack_title		    = get_the_title( sanitize_text_field( $_POST['packs'] ) ); 
		$duration 			= fw_get_db_post_option(sanitize_text_field( $_POST['packs'] ), 'duration', true);
		$price 			    = fw_get_db_post_option(sanitize_text_field( $_POST['packs'] ), 'price', true);
		$pac_subtitle 		= fw_get_db_post_option(sanitize_text_field( $_POST['packs'] ), 'pac_subtitle', true);
		$currency_select 	= fw_get_db_settings_option('currency_select');
		$currency_sign  	= fw_get_db_settings_option('currency_sign');
		
			
		if( isset( $_POST['gateway'] ) && $_POST['gateway'] === 'paypal' ){			
			
			/*---------------------------------------------
			 * @Paypal Payment Gateway Process
			 * @Return HTML
			 ---------------------------------------------*/
			$sandbox_enable = fw_get_db_settings_option('paypal_enable_sandbox');
			$business_email = fw_get_db_settings_option('paypal_bussiness_email');
			$listner_url    = fw_get_db_settings_option('paypal_listner_url');
		
			$package_name	= $pack_title.' - '.$duration.esc_html__('Days','docdirect');
			
            if (isset($sandbox_enable) && $sandbox_enable == 'on') {
                $paypal_path = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $paypal_path = 'https://www.paypal.com/cgi-bin/webscr';
            }

            if ($currency_select == '' || $business_email == '' || $listner_url == '') {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur please contact to administrator.', 'docdirect');
                echo json_encode($json);
                die;
            }
			
			//prepare return url
			$dir_profile_page = '';
			if (function_exists('fw_get_db_settings_option')) {
                $dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
            }
			
			//Add New Order
			$order_no	= docdirect_add_new_order(
				array(
					'packs'		=> sanitize_text_field( $_POST['packs'] ),
					'gateway'	  => sanitize_text_field( $_POST['gateway'] ),
					'price'		=> number_format((float)$price, 2, '.', ''),
					'payment_type' => 'gateway',
					'mc_currency'  => $currency_select,
				)
			);
			
			$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
			
			$return_url	= DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'invoices', $user_identity,true);
			
			$output  = '';
            $output .= '<form name="paypal_subscription" id="paypal_subscribe_form" action="' . $paypal_path . '" method="post">  
							<input type="hidden" name="cmd" value="_xclick">  
							<input type="hidden" name="business" value="' . $business_email . '">
							<input type="hidden" name="amount" value="' . $price . '">
							<input type="hidden" name="item_name" value="'.sanitize_text_field($package_name).'"> 
							<input type="hidden" name="currency_code" value="' . $currency_select . '">
							<input type="hidden" name="item_number" value="'.sanitize_text_field($_POST['packs']).'">  
							<input name="cancel_return" value="'.$return_url.'" type="hidden">
							<input type="hidden" name="custom" value="'.$order_no.'|_|'.sanitize_text_field($_POST['packs']).'|_|'.$user_identity.'">    
							<input type="hidden" name="no_note" value="1">  
							<input type="hidden" name="notify_url" value="' . $listner_url . '">
							<input type="hidden" name="lc">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="return" value="'.$return_url.'&subscription=done">  
					   </form>';

            $output .= '<script>
							jQuery("#paypal_subscribe_form").submit();
					  </script>';

            $json['form_data']  = $output;
            $json['type'] 		= 'success';
			$json['payment_type']  = 'gateway';
	
		}else if( isset( $_POST['gateway'] ) && $_POST['gateway'] === 'stripe' ){
				/*---------------------------------------------
				 * @Strip Payment Gateway Process
				 * @Return HTML
				 ---------------------------------------------*/
				 $currency_sign   = '';
				 $stripe_secret    = '';
				 $stripe_publishable = '';
				 $stripe_site     = '';
				 $stripe_decimal  = '';
				 $stripe_decimal  = 'en';
					
				 if (function_exists('fw_get_db_settings_option')) {
					$currency_sign   = fw_get_db_settings_option('currency_select');
					$stripe_secret    = fw_get_db_settings_option('stripe_secret');
					$stripe_publishable = fw_get_db_settings_option('stripe_publishable');
					$stripe_site     	= fw_get_db_settings_option('stripe_site');
					$stripe_decimal  	= fw_get_db_settings_option('stripe_decimal');
					$stripe_language  	= fw_get_db_settings_option('stripe_language');
				 }
				 
				 $stripe_language	=  !empty( $stripe_language ) ? $stripe_language : 'en';
				 
				 $total_amount	= $price;
				 
				 if( isset( $stripe_decimal ) && $stripe_decimal == 0 ){
					$package_amount	= $price;
				 } else{
					$package_amount	= $price*100;	 
				 }

				  
				//prepare return url
				$dir_profile_page = '';
				if (function_exists('fw_get_db_settings_option')) {
					$dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
				}
				
				//Add New Order
				$order_no	= docdirect_add_new_order(
					array(
						'packs'		=> sanitize_text_field( $_POST['packs'] ),
						'gateway'	  => sanitize_text_field( $_POST['gateway'] ),
						'price'		=> number_format((float)$price, 2, '.', ''),
						'payment_type' => 'gateway',
						'mc_currency'  => $currency_select,
					)
				);
				
				$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
				
				$return_url	= DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'invoices', $user_identity,true);
				
				$userdata	  = get_userdata( $user_identity );
				$user_email	= '';
				if( !empty( $userdata ) ) {
					$user_email	= $userdata->user_email;
				}
				
				$first_name	 = get_user_meta($user_identity,'first_name',true);
				$last_name	 = get_user_meta($user_identity,'last_name',true);
				$user_name	 = get_user_meta($user_identity,'first_name',true).' '.get_user_meta($user_identity,'last_name',true);
				$useraddress   = get_user_meta($user_identity,'address',true);

				
				$package_name	= $pack_title.' - '.$duration.esc_html__(' Days','docdirect');
				   
				echo json_encode( 
					array( 
						   'first_name' 	=> $first_name,
						   'last_name' 	   	=> $last_name,
						   'username' 	 	=> $user_name,
						   'email' 		    => $user_email,
						   'useraddress'    => $useraddress,
						   'order_no' 	    => $order_no,
						   'user_identity'  => $user_identity,
						   'package_id' 	=> sanitize_text_field( $_POST['packs'] ),
						   'package_name'   => $package_name,
						   'gateway' 	  	=> 'stripe',
						   'type' 		 	=> 'success',
						   'payment_type' 	=> 'stripe',
						   'process'=>true, 
						   'name'=> $stripe_site, 
						   'description'=> $package_name,
						   'locale'			=> $stripe_language,
						   'amount' => $package_amount,
						   'total_amount' => $total_amount,
						   'key'=> $stripe_publishable,
						   'currency'=> $currency_sign
						  )
					);
				 
				 die;

		}else if( isset( $_POST['gateway'] ) && $_POST['gateway'] === 'authorize' ){			
			/*---------------------------------------------
			 * @Authorize.Net Payment Gateway Process
			 * @Return HTML
			 ---------------------------------------------*/
			
			$current_date   				 = date('Y-m-d H:i:s');
			$output					   		= '';
			$authorize_login_id 		    = fw_get_db_settings_option('authorize_login_id');
			$authorize_transaction_key 		= fw_get_db_settings_option('authorize_transaction_key');
			$authorize_listner_url 			= fw_get_db_settings_option('authorize_listner_url');
			$authorize_enable_sandbox 	 	= fw_get_db_settings_option('authorize_enable_sandbox');
			
			$timeStamp	= time();
			$sequence	 = rand(1, 1000);
			
			if( phpversion() >= '5.1.2' ) {
				{ $fingerprint = hash_hmac("md5", $authorize_login_id . "^" . $sequence . "^" . $timeStamp . "^" . $price . "^". $currency_select, $authorize_transaction_key); }
			} else {
				$fingerprint = bin2hex(mhash(MHASH_MD5, $authorize_login_id . "^" . $sequence . "^" . $timeStamp . "^" . $price . "^". $currency_select, $authorize_transaction_key));
			}
				
			$package_name	= $pack_title.' - '.$duration.esc_html__('Days','docdirect');
			

            if ($currency_select == '' || $authorize_login_id == '') {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur please contact to administrator.', 'docdirect');
                echo json_encode($json);
                die;
            }
			
			if (isset($authorize_enable_sandbox) && $authorize_enable_sandbox == 'on') {
                $gateway_path = 'https://test.authorize.net/gateway/transact.dll';
            } else {
                $gateway_path = 'https://secure.authorize.net/gateway/transact.dll';
            }
			
			//Add New Order
			$order_no	= docdirect_add_new_order(
				array(
					'packs'		  => sanitize_text_field( $_POST['packs'] ),
					'gateway'	  => sanitize_text_field( $_POST['gateway'] ),
					'price'		  => $price,
					'payment_type' => 'gateway',
				)
			);
			
			//prepare return url
			$dir_profile_page = '';
			
			if (function_exists('fw_get_db_settings_option')) {
                $dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
            }

			$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
			
			$return_url	= DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'invoices', $user_identity,true);
			
			$output .= '<form name="AuthorizeForm" id="authorize-form" action="'.$gateway_path.'" method="post">  
							<input type="hidden" name="x_login" value="'.$authorize_login_id.'">
							<input type="hidden" name="x_type" value="AUTH_CAPTURE"/>
							<input type="hidden" name="x_amount" value="'.$price.'">
							<input type="hidden" name="x_fp_sequence" value="'.$sequence.'" />
							<input type="hidden" name="x_fp_timestamp" value="'.$timeStamp.'" />
							<input type="hidden" name="x_fp_hash" value="'.$fingerprint.'" />
							<input type="hidden" name="x_show_form" value="PAYMENT_FORM" />
							<input type="hidden" name="x_invoice_num" value="'.$package_name.'">
							<input type="hidden" name="x_po_num" value="'.$order_no.'|_|'.sanitize_text_field( $_POST['packs'] ).'|_|'.$user_identity.'">
							<input type="hidden" name="x_cust_id" value="'.sanitize_text_field($order_no).'"/> 
							<input type="hidden" name="x_first_name" value="'.get_user_meta('first_name' ,$user_identity).'"> 
							<input type="hidden" name="x_last_name" value="'.get_user_meta('last_name' ,$user_identity).'"> 
							<input type="hidden" name="x_address" value="'.get_user_meta( 'address' ,$user_identity).'"> 
							<input type="hidden" name="x_fax" value="'.get_user_meta('fax' ,$user_identity).'"> 
							<input type="hidden" name="x_email" value="'.get_user_meta('email' ,$user_identity).'"> 
							<input type="hidden" name="x_description" value="'.$package_name.'">
							<input type="hidden" name="x_currency_code" value="'.$currency_select.'" />	 
							<input type="hidden" name="x_cancel_url" value="'.esc_url( $return_url ).'" />
							<input type="hidden" name="x_cancel_url_text" value="Cancel Order" />
							<input type="hidden" name="x_relay_response" value="TRUE" />
							<input type="hidden" name="x_relay_url" value="'.sanitize_text_field( $authorize_listner_url ).'"/> 
							<input type="hidden" name="x_test_request" value="false"/>
						</form>';					

            $output .= '<script>
							jQuery("#authorize-form").submit();
					  </script>';

            $json['form_data']  = $output;
            $json['type'] 		= 'success';
			$json['payment_type']  = 'gateway';
	
		} else if( isset( $_POST['gateway'] ) && $_POST['gateway'] === 'bank' ){			
			/*---------------------------------------------
			 * @Bank Transfer Gateway Process
			 * @Return HTML
			 ---------------------------------------------*/
			$bank_name = fw_get_db_settings_option('bank_name');
			$bank_account = fw_get_db_settings_option('bank_account');
			$other_information    = fw_get_db_settings_option('other_information');
			$package_name	= $pack_title.'-'.$duration.' '.esc_html__('Days','docdirect');
			$first_name	  = get_user_meta($user_identity,'first_name',true);
			$last_name	 = get_user_meta($user_identity,'last_name',true);
			$user_name	 = get_user_meta($user_identity,'first_name',true).' '.get_user_meta($user_identity,'last_name',true);
			$useraddress   = get_user_meta($user_identity,'address',true);
			$package_id	= sanitize_text_field( $_POST['packs'] );
			
			$payment_date = date('Y-m-d H:i:s');
			$user_featured_date	= get_the_author_meta('user_featured',$user_identity, true);
			$featured_date	= date('Y-m-d H:i:s');
			
			if( !empty( $user_featured_date ) ){
				$duration = fw_get_db_post_option($package_id, 'duration', true);
				if( $duration > 0 ){
					$featured_date	= strtotime("+".$duration." days", $user_featured_date);
					$featured_date	= date('Y-m-d H:i:s',$featured_date);
				}
			} else{
				$current_date	= date('Y-m-d H:i:s');
				$duration = fw_get_db_post_option($package_id, 'duration', true);
				if( $duration > 0 ){
					$featured_date		 = strtotime("+".$duration." days", strtotime($current_date));
					$featured_date	     = date('Y-m-d H:i:s',$featured_date);
				}
			}
			
			$userdata	  = get_userdata( $user_identity );
			$user_email	= '';
			if( !empty( $userdata ) ) {
				$user_email	= $userdata->user_email;
			}
				
		
			//Add New Order
			$order_no	= docdirect_add_new_order(
				array(
					'packs'		  => sanitize_text_field( $_POST['packs'] ),
					'gateway'	  => sanitize_text_field( $_POST['gateway'] ),
					'price'		  => $price,
					'mc_currency' => $currency_select,
				)
			);
			
			$html	= '';
			$html	.= '<div class="membership-price-header">'.esc_html__('Order Summary','docdirect').'</div>';
			$html	.= '<div class="system-gateway">';
			
			$html	.= '<ul>';
				$html	.= '<li>';
					$html	.= '<label for="doc-payment-bank">'.esc_html__('General Information','docdirect').'</label>';
					$html	.= '<ul>';
						$html	.= '<li>';
						$html	.= '<span class="pull-left col-md-6 col-xs-12">'.esc_html__('Order No','docdirect').'</span>';
						$html	.= '<span class="pull-right col-md-6 col-xs-12">'.$order_no.'</span>';
						$html	.= '</li>';
						
						$html	.= '<li>';
						$html	.= '<span class="pull-left col-md-6 col-xs-12">'.esc_html__('Package Name','docdirect').'</span>';
						$html	.= '<span class="pull-right col-md-6 col-xs-12">'.get_the_title(sanitize_text_field($_POST['packs'])).'</span>';
						$html	.= '</li>';
					
						$html	.= '<li>';
						$html	.= '<span class="pull-left col-md-6 col-xs-12">'.esc_html__('Price','docdirect').'</span>';
						$html	.= '<span class="pull-right col-md-6 col-xs-12">'.$currency_sign.$price.'</span>';
						$html	.= '</li>';
						
						
					$html	.= '</ul>';
				$html	.= '</li>';
				$html	.= '<li>';
					$html	.= '<label for="doc-payment-bank">'.esc_html__('Bank Information','docdirect').'</label>';
					$html	.= '<ul>';
						if( !empty( $other_information ) ) {
							$html	.= '<li>';
							$html	.= '<span class="pull-left col-md-6 col-xs-12">'.esc_html__('Bank Name','docdirect').'</span>';
							$html	.= '<span class="pull-right col-md-6 col-xs-12">'.$bank_name.'</span>';
							$html	.= '</li>';
						}
						
						if( !empty( $other_information ) ) {
							$html	.= '<li>';
							$html	.= '<span class="pull-left col-md-6 col-xs-12">'.esc_html__('Bank Account No','docdirect').'</span>';
							$html	.= '<span class="pull-right col-md-6 col-xs-12">'.$bank_account.'</span>';
							$html	.= '</li>';
						}
						
						if( !empty( $other_information ) ) {
							$html	.= '<li>';
							$html	.= '<span class="pull-left col-md-6 col-xs-12">'.esc_html__('Other Information','docdirect').'</span>';
							$html	.= '<span class="pull-right col-md-6 col-xs-12">'.$other_information.'</span>';
							$html	.= '</li>';
						}
					$html	.= '</ul>';
				$html	.= '</li>';
			$html	.= '</ul>';
			
			
			//Send ean email 
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				$email_helper	= new DocDirectProcessEmail();
				$emailData	   = array();
				$emailData['mail_to']	  	    = $user_email;
				$emailData['name']			    = $user_name;
				$emailData['invoice']	  	    = $order_no;
				$emailData['package_name']	    = $package_name;					
				$emailData['amount']			= $currency_sign.$price;
				$emailData['status']			= esc_html__('Pending','docdirect');
				$emailData['method']			= esc_html__('Bank Transfer','docdirect');
				$emailData['date']			    = date('Y-m-d H:i:s');
				$emailData['expiry']			= $featured_date;
				$emailData['address']		    = $useraddress;
				
				$email_helper->process_invoice_email( $emailData );
			}
			
            $json['form_data']  = $html;
			$json['payment_type']  = 'bank';
            $json['type'] 		= 'success';
	
		} else{
			$json['message'] = esc_html__('Some error occur please contact to administrator.', 'docdirect');
            $json['type'] 		= 'error';
			$json['payment_type']  = 'gateway';
		}
		
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdirect_do_process_subscription', 'docdirect_do_process_subscription');
	add_action('wp_ajax_nopriv_docdirect_do_process_subscription', 'docdirect_do_process_subscription');
}

/**
 * @Stripe Payment
 *
 * @param json
 * @return string
 */
if ( !function_exists('docdirect_complete_stripe_payment') ) {
	function docdirect_complete_stripe_payment() {		
		$first_name   	= sanitize_text_field( $_POST['first_name'] );
		$last_name		= sanitize_text_field( $_POST['last_name'] );
		$username	 	= sanitize_text_field( $_POST['username'] );
		$user_identity	= sanitize_text_field( $_POST['user_identity'] );
		$email  		= sanitize_text_field( $_POST['email'] );
		$order_no 	 	= sanitize_text_field( $_POST['order_no'] );
		$package_id   	= sanitize_text_field( $_POST['package_id'] );
		$package_name 	= sanitize_text_field( $_POST['package_name'] );
		$useraddress  	= sanitize_text_field( $_POST['useraddress'] );
		$gateway 	  	= sanitize_text_field( $_POST['gateway'] );
		$type		 	= sanitize_text_field( $_POST['type'] );
		$payment_type 	= sanitize_text_field( $_POST['payment_type'] );
		$process 	  	= sanitize_text_field( $_POST['process'] );
		$name		 	= sanitize_text_field( $_POST['name'] );
		$amount	   		= sanitize_text_field( $_POST['amount'] );
		$total_amount 	= sanitize_text_field( $_POST['total_amount'] );
		$token	    	= docdirect_sanitize_array( $_POST['token'] );
		
		$currency_sign	= 'USD';
		
		 if (function_exists('fw_get_db_settings_option')) {
			$currency_sign   = fw_get_db_settings_option('currency_select');
			$stripe_secret    = fw_get_db_settings_option('stripe_secret');
			$stripe_publishable = fw_get_db_settings_option('stripe_publishable');
			$stripe_site     = fw_get_db_settings_option('stripe_site');
			$stripe_decimal  = fw_get_db_settings_option('stripe_decimal');
		 }
		 
		 
		 if( class_exists( 'DocDirectGlobalSettings' ) ) {		 
		 	require_once( DocDirectGlobalSettings::get_plugin_path().'/libraries/stripe/init.php');
		 } else{
			$json['type']     = 'error';
			$json['message']  = esc_html__('Stripe API not found.','docdirect');
			echo json_encode($json);
			die;
		 }
		 
		 $stripe = array(
			"secret_key"      => $stripe_secret,
			"publishable_key" => $stripe_publishable
		  );

		  \Stripe\Stripe::setApiKey($stripe['secret_key']);
		  
		  $charge = \Stripe\Charge::create(array(
			'amount'   	=> $amount,
			'currency' 	=> $currency_sign,
			'source'  	=> $token['id'],
			'description' => $package_name,
		  ));
		
		if ($charge->status == 'succeeded') {
			
			if( !empty( $charge->source->id ) ){
				$transaction_id	= $charge->source->id;
			} else{
				$transaction_id	= docdirect_unique_increment(10);
			}
			
			//Update Order
            $expiry_date	= docdirect_update_order_data(
				array(
					'order_id'		   => $order_no,
					'user_identity'	   => $user_identity,
					'package_id'	   => $package_id,
					'txn_id'		   => $transaction_id,
					'payment_gross'	   => $total_amount,
					'payment_method'   => 'stripe',
					'mc_currency'	   => $currency_sign,
				)
			);
			
			//Add Invoice
			docdirect_new_invoice(
				array(
					'user_identity'	 	=> $user_identity,
					'package_id'		=> $package_id,
					'txn_id'			=> $transaction_id,
					'payment_gross'	 	=> $total_amount,
					'item_name'		 	=> $package_name,
					'payer_email'	    => $email,
					'mc_currency'	    => $currency_sign,
					'address_name'	  	=> $useraddress,
					'ipn_track_id'	  	=> '',
					'transaction_status'=> 'approved',
					'payment_method'	=> 'stripe',
					'full_address'	  	=> $useraddress,
					'first_name'		=> $first_name,
					'last_name'		 	=> $last_name,
					'purchase_on'	    => date('Y-m-d H:i:s'),
				)
			);
			
			//Send ean email 
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				$email_helper	= new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['name']			   = sanitize_text_field( $_POST['first_name'] ).' '.sanitize_text_field( $_POST['last_name'] );
				$emailData['mail_to']	  	   = $email;
				$emailData['invoice']	  	   = $transaction_id;
				$emailData['package_name']	   = $package_name;					
				$emailData['amount']			= $currency_sign.$total_amount;
				$emailData['status']			= esc_html__('Approved','docdirect');
				$emailData['method']			= esc_html__('Stripe( Credit Card )','docdirect');
				$emailData['date']			  	= date('Y-m-d H:i:s');
				$emailData['expiry']			= $expiry_date;
				$emailData['address']		    = $useraddress;
				

				$email_helper->process_invoice_email($emailData);
			}
						
			
			$json['type']     = 'success';
			$json['message']  = esc_html__('Thank you! Your package has been updated.','docdirect');
			echo json_encode($json);
			die;
		}
		
		$json['type']     = 'error';
		$json['message']  = esc_html__('Some Error occur, please try again later.','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_complete_stripe_payment', 'docdirect_complete_stripe_payment');
	add_action('wp_ajax_nopriv_docdirect_complete_stripe_payment', 'docdirect_complete_stripe_payment');
}

/**
 * Update User Password
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_process_acount' ) ) {
	function docdirect_process_acount() {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	=  array();
		$action	= sanitize_text_field( $_POST['process'] );
		$old_password	= sanitize_text_field( $_POST['old_password'] );
		$confirm_password	= sanitize_text_field( $_POST['confirm_password'] );
		$message	= sanitize_text_field( $_POST['message'] );
		
		$user = wp_get_current_user(); //trace($user);
		
		$do_check = check_ajax_referer( 'docdirect_deleteme_nounce', 'account-process', false );
		if( $do_check == false ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('No kiddies please!','docdirect');	
			echo json_encode($json);
			die;
		}
		
		//Account Activation
		if( isset( $action ) && $action === 'activateme' ){
			update_user_meta( $user->data->ID, 'profile_status', 'active' );
			$json['type']		=  'success';	
			$json['message']		= esc_html__('Account activated..','docdirect');
			echo json_encode($json);
			die;
		} 
		
		//Account de-activation			
		
		if ( empty( $message ) ) {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Please add some description','docdirect');	
			echo json_encode($json);
			exit;
		}
		
		if ( empty($old_password ) || empty( $confirm_password ) ) {
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Please add your password and confirm password.','docdirect');	
			echo json_encode($json);
			exit;
		}

    	$is_password = wp_check_password( sanitize_text_field( $old_password ), $user->user_pass, $user->data->ID );
		
		if( $is_password ){
			if ( $old_password == $confirm_password ) {
				if( isset( $action ) && $action === 'deleteme' ){
					wp_delete_user( $user->data->ID );
					
					if( class_exists( 'DocDirectProcessEmail' ) ) {
						$email_helper	= new DocDirectProcessEmail();
						
						$emailData	= array();
						$emailData['user_identity']	=  $user->data->ID;
						$emailData['reason']		=  esc_attr( $message );
						$emailData['email']	   		=  $user->data->user_email;;
						$email_helper->delete_user_account($emailData);

					} else{
						docdirect_wp_user_delete_notification($user->data->ID,$message); //email to admin
					}
					
					
					$json['type']		=  'success';	
					$json['message']		= esc_html__('Account deleted.','docdirect');
				} elseif( isset( $action ) && $action === 'deactivateme' ){
					update_user_meta( $user->data->ID, 'profile_status', 'de-active' );
					update_user_meta( $user->data->ID, 'deactivate_reason', $message );
					
					$json['type']		=  'success';	
					$json['message']		= esc_html__('Account de-activated..','docdirect');
				} 
					
			} else {
				$json['type']		=  'error';	
				$json['message']		= esc_html__('The passwords you entered do not match.', 'docdirect');
			}
		} else{
			$json['type']		=  'error';	
			$json['message']		= esc_html__('Password doesn\'t match.', 'docdirect');
		}
		
		echo json_encode($json);
		exit;
	}
	add_action('wp_ajax_docdirect_process_acount', 'docdirect_process_acount');
	add_action('wp_ajax_nopriv_docdirect_process_acount', 'docdirect_process_acount');
}

/**
 * Update User Password
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_add_user_roles' ) ) {
	function docdirect_add_user_roles() {
		
		$visitor = add_role('visitor', esc_html__('Visitor','docdirect'));
		$professional = add_role('professional', esc_html__('Professional','docdirect'));
	}
	add_action( 'admin_init', 'docdirect_add_user_roles' );
}

/**
 * Check if user is active user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_user_profile_status_message' ) ) {
	function docdirect_user_profile_status_message() {
		?>
        <div class="sticky-queue bottom-right">
            <div class="sticky border-top-right important" id="s939311313">
                <span class="sticky-close"></span><p class="sticky-note">
                <?php
					wp_kses( _e( 'Your account is de-active, please activate your account.<br/> Note: Your account will not be shown publicly until you activate your account. <br/> To activate please go to Security Settings', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	))?>
                </p>
            </div>
        </div>
		<?php 
	}
}

/**
 * Check if user is verified user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_is_user_verified_message' ) ) {
	function docdirect_is_user_verified_message() {
		?>
        <div class="sticky-queue bottom-right">
            <div class="sticky border-top-right important" id="s939311313">
                <span class="sticky-close"></span><p class="sticky-note">
                <?php
					wp_kses( _e( 'You are not a verified user! If you didn\'t get verification email, Please contact to administrator to get verified.<br/>Note: Your account will not be shown publicly until your account will get verified.', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	))?>
																	</p>
            </div>
        </div>
		<?php 
	}
}

/**
 * Order_Status
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_prepare_order_status' ) ) {
	function docdirect_prepare_order_status($type="array",$index='cancelled'){
		$status	= array(
					'approved'	=> esc_html__('Complete', 'docdirect'),
					'pending'	=> esc_html__('Pending', 'docdirect'),
					'cancelled'	=> esc_html__('Rejected', 'docdirect'),
				);
		
		if( $type === 'array' ){
			return $status;
		}else{
			if( isset( $status[$index] ) ){
				return  $status[$index];
			} else{
				return '';
			}
			
		}
	}
}


/**
 * Package Type
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_prepare_payment_type' ) ) {
	function docdirect_prepare_payment_type($type="array",$index='bank'){
		$packages	= apply_filters('docdirect_payment_types',array(
							'bank'	=> esc_html__('Bank Transfer', 'docdirect'),
							'paypal'	=> esc_html__('Paypal', 'docdirect'),
							'stripe'	=> esc_html__('Credit Card (Stripe)', 'docdirect'),
							'authorize'	=> esc_html__('Authorize.Net', 'docdirect'),
							'local'	=> esc_html__('Payment On Arrival', 'docdirect'),
						) );
		
		if( $type === 'array' ){
			return $packages;
		}else{
			if( isset( $packages[$index] ) ){
				return  $packages[$index];
			} else{
				return '';
			}
			
		}
	}
}

/**
 * @Make Review
 * @return 
 */
if ( ! function_exists( 'docdirect_make_review' ) ) {
	function docdirect_make_review() {
		global $current_user, $wp_roles,$userdata,$post;
		
		
		$user_to	  = sanitize_text_field( $_POST['user_to'] );
		$is_verified  = get_user_meta($current_user->ID, 'verify_user', true);
			
				
		$dir_review_status	= 'pending';
		if (function_exists('fw_get_db_settings_option')) {
            $dir_review_status = fw_get_db_settings_option('dir_review_status', $default_value = null);
        }
			
		if( apply_filters('docdirect_is_user_logged_in','check_user') === false ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Please login first to add review.','docdirect');	
			echo json_encode($json);
			die;
		}
		
		if( isset( $is_verified ) && $is_verified != 'on' ) {
			$json['type']	= 'error';
			$json['message']	= esc_html__('You are not a verified user, You can\'t make a review. Please contact to administrator.','docdirect');	
			echo json_encode($json);
			die;
		}

		
		$user_reviews = array(
			'posts_per_page'   => "-1",
			'post_type'		   => 'docdirectreviews',
			'post_status'	   => 'any',
			'author' 		   => $current_user->ID,
			'meta_key'		   => 'user_to',
			'meta_value'	   => $user_to,
			'meta_compare'	   => "=",
			'orderby'		   => 'meta_value',
			'order'			   => 'ASC',
		);

		$reviews_query = new WP_Query($user_reviews);
		$reviews_count = $reviews_query->post_count;
		if( isset( $reviews_count ) && $reviews_count > 0 ){
			$json['type']		= 'error';
			$json['message']	= esc_html__('You have already submit a review.', 'docdirect');
			echo json_encode($json);
			die();
		}
		
		$db_directory_type	 = get_user_meta( $user_to, 'directory_type', true);
			
		if( !empty( $_POST['user_subject'] ) 
			&& !empty( $_POST['user_description'] ) 
			&& !empty( $_POST['user_rating'] )
			&& !empty( $_POST['user_to'] )
		) {
		
			$user_subject	   = sanitize_text_field( $_POST['user_subject'] );
			$user_description  = sanitize_text_field( $_POST['user_description'] );
			$user_rating	   = sanitize_text_field( $_POST['user_rating'] );
			$user_from	       = $current_user->ID;
			$user_to	   	   = sanitize_text_field( $_POST['user_to'] );
			$directory_type	   = $db_directory_type;
			
			$review_post = array(
				'post_title'  => $user_subject,
				'post_status' => $dir_review_status,
				'post_content'=> $user_description,
				'post_author' => $user_from,
				'post_type'   => 'docdirectreviews',
				'post_date'   => current_time('Y-m-d H:i:s')
			);
			
			$post_id = wp_insert_post( $review_post );
	
			$review_meta = array(
				'user_rating' 	 	 => $user_rating,
				'user_from' 	     => $user_from,
				'user_to'   		 => $user_to,
				'directory_type'  	 => $directory_type,
				'review_date'   	 => current_time('Y-m-d H:i:s'),
			);
			
			//Update post meta
			foreach( $review_meta as $key => $value ){
				update_post_meta($post_id,$key,$value);
			}
			
			$new_values = $review_meta;
			
			if (isset($post_id) && !empty($post_id)) {
				fw_set_db_post_option($post_id, null, $new_values);
			}
			
			$json['type']	   = 'success';
			

			if( isset( $dir_review_status ) && $dir_review_status == 'publish' ) {
				$json['message']	= esc_html__('Your review published successfully.','docdirect');
				$json['html']	   = 'refresh';
			} else{
				$json['message']	= esc_html__('Your review is submitted successfully, it will be published after approval.','docdirect');
				$json['html']	   = '';
			}
			
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				$user_from_data	= get_userdata($user_from);
				$user_to_data	  = get_userdata($user_to);
				$email_helper	  = new DocDirectProcessEmail();
				
				$emailData	= array();
				
				//User to data
				$emailData['email_to']	    = $user_to_data->user_email;
				$emailData['link_to']	= get_author_posts_url($user_to_data->ID);
				if( !empty( $user_to_data->display_name ) ) {
					$emailData['username_to']	   = $user_to_data->display_name;
				} elseif( !empty( $user_to_data->first_name ) || $user_to_data->last_name ) {
					$emailData['username_to']	   = $user_to_data->first_name.' '.$user_to_data->last_name;
				}
				
				//User from data
				if( !empty( $user_from_data->display_name ) ) {
					$emailData['username_from']	   = $user_from_data->display_name;
				} elseif( !empty( $user_from_data->first_name ) || $user_from_data->last_name ) {
					$emailData['username_from']	   = $user_from_data->first_name.' '.$user_from_data->last_name;
				}

				$emailData['link_from']	= get_author_posts_url($user_from_data->ID);
				
				//General
				$emailData['rating']	        = $user_rating;
				$emailData['reason']	        = $user_subject;
				
				$email_helper->process_rating_email($emailData);
			}
			
			echo json_encode($json);
			die;
			
		} else{
			$json['type']		= 'error';
			$json['message']	 = esc_html__('Please fill all the fields.','docdirect');	
			echo json_encode($json);
			die;
		}
		
	}
	add_action('wp_ajax_docdirect_make_review','docdirect_make_review');
	add_action( 'wp_ajax_nopriv_docdirect_make_review', 'docdirect_make_review' );
}

/**
 * @Contact Doctor
 * @return 
 */
if (!function_exists('docdirect_submit_me')) {
	function docdirect_submit_me(){
		global $current_user;
		
		$json	= array();
		
		$do_check = check_ajax_referer( 'docdirect_contact_me', 'user_security', false );
		if( $do_check == false ){
			//Do something
		}
		
		$bloginfo 		   = get_bloginfo();
		$email_subject 	=  "(" . $bloginfo . ") Contact Form Received";
		$success_message 	= esc_html__('Message Sent.','docdirect');
		$failure_message 	= esc_html__('Message Fail.','docdirect');
		
		$recipient 	=  sanitize_text_field( $_POST['email_to'] );
		
		if( empty( $_POST['email_to'] )){
			$recipient = get_option( 'admin_email' ,'Aamirshahzad2009@live.com' );
		}
		
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the form fields and remove whitespace.
            
			if( empty( $_POST['username'] )
				|| empty( $_POST['useremail'] ) 
				|| empty( $_POST['userphone']  ) 
				|| empty( $_POST['usersubject']  ) 
				|| empty( $_POST['user_description']  )
			){
				$json['type']	= 'error';
				$json['message']	= esc_html__('Please fill all fields.','docdirect');	
				echo json_encode($json);
				die;
			}
			
			if( ! is_email($_POST['useremail']) ){
				$json['type']	= 'error';
				$json['message']	= esc_html__('Email address is not valid.','docdirect');	
				echo json_encode($json);
				die;
			}
			
			$name	    = sanitize_text_field( $_POST['username'] );
			$email	  	= sanitize_text_field( $_POST['useremail'] );
			$subject	= sanitize_text_field( $_POST['usersubject'] );
			$phone	    = sanitize_text_field( $_POST['userphone'] );
			$message	= sanitize_text_field( $_POST['user_description'] );
			
            // Set the recipient email address.
            // FIXME: Update this to your desired email address.
            // Set the email subject.
            
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				$email_helper	= new DocDirectProcessEmail();
				$emailData	   = array();
				$emailData['name']	  	       = $name;
				$emailData['email']			   = $email;
				$emailData['email_subject']	   = $email_subject;
				$emailData['subject']	  	    = $subject;
				$emailData['phone']	 		    = $phone;					
				$emailData['message']			= $message;
				$emailData['email_to']			= $recipient;
				
				$email_helper->process_contact_user_email( $emailData );
			}
			
            // Send the email.
            $json['type']    = "success";
			$json['message'] = esc_attr($success_message);
			echo json_encode( $json );
			die();
        } else {
            echo 
			$json['type']    = "error";
			$json['message'] = esc_attr($failure_message);
			echo json_encode( $json );
            die();
        }
		
	}
	
	add_action('wp_ajax_docdirect_submit_me','docdirect_submit_me');
	add_action( 'wp_ajax_nopriv_docdirect_submit_me', 'docdirect_submit_me' );
}

/**
 * @Submit Claim
 * @return 
 */
if (!function_exists('docdirect_submit_claim')) {
	function docdirect_submit_claim(){
		global $current_user;
		
		$json	= array();
		
		$do_check = check_ajax_referer( 'docdirect_claim', 'security', false);
		if( $do_check == false ){
			//Do something
		}
		
		$user_to	= !empty( $_POST['user_to'] ) ? intval( $_POST['user_to'] ) : '';
		$user_from  = $current_user->ID;
		
		$subject	= sanitize_text_field( $_POST['subject'] );
		$report	    = sanitize_text_field( $_POST['report'] );
		
		if( empty( $subject ) 
			||
			empty( $report )  
			||
			empty( $user_to )  
			||
			empty( $user_from )  
		) {
			$json['type']	   = 'error';
			$json['message']	= esc_html__('Please fill all the fields.','docdirect');
			echo json_encode($json);
			die;
		}
		
		
		$claim_post = array(
			'post_title'  => $subject,
			'post_status' => 'publish',
			'post_content'=> $report,
			'post_author' => $user_from,
			'post_type'   => 'doc_claims',
			'post_date'   => current_time('Y-m-d H:i:s')
		);
		
		$post_id = wp_insert_post( $claim_post );

		$claim_meta = array(
			'subject' 	  => $user_rating,
			'user_from'   => $user_from,
			'user_to'     => $user_to,
			'report'  	  => $report,
		);
		
		//Update post meta
		foreach( $claim_meta as $key => $value ){
			update_post_meta($post_id,$key,$value);
		}
		
		$new_values = $claim_meta;
		
		if (isset($post_id) && !empty($post_id)) {
			fw_set_db_post_option($post_id, null, $new_values);
		}
		
		if( class_exists( 'DocDirectProcessEmail' ) ) {
			$email_helper	= new DocDirectProcessEmail();
			$emailData	   = array();
			
			$emailData['claimed_user_name']	= docdirect_get_username($user_to);
			$emailData['claimed_by_name']	= docdirect_get_username($user_from);
			$emailData['claimed_user_link']	= get_author_posts_url($user_to);
			$emailData['claimed_by_link']	= get_author_posts_url($user_from);
			$emailData['message']			= $report;

			$email_helper->process_claim_admin_email( $emailData );
		}
		
		
		$json['type']	   = 'success';
		$json['message']	= esc_html__('Your report received successfully.','docdirect');
		echo json_encode($json);
		die;
	}
	
	add_action('wp_ajax_docdirect_submit_claim','docdirect_submit_claim');
	add_action( 'wp_ajax_nopriv_docdirect_submit_claim', 'docdirect_submit_claim' );
}


/**
 * @Locate Me Snipt
 * @return 
 */
if (!function_exists('docdirect_locateme_snipt')) {
	function docdirect_locateme_snipt(){
		if (function_exists('fw_get_db_settings_option')) {
			$dir_geo = fw_get_db_settings_option('dir_geo');
			$dir_radius = fw_get_db_settings_option('dir_radius');
			$dir_default_radius = fw_get_db_settings_option('dir_default_radius');
			$dir_max_radius = fw_get_db_settings_option('dir_max_radius');
		} else{
			$dir_geo = '';
			$dir_radius = '';
			$dir_default_radius = 50;
			$dir_max_radius = 300;
		}
		
		$dir_default_radius 	=  !empty($dir_default_radius) ?  $dir_default_radius : 50;
		$dir_max_radius 	=  !empty($dir_max_radius) ?  $dir_max_radius : 300;
		
		$location	= '';
		if( isset( $_GET['geo_location'] ) && !empty( $_GET['geo_location'] ) ){
			$location	= $_GET['geo_location'];
		}
		
		$lat 	= !empty( $_GET['lat'] ) ? $_GET['lat'] : '';
		$long 	= !empty( $_GET['long'] ) ? $_GET['long'] : '';
		
		$distance	= $dir_default_radius;
		if( isset( $_GET['geo_distance'] ) && !empty( $_GET['geo_distance'] ) ){
			$distance	= $_GET['geo_distance'];
		}
		
		if (function_exists('fw_get_db_settings_option')) {
			$dir_distance_type = fw_get_db_settings_option('dir_distance_type');
		} else{
			$dir_distance_type = 'mi';
		}
		
		$distance_title = esc_html__('( Miles )','docdirect');
		if( $dir_distance_type === 'km' ) {
			$distance_title = esc_html__('( Kilometers )','docdirect');
		}
	?>
    	<div class="locate-me-wrap">
            <div id="location-pickr-map" class="elm-display-none"></div>
            <input type="text"  autocomplete="on" id="location-address" value="<?php echo esc_attr( $location );?>" name="geo_location" placeholder="<?php esc_html_e('Geo location','docdirect');?>" class="form-control">
            <?php if( isset( $dir_geo ) && $dir_geo === 'enable' ){?>
            	<a href="javascript:;" class="geolocate"><img src="<?php echo get_template_directory_uri();?>/images/geoicon.svg" width="16" height="16" class="geo-locate-me" alt="<?php esc_html_e('Locate me!','docdirect');?>"></a>
            <?php }?>
            <?php if( isset( $dir_radius ) && $dir_radius === 'enable' ){?>
            <a href="javascript:;" class="geodistance"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
            <div class="geodistance_range elm-display-none">
                <div class="distance-ml"><?php esc_html_e('Distance in','docdirect');?>&nbsp;<?php echo esc_attr( $distance_title );?><span><?php echo esc_js( $distance );?></span></div>
                <input type="hidden" name="geo_distance" value="<?php echo esc_js( $distance );?>" class="geo_distance" />
                <input type="hidden" id="location-latitude" name="lat" value="<?php echo esc_attr( $lat );?>" class="" />
                <input type="hidden" id="location-longitude" name="long" value="<?php echo esc_attr( $long );?>" class="" />
                <div class="geo_distance" id="geo_distance"></div>
            </div>
            <?php }?>
        </div>
        <?php if( isset( $dir_radius ) && $dir_radius === 'enable' ){?>
		<script>
			jQuery(document).ready(function(e) {
				jQuery( "#geo_distance" ).slider({
				   range: "min",
				   min:1,
				   max:<?php echo esc_js($dir_max_radius);?>,
				   value:<?php echo esc_js( $distance );?>,
				   animate:"slow",
				   orientation: "horizontal",
				   slide: function( event, ui ) {
					  jQuery( ".distance-ml span" ).html( ui.value );
					  jQuery( ".geo_distance" ).val( ui.value );
				   }	
				});
			});
		</script>
        <?php }?>
    <?php
	}
}


/**
 * @Update add to favorites
 * @return 
 */
if (!function_exists('docdirect_update_wishlist')) {
	function docdirect_update_wishlist(){
		global $current_user;
		$wishlist	= array();
		$wishlist    = get_user_meta($current_user->ID,'wishlist', true);
		$wishlist    = !empty($wishlist) && is_array( $wishlist ) ? $wishlist : array();
		$wl_id		= sanitize_text_field( $_POST['wl_id'] );
		
		if( !empty( $wl_id ) ) {
			$wishlist[]	= $wl_id;
			$wishlist = array_unique($wishlist);
			update_user_meta($current_user->ID,'wishlist',$wishlist);
			
			$json	= array();
			$json['type']	= 'success';
			$json['message']	= esc_html__('Successfully! added to your favorites','docdirect');
			echo json_encode($json);
			die();
		}
		
		$json	= array();
		$json['type']	= 'error';
		$json['message']	= esc_html__('Oops! something is going wrong.','docdirect');
		echo json_encode($json);
		die();
	}
	add_action('wp_ajax_docdirect_update_wishlist','docdirect_update_wishlist');
	add_action( 'wp_ajax_nopriv_docdirect_update_wishlist', 'docdirect_update_wishlist' );
	
}

/**
 * @Update add to favorites
 * @return 
 */
if (!function_exists('docdirect_remove_wishlist')) {
	function docdirect_remove_wishlist(){
		global $current_user;
		$wishlist	= array();
		$wishlist    = get_user_meta($current_user->ID,'wishlist', true);
		$wishlist    = !empty($wishlist) && is_array( $wishlist ) ? $wishlist : array();
		
		$wl_id	= array();
		$wl_id[]  = sanitize_text_field( $_POST['wl_id'] );
		$posted_id	= sanitize_text_field( $_POST['wl_id'] );
		
		if( !empty( $posted_id ) ) {
			$wishlist = array_diff( $wishlist , $wl_id );	
			update_user_meta($current_user->ID,'wishlist',$wishlist);
			
			$json	= array();
			$json['type']	= 'success';
			$json['message']	= esc_html__('Successfully! removed from your wishlist','docdirect');
			echo json_encode($json);
			die();
		}
		
		$json	= array();
		$json['type']	= 'error';
		$json['message']	= esc_html__('Oops! something is going wrong.','docdirect');
		echo json_encode($json);
		die();
	}
	add_action('wp_ajax_docdirect_remove_wishlist','docdirect_remove_wishlist');
	add_action( 'wp_ajax_nopriv_docdirect_remove_wishlist', 'docdirect_remove_wishlist' );
	
}

/**
 * @Get featuired tag
 * @return 
 */
if (!function_exists('docdirect_get_featured_tag')) {
	function docdirect_get_featured_tag($echo=false,$view='v1'){
		global $current_user;
		ob_start();
		if( isset( $view ) && $view === 'v2' ){
			?>
			<a class="doc-featuredicon" href="javascript:;"><i class="fa fa-bolt"></i><?php esc_html_e('featured','docdirect');?></a>
			<?php
		} else{
			?>
			<span class="tg-featuredtags">
				<a class="tg-featured" href="javascript:;"><?php esc_html_e('featured','docdirect');?></a>
			</span>
			<?php
		}
		if( $echo == true ){
			echo ob_get_clean();
		} else{
			return ob_get_clean();
		}
	}
}

/**
 * @Get verified tag
 * @return 
 */
if (!function_exists('docdirect_get_verified_tag')) {
	function docdirect_get_verified_tag($echo=false, $user_id = '',$view_type='svg',$view='v1'){
		global $current_user;
		
		if( !empty( $user_id ) ) {
			$featured_date  = get_user_meta($user_id, 'verify_user', true);
			if( isset( $featured_date ) && $featured_date === 'on' ) {
				ob_start();
				if( isset( $view ) && $view === 'v2' ){
					?>
                    	<a class="doc-featuredicon doc-verfiedicon" href="javascript:;"><i class="fa fa-shield"></i><?php esc_html_e('Verified','docdirect');?></a>
                    <?php
				} else{
					if( isset( $view_type ) && $view_type === 'simple' ){
					?>
						<li class="tg-varified"><a href="javascript:;"><i class="fa fa-shield"></i><span><?php esc_html_e('Verified','docdirect');?></span></a></li>
					
					<?php
					} else{?>
						<span class="user-verified svg-verfied">
							<i class="fa fa-shield" aria-hidden="true"></i><?php esc_html_e('Verified','docdirect');?>
						</span> 
					<?php }
				}
				
				if( $echo == true ){
					echo ob_get_clean();
				} else{
					return ob_get_clean();
				}
			}
		}
	}
}

/**
 * @User column data
 * @return 
 */
if ( !function_exists('docdirect_user_manage_user_column_row') )  {
	add_filter('manage_users_custom_column', 'docdirect_user_manage_user_column_row', 10, 3);
	
	function docdirect_user_manage_user_column_row($val, $column_name, $user_id) {
		$user = get_userdata($user_id);
	
		$user_type	= esc_html__('Visitor/Patient','docdirect');
		if( isset( $user->directory_type ) && !empty( $user->directory_type ) ){
			$user_type	= get_the_title( $user->directory_type );
		}
		
		switch ($column_name) {
			case 'status' :
				$status = get_user_meta($user_id, 'verify_user', true);
				$val = '<span style="color:red;">' . esc_html__('Not Verified', 'docdirect') . '</span>';
				if (isset( $status ) && $status === 'on') {
					$val = '<span style="color:green;">' . esc_html__('Verified', 'docdirect') . '</span>';
				}
				return $val;
				break;
			case 'type' :
					return $user_type;
					break;
			default:
		}
	}
}

/**
 * @User Row action
 * @return 
 */
if ( !function_exists('docdirect_user_user_table_action_links') )  {
	add_filter('user_row_actions', 'docdirect_user_user_table_action_links', 10, 2);
	
	function docdirect_user_user_table_action_links($actions, $user) {
		$is_approved = get_user_meta($user->ID, 'verify_user', true);
		
		$actions['docdirect_status'] = "<a style='color:" . ((isset( $is_approved ) && $is_approved === 'off') ? 'green' : 'red') . "' href='" . esc_url(admin_url("users.php?action=docdirect_change_status&users=" . $user->ID . "&nonce=" . wp_create_nonce('docdirect_change_status_' . $user->ID))) . "'>" . ((isset( $is_approved ) && $is_approved === 'off') ? esc_html__('Approve', 'docdirect') : esc_html__('Unapprove', 'docdirect')) . "</a>";
		return $actions;
	}
}

/**
 * @Admmin notices
 * @return 
 */
if ( !function_exists('docdirect_user_change_status_notices') )  {
	add_action('admin_notices', 'docdirect_user_change_status_notices');
	function docdirect_user_change_status_notices() {
		global $pagenow;
		if ($pagenow == 'users.php') {
			if (isset($_REQUEST['updated'])) {
				$message = $_REQUEST['updated'];
				if ($message == 'docdirect_false') {
					print '<div class="updated notice error is-dismissible"><p>' . esc_html__('Something wrong. Please try again.', 'docdirect') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'docdirect') . '</span></button></div>';
				}
				if ($message == 'approved') {
					print '<div class="updated notice is-dismissible"><p>' . esc_html__('User approved.', 'docdirect') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'docdirect') . '</span></button></div>';
				}
				if ($message == 'unapproved') {
					print '<div class="updated notice is-dismissible"><p>' . esc_html__('User unapproved.', 'docdirect') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'docdirect') . '</span></button></div>';
				}
			}
		}
	}
}

/**
 * @Get Matched users
 * @return
 */
if (!function_exists('docdirect_get_team_members')) {

    function docdirect_get_team_members() {
        $s = sanitize_text_field($_POST['email']);
        
		$json = array();
        $user_json = array();
        $output = '';
        $meta_query_args = array();
		
		if( !is_email($s) ){
			$json['type'] = 'error';
            $json['msg'] = esc_html__('Please add valid email ID', 'docdirect');
            echo json_encode($json);
            die;
		}

        $order = 'DESC';
        $orderby = 'ID';

        $query_args = array(
            'role' => 'professional',
            'order' => $order,
            'orderby' => $orderby,
        );
		
		$search_args	= array(
								'search'         => trim( $s ),
								'search_columns' => array(
									'user_email',
								)
							);
		
		$query_args	= array_merge( $query_args, $search_args );
		$users_query = new WP_User_Query($query_args);

        if (!empty($users_query->results)) {
            $counter = 0;

            foreach ($users_query->results as $user) {
                $is_claimed = '';
                $username = docdirect_get_username($user->ID);
				$user_email = $user->user_email;
				$avatar = apply_filters(
                                    'docdirect_get_user_avatar_filter',
                                     docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user->ID),
                                     array('width'=>150,'height'=>150) //size width,height
                                );
								
                $user_json[$user->ID]['id'] = $user->ID;
                $user_json[$user->ID]['username'] = $username;
				$user_json[$user->ID]['user_email'] = $user_email;
				$user_json[$user->ID]['photo'] = $avatar;
                $user_json[$user->ID]['user_link'] = get_author_posts_url($user->ID);

                $counter++;
            }


            $json['type'] = 'success';
            $json['user_json'] = $user_json;
            $json['msg'] = esc_html__('Users found', 'docdirect');
            echo json_encode($json);
            die;
        } else {
            $json['type'] = 'error';
            $json['user_json'] = $user_json;
            $json['msg'] = esc_html__('No user found', 'docdirect');
            echo json_encode($json);
            die;
        }
    }

    add_action('wp_ajax_docdirect_get_team_members', 'docdirect_get_team_members');
    add_action('wp_ajax_nopriv_docdirect_get_team_members', 'docdirect_get_team_members');
}


/**
* @update team member
* @return {}
*/
if (!function_exists('docdirect_update_team_members')) {

    function docdirect_update_team_members() {
		global $current_user;
		
		$id = sanitize_text_field($_POST['id']);
		
		$teams	= array();
		$teams    = get_user_meta($current_user->ID,'teams_data', true);
		$teams    = !empty($teams) && is_array( $teams ) ? $teams : array();
		
		if( !empty( $id ) ) {
			$team_id	 = intval( $id );
			$teams[]	 = $team_id;
			$teams = array_unique($teams);
			update_user_meta($current_user->ID,'teams_data',$teams);
			
			$json	= array();
			$json['type']	= 'success';
			$json['message']	= esc_html__('Successfully! added to your team members','docdirect');
			echo json_encode($json);
			die();
		}
		
		$json	= array();
		$json['type']	= 'error';
		$json['message']	= esc_html__('Oops! something is going wrong.','docdirect');
		echo json_encode($json);
		die();
 
	}
	add_action('wp_ajax_docdirect_update_team_members', 'docdirect_update_team_members');
    add_action('wp_ajax_nopriv_docdirect_update_team_members', 'docdirect_update_team_members');
}

/**
 * @delete team member
 * @return 
 */
if (!function_exists('docdirect_remove_team_member')) {
	function docdirect_remove_team_member(){
		global $current_user;
		$teams	= array();
		$teams    = get_user_meta($current_user->ID,'teams_data', true);
		$teams    = !empty($teams) && is_array( $teams ) ? $teams : array();
		
		if( !empty( $_POST['id'] ) ) {
			$team_id	= array();
			$team_id[]  = intval( $_POST['id']);
			$teams = array_diff( $teams , $team_id );	
			update_user_meta($current_user->ID,'teams_data',$teams);
			
			$json	= array();
			$json['type']	= 'success';
			$json['message']	= esc_html__('Successfully! removed from your teams','docdirect');
			echo json_encode($json);
			die();
		}
		
		$json	= array();
		$json['type']	= 'error';
		$json['message']	= esc_html__('Oops! something is going wrong.','docdirect');
		echo json_encode($json);
		die();
	}
	add_action('wp_ajax_docdirect_remove_team_member','docdirect_remove_team_member');
	add_action( 'wp_ajax_nopriv_docdirect_remove_team_member', 'docdirect_remove_team_member' );
	
}

/**
* @update team member
* @return {}
*/
if (!function_exists('docdirect_update_team_members')) {

    function docdirect_update_team_members() {
		global $current_user;
		
		$id = sanitize_text_field($_POST['id']);
		
		$teams	= array();
		$teams    = get_user_meta($current_user->ID,'teams_data', true);
		$teams    = !empty($teams) && is_array( $teams ) ? $teams : array();
		if( !empty( $id ) ) {
			$team_id	 = intval( $id );
			$teams[]	 = $team_id;
			$teams = array_unique($teams);
			update_user_meta($current_user->ID,'teams_data',$teams);
			
			$json	= array();
			$json['type']	= 'success';
			$json['message']	= esc_html__('Successfully! added to your team members','docdirect');
			echo json_encode($json);
			die();
		}
		
		$json	= array();
		$json['type']	= 'error';
		$json['message']	= esc_html__('Oops! something is going wrong.','docdirect');
		echo json_encode($json);
		die();
 
	}
	add_action('wp_ajax_docdirect_update_team_members', 'docdirect_update_team_members');
    add_action('wp_ajax_nopriv_docdirect_update_team_members', 'docdirect_update_team_members');
}

/**
 * @invite users
 * @return 
 */
if (!function_exists('docdirect_invite_users')) {
	function docdirect_invite_users(){
		global $current_user;
		
		$email = sanitize_text_field($_POST['email']);
		$message = sanitize_text_field($_POST['message']);
        
		$json = array();
		
		if( empty( $email ) ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('Please add email ID', 'docdirect');
            echo json_encode($json);
            die;
		}else if( !is_email( $email ) ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('Please add valid email ID', 'docdirect');
            echo json_encode($json);
            die;
		}else if( empty( $message ) ){
			$json['type'] = 'error';
            $json['message']  = esc_html__('Please add message', 'docdirect');
            echo json_encode($message);
            die;
		}
		
		//Send ean email 
		if( class_exists( 'DocDirectProcessEmail' ) ) {
			$email_helper	= new DocDirectProcessEmail();
			$emailData	    = array();
			
			$emailData['email_to']	= $email;
			$emailData['username']	= docdirect_get_username($current_user->ID);
			$emailData['message']	= $message;
			$emailData['link']		= esc_url(home_url('/')).'?invitation=signup';
			
			$email_helper->process_invitation_email( $emailData );
			$json['type']    = 'success';
            $json['message'] = esc_html__('Email has sent.', 'docdirect');
		} else {
			$json['message'] = esc_html__('Some error occur please try again later.', 'docdirect');
            $json['type'] 	 = 'error';
		}
		
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_invite_users','docdirect_invite_users');
	add_action( 'wp_ajax_nopriv_docdirect_invite_users', 'docdirect_invite_users' );
}


/*------------------------------------------------
 * Lost Password
 * @Lost Password Form
 * @return 
 */
if (!function_exists('docdirect_user_lostpassword')) {

    function docdirect_user_lostpassword() {
        ?>
        <div class="modal fade tg-user-lp-model" tabindex="-1" role="dialog">
            <div class="tg-modal-content">
                <div class="panel-lostps">
                    <form class="tg-form-modal tg-form-signup do-forgot-form">
                        <fieldset>
                            <div class="form-group">
                                <h1><?php esc_html_e('Forgot Password', 'docdirect'); ?></h1>
                                <p><?php esc_html_e('Forgot your password? Enter the email address for your account to reset your password, otherwise you can', 'docdirect'); ?><a href="javascript:;" class="try-again-lp">&nbsp;<?php esc_html_e('try again', 'docdirect'); ?></a></p>
                                <div class="forgot-fields">
                                    <div class="form-group">
                                        <input type="email" name="psemail" class="form-control psemail" placeholder="<?php esc_html_e('Email Address*', 'docdirect'); ?>">
                                        <input type="hidden" name="tg_pwd_nonce" value="<?php echo wp_create_nonce("tg_pwd_nonce"); ?>" />
                                    </div>
                                </div>
                                <button class="tg-btn tg-btn-lg  do-lp-button" type="button"><?php esc_html_e('Submit', 'docdirect'); ?></button>
                            </div>
                        </fieldset>
                    </form>    
                </div>
            </div>
        </div>
        <?php
    }

    add_shortcode('user_lostpassword', 'docdirect_user_lostpassword');
}

/**
 * @Lost Password action
 * @return 
 */
if (!function_exists('docdirect_ajax_lp')) {

    function docdirect_ajax_lp() {
        global $wpdb;
        $json = array();

        if (function_exists('fw_get_db_settings_option')) {
            $captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
        }

        $user_input = !empty($_POST['psemail']) ? esc_attr( $_POST['psemail'] ) : '';
		$pwd_nonce  = !empty($_POST['tg_pwd_nonce']) ? esc_attr( $_POST['tg_pwd_nonce'] ) : '';

        if (!wp_verify_nonce($pwd_nonce, "tg_pwd_nonce")) {
            $json['type'] = 'success';
            $json['message'] = esc_html__('No tricks please!', 'docdirect');
            echo json_encode($json);
            die;
        }

        if (empty($user_input)) {
            $json['type'] = 'success';
            $json['message'] = esc_html__('Please add email address.', 'docdirect');
            echo json_encode($json);
            die;
        } else if (!is_email($user_input)) {
            $json['type'] = "error";
            $json['message'] = esc_html__("Please add a valid email address.", 'docdirect');
            echo json_encode($json);
            die;
        }


        $user_data = get_user_by_email($user_input);
        if (empty($user_data) || $user_data->caps[administrator] == 1) {
            //the condition $user_data->caps[administrator] == 1 to prevent password change for admin users.
            //if you prefer to offer password change for admin users also, just delete that condition.
            $json['type'] = "error";
            $json['message'] = esc_html__("Invalid E-mail address!", 'docdirect');
            echo json_encode($json);
            die;
        }

        $user_id = $user_data->ID;
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

        if (empty($key)) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }

        $protocol = is_ssl() ? 'https' : 'http';
        $reset_link = esc_url(add_query_arg(array('action' => 'reset_pwd', 'key' => $key, 'login' => $user_login), home_url('/', $protocol)));

        if (class_exists('DocDirectProcessEmail')) {
            $email_helper = new DocDirectProcessEmail();

            //Get User Name with User ID
            $username = docdirect_get_username($user_id);
            $emailData = array();

            $emailData['username'] = $username;
            $emailData['email'] = $user_email;
            $emailData['link'] = $reset_link;
            $email_helper->process_lostpassword_email($emailData);
        }

        $json['type'] = "success";
        $json['message'] = esc_html__("A link has been sent, please check your email.", 'docdirect');
        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_docdirect_ajax_lp', 'docdirect_ajax_lp');
    add_action('wp_ajax_nopriv_docdirect_ajax_lp', 'docdirect_ajax_lp');
}

/**
 * @Reset Password Form
 * @return 
 */
if (!function_exists('docdirect_reset_password_form')) {

    function docdirect_reset_password_form() {
        global $wpdb;
        $captcha_settings = '';
        if (function_exists('fw_get_db_settings_option')) {
            $captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
        }

        if (!empty($_GET['key']) &&
			( isset($_GET['action']) && $_GET['action'] == "reset_pwd" ) &&
			(!empty($_GET['login']) )
        ) {
            $reset_key = $_GET['key'];
            $user_login = $_GET['login'];
            $reset_action = $_GET['action'];

            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

            if ($reset_key === $key) {
                $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

                $user_login = $user_data->user_login;
                $user_email = $user_data->user_email;

                if (!empty($user_data)) {
                    ob_start();
                    ?>
                    <div class="modal fade tg-user-reset-model" tabindex="-1" role="dialog">
                        <div class="tg-modal-content">
                            <div class="panel-lostps">
                                <form class="tg-form-modal tg-form-signup do-reset-form">
                                    <fieldset>
                                        <div class="form-group">
                                            <h1><?php esc_html_e('Reset Password', 'docdirect'); ?></h1>
                                            <p><?php echo wp_get_password_hint(); ?></p>
                                            <div class="forgot-fields">
                                                <div class="form-group">
                                                    <label for="pass1"><?php esc_html_e('New password', 'docdirect') ?></label>
                                                    <input type="password"  name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="pass2"><?php esc_html_e('Repeat new password', 'docdirect') ?></label>
                                                    <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />

                                                    <input type="hidden" name="tg_pwd_nonce" value="<?php echo wp_create_nonce("tg_pwd_nonce"); ?>" />
                                                </div>
                                            </div>
                                            <button class="tg-btn tg-btn-lg  do-reset-button" type="button"><?php esc_html_e('Submit', 'docdirect'); ?></button>

                                            <input type="hidden" name="key" value="<?php echo esc_attr($reset_key); ?>" />
                                            <input type="hidden" name="reset_action" value="<?php echo esc_attr($reset_action); ?>" />
                                            <input type="hidden" name="login" value="<?php echo esc_attr($user_login); ?>" />
                                        </div>
                                    </fieldset>
                                </form>    
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" class="open-reset-window" data-toggle="modal" data-target=".tg-user-reset-model"></a>
                    <?php
                    echo ob_get_clean();
                    print("<script>jQuery(document).ready(function ($) {setTimeout(function() {jQuery('.open-reset-window').trigger('click');},100);});</script>");
                }
            }
        }
    }

    add_action('docdirect_reset_password_form', 'docdirect_reset_password_form');
}

/**
 * @Reset Password action
 * @return 
 */
if (!function_exists('docdirect_ajax_reset_password')) {

    function docdirect_ajax_reset_password() {
        global $wpdb;
        $json = array();

        $captcha_settings = '';

        if (function_exists('fw_get_db_settings_option')) {
            $captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
        }
		
		$pwd_nonce	 = sanitize_text_field($_POST['tg_pwd_nonce']);
		$pass1	 = sanitize_text_field($_POST['pass1']);
		$pass2	 = sanitize_text_field($_POST['pass2']);
		$recaptcha_response	 = sanitize_text_field($_POST['g-recaptcha-response']);
		
        if (!wp_verify_nonce($pwd_nonce, "tg_pwd_nonce")) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('No trick please.', 'docdirect');
            echo json_encode($json);
            die;
        }


        if (isset($pass1)) {
            if ($pass1 != $pass2) {
                // Passwords don't match
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! password is not matched", 'docdirect');
                echo json_encode($json);
                die;
            }

            if (empty($pass1)) {
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! password should not be empty", 'docdirect');
                echo json_encode($json);
                die;
            }
        } else {
            $json['type'] = "error";
            $json['message'] = esc_html__("Oops! Invalid request", 'docdirect');
            echo json_encode($json);
            die;
        }

		$reset_key = sanitize_text_field( $_POST['key'] );
        $user_login = sanitize_text_field( $_POST['login'] );
		$reset_action = sanitize_text_field( $_POST['reset_action'] );
        
        if (!empty($reset_key) 
			&& ( isset($reset_action) && $reset_action == "reset_pwd" ) 
			&& (!empty($user_login) )
        ) {

            $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;

            if (!empty($reset_key) && !empty($user_data)) {
                $new_password = $pass1;

                wp_set_password($new_password, $user_data->ID);

                $json['redirect_url'] = home_url('/');
                $json['type'] = "success";
                $json['message'] = esc_html__("Congratulation! your password has been changed.", 'docdirect');
                echo json_encode($json);
                die;
            } else {
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! Invalid request", 'docdirect');
                echo json_encode($json);
                die;
            }
        }
    }

    add_action('wp_ajax_docdirect_ajax_reset_password', 'docdirect_ajax_reset_password');
    add_action('wp_ajax_nopriv_docdirect_ajax_reset_password', 'docdirect_ajax_reset_password');
}

/**
 * @get total artiles
 * @return array()
 */
if (!function_exists('docdirect_get_total_articles_by_user')) {

    function docdirect_get_total_articles_by_user($user_id = '') {
        if (empty($user_id)) {
            return 0;
        }

        $args = array('posts_per_page' => '-1',
            'post_type' => 'sp_articles',
            'orderby' => 'ID',
            'post_status' => array('publish','pending'),
            'author' => $user_id,
            'suppress_filters' => false
        );
        $query = new WP_Query($args);
        return $query->post_count;
    }

}

/**
 * Get Image Src
 * @return 
 */
if (!function_exists('docdirect_get_image_metadata')) {

    function docdirect_get_image_metadata($attachment_id) {

        if (!empty($attachment_id)) {
            $attachment = get_post($attachment_id);
            if (!empty($attachment)) {
                return array(
                    'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
                    'caption' => $attachment->post_excerpt,
                    'description' => $attachment->post_content,
                    'href' => get_permalink($attachment->ID),
                    'src' => $attachment->guid,
                    'title' => $attachment->post_title
                );
            } else {
                return array();
            }
        }
    }
}

/* @Post title HTML
 * $return {HTML}
 */
if (!function_exists('docdirect_get_post_title')) {

    function docdirect_get_post_title($post_id = '') {
        global $post;
        echo '<a href="' . get_the_permalink($post_id) . '">' . get_the_title($post_id) . '</a>';
    }
}

/* @Post author HTML
 * $return {HTML}
 */
if (!function_exists('docdirect_get_post_author')) {

    function docdirect_get_post_author($post_author_id = '', $linked = 'linked', $post_id = '') {
        global $post;
        echo '<a href="' . esc_url(get_author_posts_url($post_author_id)) . '"><i class="fa fa-user"></i><span>' . get_the_author() . '</span></a>';
    }

}
/* @Post date HTML
 * $return {HTML}
 */
if (!function_exists('docdirect_get_post_date')) {

    function docdirect_get_post_date($post_id = '') {
        global $post;
        echo '<time datetime="' . date_i18n('Y-m-d', strtotime(get_the_date('Y-m-d', $post_id))) . '"><i class="fa fa-calendar"></i><span>' . date_i18n(get_option('date_format'), strtotime(get_the_date('Y-m-d', $post_id))) . '</span></time>';
    }

}

/* @Get categories HTML
 * $return {HTML}
 */
if (!function_exists('docdirect_get_post_categories')) {

    function docdirect_get_post_categories($post_id = '', $classes = 'tg-tag', $categoty_type = 'category', $display_title = 'Categories', $enable_title = 'yes') {
        global $post;
        ob_start();
        $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
        $terms = wp_get_post_terms($post_id, $categoty_type, $args);
        if (!empty($terms)) {
            ?>
            <div class="tg-posttags">
                <?php if (isset($enable_title) && $enable_title === 'yes') { ?>
                    <span><?php echo esc_attr( $display_title ); ?></span>
                <?php } ?>
                <?php foreach ($terms as $key => $terms) { ?>
                    <a class="tg-tag <?php echo esc_attr($classes); ?>" href="<?php echo get_term_link($terms->term_id, $categoty_type); ?>"><?php echo esc_attr($terms->name); ?></a>
                <?php } ?>

            </div>
            <?php
        }

        echo ob_get_clean();
    }
}

/**
 * Check if user is active user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_add_post_question_form' ) ) {
	function docdirect_add_post_question_form() {
		$type_args = array('posts_per_page' => '-1', 
			   'post_type' => 'directory_type', 
			   'post_status' => 'publish',
			   'suppress_filters' => false
		);

		$cust_query = get_posts($type_args);
		?>
        <div class="modal fade tg-modalmanageteam AskQ" tabindex="-1">
			<div class="modal-dialog tg-modaldialog" role="document">
				<div class="modal-content tg-modalcontent tg-add-questions">
					<div class="tg-modalhead">
						<h2><?php esc_html_e('Ask a Question', 'docdirect'); ?></h2>
					</div>
					<div class="tg-modalbody">
						<form class="tg-themeform fw_ext_questions_form">
							<fieldset>
								<div class="form-group">
									<input type="text" class="question_title" name="question_title" placeholder="<?php esc_html_e('Question title', 'docdirect'); ?>">
								</div>
								<div class="form-group">
									<div class="doc-select">
										<select class="directory_type" name="category">
										  <option value=""><?php esc_html_e('Category','docdirect');?></option>
										  <?php
											if( isset( $cust_query ) && !empty( $cust_query ) ) {
												foreach ($cust_query as $key => $dir) {
													$title	= get_the_title($dir->ID);
												?>
												<option value="<?php echo esc_attr( $dir->ID );?>"><?php echo esc_attr( ucwords( $title ) );?></option>
												<?php	
											  }
											}
										 ?>	
										</select>
									</div>
								</div>
								<div class="form-group">
									<textarea class="question_details" name="question_description" placeholder="<?php esc_html_e('Ask question here', 'docdirect'); ?>"></textarea>
									<?php wp_nonce_field('docdirect_question_answers_nounce', 'docdirect_question_answers_nounce'); ?>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tg-modalfoot">
						<a href="javascript:;" class="tg-btn fw_ext_question_save_btn"  data-type="open" type="submit"><?php esc_html_e('Ask a Question', 'docdirect'); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php 
	}
}

/**
 * get latitude and longitude for search
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_get_location_lat_long' ) ) {
	function docdirect_get_location_lat_long() {
		if (function_exists('fw_get_db_settings_option')) {
			$dir_longitude = fw_get_db_settings_option('dir_longitude');
			$dir_latitude  = fw_get_db_settings_option('dir_latitude');
		} else{
			 $dir_longitude = '-0.1262362';
			 $dir_latitude 	= '51.5001524';
		}

		$current_latitude	= $dir_latitude;
		$current_longitude	= $dir_longitude;

		if( !empty( $_GET['lat'] ) && !empty( $_GET['long'] ) ){
			$current_latitude	= esc_attr( $_GET['lat'] );
			$current_longitude	= esc_attr( $_GET['long'] );
		} else{
			
			$args = array(
				'timeout'     => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
			);
			
			$address	 = !empty($_GET['geo_location']) ? $_GET['geo_location'] : '';

			$prepAddr	= str_replace(' ','+',$address);
			
			$url	    = 'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
			$response   = wp_remote_get( $url, $args );
			$geocode	= wp_remote_retrieve_body($response);

			$output	  = json_decode($geocode);

			if( isset( $output->results ) && !empty( $output->results ) ) {
				$Latitude	 = $output->results[0]->geometry->location->lat;
				$Longitude   = $output->results[0]->geometry->location->lng;
			}
			
			if( !empty( $Latitude ) && !empty( $Longitude ) ){
				$current_latitude	= $Latitude;
				$current_longitude	= $Longitude;
			} else{
				$current_latitude	= $dir_latitude;
				$current_longitude	= $dir_longitude;
			}
		}
		
		$location	= array();
		
		$location['lat']	= $current_latitude;
		$location['long']	= $current_longitude;
		
		return $location;
	}
}

/**
 * @get user category under which user get registered
 * @return {}
 */
if (!function_exists('docdirect_get_provider_category')) {

    function docdirect_get_provider_category($userid) {
        return get_user_meta($userid, 'directory_type', true);
    }
}

/**
 * @get settings
 * @return {}
 */
if (!function_exists('docdirect_profile_settings')) {
	function  docdirect_profile_settings(){
		$list	= array(
			'media'	 			=> 'media',
			'gallery'			=> 'gallery',
			'basics'	 		=> 'basics',
			'sub_categories'	=> 'sub_categories',
			'specialities'	 	=> 'specialities',
			'professional-statements'	 	=> 'professional-statements',
			'social'	 	=> 'social',
			'awards'	 	=> 'awards',
			'education'		=> 'education',
			'experience'	=> 'experience',
			'prices'	 	=> 'prices',
			'languages'	 	=> 'languages',
			'insurance'	 	=> 'insurance',
			'video'	 		=> 'video',
			'locations'	 	=> 'locations',
		);
		return $list;
	}
}

/**
 * @get dsiplay settings
 * @return {}
 */
if (!function_exists('docdirect_profile_display_settings')) {
	function  docdirect_profile_display_settings(){
		$list	= array(
			'map'				=> 'map',
			'ads-area'	 		=> 'ads-area',
			'about'	 			=> 'about',
			'languages'	 		=> 'languages',
			'specialities'	 	=> 'specialities',
			'insurance'			=> 'insurance',
			'experience'		=> 'experience',
			'education'	 		=> 'education',
			'awards'	 		=> 'awards',
			'articles'	 		=> 'articles',
			'prices'	 		=> 'prices',
			'teams'	 			=> 'teams',
			'video'	 			=> 'video',
			'gallery'	 		=> 'gallery',
			'more-info-tabs'	=> 'more-info-tabs',
		);
		return $list;
	}
}