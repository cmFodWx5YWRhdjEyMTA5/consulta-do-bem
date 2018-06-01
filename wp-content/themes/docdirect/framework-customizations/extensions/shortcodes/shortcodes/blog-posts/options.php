<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'blog_view' => array(
        'type' => 'select',
        'value' => 'grid',
        'label' => esc_html__('Select View', 'docdirect'),
        'choices' => array(
            'grid' => esc_html__('Grid', 'docdirect'),
            'list' => esc_html__('List', 'docdirect'),
			'large' => esc_html__('Large', 'docdirect'),
        ),
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
                'desc' => esc_html__('Select news by category or item', 'docdirect'),
                'label' => esc_html__('News By', 'docdirect'),
                'choices' => array(
                    'by_cats' => esc_html__('By Categories', 'docdirect'),
                    'by_posts' => esc_html__('By item', 'docdirect'),
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
                        'min' => 1,
                        'max' => 100,
                        'sep' => 1,
                    ),
                    'label' => esc_html__('Show No of Posts', 'docdirect'),
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
				'show_posts' => array(
                    'type' => 'slider',
                    'value' => 9,
                    'properties' => array(
                        'min' => 1,
                        'max' => 100,
                        'sep' => 1,
                    ),
                    'label' => esc_html__('Show No of Posts', 'docdirect'),
                ),
            )
        ),
        'show_borders' => true,
    ),
   'excerpt' => array(    
        'value' => '',
        'label' => esc_html__('Excerpt Length', 'docdirect'),
        'desc' => esc_html__('Enter excerpt length. leave it empty to hide.', 'docdirect'),
        'type' => 'text',
    ),
	'show_pagination' => array(
        'type' => 'select',
        'value' => 'no',
        'label' => esc_html__('Show Pagination', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'choices' => array(
            'yes' => esc_html__('Yes', 'docdirect'),
            'no' => esc_html__('No', 'docdirect'),
        ),
        'no-validate' => false,
    ),
);
