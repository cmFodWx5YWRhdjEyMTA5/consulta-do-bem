<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

if (function_exists('fw_get_db_settings_option')) {
	$currency_select = fw_get_db_settings_option('currency_select');
	$currency_sign = fw_get_db_settings_option('currency_sign');
	$paypal_enable = fw_get_db_settings_option('paypal_enable');
	$authorize_enable = fw_get_db_settings_option('authorize_enable');
} else{
	$currency_select = 'USD';
	$currency_sign = '$';
	$paypal_enable = '';
	$authorize_enable = '';
}

if( empty( $currency_select ) ){
	$currency_select = 'USD';
	$currency_sign = '$';
}


?>
<div class="tg-haslayout tg-myaccount">
	<div class="tg-system-packages">
		<?php 
			if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
				get_template_part('directory/templates/user','woo-invoices');
				get_template_part('directory/templates/user','woo-payments');
			} else{
				get_template_part('directory/templates/user','invoices');
				if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
					get_template_part('directory/templates/user','limitations-packages');
				} else{
					get_template_part('directory/templates/user','packages');
				}
			}
		?>
	</div>
</div>