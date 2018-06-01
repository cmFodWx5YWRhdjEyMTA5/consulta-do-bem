<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
$req = 'cmd=' . urlencode('_notify-validate');
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

define("DEBUG", 1);
define("USE_SANDBOX", 1);
define("LOG_FILE", "./ipn.log");

$args = array(
	'timeout' => 5,
	'redirection' => 5,
	'httpversion' => '1.0',
	'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url(home_url('/')),
	'blocking' => true,
	'headers' => array(),
	'cookies' => array(),
	'body' => $req,
	'compress' => false,
	'decompress' => true,
	'sslverify' => true,
	'stream' => false,
	'filename' => null
);
	
if ( isset( $_POST['payment_status'] ) && $_POST['payment_status'] == 'Completed' ) {
    $sandbox_enable = '';
	if (function_exists('fw_get_db_settings_option')) {
        $sandbox_enable = fw_get_db_settings_option('enable_sandbox');
    }
	
    if (isset($sandbox_enable) && $sandbox_enable == 'on') {
        $paypal_path = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    } else {
        $paypal_path = 'https://www.paypal.com/cgi-bin/webscr';
    }
    
   $res = wp_remote_get($paypal_path, $args);
   
   if (strcmp ($res, "VERIFIED") == 0) {
        
            global $current_user;
			
			$custom_var 	  =  esc_attr( $_POST['custom'] );
			$custom_var_array = explode('|_|',$custom_var);
			
			if(isset($custom_var_array['0'])){
				$order_id = $custom_var_array['0'];
			}
			if(isset($custom_var_array['1'])){
				$package_id = $custom_var_array['1'];
			}
			if(isset($custom_var_array['2'])){
				$user_identity = $custom_var_array['2'];
			}

			$user_email	= get_user_meta('email',$user_identity,true);
			$user_name	 = get_user_meta('first_name',$user_identity,true).' '.get_user_meta('last_name',$user_identity,true);
			$userdata	  = get_userdata($user_identity);
	
			if( !empty( $userdata->user_email ) ) {
				$user_email	= $userdata->user_email;
			} else{
				$user_email	= esc_attr( $_POST['receiver_email'] );
			}
			
			if( isset( $user_name ) && !empty( $user_name ) ) {
				$user_name	= $user_name;
			} else{
				$user_name	= esc_attr( $_POST['first_name'] ) . ' ' . esc_attr( $_POST['last_name'] );
			}
			
			//Update Order
            $expiry_date	= docdirect_update_order_data(
				array(
					'order_id'		    => $order_id,
					'user_identity'		=> $user_identity,
					'package_id'	    => $package_id,
					'txn_id'		    => esc_attr( $_POST['txn_id']),
					'payment_gross'		=> esc_attr( $_POST['mc_gross']),
					'payment_method'    => 'paypal',
					'mc_currency'	    => esc_attr( $_POST['mc_currency'] ),
				)
			);
			
			//Add Invoice
			docdirect_new_invoice(
				array(
					'user_identity'	    => $user_identity,
					'package_id'		=> $package_id,
					'txn_id'			=> esc_attr( $_POST['txn_id']),
					'payment_gross'		=> esc_attr( $_POST['mc_gross']),
					'item_name'		 	=> esc_attr( $_POST['item_name']),
					'payer_email'	    => esc_attr( $_POST['payer_email']),
					'mc_currency'	    => esc_attr( $_POST['mc_currency']),
					'address_name'	    => esc_attr( $_POST['address_name']),
					'ipn_track_id'	    => esc_attr( $_POST['ipn_track_id']),
					'transaction_status'=> 'approved',
					'payment_method'	=> 'paypal',
					'full_address'	    => esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']),
					'first_name'		=> esc_attr( $_POST['first_name']),
					'last_name'		    => esc_attr( $_POST['last_name']),
					'purchase_on'	    => date('Y-m-d H:i:s'),
				)
			);
			
			
			//Send ean email 
			if( class_exists( 'DocDirectProcessEmail' ) ) {
				$email_helper	= new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['mail_to']	  	   = $user_email;
				$emailData['name']			   = esc_attr( $_POST['first_name']).' '.esc_attr( $_POST['last_name']);
				$emailData['invoice']	  	   = esc_attr( $_POST['txn_id']);
				$emailData['package_name']	   = esc_attr( $_POST['item_name']);					
				$emailData['amount']			= esc_attr( $_POST['mc_currency']).esc_attr( $_POST['mc_gross']);
				$emailData['status']			= esc_html__('Approved','docdirect_core');
				$emailData['method']			= esc_html__('Paypal','docdirect_core');
				$emailData['date']			  = date('Y-m-d H:i:s');
				$emailData['expiry']			= $expiry_date;
				$emailData['address']		   = esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']);
				
				$email_helper->process_invoice_email($emailData);
			}
			
			//Make featured[Update Expiry date]

			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
			}
			
			//header('HTTP/1.1 200 OK'); 
			
    }
}

/*---------------------------------------------
* @Authorize Gateway Listner
* @Return {}
---------------------------------------------*/
if ( isset( $_POST['x_response_code'] ) && $_POST['x_response_code'] == '1' ) {
	
	$custom_var 	  =  esc_attr( $_POST['x_po_num'] );
	$custom_var_array = explode('|_|',$custom_var);
			
	if(isset($custom_var_array['0'])){
		$order_id = $custom_var_array['0'];
	}
	if(isset($custom_var_array['1'])){
		$package_id = $custom_var_array['1'];
	}
	if(isset($custom_var_array['2'])){
		$user_identity = $custom_var_array['2'];
	}
	
	$mc_currency   = fw_get_db_settings_option('currency_select');
	$user_email	= get_user_meta('email',$user_identity,true);
	$user_name	 = get_user_meta('first_name',$user_identity,true).' '.get_user_meta('last_name',$user_identity,true);
	$userdata	= get_userdata($user_identity);
	
	if( !empty( $userdata->user_email ) ) {
		$user_email	= $userdata->user_email;
	} else{
		$user_email	= esc_attr( $_POST['receiver_email'] );
	}
	
	if( isset( $user_name ) && !empty( $user_name ) ) {
		$user_name	= $user_name;
	} else{
		$user_name	= esc_attr( $_POST['x_first_name']) . ' ' . esc_attr( $_POST['x_last_name'] );
	}
	
	$payment_date = date('Y-m-d H:i:s');
	
	//Update Order
	$expiry_date	= docdirect_update_order_data(
		array(
			'order_id'		  	=> $order_id,
			'user_identity'	 	=> $user_identity,
			'package_id'		=> $package_id,
			'txn_id'			=> esc_attr( $_POST['txn_id']),
			'payment_gross'	 	=> esc_attr( $_POST['x_amount']),
			'payment_method'	=> 'authorize',
			'mc_currency'	    => isset( $mc_currency ) && !empty( $mc_currency ) ? $mc_currency :'USD',
		)
	);
	
	//Add Invoice
	docdirect_new_invoice(
		array(
			'user_identity'	=> $user_identity,
			'package_id'	=> $package_id,
			'txn_id'		=> esc_attr( $_POST['x_trans_id']),
			'payment_gross'	=> esc_attr( $_POST['x_amount']),
			'item_name'		=> esc_attr( $_POST['x_description']),
			'payer_email'	=> esc_attr( $_POST['payer_email']),
			'mc_currency'	=> isset( $mc_currency ) ? $mc_currency :'USD',
			'address_name'	=> esc_attr($_POST['x_address']).' '.esc_attr($_POST['x_city']).' '.esc_attr($_POST['x_country']),
			'ipn_track_id'	=> '',
			'transaction_status'	=> 'approved',
			'payment_method'	=> 'authorize',
			'full_address'	=> esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']),
			'first_name'	=> esc_attr( $_POST['x_first_name']),
			'last_name'	=> esc_attr( $_POST['x_last_name']),
			'purchase_on'	=> date('Y-m-d H:i:s'),
		)
	);
	
	
	
	//Send ean email 
	if( class_exists( 'DocDirectProcessEmail' ) ) {
		$email_helper	= new DocDirectProcessEmail();
		$emailData	= array();
		$emailData['mail_to']	  	   = $user_email;
		$emailData['name']			   = esc_attr( $_POST['x_first_name'] ).' '.esc_attr( $_POST['x_last_name'] );
		$emailData['invoice']	  	   = esc_attr( $_POST['x_trans_id'] );
		$emailData['package_name']	   = esc_attr( $_POST['x_description'] );					
		$emailData['amount']			= isset( $mc_currency ) ? $mc_currency :'USD'.esc_attr( $_POST['x_amount'] );
		$emailData['status']			= esc_html__('Approved','docdirect_core');
		$emailData['method']			= esc_html__('Authorize.net','docdirect_core');
		$emailData['date']			  = date('Y-m-d H:i:s');
		$emailData['expiry']			= $expiry_date;
		$emailData['address']		   = esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']);
		
		$email_helper->process_invoice_email($emailData);
	}
}
