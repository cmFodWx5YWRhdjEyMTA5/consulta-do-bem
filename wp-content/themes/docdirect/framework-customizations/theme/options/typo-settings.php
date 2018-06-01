<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'typo' => array(
        'title'   => esc_html__( 'Typography', 'docdirect' ),
        'type'    => 'tab',
        'options' => array(
            'typo-box' => array(
                'title'   => esc_html__( 'Typography Settings', 'docdirect' ),
                'type'    => 'box',
                'options' => array(
                    'enable_typo' => array(
                        'type'  => 'switch',
                        'value' => 'off',
                        'attr'  => array( 'class' => 'custom-class', 'data-foo' => 'bar' ),
                        'label' => esc_html__('Enable / Disable Typography', 'docdirect'),
                        'desc'  => esc_html__('If you disable this, these options below would not be applied to frontend', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('Off', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
                    'body_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'family' => 'Verdana',
                            'size' => 15,
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('Regular Font', 'docdirect'),
                        'desc'  => esc_html__('Default Font for body p ul li', 'docdirect'),
                    ),
                    'h1_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'size' => 40,
							'family' => 'Verdana',
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('H1 Heading', 'docdirect'),
                        'desc'  => esc_html__('Choose Your H1 Heading font-family, font-size, color, font-weight.', 'docdirect'),
                    ),
                    'h2_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'family' => 'Verdana',
                            'size' => 40,
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('H2 Heading', 'docdirect'),
                        'desc'  => esc_html__('Choose Your H2 Heading font-family, font-size, color, font-weight.', 'docdirect'),
                    ),
                    'h3_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'family' => 'Verdana',
                            'size' => 30,
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('H3 Heading', 'docdirect'),
                        'desc'  => esc_html__('Choose Your H3 Heading font-family, font-size, color, font-weight.', 'docdirect'),
                    ),
                    'h4_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'family' => 'Verdana',
                            'size' => 24,
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('H4 Heading', 'docdirect'),
                        'desc'  => esc_html__('Choose Your H4 Heading font-family, font-size, color, font-weight.', 'docdirect'),
                    ),
                    'h5_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'family' => 'Verdana',
                            'size' => 20,
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('H5 Heading', 'docdirect'),
                        'desc'  => esc_html__('Choose Your H5 Heading font-family, font-size, color, font-weight.', 'docdirect'),
                    ),
                    'h6_font' => array(
                        'type'  => 'typography',
                        'value' => array(
                            'family' => 'Verdana',
                            'size' => 16,
                            'style' => '300italic',
                            'color' => '#5d5955'
                        ),
                        'components' => array(
                            'family' => true,
                            'size'   => true,
                            'color'  => true
                        ),
                        'label' => esc_html__('H6 Heading', 'docdirect'),
                        'desc'  => esc_html__('Choose Your H6 Heading font-family, font-size, color, font-weight.', 'docdirect'),
                    ),
                )
            ),
        )
    )
);