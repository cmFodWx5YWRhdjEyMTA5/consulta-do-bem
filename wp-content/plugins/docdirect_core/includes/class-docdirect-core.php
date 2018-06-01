<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://themographics.com
 * @since      1.0
 *
 * @package    DocDirect
 * @subpackage DocDirect Core/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0
 * @package    DocDirect Core
 * @subpackage DocDirect Core/includes
 * @author     Themographics <themographics@gmail.com>
 */

if( !class_exists( 'DocDirect_Core' ) ) {
    class DocDirect_Core {
		private $PluginVersion = '1.0';
		public function __construct(){
			
			$this->PluginVersion	 = DocDirectGlobalSettings::PluginVersion;
			
			//Post Types
			$dir	= DocDirectGlobalSettings::get_plugin_path();
			$scan_PostTypes = glob("$dir/core/post-types/*");
			foreach ($scan_PostTypes as $filename) {
				$file = basename($filename);         // $file is set to "index.php"
				$file = basename($file, ".php"); // $file is set to "index"
				include docdirect_template_exsits( 'core/post-types/'.$file );
			}
			

			//Enqueue Scripts
			add_action('admin_enqueue_scripts', array(&$this, 'tg_enqueue_scripts'));
			add_action('wp_enqueue_scripts', array(&$this, 'tg_enqueue_fronetend_scripts'));
			
			
			//Menus
			add_action( 'admin_menu', array($this, 'tg_edit_admin_menus') );
			add_action( 'admin_menu', array($this, 'tg_rename_admin_menus') );
			
			//Language Load
			//add_action( 'init', array( &$this, 'tg_load_textdomain') );

		}
		
		/**
		 * @PLugin Scripts
		 * @return {}
		 */
		public function tg_load_textdomain(){
			load_plugin_textdomain( 'docdirect_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
		}
		
		/**
		 * @Rename Menu
		 * @return {}
		 */
		public function tg_edit_admin_menus() {
			global $menu,$submenu;
			foreach( $menu as $key => $menu_item ) {
				if( $menu_item[2] == 'edit.php?post_type=directory_type' ){
					$menu[$key][0] = __('DocDirect','docdirect_core');
				}
			}
			
		}
		
		/**
		 * @Sub menu ordering
		 * @return {}
		 */
		public function tg_rename_admin_menus() {
			global $menu,$submenu;
			unset($submenu['edit.php?post_type=docdirectorders'][10]);
		}
		
		/**
		 * @Plugin Scripts
		 * @return {}
		 */
		public function tg_enqueue_scripts(){
			wp_enqueue_media();
        	wp_enqueue_script('jquery', 'media-upload', 'jquery-ui-slide');
			wp_enqueue_script('docdirect_core_script', DocDirectGlobalSettings::get_plugin_url().'/js/functions.js', '', $this->PluginVersion, true);
			wp_enqueue_style('system-styles', DocDirectGlobalSettings::get_plugin_url().'core/assets/css/system-admin.css', array(), $this->PluginVersion, 'all');
			if(is_admin()){
 				wp_enqueue_script('docdirect_functions_script', DocDirectGlobalSettings::get_plugin_url().'/core/assets/js/admin_functions.js', '', $this->PluginVersion, true);
			}
		}
		
		/**
		 * @Plugin Scripts
		 * @return {}
		 */
		public function tg_enqueue_fronetend_scripts(){
			wp_enqueue_script('docdirect_core_script',DocDirectGlobalSettings::get_plugin_url().'/js/functions.js', '', $this->PluginVersion, true);
		}
		
		/**
		 * @Plugin gmap Scripts
		 * @return {}
		 */
		public static function tg_enqueue_gmap3(){
			wp_enqueue_script( 'jquery-goolge-places', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', '', $this->PluginVersion, true);
			wp_enqueue_script('gmap3',DocDirectGlobalSettings::get_plugin_url().'/js/gmap3.min.js', '', $this->PluginVersion, true);
		}
	}
}
