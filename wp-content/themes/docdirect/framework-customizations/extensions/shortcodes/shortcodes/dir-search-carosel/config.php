<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array();

$cfg['page_builder'] = array(
	'title'       => esc_html__( 'Directory Search Carousel', 'docdirect' ),
	'description' => esc_html__( 'Add a Directory Search Carousel element', 'docdirect' ),
	'tab'         => esc_html__( 'DocDirect', 'docdirect' ),
	'popup_size'  => 'medium'
);