<?php
/**
 *@ Hooks
 *@ return {}
 *@ Autor: Themographics
 */

/**
 * @Import User Menu
 * @return {}
 */
if (!function_exists('docdirect_import_users_menu')) {
	add_action('admin_menu', 'docdirect_import_users_menu');
	function  docdirect_import_users_menu(){
		add_submenu_page('edit.php?post_type=directory_type', 
							 esc_html__('Import User','docdirect_core'), 
							 esc_html__('Import User','docdirect_core'), 
							 'manage_options', 
							 'import_users',
							 'docdirect_import_users'
						 );
	}
}

/**
 * @Import Users
 * @return {}
 */
if (!function_exists('docdirect_submenu_order')) {
	//add_filter( 'custom_menu_order', 'docdirect_submenu_order' );
	function docdirect_submenu_order( $order ) {
		global $submenu;

		$arr = array();
		$arr[] = $submenu['edit.php?post_type=directory_type'][5];
		$arr[] = $submenu['edit.php?post_type=directory_type'][10];
		$arr[] = $submenu['edit.php?post_type=directory_type'][15];
		$arr[] = $submenu['edit.php?post_type=directory_type'][16];
		$arr[] = $submenu['edit.php?post_type=directory_type'][17];
		$arr[] = $submenu['edit.php?post_type=directory_type'][18];
		$arr[] = $submenu['edit.php?post_type=directory_type'][19];
		$arr[] = $submenu['edit.php?post_type=directory_type'][20];
		$arr[] = $submenu['edit.php?post_type=directory_type'][21];
		$arr[] = $submenu['edit.php?post_type=directory_type'][22];
		$arr[] = $submenu['edit.php?post_type=directory_type'][23];
		if( apply_filters('docdirect_get_theme_settings', 'payments') === 'custom' ){
			$arr[] = $submenu['edit.php?post_type=directory_type'][25];
			$arr[] = $submenu['edit.php?post_type=directory_type'][24];
		}
		$submenu['edit.php?post_type=directory_type'] = $arr;

		return $order;
	}
}
/**
 * @Import Users
 * @return {}
 */
if (!function_exists('docdirect_do_import_users')) {
	function  docdirect_do_import_users(){
		$import_user	= new DocDirect_Import_User();
		$import_user->docdirect_import_user();
		if (function_exists('docdirect_update_users')) {docdirect_update_users();}
		$json	= array();
		$json['type']	= 'success';	
		$json['message']	= esc_html__('User Imported Successfully','docdirect_core' );
		echo json_encode( $json );
		die;	
	}
	add_action('wp_ajax_docdirect_do_import_users', 'docdirect_do_import_users');	
}

/**
 * @Wp Login
 * @return 
 */
if ( ! function_exists( 'docdirect_ajax_login' ) ) {
	function docdirect_ajax_login(){
		$captcha_settings		= '';
		$user_array = array();
		$user_array['user_login'] 		= esc_sql($_POST['username']);
		$user_array['user_password'] 	= esc_sql($_POST['password']);
		
		if(function_exists('fw_get_db_settings_option')) {
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
		}
			
		//recaptcha check
		if( isset( $captcha_settings ) 
			&& $captcha_settings === 'enable' 
		) {
			if ( isset($_POST['g-recaptcha-response']) && !empty( $_POST['g-recaptcha-response'] ) ) {
				  $docReResult = docdirect_get_recaptcha_response($_POST['g-recaptcha-response']);
				  
				  if ( $docReResult == 1 ) {
					  $workdone = 1;
				  } else if ( $docReResult == 2 ) {
					  echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__('Some error occur, please try again later.','docdirect_core' )));
					   die;
				  }else{
					echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__('Wrong reCaptcha. Please verify first.','docdirect_core' )));
					die;
				  }
			
			} else{
				echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__( 'Please enter reCaptcha!','docdirect_core' )));
				die;
			}
		}
		
		if ( isset($_POST['rememberme'])){
			$remember  = esc_sql($_POST['rememberme']);
		} else {
			$remember  = '';
		}
	
		if($remember) {
			$user_array['remember'] = true;
		} else {
			$user_array['remember'] = false;
		}
		
		if($user_array['user_login'] == ''){
			echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__('User name should not be empty.','docdirect_core')));
			exit();
		}elseif($user_array['user_password'] == ''){
			echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=>esc_html__('Password should not be empty.','docdirect_core')));
			exit();
		}else{
			$status = wp_signon( $user_array, false );
			if ( is_wp_error($status) ){
				echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=>esc_html__('Wrong username or password.','docdirect_core')));
			} else {
				echo json_encode(array('type'=>'success','url'=> home_url( '/' ),'loggedin'=>true, 'message'=>esc_html__('Successfully Logged in.','docdirect_core')));
			}
		}
	
		die();
	}
	add_action('wp_ajax_docdirect_ajax_login', 'docdirect_ajax_login');
	add_action('wp_ajax_nopriv_docdirect_ajax_login', 'docdirect_ajax_login');
}

/**
 * @Wp Registration
 * @return 
 */
if ( !function_exists('docdirect_user_registration') ) {
	function docdirect_user_registration($atts =''){
		global $wpdb;
			$captcha_settings = '';
			$verify_user	= 'off';
			$verify_switch	= '';
			
			if(function_exists('fw_get_db_settings_option')) {
				$verify_switch = fw_get_db_settings_option('verify_user', $default_value = null);
			}
			
			//Demo Ready
			if( isset( $_SERVER["SERVER_NAME"] ) 
				&& $_SERVER["SERVER_NAME"] === 'themographics.com' ){
				$json['type']	   =  "error";
				$json['message']	=  esc_html__("Registration is disabled by administrator",'docdirect_core' );
				echo json_encode( $json );
				exit();
			}
	
			if(function_exists('fw_get_db_settings_option')) {
				$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
			}
				
			//recaptcha check
			if( isset( $captcha_settings ) 
				&& $captcha_settings === 'enable' 
			) {
				if ( isset($_POST['g-recaptcha-response']) && !empty( $_POST['g-recaptcha-response'] ) ) {
					  $docReResult = docdirect_get_recaptcha_response($_POST['g-recaptcha-response']);
					  
					  if ( $docReResult == 1 ) {
						  $workdone = 1;
					  } else if ( $docReResult == 2 ) {
						  echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__('Some error occur, please try again later.','docdirect_core' )));
						   die;
					  }else{
						echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__('Wrong reCaptcha. Please verify first.','docdirect_core' )));
						die;
					  }
				
				} else{
					echo json_encode(array('type'=>'error','loggedin'=>false, 'message'=> esc_html__( 'Please enter reCaptcha!','docdirect_core' )));
					die;
				}
			}
		
			$username 	= esc_sql($_POST['username']); 
			$terms 		= esc_attr( $_POST['terms'] ); 
			$password 	= esc_sql($_POST['password']);   
			$confirm_password = esc_sql($_POST['confirm_password']);   
			
			$json	= array();
			
			//Demo Ready			
			if( empty( $_POST['user_type'] ) ) {
				$json['type']		=  "error";
				$json['message']	=  esc_html__("Please select user type.", 'docdirect_core');
				echo json_encode( $json );
				exit();
			}
			
			//User Role
			if( isset( $_POST['user_type'] ) && $_POST['user_type'] === 'professional' ) {
				$db_user_role	= 'professional';
			} else{
				$db_user_role	= 'visitor';
			}
			
			if( isset( $_POST['user_type'] ) && $_POST['user_type'] === 'professional' ) {
				if( empty( $_POST['directory_type'] ) ) {
					$json['type']		=  "error";
					$json['message']	=  esc_html__("Please select Directory Type.", 'docdirect_core');
					echo json_encode( $json );
					exit();
				}
			}
			
			if(empty($username)) { 
				$json['type']		=  "error";
				$json['message']	=  esc_html__("User name should not be empty.", 'docdirect_core');
				echo json_encode( $json );
				exit();
			}
			
			$email = esc_sql($_POST['email']); 
			if(empty($email)) { 
				$json['type']		=  "error";
				$json['message']	=  esc_html__("Email should not be empty.", 'docdirect_core');
				echo json_encode( $json );
				exit();
			}
	
			if( !is_email($email) ) { 
				
				$json['type']		=  "error";
				$json['message']	=  esc_html__("Please enter a valid email.", 'docdirect_core');
				echo json_encode( $json );
				die;
			}

			if(empty($password)) { 
				$json['type']		=  "error";
				$json['message']	 =  esc_html__("Password is required.", 'docdirect_core');
				echo json_encode( $json );
				exit();
			}
			
			if( $password != $confirm_password) { 
				$json['type']		=  "error";
				$json['message']	=  esc_html__("Password is not matched.",'docdirect_core');
				echo json_encode( $json );
				exit();
			}
		
			
			if( $terms  == '0') { 
				$json['type']		=  "error";
				$json['message']	=  esc_html__("Please Check Terms and Conditions",'docdirect_core');
				echo json_encode( $json );
				exit();
			}
			
			$random_password = $password;
			 
			$user_identity = wp_create_user( $username,$random_password, $email );
			if ( is_wp_error($user_identity) ) { 
				$json['type']		=  "error";
				$json['message']	=  esc_html__("User already exists. Please try another one.", 'docdirect_core');
				echo json_encode( $json );
				die;
			} else {
				global $wpdb;
				wp_update_user(array('ID'=>esc_sql($user_identity),'role'=>esc_sql($db_user_role),'user_status' => 1));

				$wpdb->update(
				  $wpdb->prefix.'users',
				  array( 'user_status' => 1),
				  array( 'ID' => esc_sql($user_identity) )
				);

				if (function_exists('fw_get_db_settings_option')) {
					$dir_longitude = fw_get_db_settings_option('dir_longitude');
					$dir_latitude  = fw_get_db_settings_option('dir_latitude');
					$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
					$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
				} else{
					$dir_longitude = '-0.1262362';
					$dir_latitude = '51.5001524';
				}
				
				
				$privacy	= array(
					'appointments'	=> 'on',
					'phone'			=> 'on',
					'email'			=> 'on',
					'contact_form'	=> 'on',
					'opening_hours'	=> 'on',
				);
				
				update_user_meta( $user_identity, 'show_admin_bar_front', false );
				update_user_meta( $user_identity, 'user_type', esc_sql($_POST['user_type'] ) );
				update_user_meta( $user_identity, 'first_name', esc_sql($_POST['first_name'] ) );
				update_user_meta( $user_identity, 'last_name', esc_sql($_POST['last_name'] ) );
				update_user_meta( $user_identity, 'phone_number', esc_sql($_POST['phone_number'] ) );
				update_user_meta( $user_identity, 'directory_type', esc_sql($_POST['directory_type'] ) );
				update_user_meta( $user_identity, 'latitude', $dir_latitude);
				update_user_meta( $user_identity, 'longitude', $dir_longitude);
				update_user_meta( $user_identity, 'profile_status', 'active' );
				update_user_meta( $user_identity, 'verify_user', $verify_user );
				update_user_meta( $user_identity, 'rich_editing', 'true' );
				update_user_meta( $user_identity, 'privacy', $privacy );

				$full_name = docdirect_get_username($user_identity);
				update_user_meta( $user_identity, 'full_name', $full_name );

				//Update Profile Hits
				$year			= date('y');
				$month		    = date('m');
				$profile_hits	= array();
				$months_array	= docdirect_get_month_array(); //Get Month  Array

				foreach( $months_array as $key => $value ){
					$profile_hits[$year][$key]	= 0;
				}

				update_user_meta( $user_identity, 'profile_hits', $profile_hits );


				if( class_exists( 'DocDirectProcessEmail' ) ) {
					$email_helper	= new DocDirectProcessEmail();

					$emailData	= array();
					$emailData['user_identity']	=  $user_identity;
					$emailData['first_name']	   =  esc_attr( $_POST['first_name']);
					$emailData['last_name']		=  esc_attr( $_POST['last_name'] );
					$emailData['password']	=  $random_password;
					$emailData['username']	=  $username;
					$emailData['email']	   =  $email;
					$email_helper->process_registeration_email($emailData);
					$email_helper->process_registeration_admin_email($emailData);

					if( !empty( $verify_switch ) && $verify_switch === 'verified' ){
						$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
						update_user_meta( $user_identity, 'confirmation_key', $key_hash);

						$protocol = is_ssl() ? 'https' : 'http';

						$verify_link = esc_url(add_query_arg(array(
							'key' => $key_hash.'&verifyemail='.$email
										), home_url('/', $protocol)));

						$emailData['verify_link'] 	 = $verify_link;
						$email_helper->process_email_verification($emailData);
					}

				} else{
					docdirect_wp_new_user_notification(esc_sql($user_identity), $random_password);
				}

				$dir_profile_page = '';
				if (function_exists('fw_get_db_settings_option')) {
					$dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
				}

				$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';

				$profile_url	= '';
				if( !empty($profile_page) ) {
					$profile_url	= DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'dashboard', $user_identity,true);
				}


				$user_array = array();
				$user_array['user_login'] 		= $username;
				$user_array['user_password'] 	= $random_password;
				$status = wp_signon( $user_array, false );

				$json['type']		=  "success";
				$json['profile_url']	=  $profile_url;
				$json['message']	=  esc_html__("Your have successfully signed up.", "docdirect_core");
				echo json_encode( $json );
				die;
			}
		die();
	}
	
	add_action('wp_ajax_docdirect_user_registration', 'docdirect_user_registration');
	add_action('wp_ajax_nopriv_docdirect_user_registration', 'docdirect_user_registration');
}

/**
 *@User Notification
 * Return{}
 */
if ( !function_exists('docdirect_wp_new_user_notification') ) {
	function docdirect_wp_new_user_notification( $user_id, $plaintext_pass = '') {
		$user = get_userdata( $user_id );
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		// email header
		$header    = 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header   .= 'From: ' . $blogname . " <noreply@docdirect.com> \r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		
		//Body
		$message = sprintf(esc_html__('New user registration on your site %s:','docdirect_core'), $blogname) . "\r\n\r\n";
		$message .= sprintf(esc_html__('Username: %s','docdirect_core'), $user->user_login) . "\r\n\r\n";
		$message .= sprintf(esc_html__('E-mail: %s','docdirect_core'), $user->user_email) . "\r\n";
	
		@wp_mail(get_option('admin_email'), sprintf(esc_html__('[%s] New User Registration','docdirect_core'), $blogname), $message, $header);
	
		if ( empty($plaintext_pass) )
			return;
		
		$message = sprintf(esc_html__('Welcome to %s','docdirect_core'), $blogname) . "\r\n\r\n<br/>";
		$message .= sprintf(esc_html__('Your Username: %s','docdirect_core'), $user->user_login) . "\r\n\r\n<br/>";
		$message .= sprintf(esc_html__('Your Password: %s','docdirect_core'), $plaintext_pass) . "\r\n\r\n<br/>";
		$message .= esc_html__('Click to login','docdirect_core').'<a href="'.esc_url(home_url('/')).'">'.esc_html__('  Login','docdirect_core').'</a>'."\r\n\r\n<br/>";
	
		wp_mail($user->user_email, sprintf(esc_html__('[%s] Your username and password','docdirect_core'), $blogname), $message, $header);
	
	}
}

/**
 *@User Delete
 * Return{}
 */
if ( !function_exists('docdirect_wp_user_delete_notification') ) {
	function docdirect_wp_user_delete_notification($user_id='',$reason='') {
		
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
		$message = sprintf(esc_html__('User deleted %s:','docdirect_core'), $blogname) . "\r\n\r\n";
		$message = sprintf(esc_html__('An existing user has deleted your account due to following reason: %s','docdirect_core'), $reason) . "\r\n\r\n";
		$message .= sprintf(esc_html__('Username: %s','docdirect_core'), $user->user_login) . "\r\n\r\n";
		$message .= sprintf(esc_html__('E-mail: %s','docdirect_core'), $user->user_email) . "\r\n";
	
		@wp_mail(get_option('admin_email'), sprintf(esc_html__('[%s] User Deleted','docdirect_core'), $blogname), $message);
	
	}
}

/**
 * @submit Order
 * @return 
 */
if (!function_exists('docdirect_submit_contact')) {
	function docdirect_submit_contact(){
		global $current_user;
		
		$json	= array();
		
		$do_check = check_ajax_referer( 'docdirect_submit_contact', 'security', false );
		if( $do_check == false ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('No kiddies please!','docdirect_core');	
			echo json_encode($json);
			die;
		}
		
		$bloginfo 		= get_bloginfo();
		$email_subject   =  "(" . $bloginfo . ") Contact Form Received";
		
		$success_message =  esc_attr( $_POST['success'] );
		
		if( $_POST['success'] == '' ){
			$success_message 	=  esc_html__('Message Sent.','docdirect_core');
		}
		
		$failure_message 	=  esc_attr( $_POST['error'] );
		if( $_POST['error'] == '' ){
			$failure_message = esc_html__('Message Fail.','docdirect_core');
		}
		
		$recipient 	=  $_POST['email'];
		if( $_POST['email'] == '' ){
			$recipient	= get_option( 'admin_email' ,'example@example.com' );
		}
		
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the form fields and remove whitespace.
            
			if( $_POST['username'] == '' 
				|| $_POST['useremail'] == '' 
				|| $_POST['subject'] == '' 
				|| $_POST['phone'] == '' 
				|| $_POST['description'] == '' 
			){
				$json['type']	= 'error';
				$json['message']	= esc_html__('Please fill all fields.','docdirect_core');	
				echo json_encode($json);
				die;
			}
			
			if( ! docdirect_isValidEmail($_POST['useremail']) ){
				$json['type']	= 'error';
				$json['message']	= esc_html__('Email address is not valid.','docdirect_core');	
				echo json_encode($json);
				die;
			}
			
			$name	  = esc_attr( $_POST['username'] );
			$email	  = esc_attr( $_POST['useremail'] );
			$subject  = esc_attr( $_POST['subject'] );
			$phone	  = esc_attr( $_POST['phone'] );
			$message  = esc_attr( $_POST['description'] );
		

            // Build the email content.
            $email_content = "Name: $name\n";
            $email_content .= "Email: $email\n\n";
			$email_content .= "Subject: $subject\n\n";
			$email_content .= "Phone: $phone\n\n";
            $email_content .= "Message:\n$message\n";

            // Build the email headers.
            $email_headers = "From: $name <$email>";
			
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				$email_helper	= new DocDirectProcessEmail();
				$emailData	   = array();
				$emailData['name']	  	        = $name;
				$emailData['email']			    = $email;
				$emailData['email_subject']	    = $email_subject;
				$emailData['subject']	  	    = $subject;
				$emailData['phone']	 		    = $phone;					
				$emailData['message']			= $message;
				$emailData['email_to']			= $recipient;
				$email_helper->process_contact_form( $emailData );
			} else{ 
				if (mail($recipient, $email_subject, $email_content, $email_headers)) {
					$json['type']    = "success";
					$json['message'] = esc_attr($success_message);
					echo json_encode( $json );
					die();
				} else {
					$json['type']    = "error";
					$json['message'] = esc_attr($failure_message);
					echo json_encode( $json );
					die();
				}
			}
			
			$json['type']    = "success";
			$json['message'] = esc_attr($success_message);
			echo json_encode( $json );
			die();
			
        } else {
            // Not a POST request, set a 403 (forbidden) response code.
           // http_response_code(403);
            echo 
			$json['type']    = "error";
			$json['message'] = esc_attr($failure_message);
			echo json_encode( $json );
            die();
        }
		
	}
	
	add_action('wp_ajax_docdirect_submit_contact','docdirect_submit_contact');
	add_action( 'wp_ajax_nopriv_docdirect_submit_contact', 'docdirect_submit_contact' );
}


/**
 * Update Order
 *
 * @param json
 * @return string
 */
if (!function_exists('docdirect_new_invoice')) {
	function docdirect_new_invoice($invoice_data=array()){
		extract($invoice_data);
		
		$payment_date = date_i18n(date('Y-m-d H:i:s'));
		$invoice_post = array(
			'post_title' => $txn_id,
			'post_status' => 'publish',
			'post_type' => 'docdirectinvoices',
			'post_date' => current_time('Y-m-d h:i:s')
		);
		
		$post_id = wp_insert_post($invoice_post);
		
		//Update meta for searching purpose
		foreach( $invoice_data as $key=>$value){
			update_post_meta($post_id,$key,$value);
		}
		
		$new_values = $invoice_data;
		if (isset($post_id) && !empty($post_id)) {
			fw_set_db_post_option($post_id, null, $new_values);
		}		
	}
}


/**
 * Update Order
 *
 * @param json
 * @return string
 */
if (!function_exists('docdirect_update_order_data')) {
	function docdirect_update_order_data($data=array()){
		extract($data);
		$payment_date = date('Y-m-d H:i:s');
		$user_featured_date    = get_user_meta( $user_identity, 'user_featured', true);
		$offset = get_option('gmt_offset') * intval(60) * intval(60);
		$payment_date	= strtotime($payment_date) + $offset;

		//Custom Packages Listings managment
		if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
			$featured_date	= date('Y-m-d H:i:s');
			
			//Featured
			if( !empty( $user_featured_date ) && $user_featured_date >  $payment_date ){
				$duration = fw_get_db_post_option($package_id, 'featured_expiry', true); //no of days for a feature listings
				if( $duration > 0 ){
					$featured_date	= strtotime("+".$duration." days", $user_featured_date);
					$featured_date	= date('Y-m-d H:i:s',$featured_date);
				}
			} else{
				$current_date	= date('Y-m-d H:i:s');
				$duration = fw_get_db_post_option($package_id, 'featured_expiry', true);//no of days for a feature listings
				if( $duration > 0 ){
					$featured_date		 = strtotime("+".$duration." days", strtotime($current_date));
					$featured_date	     = date('Y-m-d H:i:s',$featured_date);
				}
			}
			
			//Package Expiry
			$package_expiry	= date('Y-m-d H:i:s');
			$user_package_expiry    = get_user_meta( $user_identity, 'user_current_package_expiry', true);
			
			if( !empty( $user_package_expiry ) && $user_package_expiry >  $payment_date ){
				$package_duration = fw_get_db_post_option($package_id, 'duration', true);
				if( $package_duration > 0 ){
					$package_expiry	= strtotime("+".$package_duration." days", $user_package_expiry);
					$package_expiry	= date('Y-m-d H:i:s',$package_expiry);
				}
			} else{
				$current_date	= date('Y-m-d H:i:s');
				$package_duration = fw_get_db_post_option($package_id, 'duration', true);
				if( $package_duration > 0 ){
					$package_expiry		 = strtotime("+".$package_duration." days", strtotime($current_date));
					$package_expiry	     = date('Y-m-d H:i:s',$package_expiry);
				}
			}
			
			$package_expiry = strtotime($package_expiry) + $offset;
			update_user_meta($user_identity,'user_current_package_expiry',$package_expiry); //package duration
			
		} else{
			
			//Feature Listng For default settings
			$featured_date	= date('Y-m-d H:i:s');

			if( !empty( $user_featured_date ) && $user_featured_date >  $payment_date ){
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
			
			$package_expiry = strtotime($featured_date) + $offset;
			update_user_meta($user_identity,'user_current_package_expiry',$package_expiry); //package duration
		}
		
		$featured_date = strtotime($featured_date) + $offset;
		update_user_meta($user_identity,'user_featured',$featured_date); //featured Expiry
		update_user_meta($user_identity,'user_current_package',$package_id); //Current package
		
		$order_meta = array(
			'transaction_id' 	   => $txn_id,
			'order_status' 	       => 'approved',
			'price' 			   => $payment_gross,
			'payment_date' 	  	   => date('Y-m-d H:i:s', $payment_date ),
			'payment_date_string'  => $payment_date,
			'expiry_date_string'   => $featured_date,
			'expiry_date' 	   	   => date('Y-m-d H:i:s',$featured_date),
			'payment_method' 	   => $payment_method,
			'package' 		   	   => $package_id,
			'mc_currency' 	   	   => $mc_currency,
			'payment_user' 	  	   => $user_identity,
		);
		
		//Update meta for searching purpose
		foreach( $order_meta as $key=>$value){
			update_post_meta($order_id,$key,$value);
		}
		
		$new_values = $order_meta;
		if (isset($order_id) && !empty($order_id)) {
			fw_set_db_post_option($order_id, null, $new_values);
		}
		
		return $featured_date;
	}
}

/**
 * REcaptucha
 *
 * @param json
 * @return string
 */
if (!function_exists('docdirect_get_recaptcha_response')) {
	function docdirect_get_recaptcha_response($recaptcha_data=''){
		if( function_exists( 'fw_get_db_settings_option' ) ){
			$response = null;
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
			$secret_key = fw_get_db_settings_option('secret_key', $default_value = null);
			
			if( !empty( $secret_key ) ){
				$reCaptcha = new ReCaptcha( $secret_key );
				
				if ($recaptcha_data) {
					$response = $reCaptcha->verifyResponse(
						$_SERVER["REMOTE_ADDR"],
						$recaptcha_data
					);
				}

				if ($response != null && $response->success) {
					$statusofjob = 1;
				}else {
					$statusofjob = 0;
				}
			} else{
				$statusofjob = 2;
			}
		}
		
		return $statusofjob;
	}
}



/**
 * @Import Users
 * @return {}
 */
if (!function_exists('docdirect_set_packages_default_settings')) {
	function  docdirect_set_packages_default_settings(){
		$type	=  !empty( $_POST['type'] ) ?  $_POST['type'] : 'default';
		update_option( 'docdirect_packages_settings',$type);
		
		$json['type']	= 'success';	
		$json['message']	= esc_html__('Settings updated.','docdirect_core' );
		echo json_encode( $json );
		die;	
	}
	add_action('wp_ajax_docdirect_set_packages_default_settings', 'docdirect_set_packages_default_settings');	
}

/**
 * @Import Users
 * @return {}
 */
if (!function_exists('docdirect_save_theme_settings')) {
	function  docdirect_save_theme_settings(){
		$settings	= $_POST['settings'];
		$json	= array();
		
		update_option( 'dc_theme_settings', $settings, true );
		$json['type']	= 'success';	
		$json['message']	= esc_html__('Settings updated','docdirect_core' );
		echo json_encode( $json );
		die;	
	}
	add_action('wp_ajax_docdirect_save_theme_settings', 'docdirect_save_theme_settings');	
}

/**
 * @get settings
 * @return {}
 */
if (!function_exists('docdirect_get_theme_settings')) {
	function  docdirect_get_theme_settings($key=''){
		$dc_theme_settings = get_option( 'dc_theme_settings' );
		$setting	= !empty( $dc_theme_settings[$key] ) ? $dc_theme_settings[$key] : '';
		return $setting;
	}
	add_filter('docdirect_get_theme_settings', 'docdirect_get_theme_settings', 10, 1);
}


/**
 * @Register new user from bac-kend
 * @return 
 */
if (!function_exists('docdirect_registration_save')) {
	add_action( 'user_register', 'docdirect_registration_save', 10, 1 );
	function docdirect_registration_save( $user_id ) {
		
		$privacy	= array(
						'appointments'	=> 'on',
						'phone'			=> 'on',
						'email'			=> 'on',
						'contact_form'	=> 'on',
						'opening_hours'	=> 'on',
					);
				
		update_user_meta( $user_id, 'show_admin_bar_front', false );
		update_user_meta( $user_id, 'profile_status', 'active' );
		update_user_meta( $user_id, 'verify_user', 'on' );
		update_user_meta( $user_id, 'rich_editing', 'true' );
		update_user_meta( $user_id, 'privacy', $privacy );

		$full_name = docdirect_get_username($user_id);
		update_user_meta( $user_id, 'full_name', $full_name );
	}
}