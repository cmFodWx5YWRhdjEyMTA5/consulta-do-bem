<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'note' => array(
		'type'  => 'html',
		'value' => '',
		'attr'  => array(),
		'label' => esc_html__('Note', 'docdirect'),
		'desc'  => esc_html__('', 'docdirect'),
		'help'  => esc_html__('', 'docdirect'),
		'html'  => 'Please Set search page before list the search categories types. For this please go to Theme Settings > Directory Settings > Search Settings.',
	),
	'type' => array(
		'label' => esc_html__('List Type', 'docdirect'),
		'type' => 'select',
		'value' => '',
		'desc' => esc_html__('Please select search view.', 'docdirect'),
		'choices'	=> array(
			'locations'	=> 'locations',
			'speciality'   => 'Speciality',
		)
	),
	'title' => array(
		'label' => esc_html__('Type Title', 'docdirect'),
		'type' => 'text',
		'value' => '',
		'desc' => esc_html__('Please add title for category type', 'docdirect'),
	),
	'icon' => array(
		'type' => 'new-icon',
		'value' => '',
		'attr' => array(),
		'label' => esc_html__('Choos Icon', 'docdirect'),
		'desc' => esc_html__('', 'docdirect'),
		'help' => esc_html__('', 'docdirect'),
	),
	
	'show_posts' => array(
        'type' => 'slider',
        'value' => 18,
        'properties' => array(
            'min' => 0,
            'max' => 500,
            'sep' => 1,
        ),
        'label' => esc_html__('Show Number of elements', 'docdirect'),
    ),
);
