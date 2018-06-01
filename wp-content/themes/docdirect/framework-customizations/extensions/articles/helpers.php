<?php

if (!defined('FW')) {
    die('Forbidden');
}

/**
 * Return the article listing view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_articles_listing')) {
	function fw_ext_get_render_articles_listing() {
		return fw()->extensions->get('articles')->render_article_listing();
	}
}

/**
 * Return the articles add view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_articles_add')) {
	function fw_ext_get_render_articles_add() {
		return fw()->extensions->get('articles')->render_add_articles();
	}
}

/**
 * Return the articles edit view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_articles_edit')) {
	function fw_ext_get_render_articles_edit() {
		return fw()->extensions->get('articles')->render_edit_articles();
	}
}


/**
 * Return the articles dashboard display view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_articles_dashboard_view')) {
	function fw_ext_get_render_articles_dashboard_view() {
		return fw()->extensions->get('articles')->render_display_dashboard_articles();
	}
}