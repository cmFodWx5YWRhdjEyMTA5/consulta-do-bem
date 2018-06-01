<?php
/**
 * @Init Reviews Post Type
 * @return {post}
 */
if( ! class_exists('TG_Reviews') ) {
	
	class TG_Reviews {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_review'));
			add_action( 'add_meta_boxes', array(&$this, 'tg_reviews_add_meta_box'), 10,1);
			add_filter('manage_docdirectreviews_posts_columns', array(&$this, 'reviews_columns_add'));
			add_action('manage_docdirectreviews_posts_custom_column', array(&$this, 'reviews_columns'),10, 2);				
		}
		
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_review(){
			$this->prepare_post_type();
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$labels = array(
				'name' 				 => esc_html__( 'Reviews', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Reviews', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Review', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Review', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Review', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Review', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Review', 'docdirect_core' ),
				'view'               => esc_html__( 'View Review', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Review', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Review', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Review found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Review found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Review', 'docdirect_core' ),
			);
			$args = array(
				'labels'			  => $labels,
				'description'         => esc_html__( '', 'docdirect_core' ),
				'public'              => true,
				'supports'            => array( 'title','editor'),
				'show_ui'             => true,
				'capability_type'     => 'post',
				'show_in_nav_menus' => false, 
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'show_in_menu' => 'edit.php?post_type=directory_type',
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'menu_position' 	  => 10,
				'rewrite'			  => array('slug' => 'reviews', 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => 'false',
			); 
			register_post_type( 'docdirectreviews' , $args );
			  
		}
		
		/**
		 * @Init Meta Boxes
		 * @return {post}
		 */
		public function tg_reviews_add_meta_box($post_type){
			if ($post_type == 'docdirectreviews') {
				add_meta_box(
					'tg_reviews_info',
					esc_html__( 'REVIEW INFO', 'docdirect_core' ),
					array(&$this,'tg_reviews_meta_box_reviewinfo'),
					'docdirectreviews',
					'side',
					'high'
				);
			}
		}
		
		/**
		 * @Init Review info
		 * @return {post}
		 */
		public function tg_reviews_meta_box_reviewinfo(){
			global $post;
			
			if (function_exists('fw_get_db_settings_option')) {
				$user_from   = fw_get_db_post_option($post->ID, 'user_from', true);
				$user_to   = fw_get_db_post_option($post->ID, 'user_to', true);
				$rating 		  = fw_get_db_post_option($post->ID, 'user_rating', true);
				$user_from 	   = get_user_by( 'id', intval( $user_from ) );
				$user_to 		 = get_user_by( 'id', intval( $user_to ) );
			} else{
				$user_from = '';
				$user_to = '';
				$rating = 0;
			}
			
			?>
			<ul class="review-info">
				<li>
					<span class="push-left"><strong><?php esc_html_e('Review Subject','docdirect_core');?></strong></span>
					<span class="push-right"><?php echo get_the_title( $post->ID );?></span>
				</li>
				<li>
					<span class="push-left"><strong><?php esc_html_e('Rating','docdirect_core');?></strong></span>
					<span class="push-right"><?php echo intval( $rating );?>/5</span>
				</li>
				<?php if( isset( $user_from->data->display_name ) && !empty( $user_from->data->display_name ) ){?>
				<li>
					<span class="push-left"><strong><?php esc_html_e('Review By','docdirect_core');?></strong></span>
					<span class="push-right"><a href="<?php echo get_edit_user_link($user_from->data->ID);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $user_from->data->display_name );?></a></span>
				</li>
				<?php }?>
                <?php if( isset( $user_to->data->display_name ) && !empty( $user_to->data->display_name ) ){?>
				<li>
					<span class="push-left"><strong><?php esc_html_e('Review To','docdirect_core');?></strong></span>
					<span class="push-right"><a href="<?php echo get_edit_user_link($user_to->data->ID);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $user_to->data->display_name );?></a></span>
				</li>
				<?php }?>
			</ul>
			<?php
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function reviews_columns_add($columns) {
			$columns['user_from'] 		= esc_html__('User From','docdirect_core');
			$columns['user_to'] 		  = esc_html__('User To','docdirect_core');
			$columns['rating'] 		   = esc_html__('Rating','docdirect_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function reviews_columns($name) {
			global $post;
			
			$user_identity 	  = '';
			$rating   = '';
				
			if (function_exists('fw_get_db_settings_option')) {
				$user_from 	= fw_get_db_post_option($post->ID, 'user_from', true);
				$user_to 	  = fw_get_db_post_option($post->ID, 'user_to', true);
				$rating  	   = fw_get_db_post_option($post->ID, 'user_rating', true);
				$user_from 	= get_user_by( 'id', intval( $user_from ) );
				$user_to 	  = get_user_by( 'id', intval( $user_to ) );
			}
			
			switch ($name) {
				case 'user_from':
					if( isset( $user_from->data->display_name ) && !empty( $user_from->data->display_name ) ){
						echo esc_attr( $user_from->data->display_name );
					}
				break;
				case 'user_to':
					if( isset( $user_to->data->display_name ) && !empty( $user_to->data->display_name ) ){
						echo esc_attr( $user_to->data->display_name );
					}
				break;		
				case 'rating':
					printf( '%s',$rating );
				break;		
				
			}
		}
		
	}
	
  	new TG_Reviews();	
}