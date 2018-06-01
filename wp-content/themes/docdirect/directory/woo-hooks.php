<?php

/**
 * @Woocommerce order hooks
 * return {}
 */
/**
 * @Check user role
 * @return 
 */

if (!function_exists('docdirect_payment_complete')) {
    add_action('woocommerce_payment_complete', 'docdirect_payment_complete');
	add_action( 'woocommerce_order_status_completed','docdirect_payment_complete' );
    function docdirect_payment_complete($order_id) {
		global $current_user, $wpdb;
		
        $order = wc_get_order($order_id);

        $user = $order->get_user();
        $items = $order->get_items();
        $offset = get_option('gmt_offset') * intval(60) * intval(60);
		$invoice_id = esc_html__('Order #','docdirect') . ' ' . $order_id;
		$currency_code = $order->get_currency();
		$currency_symbol = get_woocommerce_currency_symbol( $currency_code );

        foreach ($items as $key => $item) {
            $product_id = $item['product_id'];
            $product_qty = !empty($item['qty']) ? $item['qty'] : 1;

            if ($user) {
				
				$payment_type = wc_get_order_item_meta( $key, 'payment_type', true );
				if( !empty( $payment_type ) && $payment_type === 'booking' ){
					//booking meta
				} else{
					

					$payment_date 		= date('Y-m-d H:i:s');
					$user_featured_date = get_user_meta( $user->ID, 'user_featured', true);
					$offset = get_option('gmt_offset') * intval(60) * intval(60);
					$payment_date	= strtotime($payment_date) + $offset;

					//Custom Packages Listings managment
					if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
						$featured_date	= date('Y-m-d H:i:s');
						
						$dd_duration 	 = get_post_meta($product_id, 'dd_duration', true);
						$dd_featured 	 = get_post_meta($product_id, 'dd_featured', true);
						$dd_appointments = get_post_meta($product_id, 'dd_appointments', true);
						$dd_banner 		 = get_post_meta($product_id, 'dd_banner', true);
						$dd_insurance 	 = get_post_meta($product_id, 'dd_insurance', true);
						$dd_favorites 	 = get_post_meta($product_id, 'dd_favorites', true);
						$dd_teams 		 = get_post_meta($product_id, 'dd_teams', true);
						$dd_hours 		 = get_post_meta($product_id, 'dd_hours', true);
						
						//If extension is enabled
						if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {
							$dd_articles 	 = get_post_meta($product_id, 'dd_articles', true);
						}else{
							$dd_articles 	 = 0;
						}
						
						//If extension is enabled
						if ( function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
							$dd_qa 	 		 = get_post_meta($product_id, 'dd_qa', true);
						} else{
							$dd_qa 	 		 = 'no';
						}
						
						//Featured
						if( !empty( $user_featured_date ) && $user_featured_date >  $payment_date ){
							$duration = get_post_meta($product_id, 'dd_featured', true); //no of days for a feature listings
							if( $duration > 0 ){
								$featured_date	= strtotime("+".$duration." days", $user_featured_date);
								$featured_date	= date('Y-m-d H:i:s',$featured_date);
							}
						} else{
							$current_date	= date('Y-m-d H:i:s');
							$duration = get_post_meta($product_id, 'dd_featured', true);//no of days for a feature listings
							if( $duration > 0 ){
								$featured_date		 = strtotime("+".$duration." days", strtotime($current_date));
								$featured_date	     = date('Y-m-d H:i:s',$featured_date);
							}
						}

						//Package Expiry
						$package_expiry	= date('Y-m-d H:i:s');
						$user_package_expiry    = get_user_meta( $user->ID, 'user_current_package_expiry', true);

						if( !empty( $user_package_expiry ) && $user_package_expiry >  $payment_date ){
							$package_duration = get_post_meta($product_id, 'dd_duration', true);
							if( $package_duration > 0 ){
								$package_expiry	= strtotime("+".$package_duration." days", $user_package_expiry);
								$package_expiry	= date('Y-m-d H:i:s',$package_expiry);
							}
						} else{
							$current_date	= date('Y-m-d H:i:s');
							$package_duration = get_post_meta($product_id, 'dd_duration', true);
							if( $package_duration > 0 ){
								$package_expiry		 = strtotime("+".$package_duration." days", strtotime($current_date));
								$package_expiry	     = date('Y-m-d H:i:s',$package_expiry);
							}
						}

						$package_expiry = strtotime($package_expiry) + $offset;
						update_user_meta($user->ID,'user_current_package_expiry',$package_expiry); //package duration

					} else{
						
						$dd_duration 	 = get_post_meta($product_id, 'dd_duration', true);
						$dd_featured 	 = '';
						$dd_appointments = '';
						$dd_banner 		 = 'no';
						$dd_insurance 	 = 'no';
						$dd_favorites 	 = 'no';
						$dd_teams 		 = 'no';
						$dd_hours 		 = 'no';
						$dd_articles 	 = 0;
						$dd_qa 	 		 = 'no';
						
						//Feature Listng For default settings
						$featured_date	= date('Y-m-d H:i:s');

						if( !empty( $user_featured_date ) && $user_featured_date >  $payment_date ){
							$duration = get_post_meta($product_id, 'dd_duration', true);
							if( $duration > 0 ){
								$featured_date	= strtotime("+".$duration." days", $user_featured_date);
								$featured_date	= date('Y-m-d H:i:s',$featured_date);
							}
						} else{
							$current_date	= date('Y-m-d H:i:s');
							$duration = get_post_meta($product_id, 'dd_duration', true);
							if( $duration > 0 ){
								$featured_date		 = strtotime("+".$duration." days", strtotime($current_date));
								$featured_date	     = date('Y-m-d H:i:s',$featured_date);
							}
						}
						
						$package_expiry = strtotime($featured_date) + $offset;
						update_user_meta($user->ID,'user_current_package_expiry',$package_expiry); //package duration
					}

					$featured_date = strtotime($featured_date) + $offset;
					update_user_meta($user->ID,'user_featured',$featured_date); //featured Expiry
					update_user_meta($user->ID,'user_current_package',$product_id); //Current package
					
					
					//update data
					$package_data = array(
						'subscription_id' 				=> $product_id,
						'subscription_expiry' 			=> $package_expiry,
						'subscription_featured_expiry' 	=> $featured_date,
						'subscription_appointments' 	=> $dd_appointments,
						'subscription_profile_banner' 	=> $dd_banner,
						'subscription_insurance' 		=> $dd_insurance,
						'subscription_favorites' 		=> $dd_favorites,
						'subscription_teams' 			=> $dd_teams,
						'subscription_business_hours' 	=> $dd_hours,
						'subscription_articles'  		=> $dd_articles,
						'subscription_questions'  		=> $dd_qa,
					);

					update_user_meta($user->ID, 'dd_subscription', $package_data);
					
					//Prepare Email Data.
					$product 		= wc_get_product($product_id);
					$invoice_id 	= esc_html__('Order #','docdirect') . '&nbsp;' . $order_id;
					$package_name 	= $product->get_title();
					$amount = $product->get_price();
					$status = $order->get_status();
					$method = $order->payment_method;
					$name 	= $order->billing_first_name . '&nbsp;' . $order->billing_last_name;

					//Get UTC Time Format
					$expiry_package_date = date_i18n('Y-m-d H:i:s', $package_expiry);

					//Get UTC Time Format
					$order_timestamp = strtotime($order->order_date);
					$order_local_timestamp = $order_timestamp + $offset;
					$order_date = date_i18n('Y-m-d H:i:s', $order_local_timestamp);

					$billing_address = $order->get_formatted_billing_address();
					$mail_to = $order->billing_email;


					//Send ean email 
					if( class_exists( 'DocDirectProcessEmail' ) ) {
						$email_helper	= new DocDirectProcessEmail();
						$emailData	= array();
						$emailData['mail_to']	  	    = $mail_to;
						$emailData['name']			    = docdirect_get_username($user->ID);
						$emailData['invoice']	  	    = $invoice_id;
						$emailData['package_name']	    = $package_name;					
						$emailData['amount']			= $currency_symbol.$amount;
						$emailData['status']			= $status;
						$emailData['method']			= $method;
						$emailData['date']			    = date('Y-m-d H:i:s');
						$emailData['expiry']			= $expiry_package_date;
						$emailData['address']		    = $billing_address;

						$email_helper->process_invoice_email($emailData);
					}
					
				}
            }
        }
    }

}

/**
 * @remove payment gateway
 * @return 
 */
if (!function_exists('docdirect_unused_payment_gateways')) {
    //add_filter('woocommerce_payment_gateways', 'docdirect_unused_payment_gateways', 20, 1);
    function docdirect_unused_payment_gateways($load_gateways) {
        $remove_gateways = array(
            'WC_Gateway_BACS',
            'WC_Gateway_Cheque',
            'WC_Gateway_COD',
        );
        foreach ($load_gateways as $key => $value) {
            if (in_array($value, $remove_gateways)) {
                unset($load_gateways[$key]);
            }
        }
        return $load_gateways;
    }

}

/**
 * @remove product types
 * @return 
 */
if (!function_exists('docdirect_remove_product_types')) {
    add_filter('product_type_selector', 'docdirect_remove_product_types');

    function docdirect_remove_product_types($types) {
        unset($types['grouped']);
        unset($types['external']);
        unset($types['variable']);

        return $types;
    }

}

/**
 * @remove tabs settings
 * @return 
 */
if (!function_exists('docdirect_remove_product_setting_tabs')) {
    add_filter('woocommerce_product_data_tabs', 'docdirect_remove_product_setting_tabs', 10, 1);

    function docdirect_remove_product_setting_tabs($tabs) {
        unset($tabs['inventory']);
        unset($tabs['shipping']);
        unset($tabs['linked_product']);
        unset($tabs['attribute']);
        unset($tabs['advanced']);
        return($tabs);
    }

}

/**
 * @get subscription meta
 * @return 
 */
if (!function_exists('docdirect_get_subscription_meta')) {

    function docdirect_get_subscription_meta($key = '', $user_id) {
        $dd_subscription = get_user_meta($user_id, 'dd_subscription', true);

        if (isset($dd_subscription[$key]) && $dd_subscription[$key] != '') {
            return $dd_subscription[$key];
        }

        return '';
    }

}

/**
 * @get package meta
 * @return 
 */
if (!function_exists('docdirect_get_package_features')) {

    function docdirect_get_package_features($key) {
        $features = array(
			'dd_duration' 		=> esc_html__( 'Package Duration', 'docdirect' ), 
			'dd_featured' 		=> esc_html__( 'Featured duration', 'docdirect' ), 
			'dd_articles' 		=> esc_html__( 'Articles included?', 'docdirect' ), 
			'dd_appointments' 	=> esc_html__( 'Appointments included?', 'docdirect' ), 
			'dd_banner' 		=> esc_html__( 'Profile banner included?', 'docdirect' ), 
			'dd_insurance' 		=> esc_html__( 'Insurance included?', 'docdirect' ), 
			'dd_favorites' 		=> esc_html__( 'favorites included?', 'docdirect' ), 
			'dd_teams' 			=> esc_html__( 'Teams included?', 'docdirect' ), 
			'dd_hours' 			=> esc_html__( 'Business hours included?', 'docdirect' ),
			'dd_photo' 			=> esc_html__( 'Gallery photos included?', 'docdirect' ),
			'dd_qa' 			=> esc_html__( 'Question and answers included?', 'docdirect' ),
			'dd_videos' 		=> esc_html__( 'Video link included?', 'docdirect' ),
			
			'apt_description' 	=> esc_html__( 'Message', 'docdirect' ),
			'apt_name' 			=> esc_html__( 'Name', 'docdirect' ),
			'apt_mobile' 		=> esc_html__( 'Phone', 'docdirect' ),
			'apt_email' 		=> esc_html__( 'Email Address', 'docdirect' ),
			'apt_location'  	=> esc_html__( 'Location', 'docdirect' ),
			'title'  			=> esc_html__( 'Service', 'docdirect' ),
			'reason' 			=> esc_html__( 'Booking Reason', 'docdirect' ),
			'type' 				=> esc_html__( 'Booking Type', 'docdirect' ),
			'apt_time'  		=> esc_html__( 'Booking Time', 'docdirect' ),
			'apt_date'  		=> esc_html__( 'Booking Date', 'docdirect' ),
			'apt_number'  		=> esc_html__( 'Appointment Number', 'docdirect' ),
			'apt_admin_shares'  		=> esc_html__( 'Admin Shares', 'docdirect' ),
			'apt_provider_shares'  		=> esc_html__( 'Provider Shares', 'docdirect' ),
			
        );
		
		if( !empty( $features[$key] ) ){
			return $features[$key];
		} else{
			return '';
		}
    }
}


/**
 * @Display order detail t cgeckout page
 * @return 
 */
if (!function_exists('docdirect_add_new_fields_checkout')) {
	add_filter( 'woocommerce_checkout_after_customer_details', 'docdirect_add_new_fields_checkout', 10, 1 );
	function docdirect_add_new_fields_checkout() {
		global $product,$woocommerce;
		$cart_data = WC()->session->get( 'cart', null );

		if( !empty( $cart_data ) ) {
			foreach( $cart_data as $key => $cart_items ){
				$quantity	= !empty( $cart_items['quantity'] ) ?  $cart_items['quantity'] : 1;

				if( !empty( $cart_items['cart_data'] ) ){
					
					if( !empty( $cart_items['payment_type'] ) && $cart_items['payment_type'] === 'booking' ){
						$cart_items['cart_data']	= apply_filters('docdirect_get_booking_meta', $cart_items['cart_data']);					
					}
				?>
				<div class="col-md-12">
					<div class="row">
						<div class="cart-data-wrap">
						  <h3><?php echo get_the_title($cart_items['product_id']);?><span class="cus-quantity">×<?php echo esc_attr( $quantity );?></span></h3>
						  <div class="selection-wrap">
							<?php 
								$counter	= 0;
								foreach( $cart_items['cart_data'] as $key => $value ){
									$counter++;
								?>
									<div class="cart-style"> 
										<span class="style-lable"><?php echo docdirect_get_package_features($key);?></span> 
										<span class="style-name"><?php echo esc_attr( $value );?></span> 
									</div>
								<?php }?>
						  </div>
						</div>
					</div>
				 </div>	
				<?php
				}
			}
		}
	}
}

/**
 * @save into meta
 * @return 
 */
if (!function_exists('docdirect_woo_convert_item_session_to_order_meta')) {
	add_action( 'woocommerce_add_order_item_meta', 'docdirect_woo_convert_item_session_to_order_meta', 10, 3 ); //Save cart data
	function docdirect_woo_convert_item_session_to_order_meta( $item_id, $values, $cart_item_key ) {
		$cart_key				= 'cart_data';
		$cart_type_key			= 'payment_type';
		$apt_admin_shares		= 'admin_shares';
		$apt_provider_shares	= 'provider_shares';
		
		$cart_item_data = docdirect_woo_get_item_data( $cart_item_key,$cart_key );
		$cart_item_type = docdirect_woo_get_item_data( $cart_item_key,$cart_type_key );
		//$admin_shares = docdirect_woo_get_item_data( $cart_item_key,$apt_admin_shares );
		//$provider_shares = docdirect_woo_get_item_data( $cart_item_key,$apt_provider_shares );
		// Add the array of all meta data to "_ld_woo_product_data". These are hidden, and cannot be seen or changed in the admin.
		if ( !empty( $cart_item_data ) ) {
			wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $cart_item_data );
		}
		
		if ( !empty( $cart_item_type ) ) {
			wc_add_order_item_meta( $item_id, 'payment_type', $cart_item_type );
		}
		
		//wc_add_order_item_meta( $item_id, 'admin_shares', $admin_shares );
		//wc_add_order_item_meta( $item_id, 'provider_shares', $provider_shares );
	}
}

/**
 * get woo session data
 *
 */
if (!function_exists('docdirect_woo_get_item_data')) {
	function docdirect_woo_get_item_data( $cart_item_key, $key = null, $default = null ) {
		global $woocommerce;

		$data = (array)WC()->session->get( 'cart',$cart_item_key );
		if ( empty( $data[$cart_item_key] ) ) {
			$data[$cart_item_key] = array();
		}

		// If no key specified, return an array of all results.
		if ( $key == null ) {
			return $data[$cart_item_key] ? $data[$cart_item_key] : $default;
		}else{
			return empty( $data[$cart_item_key][$key] ) ? $default : $data[$cart_item_key][$key];
		}
	}
}


// Display order detail
if (!function_exists('docdirect_display_order_data')) {
	add_action( 'woocommerce_thankyou', 'docdirect_display_order_data', 20 ); 
	add_action( 'woocommerce_view_order', 'docdirect_display_order_data', 20 );
	function docdirect_display_order_data( $order_id ) {
		global $product,$woocommerce,$wpdb;
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		if( !empty( $items ) ) {
			$counter	= 0;
			foreach( $items as $key => $order_item ){
				$counter++;
				$item_id    = $order_item['product_id'];
				$name		= !empty( $order_item['name'] ) ?  $order_item['name'] : '';
				$quantity	= !empty( $order_item['qty'] ) ?  $order_item['qty'] : 5;
				$order_detail = wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
				$payment_type = wc_get_order_item_meta( $key, 'payment_type', true );
				
				if( !empty( $order_detail ) ) {
					
					if( !empty( $payment_type ) && $payment_type === 'booking' ){
						$order_detail	= apply_filters('docdirect_get_booking_meta', $order_detail);					
					}
					
					?>
					<div class="col-md-12">
						<div class="row">
							<div class="cart-data-wrap">
							  <h3><?php echo esc_attr($name);?><span class="cus-quantity">×<?php echo esc_attr( $quantity );?></span></h3>
							  <div class="selection-wrap">
								<?php 
									$counter	= 0;
									foreach( $order_detail as $key => $value ){
										$counter++;
									?>
										<div class="cart-style"> 
											<span class="style-lable"><?php echo docdirect_get_package_features($key);?></span> 
											<span class="style-name"><?php echo esc_attr( $value );?></span> 
										</div>
									<?php }?>
							  </div>
							</div>
						</div>
					 </div>	
				<?php
				}
			}
		}
	}
}

 
/**
 *Print order meta at back-end in order detail page
 *
 * @since 1.0
*/
if (!function_exists('docdirect_woo_order_meta')) {
	add_filter( 'woocommerce_after_order_itemmeta', 'docdirect_woo_order_meta', 10, 3 );
	function docdirect_woo_order_meta( $item_id, $item, $_product ) {
		global $product,$woocommerce,$wpdb;
		$order_detail = wc_get_order_item_meta( $item_id, 'cus_woo_product_data', true );
		
		$order_item = new WC_Order_Item_Product($item_id);
		$order	= $order_item->get_order();
		$order_status	= $order->get_status();
  		$customer_user = get_post_meta( $order->get_id(), '_customer_user', true );
		$dd_subscription = get_user_meta( $customer_user, 'dd_subscription', true );
		$payment_type 	 = wc_get_order_item_meta( $item_id, 'payment_type', true );

		if( !empty( $payment_type ) && $payment_type === 'booking' ){
			$order_detail	= apply_filters('docdirect_get_booking_meta', $order_detail);					
		}

		if( !empty( $order_detail ) ) {?>
			<div class="order-edit-wrap">
				<div class="view-order-detail">
					<a href="javascript:;" data-target="#cus-order-modal-<?php echo esc_attr( $item_id );?>" class="cus-open-modal cus-btn cus-btn-sm"><?php esc_html_e('View order detail?','docdirect');?></a>
				</div>
				<div class="cus-modal" id="cus-order-modal-<?php echo esc_attr( $item_id );?>">
					<div class="cus-modal-dialog">
						<div class="cus-modal-content">
							<div class="cus-modal-header">
								<a href="javascript:;" data-target="#cus-order-modal-<?php echo esc_attr( $item_id );?>" class="cus-close-modal">×</a>
								<h4 class="cus-modal-title"><?php esc_html_e('Order Detail','docdirect');?></h4>
							</div>
							<div class="cus-modal-body">
								<div class="sp-order-status">
									<p><?php echo ucwords( $order_status );?></p>
								</div>
								<?php if( !empty( $payment_type ) && $payment_type === 'subscription' ){?>
								<div class="cus-options-data sp-up">
									<label><span><?php esc_html_e('Upgrade Package','docdirect');?></span></label>
									<div class="step-value">
										<span><a target="_blank" href="<?php echo get_edit_user_link( $customer_user ) ?>?#sp-pkgexpireyandcounter"><?php esc_html_e('Upgrade','docdirect');?></a></span>
									</div>
								</div>
								<?php }?>
								<div class="cus-form cus-form-change-settings">
									<div class="edit-type-wrap">
										<?php 
										$counter	= 0;
										foreach( $order_detail as $key => $value ){
											$counter++;
										?>
										<div class="cus-options-data">
											<label><span><?php echo docdirect_get_package_features($key);?></span></label>
											<div class="step-value">
												<span><?php echo esc_attr( $value );?></span>
											</div>
										</div>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php						
		}
	}
}


/**
 *Order Email
 * @since 1.0
 */
if (!function_exists('docdirect_add_order_meta_email')) {
	add_action( 'woocommerce_email_before_order_table', 'docdirect_add_order_meta_email', 10, 2 );
	function docdirect_add_order_meta_email( $order, $sent_to_admin ) {
		global $product,$woocommerce,$wpdb;
		$order_id	= $order->id;
		$order = new WC_Order( $order_id );
		$items = $order->get_items();			
		
		if( !empty( $items ) ) {
			$counter	= 0;
			foreach( $items as $key => $order_item ){
				$counter++;
				$item_id    = $order_item['product_id'];
				$name		= !empty( $order_item['name'] ) ?  $order_item['name'] : '';
				$quantity	= !empty( $order_item['qty'] ) ?  $order_item['qty'] : 1;
				$order_detail = wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
				$payment_type = wc_get_order_item_meta( $key, 'payment_type', true );
				
				if( !empty( $order_detail ) ) {
					
					if( !empty( $payment_type ) && $payment_type === 'booking' ){
						$order_detail	= apply_filters('docdirect_get_booking_meta', $order_detail);					
					}
				?>
				<table class="cus-table" style="background:#fbfbfb; margin:auto 0; width:600px; border-spacing:0; border-radius: 3px;">
					<tbody>
						<tr style="text-align:left; border:0; line-height: 2.5; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
							<td scope="col" style="text-align:left; padding:0 15px; border:0; border-bottom:1px solid #ececec; line-height: 2.5; font-size: 20px; font-weight: bold;"><?php echo esc_attr($name);?><span class="cus-quantity">×<?php echo esc_attr( $quantity );?></span></td>
						</tr>
						<tr style="text-align:left; border:0; line-height: 2.5; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
							<td scope="col" style="text-align:left; padding:0 15px; border:0; border-bottom:1px solid #ececec; line-height: 2.5;"><strong><?php echo esc_html__('Order Detail','docdirect');?></strong></td>
						</tr>
						<tr style="text-align:left; border:0; line-height: 2.5; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
							<td scope="col" style="text-align:left; padding:0 0; border:0; border-bottom:1px solid #ececec; line-height: 2.5;">
								<table style="width:100%; margin:0; border-spacing:0;">
									<tbody>
										<?php 
										$counter	= 0;
										foreach( $order_detail as $key => $value ){
											$counter++;
										?>
										<tr style="line-height: 2.5; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
											<td scope="col" style="width:50%; text-align:left; padding:0 15px; border:0; border-bottom:1px solid #ececec; border-right:1px solid #ececec; line-height: 2.5;"><?php echo docdirect_get_package_features($key);?></td>
											<td scope="col" style="width:50%; text-align:left; padding:0 15px; border:0; border-bottom:1px solid #ececec; line-height: 2.5;"><?php echo esc_attr($value);?></td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<?php 
				}
			}
		}
	}
}

/**
 * Add product types for bookings
 * @since 1.0
 */
if (!function_exists('docdirect_product_type_options')) {
	//add_filter('product_type_options', 'docdirect_product_type_options', 10, 1);
	function docdirect_product_type_options( $options ) {
		if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
			$options['docdirect_appointment'] = array(
				'id' => '_docdirect_appointment',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label' => esc_html__('Book Appointments', 'docdirect'),
				'description' => esc_html__('Book Appointment products will be use for booking/appointment payment', 'docdirect'),
				'default' => 'no'
			);
		}

		return $options;
	}
}

/**
 * save product type
 * @since 1.0
 */
if (!function_exists('docdirect_woocommerce_process_product_meta')) {
	//add_action('woocommerce_process_product_meta_variable', 'docdirect_woocommerce_process_product_meta', 10, 1);
	//add_action('woocommerce_process_product_meta_simple', 'docdirect_woocommerce_process_product_meta', 10, 1);
	function docdirect_woocommerce_process_product_meta( $post_id ) {
		if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
			docdirect_update_booking_product(); //update default booking product

			$is_docdirect_appointment	= isset($_POST['_docdirect_appointment']) ? 'yes' : 'no';
			update_post_meta($post_id, '_docdirect_appointment', $is_docdirect_appointment);
		}
	}
}

/**
 * price override
 * @since 1.0
 */
if (!function_exists('docdirect_apply_custom_price_to_cart_item')) {
	
	//add_action( 'woocommerce_before_calculate_totals', 'docdirect_apply_custom_price_to_cart_item', 99 );
	function docdirect_apply_custom_price_to_cart_item( $cart_object ) {  
		if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
			if( !WC()->session->__isset( "reload_checkout" )) {
				foreach ( $cart_object->cart_contents as $key => $value ) {
					if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'booking' ){
						if( isset( $value['cart_data']['price'] ) ){
							$bk_price = floatval( $value['cart_data']['price'] );
							$value['data']->set_price($bk_price);
						}
					}
				}   
			}
		}
	}
}


/**
 * @dupdate cart
 * @return 
 */
if (!function_exists('docdirect_update_cart')) {

    function docdirect_update_cart() {
        global $current_user, $woocommerce;

        if (!empty($_POST['id'])) {
            $product_id = intval($_POST['id']);
			$cart_meta		= array();
			
			if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
				$dd_duration 		= get_post_meta( $product_id, 'dd_duration', true );
				$dd_favorites 		= get_post_meta( $product_id, 'dd_favorites', true );
				$dd_featured		= get_post_meta( $product_id, 'dd_featured', true );
				$dd_appointments 	= get_post_meta( $product_id, 'dd_appointments', true );
				$dd_banner 			= get_post_meta( $product_id, 'dd_banner', true );
				$dd_insurance 		= get_post_meta( $product_id, 'dd_insurance', true );
				$dd_teams 			= get_post_meta( $product_id, 'dd_teams', true );
				$dd_hours 			= get_post_meta( $product_id, 'dd_hours', true );
				$dd_qa 				= get_post_meta( $product_id, 'dd_qa', true );
				$dd_articles 				= get_post_meta( $product_id, 'dd_articles', true );

				$cart_meta['dd_duration']		= $dd_duration.' '.esc_html__( 'days','docdirect' );
				$cart_meta['dd_featured']		=esc_html__( 'Featured listing for','docdirect' ).' '.$dd_featured.' '.esc_html__( 'day(s)','docdirect' );
				$cart_meta['dd_appointments']	= $dd_appointments === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No','docdirect' );
				$cart_meta['dd_banner']		= $dd_banner === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No','docdirect' );
				$cart_meta['dd_insurance']	= $dd_insurance === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No','docdirect' );
				$cart_meta['dd_favorites']	= $dd_favorites === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No ','docdirect' );
				$cart_meta['dd_teams']		= $dd_teams === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No','docdirect' );
				$cart_meta['dd_hours']		= $dd_hours === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No','docdirect' );
				
				if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {
					$cart_meta['dd_articles']		= esc_html__( 'Number of articles','docdirect' ).' '.$dd_articles;
				}
				
				if ( function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
					$cart_meta['dd_qa']			= $dd_qa === 'yes' ? esc_html__( 'Yes','docdirect' ) :  esc_html__( 'No','docdirect' );
				}
				
			} else{
				$dd_duration 					= get_post_meta( $product_id, 'dd_duration', true );
				$cart_meta['dd_featured']		= esc_html__( 'Featured listing for','docdirect' ).' '.$dd_duration.' '.esc_html__( 'day(s)','docdirect' );
			}
			
			
            $cart_data = array(
                'product_id' 	=> $product_id,
				'cart_data'     => $cart_meta,
				'payment_type'  => 'subscription',
            );
			
            if (class_exists('WooCommerce')) {

                $woocommerce->cart->empty_cart();
                $cart_item_data = $cart_data;
                WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

                $json = array();
                $json['type'] = 'success';
                $json['message'] = esc_html__('Please wait you are redirecting to checkout page.', 'docdirect');
                $json['checkout_url'] = esc_url($woocommerce->cart->get_checkout_url());
                echo json_encode($json);
                die();
            } else {
                $json = array();
                $json['type'] = 'error';
                $json['message'] = esc_html__('Please install WooCommerce plugin to process this order', 'docdirect');
            }
        }

        $json = array();
        $json['type'] = 'error';
        $json['message'] = esc_html__('Oops! something is going wrong.', 'docdirect');
        echo json_encode($json);
        die();
    }

    add_action('wp_ajax_docdirect_update_cart', 'docdirect_update_cart');
    add_action('wp_ajax_nopriv_docdirect_update_cart', 'docdirect_update_cart');
}

/**
 * @Add http from URL
 * @return {}
 */
if (!function_exists('docdirect_matched_cart_items')) {

    function docdirect_matched_cart_items($product_id) {
        // Initialise the count
        $count = 0;

        if (!WC()->cart->is_empty()) {
            foreach (WC()->cart->get_cart() as $cart_item):
                $items_id = $cart_item['product_id'];

                // for a unique product ID (integer or string value)
                if ($product_id == $items_id) {
                    $count++; // incrementing the counted items
                }
            endforeach;
            // returning counted items 
            return $count;
        }

        return $count;
    }
}

/**
 * @Add http from URL
 * @return {}
 */
if (!function_exists('docdirect_my_account_menu_items')) {
	add_filter( 'woocommerce_account_menu_items', 'docdirect_my_account_menu_items' );
	function docdirect_my_account_menu_items( $items ) {
		unset($items['dashboard']);
		unset($items['downloads']);
		unset($items['edit-address']);
		unset($items['payment-methods']);
		unset($items['edit-account']);
		return $items;
	}
}