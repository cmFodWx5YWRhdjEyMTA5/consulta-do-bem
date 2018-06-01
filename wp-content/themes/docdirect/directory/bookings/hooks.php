<?php
/**
 * @Booking Hooks
 * All the hooks will be in this file
 */



/**
 * @Add Services categories
 * @return {}
 */
if ( ! function_exists( 'docdirect_update_service_category' ) ) {
	function docdirect_update_service_category(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$cat_title	 = esc_attr($_POST['title']);
		
		if( empty( $cat_title ) ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Please add title.','docdirect');
			echo json_encode($json);
			die;
		}
		
		$services_cats	= array();
		$key	 = !empty( $_POST['key'] ) ? esc_attr( $_POST['key'] ) : 'new';
		$type	 = !empty( $_POST['type'] ) ? esc_attr( $_POST['type'] ) : 'add';
		
		if( $key === 'new' ) {
			$services_cats = get_user_meta($user_identity , 'services_cats' , true);
			$services_cats	= empty( $services_cats ) ? array() : $services_cats;
			$title	  = $cat_title;
			$key	  = sanitize_title($title);

			if ( !empty( $services_cats )
				&& array_key_exists($key, $services_cats)
				
			) {
				$key	  = sanitize_title($title).docdirect_unique_increment(3);
			}
			
			$new_cat[$key]	=  $title;
			
			$services_cats	= array_merge($services_cats,$new_cat);
			$message	= esc_html__('Category added successfully.','docdirect');
		} else{
			$services_cats = get_user_meta($user_identity , 'services_cats' , true);
			$services_cats	= empty( $services_cats ) ? array() : $services_cats;
			$title	= esc_attr ( $_POST['title'] );
			$services_cats[$key]	= $title;
			$message	= esc_html__('Category updated successfully.','docdirect');
		}
		
		update_user_meta( $user_identity, 'services_cats', $services_cats );
		
		$json['title']	  = $title;
		$json['key']	  = $key;
		$json['type']	  = 'update';
		$json['message_type']	 = 'success';
		$json['message']  = $message;
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_update_service_category','docdirect_update_service_category');
	add_action( 'wp_ajax_nopriv_docdirect_update_service_category', 'docdirect_update_service_category' );
}


/**
 * @Delete Service categories
 * @return {}
 */
if ( ! function_exists( 'docdirect_delete_service_category' ) ) {
	function docdirect_delete_service_category(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$posted_key	 = sanitize_title($_POST['key']);
		
		if( empty( $posted_key ) ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later.','docdirect');
			echo json_encode($json);
			die;
		}
		
		$services_cats	= array();
		$services_cats = get_user_meta($user_identity , 'services_cats' , true);
		
		unset( $services_cats[$posted_key] );
		
		update_user_meta( $user_identity, 'services_cats', $services_cats );
		
		$json['message_type']	 = 'success';
		$json['message']  = esc_html__('Category deleted successfully.','docdirect');
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_delete_service_category','docdirect_delete_service_category');
	add_action( 'wp_ajax_nopriv_docdirect_delete_service_category', 'docdirect_delete_service_category' );
}


/**
 * @Add Services
 * @return {}
 */
if ( ! function_exists( 'docdirect_update_services' ) ) {
	function docdirect_update_services(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$service_title	= esc_attr ( $_POST['service_title'] );
		$service_price	= esc_attr ( $_POST['service_price'] );
		$service_category = esc_attr ( $_POST['service_category'] );
		
		if( empty( $service_title )
			|| empty( $service_price )
			|| empty( $service_category )
		 ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Please fill all the fields.','docdirect');
			echo json_encode($json);
			die;
		}
		
		$services	= array();
		$key	 = !empty( $_POST['key'] ) ? esc_attr( $_POST['key'] ) : 'new';
		$type	 = !empty( $_POST['type'] ) ? esc_attr( $_POST['type'] ) : 'add';
		
		if( $key === 'new' ) {
			$services = get_user_meta($user_identity , 'booking_services' , true);
			$services	= empty( $services ) ? array() : $services;

			$key	  = sanitize_title($service_title);

			if ( !empty( $services )
				&& array_key_exists($key, $services)
				
			) {
				$key	  = sanitize_title($service_title).docdirect_unique_increment(3);
			}
			
			$new_service[$key]['title']	= $service_title;
			$new_service[$key]['price']	= $service_price;
			$new_service[$key]['category']	= $service_category;
			
			$services	= array_merge($services,$new_service);
			$message	 = esc_html__('Service added successfully.','docdirect');
		} else{
			$services = get_user_meta($user_identity , 'booking_services' , true);
			$services	= empty( $services ) ? array() : $services;
			$service_title	   = esc_attr ( $_POST['service_title'] );
			$service_price	   = esc_attr ( $_POST['service_price'] );
			$service_category= esc_attr ( $_POST['service_category'] );

			$services[$key]['title']	= $service_title;
			$services[$key]['price']	= $service_price;
			$services[$key]['category']	= $service_category;
			
			$message	= esc_html__('Service updated successfully.','docdirect');
		}
		
		update_user_meta( $user_identity, 'booking_services', $services );
		
		$json['service_title']	   = $service_title;
		$json['service_price']	   = $service_price;
		$json['service_category']	= $service_category;
		$json['key']	  = $key;
		$json['type']	 = 'update';
		$json['cats']	 = '';
		$json['message_type']	 = 'success';
		$json['message']  = $message;
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_update_services','docdirect_update_services');
	add_action( 'wp_ajax_nopriv_docdirect_update_services', 'docdirect_update_services' );
}

/**
 * @Delete Service
 * @return {}
 */
if ( ! function_exists( 'docdirect_delete_service' ) ) {
	function docdirect_delete_service(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$posted_key	 = sanitize_title($_POST['key']);
		
		if( empty( $posted_key ) ){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later.','docdirect');
			echo json_encode($json);
			die;
		}
		
		$booking_services	= array();
		$booking_services = get_user_meta($user_identity , 'booking_services' , true);
		
		unset( $booking_services[$posted_key] );
		
		update_user_meta( $user_identity, 'booking_services', $booking_services );
		
		$json['message_type']	 = 'success';
		$json['message']  = esc_html__('Service deleted successfully.','docdirect');
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_delete_service','docdirect_delete_service');
	add_action( 'wp_ajax_nopriv_docdirect_delete_service', 'docdirect_delete_service' );
}

/**
 * @Add Time Slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_add_time_slots' ) ) {
	function docdirect_add_time_slots(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$day 			= esc_attr( $_POST['day'] );
		$slot_title 	= esc_attr( $_POST['slot_title'] );
		$start_time 	= esc_attr( $_POST['start_time'] );
		$end_time 		= esc_attr( $_POST['end_time'] );
		$meeting_time 	= esc_attr( $_POST['meeting_time'] );
		$padding_time 	= esc_attr( $_POST['padding_time'] );
		
		if( empty( $day )
		   || empty( $slot_title )
		   || empty( $start_time )
		   || empty( $end_time )
		   || empty( $meeting_time )
		   || empty( $padding_time )
		){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Please fill fields to add time slots.','docdirect');
			echo json_encode($json);
			die;
		}
		
		$default_slots	= array();
		$default_slots = get_user_meta($user_identity , 'default_slots' , true);

		if (empty($default_slots)): $default_slots = array(); endif;
		
		do {

			$newStartTime = date_i18n("Hi", strtotime('+'.$meeting_time.' minutes', strtotime($start_time)));
			
			if (!empty($default_slots[$day][$start_time.'-'.$newStartTime])){
				$currentCount = $default_slots[$day][$start_time.'-'.$newStartTime]; 
			} else { 
				$currentCount = 0;
			}
			
			$default_slots[$day][$start_time.'-'.$newStartTime] = $count + $currentCount;
			
			$default_slots[$day.'-details'][$start_time.'-'.$newStartTime]['slot_title'] = $slot_title;
			
			if ($padding_time):
				$time_to_add = $padding_time + $meeting_time;
			else :
				$time_to_add = $meeting_time;
			endif;
			
			$start_time = date_i18n("Hi", strtotime('+'.$time_to_add.' minutes', strtotime($start_time)));
			if ($start_time == '0000'):
				$start_time = '2400';
			endif;

		} while ($start_time < $end_time);
		
		update_user_meta( $user_identity, 'default_slots', $default_slots );
		
		
		$json['slots_data']	   = docdirect_get_default_slots($day,'return');
		$json['message_type']	 = 'success';
		$json['message']  = esc_html__('Slots added successfully.','docdirect');
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_add_time_slots','docdirect_add_time_slots');
	add_action( 'wp_ajax_nopriv_docdirect_add_time_slots', 'docdirect_add_time_slots' );
}

/**
 * @Add Time Slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_delete_time_slot' ) ) {
	function docdirect_delete_time_slot(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$day 		= esc_attr( $_POST['day'] );
		$time 	   = esc_attr( $_POST['time'] );
		
		if( empty( $day ) 
			||
			empty( $time )
		){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later.','docdirect');
			echo json_encode($json);
			die;
		}

		$default_slots = get_user_meta($user_identity , 'default_slots' , true);
		
		if ( isset( $default_slots[$day][$time] ) ){
			unset($default_slots[$day][$time]);
			unset($default_slots[$day.'-details'][$time]);
			
			update_user_meta( $user_identity, 'default_slots', $default_slots );
		
			$json['type']	= 'success';
			$json['message']	= esc_html__('Slot deleted succesfully.','docdirect');
			
		} else{
			$json['type']	= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later.','docdirect');
		}
		
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_delete_time_slot','docdirect_delete_time_slot');
	add_action( 'wp_ajax_nopriv_docdirect_delete_time_slot', 'docdirect_delete_time_slot' );
}

/**
 * @Add Time Slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_save_custom_slots' ) ) {
	function docdirect_save_custom_slots(){
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$timeslots_object	 = esc_attr($_POST['custom_timeslots_object']);
		$custom_timeslots_object = stripslashes($timeslots_object);
		update_user_meta( $user_identity, 'custom_slots', addslashes($custom_timeslots_object) );
		
		$json['type']	= 'success';
		$json['message']	= esc_html__('Custom dates added successfully.','docdirect');
			
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_save_custom_slots','docdirect_save_custom_slots');
	add_action( 'wp_ajax_nopriv_docdirect_save_custom_slots', 'docdirect_save_custom_slots' );
}

/**
 * @Add Custom Time Slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_add_custom_time_slots' ) ) {
	function docdirect_add_custom_time_slots(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json	= array();
		$default_slots	= array();
		$default_slots = get_user_meta($user_identity , 'default_slots' , true);
		
		$slot_title 	= esc_attr( $_POST['slot_title'] );
		$start_time 	= esc_attr( $_POST['start_time'] );
		$end_time 		= esc_attr( $_POST['end_time'] );
		$meeting_time 	= esc_attr( $_POST['meeting_time'] );
		$padding_time 	= esc_attr( $_POST['padding_time'] );
		
		$cus_start_date   = esc_attr( $_POST['cus_start_date'] );
		$cus_end_date 	  = esc_attr( $_POST['cus_end_date'] );
		
		$custom_time_slots	 		 = esc_attr($_POST['custom_time_slots']);
		$custom_time_slot_details	 = esc_attr($_POST['custom_time_slot_details']);
		
		$current_times = json_decode(stripslashes($custom_time_slots),true);
		$current_times_details = !empty($custom_time_slot_details) ? json_decode(stripslashes($custom_time_slot_details),true) : array();
				

		do {

			$newStartTime = date_i18n("Hi", strtotime('+'.$meeting_time.' minutes', strtotime($start_time)));
			
			if (!empty($current_times[$start_time.'-'.$newStartTime])){
				$currentCount = $current_times[$start_time.'-'.$newStartTime]; 
			} else { 
				$currentCount = 0;
			}
			
			$current_times[$start_time.'-'.$newStartTime] = $count + $currentCount;
			
			$current_times_details[$start_time.'-'.$newStartTime]['slot_title'] = $slot_title;
			
			if ($padding_time):
				$time_to_add = $padding_time + $meeting_time;
			else :
				$time_to_add = $meeting_time;
			endif;
			
			$start_time = date_i18n("Hi", strtotime('+'.$time_to_add.' minutes', strtotime($start_time)));
			if ($start_time == '0000'):
				$start_time = '2400';
			endif;

		} while ($start_time < $end_time);
		
		
		$json['timeslot']	 = $current_times;
		$json['timeslot_details']  =  $current_times_details;
		$json['type']	= 'success';
		$json['message']	= esc_html__('Custom slots data.','docdirect');
		echo json_encode($json);
		die;
		
	}
	add_action('wp_ajax_docdirect_add_custom_time_slots','docdirect_add_custom_time_slots');
	add_action( 'wp_ajax_nopriv_docdirect_add_custom_time_slots', 'docdirect_add_custom_time_slots' );
}

/**
 * @get time slots list
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_time_slots_list' ) ) {
	function docdirect_get_time_slots_list(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$json_list	 = esc_attr($_POST['json_list']);
		$timeslots = json_decode(stripslashes($json_list),true);
		
		$list	= docdirect_get_custom_slots($timeslots,'return');
		
		$json['timeslot_list']	 = $list;
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_get_time_slots_list','docdirect_get_time_slots_list');
	add_action( 'wp_ajax_nopriv_docdirect_get_time_slots_list', 'docdirect_get_time_slots_list' );
}

/**
 * @delete time slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_delete_custom_time_slots' ) ) {
	function docdirect_delete_custom_time_slots(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$json	= array();
		
		$slot	 = esc_attr($_POST['slot']);
		$current_slots	 = esc_attr($_POST['current_slots']);
		$current_slot_details	 = esc_attr($_POST['current_slot_details']);
		
		if( !empty( $slot )
			&&
			!empty( $current_slots )	
		){
			$delete_slot = $_POST['slot'];
			$current_slots = json_decode(stripslashes($current_slots),true);
			$current_slot_details = json_decode(stripslashes($current_slot_details),true);
			
			if ( isset($current_slots[$delete_slot]) ){
				unset( $current_slots[$delete_slot] );
				unset( $current_slot_details[$delete_slot] );
			}
			
			$json['timeslot']	 = $current_slots;
			$json['timeslot_details']  =  $current_slot_details;
		}
		
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_delete_custom_time_slots','docdirect_delete_custom_time_slots');
	add_action( 'wp_ajax_nopriv_docdirect_delete_custom_time_slots', 'docdirect_delete_custom_time_slots' );
}

/**
 * @Process Booking
 * @return {}
 */
if ( ! function_exists( 'docdirect_do_process_booking' ) ) {
	function docdirect_do_process_booking(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$date_format = get_option('date_format');
		$time_format = get_option('time_format');
		
		$bk_category  = esc_attr( $_POST['bk_category'] );
		$bk_service 	   = esc_attr( $_POST['bk_service'] );
		$booking_date 	   = esc_attr( $_POST['booking_date'] );
		$timestamp 	 	   = strtotime(esc_attr( $_POST['booking_date'] ));
		$slottime		   = esc_attr( $_POST['slottime'] );
		$subject 		   = esc_attr( $_POST['subject'] );
		$username 		 	= esc_attr( $_POST['username'] );
		$userphone 			= esc_attr( $_POST['userphone'] );
		$useremail 			= esc_attr( $_POST['useremail'] );
		$booking_note 	 	= esc_attr( $_POST['booking_note'] );
		$payment 		  	= esc_attr( $_POST['payment'] );
		$user_to		  	= esc_attr( $_POST['data_id'] );
		$status		   		= 'pending';
		$user_from			= $current_user->ID;
		$bk_status			= 'pending';
		$payment_status		= 'pending';
		
		
		//Add Booking
		$appointment = array(
			'post_title'  => $subject,
			'post_status' => 'publish',
			'post_author' => $current_user->ID,
			'post_type'   => 'docappointments',
			'post_date'   => current_time('Y-m-d h')
		);
		
		//User Detail
		$currency	    	 = get_user_meta( $user_to, 'currency', true);
		$stripe_secret	     = get_user_meta( $user_to, 'stripe_secret', true);
		$stripe_publishable  = get_user_meta( $user_to, 'stripe_publishable', true);
		$stripe_site	     = get_user_meta( $user_to, 'stripe_site', true);
		$paypal_enable	     = get_user_meta( $user_to, 'paypal_enable', true);
		$stripe_decimal	     = get_user_meta( $user_to, 'stripe_decimal', true);
			 
		//Price
		$services = get_user_meta($user_to , 'booking_services' , true);
		 
		if( !empty( $services[$bk_service]['price'] ) ){
		  $price	= $services[$bk_service]['price'];	
		}
			 
		$post_id  = wp_insert_post($appointment);
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		$appointment_no = substr($blogname,0,2).'-'.docdirect_unique_increment(5);
		
		$appointment_meta = array(
			'bk_code' 	  	     => $appointment_no,
			'bk_category' 	     => $bk_category,
			'bk_service' 	     => $bk_service,
			'bk_booking_date' 	 => $booking_date,
			'bk_slottime' 		 => $slottime,
			'bk_subject' 		 => $subject,
			'bk_username' 		 => $username,
			'bk_userphone' 		 => $userphone,
			'bk_useremail' 		 => $useremail,
			'bk_booking_note' 	 => $booking_note,
			'bk_payment' 		 => $payment,
			'bk_user_to' 		 => $user_to,
			'bk_timestamp' 		 => $timestamp,
			'bk_status' 		 => $bk_status,
			'payment_status' 	 => $payment_status,
			'bk_user_from' 		 => $user_from,
			'bk_paid_amount' 	 => $price,
			'bk_currency' 	     => $currency,
			'bk_transaction_status'  => 'pending',
			'bk_payment_date' 	    => date('Y-m-d H:i:s'),
		);
		
		$new_values = $appointment_meta;
		if ( isset( $post_id ) && !empty( $post_id ) ) {
			fw_set_db_post_option($post_id, null, $new_values);
		}
		
		//Update post meta
		foreach( $appointment_meta as $key => $value ){
			update_post_meta($post_id,$key,$value);
		}
 
		if( isset( $payment ) && $payment === 'stripe' ){

			 if( class_exists( 'DocDirectProcessEmail' ) ) {
				//Send Email
				$email_helper	  = new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['post_id']	= $post_id;
				$email_helper->process_appointment_confirmation_email($emailData);
				$email_helper->process_appointment_confirmation_admin_email($emailData);
			 }
			 
			 //Process Payment

			 $total_amount	= $price;
			 if( isset( $stripe_decimal ) && $stripe_decimal == 0 ){
				$service_amount	= $price;
			 } else{
				$service_amount	= $price * 100;	 
			 }
			 
			 echo json_encode( 
				array( 
					   'username' 	 	=> $user_name,
					   'email' 		    => $useremail,
					   'order_no' 	    => $post_id,
					   'user_to'   		=> $user_to,
					   'user_from' 	    => $user_from,
					   'subject'    	=> $subject,
					   'process'		=> true, 
					   'name'			=> $stripe_site, 
					   'amount' 		=> $service_amount,
					   'total_amount' 	=> $total_amount,
					   'key'			=> $stripe_publishable,
					   'currency'		=> $currency,
					   'data'			=> '',
					   'type'			=> 'success',
					   'payment_type'	=> 'stripe',
					  )
				);
				
			 die;
			 
		} else if( isset( $payment ) && $payment === 'paypal' ){
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				//Send Email
				$email_helper	  = new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['post_id']	= $post_id;
				$email_helper->process_appointment_confirmation_email($emailData);
				$email_helper->process_appointment_confirmation_admin_email($emailData);
			}

			
			
			/*---------------------------------------------
			 * @Paypal Payment Gateway Process
			 * @Return HTML
			 ---------------------------------------------*/
			$sandbox_enable = fw_get_db_settings_option('user_enable_sandbox');
			$business_email	  = get_user_meta( $user_to, 'paypal_email_id', true);
			$currency	    	= get_user_meta( $user_to, 'currency', true);
			$listner_url	= '';
			
			if( class_exists( 'DocDirectGlobalSettings' ) ) {
				$plugin_url	= DocDirectGlobalSettings::get_plugin_url();
				$listner_url	   = $plugin_url. '/payments/booking.php';
			}
			
			if (isset($sandbox_enable) && $sandbox_enable == 'on') {
                $paypal_path = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $paypal_path = 'https://www.paypal.com/cgi-bin/webscr';
            }

            if ( $currency == '' 
				 ||
				 $business_email == '' 
				 || 
				 $listner_url == ''
			) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur please contact to administrator.', 'docdirect');
                echo json_encode($json);
                die;
            }
			
			
			$return_url	= get_author_posts_url($user_to);
			
			$output  = '';
            $output .= '<form name="paypal_subscription" id="paypal_subscribe_form" action="' . $paypal_path . '" method="post">  
							<input type="hidden" name="cmd" value="_xclick">  
							<input type="hidden" name="business" value="' . $business_email . '">
							<input type="hidden" name="amount" value="' . $price . '">
							<input type="hidden" name="item_name" value="'.esc_attr($subject).'"> 
							<input type="hidden" name="currency_code" value="' . $currency . '">
							<input type="hidden" name="item_number" value="'.esc_attr($post_id).'">  
							<input name="cancel_return" value="'.$return_url.'" type="hidden">
							<input type="hidden" name="custom" value="'.$post_id.'">    
							<input type="hidden" name="no_note" value="1">  
							<input type="hidden" name="notify_url" value="' . $listner_url . '">
							<input type="hidden" name="lc">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="return" value="'.$return_url.'?payment=true">  
					   </form>';

            $output .= '<script>
							jQuery("#paypal_subscribe_form").submit();
					  </script>';

            $json['form_data']     = $output;
            $json['type'] 		  = 'success';
			$json['payment_type']  = 'paypal';
			echo json_encode($json);
			die;

		} else {
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				//Send Email
				$email_helper	  = new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['post_id']	= $post_id;
				$email_helper->process_appointment_confirmation_email($emailData);
				$email_helper->process_appointment_confirmation_admin_email($emailData);
			}
			
		    $json['data']	= docdirect_get_booking_step_five($user_to);
			$json['message']		= esc_html__('Your boooking has submitted.','docdirect');	
			$json['type']  =  'error';
			$json['payment_type']  =  'local';
			echo json_encode($json);
			die;
		}
	}
	add_action('wp_ajax_docdirect_do_process_booking','docdirect_do_process_booking');
	add_action( 'wp_ajax_nopriv_docdirect_do_process_booking', 'docdirect_do_process_booking' );
}


/**
 * @Stripe Payment
 *
 * @param json
 * @return string
 */
if ( !function_exists('docdirect_complete_booking_stripe_payment') ) {
	function docdirect_complete_booking_stripe_payment() {		
										
		$username	  = esc_attr( $_POST['username'] );
		$email	 	  = esc_attr( $_POST['email'] );
		$post_id  	  = esc_attr( $_POST['order_no'] );
		$user_to 	  = esc_attr( $_POST['user_to'] );
		$user_from    = esc_attr( $_POST['user_from'] );
		$subject 	  = esc_attr( $_POST['subject'] );
		$name  		  = esc_attr( $_POST['name'] );
		$amount 	    = esc_attr( $_POST['amount'] );
		$total_amount   = esc_attr( $_POST['total_amount'] );
		$token	    	= docdirect_sanitize_array( $_POST['token'] );
		$currency 	 	= esc_attr( $_POST['currency'] );
		$type 	  	 	= esc_attr( $_POST['type'] );
		$payment_type 	= esc_attr( $_POST['payment_type'] );
		$currency_sign	= 'USD';
		
		$currency	    	= get_user_meta( $user_to, 'currency', true);
		$stripe_secret	   = get_user_meta( $user_to, 'stripe_secret', true);
		$stripe_publishable  = get_user_meta( $user_to, 'stripe_publishable', true);
		$stripe_site		 = get_user_meta( $user_to, 'stripe_site', true);
		$paypal_enable	   = get_user_meta( $user_to, 'paypal_enable', true);
		$stripe_decimal	= get_user_meta( $user_to, 'stripe_decimal', true);
		
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
			'amount'   => $amount,
			'currency' => ''.$currency_sign.'',
			'source'  => $token['id'],
			'description' => $package_name,
		  ));
		
		if ($charge->status == 'succeeded') {
			
			if( !empty( $charge->source->id ) ){
				$transaction_id	= $charge->source->id;
			} else{
				$transaction_id	= docdirect_unique_increment(10);
			}
			
			$old_values = (array)fw_get_db_post_option($post_id);
			
			$new_values	= array();
			$new_values['bk_code']	= $transaction_id;
			$new_values['bk_payment_date']		  = date('Y-m-d H:i:s');
			$new_values['payment_status']	       = 'approved';
			$new_values['bk_status']				= 'approved';
			$new_values['bk_transaction_status']	= 'approved';

			fw_set_db_post_option(
				$post_id, 
				null, // this means it will replace all option values, not only a specific option_id
				array_merge($old_values, $new_values)
			);
			
			//Update post meta
			foreach( $new_values as $key => $value ){
				update_post_meta($post_id,$key,$value);
			}
			
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				//Send Email
				$email_helper	  = new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['post_id']	= $post_id;
				$email_helper->process_appointment_approved_email($emailData);
			}
			
		    $json['data']	= docdirect_get_booking_step_five($user_to);
			$json['message']  = esc_html__('Thank you! Booking has been completed','docdirect');
			$json['type']  =  'success';
			echo json_encode($json);
			die;
		}
		
		$json['type']     = 'error';
		$json['message']  = esc_html__('Some Error occur, please try again later.','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_complete_booking_stripe_payment', 'docdirect_complete_booking_stripe_payment');
	add_action('wp_ajax_nopriv_docdirect_complete_booking_stripe_payment', 'docdirect_complete_booking_stripe_payment');
}

/**
 * @Approve/Cancel Booking
 * @return {}
 */
if ( ! function_exists( 'docdirect_change_appointment_status' ) ) {
	function docdirect_change_appointment_status(){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;;
		
		$type		  = esc_attr( $_POST['type']);
		$post_id	  = esc_attr( $_POST['id'] );
		
		if( empty( $type ) 
			||
			empty( $post_id )
		){
			$json['type']	= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later.','docdirect');
			echo json_encode($json);
			die;
		}

		if( $type === 'approve' ){
			$value	= 'approved';
			
			update_post_meta($post_id,'bk_status',$value);
			
			//Send Email
			$email_helper	  = new DocDirectProcessEmail();
			$emailData	= array();
			$emailData['post_id']	= $post_id;
			$email_helper->process_appointment_approved_email($emailData);
			
			//Send status
			$json['action_type']	= $value;
			$json['type']		   = 'success';
			$json['message']		= esc_html__('Appointment status has updated.','docdirect');
			echo json_encode($json);
			die;
		
		} else if( $type === 'cancel' ){
			$value	= 'cancelled';

			//Send Email
			$email_helper	  = new DocDirectProcessEmail();
			$emailData	= array();
			$emailData['post_id']	= $post_id;
			$email_helper->process_appointment_cancelled_email($emailData);
			
			update_post_meta($post_id,'bk_status',$value);
			
			//Return status
			$json['action_type']	= $value;
			$json['type']		   = 'success';
			$json['message']		= esc_html__('Appointment has been cancelled.','docdirect');
			echo json_encode($json);
			die;
		}

		
	}
	add_action('wp_ajax_docdirect_change_appointment_status','docdirect_change_appointment_status');
	add_action( 'wp_ajax_nopriv_docdirect_change_appointment_status', 'docdirect_change_appointment_status' );
}
