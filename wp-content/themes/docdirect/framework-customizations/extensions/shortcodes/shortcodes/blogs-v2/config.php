<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg = array(
	'page_builder' => array(
		'title' => esc_html__('Blogs V2', 'docdirect'),
		'description' => esc_html__('Display blogs carousel', 'docdirect'),
		'tab' => esc_html__('DocDirect 4.0', 'docdirect'),
		'popup_size' => 'small' // can be large, medium or small
	)
);