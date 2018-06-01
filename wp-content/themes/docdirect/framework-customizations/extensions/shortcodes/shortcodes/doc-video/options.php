<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'video_title' => array(
        'type' => 'text',
        'label' => esc_html__('Title', 'docdirect'),
    ),
    
     'video_link' => array(
        'type' => 'text',
         'value' => '#',
        'label' => esc_html__('video url', 'docdirect'),
    ),
     'video_image' => array(
        'type' => 'upload',
         'value' => '',
        'label' => esc_html__('Upload the image', 'docdirect'),
    ),
    
);
