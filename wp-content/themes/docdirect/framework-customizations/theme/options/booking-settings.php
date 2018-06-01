<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$currencies	= docdirect_prepare_currency_symbols();
$currencies_array	= array();
foreach($currencies as $key => $value ){
	$currencies_array[$key] = $value['name'].'-'.$value['code'];
}

$process	= '';
$process_booking	= '';
if( class_exists( 'DocDirectGlobalSettings' ) ) {
	$plugin_url	= DocDirectGlobalSettings::get_plugin_url();
	$process	   = $plugin_url. '/payments/process.php';
	$process_booking	   = $plugin_url. '/payments/booking.php';
}

$options = array(
	'bk_payments' => array(
		'title'   => esc_html__( 'Booking Settings', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'booking_settings' => array(
                'title' => esc_html__('Booking User Payments', 'docdirect'),
                'type' => 'tab',
                'options' => array(
					'user_disable_stripe' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Enable Stripe', 'docdirect'),
                        'desc' => esc_html__('Enable/Disable Stripe(Credit cards) payment gateway for users to get payment online.', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
					'user_disable_paypal' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Enable PayPal', 'docdirect'),
                        'desc' => esc_html__('Enable/Disable PayPal payment gateway for users to get payment online.', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
					'default_booking'     => array(
						'type'  => 'html',
						'value' => '',
						'label' => esc_html__('', 'docdirect'),
						'desc'  => esc_html__('This is only used when fron-end user want to check your testing payments.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'html'  => '',
					),
					'user_enable_sandbox' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Enable Sandbox Mode for paypal', 'docdirect'),
                        'desc' => esc_html__('', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
					'booking_listner_url'     => array(
						'label' => esc_html__( 'IPN Listner URL', 'docdirect' ),
						'type'  => 'text',
						'value' => $process_booking,
						'desc'  => esc_html__( 'Please don\'t change it. This is only for development purpose.', 'docdirect' )
					),
                )
            ),
		)
	)
);