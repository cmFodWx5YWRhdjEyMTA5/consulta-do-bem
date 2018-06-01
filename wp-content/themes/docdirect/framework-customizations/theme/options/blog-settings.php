<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'blogs' => array(
		'title'   => esc_html__( 'Blogs', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'general-box' => array(
				'title'   => esc_html__( 'Blog Settings', 'docdirect' ),
				'type'    => 'box',
				'options' => array(
					'enable_comments' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Comments', 'docdirect'),
						'desc'  => esc_html__('Enable or Disable Comments at post detail page. It will override post settings and remove Comments from all over the site.', 'docdirect'),
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
						'desc'  => esc_html__('Enable or Disable Author Information at post detail page.It will override post settings and remove coomments from all over the site.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
				)
			),
		)
	)
);