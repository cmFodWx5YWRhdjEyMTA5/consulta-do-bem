<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg['page_builder'] = array(
	'title'       => esc_html__( 'Top Categories', 'docdirect' ),
	'description' => esc_html__( 'Show top categories', 'docdirect' ),
	'tab'         => esc_html__( 'DocDirect 4.0', 'docdirect' ),
	'popup_size'  => 'medium'
);