<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'colors' => array(
		'title'   => esc_html__( 'Theme Colors', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'general-box' => array(
				'title'   => esc_html__( 'Theme Colors Settings', 'docdirect' ),
				'type'    => 'box',
				'options' => array(
					'theme_type' => array(
						'type'  => 'select',
						'value' => 'light',
						'attr'  => array(),
						'label' => esc_html__('Color Setting?', 'docdirect'),
						'desc'  => esc_html__('Please select Custom styling option for dynamic color from given below color. Leave it default to set as default color.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'choices' => array(
							'default'   	=> esc_html__('Default', 'docdirect'),
							'custom'		=> esc_html__('Custom Styling', 'docdirect'),
						),
					),
					'theme_color' => array(
                        'type'  => 'color-picker',
						'value' => '#7dbb00',
						'attr'  => array(),
						'label' => esc_html__('Theme Color', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),
					'background_color' => array(
                        'type'  => 'color-picker',
						'value' => '#FFF',
						'attr'  => array(),
						'label' => esc_html__('Body Background Color', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),
				)
			),
		)
	)
);