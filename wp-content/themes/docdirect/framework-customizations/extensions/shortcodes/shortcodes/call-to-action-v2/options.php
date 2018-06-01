<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'cta_title'  => array(
        'type'  => 'textarea',
        'label' => esc_html__('Title?', 'docdirect'),
    ),
	'cta_text'  => array(
        'type'  => 'textarea',
        'label' => esc_html__('Description?', 'docdirect'),
    ),
   'cta_button_text'  => array(
        'type'  => 'text',
		'value'  => 'Read more',
        'label' => esc_html__('Button Title', 'docdirect'),
		'desc'  => esc_html__('Leave it empty to hide button', 'docdirect'),
    ),
	'cta_button_link'  => array(
        'type'  => 'text',
		'value'  => '#',
        'label' => esc_html__('Button Link', 'docdirect'),
    ),
);