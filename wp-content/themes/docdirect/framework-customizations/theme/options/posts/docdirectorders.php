<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'settings' => array(
        'title' => 'Transaction History',
        'type' => 'box',
        'options' => array(
			'transaction_id' => array(
				'type'  => 'text',
				'label' => esc_html__('Transaction ID', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
			),
			'order_status' => array(
				'type'  => 'select',
				'label' => esc_html__('Order Status', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
				'choices'	=> docdirect_prepare_order_status('array',''),
			),
			'payment_method' => array(
				'type'  => 'select',
				'label' => esc_html__('Payment Option', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
				'choices'	=> docdirect_prepare_payment_type('array',''),
			),
			'package' => array(
				'type'  => 'select',
				'label' => esc_html__('Package Type', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
				'choices'	=> docdirect_prepare_custom_posts('directory_packages')
			),
			'price' => array(
				'type'  => 'text',
				'label' => esc_html__('Paid Amount', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
			),
			'payment_date' => array(
				'type'  => 'datetime-picker',
				'label' => esc_html__('Payment Date', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
				'datetime-picker' => array(
					'format'        => 'Y-m-d H:i:s', // Format datetime.
					'maxDate'       => false,  // By default there is not maximum date , set a date in the datetime format.
					'minDate'       => false,  // By default minimum date will be current day, set a date in the datetime format.
					'timepicker'    => true,   // Show timepicker.
					'datepicker'    => true,   // Show datepicker.
					'defaultTime'   => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
				),
			),
			'expiry_date' => array(
				'type'  => 'datetime-picker',
				'label' => esc_html__('Expiry Date', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('To udpate user expiry date please add expiry date here. Note: if user have already a package then this wll increase no of days in user package.', 'docdirect'),
				'datetime-picker' => array(
					'format'        => 'Y-m-d H:i:s', // Format datetime.
					'maxDate'       => false,  // By default there is not maximum date , set a date in the datetime format.
					'minDate'       => false,  // By default minimum date will be current day, set a date in the datetime format.
					'timepicker'    => true,   // Show timepicker.
					'datepicker'    => true,   // Show datepicker.
					'defaultTime'   => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
				),
			),
			'payment_user' => array(
				'type'  => 'select',
				'label' => esc_html__('User', 'docdirect'),
				'hint' => esc_html__('', 'docdirect'),
				'desc'  => esc_html__('', 'docdirect'),
				'choices'	=> docdirect_prepare_user_list(),
			),
		)
    ),
);
