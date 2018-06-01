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
	'payments' => array(
		'title'   => esc_html__( 'Payment Settings', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'currency-box' => array(
				'title'   => esc_html__( 'Currency Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					'currency_select'     => array(
						'label' => esc_html__( 'Select Currency', 'docdirect' ),
						'type'  => 'select',
						'attr'  => array( 'class' => 'currency_symbol' ),
						'choices' => $currencies_array,
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'currency_sign'     => array(
						'label' => esc_html__( 'Currency Sign', 'docdirect' ),
						'type'  => 'text',
						'value' => '$',
						'desc'  => esc_html__( '', 'docdirect' )
					),
				),
			),
			'paypal_settings' => array(
                'title' => esc_html__('Paypal Settings', 'docdirect'),
                'type' => 'tab',
                'options' => array(
					'paypal_enable' => array(
                        'type' => 'switch',
                        'value' => 'off',
                        'attr' => array(),
                        'label' => esc_html__('Enable gateway', 'docdirect'),
                        'desc' => esc_html__('Enable this gateway at front end.', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
					'paypal_enable_sandbox' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Enable Sandbox Mode', 'docdirect'),
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
					'paypal_bussiness_email'     => array(
						'label' => esc_html__( 'Paypal Business Email', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'paypal_listner_url'     => array(
						'label' => esc_html__( 'IPN Listner URL', 'docdirect' ),
						'type'  => 'text',
						'value' => $process,
						'desc'  => esc_html__( '', 'docdirect' )
					),
                )
            ),
			'stripe_settings' => array(
                'title' => esc_html__('Strip Settings', 'docdirect'),
                'type' => 'tab',
                'options' => array(
					'enable_strip' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Enable Payment Gateway', 'docdirect'),
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
					'stripe_secret'     => array(
						'label' => esc_html__( 'Secret Key', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'stripe_publishable'     => array(
						'label' => esc_html__( 'Publishable Key', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'stripe_site'     => array(
						'label' => esc_html__( 'Site Name', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'stripe_language'     => array(
						'label' => esc_html__( 'Language', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc' => wp_kses( __( 'Please check this page for supported languages: <a href="https://stripe.com/docs/checkout#supported-languages" target="_blank"> View </a> <br> Default will be english', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
					),
					'stripe_decimal' => array(
                        'type' => 'switch',
                        'value' => '2',
                        'attr' => array(),
                        'label' => esc_html__('Decimals', 'docdirect'),
                        'desc' => wp_kses( __( 'Please check this page: <a href="https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support" target="_blank"> DECIMAL INFO </a> <br> If your currency listed in this page please use decimal number 0', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                        'left-choice' => array(
                            'value' => '2',
                            'label' => esc_html__('2', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => '0',
                            'label' => esc_html__('0', 'docdirect'),
                        ),
                    ),
                )
            ),
			'authorize_settings' => array(
                'title' => esc_html__('Authorize.net Settings', 'docdirect'),
                'type' => 'tab',
                'options' => array(
					'authorize_enable' => array(
                        'type' => 'switch',
                        'value' => 'off',
                        'attr' => array(),
                        'label' => esc_html__('Enable gateway', 'docdirect'),
                        'desc' => esc_html__('Enable this gateway at front end.', 'docdirect'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'docdirect'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'docdirect'),
                        ),
                    ),
					'authorize_enable_sandbox' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Enable Sandbox Mode', 'docdirect'),
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
					'authorize_login_id'     => array(
						'label' => esc_html__( 'Login ID', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'authorize_transaction_key'     => array(
						'label' => esc_html__( 'Transaction Key', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( '', 'docdirect' )
					),
					'authorize_listner_url'     => array(
						'label' => esc_html__( 'IPN Listner URL', 'docdirect' ),
						'type'  => 'text',
						'value' => $process,
						'desc'  => esc_html__( 'Please don\'t change it. This is only for development purpose.', 'docdirect' )
					),
                )
            ),
			'bank_settings' => array(
                'title' => esc_html__('Bank Transfer', 'docdirect'),
                'type' => 'tab',
                'options' => array(
					'default_gateway'     => array(
						'type'  => 'html',
						'value' => 'This is default gateway.',
						'label' => esc_html__('', 'docdirect'),
						'desc'  => esc_html__('Bank Transfer is default gateway for payments.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'html'  => '',
					),
					'bank_name'     => array(
						'label' => esc_html__( 'Bank Information', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( 'Enter the bank name to which you want to transfer payment', 'docdirect' )
					),
					'bank_account'     => array(
						'label' => esc_html__( 'Account Number', 'docdirect' ),
						'type'  => 'text',
						'value' => '',
						'desc'  => esc_html__( 'Enter your bank Account ID', 'docdirect' )
					),
					'other_information'     => array(
						'label' => esc_html__( 'User Information', 'docdirect' ),
						'type'  => 'textarea',
						'value' => '',
						'desc'  => esc_html__( 'Add Some information to show user related to bank or payments.', 'docdirect' )
					),
                )
            ),
		)
	)
);