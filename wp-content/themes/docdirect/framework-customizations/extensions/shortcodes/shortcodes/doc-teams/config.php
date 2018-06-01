<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg['page_builder'] = array(
	'title'       => esc_html__( 'Teams', 'docdirect' ),
	'description' => esc_html__( 'Add a Team Member', 'docdirect' ),
	'tab'         => esc_html__( 'DocDirect', 'docdirect' ),
	'popup_size'  => 'medium'
);