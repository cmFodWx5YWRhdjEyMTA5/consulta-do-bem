<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
	'advance_filters' => array(
		'type'  => 'switch',
		'value' => 'enable',
		'label' => esc_html__('Advance Filters?', 'docdirect'),
		'desc'  => esc_html__('Enable advance search filters?', 'docdirect'),
		'left-choice' => array(
			'value' => 'enable',
			'label' => esc_html__('Enable', 'docdirect'),
		),
		'right-choice' => array(
			'value' => 'disable',
			'label' => esc_html__('Disable', 'docdirect'),
		),
	),
	'bg' => array(
		'label' => esc_html__('Background image?', 'docdirect'),
		'type' => 'upload',
		'desc' => esc_html__('Please select background image', 'docdirect'),
	),
);
