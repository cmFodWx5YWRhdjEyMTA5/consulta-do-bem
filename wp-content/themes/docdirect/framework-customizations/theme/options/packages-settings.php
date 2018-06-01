<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'packages' => array(
		'title'   => esc_html__( 'Price Plans', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'general-box' => array(
				'title'   => esc_html__( 'Price Plans', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					 'dir_packages' => array(
						'label'	=> esc_html__( 'Package', 'docdirect' ),
						'type'	=> 'addable-popup',
						'value'	=> array(),
						'desc'	=> esc_html__( 'Add Price plans(Packages) for payemnts from front end.', 'docdirect' ),
						'popup-options'  => array(
							'featured' => array(
								'type' => 'checkbox',
								'label' => esc_html__('Make Featured?', 'docdirect'),
								'desc' => esc_html__('', 'docdirect'),
							),
							'pac_title' => array(
								'type' => 'text',
								'attr' => array(),
								'label' => esc_html__('Package Title', 'docdirect'),
								'desc' => esc_html__('Add Package Title', 'docdirect'),
							),
							'pac_subtitle' => array(
								'type' => 'text',
								'attr' => array(),
								'label' => esc_html__('Package Sub Title', 'docdirect'),
								'desc' => esc_html__('Add Package Sub Title', 'docdirect'),
							),
							'price' => array(
								'type' => 'text',
								'attr' => array(),
								'label' => esc_html__('Price', 'docdirect'),
								'desc' => esc_html__('Add Price as : 75', 'docdirect'),
							),
							'duration' => array(
								'type' => 'text',
								'attr' => array(),
								'label' => esc_html__('Duration', 'docdirect'),
								'desc' => esc_html__('Add Duration as : 30, please add only integer value', 'docdirect'),
							),
							'short_description' => array(
								'type' => 'text',
								'attr' => array(),
								'label' => esc_html__('Package Description', 'docdirect'),
								'desc' => esc_html__('Add Package short description.', 'docdirect'),
							),
						),
						'template' => '{{- pac_title }}',
					),
				),
			),
		)
	)
);