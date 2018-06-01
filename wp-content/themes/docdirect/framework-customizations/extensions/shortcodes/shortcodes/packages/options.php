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
	'featured' => array(
        'type' => 'checkbox',
        'label' => esc_html__('Make Featured?', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'pac_title' => array(
		'type' => 'text',
		'attr' => array(),
		'label' => esc_html__('Package Title', 'docdirect'),
		'desc' => esc_html__('Add Package Title', 'docdirect'),
	),
	'pac_subtitle' => array(
		'type' => 'text',
		'attr' => array(),
		'label' => esc_html__('Package Sub Title', 'docdirect'),
		'desc' => esc_html__('Add Package Sub Title', 'docdirect'),
	),
	'currency' => array(
		'type' => 'text',
		'attr' => array(),
		'label' => esc_html__('Currency', 'docdirect'),
		'desc' => esc_html__('Add Currency as : $ OR USD', 'docdirect'),
	),
	'price' => array(
		'type' => 'text',
		'attr' => array(),
		'label' => esc_html__('Price', 'docdirect'),
		'desc' => esc_html__('Add Price as : 75', 'docdirect'),
	),
	'duration' => array(
		'type' => 'text',
		'attr' => array(),
		'label' => esc_html__('Duration', 'docdirect'),
		'desc' => esc_html__('Add Duration', 'docdirect'),
	),
	'rating' => array(
		'label' => esc_html__('Work Description', 'docdirect'),
		'type' => 'select',
		'value' => 5,
		'choices'	=> array(
			''	=> 'Select Rating',
			'1'	=> 1,
			'2'	=> 2,
			'3'	=> 3,
			'4'	=> 4,
			'5'	=> 5,
		),
		'desc' => esc_html__('Add work description. leave it empty to hide.', 'docdirect'),
	),
	'features' => array(
		'label' => esc_html__('List Items', 'docdirect'),
		'type'  => 'addable-option',
		'value' => array('Lorem ipsum dolor'),
		'desc' => esc_html__('Add list items', 'docdirect'),
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
);
