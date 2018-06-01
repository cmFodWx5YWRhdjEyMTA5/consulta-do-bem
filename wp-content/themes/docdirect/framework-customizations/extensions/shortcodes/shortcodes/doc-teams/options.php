<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'heading' => array(
        'label' => esc_html__('Title', 'docdirect'),
        'desc' => esc_html__('Add Title Here', 'docdirect'),
        'type' => 'text',
        'value' => ''
    ),
    'team_description' => array(
        'label' => esc_html__('Description', 'docdirect'),
        'desc' => esc_html__('Add Description Here', 'docdirect'),
        'type' => 'textarea',
        'value' => ''
    ),
    'team_member' => array(
        'label' => esc_html__('Add Team member', 'docdirect'),
        'type' => 'addable-popup',
        'template' => '{{- name }}',
        'value' => array(),
        'desc' => esc_html__('Add Team Member', 'docdirect'),
        'popup-options' => array(
            'avatar' => array(
                'label' => esc_html__('Team Member Image', 'docdirect'),
                'desc' => esc_html__('Either upload a new, or choose an existing image from your media library', 'docdirect'),
                'type' => 'upload'
            ),
            'name' => array(
                'label' => esc_html__('Team Member Name', 'docdirect'),
                'desc' => esc_html__('Name of the person', 'docdirect'),
                'type' => 'text',
                'value' => ''
            ),
            
            'designation' => array(
                'label' => esc_html__('Designation', 'docdirect'),
                'desc' => esc_html__('', 'docdirect'),
                'type' => 'text',
                'value' => ''
            ),
            'social_icons' => array(
                'label' => esc_html__('Social Icons', 'docdirect'),
                'type' => 'addable-popup',
                'template' => '{{- name }}',
                'value' => array(),
                'desc' => esc_html__('Add Social Icons as much as you want. Choose the icon, url and the title', 'docdirect'),
                'popup-options' => array(
                    'name' => array(
                        'label' => esc_html__('Title', 'docdirect'),
                        'type' => 'text',
                        'value' => 'google',
                        'desc' => esc_html__('The Title of the Link', 'docdirect')
                    ),
                    'icon' => array(
                        'type' => 'new-icon',
                        'value' => 'fa-smile-o',
                        'attr' => array(),
                        'label' => esc_html__('Choos Icon', 'docdirect'),
                        'desc' => esc_html__('', 'docdirect'),
                        'help' => esc_html__('', 'docdirect'),
                    ),
                    'url' => array(
                        'label' => esc_html__('Url', 'docdirect'),
                        'type' => 'text',
                        'value' => '#',
                        'desc' => esc_html__('The link to the social profile.', 'docdirect')
                    ),
                    'color' => array(
                        'label' => esc_html__('Icon Color', 'docdirect'),
                        'type' => 'color-picker',
                        'value' => '',
                        'desc' => esc_html__('Add Icon Color', 'docdirect'),
                    ),
                ),
            ),
        ),
    ),
);
