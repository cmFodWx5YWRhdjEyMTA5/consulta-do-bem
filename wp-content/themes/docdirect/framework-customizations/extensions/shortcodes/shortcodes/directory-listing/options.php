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
	'show_posts' => array(
        'type' => 'slider',
        'value' => 9,
        'properties' => array(
            'min' => 0,
            'max' => 100,
            'sep' => 1,
        ),
        'label' => esc_html__('Show Number of elements', 'docdirect'),
		'desc' => esc_html__('Set it to 0 to show all posts.', 'docdirect'),
    ),
);
