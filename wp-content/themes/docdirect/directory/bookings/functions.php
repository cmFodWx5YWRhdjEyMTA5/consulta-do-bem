<?php
/**
 * @Booking functionality
 * All the fu8nctions will be in this file
 */



/**
 * @get list of default slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_default_slots' ) ) {
	function docdirect_get_default_slots($day='',$return_type="return"){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		$week_days	= docdirect_get_week_array();
		
		$default_slots	= array();
		$default_slots = get_user_meta($user_identity , 'default_slots' , true);
		$time_format = get_option('time_format');
		
		ob_start();
		if( !empty( $default_slots[$day] ) ) {
			foreach( $default_slots[$day] as $key => $value ){
				
				$time = explode('-',$key);
				?>
				<div class="tg-doctimeslot form-group">
					<div class="tg-box">
						<a class="tg-deleteslot delete-current-slot" data-time="<?php echo esc_attr( $key );?>" data-day="<?php echo esc_attr( $day );?>" href="javascript:;"><i class="fa fa-close"></i></a>
						<?php if( !empty( $default_slots[$day.'-details'][$key]['slot_title'] ) ){?>
                        	<span class="tg-title"><?php echo esc_attr( $default_slots[$day.'-details'][$key]['slot_title'] );?></span>
                        <?php }?>
						<time datetime="2020-010-01"><?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>-<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></time>
					</div>
				</div>
				<?php	
			}
		}
		
		if( $return_type === 'return' ){
			return ob_get_clean();
		} else{
			echo ob_get_clean();
		}
		
	}
}


/**
 * @get list of custom slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_custom_slots' ) ) {
	function docdirect_get_custom_slots($data='',$return_type="return"){ 
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		$time_format = get_option('time_format');
		
		ob_start();
		if( !empty( $data['timeslot'] ) ) {
			foreach( $data['timeslot'] as $key => $value ){
				
				$time = explode('-',$key);
				?>
				<div class="tg-doctimeslot form-group">
					<div class="tg-box">
						<a class="tg-deleteslot delete-custom-slot" data-time="<?php echo esc_attr( $key );?>" href="javascript:;"><i class="fa fa-close"></i></a>
						<?php if( !empty( $data['timeslot_details'][$key]['slot_title'] ) ){?>
                        	<span class="tg-title"><?php echo esc_attr( $data['timeslot_details'][$key]['slot_title'] );?></span>
                        <?php }?>
						<time datetime="2020-010-01"><?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>-<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></time>
					</div>
				</div>
				<?php	
			}
		}
		
		if( $return_type === 'return' ){
			return ob_get_clean();
		} else{
			echo ob_get_clean();
		}
		
	}
}

/**
 * @prepare seprate array
 * @return {}
 */
if ( ! function_exists( 'docdirect_prepare_seprate_array' ) ) {
	function docdirect_prepare_seprate_array($slot_timings){
	
		$total_fields = isset( $slot_timings['cus_start_date'] ) ? count($slot_timings['cus_start_date']) - 1 : 0;
		$custom_timeslots_array = array();
		
		$counter = 0;
	
		if ( $total_fields ):
	
			do {
				foreach($slot_timings as $key => $values):
					if ($key == 'custom_time_slots' || $key == 'custom_time_slot_details'):
						$values = json_decode($values[$counter],true);
						$custom_timeslots_array[$counter][$key] = $values;
					else:
						$custom_timeslots_array[$counter][$key] = (isset($values[$counter]) ? $values[$counter] : $values);
					endif;
				endforeach;
				$counter++;
			} while($total_fields >= $counter);
	
		else :
	
			$custom_timeslots_array[0] = $slot_timings;
	
		endif;
	
		return $custom_timeslots_array;
	
	}
}

/**
 * @Custom Time Slots
 * @return {}
 */
if ( ! function_exists( 'docdirect_custom_timeslots_filter' ) ) {
	function docdirect_custom_timeslots_filter( $default_slots=false,$user_id = false){
		
		$default_slots	= $default_slots[0];
		//print_r($default_slots);
		$custom_timeslots_array = array();
		$custom_slots = get_user_meta($user_id , 'custom_slots' , true);
		
		$custom_slots = json_decode($custom_slots,true);
	
		if (!empty($custom_slots)):
	
			$custom_timeslots_array = docdirect_prepare_seprate_array($custom_slots);
			
			//print_r($custom_timeslots_array);
			
			foreach($custom_timeslots_array as $key => $value):
	
				if ($value['cus_start_date']):
	
					$formatted_date = date_i18n('Ymd',strtotime($value['cus_start_date']));
					$formatted_end_date = date_i18n('Ymd',strtotime($value['cus_end_date']));
	
					if (!$value['cus_end_date']){
						// Single Date
						if ( isset( $value['disable_appointment'] )
							 &&
							 $value['disable_appointment'] === 'disable'
						){
							// Time slots disabled
							$default_slots[$formatted_date] = array();
							$default_slots[$formatted_date.'-details'] = array();
						} else {
							// Add time slots to this date
							$default_slots[$formatted_date] = $value['custom_time_slots'];
							$default_slots[$formatted_date.'-details'] = !empty($value['custom_time_slot_details']) ? $value['custom_time_slot_details'] : array();
						}
					} else {
						// Multiple Dates
						$tempDate = $formatted_date;
						do {
							if ( isset( $value['disable_appointment'] )
								 &&
								 $value['disable_appointment'] === 'disable'
							){
								// Time slots disabled
								$default_slots[$tempDate] = array();
								$default_slots[$tempDate.'-details'] = array();
							} else {
								// Add time slots to this date
								$default_slots[$tempDate] = $value['custom_time_slots'];
								$default_slots[$tempDate.'-details'] = !empty($value['custom_time_slot_details']) ? $value['custom_time_slot_details'] : array();
							}
							$tempDate = date_i18n('Ymd',strtotime($tempDate . ' +1 day'));
						} while ($tempDate <= $formatted_end_date);
					}

				endif;
	
			endforeach;
	
		endif;
	
		return $default_slots;
	}
}

/**
 * @Booking Step 1
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_booking_step_one' ) ) {
	function docdirect_get_booking_step_one($user_identity='',$return_type='echo'){
		global $current_user, $wp_roles,$userdata,$post;
		$booking_all_services	= array();
		$services_cats = get_user_meta($user_identity , 'services_cats' , true);
		$booking_services = get_user_meta($user_identity , 'booking_services' , true);
		$currency_symbol	       = get_user_meta( $user_identity, 'currency_symbol', true);
		$currency_symbol	= !empty( $currency_symbol ) ? $currency_symbol : '';

		ob_start();
		?>
        <div class="bk-step-1">
          <div class="form-group">
            <div class="doc-select">
              <select name="bk_category" class="bk_category">
                <option value=""><?php esc_html_e('Select Category*','docdirect');?></option>
                <?php 
				if( !empty( $services_cats ) ) {
					foreach( $services_cats as $key => $value ){
				 ?>
                   <option value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value );?></option>
                 <?php
						}
					}
				?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="doc-select">
              <select name="bk_service" class="bk_service">
                <option value=""><?php esc_html_e('Select Service*','docdirect');?></option>
                <?php 
				if( !empty( $booking_services ) ) {
					
					foreach( $booking_services as $key => $value ){
						$booking_all_services[$value['category']][$key]	= $value;
				 ?>
                   <option value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value['title'] );?>&nbsp;--&nbsp;<?php echo esc_attr( $currency_symbol );?><?php echo esc_attr( $value['price'] );?></option>
                 <?php
						}
					}
				?>
              </select>
              <script>
				jQuery(document).ready(function() {
					var Z_Editor = {};
					Z_Editor.services = {};
					Z_Editor.all_services = {};
					window.Z_Editor = Z_Editor;
					Z_Editor.services = jQuery.parseJSON( '<?php echo addslashes(json_encode($booking_all_services));?>' );
					Z_Editor.all_services = jQuery.parseJSON( '<?php echo addslashes(json_encode($booking_services));?>' );
				});
			</script> 
            <script type="text/template" id="tmpl-load-services">
				<option value=""><?php esc_html_e('Select Service*','docdirect');?></option>
				<#
					var _option	= '';
					if( !_.isEmpty(data) ) {
						_.each( data , function(element, index, attr) { #>
							 <option value="{{index}}">{{element.title}}&nbsp;--&nbsp;<?php echo esc_attr( $currency_symbol );?>{{element.price}}</option>
						<#	
						});
					}
				#>
			</script> 
            </div>
          </div>
        </div>
        <?php
		if( isset( $return_type ) && $return_type == 'return' ){
			return ob_get_clean();
		} else {
			echo ob_get_clean();
		}
	}
}

/**
 * @Booking Step 2
 * Schedules
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_booking_step_two' ) ) {
	function docdirect_get_booking_step_two(){
		global $current_user, $wp_roles,$userdata,$post;
		
		$user_id	= sanitize_text_field( $_POST['data_id'] );
		$slot_date	= sanitize_text_field( $_POST['slot_date'] );

		if( !empty( $slot_date ) ){
			$day		= strtolower(date('D',strtotime( $slot_date )));
			$current_date_string	= date_i18n('M d, l',strtotime($slot_date));
			$current_date	= $slot_date;
			$slot_date	    = $slot_date;
		} else{
			$day		= strtolower(date('D'));
			$current_date_string	= date_i18n('M d, l');
			$current_date	= date('Y-m-d');
			$slot_date	   = date('Y-m-d');
		}

		$week_days	= docdirect_get_week_array();
		
		$default_slots	= array();
		$default_slots = get_user_meta($user_id , 'default_slots' , false);
		$time_format   = get_option('time_format');
		
		//Custom Slots
		$custom_slot_list	= docdirect_custom_timeslots_filter($default_slots,$user_id);

		//Get booked Appointments
		$year  	  = date_i18n('Y',strtotime($slot_date));
		$month 	  = date_i18n('m',strtotime($slot_date));
		$day_no   = date_i18n('d',strtotime($slot_date));

		$start_timestamp = strtotime($year.'-'.$month.'-'.$day_no.' 00:00:00');
		$end_timestamp = strtotime($year.'-'.$month.'-'.$day_no.' 23:59:59');
		

		$args 		= array('posts_per_page' => -1, 
							 'post_type' => 'docappointments', 
							 'post_status' => 'publish', 
							 'ignore_sticky_posts' => 1,
							 'meta_query' => array(
									array(
										'key'     => 'bk_timestamp',
										'value'   => array( $start_timestamp, $end_timestamp ),
										'compare' => 'BETWEEN'
									),
									array(
										'key'     => 'bk_user_to',
										'value'   => $user_id,
										'compare' => '='
									),
									array(
										'key'     => 'bk_status',
										'value' => array('approved', 'pending'),
										'compare' => 'IN'
									)
								)
							);
			

		$query 		= new WP_Query($args);
		$count_post = $query->post_count;        
		$appointments_array	= array();
		while($query->have_posts()) : $query->the_post();
			global $post;
			
			$bk_category      = get_post_meta($post->ID, 'bk_category',true);
			$bk_service       = get_post_meta($post->ID, 'bk_service',true);
			$bk_booking_date  = get_post_meta($post->ID, 'bk_booking_date',true);
			$bk_slottime 	  = get_post_meta($post->ID, 'bk_slottime',true);
			$bk_subject       = get_post_meta($post->ID, 'bk_subject',true);
			$bk_username      = get_post_meta($post->ID, 'bk_username',true);
			$bk_userphone 	  = get_post_meta($post->ID, 'bk_userphone',true);
			$bk_useremail     = get_post_meta($post->ID, 'bk_useremail',true);
			$bk_booking_note  = get_post_meta($post->ID, 'bk_booking_note',true);
			$bk_payment       = get_post_meta($post->ID, 'bk_payment',true);
			$bk_user_to       = get_post_meta($post->ID, 'bk_user_to',true);
			$bk_timestamp     = get_post_meta($post->ID, 'bk_timestamp',true);
			$bk_status        = get_post_meta($post->ID, 'bk_status',true);
			$bk_user_from     = get_post_meta($post->ID, 'bk_user_from',true);
			
			$appointments_array[$bk_slottime]['bk_category'] = $bk_category;
			$appointments_array[$bk_slottime]['bk_service'] = $bk_service;
			$appointments_array[$bk_slottime]['bk_booking_date'] = $bk_booking_date;
			$appointments_array[$bk_slottime]['bk_slottime'] = $bk_slottime;
			$appointments_array[$bk_slottime]['bk_subject'] = $bk_subject;
			$appointments_array[$bk_slottime]['bk_username'] = $bk_username;
			$appointments_array[$bk_slottime]['bk_userphone'] = $bk_userphone;
			$appointments_array[$bk_slottime]['bk_useremail'] = $bk_useremail;
			$appointments_array[$bk_slottime]['bk_booking_note'] = $bk_booking_note;
			$appointments_array[$bk_slottime]['bk_user_to'] = $bk_user_to;
			$appointments_array[$bk_slottime]['bk_timestamp'] = $bk_timestamp;
			$appointments_array[$bk_slottime]['bk_status'] = $bk_status;
			$appointments_array[$bk_slottime]['bk_user_from'] = $bk_user_from;
			
		endwhile; wp_reset_postdata(); 
		
		
		
		$formatted_date = date_i18n('Ymd',strtotime($slot_date));
		$day_name 	   = strtolower(date('D',strtotime($slot_date)));
		
		if (  isset($custom_slot_list[$formatted_date]) 
			&& 
			  !empty($custom_slot_list[$formatted_date])
		){
			$todays_defaults = is_array($custom_slot_list[$formatted_date]) ? $custom_slot_list[$formatted_date] : json_decode($custom_slot_list[$formatted_date],true);
			
			$todays_defaults_details = is_array($custom_slot_list[$formatted_date.'-details']) ? $custom_slot_list[$formatted_date.'-details'] : json_decode($custom_slot_list[$formatted_date.'-details'],true);
		
		} else if ( isset($custom_slot_list[$formatted_date]) 
					&& 
					empty($custom_slot_list[$formatted_date])
		){
			$todays_defaults = false;
			$todays_defaults_details = false;
		} else if (  isset($custom_slot_list[$day_name]) 
					 && 
					 !empty($custom_slot_list[$day_name])
		){
			$todays_defaults = $custom_slot_list[$day_name];
			$todays_defaults_details = $custom_slot_list[$day_name.'-details'];
		} else {
			$todays_defaults = false;
			$todays_defaults_details = false;
		}

		
		//Data
		ob_start();
        if( !empty( $todays_defaults ) ) {
        foreach( $todays_defaults as $key => $value ){
            $time = explode('-',$key);
            
            if( !empty( $appointments_array[$key]['bk_slottime'] )
                &&
                $appointments_array[$key]['bk_slottime'] == $key
            ){
                $slotClass	= 'tg-booked';
                $slot_status	= 'disabled';
            } else{
                $slotClass	= 'tg-available';
                $slot_status	= '';
            }
        ?>
        <div class="tg-doctimeslot <?php echo sanitize_html_class( $slotClass );?>">
            <div class="tg-box">
                <div class="tg-radio">
                    <input <?php echo esc_attr( $slot_status );?> id="<?php echo esc_attr( $key );?>" value="<?php echo esc_attr( $key );?>" type="radio" name="slottime">
                    <label for="<?php echo esc_attr( $key );?>"><?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>&nbsp;-&nbsp;<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></label>
                </div>
            </div>
        </div>
        <?php
        } }
      
		$json['data']	 = ob_get_clean();
		$json['type']	 = 'success';
		$json['message']  = esc_html__('slots returned','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_get_booking_step_two','docdirect_get_booking_step_two');
	add_action( 'wp_ajax_nopriv_docdirect_get_booking_step_two', 'docdirect_get_booking_step_two' );
}

/**
 * @Booking Step 2
 * Schedules
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_booking_step_two_calender' ) ) {
	function docdirect_get_booking_step_two_calender($user_identity='',$return_type='echo'){
		global $current_user, $wp_roles,$userdata,$post;
		$schedule_message	     = get_user_meta( $user_identity, 'schedule_message', true);
		$current_date	= date('Y-m-d');
		$slot_date	    = date('Y-m-d');
		$current_date_string	= date_i18n( get_option( 'date_format' ), strtotime( date('Y-m-d') ) );
			
		?>
        <div class="bk-booking-schedules">
            <div class="tg-appointmenttime">
              <?php if( !empty( $schedule_message ) ){?>
                  <div class="tg-description">
                      <p><?php echo force_balance_tags( $schedule_message );?></p>
                  </div>
              <?php }?>
              <div class="clearfix"></div>
              <div class="tg-dayname booking-pickr"> 
              	<strong><?php echo esc_attr( $current_date_string );?></strong>
                <input type="hidden" name="booking_date" class="booking_date" value="<?php echo esc_attr( $current_date );?>" />
              </div>
              <div class="tg-timeslots step-two-slots">
                  <div class="tg-timeslotswrapper"></div>
              </div>
       		</div>
        </div>
        <?php
	}
}

/**
 * @Booking Step 3
 * Customer detail
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_booking_step_three' ) ) {
	function docdirect_get_booking_step_three(){
		global $current_user, $wp_roles,$userdata,$post;
		ob_start();
		$username	= '';
		$userphone	= '';
		$useremail	= '';
		
		if( !empty( $current_user->ID ) ){
			$user_id	= esc_attr( $_POST['data_id'] );
			$user_date	= get_userdata(intval($current_user->ID));
			$user_login   = $user_date->user_login;
			$nickname     = $user_date->nickname;
			$first_name   = $user_date->first_name; 
			$last_name    = $user_date->last_name;
			$userphone    = $user_date->phone_number;
			
			if( !empty( $user_date->user_email ) ){
				$useremail    = $user_date->user_email;
			}
			
			if( !empty( $first_name ) || !empty( $last_name ) ){
				$username	= $first_name.' '.$last_name;
			} else if( !empty( $nickname ) ){
				$username	= $nickname;
			} else{
				$username	= $user_login;
			}

		}
		?>
        <div class="bk-customer-form">
            <div class="form-group">
              <input type="text" class="form-control" name="subject" placeholder="<?php esc_attr_e('Subject*','docdirect');?>">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="username" value="<?php echo esc_attr($username);?>" placeholder="<?php esc_attr_e('Your Name*','docdirect');?>">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo esc_attr($userphone);?>" name="userphone" placeholder="<?php esc_attr_e('Phone*','docdirect');?>">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="useremail" value="<?php echo esc_attr($useremail);?>" placeholder="<?php esc_attr_e('Email*','docdirect');?>">
            </div>
            <div class="form-group tg-textarea">
              <textarea class="form-control" name="booking_note" placeholder="<?php esc_attr_e('Note*','docdirect');?>"></textarea>
            </div>
        </div>
		<?php
		$json['data']	 = ob_get_clean();
		$json['type']	 = 'success';
		$json['message']  = esc_html__('form returned','docdirect');
		echo json_encode($json);
		die;
	}
	
	add_action('wp_ajax_docdirect_get_booking_step_three','docdirect_get_booking_step_three');
	add_action( 'wp_ajax_nopriv_docdirect_get_booking_step_three', 'docdirect_get_booking_step_three' );
}

/**
 * @Booking Step 4
 * Payment Mode
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_booking_step_four' ) ) {
	function docdirect_get_booking_step_four(){
		global $current_user, $wp_roles,$userdata,$post;
		ob_start();

		if( !empty( $_POST['data_id'] ) ){
			$paypal_enable	= get_user_meta( esc_attr( $_POST['data_id'] ), 'paypal_enable', true);
			$stripe_enable	= get_user_meta( esc_attr( $_POST['data_id'] ) , 'stripe_enable', true);
		}
		
		$user_disable_stripe	= '';
		$user_disable_paypal	= '';
		
		if(function_exists('fw_get_db_settings_option')) {
			$user_disable_stripe = fw_get_db_settings_option('user_disable_stripe', $default_value = null);
			$user_disable_paypal = fw_get_db_settings_option('user_disable_paypal', $default_value = null);
		}

		?>
        <div class="bk-payment-methods">
            <div class="form-group tg-pay-radiobox">
              <label>
                <input type="radio" value="local" name="payment" checked>
                <span><?php esc_html_e('I will pay locally.','docdirect');?></span> 
              </label>
            </div>
            <?php 
			if( ( !empty( $paypal_enable ) && $paypal_enable === 'on' )
				 && ( !empty( $user_disable_paypal ) && $user_disable_paypal === 'on' ) 
			){?>
            <div class="form-group tg-pay-radiobox tg-paypal">
              <label>
                <input type="radio" value="paypal" name="payment">
                <span><?php esc_html_e('I will pay now through Paypal.','docdirect');?></span></label>
            </div>
            <?php }?>
            <?php 
			if( !empty( $stripe_enable ) && $stripe_enable === 'on' 
				&&  ( !empty( $user_disable_stripe ) && $user_disable_stripe === 'on' ) 
			){?>
            <div class="form-group tg-pay-radiobox tg-creditcard">
              <label>
                <input type="radio" value="stripe" name="payment">
                <span><?php esc_html_e('I will pay now through Credit Card.','docdirect');?></span></label>
            </div>
            <?php }?>
        </div>
		<?php
		$json['data']	 = ob_get_clean();
		$json['type']	 = 'success';
		$json['message']  = esc_html__('form returned','docdirect');
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_docdirect_get_booking_step_four','docdirect_get_booking_step_four');
	add_action( 'wp_ajax_nopriv_docdirect_get_booking_step_four', 'docdirect_get_booking_step_four' );
}

/**
 * @Booking Step 5
 * Thank You Message
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_booking_step_five' ) ) {
	function docdirect_get_booking_step_five($user_identity){
		global $current_user, $wp_roles,$userdata,$post;
		ob_start();
		$thank_you	     		= get_user_meta( $user_identity, 'thank_you', true);
		$schedule_message	     = get_user_meta( $user_identity, 'schedule_message', true);
		
		if( empty( $thank_you ) ){
			$thank_you	= esc_html__('Thank you for booking. We have received your booking. Soon we will get back to you.','docdirect');
		}
		?>
        <div class="bk-thanks-message">
            <div class="tg-message">
              <h2><?php esc_html_e('Thank you!','docdirect');?></h2>
              <div class="tg-description">
                <p><?php echo force_balance_tags( $thank_you );?></p>
              </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}

