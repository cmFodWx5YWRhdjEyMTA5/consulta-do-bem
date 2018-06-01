<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'counter_popup' => array(
        'type' => 'addable-popup',
        'label' => esc_html__('Add Counter', 'docdirect'),
        'desc' => esc_html__('', 'docdirect'),
        'template' => '{{- counter_title }}',
        'popup-title' => null,
        'size' => 'small', // small, medium, large
        'limit' => 0, // limit the number of popup`s that can be added
        'add-button-text' => esc_html__('Counter', 'docdirect'),
        'sortable' => true,
        'popup-options' => array(
            'counter_title' => array(
                'label' => esc_html__('Counter Title', 'docdirect'),
                'type' => 'text',
                'value' => '',
                'desc' => esc_html__('Add counter title', 'docdirect'),
            ),
            'counter_icons' => array(
                'label' => esc_html__('Counter Icon', 'docdirect'),
                'type' => 'new-icon',
                'value' => '',
                'desc' => esc_html__('Add counter icons', 'docdirect'),
            ),
            'counter_start' => array(
                'label' => esc_html__('Start Number', 'docdirect'),
                'type' => 'text',
                'value' => '',
                'desc' => esc_html__('Add counter start', 'docdirect'),
            ),
            'counter_end' => array(
                'label' => esc_html__('End Number', 'docdirect'),
                'type' => 'text',
                'value' => '',
                'desc' => esc_html__('Add counter end', 'docdirect'),
            ),
            'counter_interval' => array(
                'type' => 'slider',
                'value' => 0,
                'properties' => array(
                    'min' => 0,
                    'max' => 50,
                    'sep' => 1,
                ),
                'attr' => array(),
                'label' => esc_html__('Interval', 'docdirect'),
                'desc' => esc_html__('add interval', 'docdirect'),
            ),
            'counter_speed' => array(
                'type' => 'slider',
                'value' => 0,
                'properties' => array(
                    'min' => 1000,
                    'max' => 10000,
                    'sep' => 1,
                ),
                'attr' => array(),
                'label' => esc_html__('Speed', 'docdirect'),
                'desc' => esc_html__('add speed', 'docdirect'),
            ),
        ),
    ),
);
