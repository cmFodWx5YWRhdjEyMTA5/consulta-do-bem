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
	'description' => array(
		'label' => esc_html__('Description', 'docdirect'),
		'type' => 'textarea',
		'value' => '',
		'desc' => esc_html__('Add short description', 'docdirect'),
	),
	'banner' => array(
		'type' => 'upload',
		'attr' => array(),
		'label' => esc_html__('Image', 'docdirect'),
		'desc' => esc_html__('Add Image', 'docdirect'),
	),
	'work_description' => array(
		'label' => esc_html__('Work Description', 'docdirect'),
		'type' => 'textarea',
		'value' => '',
		'desc' => esc_html__('Add work description. leave it empty to hide.', 'docdirect'),
	),
	'button_title' => array(
		'label' => esc_html__('Button Title', 'docdirect'),
		'type' => 'text',
		'value' => '',
		'desc' => esc_html__('Add button title, Leave it empty to hide.', 'docdirect'),
	),
	'link' => array(
		'label' => esc_html__('Button Link', 'docdirect'),
		'type' => 'text',
		'value' => '#',
		'desc' => esc_html__('Add button link', 'docdirect'),
	),
    'work_popup' => array(
        'type' => 'addable-popup',
        'label' => esc_html__('Add Work list', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'template' => '{{- title }}',
        'popup-title' => null,
        'size' => 'small', // small, medium, large
        'limit' => 0, // limit the number of popup`s that can be added
        'add-button-text' => esc_html__('Work', 'docdirect'),
        'sortable' => true,
        'popup-options' => array(
            'title' => array(
                'label' => esc_html__('Title', 'docdirect'),
                'type' => 'text',
                'value' => '',
                'desc' => esc_html__('Add title', 'docdirect'),
            ),
            'description' => array(
                'label' => esc_html__('Description', 'docdirect'),
                'type' => 'textarea',
                'value' => '',
                'desc' => esc_html__('Add description', 'docdirect'),
            ),
        ),
    ),
);
