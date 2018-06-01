<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
	'title' => array(
		'label' => esc_html__('Title', 'docdirect'),
		'type' => 'text',
		'value' => 'find your nearest',
		'desc' => esc_html__('Add title for heading.', 'docdirect'),
	),
	'gallery' => array(
		'label' => esc_html__('Background images', 'docdirect'),
		'type' => 'multi-upload',
		'desc' => esc_html__('Please select background images for slider.', 'docdirect'),
	),
);
