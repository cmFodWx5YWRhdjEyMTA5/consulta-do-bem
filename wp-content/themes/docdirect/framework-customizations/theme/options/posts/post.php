<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'sub_headers' => array(
		'title'   => esc_html__( 'Page Settings', 'docdirect' ),
		'type'    => 'box',
		'options' => array(
			'enable_subheader' => array(
				'type'  => 'switch',
				'value' => 'enable',
				'label' => esc_html__('Subheader', 'docdirect'),
				'desc'  => esc_html__('Enable or Disable Subheader', 'docdirect'),
				'left-choice' => array(
					'value' => 'enable',
					'label' => esc_html__('Enable', 'docdirect'),
				),
				'right-choice' => array(
					'value' => 'disable',
					'label' => esc_html__('Disable', 'docdirect'),
				),
			),
			'subheader_type' => array(
				'type'         => 'multi-picker',
				'label'        => false,
				'desc'         => false,
				'picker'       => array(
					'gadget' => array(
						'label'   => esc_html__( 'Subheader Type', 'docdirect' ),
						'desc'   => esc_html__( 'Select Subheader Type', 'docdirect' ),
						'type'    => 'select',
						'choices' => array(
							'default' => esc_html__('Default Sub Headers', 'docdirect'),
							'custom' => esc_html__('Custom Sub Headers', 'docdirect'),
							'tg_slider' => esc_html__('TG Slider', 'docdirect'),
							'rev_slider' => esc_html__('Revolution Slider', 'docdirect'),
							'custom_shortcode' => esc_html__('Custom Shortcode', 'docdirect'),	
						)
					)
				),
				'choices'      => array(
					'default'  => array(
						'blog_post_image' => array(
							'type' => 'html',
							'html' => 'Default Subheaders',
							'label' => esc_html__('', 'docdirect'),
							'desc' => esc_html__('Please default settings from theme options.', 'docdirect'),
							'help' => esc_html__('Please Go To Appearance >> Theme Settings >> Subheaders', 'docdirect'),
							'images_only' => true,
						),
					),
					'custom'  => array(
						'enable_breadcrumbs' => array(
							'type'  => 'switch',
							'value' => 'enable',
							'label' => esc_html__('Breadcrumbs', 'docdirect'),
							'desc'  => esc_html__('Enable or Disable Breadcrumbs.', 'docdirect'),
							'left-choice' => array(
								'value' => 'enable',
								'label' => esc_html__('Enable', 'docdirect'),
							),
							'right-choice' => array(
								'value' => 'disable',
								'label' => esc_html__('Disable', 'docdirect'),
							),
						),
						'sub_header_bg' => array(
							'type'  => 'rgba-color-picker',
							'value' => 'rgba(255,255,255,0)',
							'label' => esc_html__('Sub Header bg color', 'docdirect'),
							'desc'  => esc_html__('', 'docdirect'),
						),
						'subheader_bg_image' => array(
							'type'  => 'upload',
							'label' => esc_html__('Upload background image', 'docdirect'),
							'desc'  => esc_html__('It will override background color', 'docdirect'),
							'images_only' => true,
						),
					),
					'tg_slider'  => array(
						'sub_shortcode' => array(
							'type'  => 'select',
							'value' => '',
							'label' => esc_html__('TG Slider', 'docdirect'),
							'desc'  => esc_html__('Select Themographic Slider.', 'docdirect'),
							'choices' => docdirect_prepare_sliders(),
						),
					),
					'rev_slider'  => array(
						'rev_slider' => array(
							'type'  => 'select',
							'value' => '',
							'label' => esc_html__('Revolution Slider', 'docdirect'),
							'desc'  => esc_html__('Please Select Revolution slider.', 'docdirect'),
							'help' => esc_html__('Please install revolution slider first.', 'docdirect'),
							'choices' => docdirect_prepare_rev_slider(),
						),
					),
					'custom_shortcode'  => array(
						'custom_shortcode' => array(
							'type'  => 'textarea',
							'value' => '',
							'desc' => esc_html__('', 'docdirect'),
							'label'  => esc_html__('Custom Slider', 'docdirect'),
						),
					),
				)
			),
		)
	),
	'post_settings' => array(
		'title' => esc_html__('Post Settings', 'docdirect'),
		'type' => 'box',
		'options' => array(
			'enable_comments' => array(
				'type'  => 'switch',
				'value' => 'enable',
				'label' => esc_html__('Comments', 'docdirect'),
				'desc'  => esc_html__('Enable or Disable Comments at post detail page.', 'docdirect'),
				'left-choice' => array(
					'value' => 'enable',
					'label' => esc_html__('Enable', 'docdirect'),
				),
				'right-choice' => array(
					'value' => 'disable',
					'label' => esc_html__('Disable', 'docdirect'),
				),
			),
			'enable_author_info' => array(
				'type'  => 'switch',
				'value' => 'enable',
				'label' => esc_html__('Author Information', 'docdirect'),
				'desc'  => esc_html__('Enable or Disable Author Information at post detail page.', 'docdirect'),
				'left-choice' => array(
					'value' => 'enable',
					'label' => esc_html__('Enable', 'docdirect'),
				),
				'right-choice' => array(
					'value' => 'disable',
					'label' => esc_html__('Disable', 'docdirect'),
				),
			),
			'enable_sharing' => array(
				'type'  => 'switch',
				'value' => 'enable',
				'label' => esc_html__('Social Share', 'docdirect'),
				'desc'  => esc_html__('Enable or Disable sharing at post detail page.', 'docdirect'),
				'left-choice' => array(
					'value' => 'enable',
					'label' => esc_html__('Enable', 'docdirect'),
				),
				'right-choice' => array(
					'value' => 'disable',
					'label' => esc_html__('Disable', 'docdirect'),
				),
			),
			'post_settings' => array(
				'type'         => 'multi-picker',
				'label'        => false,
				'desc'         => false,
				'picker'       => array(
					'gadget' => array(
						'label'   => esc_html__( 'Post Format', 'docdirect' ),
						'desc'   => esc_html__( 'Select Post Format', 'docdirect' ),
						'type'    => 'radio',
						'value'    => 'image',
						'choices' => array(
							'image' => esc_html__('Image', 'docdirect'),
							'gallery' => esc_html__('Image Slider', 'docdirect'),
							'video' => esc_html__('Audio/Video', 'docdirect'),
						),
						'inline' => true,
					)
				),
				'choices'      => array(
					'image'  => array(
						'blog_post_image' => array(
							'type' => 'html',
							'html' => 'Featured Image',
							'label' => esc_html__('', 'docdirect'),
							'desc' => esc_html__('Please add featured image.', 'docdirect'),
							'help' => esc_html__('Add Featured image for this post.', 'docdirect'),
							'images_only' => true,
						),
					),
					'gallery'  => array(
						'blog_post_gallery' => array(
							'type' => 'multi-upload',
							'label' => esc_html__('Add Image Slider', 'docdirect'),
							'desc' => esc_html__('Add Image Slider for your post. (Preferred Size is 1314 by 737.)', 'docdirect'),
							'help' => esc_html__('Only worked if the post format setting is equal to image slider.', 'docdirect'),
							'images_only' => true,
						),
					),
					'video'  => array(
						'blog_video_link' => array(
							'type' => 'text',
							'label' => esc_html__('Audio/Video Link', 'docdirect'),
							'desc' => esc_html__('Only worked if the post format setting is equal to Audio/Video.', 'docdirect'),
						),
					),
				)
			),
		)
	), 
);
