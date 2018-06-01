<?php if (!defined('FW')) die('Forbidden');
/**
 * @var string $uri Demo directory url
 */

$manifest = array();
$manifest['title'] = esc_html__('DocDirect New Version ', 'docdirect');
$manifest['screenshot'] = get_template_directory_uri(). '/demo-content/images/demo-new.jpg';
$manifest['preview_link'] = 'http://themographics.com/wordpress/directory/';