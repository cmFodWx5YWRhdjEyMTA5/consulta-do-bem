<?php
/**
 * @Init Claims Post Type
 * @return {post}
 */
if( ! class_exists('TG_Claims') ) {
	
	class TG_Claims {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_claim'));
			add_action( 'add_meta_boxes', array(&$this, 'tg_claims_add_meta_box'), 10,1);
			add_filter('manage_doc_claims_posts_columns', array(&$this, 'claims_columns_add'));
			add_action('manage_doc_claims_posts_custom_column', array(&$this, 'claims_columns'),10, 2);				
		}
		
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_claim(){
			$this->prepare_post_type();
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$labels = array(
				'name' 				 => esc_html__( 'Claim/Reports', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Claim/Reports', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Claim/Report', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Claim/Report', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Claim/Report', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Claim/Report', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Claim/Report', 'docdirect_core' ),
				'view'               => esc_html__( 'View Claim/Report', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Claim/Report', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Claim/Report', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Claim/Report found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Claim/Report found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Claim/Report', 'docdirect_core' ),
			);
			$args = array(
				'capabilities'       => array( 'create_posts' => false ), //Hide add New Button
				'labels'			  => $labels,
				'description'         => esc_html__( '', 'docdirect_core' ),
				'public'              => false,
				'supports'            => array( 'title' ),
				'show_ui'             => true,
				'capability_type'     => 'post',
				'show_in_nav_menus'   => false, 
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'show_in_menu' => 'edit.php?post_type=directory_type',
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'menu_position' 	  => 10,
				'rewrite'			  => array('slug' => 'reports', 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => false,
			); 
			register_post_type( 'doc_claims' , $args );
			  
		}
		
		/**
		 * @Init Meta Boxes
		 * @return {post}
		 */
		public function tg_claims_add_meta_box($post_type){
			if ( $post_type == 'doc_claims' ) {
				add_meta_box(
					'tg_claims_detail',
					esc_html__( 'Claim/Report Detail', 'docdirect_core' ),
					array(&$this,'tg_claims_meta_box_detail'),
					'doc_claims',
					'normal',
					'high'
				);
			}
		}
		
		/**
		 * @Init Invoice info
		 * @return {post}
		 */
		public function tg_claims_meta_box_detail(){
			global $post;
			
			$post = get_post($post->ID);
			if ( function_exists('fw_get_db_settings_option') 
				&& 
				!empty( $post->ID ) 
				&& 
				isset( $_GET['post'] ) 
				&& 
				!empty( $_GET['post'] ) 
			) {
				$subject = fw_get_db_post_option($post->ID, 'subject', true);
				$user_from = fw_get_db_post_option($post->ID, 'user_from', true);
				$user_to = fw_get_db_post_option($post->ID, 'user_to', true);
				
				$user_from  = !empty( $user_from ) ? get_user_by( 'id', intval($user_from) ) : '';
				$user_to 	= !empty( $user_to ) ? get_user_by( 'id', intval($user_to) ) : '';
				
			} else{
				$user_identity = esc_html__('NILL','docdirect_core');
				$package_id = esc_html__('NILL','docdirect_core');
			}
			
			?>
			<ul class="invoice-info">
				<?php if( isset( $user_from->data ) && !empty( $user_from->data ) ){?>
					<li>
						<strong><?php esc_html_e('Reported By','docdirect_core');?></strong>
						<span><a href="<?php echo get_edit_user_link($user_from->data->ID);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $user_from->data->display_name );?></a></span>
					</li>
				<?php }?>
                <?php if( isset( $user_to->data ) && !empty( $user_to->data ) ){?>
					<li>
						<strong><?php esc_html_e('Reported To','docdirect_core');?></strong>
						<span><a href="<?php echo get_edit_user_link($user_to->data->ID);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $user_to->data->display_name );?></a></span>
					</li>
				<?php }?>
                <li>
					<strong><?php esc_html_e('Report/Claim Detail','docdirect_core');?></strong>
					<span><?php echo esc_attr( $post->post_content );?></span>
				</li>
			</ul>
			<?php
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function claims_columns_add($columns) {
			unset($columns['date']);
			$columns['user_from'] 		= esc_html__('Reported By','docdirect_core');
			$columns['user_to'] 		  = esc_html__('Reported against','docdirect_core');
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function claims_columns($name) {
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
				
			}
		}
		
	}
	
  	new TG_Claims();	
}