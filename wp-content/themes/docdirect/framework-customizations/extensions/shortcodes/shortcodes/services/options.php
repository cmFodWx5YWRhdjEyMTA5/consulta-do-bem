<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'heading'    => array(
		'type'  => 'text',
		'label' => esc_html__( 'Heading Title', 'docdirect' ),
		'desc'  => esc_html__( 'Write the heading title content', 'docdirect' ),
	),
	'description'    => array(
		'type'  => 'textarea',
		'label' => esc_html__( 'Description', 'docdirect' ),
		'desc'  => esc_html__( 'Write the Description content', 'docdirect' ),
	),
	'services_popup' => array(
        'type' => 'addable-popup',
        'label' => esc_html__('Add Services', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'template' => '{{- title }}',
        'popup-title' => null,
        'size' => 'small', // small, medium, large
        'limit' => 0, // limit the number of popup`s that can be added
        'add-button-text' => esc_html__('Services', 'docdirect'),
        'sortable' => true,
        'popup-options' => array(
            'responsive_classes' => array(
                'type' => 'select',
                'value' => 'col-sm-6',
                'attr' => array(),
                'label' => esc_html__('Small Screen Class', 'docdirect'),
                'desc' => esc_html__('Choose Your Small Screen Class', 'docdirect'),
                'help' => esc_html__('', 'docdirect'),
                'choices' => array(
                    'col-sm-1' => esc_html__('col-sm-1', 'docdirect'),
                    'col-sm-2' => esc_html__('col-sm-2', 'docdirect'),
                    'col-sm-3' => esc_html__('col-sm-3', 'docdirect'),
                    'col-sm-4' => esc_html__('col-sm-4', 'docdirect'),
                    'col-sm-5' => esc_html__('col-sm-5', 'docdirect'),
                    'col-sm-6' => esc_html__('col-sm-6', 'docdirect'),
                    'col-sm-7' => esc_html__('col-sm-7', 'docdirect'),
                    'col-sm-8' => esc_html__('col-sm-8', 'docdirect'),
                    'col-sm-9' => esc_html__('col-sm-9', 'docdirect'),
                    'col-sm-10' => esc_html__('col-sm-10', 'docdirect'),
                    'col-sm-11' => esc_html__('col-sm-11', 'docdirect'),
					'col-sm-12' => esc_html__('col-sm-12', 'docdirect'),
                ),
            ),
			'title' => array(
                'label' => esc_html__('Service Title', 'docdirect'),
                'type' => 'text',
                'value' => '',
                'desc' => esc_html__('Add service title', 'docdirect'),
            ),
            'media_type' => array(
				'type'         => 'multi-picker',
				'label'        => false,
				'desc'         => false,
				'picker'       => array(
					'gadget' => array(
						'label'   => esc_html__( 'Select Media Type', 'docdirect' ),
						'type'    => 'select',
						'choices' => array(
							'image'	=> 'Image',	
							'icon'	=> 'Icon',	
						)
					)
				),
				'choices'      => array(
					'image'  => array(
						'image' => array(
							'type'  => 'upload',
							'label' => esc_html__('Upload Logo', 'docdirect'),
							'desc'  => esc_html__('', 'docdirect'),
							'images_only' => true,
						),
					),
					'icon'  => array(
						'icon'     => array(
							'type'  => 'new-icon',
							'value' => 'icon-stethoscope',
							'attr'  => array(),
							'label' => esc_html__('Choos Icon', 'docdirect'),
							'desc'  => esc_html__('', 'docdirect'),
							'help'  => esc_html__('', 'docdirect'),
						),
					),
				)
			),
			'lists' => array(
				'label' => esc_html__('List Items', 'docdirect'),
				'type'  => 'addable-option',
				'value' => array(),
				'desc' => esc_html__('Add list items', 'docdirect'),
			),
            'link' => array(
                'type' => 'text',
                'value' => '#',
                'attr' => array(),
                'label' => esc_html__('Link', 'docdirect'),
                'desc' => esc_html__('Add Serice Link', 'docdirect'),
            ),
        ),
    ),
);
