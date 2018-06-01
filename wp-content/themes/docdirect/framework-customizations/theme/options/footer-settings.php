<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'footer' => array(
        'title' => esc_html__('Footer', 'docdirect'),
        'type' => 'tab',
        'options' => array(
            'footer_settings' => array(
                'title' => esc_html__('Footer Settings', 'docdirect'),
                'type' => 'box',
                'options' => array(
                    'footer_type' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'value' => array('gadget' => 'footer_v1'),
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Footer Type', 'docdirect'),
                                'type' => 'image-picker',
                                'choices' => array(
                                    'footer_v1' => array(
                                        'label' => __('Footer V1', 'docdirect'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/footers/f_1.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/footers/f_1.jpg'
                                        ),
                                    ),
                                    'footer_v2' => array(
                                        'label' => __('Footer V2', 'docdirect'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/footers/f_2.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/footers/f_2.jpg'
                                        ),
                                    ),
                                ),
                                'desc' => esc_html__('Select footer type.', 'docdirect'),
                            )
                        ),
                        'choices' => array(
                            'footer_v2' => array(
                                'newsletter' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Enable / Disable newsletter', 'docdirect'),
									'desc' => esc_html__('', 'docdirect'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'docdirect'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'docdirect'),
									),
								),
								'newsletter_title' => array(
									'type' => 'text',
									'value' => 'Signup For Newsletter',
									'label' => esc_html__('Newsletter title', 'docdirect'),
								),
								'newsletter_desc' => array(
									'type' => 'textarea',
									'value' => '',
									'label' => esc_html__('Newsletter short description', 'docdirect'),
								),
								'footer_menu' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Enable / Disable Menu', 'docdirect'),
									'desc' => esc_html__('', 'docdirect'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'docdirect'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'docdirect'),
									),
								),
                            )
                        ),
                        'show_borders' => true,
                    ),
					'enable_widget_area' => array(
                        'type' => 'switch',
                        'value' => 'off',
                        'attr' => array(),
                        'label' => esc_html__('Enable / Disable Widget Area', 'docdirect'),
                        'desc' => esc_html__('', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('Off', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
					
                    'footer_copyright' => array(
                        'type' => 'text',
                        'value' => '2015 All Rights Reserved &copy; DocDirect',
                        'label' => esc_html__('Footer Copyright', 'docdirect'),
                    ),
                )
            ),
        )
    )
);
