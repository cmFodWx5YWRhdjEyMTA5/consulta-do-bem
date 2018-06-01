<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
    'textblock_description'  => array(
        'type'  => 'wp-editor',
        'value' => 'default value',
        'label' => esc_html__('Add HTML', 'docdirect'),
        'desc'  => esc_html__('Please add raw html code in text editor', 'docdirect'),
    ),
);