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
	'sub_heading' => array(
        'type' => 'text',
        'label' => esc_html__('Sub Heading', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'description' => array(
		'label' => esc_html__('Description', 'docdirect'),
		'type' => 'textarea',
		'value' => '',
		'desc' => esc_html__('Add short description', 'docdirect'),
	),
	'get_mehtod' => array(
        'type' => 'multi-picker',
        'label' => false,
        'desc' => false,
        'value' => array('gadget' => 'normal'),
        'picker' => array(
            'gadget' => array(
                'type' => 'select',
                'value' => 'by_cats',
                'desc' => esc_html__('Enable/Disbale Site Boxed View', 'docdirect'),
                'label' => esc_html__('Posts By', 'docdirect'),
                'choices' => array(
                    'by_cats' => esc_html__('By Categories', 'docdirect'),
                    'by_posts' => esc_html__('By Posts', 'docdirect'),
                ),
            )
        ),
        'choices' => array(
            'by_cats' => array(
                'categories' => array(
                    'type' => 'multi-select',
                    'label' => esc_html__('Select Categories', 'docdirect'),
                    'population' => 'taxonomy',
                    'source' => 'category',
					'prepopulate' => 500,
                    'desc' => esc_html__('Show posts by category selection.', 'docdirect'),
                ),
            ),
            'by_posts' => array(
                'posts' => array(
                    'type' => 'multi-select',
                    'label' => esc_html__('Select Posts', 'docdirect'),
                    'population' => 'posts',
                    'source' => 'post',
					'prepopulate' => 500,
                    'desc' => esc_html__('Show posts by post selection.', 'docdirect'),
                ),
            )
        ),
        'show_borders' => true,
    ),
    'order' => array(
        'type' => 'select',
        'value' => 'DESC',
        'desc' => esc_html__('Post Order', 'docdirect'),
        'label' => esc_html__('Posts By', 'docdirect'),
        'choices' => array(
            'ASC' => esc_html__('ASC', 'docdirect'),
            'DESC' => esc_html__('DESC', 'docdirect'),
        ),
    ),
    'orderby' => array(
        'type' => 'select',
        'value' => 'ID',
        'desc' => esc_html__('Post Order', 'docdirect'),
        'label' => esc_html__('Posts By', 'docdirect'),
        'choices' => array(
            'ID' => esc_html__('Order by post id', 'docdirect'),
            'author' => esc_html__('Order by author', 'docdirect'),
            'title' => esc_html__('Order by title', 'docdirect'),
            'name' => esc_html__('Order by post name', 'docdirect'),
            'date' => esc_html__('Order by date', 'docdirect'),
            'rand' => esc_html__('Random order', 'docdirect'),
            'comment_count' => esc_html__('Order by number of comments', 'docdirect'),
        ),
    ),
    'show_posts' => array(
        'type' => 'slider',
        'value' => 9,
        'properties' => array(
            'min' => 0,
            'max' => 100,
            'sep' => 1,
        ),
        'label' => esc_html__('Show No of Posts', 'docdirect'),
    ),
    'show_description' => array(
        'type' => 'switch',
        'value' => 'show',
        'label' => esc_html__('Description', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'left-choice' => array(
            'value' => 'show',
            'label' => esc_html__('Show Description', 'docdirect'),
        ),
        'right-choice' => array(
            'value' => 'hide',
            'label' => esc_html__('Hide Description', 'docdirect'),
        ),
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
