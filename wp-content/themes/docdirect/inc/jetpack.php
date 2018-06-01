<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package Doctor Directory
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
if (!function_exists('docdirect_jetpack_setup')) {
	function docdirect_jetpack_setup() {
		add_theme_support( 'infinite-scroll', array(
			'container' => 'main',
			'render'    => 'docdirect_infinite_scroll_render',
			'footer'    => 'page',
		) );
	} // end function docdirect_jetpack_setup
	add_action( 'after_setup_theme', 'docdirect_jetpack_setup' );
}

if (!function_exists('docdirect_infinite_scroll_render')) {
	function docdirect_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', get_post_format() );
		}
	} // end function docdirect_infinite_scroll_render
}