<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg = array(
	'page_builder' => array(
		'title' => esc_html__('Call To Action', 'docdirect'),
		'description' => esc_html__('Display Call To Action', 'docdirect'),
		'tab' => esc_html__('DocDirect 4.0', 'docdirect'),
		'popup_size' => 'small' // can be large, medium or small
	)
);