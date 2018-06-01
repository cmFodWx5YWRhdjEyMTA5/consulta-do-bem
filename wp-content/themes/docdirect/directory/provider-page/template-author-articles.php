<?php
/**
 *
 * Author Articles Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $current_user;
$author_profile = $wp_query->get_queried_object();
$directory_type	= $author_profile->directory_type;

if(function_exists('fw_get_db_settings_option')) {
	$article_switch    = fw_get_db_post_option($directory_type, 'articles', true);
}
 
if( isset( $article_switch )  && $article_switch === 'enable' ){
	if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {
		do_action('render_sp_display_articles');
	}
}