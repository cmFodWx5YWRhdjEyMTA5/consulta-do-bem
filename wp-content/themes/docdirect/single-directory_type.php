<?php
/**
 *
 * The template used for displaying default provider search result
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $wp_query;
get_header();
	get_template_part( '/directory/user_search' );
get_footer();
