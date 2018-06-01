<?php
/**
* @ Themographics Framework Core
* @ return {}
* @ Version 1.0.0
*/

if( !class_exists( 'TG_FrameworkCore' ) ) {
	class TG_FrameworkCore{
		protected static $instance = null;
		public $theme_style_path;
		public $theme_directory_path;
		public $theme_css_path;
		public $theme_js_path;
		public $theme_images_path;
			
		public function __construct() {
			$this->theme_style_path		= get_template_directory_uri();
			$this->theme_directory_path	= get_template_directory_uri();
			$this->theme_css_path		= get_template_directory_uri().'/css/';
			$this->theme_js_path		= get_template_directory_uri().'/js/';
			$this->theme_images_path	= get_template_directory_uri().'/images/';
		}
		
		
		/**
		 * Returns the *Singleton* instance of this class.
		 * @return Singleton The *Singleton* instance.
		 */
        public static function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

	}
	new TG_FrameworkCore();
}
?>