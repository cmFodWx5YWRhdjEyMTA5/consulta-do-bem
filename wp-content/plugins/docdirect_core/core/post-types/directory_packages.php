<?php
/**
 * File Type: Package
 */
if( ! class_exists('TG_DirectoryPackages') ) {
	
	class TG_DirectoryPackages {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_directory_packages'));
			add_filter('manage_directory_packages_posts_columns', array(&$this, 'directory_packages_columns_add'));
			add_action('manage_directory_packages_posts_custom_column', array(&$this, 'directory_packages_columns'),10, 2);						
		}
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_directory_packages(){
			if( apply_filters('docdirect_get_theme_settings', 'payments') === 'custom' ){
				$this->prepare_post_type();
			}
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$labels = array(
				'name' 				 => esc_html__( 'Packages', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Packages', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Package', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Package', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Package', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Package', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Package', 'docdirect_core' ),
				'view'               => esc_html__( 'View Package', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Package', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Package', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Package found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Package found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Package', 'docdirect_core' ),
			);
			$args = array(
				'labels'			  => $labels,
				'description'         => esc_html__( 'This is where you can add new Package', 'docdirect_core' ),
				'public'              => false,
				'supports'            => array( 'title'),
				'show_ui'             => true,
				'capability_type'     => 'post',
				'show_in_nav_menus'   => false, 
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'show_in_menu' => 'edit.php?post_type=directory_type',
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'menu_position' 	  => 10,
				'rewrite'			  => array('slug' => 'packages', 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => false,
			); 
			register_post_type( 'directory_packages' , $args );
			  
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function directory_packages_columns_add($columns) {
			unset($columns['date']);
			
			$columns['price'] 		= esc_html__('Price','docdirect_core');
			$columns['duration'] 		= esc_html__('Duration','docdirect_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function directory_packages_columns($name) {
			global $post;
			
			$price 		= '';
			$duration   = '';
				
			if (function_exists('fw_get_db_settings_option')) {
				$price 		= fw_get_db_post_option($post->ID, 'price', true);
				$duration   = fw_get_db_post_option($post->ID, 'duration', true);
			}
			
			switch ($name) {
				case 'price':
					printf( '%s',$price );
				break;		
				case 'duration':
					printf( '%s',$duration );
				break;		
				
			}
		}
		
	}
	
  	new TG_DirectoryPackages();	
}