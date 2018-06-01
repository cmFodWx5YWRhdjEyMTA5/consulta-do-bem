<?php
if (!defined('FW'))
    die('Forbidden');

$manifest = array();
$manifest['name'] = esc_html__('Articles', 'docdirect');
$manifest['uri'] = 'https://themeforest.net/user/themographics/portfolio';
$manifest['description'] = esc_html__('This extension will enable providers to create articles from their dashboard.', 'docdirect');
$manifest['version'] = '1.0';
$manifest['author'] = 'Themographics';
$manifest['display'] = true;
$manifest['standalone'] = true;
$manifest['author_uri'] = 'https://themeforest.net/user/themographics/portfolio';
$manifest['requirements'] = array(
    'wordpress' => array(
        'min_version' => '4.0',
    )
);

$manifest['thumbnail'] = fw_get_template_customizations_directory_uri().'/extensions/articles/static/img/thumbnails/articles.png';
