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
	'success' => array(
        'type' => 'text',
		'value' => 'Message Sent.',
        'label' => esc_html__('Success Message', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'error' => array(
        'type' => 'text',
		'value' => 'Message Fail.',
        'label' => esc_html__('Error Message', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'email_to' => array(
        'type' => 'text',
        'label' => esc_html__('Email Adress', 'docdirect'),
        'desc' => esc_html__('Add email address. This will be used to receive contact form', 'docdirect'),
    ),
	'button_title' => array(
		'label' => esc_html__('Button Title', 'docdirect'),
		'type' => 'text',
		'value' => 'Send',
		'desc' => esc_html__('Add button title, Leave it empty to hide.', 'docdirect'),
	),
);
