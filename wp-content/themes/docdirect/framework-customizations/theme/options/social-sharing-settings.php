<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array (
    'social_sharing' => array (
        'title'   => esc_html__('Sharing Sharing' , 'docdirect') ,
        'type'    => 'tab' ,
        'options' => array (
            'social_facebook'  => array (
                'label'        => esc_html__('Facebook' , 'docdirect') ,
                'type'         => 'switch' ,
                'value'        => 'enable' ,
                'desc'         => esc_html__('Sharing on/off' , 'docdirect') ,
                'left-choice'  => array (
                    'value' => 'enable' ,
                    'label' => esc_html__('Enable' , 'docdirect') ,
                ) ,
                'right-choice' => array (
                    'value' => 'disable' ,
                    'label' => esc_html__('Disable' , 'docdirect') ,
                ) ,
            ) ,
			'social_twitter' => array(
				'type'         => 'multi-picker',
				'label'        => false,
				'desc'         => false,
				'picker'       => array(
					'gadget' => array(
						'label'        => esc_html__('Twitter' , 'docdirect') ,
						'type'         => 'switch' ,
						'value'        => 'enable' ,
						'desc'         => esc_html__('Sharing on/off' , 'docdirect') ,
						'left-choice'  => array (
							'value' => 'enable' ,
							'label' => esc_html__('Enable' , 'docdirect') ,
						) ,
						'right-choice' => array (
							'value' => 'disable' ,
							'label' => esc_html__('Disable' , 'docdirect') ,
						) ,
					)
				),
				'choices'      => array(
					'enable'  => array(
						'twitter_username' => array (
							'type'  => 'text' ,
							'value' => '' ,
							'label' => esc_html__('Twitter username' , 'docdirect') ,
							'desc'  => esc_html__('This will be used in the tweet for the via parameter. The site name will be used if no twitter username is provided. Do not include the @' , 'docdirect') ,
						) ,
					),
				)
			),
			'social_gmail'   => array (
                'label'        => esc_html__('Google Share' , 'docdirect') ,
                'type'         => 'switch' ,
                'value'        => 'enable' ,
                'desc'         => esc_html__('Sharing on/off' , 'docdirect') ,
                'left-choice'  => array (
                    'value' => 'enable' ,
                    'label' => esc_html__('Enable' , 'docdirect') ,
                ) ,
                'right-choice' => array (
                    'value' => 'disable' ,
                    'label' => esc_html__('Disable' , 'docdirect') ,
                ) ,
            ) ,
			'social_pinterest'   => array (
                'label'        => esc_html__('Pinterest Share' , 'docdirect') ,
                'type'         => 'switch' ,
                'value'        => 'enable' ,
                'desc'         => esc_html__('Sharing on/off' , 'docdirect') ,
                'left-choice'  => array (
                    'value' => 'enable' ,
                    'label' => esc_html__('Enable' , 'docdirect') ,
                ) ,
                'right-choice' => array (
                    'value' => 'disable' ,
                    'label' => esc_html__('Disable' , 'docdirect') ,
                ) ,
            ) ,
        ) ,
    ) ,
);
