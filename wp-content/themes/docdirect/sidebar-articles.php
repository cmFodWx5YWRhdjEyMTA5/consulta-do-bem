<?php
/**
 *
 * Theme sidebar
 * @desc      Articles Sidebar
 * @package   Service Providers
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $current_user, $wp_query;
if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {
	do_action( 'render_sp_display_articles' );
}


