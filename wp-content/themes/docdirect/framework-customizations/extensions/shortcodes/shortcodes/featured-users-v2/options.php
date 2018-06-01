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
	'user_type' => array(
        'type' => 'select',
        'value' => '',
        'label' => esc_html__('Select Category', 'docdirect'),
        'choices' => docdirect_prepare_custom_posts('directory_type')
    ),
	'show_users' => array(
        'type' => 'slider',
        'value' => 9,
        'properties' => array(
            'min' => 0,
            'max' => 100,
            'sep' => 1,
        ),
        'label' => esc_html__('No of users to show.', 'docdirect'),
    ),
	'order' => array(
        'type' => 'select',
        'value' => 'DESC',
        'label' => esc_html__('Order', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'choices' => array(
            'DESC' => esc_html__('DESC', 'docdirect'),
            'ASC' => esc_html__('ASC', 'docdirect'),
        ),
        'no-validate' => false,
    ),
);
