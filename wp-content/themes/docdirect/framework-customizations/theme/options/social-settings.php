<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}
$options = array(
    'social_icons' => array(
        'title'   => esc_html__( 'Social Media', 'docdirect' ),
        'type'    => 'tab',
        'options' => array(
            'social_icons' => array(
                'label'	=> esc_html__( 'Social Icons', 'docdirect' ),
                'type'	=> 'addable-popup',
                'value'	=> array(),
                'desc'	=> esc_html__( 'Add Social Icons as much as you want. Choose the icon, url and the title', 'docdirect' ),
                'popup-options'  => array(
                    'social_name'     => array(
                        'label' => esc_html__( 'Title', 'docdirect' ),
                        'type'  => 'text',
                        'value' => 'Name',
                        'desc'  => esc_html__( 'The Title of the Link', 'docdirect' )
                    ),
                    'social_icons_list'     => array(
						'type'  => 'new-icon',
						'value' => 'fa-smile-o',
						'attr'  => array(),
						'label' => esc_html__('Choos Icon', 'docdirect'),
						'desc'  => esc_html__('', 'docdirect'),
						'help'  => esc_html__('', 'docdirect'),
					),
                    'social_url'     => array(
                        'label' => esc_html__( 'Url', 'docdirect' ),
                        'type'  => 'text',
                        'value' => '#',
                        'desc'  => esc_html__( 'The link to the social profile.', 'docdirect' )
                    ),
                ),
                'template' => '{{- social_name }}',
            ),
            'social_icon_target' => array(
                'label' => esc_html__( 'Open in New Window', 'docdirect' ),
                'type'  => 'switch',
                'desc'  => esc_html__( 'The links will be opened into new tab or window when your visitors clicked on the link.', 'docdirect' )
            ),
        ),
    ),



);
