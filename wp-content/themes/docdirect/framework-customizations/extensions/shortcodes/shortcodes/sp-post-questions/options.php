<?php
if (!defined('FW'))
    die('Forbidden');

$options = array(
    'heading' => array(
        'label' => esc_html__('Heading', 'docdirect'),
        'desc' => esc_html__('Search the forum for previous questions or ask a new question.', 'docdirect'),
        'type' => 'text',
    ),
	'show_recent' => array(
        'type' => 'select',
        'value' => 'no',
        'label' => esc_html__('Show recent question', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'choices' => array(
            'yes' => esc_html__('Yes', 'docdirect'),
            'no' => esc_html__('No', 'docdirect'),
        ),
        'no-validate' => false,
    ),
);
