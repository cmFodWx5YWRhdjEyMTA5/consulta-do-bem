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
        $sandbox_enable = fw_get_db_settings_option('user_enable_sandbox');
    }
	
	if (isset($sandbox_enable) && $sandbox_enable == 'on') {
        $paypal_path = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    } else {
        $paypal_path = 'https://www.paypal.com/cgi-bin/webscr';
    }
    
   $res = wp_remote_get($paypal_path, $args);
   
   if (strcmp ($res, "VERIFIED") == 0) {
        
            global $current_user;
			$post_id 	=  esc_attr( $_POST['custom'] );
			
			$old_values 	= (array)fw_get_db_post_option($post_id);
			
			$new_values	= array();
			$new_values['bk_code']	= esc_attr( $_POST['txn_id'] );
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
			

			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
			}
			
			//header('HTTP/1.1 200 OK'); 
			
    }
}
