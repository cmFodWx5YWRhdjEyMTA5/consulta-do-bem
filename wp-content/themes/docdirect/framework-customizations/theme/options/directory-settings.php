<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'directory' => array(
		'title'   => esc_html__( 'Directory Settings', 'docdirect' ),
		'type'    => 'tab',
		'options' => array(
			'general-settings' => array(
				'title'   => esc_html__( 'General Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					'calendar_format'    => array(
						'label' => esc_html__( 'Calender Date Format', 'docdirect' ),
						'type'  => 'select',
						'value'  => 'Y-m-d',
						'desc' => esc_html__('Select your calender date format.', 'docdirect'),
						'choices'	=> array(
							'Y-m-d'	  => 'Y-m-d',
							'd-m-Y'	  => 'd-m-Y',
						)
					),
					'calendar_locale'    => array(
						'label' => esc_html__( 'Calender Language', 'docdirect' ),
						'type'  => 'text',
						'value'  => '',
						'desc' => wp_kses( __( 'Add 639-1 code. It will be two digit code like "en" for english. Leave it empty to use deualt. Click here to get code <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank"> Get Code </a>', 'docdirect' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
					),
					'user_page_slug' => array(
						'type'  => 'text',
						'value'  => 'user',
                        'label' => esc_html__('User detail page slug', 'docdirect'),
						'desc'  => esc_html__('You can add user detail page slug here. default is : user', 'docdirect'),
                        'help'  => esc_html__('', 'docdirect'),
					),
					'dir_review_status'     => array(
						'label' => esc_html__( 'Review Status', 'docdirect' ),
						'type'  => 'select',
						'desc' => esc_html__('Please select review status when it post.', 'docdirect'),
						'help' => esc_html__('If you want to publish review then select status as Publish, otherwise select Pending to make it draft( Only admin ) can approve it.', 'docdirect'),
						'choices'	=> array(
							'pending'	  => esc_html__( 'Pending', 'docdirect' ),
							'publish'	=> esc_html__( 'Publish', 'docdirect' ),
						)
					),
					'verify_user'     => array(
						'label' => esc_html__( 'Verify User', 'docdirect' ),
						'type'  => 'select',
						'value' => 'none',
						'desc' => esc_html__('Verify user before publicly available. Note: If you select "Need to verify, after registration" then user will not be shown until it will be verified by site owner. If you select "Verify automatically" then user will get an email for verifications. After clicking link user will be published and available at the website.', 'docdirect'),
						'choices'	=> array(
							'verified'  => 'Verify automatically',
							'none'	=> 'Need to verify, after registeration',
						)
					),
					'article_limit' => array(
						'type' => 'slider',
						'value' => 0,
						'properties' => array(
							'min' => 0,
							'max' => 1000,
							'step' => 1, // Set slider step. Always > 0. Could be fractional.
						),
						'label' => esc_html__('Articles Limit?', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
						'desc' => esc_html__('Select default article limit for each user. This limit will be added to provider purchased package. eg if package have 5 article and default limit is 3 then total articles limit will be 5 + 3 = 8', 'docdirect'),
						'images_only' => true,
					),
					'approve_articles'     => array(
						'label' => esc_html__( 'Article Approval', 'docdirect' ),
						'type'  => 'select',
						'value' => 'auto',
						'desc' => esc_html__('Select either articles should publish or needs admin approval to publish.', 'docdirect'),
						'help' => esc_html__('This will work only when article extension will be activated. Please go to Unyson > Articles > Activate', 'docdirect'),
						'choices'	=> array(
							'auto'  => 'Auto Approve',
							'need_approval'	=> 'Need to approve by admin',
						)
					),
					'speciality_limit' => array(
						'type' => 'slider',
						'value' => 50,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'step' => 1, // Set slider step. Always > 0. Could be fractional.
						),
						'label' => esc_html__('Speciality Limit?', 'docdirect'),
						'hint' => esc_html__('How much specialities a provider can select in their dashboard.', 'docdirect'),
						'desc' => esc_html__('', 'docdirect'),
						'images_only' => true,
					),
				)
			),
			'company-box' => array(
				'title'   => esc_html__( 'Company Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					'support-section' => array(
						'type' => 'html',
						'html' => 'Support Section',
						'label' => esc_html__('', 'docdirect'),
						'desc' => esc_html__('please note that if you want to show company profile at user dashboard then please do settings which is given below. ', 'docdirect'),
						'help' => esc_html__('', 'docdirect'),
						'images_only' => true,
					),
					'company_profile' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Show Company Profile', 'docdirect'),
						'desc'  => esc_html__('Enable Company Profile section at user dashboard.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'com_description' => array(
                        'type'  => 'textarea',
                        'label' => esc_html__('Add company description', 'docdirect'),
						'hint' => esc_html__('Leave it empty to hide.', 'docdirect'),
                        'desc'  => esc_html__('', 'docdirect'),
                    ),
					'com_logo' => array(
                        'type'  => 'upload',
                        'label' => esc_html__('Add Logo', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Leave it empty to hide.', 'docdirect'),
                        'images_only' => true,
                    ),
					'support_box' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Show support information', 'docdirect'),
						'desc'  => esc_html__('Enable support information section at user dashboard.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'support_heading' => array(
                        'type'  => 'text',
                        'label' => esc_html__('Support Heading', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Leave it empty to hide.', 'docdirect'),
                    ),
					'support_address' => array(
                        'type'  => 'text',
                        'label' => esc_html__('Address', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Leave it empty to hide.', 'docdirect'),
                    ),
					'support_phone' => array(
                        'type'  => 'text',
                        'label' => esc_html__('Phone No', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Leave it empty to hide.', 'docdirect'),
                    ),
					'support_email' => array(
                        'type'  => 'text',
                        'label' => esc_html__('Email address', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Leave it empty to hide.', 'docdirect'),
                    ),
					'support_fax' => array(
                        'type'  => 'text',
                        'label' => esc_html__('Fax', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Leave it empty to hide.', 'docdirect'),
                    ),
				),
			),
			'general-box' => array(
				'title'   => esc_html__( 'Directory Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					'dir_profile_page'     => array(
						'label' => esc_html__( 'User Profile Page', 'docdirect' ),
						'type'  => 'multi-select',
						'population' => 'posts',
						'source' => 'page',
						'desc' => esc_html__('User Profile Page', 'docdirect'),
						'limit'	=> 1
					),
					'dir_datasize' => array(
                        'type'  => 'text',
						'value' => '5120',
						'attr'  => array(),
						'label' => esc_html__('Upload Size', 'docdirect'),
						'desc'  => esc_html__('Maximum Image Upload Size. Max 5MB, add in bytes. for example 5MB = 5242880 ( 1024x1024x5 )', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),
					'directory_prefix' => array(
                        'type'  => 'text',
						'value' => 'DD-',
						'label' => esc_html__('Prefixes for orders.', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),
				),
			),
			'dashboard-box' => array(
				'title'   => esc_html__( 'Dashboard Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					'delete_account_text'     => array(
						'label' => esc_html__( 'Account Deletion Description.', 'docdirect' ),
						'type'  => 'textarea',
						'desc' => esc_html__('Add message to show it in security settings, wheb user will go to delete your account.', 'docdirect'),
					),
				),
			),
			'map-box' => array(
				'title'   => esc_html__( 'Map Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					'dir_map_type'     => array(
						'label' => esc_html__( 'Map Type', 'docdirect' ),
						'type'  => 'select',
						'desc' => esc_html__('Select Map Type.', 'docdirect'),
						'choices'	=> array(
							'ROADMAP'	  => 'ROADMAP',
							'SATELLITE'	=> 'SATELLITE',
							'HYBRID'	   => 'HYBRID',
							'TERRAIN'	  => 'TERRAIN',
						)
					),
					'map_styles'     => array(
						'label' => esc_html__( 'Map Style', 'docdirect' ),
						'type'  => 'select',
						'desc' => esc_html__('Select map style. It will override map type.', 'docdirect'),
						'choices'	=> array(
							'none'   => esc_html__('NONE', 'docdirect'),
							'view_1' => esc_html__('Default','docdirect'),
							'view_2' => esc_html__('View 2', 'docdirect'),
							'view_3' => esc_html__('View 3', 'docdirect'),
							'view_4' => esc_html__('View 4', 'docdirect'),
							'view_5' => esc_html__('View 5', 'docdirect'),
							'view_6' => esc_html__('View 6', 'docdirect'),
						)
					),
					'dir_map_scroll'     => array(
						'label' => esc_html__( 'Map Dragable', 'docdirect' ),
						'type'  => 'select',
						'desc' => esc_html__('Enbale map dragable', 'docdirect'),
						'value'  => 'false',
						'choices'	=> array(
							'false'	=> esc_html__('No', 'docdirect'),
							'true'	 => esc_html__('Yes', 'docdirect'),
						)
					),
					'dir_cluster_marker' => array(
                        'type'  => 'upload',
                        'label' => esc_html__('Cluster Map Marker', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Default Cluster map marker.', 'docdirect'),
                        'images_only' => true,
                    ),
					'dir_cluster_color' => array(
                        'type'  => 'color-picker',
						'value' => '#505050',
						'attr'  => array(),
						'label' => esc_html__('Map Cluster Color', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),
					'dir_map_marker' => array(
                        'type'  => 'upload',
                        'label' => esc_html__('Map Marker', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Default map marker for all categories. Priority will be set from Directory Types.', 'docdirect'),
                        'images_only' => true,
                    ),
					'dir_zoom' => array(
                        'type'  => 'slider',
						'value' => 12,
						'properties' => array(
							'min' => 1,
							'max' => 16,
							'step' => 1, // Set slider step. Always > 0. Could be fractional.
						),
                        'label' => esc_html__('Map Zoom', 'docdirect'),
						'hint' => esc_html__('', 'docdirect'),
                        'desc'  => esc_html__('Select map zoom level', 'docdirect'),
                        'images_only' => true,
                    ),
					
					'dir_latitude' => array(
                        'type'  => 'text',
						'value' => '51.5001524',
						'attr'  => array(),
						'label' => esc_html__('Latitude', 'docdirect'),
						'desc'  => esc_html__('Default Latitude for map on search result page and also for search form elements.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),
					'dir_longitude' => array(
                        'type'  => 'text',
						'value' => '-0.1262362',
						'attr'  => array(),
						'label' => esc_html__('Longitude', 'docdirect'),
						'desc'  => esc_html__('Default longitude for map on search result page and also for search form elements.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
                    ),	
				),
			),
			'search-box' => array(
				'title'   => esc_html__( 'Search Settings', 'docdirect' ),
				'type'    => 'tab',
				'options' => array(
					
					'search_page_map' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Search result map', 'docdirect'),
						'desc'  => esc_html__('Enable/Disble google map at search page.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_search_view' => array(
                        'type'  => 'select',
						'value' => 'list',
						'attr'  => array(),
						'label' => esc_html__('Search Listing View', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'choices'  => array(
							'list'	=> esc_html__('List v1', 'docdirect'),
							'list_v2'	=> esc_html__('List v2', 'docdirect'),								
							'grid'	=> esc_html__('Grid v1', 'docdirect'),
							'grid_v2'	=> esc_html__('Grid v2', 'docdirect'),
						),
                    ),
					'dir_listing_type' => array(
                        'type'  => 'select',
						'value' => 'top',
						'attr'  => array(),
						'label' => esc_html__('Search page map position', 'docdirect'),
						'desc'  => esc_html__('Select search page map position. It will only work with Grid V1 and List V1', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'choices'  => array(
							'top'	=> esc_html__('Top', 'docdirect'),
							'left'	=> esc_html__('left', 'docdirect'),
						),
                    ),
					'dir_search_pagination' => array(
                        'type'  => 'slider',
						'attr'  => array(),
						'label' => esc_html__('Search Result per page', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'value' => 10,
						'properties' => array(
							'min' => 1,
							'max' => 100,
							'step' => 1, // Set slider step. Always > 0. Could be fractional.
						),
                    ),
					'dir_search_page'     => array(
						'label' => esc_html__( 'Search Page', 'docdirect' ),
						'type'  => 'multi-select',
						'population' => 'posts',
						'source' => 'page',
						'desc' => esc_html__('Search result page.', 'docdirect'),
						'limit'	=> 1
					),
					'dir_keywords' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Keywords Search', 'docdirect'),
						'desc'  => esc_html__('Enable Keywords Search', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_phote' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Phote Search?', 'docdirect'),
						'desc'  => esc_html__('Enable search filter by profile phote.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_appointments' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Appointment Search?', 'docdirect'),
						'desc'  => esc_html__('Enable search filter by appointments.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'zip_code_search' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Zip Code', 'docdirect'),
						'desc'  => esc_html__('Enable/Disable Zip Code Search', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_location' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Geo Location Autocomplete', 'docdirect'),
						'desc'  => esc_html__('Enable geo location autocomplete field', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_radius' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Radius Search', 'docdirect'),
						'desc'  => esc_html__('Enable Radius Search, Note it will be display when location will be enable.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_default_radius' => array(
                        'type'  => 'slider',
						'attr'  => array(),
						'label' => esc_html__('Default radius', 'docdirect'),
						'desc'  => esc_html__('Please select default radius for radius slider.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'value' => 50,
						'properties' => array(
							'min' => 1,
							'max' => 1000,
							'step' => 5, // Set slider step. Always > 0. Could be fractional.
						),
                    ),
					'dir_max_radius' => array(
                        'type'  => 'slider',
						'attr'  => array(),
						'label' => esc_html__('Maximum radius', 'docdirect'),
						'desc'  => esc_html__('Please select maximum radius for radius slider.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'value' => 300,
						'properties' => array(
							'min' => 1,
							'max' => 1000,
							'step' => 5, // Set slider step. Always > 0. Could be fractional.
						),
                    ),
					'dir_distance_type' => array(
                        'type'  => 'select',
						'value' => 'list',
						'attr'  => array(),
						'label' => esc_html__('Distance in?', 'docdirect'),
						'desc'  => esc_html__('Search location radius in miles or kilometers.', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
						'choices'  => array(
							'mi'	=> esc_html__('Miles', 'docdirect'),
							'km'	=> esc_html__('Kilometers', 'docdirect'),								
						),
                    ),
					'dir_geo' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Geo location', 'docdirect'),
						'desc'  => esc_html__('Enable geo location locate me button.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'country_restrict' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => false,
						'picker' => array(
							'gadget' => array(
								'type'  => 'switch',
								'value' => 'disable',
								'label' => esc_html__('Restrict Country', 'docdirect'),
								'desc'  => esc_html__('Restrict Country in geo location auto complete field.', 'docdirect'),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'docdirect'),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'docdirect'),
								),
							)
						),
						'choices' => array(
							'enable' => array(
								'country_code' => array(
									'type' => 'text',
									'value' => 'us',
									'label' => esc_html__('Country Code', 'docdirect'),
									'desc' => wp_kses( __( 'Add your 2 digit country code eg : us, to check country code please visit link <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank"> Get Code </a>', 'docdirect' ),array(
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
						'show_borders' => true,
					),
					'language_search' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Language search', 'docdirect'),
						'desc'  => esc_html__('Enable or disbale language search.', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					
					'dir_search_cities' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Cities base search', 'docdirect'),
						'desc'  => esc_html__('Enable cities base search', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
					'dir_search_insurance' => array(
						'type'  => 'switch',
						'value' => 'enable',
						'label' => esc_html__('Insurance base search', 'docdirect'),
						'desc'  => esc_html__('Enable insurance base search', 'docdirect'),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'docdirect'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'docdirect'),
						),
					),
				),
			),
		)
	)
);