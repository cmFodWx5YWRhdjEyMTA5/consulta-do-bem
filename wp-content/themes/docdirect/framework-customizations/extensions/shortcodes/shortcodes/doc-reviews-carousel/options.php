<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
	'title' => array(
        'type'       => 'text',
        'label'      => esc_html__( 'Title', 'docdirect' ),
        'desc'       => esc_html__( 'Add Title here.','docdirect' ),
    ),
	'description' => array(
        'type'       => 'textarea',
        'label'      => esc_html__( 'Description', 'docdirect' ),
        'desc'       => esc_html__( 'Add Description here.','docdirect' ),
    ),
	'directory_type' => array(
        'type'       => 'multi-select',
        'label'      => esc_html__( 'Select Directory Type', 'docdirect' ),
        'population' => 'posts',
        'source'     => 'directory_type',
        'desc'       => esc_html__( 'Show review by Directory selection.','docdirect' ),
    ),
    'show_posts' => array(
        'type' => 'slider',
        'value' => 9,
        'properties' => array(
            'min' => 0,
            'max' => 100,
            'sep' => 1,
        ),
        'label' => esc_html__('Show No of Reviews', 'docdirect'),
    ),
    'excerpt_length' => array(
        'type' => 'slider',
        'value' => 123,
        'properties' => array(
            'min' => 0,
            'max' => 1000,
            'sep' => 1,
        ),
        'label' => esc_html__('Excerpt length', 'docdirect'),
    ),
);
