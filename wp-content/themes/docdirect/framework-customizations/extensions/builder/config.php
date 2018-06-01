<?php

if (!defined('FW'))
    die('Forbidden');

$cfg = array();

/**
 * Default item widths for all builders
 *
 * It is better to use fw_ext_builder_get_item_width() function to retrieve the item widths
 * because it has a filter and users will be able to customize the widths for a specific builder
 *
 * @see fw_ext_builder_get_item_width()
 */
$cfg['default_item_widths'] = array(
    '1_6' => array(
        'title' => '1/6',
        'backend_class' => 'fw-col-sm-2',
        'frontend_class' => 'col-xs-12 col-md-1',
    ),
    '1_5' => array(
        'title' => '1/5',
        'backend_class' => 'fw-col-sm-15',
        'frontend_class' => 'col-xs-12 col-md-2',
    ),
    '1_4' => array(
        'title' => '1/4',
        'backend_class' => 'fw-col-sm-3',
        'frontend_class' => 'col-xs-12 col-md-3',
    ),
    '1_3' => array(
        'title' => '1/3',
        'backend_class' => 'fw-col-sm-4',
        'frontend_class' => 'col-xs-12  col-md-4',
    ),
    '2_5' => array(
        'title' => '2/5',
        'backend_class' => 'fw-col-sm-5',
        'frontend_class' => 'col-xs-12 col-md-5',
    ),
	'1_2' => array(
        'title' => '1/2',
        'backend_class' => 'fw-col-sm-6',
        'frontend_class' => 'col-xs-12 col-md-6',
    ),
    '3_5' => array(
        'title' => '3/5',
        'backend_class' => 'fw-col-sm-7',
        'frontend_class' => 'col-xs-12 col-md-7',
    ),
	'2_3' => array(
        'title' => '2/3',
        'backend_class' => 'fw-col-sm-8',
        'frontend_class' => 'col-xs-12 col-md-8',
    ),
    '3_4' => array(
        'title' => '3/4',
        'backend_class' => 'fw-col-sm-9',
        'frontend_class' => 'col-xs-12 col-md-9',
    ),
	'5_6' => array(
        'title' => '5/6',
        'backend_class' => 'fw-col-sm-10',
        'frontend_class' => 'col-xs-12 col-md-10',
    ),
    '1_1' => array(
        'title' => '1/1',
        'backend_class' => 'fw-col-sm-12',
        'frontend_class' => 'col-xs-12 col-md-12',
    ),
);
