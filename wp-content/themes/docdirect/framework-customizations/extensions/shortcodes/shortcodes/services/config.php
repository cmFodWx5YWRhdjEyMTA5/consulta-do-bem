<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg = array(
	'page_builder' => array(
		'title' => esc_html__('Services', 'docdirect'),
		'description' => esc_html__('Display Services.', 'docdirect'),
		'tab' => esc_html__('DocDirect', 'docdirect'),
		'popup_size' => 'small' // can be large, medium or small
	)
);