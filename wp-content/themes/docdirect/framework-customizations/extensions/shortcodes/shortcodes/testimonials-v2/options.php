<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'heading' => array(
        'type' => 'text',
        'label' => esc_html__('Heading', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'sub_heading' => array(
        'type' => 'text',
        'label' => esc_html__('Sub Heading', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'description' => array(
		'label' => esc_html__('Description', 'docdirect'),
		'type' => 'textarea',
		'value' => '',
		'desc' => esc_html__('Add short description', 'docdirect'),
	),
	'auto' => array(
		'label' => esc_html__('Autoplay', 'docdirect'),
		'type' => 'select',
		'value' => 'true',
		'choices'	=> array(
			'true'	=> esc_html__('Yes', 'docdirect'),
			'false'	=> esc_html__('No', 'docdirect'),
		),
		'desc' => esc_html__('Autoplay testimonials?', 'docdirect'),
	),
	'data' => array(
		'type' => 'addable-box',
		'value' => array(
			array(
				'name' => esc_html__('Name', 'docdirect'),
				'designation' => esc_html__('Designation', 'docdirect'),
				'description' => esc_html__('Designation', 'docdirect'),
				'image' => esc_html__('Image', 'docdirect'),
				'video_url' => esc_html__('Video URL', 'docdirect'),
			),
		),
		'label' => esc_html__('Add review', 'docdirect'),
		'desc' => esc_html__('Add reviews here.', 'docdirect'),
		'box-options' => array(
			'name' => array(
				'type' => 'text',
				'desc' => esc_html__('Add name here.', 'docdirect'),
			),
			'designation' => array(
				'type' => 'text',
				'desc' => esc_html__('Add designation', 'docdirect'),
			),
			'description' => array(
				'type' => 'textarea',
				'desc' => esc_html__('Add short description', 'docdirect'),
			),
			'image' => array(
				'type' => 'upload',
				'desc' => esc_html__('Add client image', 'docdirect'),
			),
			'video_url' => array(
				'type' => 'text',
				'desc' => esc_html__('Add video URL from any source. Leave it empty to hide.', 'docdirect'),
			),
		),
		'template' => '{{- name }}', // box title
		'add-button-text' => esc_html__('Add More', 'docdirect'),
		'sortable' => true,
	),
);
