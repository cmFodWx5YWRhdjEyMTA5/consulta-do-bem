<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}
$options = array(
    'commingsoon_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Coming Soon', 'docdirect'),
        'options' => array(
            'commingsoon-box' => array(
                'title'   => esc_html__( 'Coming Soon Settings', 'docdirect' ),
                'type'    => 'box',
                'options' => array(
                    'maintenance' => array(
                        'type'  => 'switch',
                        'value' => 'disable',
                        'label' => esc_html__('Maintenance Mode', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'docdirect'),
                        ),
                    ),
					'comming_title' => array(
                        'type'  => 'text',
						'label' => esc_html__('Title', 'docdirect'),
						'value' =>'Coming Soon!',
                        'desc' => esc_html__('', 'docdirect'),
                    ),
					'comming_description' => array(
                        'type'  => 'textarea',
						'label' => esc_html__('Description', 'docdirect'),
						'value' =>'Stay tuned, we are launching very soon...',
                        'desc' => esc_html__('', 'docdirect'),
                    ),
                    'background' => array(
                        'type'  => 'upload',
                        'label' => esc_html__('Background Image', 'docdirect'),
                        'desc'  => esc_html__('Upload Your background image on coming soon page.', 'docdirect'),
                        'images_only' => true,
                    ),
                    'date' => array(
                        'type'  => 'date-picker',
                        'label' => esc_html__('Choose Date', 'docdirect'),
                        'monday-first' => true, // The week will begin with Monday; for Sunday, set to false
                        'min-date' => date('m,d,Y'), // By default minimum date will be current day. Set a date in format d-m-Y as a start date
                        'max-date' => null, // By default there is not maximum date. Set a date in format d-m-Y as a start date
                    ),
                )
            ),
        )
    )
);
