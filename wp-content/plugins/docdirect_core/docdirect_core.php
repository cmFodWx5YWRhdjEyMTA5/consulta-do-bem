<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://themographics.com
 * @since             1.0
 * @package           DocDirect
 *
 * @wordpress-plugin
 * Plugin Name:       DocDirect Core
 * Plugin URI:        http://themographics.com
 * Description:       This plugin is used for creating custom post types and other functionality for DocDirect Theme
 * Version:           4.8
 * Author:            Themographics
 * Author URI:        http://themeforest.net/user/themographics
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       docdirect_core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
if( !function_exists( 'activate_docdirect_core' ) ) {
	function activate_docdirect_core() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
		DocDirect_Activator::activate();
	}
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
if( !function_exists( 'deactivate_docdirect_core' ) ) {
	function deactivate_docdirect_core() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
		DocDirect_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_docdirect_core' );
register_deactivation_hook( __FILE__, 'deactivate_docdirect_core' );

/**
 * MailChimp Configuration Files
 */
require plugin_dir_path( __FILE__ ) . '/libraries/mailchimp/class-mailchimp.php';
/**
 * Mailchimp OAth Authentication
 */
require plugin_dir_path( __FILE__ ) . '/libraries/mailchimp/class-mailchimp-oath.php';

/**
 * Plugin configuration file,
 * It include getter & setter for global settings
 */
require plugin_dir_path( __FILE__ ) . 'config.php';

/**
 * settings page
 * It include getter & setter for global settings
 */
require plugin_dir_path( __FILE__ ) . 'core/settings/settings.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-docdirect-core.php';

//Include Files
require_once( 'core/class-core.php' );
require_once( 'core/class-functions.php' );
require_once( 'import-users/class-readcsv.php' );
require_once( 'libraries/recaptchalib/recaptchalib.php');

include docdirect_template_exsits( 'hooks/hooks' );
include docdirect_template_exsits( 'import-users/class-import-user' );
include docdirect_template_exsits( 'shortcodes/class-registration' );


/**
 * Get template from plugin or theme.
 *
 * @param string $file  Template file name.
 * @param array  $param Params to add to template.
 *
 * @return string
 */
function docdirect_template_exsits( $file, $param = array() ) {
	extract( $param );
	if ( is_dir( get_stylesheet_directory() . '/docdirect_core/' ) ) {
		if ( file_exists( get_stylesheet_directory() . '/docdirect_core/' . $file . '.php' ) ) {
			$template_load = get_stylesheet_directory() . '/docdirect_core/' . $file . '.php';
		} else {
			$template_load = DocDirectGlobalSettings::get_plugin_path() . '/' . $file . '.php';
		}
	} else {
		$template_load = DocDirectGlobalSettings::get_plugin_path() . '/' . $file . '.php';
	}
	return $template_load;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
if( !function_exists( 'run_DocDirect_Core' ) ) {
	function run_DocDirect_Core() {
		$plugin = new DocDirect_Core();
	}
	run_DocDirect_Core();
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'init', 'tg_load_textdomain' );
function tg_load_textdomain() {
  load_plugin_textdomain( 'docdirect_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
