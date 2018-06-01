<?php
/**
 * File Type: Directory Type
 */
if( ! class_exists('TG_DirectoryType') ) {
	
	class TG_DirectoryType {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_directory_type'));		
			
			add_action( 'insurance_add_form_fields', array(&$this, 'add_insurance_logo'), 10, 2 );	
			add_action( 'created_insurance', array(&$this, 'save_insurance_meta'), 10, 2);
			add_action( 'insurance_edit_form_fields', array(&$this, 'insurance_edit_meta'),10, 2);
			add_action( 'edited_insurance', array(&$this, 'update_insurance_meta'), 10, 2 );
			add_filter('manage_edit-insurance_columns', array(&$this, 'add_insurance_column') );
			add_filter('manage_insurance_custom_column', array(&$this,'add_insurance_column_content'), 10, 3 );	
		}
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_directory_type(){
			$this->prepare_post_type();
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$directory_slug	= docdirect_get_theme_settings('directory');
			$insurance_slug	= docdirect_get_theme_settings('insurance');
			$cities_slug	= docdirect_get_theme_settings('cities');
			$speciality_slug	= docdirect_get_theme_settings('speciality');
			$sub_category_slug	= docdirect_get_theme_settings('sub_category_slug');
			
			$directory_slug		=  !empty( $directory_slug ) ? $directory_slug : 'provider-category';
			$insurance_slug		=  !empty( $insurance_slug ) ? $insurance_slug : 'insurance';
			$cities_slug		=  !empty( $cities_slug ) ? $cities_slug : 'location';
			$speciality_slug	=  !empty( $speciality_slug ) ? $speciality_slug : 'speciality';
			$sub_category_slug	=  !empty( $sub_category_slug ) ? $sub_category_slug : 'sub_category';
			
			$labels = array(
				'name' 				 => esc_html__( 'Directory Types', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Directory Types', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Directory Type', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Directory Type', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Directory Type', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Directory Type', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Directory Type', 'docdirect_core' ),
				'view'               => esc_html__( 'View Directory Type', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Directory Type', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Directory Type', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Directory Type found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Directory Type found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Directory Type', 'docdirect_core' ),
			);
			$args = array(
				'labels'			  => $labels,
				'description'         => esc_html__( 'This is where you can add new Directory Type', 'docdirect_core' ),
				'public'              => true,
				'supports'            => array( 'title'),
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'menu_position' 	  => 10,
				'rewrite'			  => array('slug' => $directory_slug, 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => false,
			); 
			register_post_type( 'directory_type' , $args );
			
			//Locations
			$labels = array(
				'name'              => esc_html__( 'Location(Cities)', 'taxonomy general name', 'docdirect_core' ),
				'singular_name'     => esc_html__( 'Location(Cities)', 'taxonomy singular name' , 'docdirect_core'),
				'search_items'      => esc_html__( 'Search Location' , 'docdirect_core'),
				'all_items'         => esc_html__( 'All Location' , 'docdirect_core'),
				'parent_item'       => esc_html__( 'Parent Location' , 'docdirect_core'),
				'parent_item_colon' => esc_html__( 'Parent Location:' , 'docdirect_core'),
				'edit_item'         => esc_html__( 'Edit Location' , 'docdirect_core'),
				'update_item'       => esc_html__( 'Update Location' , 'docdirect_core'),
				'add_new_item'      => esc_html__( 'Add New Location' , 'docdirect_core'),
				'new_item_name'     => esc_html__( 'New Location Name', 'docdirect_core' ),
				'menu_name'         => esc_html__( 'Locations (Cities)', 'docdirect_core' ),
			);
		
			$args = array(
				'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $cities_slug ),
			);
			
			//Specialities
			$specialities_labels = array(
				'name'              => esc_html__( 'Specialities', 'taxonomy general name', 'docdirect_core' ),
				'singular_name'     => esc_html__( 'Specialities', 'taxonomy singular name' , 'docdirect_core'),
				'search_items'      => esc_html__( 'Search Speciality' , 'docdirect_core'),
				'all_items'         => esc_html__( 'All Speciality' , 'docdirect_core'),
				'parent_item'       => esc_html__( 'Parent Speciality' , 'docdirect_core'),
				'parent_item_colon' => esc_html__( 'Parent Speciality:' , 'docdirect_core'),
				'edit_item'         => esc_html__( 'Edit Speciality' , 'docdirect_core'),
				'update_item'       => esc_html__( 'Update Speciality' , 'docdirect_core'),
				'add_new_item'      => esc_html__( 'Add New Speciality' , 'docdirect_core'),
				'new_item_name'     => esc_html__( 'New Speciality Name', 'docdirect_core' ),
				'menu_name'         => esc_html__( 'Specialities', 'docdirect_core' ),
			);
		
			$specialities_args = array(
				'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
				'labels'            => $specialities_labels,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $speciality_slug ),
			);
			
			
			//insurance
			$insurance_labels = array(
				'name'              => esc_html__( 'Insurance', 'taxonomy general name', 'docdirect_core' ),
				'singular_name'     => esc_html__( 'Insurance', 'taxonomy singular name' , 'docdirect_core'),
				'search_items'      => esc_html__( 'Search Insurance' , 'docdirect_core'),
				'all_items'         => esc_html__( 'All Insurance' , 'docdirect_core'),
				'parent_item'       => esc_html__( 'Parent Insurance' , 'docdirect_core'),
				'parent_item_colon' => esc_html__( 'Parent Insurance:' , 'docdirect_core'),
				'edit_item'         => esc_html__( 'Edit Insurance' , 'docdirect_core'),
				'update_item'       => esc_html__( 'Update Insurance' , 'docdirect_core'),
				'add_new_item'      => esc_html__( 'Add New Insurance' , 'docdirect_core'),
				'new_item_name'     => esc_html__( 'New Insurance Name', 'docdirect_core' ),
				'menu_name'         => esc_html__( 'Insurance', 'docdirect_core' ),
			);
		
			$insurance_args = array(
				//'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
				'labels'            => $insurance_labels,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $insurance_slug ),
			);
			
			//sub category
			$subcat_labels = array(
				'name'              => esc_html__( 'Sub Category', 'taxonomy general name', 'docdirect_core' ),
				'singular_name'     => esc_html__( 'Sub Category', 'taxonomy singular name' , 'docdirect_core'),
				'search_items'      => esc_html__( 'Search Sub Category' , 'docdirect_core'),
				'all_items'         => esc_html__( 'All Sub Category' , 'docdirect_core'),
				'parent_item'       => esc_html__( 'Parent Sub Category' , 'docdirect_core'),
				'parent_item_colon' => esc_html__( 'Parent Sub Category:' , 'docdirect_core'),
				'edit_item'         => esc_html__( 'Edit Sub Category' , 'docdirect_core'),
				'update_item'       => esc_html__( 'Update Sub Category' , 'docdirect_core'),
				'add_new_item'      => esc_html__( 'Add New Sub Category' , 'docdirect_core'),
				'new_item_name'     => esc_html__( 'New Sub Category Name', 'docdirect_core' ),
				'menu_name'         => esc_html__( 'Sub Category', 'docdirect_core' ),
			);
		
			$subcate_args = array(
				'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
				'labels'            => $subcat_labels,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $sub_category_slug ),
			);
			
			if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){
				register_taxonomy( 'sub_category', array( 'directory_type' ), $subcate_args );
			}
			
			register_taxonomy( 'locations', array( 'directory_type' ), $args );
			register_taxonomy( 'specialities', array( 'directory_type' ), $specialities_args );
			register_taxonomy( 'insurance', array( 'directory_type' ), $insurance_args );
			  
		}
		
		/**
		 * @insurance logo
		 * @return {}
		 */
		public function add_insurance_logo($taxonomy){
			?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th> <?php esc_html_e('Insurance logo', 'docdirect_core'); ?></th>
                        <td>
                            <input type="hidden" name="insurance_logo" class="media-image" id="insurance_logo"  value="" />
                            <input type="button" id="upload-insurance" class="button button-secondary" value="<?php esc_html_e('Uplaod insurance logo','docdirect_core');?>" />
                        </td>
                    </tr>
                    <tr id="insurance-wrap" class="elm-display-none">
                        <td class="backgroud-image">
                            <a href="javascript:;" class="delete-insurance"><i class="fa fa-times"></i></a>
                            <img class="insurance-src-style" height="100px" src="" id="insurance-src" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
		}
		
		/**
		 * @insurance Save
		 * @return {}
		 */
		public function save_insurance_meta($term_id, $tt_id){
			
			if( !empty( $_POST['insurance_logo'] ) ){
				$insurance_logo = esc_url( $_POST['insurance_logo'] );
				add_term_meta( $term_id, 'insurance_logo', $insurance_logo, true );
			}
			ob_start();
			?>
            <script>
            	jQuery(document).ready(function(e) {
                    jQuery('#insurance-wrap').hide();
                });
            </script>
            <?php
			return ob_get_clean();
		}
		

		/**
		 * @insurance update
		 * @return {}
		 */
		public function update_insurance_meta($term_id, $tt_id){
			$insurance_logo = esc_url( $_POST['insurance_logo'] );
			update_term_meta( $term_id, 'insurance_logo', $insurance_logo );
		}		
		
		/**
		 * @insurance Edit
		 * @return {}
		 */
		public function insurance_edit_meta($term, $taxonomy){
			$insurance_logo = get_term_meta( $term->term_id, 'insurance_logo', true );
			
			$display_image = '';
			if ( empty( $insurance_logo )) {
				$display_image = 'elm-display-none';
			}
		
			?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th> <?php esc_html_e('Insurance logo', 'docdirect_core'); ?></th>
                        <td>
                            <input type="hidden" name="insurance_logo" class="media-image" id="insurance_logo"  value="<?php echo esc_url($insurance_logo); ?>" />
                            <input type="button" id="upload-insurance" class="button button-secondary" value="<?php esc_html_e('Uplaod insurance logo','docdirect_core');?>" />
                        </td>
                    </tr>
                    <tr id="insurance-wrap" class="<?php echo esc_attr($display_image); ?>">
                        <td class="backgroud-image">
                            <a href="javascript:;" class="delete-insurance"><i class="fa fa-times"></i></a>
                            <img class="insurance-src-style" height="100px" src="<?php echo esc_url($insurance_logo); ?>" id="insurance-src" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
		}
		
		/**
		 * @insurance Column
		 * @return {}
		 */
		public function add_insurance_column( $columns ){
			unset($columns['posts']);
			$columns['insurance_logo'] = esc_html__( 'Insurance logo', 'my_plugin' );
			return $columns;
		}
		
		/**
		 * @insurance Column show
		 * @return {}
		 */
		public function add_insurance_column_content( $content, $column_name, $term_id ){
			if( $column_name !== 'insurance_logo' ){
				return $content;
			}
		
			$term_id = absint( $term_id );
			$insurance_logo = get_term_meta( $term_id, 'insurance_logo', true );

			if( !empty( $insurance_logo ) ){
				$content .= '<img src="'.$insurance_logo.'" width="50" />';
			}
		
			return $content;
		}
		
		
	}
	
  	new TG_DirectoryType();	
}