<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'general_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('General Settings', 'docdirect'),
        'options' => array(
            'custom_classes' => array(
                'label' => esc_html__('Custom Classes', 'docdirect'),
                'desc' => esc_html__('Add Custom Classes', 'docdirect'),
                'type' => 'text',
            ),
        )
    ),
    'margin_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Margin', 'docdirect'),
        'options' => array(
            'margin_top' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Margin Top', 'docdirect'),
                'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
            'margin_bottom' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Margin Bottom', 'docdirect'),
                'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
            'margin_left' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Margin Left', 'docdirect'),
                'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
            'margin_right' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Margin Right', 'docdirect'),
                'desc' => esc_html__('add margin, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
        ),
    ),
    'padding_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Padding', 'docdirect'),
        'options' => array(
            'padding_top' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Padding Top', 'docdirect'),
                'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
            'padding_bottom' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Padding Bottom', 'docdirect'),
                'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
            'padding_left' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Padding Left', 'docdirect'),
                'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
            'padding_right' => array(
                'type' => 'text',
                'value' => '',
                'label' => esc_html__('Padding Right', 'docdirect'),
                'desc' => esc_html__('add padding, Leave it empty to hide, Note: add only integer value as : 10', 'docdirect'),
            ),
        )
    ),
    'responsive_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Responsive Settings', 'docdirect'),
        'options' => array(
            'responsive_switch' => array(
                'label' => esc_html__('Responsive Settings', 'docdirect'),
                'desc' => esc_html__('Show/hide Small Screen Settings', 'docdirect'),
                'type' => 'switch',
                'value' => 'off',
                'left-choice' => array(
                    'value' => 'on',
                    'label' => esc_html__('ON', 'docdirect'),
                ),
                'right-choice' => array(
                    'value' => 'off',
                    'label' => esc_html__('OFF', 'docdirect'),
                ),
            ),
            'responsive_classes' => array(
                'type' => 'select',
                'value' => 'no-repeat',
                'attr' => array(),
                'label' => esc_html__('Small Screen Class', 'docdirect'),
                'desc' => esc_html__('Choose Your Small Screen Class', 'docdirect'),
                'help' => esc_html__('', 'docdirect'),
                'choices' => array(
                    'col-sm-1' => esc_html__('col-sm-1', 'docdirect'),
                    'col-sm-2' => esc_html__('col-sm-2', 'docdirect'),
                    'col-sm-3' => esc_html__('col-sm-3', 'docdirect'),
                    'col-sm-4' => esc_html__('col-sm-4', 'docdirect'),
                    'col-sm-5' => esc_html__('col-sm-5', 'docdirect'),
                    'col-sm-6' => esc_html__('col-sm-6', 'docdirect'),
                    'col-sm-7' => esc_html__('col-sm-7', 'docdirect'),
                    'col-sm-8' => esc_html__('col-sm-8', 'docdirect'),
                    'col-sm-9' => esc_html__('col-sm-9', 'docdirect'),
                    'col-sm-10' => esc_html__('col-sm-10', 'docdirect'),
                    'col-sm-11' => esc_html__('col-sm-11', 'docdirect'),
                    'col-sm-12' => esc_html__('col-sm-12', 'docdirect'),
                ),
            ),
            'extra_small_switch' => array(
                'label' => esc_html__('Extrasmall Settings', 'docdirect'),
                'desc' => esc_html__('Show/hide Small Screen Settings', 'docdirect'),
                'type' => 'switch',
                'value' => 'off',
                'left-choice' => array(
                    'value' => 'on',
                    'label' => esc_html__('ON', 'docdirect'),
                ),
                'right-choice' => array(
                    'value' => 'off',
                    'label' => esc_html__('OFF', 'docdirect'),
                ),
            ),
            'extra_small' => array(
                'type' => 'select',
                'value' => 'no-repeat',
                'attr' => array(),
                'label' => esc_html__('Small Screen Class', 'docdirect'),
                'desc' => esc_html__('Choose Your Small Screen Class', 'docdirect'),
                'help' => esc_html__('', 'docdirect'),
                'choices' => array(
                    'col-xs-1' => esc_html__('col-xs-1', 'docdirect'),
                    'col-sm-2' => esc_html__('col-xs-2', 'docdirect'),
                    'col-xs-3' => esc_html__('col-xs-3', 'docdirect'),
                    'col-xs-4' => esc_html__('col-xs-4', 'docdirect'),
                    'col-xs-5' => esc_html__('col-xs-5', 'docdirect'),
                    'col-xs-6' => esc_html__('col-xs-6', 'docdirect'),
                    'col-xs-7' => esc_html__('col-xs-7', 'docdirect'),
                    'col-xs-8' => esc_html__('col-xs-8', 'docdirect'),
                    'col-xs-9' => esc_html__('col-xs-9', 'docdirect'),
                    'col-xs-10' => esc_html__('col-xs-10', 'docdirect'),
                    'col-xs-11' => esc_html__('col-xs-11', 'docdirect'),
                    'col-xs-12' => esc_html__('col-xs-12', 'docdirect'),
                ),
            ),
        )
    ),
);
