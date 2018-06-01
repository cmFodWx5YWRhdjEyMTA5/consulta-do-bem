<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'general' => array(
		'title'   => esc_html__( 'General', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'general-box' => array(
				'title'   => esc_html__( 'General Settings', 'docdirect' ),
				'type'    => 'box',
				'options' => array(
					'preloader' => array(
						'type'         => 'multi-picker',
						'label'        => false,
						'desc'         => false,
						'picker'       => array(
							'gadget' => array(
								'label'        => esc_html__('Enable Preloader' , 'docdirect') ,
								'type'         => 'switch' ,
								'value'        => 'enable' ,
								'desc'         => esc_html__('Preloader on/off' , 'docdirect') ,
								'left-choice'  => array (
									'value' => 'enable' ,
									'label' => esc_html__('Enable' , 'docdirect') ,
								) ,
								'right-choice' => array (
									'value' => 'disable' ,
									'label' => esc_html__('Disable' , 'docdirect') ,
								) ,
							)
						),
						'choices'      => array(
							'enable'  => array(
								'preloader' => array(
									'type'         => 'multi-picker',
									'label'        => false,
									'desc'         => false,
									'picker'       => array(
										'gadget' => array(
											'type' => 'select',
											'value' => 'default',
											'label' => esc_html__('Select Type', 'docdirect'),
											'desc'  => esc_html__('Please select loader type.', 'docdirect'),
											'choices' => array(
												'default' => esc_html__('Default', 'docdirect'),
												'custom' => esc_html__('Custom', 'docdirect'),
											),
										)
									),
									'choices'      => array(
										'custom'  => array(
											'loader' => array(
												'type'  => 'upload',
												'label' => esc_html__('Loader Image?', 'docdirect'),
												'desc'  => esc_html__('Upload loader image.', 'docdirect'),
												'images_only' => true,
											),
										),
									
									)
								),
								'loader_speed' => array(
									'type' => 'select',
									'value' => '1000',
									'label' => esc_html__('Loader duration?', 'docdirect'),
									'desc'  => esc_html__('Seelct site loader speed', 'docdirect'),
									'choices' => array(
										'500'  => esc_html__('Half Second', 'docdirect'),
										'1000' => esc_html__('1 Second', 'docdirect'),
										'2000' => esc_html__('2 Seconds', 'docdirect'),
										'3000' => esc_html__('3 Seconds', 'docdirect'),
										'4000' => esc_html__('4 Second', 'docdirect'),
										'5000' => esc_html__('5 Seconds', 'docdirect'),
									),
								)
							),
						)
					),
					'404_image' => array(
						'label' => esc_html__( '404 Page Image', 'docdirect' ),
						'desc'  => esc_html__( '', 'docdirect' ),
						'type'  => 'upload'
					),
                    '404_title' => array(
                        'type'  => 'text',
                        'value' => 'we are sorry!',
                        'label' => esc_html__('404 Title', 'docdirect'),
                    ),
					'404_message' => array(
                        'type'  => 'textarea',
                        'value' => 'THE PAGE YOU REQUESTED CANNOT BE FOUND.',
                        'label' => esc_html__('404 Sub Title', 'docdirect'),
                    ),
					'404_description' => array(
                        'type'  => 'textarea',
                        'value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                        'label' => esc_html__('404 Description', 'docdirect'),
                    ),
					'search_text' => array(
                        'type'  => 'textarea',
                        'value' => 'Perhaps searching can help.',
                        'label' => esc_html__('Search Message', 'docdirect'),
                    ),
                    'custom_css' => array(
                        'type'  => 'textarea',
                        'label' => esc_html__('Custom CSS', 'docdirect'),
                        'desc'  => esc_html__('Add your custom css code here if you want to target specifically on different elements.', 'docdirect'),
                    ),
				)
			),
		)
	)
);