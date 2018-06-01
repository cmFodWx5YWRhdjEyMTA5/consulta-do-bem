<?php

if (!defined('FW')) {
    die('Forbidden');
}

$qa	=  array();
if( apply_filters('docdirect_get_theme_settings', 'qa_restriction') === 'paid' ){
	$qa['dd_qa'] = array(
			'type' => 'checkbox',
			'label' => esc_html__('Question and Answers included?', 'docdirect'),
			'desc' => esc_html__('If question and answers included then users will be able to post replies and also get questions on their profiles.', 'docdirect'),
		);
}

if( apply_filters('docdirect_get_packages_setting','default') === 'default' ){
	$options = array(
		'settings' => array(
			'title' => 'Packages Settings',
			'type' => 'box',
			'options' => array(
				'featured' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Make Featured?', 'docdirect'),
					'desc' => esc_html__('', 'docdirect'),
				),
				'pac_subtitle' => array(
					'type' => 'text',
					'attr' => array(),
					'label' => esc_html__('Package Sub Title', 'docdirect'),
					'desc' => esc_html__('Add Package Sub Title', 'docdirect'),
				),
				
				'short_description' => array(
					'type' => 'textarea',
					'attr' => array(),
					'label' => esc_html__('Package Description', 'docdirect'),
					'desc' => esc_html__('Add Package short description.', 'docdirect'),
				),
				'features' => array(
					'type'  => 'addable-option',
					'value' => array(),
					'attr'  => array(),
					'label' => esc_html__('Features', 'docdirect'),
					'desc'  => esc_html__('Add Package Feature', 'docdirect'),
					'help'  => esc_html__('', 'docdirect'),
					'option' => array( 'type' => 'text' ),
					'add-button-text' => esc_html__('Add Feature', 'docdirect'),
					'sortable' => true,
				),
				'price' => array(
					'type' => 'text',
					'attr' => array(),
					'label' => esc_html__('Price', 'docdirect'),
					'help' => esc_html__('Pleas add price for this package. for currency settings please go to Tools > Theme Settings > Payment Settings', 'docdirect'),
					'desc' => esc_html__('Add Price as : 75', 'docdirect'),
				),
				'duration' => array(
					'type' => 'text',
					'attr' => array(),
					'label' => esc_html__('Package Duration', 'docdirect'),
					'help' => esc_html__('Package Duation is in Days. Please add number of days for this package.', 'docdirect'),
					'desc' => esc_html__('Add Duration as : 30, please add only integer value', 'docdirect'),
				),
			)
		),
	);

} else{
	$options = array(
		'settings' => array(
			'title' => 'Packages Settings',
			'type' => 'box',
			'options' => array(
				'featured' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Make Featured?', 'docdirect'),
					'desc' => esc_html__('', 'docdirect'),
				),
				'pac_subtitle' => array(
					'type' => 'text',
					'attr' => array(),
					'label' => esc_html__('Package Sub Title', 'docdirect'),
					'desc' => esc_html__('Add Package Sub Title', 'docdirect'),
				),
				
				'short_description' => array(
					'type' => 'textarea',
					'attr' => array(),
					'label' => esc_html__('Package Description', 'docdirect'),
					'desc' => esc_html__('Add Package short description.', 'docdirect'),
				),
				'duration' => array(
					'type' => 'text',
					'attr' => array(),
					'label' => esc_html__('Package Duration', 'docdirect'),
					'help' => esc_html__('Package Duation is in Days. Please add number of days for this package.', 'docdirect'),
					'desc' => esc_html__('Add Duration as : 30, please add only integer value', 'docdirect'),
				),
				'price' => array(
					'type' => 'text',
					'attr' => array(),
					'label' => esc_html__('Package price', 'docdirect'),
					'desc' => esc_html__('Pleas add price for this package. for currency settings please go to Tools > Theme Settings > Payment Settings', 'docdirect'),
				),
				'featured_listing' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Featured listing included', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be in featured listing for given below number of days.', 'docdirect'),
				),
				'featured_expiry' => array(
					'type' => 'text',
					'label' => esc_html__('Featured expiry', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be in featured listing for given number of days.', 'docdirect'),
				),
				'appointments' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Appointments included', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be able to see bookings module.', 'docdirect'),
				),
				'profile_banner' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Profile Banner Included', 'docdirect'),
					'desc' => esc_html__('', 'docdirect'),
				),
				'insurance' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Insurance included', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be able to add insurance.', 'docdirect'),
				),
				'favorite' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Favorite Listings included', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be able to see favorite listings.', 'docdirect'),
				),
				'team' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Teams Management included', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be able to add teams.', 'docdirect'),
				),
				'schedules' => array(
					'type' => 'checkbox',
					'label' => esc_html__('Opening Hours/Schedules included', 'docdirect'),
					'desc' => esc_html__('If you enable this setting then users who buy this package, will be able to add Opening Hours/Schedules.', 'docdirect'),
				),
				'articles' => array(
                    'type' => 'slider',
                    'value' => 0,
                    'properties' => array(
                        'min' => 0,
                        'max' => 1000,
                        'sep' => 1,
                    ),
                    'desc' => esc_html__('Select to add number of articles in this package. Default article limit can be set from Theme Settings > Directory Settings > Articles Limit', 'docdirect'),
					'label' => esc_html__('Articles?', 'docdirect'),
                ),
				$qa
			)
		),
	);
}
