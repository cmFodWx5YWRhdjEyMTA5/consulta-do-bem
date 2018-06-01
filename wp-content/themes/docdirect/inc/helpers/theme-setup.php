<?php
/**
 * Doctor Directory functions and definitions
 *
 * @package Doctor Directory
 */
if (!function_exists('docdirect_theme_setup')) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function docdirect_theme_setup() {
		global $pagenow;
		
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on DocDirect, use a find and replace
		 * to change 'docdirect' to the name of your theme in all the template files
		 */
		load_theme_textdomain('docdirect', get_template_directory() . '/languages/');
		
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');
	
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');
	
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'main-menu'  => esc_attr('Header Main Menu', 'docdirect'),
			'footer-menu'  => esc_attr('Footer Menu', 'docdirect'),
		));
	
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		));
	
		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
	
		add_theme_support('post-formats', array(
			''
		));
	
		//bbpress
		 add_theme_support( 'bbpress' );
		
		
		//Woocommerce
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
	
		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('docdirect_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));
	
		add_filter('edit_user_profile','docdirect_edit_user_profile_edit',10,1);
		add_filter('show_user_profile','docdirect_edit_user_profile_edit',10,1);
		add_action('edit_user_profile_update', 'docdirect_personal_options_save' );
		add_action('personal_options_update', 'docdirect_personal_options_save' );
		add_theme_support('custom-header', array(
								'default-color' 	=> '',
								'flex-width' 		=> true,
								'flex-height' 		=> true,
								'default-image' 	=> '',
							));
		
		if ( ! get_option('docdirect_theme_installation') ) {
			update_option('docdirect_theme_installation','installed');
			wp_redirect(admin_url('themes.php?page=install-required-plugins'));
		}
		
		if ( ! get_option('docdirect_packages_settings') ) {
			update_option('docdirect_packages_settings','default');
		}
		
		//hide admin bar for all other roles except admin
		if (!current_user_can('administrator') && !is_admin()) {
		  show_admin_bar(false);
		}
	}
endif; // docdirect_setup
add_action('after_setup_theme', 'docdirect_theme_setup');