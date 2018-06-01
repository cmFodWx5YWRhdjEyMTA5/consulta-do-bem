<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'subheaders' => array(
		'title'   => esc_html__( 'Sub Headers', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'general-box' => array(
				'title'   => esc_html__( 'Sub Header Settings', 'docdirect' ),
				'type'    => 'box',
				'options' => array(
					'404_heading' => array(
                        'type'  => 'text',
                        'value' => '404 ERROR',
                        'label' => esc_html__('404 Page Title', 'docdirect'),
                        'desc'  => esc_html__('', 'docdirect'),
                    ),
					'archives_heading' => array(
                        'type'  => 'text',
                        'value' => 'Archives',
                        'label' => esc_html__('Archives Sub Heading', 'docdirect'),
                        'desc'  => esc_html__('', 'docdirect'),
                    ),
					'search_heading' => array(
                        'type'  => 'text',
                        'value' => 'Search',
                        'label' => esc_html__('Search Sub Heading', 'docdirect'),
                        'desc'  => esc_html__('', 'docdirect'),
                    ),
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
					'breadcrumb_bg' => array(
                        'type'  => 'rgba-color-picker',
                        'value' => '#5d5955',
                        'label' => esc_html__('Breadcrumbs bg color', 'docdirect'),
                        'desc'  => esc_html__('', 'docdirect'),
                    ),
				)
			),
		)
	)
);