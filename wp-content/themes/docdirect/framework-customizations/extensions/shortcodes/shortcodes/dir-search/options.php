<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'view' => array(
		'label' => esc_html__('Search View', 'docdirect'),
		'type' => 'select',
		'value' => '',
		'desc' => esc_html__('Please select search view.', 'docdirect'),
		'choices'	=> array(
			'view_1'	=> 'Verticle View(Result on Map)',
			'view_2'	=> 'Horizontal View',
			'view_3'	=> 'Verticle View(Result on page)',
		)
	),
	'latitude' => array(
		'label' => esc_html__('Default Latitude', 'docdirect'),
		'type' => 'text',
		'value' => '51.5001524',
		'desc' => esc_html__('Add Default Latitude', 'docdirect'),
	),
	'longitude' => array(
		'label' => esc_html__('Default Longitude', 'docdirect'),
		'type' => 'text',
		'value' => '-0.1262362',
		'desc' => esc_html__('Add Default Longitude', 'docdirect'),
	),
	
	'btn_title' => array(
		'label' => esc_html__('Button Title', 'docdirect'),
		'type' => 'text',
		'value' => 'Search',
		'desc' => esc_html__('Add Button title', 'docdirect'),
	),
	
	'categories' => array(
		'type' => 'multi-select',
		'label' => esc_html__('Select categories', 'docdirect'),
		'population' => 'posts',
		'source' => 'directory_type',
		'prepopulate' => 500,
		'desc' => esc_html__('Select number of categories to show in search form', 'docdirect'),
	),
);
