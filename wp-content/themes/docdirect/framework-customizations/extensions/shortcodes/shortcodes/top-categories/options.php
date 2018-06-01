<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
	'heading' => array(
		'label' => esc_html__('Heading', 'docdirect'),
		'type' => 'text',
		'value' => '',
		'desc' => esc_html__('Please add heading text', 'docdirect'),
	),
	'sub_heading' => array(
		'label' => esc_html__('Sub Heading', 'docdirect'),
		'type' => 'text',
		'value' => '',
		'desc' => esc_html__('Please add sub heading text', 'docdirect'),
	),
	'description' => array(
		'type'  => 'wp-editor',
        'value' => 'default value',
        'label' => esc_html__('Add desciption', 'docdirect'),
        'desc'  => esc_html__('Please add desciption here.', 'docdirect'),
	),
	'categories' => array(
         'type'       => 'multi-select',
        'label'      => esc_html__( 'Select categories Type', 'docdirect' ),
        'population' => 'posts',
        'source'     => 'directory_type',
        'desc'       => esc_html__( 'Show categories by selection. Leave it empty to show all categories.','docdirect' ),
    ),
);
