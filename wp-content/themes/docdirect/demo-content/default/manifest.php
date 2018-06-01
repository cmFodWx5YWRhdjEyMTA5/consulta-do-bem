<?php if (!defined('FW')) die('Forbidden');
/**
 * @var string $uri Demo directory url
 */

$manifest = array();
$manifest['title'] = esc_html__('DocDirect Default', 'docdirect');
$manifest['screenshot'] = get_template_directory_uri(). '/demo-content/images/default.jpg';
$manifest['preview_link'] = 'http://docdirect.themographics.com/';