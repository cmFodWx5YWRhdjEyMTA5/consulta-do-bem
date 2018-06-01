<?php

if (!defined('FW'))
    die('Forbidden');



$options = array(
    'heading' => array(
        'label' => esc_html__('Heading', 'docdirect'),
        'desc' => esc_html__('Add news section heading. leave it empty to hide.', 'docdirect'),
        'type' => 'text',
    ),
    'description' => array(
        'type' => 'wp-editor',
        'label' => esc_html__('Description', 'docdirect'),
        'desc' => esc_html__('Add section description. leave it empty to hide.', 'docdirect'),
        'tinymce' => true,
        'media_buttons' => false,
        'teeny' => true,
        'wpautop' => false,
        'editor_css' => '',
        'reinit' => true,
        'size' => 'small', // small | large
        'editor_type' => 'tinymce',
        'editor_height' => 200
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
                'desc' => esc_html__('Select question by category or item', 'docdirect'),
                'label' => esc_html__('Questions By', 'docdirect'),
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
                    'label' => esc_html__('Select category', 'docdirect'),
                    'population' => 'posts',
                    'source' => 'directory_type',
                    'prepopulate' => 500,
                    'desc' => esc_html__('Show question by category selection. Leave it empty to get from all categories.', 'docdirect'),
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
                    'label' => esc_html__('Show no of posts', 'docdirect'),
                ),
            ),
            'by_posts' => array(
                'posts' => array(
                    'type' => 'multi-select',
                    'label' => esc_html__('Select questions', 'docdirect'),
                    'population' => 'posts',
                    'source' => 'sp_questions',
                    'prepopulate' => 500,
                    'desc' => esc_html__('Show questions item selection.', 'docdirect'),
                ),
                'show_posts' => array(
                    'type' => 'slider',
                    'value' => 9,
                    'properties' => array(
                        'min' => 1,
                        'max' => 100,
                        'sep' => 1,
                    ),
                    'label' => esc_html__('Show no of posts', 'docdirect'),
                ),
            )
        ),
        'show_borders' => true,
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
