<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'icon' => array(
		'type' => 'new-icon',
		'value' => '',
		'attr' => array(),
		'label' => esc_html__('Choos Icon', 'docdirect'),
		'desc' => esc_html__('', 'docdirect'),
		'help' => esc_html__('', 'docdirect'),
	),
	'title' => array(
		'label' => esc_html__('Title', 'docdirect'),
		'type' => 'text',
		'value' => '',
		'desc' => esc_html__('', 'docdirect')
	),
	'description' => array(
		'type' => 'addable-option',
		'value' => array( 'Content here..' ),
		'option' => array( 'type' => 'text' ),
		'add-button-text' => esc_html__('Add', 'docdirect'),
		'sortable' => true,
		'label' => esc_html__('Add list', 'docdirect'),
	),
);