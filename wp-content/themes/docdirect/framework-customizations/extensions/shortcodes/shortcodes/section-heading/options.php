<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'heading'    => array(
		'type'  => 'text',
		'label' => esc_html__( 'Heading Title', 'docdirect' ),
		'desc'  => esc_html__( 'Write the heading title content', 'docdirect' ),
	),
	'description'    => array(
		'type'  => 'textarea',
		'label' => esc_html__( 'Description', 'docdirect' ),
		'desc'  => esc_html__( 'Write the Description content', 'docdirect' ),
	),
);
