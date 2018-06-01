<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * Framework options
 *
 * @var array $options Fill this array with options to generate framework settings form in backend
 */

$payment_settings = array();
if( apply_filters('docdirect_get_theme_settings', 'payments') === 'custom' ){
	$payment_settings = array(
    	fw()->theme->get_options( 'payments-settings' )
	);
}

$options = array(
	fw()->theme->get_options( 'general-settings' ),
	fw()->theme->get_options( 'blog-settings' ),
	fw()->theme->get_options( 'colors-settings' ),
	fw()->theme->get_options( 'headers-settings' ),
	fw()->theme->get_options( 'subheaders-settings' ),
	fw()->theme->get_options( 'footer-settings' ),
    fw()->theme->get_options( 'typo-settings' ),
    fw()->theme->get_options( 'social-settings' ),
	fw()->theme->get_options( 'social-sharing-settings' ),
    fw()->theme->get_options( 'commingsoon-settings' ),
	fw()->theme->get_options( 'directory-settings' ),
	$payment_settings,
	fw()->theme->get_options( 'booking-settings' ),
	fw()->theme->get_options( 'notification-settings' ),
	fw()->theme->get_options( 'api-settings' ),
	fw()->theme->get_options( 'captcha-settings' ),
);
