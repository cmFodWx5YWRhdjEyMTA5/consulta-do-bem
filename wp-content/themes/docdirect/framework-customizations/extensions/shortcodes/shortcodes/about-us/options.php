<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'title' => array(
        'type' => 'text',
        'label' => esc_html__('Title', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'sub_title' => array(
        'type' => 'text',
        'label' => esc_html__('Sub Title', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'description' => array(
		'label' => esc_html__('Description', 'docdirect'),
		'desc' => esc_html__('', 'docdirect'),
		'type' => 'textarea',
		'value' => ''
	),
	'lists' => array(
		'label' => esc_html__('List Items', 'docdirect'),
		'type'  => 'addable-option',
		'value' => array(),
		'desc' => esc_html__('Add list items', 'docdirect'),
	),
	'buttons' => array(
        'type' => 'addable-popup',
        'label' => esc_html__('Add Buttons', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'template' => '{{- title }}',
        'popup-title' => null,
        'size' => 'small', // small, medium, large
        'limit' => 0, // limit the number of popup`s that can be added
        'add-button-text' => esc_html__('Buttons', 'docdirect'),
        'sortable' => true,
        'popup-options' => array(
            'title' => array(
                'label' => esc_html__('Button Title', 'docdirect'),
                'type' => 'text',
                'value' => '',
                'desc' => esc_html__('Add Button title', 'docdirect'),
            ),
            'link' => array(
                'type' => 'text',
                'value' => '#',
                'attr' => array(),
                'label' => esc_html__('Link', 'docdirect'),
                'desc' => esc_html__('Add Button Link', 'docdirect'),
            ),
        ),
    ),
);
