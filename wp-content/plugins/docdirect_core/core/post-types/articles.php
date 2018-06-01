<?php
/**
 * @Init articles Post Type
 * @return {post}
 */
if( ! class_exists('DocDirect_Articles') ) {
	
	class DocDirect_Articles {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_article'));	
		}
		
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_article(){
			$this->prepare_post_type();
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$articles_slug	= docdirect_get_theme_settings('articles');
			$articles_slug		=  !empty( $articles_slug ) ? $articles_slug : 'article';
			
			register_post_type('sp_articles', array(
				'labels' => array(
					'name' => esc_html__('Articles', 'docdiret_core'),
					'all_items' => esc_html__('Articles', 'docdiret_core'),
					'singular_name' => esc_html__('Article', 'docdiret_core'),
					'add_new' => esc_html__('Add Article', 'docdiret_core'),
					'add_new_item' => esc_html__('Add New Article', 'docdiret_core'),
					'edit' => esc_html__('Edit', 'docdiret_core'),
					'edit_item' => esc_html__('Edit Article', 'docdiret_core'),
					'new_item' => esc_html__('New Article', 'docdiret_core'),
					'view' => esc_html__('View Article', 'docdiret_core'),
					'view_item' => esc_html__('View Article', 'docdiret_core'),
					'search_items' => esc_html__('Search Article', 'docdiret_core'),
					'not_found' => esc_html__('No Article found', 'docdiret_core'),
					'not_found_in_trash' => esc_html__('No Article found in trash', 'docdiret_core'),
					'parent' => esc_html__('Parent Article', 'docdiret_core'),
				),
				'description' => esc_html__('This is where you can add new Articles.', 'docdiret_core'),
				'public' => true,
				'supports' => array('title', 'editor', 'thumbnail'),
				'show_ui' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'hierarchical' => true,
				'menu_position' => 10,
				'rewrite' => array('slug' => $articles_slug, 'with_front' => true),
				'query_var' => true,
				'has_archive' => 'false'
			));
			register_taxonomy('article_tags', 'sp_articles', array(
				'hierarchical' => false,
				'labels' => array(
					'name' => esc_html__('Tags', 'docdiret_core'),
					'singular_name' => esc_html__('Tag', 'docdiret_core'),
					'search_items' => esc_html__('Search Tags', 'docdiret_core'),
					'popular_items' => esc_html__('Popular Tags', 'docdiret_core'),
					'all_items' => esc_html__('All Tags', 'docdiret_core'),
					'parent_item' => null,
					'parent_item_colon' => null,
					'edit_item' => esc_html__('Edit Tag', 'docdiret_core'),
					'update_item' => esc_html__('Update Tag', 'docdiret_core'),
					'add_new_item' => esc_html__('Add New Tag', 'docdiret_core'),
					'new_item_name' => esc_html__('New Tag Name', 'docdiret_core'),
					'separate_items_with_commas' => esc_html__('Separate tags with commas', 'docdiret_core'),
					'add_or_remove_items' => esc_html__('Add or remove tags', 'docdiret_core'),
					'choose_from_most_used' => esc_html__('Choose from the most used tags', 'docdiret_core'),
					'menu_name' => esc_html__('Tags', 'docdiret_core'),
				),
				'show_ui' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array('slug' => 'article_tags'),
			));
			
			register_taxonomy('article_categories', 'sp_articles', array(
				'labels' => array(
					'name' => esc_html__('Categories', 'docdiret_core'),
					'singular_name' => esc_html__('Category', 'docdiret_core'),
					'search_items' => esc_html__('Search Categories', 'docdiret_core'),
					'popular_items' => esc_html__('Popular Categories', 'docdiret_core'),
					'all_items' => esc_html__('All Categories', 'docdiret_core'),
					'parent_item' => null,
					'parent_item_colon' => null,
					'edit_item' => esc_html__('Edit Category', 'docdiret_core'),
					'update_item' => esc_html__('Update Category', 'docdiret_core'),
					'add_new_item' => esc_html__('Add New Category', 'docdiret_core'),
					'new_item_name' => esc_html__('New Category Name', 'docdiret_core'),
					'separate_items_with_commas' => esc_html__('Separate category with commas', 'docdiret_core'),
					'add_or_remove_items' => esc_html__('Add or remove categories', 'docdiret_core'),
					'choose_from_most_used' => esc_html__('Choose from the most used categories', 'docdiret_core'),
					'menu_name' => esc_html__('Category', 'docdiret_core'),
				),
				'update_count_callback' => '_update_post_term_count',
				'hierarchical' => true,
                'show_ui' => true,
                'query_var' => true,
				'rewrite' => array('slug' => 'article_category'),
			));
			  
		}	
	}	
}