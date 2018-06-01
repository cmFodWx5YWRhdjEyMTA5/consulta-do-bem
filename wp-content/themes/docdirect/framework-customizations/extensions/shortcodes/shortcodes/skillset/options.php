<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'heading' => array(
        'type' => 'text',
        'label' => esc_html__('Heading', 'docdirect')
    ),
    'skillset' => array(
        'type' => 'addable-popup',
        'label' => esc_html__('Skill Set', 'docdirect'),
        'popup-title' => esc_html__('Add Your Skill Set', 'docdirect'),
        'desc' => esc_html__('Add Your Skill Sets', 'docdirect'),
        'template' => '{{=skill_name}}',
        'popup-options' => array(
            'skill_highlight' => array(
                'type'  => 'checkbox',
                'value' => true, // checked/unchecked
                'attr'  => array(),
                'label' => esc_html__('Highlight', 'docdirect'),
                'desc'  => esc_html__('Highlight this skillset by check this box.', 'docdirect'),
                'text'  => esc_html__('Yes', 'docdirect'),
            ),
            'skill_name' => array(
                'type' => 'text',
                'label' => esc_html__('Skill Name', 'docdirect')
            ),
           'percentage' => array(
				'label' => esc_html__('Percentage', 'docdirect'),
				'type' => 'slider',
				'value' =>'86',
				'properties' => array(
					
					'min' => 0,
					'max' => 100,
					'step' => 1, // Set slider step. Always > 0. Could be fractional.
					
				),
			)
        ),
    )
);
