<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'general_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Genral Settings', 'docdirect'),
        'options' => array(
            'section_heading' => array(
                'label' => esc_html__('Section Heading', 'docdirect'),
                'desc' => esc_html__('Enter Your Section Heading Here', 'docdirect'),
                'type' => 'text',
            ),
			'is_fullwidth' => array(
                'type' => 'select',
                'value' => 'default',
                'attr' => array(),
                'label' => esc_html__('Section Settings', 'docdirect'),
                'desc' => esc_html__('Select Section Settings', 'docdirect'),
                'help' => esc_html__('', 'docdirect'),
                'choices' => array(
                    'default' => esc_html__('Default', 'docdirect'),
					'stretch_section' => esc_html__('Stretch Section', 'docdirect'),
                    'stretch_section_contents' => esc_html__('Stretch Section With Contents', 'docdirect'),
                    'stretch_section_contents_corner' => esc_html__('Stretch Section With Contents(No Padding)', 'docdirect'),
                    
                ),
            ),
            'background_color' => array(
                'label' => esc_html__('Background Color', 'docdirect'),
                'desc' => esc_html__('Please select the background color', 'docdirect'),
                'type' => 'rgba-color-picker',
            ),
            'background_image' => array(
                'label' => esc_html__('Background Image', 'docdirect'),
                'desc' => esc_html__('Please select the background image', 'docdirect'),
                'type' => 'background-image',
                'choices' => array(//	in future may will set predefined images
                )
            ),
            'background_repeat' => array(
                'type' => 'select',
                'value' => 'no-repeat',
                'attr' => array(),
                'label' => esc_html__('Background Repeat', 'docdirect'),
                'desc' => esc_html__('Repeat Background', 'docdirect'),
                'help' => esc_html__('', 'docdirect'),
                'choices' => array(
                    'no-repeat' => esc_html__('No Repeat', 'docdirect'),
                    'repeat' => esc_html__('Repeat', 'docdirect'),
                    'repeat-x' => esc_html__('Repeat X', 'docdirect'),
                    'repeat-y' => esc_html__('Repeat Y', 'docdirect'),
                ),
            ),
            'positioning_x' => array(
                'type' => 'slider',
                'value' => 0,
                'properties' => array(
                    'min' => -100,
                    'max' => 100,
                    'sep' => 1,
                ),
                'desc' => esc_html__('Background position Horizontally', 'docdirect'),
                'label' => esc_html__('Position X, IN ( % )', 'docdirect'),
            ),
            'positioning_y' => array(
                'type' => 'slider',
                'value' => 100,
                'properties' => array(
                    'min' => -100,
                    'max' => 100,
                    'sep' => 1,
                ),
                'desc' => esc_html__('Background position Vertically', 'docdirect'),
                'label' => esc_html__('Position Y, IN ( % )', 'docdirect'),
            ),
            'video' => array(
                'label' => esc_html__('Background Video', 'docdirect'),
                'desc' => esc_html__('Insert Video URL to embed this video', 'docdirect'),
                'type' => 'text',
            ),
            'custom_id' => array(
                'label' => esc_html__('Custom ID', 'docdirect'),
                'desc' => esc_html__('Add Custom ID', 'docdirect'),
                'type' => 'text',
            ),
            'custom_classes' => array(
                'label' => esc_html__('Custom Classes', 'docdirect'),
                'desc' => esc_html__('Add Custom Classes', 'docdirect'),
                'type' => 'text',
            )
        )
    ),
    'margin_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Margin', 'docdirect'),
        'options' => array(
            'margin_top' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Margin Top', 'docdirect'),
				'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
			'margin_bottom' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Margin Bottom', 'docdirect'),
				'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
			'margin_left' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Margin Left', 'docdirect'),
				'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
			'margin_right' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Margin Right', 'docdirect'),
				'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
        )
    ),
    'padding_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Padding', 'docdirect'),
        'options' => array(
            'padding_top' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Padding Top', 'docdirect'),
				'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
			'padding_bottom' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Padding Bottom', 'docdirect'),
				'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
			'padding_left' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Padding Left', 'docdirect'),
				'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
			'padding_right' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Padding Right', 'docdirect'),
				'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
			),
        )
    ),
    'parallax_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Parallax', 'docdirect'),
        'options' => array(
            'parallax' => array(
                'label' => esc_html__('Parallax', 'docdirect'),
                'desc' => esc_html__('Use background image as parallax.', 'docdirect'),
                'type' => 'switch',
                'value' => 'off',
                'left-choice' => array(
                    'value' => 'on',
                    'label' => esc_html__('ON', 'docdirect'),
                ),
                'right-choice' => array(
                    'value' => 'off',
                    'label' => esc_html__('OFF', 'docdirect'),
                ),
            ),
            'parallax_bleed' => array(
                'type' => 'slider',
                'value' => 0,
                'properties' => array(
                    'min' => 0,
                    'max' => 500,
                    'sep' => 1,
                ),
                'label' => esc_html__('Parallax Data Bleed', 'docdirect'),
            ),
            'parallax_speed' => array(
                'type' => 'select',
                'value' => 'no-repeat',
                'attr' => array(),
                'label' => esc_html__('Parallax Speed', 'docdirect'),
                'desc' => esc_html__('Choose Your Parallax Speed', 'docdirect'),
                'help' => esc_html__('', 'docdirect'),
                'choices' => array(
                    '0' => esc_html__('0', 'docdirect'),
                    '0.1' => esc_html__('0.1', 'docdirect'),
                    '0.2' => esc_html__('0.2', 'docdirect'),
                    '0.3' => esc_html__('0.3', 'docdirect'),
                    '0.4' => esc_html__('0.4', 'docdirect'),
                    '0.5' => esc_html__('0.5', 'docdirect'),
                    '0.6' => esc_html__('0.6', 'docdirect'),
                    '0.7' => esc_html__('0.7', 'docdirect'),
                    '0.8' => esc_html__('0.8', 'docdirect'),
                    '0.9' => esc_html__('0.9', 'docdirect'),
                    '1.0' => esc_html__('1.0', 'docdirect'),
                ),
            ),
          
        )
    ),
	'sidebars' => array(
	    'type' => 'tab',
        'title' => esc_html__('Sidebar', 'docdirect'),
        'options' => array(
			'sidebar' => array(
			'type'         => 'multi-picker',
			'label'        => false,
			'desc'         => false,
			'value' 		=> array('gadget' => 'full'),
			'picker'       => array(
				'gadget' => array(
					'label'   => esc_html__( 'Section Sidebar', 'docdirect' ),
					'type'    => 'image-picker',
					'choices' => array(
						'full'  => get_template_directory_uri() . '/images/sidebars/full.png',
						'left'  => get_template_directory_uri() . '/images/sidebars/left.png',
						'right'  => get_template_directory_uri() . '/images/sidebars/right.png',
					)
				)
			),
			'choices'      => array(
				'full'  => array(),
				'left'  => array(
					'left_sidebar' => array(
						'type' => 'select',
						'value' => '',
						'attr' => array(),
						'label' => esc_html__('Select Sidebar', 'docdirect'),
						'desc' => esc_html__('Select Left Sidebar', 'docdirect'),
						'help' => esc_html__('', 'docdirect'),
						'choices' => docdirect_get_registered_sidebars(),
					),
				),
				'right'  => array(
					'right_sidebar' => array(
						'type' => 'select',
						'value' => '',
						'attr' => array(),
						'label' => esc_html__('Select Sidebar', 'docdirect'),
						'desc' => esc_html__('Select Right Sidebar', 'docdirect'),
						'help' => esc_html__('', 'docdirect'),
						'choices' => docdirect_get_registered_sidebars(),
					),
				),
			)
		)
		)		
	),
);
