<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg['page_builder'] = array(
	'title'       => esc_html__( 'Directory', 'docdirect' ),
	'description' => esc_html__( 'Find Health Care By Categories', 'docdirect' ),
	'tab'         => esc_html__( 'DocDirect', 'docdirect' ),
	'popup_size'  => 'medium'
);