<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
	'map_height' => array(
        'type' => 'text',
        'value' => '400',
        'label' => esc_html__('Map height', 'docdirect'),
        'desc' => esc_html__('Add height in PX as : 200, Default is 300', 'docdirect'),
    ),
	'latitude' => array(
        'type' => 'text',
        'value' => '-0.127758',
        'label' => esc_html__('Latitude', 'docdirect'),
        'desc' => esc_html__('Add Latitude', 'docdirect'),
    ),
    'longitude' => array(
        'type' => 'text',
        'value' => '51.507351',
        'label' => esc_html__('Longitude', 'docdirect'),
        'desc' => esc_html__('Add Longitude', 'docdirect'),
    ),
    'map_zoom' => array(
        'type' => 'slider',
        'value' => 16,
        'properties' => array(
            'min' => 0,
            'max' => 20,
            'sep' => 1,
        ),
        'attr' => array(),
        'label' => esc_html__('Zoom Level', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
    ),
	'map_type' => array(
        'type' => 'select',
        'choices' => array(
            'ROADMAP' => esc_html__('ROADMAP', 'docdirect'),
            'HYBRID' => esc_html__('HYBRID', 'docdirect'),
			'SATELLITE' => esc_html__('SATELLITE', 'docdirect'),
            'TERRAIN' => esc_html__('TERRAIN', 'docdirect'),
        ),
        'label' => esc_html__('Map Type', 'docdirect'),
        'desc' => esc_html__('Select map type.', 'docdirect'),
    ),
	'map_styles' => array(
        'type' => 'select',
        'choices' => array(
            'none' => esc_html__('NONE', 'docdirect'),
			'view_1' => esc_html__('Default', 'docdirect'),
			'view_2' => esc_html__('View 2', 'docdirect'),
			'view_3' => esc_html__('View 3', 'docdirect'),
			'view_4' => esc_html__('View 4', 'docdirect'),
			'view_5' => esc_html__('View 5', 'docdirect'),
			'view_6' => esc_html__('View 6', 'docdirect'),
        ),
        'label' => esc_html__('Map Style', 'docdirect'),
        'desc' => esc_html__('Select map style. It will override map type.', 'docdirect'),
    ),
	'map_info' => array(
        'type' => 'textarea',
        'value' => '',
        'label' => esc_html__('Map Infobox content', 'docdirect'),
        'desc' => esc_html__('Enter the marker content', 'docdirect'),
    ),
	'info_box_width' => array(
        'type' => 'text',
        'value' => '250',
        'label' => esc_html__('Map Infobox width', 'docdirect'),
        'desc' => esc_html__('Set max width for the google map info box', 'docdirect'),
    ),
	'info_box_height' => array(
        'type' => 'text',
        'value' => '150',
        'label' => esc_html__('Map Infobox height', 'docdirect'),
        'desc' => esc_html__('Set max height for the google map info box', 'docdirect'),
    ),
    'marker' => array(
        'type' => 'upload',
        'attr' => array(),
        'label' => esc_html__('Marker', 'docdirect'),
        'desc' => esc_html__('Add Map Marker', 'docdirect'),
    ),
    'map_controls' => array(
        'type' => 'select',
        'choices' => array(
            'true' => esc_html__('OFF', 'docdirect'),
            'false' => esc_html__('ON', 'docdirect'),
        ),
        'label' => esc_html__('Map Controls', 'docdirect'),
        'desc' => esc_html__('Select map controls.', 'docdirect'),
    ),
	'map_dragable' => array(
        'type' => 'select',
        'choices' => array(
            'true' => esc_html__('Yes', 'docdirect'),
            'false' => esc_html__('NO', 'docdirect'),
        ),
        'label' => esc_html__('Map Dragable', 'docdirect'),
        'desc' => esc_html__('Select map dragable?', 'docdirect'),
    ),
    'scroll' => array(
        'type' => 'select',
        'choices' => array(
            'false' => 'No',
            'true' => 'Yes',
        ),
        'label' => esc_html__('Scroll', 'docdirect'),
        'desc' => esc_html__('Enable/Disbale Mouse over scroll.', 'docdirect'),
    ),
);
