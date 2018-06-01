<?php

if (!defined('FW')) {
    die('Forbidden');
}

$lists	= array();
if( function_exists('docdirect_mailchimp_list') ){
	$lists	= docdirect_mailchimp_list();
}

$options = array(
    'api_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Api Settings', 'docdirect'),
        'options' => array(
			'mailchimp' => array(
                'title' => esc_html__('Mail Chimp', 'docdirect'),
                'type' => 'tab',
                'options' => array(
                    'mailchimp_key' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => esc_html__('MailChimp Key', 'docdirect'),
                        'desc' => wp_kses( __( 'Get Api key From <a href="https://us11.admin.mailchimp.com/account/api/" target="_blank"> Get API KEY </a> <br/> You can create list <a href="https://us11.admin.mailchimp.com/lists/" target="_blank">here</a>', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
                    'mailchimp_list' => array(
                        'type' => 'select',
                        'label' => esc_html__('MailChimp List', 'docdirect'),
                        'choices' => $lists,
                    )
                )
            ),
			'flickr' => array(
                'title' => esc_html__('Flickr', 'docdirect'),
                'type' => 'tab',
                'options' => array(
                    'flickr_key' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => esc_html__('Flickr Key', 'docdirect'),
                        'desc' => esc_html__('Enter flickr key here.', 'docdirect'),
                    ),
					'flickr_secret' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => esc_html__('Flickr Secret', 'docdirect'),
                        'desc' => esc_html__('Enter your flickr secret here.', 'docdirect'),
                    ),
                )
            ),
			'google' => array(
                'title' => esc_html__('Google', 'docdirect'),
                'type' => 'tab',
                'options' => array(
                    'google_key' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => esc_html__('Google Map Key', 'docdirect'),
						'desc' => wp_kses( __( 'Enter google map key here. It will be used for google maps. Get and Api key From <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> Get API KEY </a>', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
                )
            ),
        )
    )
);


