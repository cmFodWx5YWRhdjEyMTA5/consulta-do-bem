<?php

if ( ! function_exists( 'docdirect_get_profile_image_url' ) ) {
	/**
	 * Get thumbnail url based on attachment data
	 *
	 * @param $attach_data
	 * @return string
	 */
	function docdirect_get_profile_image_url( $attach_data,$image_size='thumbnail' ) {
		$upload_dir = wp_upload_dir();
		$image_path_data = explode( '/', $attach_data[ 'file' ] );
		$image_path_array = array_slice( $image_path_data, 0, count( $image_path_data ) - 1 );
		$image_path = implode( '/', $image_path_array );
		$thumbnail_name = null;
		
		if ( isset( $attach_data[ 'sizes' ][ $image_size ] ) ) {
			$thumbnail_name = $attach_data[ 'sizes' ][ $image_size ][ 'file' ];
		} else {
			if( isset( $attach_data[ 'sizes' ][ 'thumbnail' ][ 'file' ] ) ) {
				$thumbnail_name = $attach_data[ 'sizes' ][ 'thumbnail' ][ 'file' ];//if size exist
			} else{
				$thumbnail_name = $image_path_data[2];//
			}
		}
		return $upload_dir[ 'baseurl' ] . '/' . $image_path . '/' . $thumbnail_name;
	}
}

/**
 * @Check user existance
 * @return 
 */
if (!function_exists('docdirect_do_check_user_existance')) {
	function docdirect_do_check_user_existance($user){
		global $current_user, $wp_roles,$userdata,$post,$wpdb;
		$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));

		if( $count == 1 ){
			$profile_status = get_user_meta($user , 'profile_status' , true);
			if( $profile_status == 'active'){
				return true; 
			} else if( $current_user->ID == $user ){
				return true; 
			} else{
				return false; 
			}
		}else{ 
			return false; 
		}
	}
	add_filter( 'docdirect_do_check_user_existance', 'docdirect_do_check_user_existance', 10, 3 );
}

/**
 * Profile hits
 *
 * @param numeric value
 * @return string
 */
if ( ! function_exists( 'docdirect_update_profile_hits' ) ) {
	function docdirect_update_profile_hits($user_identity='') {
		global $current_user, $wp_roles,$userdata,$post;
		if( apply_filters( 'docdirect_do_check_user_existance', $user_identity ) ){
			if(isset($user_identity) && $user_identity <> ''){
				$profile_hits = get_user_meta($user_identity , 'profile_hits' , true);
				
				if( empty( $profile_hits ) ){
					$profile_hits	= array();
				}
				
				$year	 = date('y');
				$month	= date('m');
				$profile_hits_year	= array();
				
				$months_array	= docdirect_get_month_array(); //Get Month  Array
				
				if( isset( $profile_hits[$year] ) ){
					$profile_hits = get_user_meta($user_identity , 'profile_hits' , true);
					if ( isset($_COOKIE["profile_hits_" . $user_identity]) ) { 
						//Cookie already set, nothing to do
					} else{
						setcookie("profile_hits_" . $user_identity , 'profile_hits' , time() + 3600);
						$profile_hits = get_user_meta($user_identity , 'profile_hits' , true);
						if( isset( $profile_hits[$year][$month] ) ){
							$profile_hits[$year][$month]++;
						} else{
							$profile_hits[$year][$month] = 1;
						}
						update_user_meta( $user_identity, 'profile_hits', $profile_hits );
						update_user_meta( $user_identity, 'profile_hits_count', $profile_hits );
					}
				} else{
					foreach( $months_array as $key => $value ){
						$profile_hits_year[$year][$key]	= 0;
					}
					
					if( isset( $profile_hits ) && !empty( $profile_hits ) ) {
						$profile_hits	= $profile_hits + $profile_hits_year;
					} else{
						$profile_hits	= $profile_hits_year;
					}
					
					if ( isset($_COOKIE["profile_hits_" . $user_identity]) ) {
						//Cookie already set
					} else{
						setcookie("profile_hits_" . $user_identity , 'profile_hits' , time() + 3600);
						$profile_hits = get_user_meta($user_identity , 'profile_hits' , true);

						if( isset( $profile_hits[$year][$month] ) ) {
							$profile_hits[$year][$month]++;
						}else{
							$profile_hits[$year][$month] = 1;
						}
					}
	
					update_user_meta( $user_identity, 'profile_hits', $profile_hits );
				}
			}
		}
	}
	add_action('docdirect_update_profile_hits','docdirect_update_profile_hits',5,2);
}


/**
 * Update User Password
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_add_new_order' ) ) {
	function docdirect_add_new_order($order_meta=array()) {
		global $current_user, $wp_roles,$userdata,$post;
		extract($order_meta);
		
		$user_identity	= $current_user->ID;
		$json	=  array();
		
		
		$directory_prefix = fw_get_db_settings_option('directory_prefix');
		$directory_prefix	= isset( $directory_prefix ) && !empty( $directory_prefix ) ? $directory_prefix :'DD-';
		
		$order_no	= $directory_prefix.docdirect_unique_increment(10);
		$payment_date = date_i18n(date('Y-m-d H:i:s'));
		$order_post = array(
			'post_title' => $order_no,
			'post_status' => 'publish',
			'post_author' => $current_user->ID,
			'post_type' => 'docdirectorders',
			'post_date' => current_time('Y-m-d h')
		);
		
		$post_id = wp_insert_post($order_post);
		
		$order_meta = array(
			'transaction_id' 	=> docdirect_unique_increment(10),
			'order_status' 	  => 'pending',
			'payment_method' 	=> $gateway,
			'package' 		   => $packs,
			'price' 			 => $price,
			'payment_date' 	  => $payment_date,
			'expiry_date' 	   => $featured_date,
			'payment_user' 	  => $user_identity,
			'mc_currency' 	   => $mc_currency,
		);
		
		$new_values = $order_meta;
		if ( isset( $post_id ) && !empty( $post_id ) ) {
			fw_set_db_post_option($post_id, null, $new_values);
		}
		
		if( isset( $payment_type ) && $payment_type === 'gateway' ){
			return $post_id;
		} else{
			return $order_no;
		}
		
	}
}

/**
 * Check if user is active user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_is_user_active' ) ) {
	function docdirect_is_user_active($user_id='') {
		
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		
		if( isset( $user_id ) && !empty( $user_id ) ) {
			$profile_status = get_user_meta($user_id , 'profile_status' , true);
			if( $user_identity == $user_id && $profile_status == 'de-active' ){
				add_action( 'wp_footer', 'docdirect_user_profile_status_message' );
			}
		}
	}
	add_action( 'docdirect_is_user_active', 'docdirect_is_user_active' );
}

/**
 * Check if user is verified user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_is_user_verified' ) ) {
	function docdirect_is_user_verified($user_id='') {
		global $current_user, $wp_roles,$userdata,$post;
		$user_identity	= $current_user->ID;
		if( isset( $user_id ) && !empty( $user_id ) ) {
			$verify_user = get_user_meta($user_id , 'verify_user' , true);
			$user_type   = get_user_meta($user_id , 'user_type' , true);
			$confirmation_key = get_user_meta($user_id , 'confirmation_key' , true);

			if( $user_identity == $user_id
				&&
				$user_type === 'professional'  
				&& 
				( $verify_user == 'off' || empty( $verify_user ) )
				
			){
				add_action( 'wp_footer', 'docdirect_is_user_verified_message' );
			}
		}
	}
	add_action( 'docdirect_is_user_verified', 'docdirect_is_user_verified' );
}




/**
 * Check if user is active user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_get_term_options' ) ) {
	function docdirect_get_term_options($current='',$taxonomyName='locations') {
		//This gets top layer terms only.  This is done by setting parent to 0.  
		$parent_terms = get_terms( $taxonomyName, array( 'parent' => 0, 'orderby' => 'slug', 'hide_empty' => false ) ); 
		$options	= '';
		if( isset( $parent_terms ) && !empty( $parent_terms ) ) {
			foreach ( $parent_terms as $pterm ) {
				//Get the Child terms
				
				$terms = get_terms( $taxonomyName, array( 'parent' => $pterm->term_id, 'orderby' => 'slug', 'hide_empty' => false ) );
				if( isset( $terms ) && !empty( $terms ) ) {
					$options	.= '<optgroup  label="'.$pterm->name.'">';
					foreach ( $terms as $term ) {
						$selected	= '';
						
						if( !empty( $current ) 
							&& is_array($current)
							&& in_array($term->slug,$current)
						){
							$selected	= 'selected';
						} else if( !empty( $current ) 
							&& !is_array($current)
							&& $term->slug == $current
						){
							$selected	= 'selected';
						}
						
						$options	.= '<option '.$selected.' value="'.$term->slug.'">'.$term->name.'</option>';
					}
					$options	.= '</optgroup>';
					
				} else{
					$selected	= '';
					
					if( !empty( $current ) 
						&& is_array($current)
						&& in_array($pterm->slug,$current)
					){
						$selected	= 'selected';
					} else if( !empty( $current ) 
						&& !is_array($current)
						&& $pterm->slug == $current
					){
						
						$selected	= 'selected';
					}
					
					$options	.= '<option '.$selected.' value="'.$pterm->slug.'">'.$pterm->name.'</option>';
				}
			}
		}
		
		echo force_balance_tags( $options );
	}
}


/**
 * Check if user is active user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_get_linked_term_options' ) ) {
	function docdirect_get_linked_term_options($current='',$taxonomyName='locations',$db_directory_type) {
		//This gets top layer terms only.  This is done by setting parent to 0.
		if(empty( $db_directory_type )){return;}
		
		$parent_terms = wp_get_post_terms($db_directory_type, 'sub_category', array("fields" => "all"));
		$options	= '';
		if( isset( $parent_terms ) && !empty( $parent_terms ) ) {
			foreach ( $parent_terms as $pterm ) {
				//Get the Child terms
				
				$terms = get_terms( $taxonomyName, array( 'parent' => $pterm->term_id, 'orderby' => 'slug', 'hide_empty' => false ) );
				if( isset( $terms ) && !empty( $terms ) ) {
					$options	.= '<optgroup  label="'.$pterm->name.'">';
					foreach ( $terms as $term ) {
						$selected	= '';
						
						if( !empty( $current ) 
							&& is_array($current)
							&& in_array($term->slug,$current)
						){
							$selected	= 'selected';
						} else if( !empty( $current ) 
							&& !is_array($current)
							&& $term->slug == $current
						){
							$selected	= 'selected';
						}
						
						$options	.= '<option '.$selected.' value="'.$term->slug.'">'.$term->name.'</option>';
					}
					$options	.= '</optgroup>';
					
				} else{
					$selected	= '';
					
					if( !empty( $current ) 
						&& is_array($current)
						&& in_array($pterm->slug,$current)
					){
						$selected	= 'selected';
					} else if( !empty( $current ) 
						&& !is_array($current)
						&& $pterm->slug == $current
					){
						
						$selected	= 'selected';
					}
					
					$options	.= '<option '.$selected.' value="'.$pterm->slug.'">'.$pterm->name.'</option>';
				}
			}
		}
		
		echo force_balance_tags( $options );
	}
}

/**
 * Check if user is active user
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_format_size_units' ) ) {
	function docdirect_format_size_units($bytes,$returntype='print'){
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576){
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1)  {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}
		
		if( $returntype === 'print' ){
			echo esc_attr( $bytes );
		} else{
			return $bytes;
		}
	}
}

/**
 * Get All user those are active.
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_prepare_user_list' ) ) {
	function docdirect_prepare_user_list(){
		$args = array(
			'orderby'      => 'nicename',
			'order'        => 'DESC',
		);
	
		$site_user = get_users($args);

		$user_list	= array();
		foreach ($site_user as $user) {
			$user_list[$user->data->ID]	= $user->data->display_name;
		}
		
		return $user_list;
	}
}


/**
 * @Check user role
 * @return 
 */
if (!function_exists('docdirect_do_check_user_type')) {
	function docdirect_do_check_user_type($user_identity){
		if( isset( $user_identity ) && !empty( $user_identity ) ) {
			$data	= get_userdata( $user_identity );
			if( isset( $data->roles[0] ) && !empty( $data->roles[0] ) && ( $data->roles[0] === 'professional' || $data->roles[0] === 'administrator' ) ){
				return true;
			} else {
				return false;
			}
		}
		
		return false;
	}
	add_filter( 'docdirect_do_check_user_type', 'docdirect_do_check_user_type', 10, 3 );
}

/** 
 * @Check if booking is enabled
 * @return 
 */
if (!function_exists('docdirect_do_check_teams')) {
	function docdirect_do_check_teams($user_identity){
		if( isset( $user_identity ) && !empty( $user_identity ) ) {
			
			$data				= get_userdata( $user_identity );
			$directory_type		= $data->directory_type;
			$booking_switch     = '';
			
			if(function_exists('fw_get_db_settings_option')) {
				$teams_switch    = fw_get_db_post_option($directory_type, 'teams', true);
			}

			if( $teams_switch === 'enable' ){
				return true;
			} else{
				return false;  
			}
		}
		
		return false;
	}
	add_filter( 'docdirect_do_check_teams', 'docdirect_do_check_teams', 10, 3 );
}

/**
 * @Remove Meta boxes
 * @return 
 */
if ( ! function_exists( 'tg_unwanted_remove_meta_box' ) ) {
	function tg_unwanted_remove_meta_box($post_type) {
		remove_meta_box('tagsdiv-insurance', 'directory_type', 'normal');
		remove_meta_box('locationsdiv', 'directory_type', 'normal');
		remove_meta_box('specialitiesdiv', 'directory_type', 'normal');
		remove_meta_box( 'submitdiv', 'docdirectinvoices','side');
		remove_meta_box( 'submitdiv', 'docappointments','side');
		remove_meta_box( 'slugdiv', 'docdirectinvoices','normal');
		remove_meta_box( 'mymetabox_revslider_0', 'docdirectinvoices', 'normal' );
	}
	add_action( 'admin_init', 'tg_unwanted_remove_meta_box', 10,1);
}

/**
 * @get all specialities
 * @return 
 */
if (!function_exists('docdirect_prepare_specialities')) {
	function docdirect_prepare_specialities(){
		global $post;
		$args = array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'specialities',
			'pad_counts'               => false 
		); 
		
		$specialities = get_categories($args); 
		//$specialities_attached	= docdirect_get_attached_specialities();
		$specialities_attached	= array();

		$speciality_array	= array();
		foreach ( $specialities as $speciality ) {
			if ( is_array($specialities_attached) && !in_array( $speciality->term_id,$specialities_attached) ) {
				$speciality_array[$speciality->term_id]	= $speciality->name;
			}
		}
		
		return $speciality_array;
	}
}

/**
 * @check attached all specialities
 * @return 
 */
if ( ! function_exists( 'docdirect_get_attached_specialities' ) ) {
	function docdirect_get_attached_specialities(){
		global $post;
		$post_id	= !empty( $post->ID ) ?  $post->ID : '';
		$specialities_array	= array();
		$args = array(
			'posts_per_page'	=> "-1",
			'post_type'         => 'directory_type',
			'suppress_filters'  => false,
			'post__not_in'	  => array($post_id),
			'post_status'       => array('publish','pending','draft'),
		);
		
        $cust_query = get_posts($args);

        if (isset($cust_query) && is_array($cust_query) && !empty($cust_query)) {
            foreach ($cust_query as $key => $val) {
				$attached = get_post_meta($val->ID, 'attached_specialities', false);
				if( isset( $attached  ) && !empty( $attached  ) ) {
					$specialities_array = array_merge($specialities_array,$attached[0]);
				}
            }
        }

	   return array_unique($specialities_array);
	   
	}
}

/**
 * @Search Directory
 * @return 
 */
if ( ! function_exists( 'docdirect_get_map_directory' ) ) {
	function docdirect_get_map_directory(){
		global $post;
		$json	= array();

	    $directories	= array();
		$directory_type = !empty( $_POST['directory_type'] ) ? esc_attr( $_POST['directory_type'] ) : '';
		$dir_subcat 	= !empty( $_POST['dir_subcat'] ) ? esc_attr( $_POST['dir_subcat'] ) : '';
		$zip	 		= !empty( $_POST['zip'] ) ? esc_attr( $_POST['zip'] ) : '';
		$by_name	 	= !empty( $_POST['by_name'] ) ? esc_attr( $_POST['by_name'] ) : '';
		$Latitude   	= !empty( $_POST['lat'] ) ? $_POST['lat'] : '';
		$Longitude  	= !empty( $_POST['long'] ) ? $_POST['long'] : '';
		
		$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
		$query_args	= array(
							'role'  => 'professional',
							'order' => 'DESC',
						 );
		
		$meta_query_args = array();

		if( !empty( $by_name ) ){
			$s = sanitize_text_field( $by_name );
			//$query_args['search'] = $s;
			$search_args	= array(
									'search'         => '*'.esc_attr( $s ).'*',
									'search_columns' => array(
										'ID',
										'display_name',
										'user_login',
										'user_nicename',
										'user_email',
										'user_url',
										
									)
								);
			//$query_args	= array_merge( $query_args, $search_args );
			$meta_by_name = array('relation' => 'OR',);
			$meta_by_name[] = array(
							'key' 	   => 'first_name',
							'value' 	 => $s,
							'compare'   => 'LIKE',
						);
	
			$meta_by_name[] = array(
									'key' 	   => 'last_name',
									'value' 	 => $s,
									'compare'   => 'LIKE',
								);

			$meta_by_name[] = array(
									'key' 	    => 'nickname',
									'value' 	=> $s,
									'compare'   => 'LIKE',
								);

			$meta_by_name[] = array(
							'key' 	   => 'username',
							'value' 	 => $s,
							'compare'   => 'LIKE',
						);

			$meta_by_name[] = array(
							'key' 	   => 'full_name',
							'value' 	 => $s,
							'compare'   => 'LIKE',
						);

			$meta_by_name[] = array(
							'key' 	    => 'description',
							'value' 	=> $s,
							'compare'   => 'LIKE',
						);

			$meta_by_name[] = array(
							'key' 	     => 'professional_statements',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);
			
			if( !empty( $meta_by_name ) ) {
				$meta_query_args[]	= array_merge( $meta_by_name,$meta_query_args );
			}
		}

		//Directory Type Search
		if( isset( $directory_type ) && !empty( $directory_type ) ){
			$meta_query_args[] = array(
									'key' 	   => 'directory_type',
									'value' 	 => $directory_type,
									'compare'   => '=',
								);
		}
		
		//Zip Search
		if( isset( $zip ) && !empty( $zip ) ){
			$meta_query_args[] = array(
									'key'     => 'zip',
									'value'   => $zip,
									'compare' => '='
								);
		}
		
		//Speciality Search
		if( isset( $dir_subcat ) && !empty( $dir_subcat ) && $dir_subcat !== 'all' ){
			$meta_query_args[] = array(
									'key'     => $dir_subcat,
									'value'   => $dir_subcat,
									'compare' => '='
								);
		}
		
		//Verify user
		$meta_query_args[] = array(
						'key'     => 'verify_user',
						'value'   => 'on',
						'compare' => '='
					);
					
		//Merge Query
		if( !empty( $meta_query_args ) ) {
			$query_relation = array('relation' => 'AND',);
			$meta_query_args	= array_merge( $query_relation,$meta_query_args );
			$query_args['meta_query'] = $meta_query_args;
		}
		
		//Radius Search
		if( (isset($_POST['geo_location']) && !empty($_POST['geo_location'])) ){
			$prepAddr   = '';
			$minLat	 = '';
			$maxLat	 = '';
			$minLong	= '';
			$maxLong	= '';
			
			//
			if( isset( $_POST['geo_distance'] ) && !empty( $_POST['geo_distance'] ) ){
				$radius = esc_attr( $_POST['geo_distance'] );
			} else{
				$radius = 300;
			}
			
			//Distance in miles or kilometers
			if (function_exists('fw_get_db_settings_option')) {
				$dir_distance_type = fw_get_db_settings_option('dir_distance_type');
			} else{
				$dir_distance_type = 'mi';
			}
			
			if( $dir_distance_type === 'km' ) {
				$radius = $radius * 0.621371;
			}
									
			if( !empty( $Latitude ) && !empty( $Longitude ) ){
				$Latitude	 = $Latitude;
				$Longitude   = $Longitude;

			} else{
				$address	 = !empty($_GET['geo_location']) ? $_GET['geo_location'] : '';
				$prepAddr	= str_replace(' ','+',$address);

				$args = array(
					'timeout'     => 15,
					'headers' => array('Accept-Encoding' => ''),
					'sslverify' => false
				);

				$url	    = 'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
				$response   = wp_remote_get( $url, $args );
				$geocode	= wp_remote_retrieve_body($response);

				$output	  = json_decode($geocode);

				if( isset( $output->results ) && !empty( $output->results ) ) {
					$Latitude	= $output->results[0]->geometry->location->lat;
					$Longitude   = $output->results[0]->geometry->location->lng;
				}
			}
			
			if( !empty( $Latitude ) && !empty( $Longitude ) ){
		
				$zcdRadius = new RadiusCheck($Latitude,$Longitude,$radius);
				$minLat  = $zcdRadius->MinLatitude();
				$maxLat  = $zcdRadius->MaxLatitude();
				$minLong = $zcdRadius->MinLongitude();
				$maxLong = $zcdRadius->MaxLongitude();
				
				$meta_query_args = array(
					'relation' => 'AND',
					 array(
						'relation' => 'AND',
						array(
							'key' 		=> 'latitude',
							'value'  	=> array($minLat, $maxLat),
							'compare' 	=> 'BETWEEN',
							'type' 	=> 'DECIMAL(20,10)',
						),
						array(
							'key' 		=> 'longitude',
							'value'   	=> array($minLong, $maxLong),
							'compare' 	=> 'BETWEEN',
							'type' 		=> 'DECIMAL(20,10)',
						)
					),
				);
				
				if( isset( $query_args['meta_query'] ) && !empty( $query_args['meta_query'] ) ) {
					$meta_query	= array_merge($meta_query_args,$query_args['meta_query']);
				} else{
					$meta_query	= $meta_query_args;
				}
		
				$query_args['meta_query']	= $meta_query;
			}
		}

			$user_query = new WP_User_Query($query_args);
			$directories	=  array();
		
			if ( ! empty( $user_query->results ) ) {
				$directories['status']	= 'found';
				$directories['lat']  = $Latitude !== '' ? floatval ( $Latitude ) : '';
				$directories['long'] = $Longitude  !== '' ? floatval ( $Longitude ) : '';
				$count	= 0;
				
				foreach ( $user_query->results as $user ) {
					$count++;
					$latitude	= get_user_meta( $user->ID, 'latitude', true);
					$longitude	= get_user_meta( $user->ID, 'longitude', true);
					
					$directory_type	= get_user_meta( $user->ID, 'directory_type', true);
					$dir_map_marker    = fw_get_db_post_option($directory_type, 'dir_map_marker', true);
					$featured_date	 = get_user_meta($user->ID, 'user_featured', true);
					$current_date = date('Y-m-d H:i:s');
					
					if( $count === 1 ){
						$directories['lat']  = $Latitude !== '' ? floatval ( $Latitude ) : $latitude;
						$directories['long'] = $Longitude  !== '' ? floatval ( $Longitude ) : $longitude;
					}
					
					$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>270,'height'=>270), $user->ID),
							 array('width'=>270,'height'=>270) //size width,height
						);
					
					$privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settings
					
					$directories_array['latitude']	 = $latitude;
					$directories_array['longitude']	 = $longitude;
					$directories_array['title']		 = $user->display_name;
					$directories_array['name']	 	 = $user->first_name.' '.$user->last_name;
					$directories_array['email']	 	 = $user->user_email;
					$directories_array['phone_number'] = $user->phone_number;
					$directories_array['address']	  = $user->user_address;
					$directories_array['group']		= $slug;
					$featured_string   = $featured_date;
					$current_string	= strtotime( $current_date );
					$review_data	= docdirect_get_everage_rating ( $user->ID );

					if( isset( $dir_map_marker['url'] ) && !empty( $dir_map_marker['url'] ) ){
						$directories_array['icon']	 = $dir_map_marker['url'];
					} else{
						if( !empty( $dir_map_marker_default['url'] ) ){
							$directories_array['icon']	 = $dir_map_marker_default['url'];
						} else{
							$directories_array['icon']	 	   = get_template_directory_uri().'/images/map-marker.png';
						}
					}

					$infoBox	 = '<div class="tg-map-marker">';
					$infoBox	.= '<figure class="tg-docimg"><a class="userlink" href="'.get_author_posts_url($user->ID).'"><img src="'.esc_url( $avatar ).'" alt="'.esc_attr( $directories_array['title'] ).'"></a>';
					$infoBox	.= docdirect_get_wishlist_button($user->ID,false);

					if( isset( $featured_string ) && $featured_string > $current_string ){
						$infoBox	.= docdirect_get_featured_tag(false); 
					}
					$infoBox	.= docdirect_get_verified_tag(false,$user->ID);
					$infoBox	.= docdirect_get_rating_stars($review_data,'return');
					$infoBox	.= '</figure>';
					$infoBox	.= '<div class="tg-mapmarker-content">';
					$infoBox	.= '<div class="tg-heading-border tg-small">';
					$infoBox	.= '<h3><a class="userlink" href="'.get_author_posts_url($user->ID).'">'.$directories_array['name'].'</a></h3>';
					$infoBox	.= '</div>';
					$infoBox	.= '<ul class="tg-info">';
					if( !empty( $directories_array['email'] ) 
						&&
						  !empty( $privacy['email'] )
						&& 
						  $privacy['email'] == 'on'
					) {
						$infoBox	.= '<li> <i class="fa fa-envelope"></i> <em><a href="mailto:'.$directories_array['email'].'?Subject=hello"  target="_top">'.$directories_array['email'].'</a></em> </li>';
					}

					if( !empty( $directories_array['phone_number'] ) 
						&&
						  !empty( $privacy['phone'] )
						&& 
						  $privacy['phone'] == 'on'
					) {
						$infoBox	.= '<li> <i class="fa fa-phone"></i> <em><a href="javascript:;">'.$directories_array['phone_number'].'</a></em> </li>';
					}

					if( !empty( $directories_array['address'] ) ) {
						$infoBox	.= '<li> <i class="fa fa-map-marker"></i> <address>'.$directories_array['address'].'</address> </li>';
					}
					$infoBox	.= '</ul>';
					$infoBox	.= '</div>';
					$infoBox	.= '</div>';

					$directories_array['html']['content']	= $infoBox;
					$directories['users_list'][]	= $directories_array;
				}
			} else{
				$directories['status']	= 'empty';
			}
		
		echo json_encode($directories);
		die; 
	}
	
	add_action('wp_ajax_docdirect_get_map_directory', 'docdirect_get_map_directory');
	add_action('wp_ajax_nopriv_docdirect_get_map_directory', 'docdirect_get_map_directory');
}



/**
 * @Authenticate user
 * @return 
 */
if (!function_exists('docdirect_is_user_logged_in')) {
	function docdirect_is_user_logged_in($check_user=''){
		global $current_user, $wp_roles,$userdata,$post,$wpdb;		
		if( is_user_logged_in() ){
			return true;
		} else{
			return false;
		}
	}
	add_filter( 'docdirect_is_user_logged_in', 'docdirect_is_user_logged_in' );
}

/**
 * @Authenticate user
 * @return 
 */
if (!function_exists('docdirect_get_everage_rating')) {
	function docdirect_get_everage_rating( $user_id='' ){
		
		$meta_query_args = array('relation' => 'AND',);
		$meta_query_args[] = array(
								'key' 	   => 'user_to',
								'value' 	 => $user_id,
								'compare'   => '=',
								'type'	  => 'NUMERIC'
							);
								
		$args 		= array('posts_per_page'   => -1, 
							'post_type'		 => 'docdirectreviews',
							'post_status'	   => 'publish',
							'orderby' 		   => 'meta_value_num',
							'meta_key' 	 => 'user_rating',
							'order' 		=> 'ASC',
						);
		
		$args['meta_query'] = $meta_query_args;
				
		$average_rating	= 0;
		$average_count	 = 0;
		$query 		= new WP_Query($args);
		
		$rate_1	= array('rating' => 0, 'total'=>0);
		$rate_2	= array('rating' => 0, 'total'=>0);
		$rate_3	= array('rating' => 0, 'total'=>0);
		$rate_4	= array('rating' => 0, 'total'=>0);
		$rate_5	= array('rating' => 0, 'total'=>0);

		while($query->have_posts()) : $query->the_post();
			global $post;
			$user_rating = fw_get_db_post_option($post->ID, 'user_rating', true);
			$user_from = fw_get_db_post_option($post->ID, 'user_from', true);
			$user_name = fw_get_db_post_option($post->ID, 'user_name', true);
			$review_date = fw_get_db_post_option($post->ID, 'review_date', true);
			$user_data 	  = get_user_by( 'id', intval( $user_from ) );
			
			if( $user_rating == 1 ){
				$rate_1['rating']   = $rate_1['rating']+$user_rating;   
				$rate_1['total']	= $rate_1['total']+ 1;   
			} else if( $user_rating == 2 ){
				$rate_2['rating']   = $rate_2['rating']+$user_rating;   
				$rate_2['total']	= $rate_2['total']+ 1;   
			} else if( $user_rating == 3 ){
				$rate_3['rating']   = $rate_3['rating']+$user_rating;   
				$rate_3['total']	= $rate_3['total']+ 1;   
			} else if( $user_rating == 4 ){
				$rate_4['rating']   = $rate_4['rating']+$user_rating;   
				$rate_4['total']	= $rate_4['total']+ 1;   
			} else if( $user_rating == 5 ){
				$rate_5['rating']   = $rate_5['rating']+$user_rating;   
				$rate_5['total']	= $rate_5['total'] + 1;   
			}

			$average_rating	= $average_rating + $user_rating;
			$average_count++;
		
		endwhile; wp_reset_postdata();

		$data['reviews']	= 0;
		$data['percentage']	= 0;
		if( isset( $average_rating ) && $average_rating > 0 ){
			$data['average_rating']	= $average_rating/$average_count;
			$data['reviews']	= $average_count;
			$data['percentage'] = ( $average_rating/ $average_count)*20;
			$data['by_ratings']	= array($rate_1,$rate_2,$rate_3,$rate_4,$rate_5);
		}
		
		return $data;
	}
	
}

/**
 * @Authenticate user
 * @return 
 */
if (!function_exists('docdirect_count_reviews')) {
	function docdirect_count_reviews( $user_id ='' ){
		$user_reviews = array(
			'posts_per_page'	=> "-1",
			'post_type'		 => 'docdirectreviews',
			'post_status'	   => 'publish',
			'meta_key'		  => 'user_to',
			'meta_value'		=> $user_id,
			'meta_compare'	  => "=",
			'orderby'		   => 'meta_value',
			'order'			 => 'ASC',
		);
		
		$reviews_query = new WP_Query($user_reviews);
		$reviews_count = $reviews_query->post_count;
		return intval( $reviews_count );
	}
	add_filter( 'docdirect_count_reviews', 'docdirect_count_reviews' );
}

/*
 * @Fetch Data
 * @return 
 */
if (!function_exists('docdirect_map_controls')) {
	function docdirect_map_controls() {
		if (function_exists('fw_get_db_settings_option')) {
			$dir_map_scroll 	   = fw_get_db_settings_option('dir_map_scroll');
		} else{
			$dir_map_scroll = '';
		}

		$lock_icon	= 'fa fa-unlock';
		if( isset( $dir_map_scroll ) && $dir_map_scroll === 'false' ){
			$lock_icon	= 'fa fa-lock';
		}
		?>
        <div class="map-controls">
            <span id="doc-mapplus"><i class="fa fa-plus"></i></span>
            <span id="doc-mapminus"><i class="fa fa-minus"></i></span>
            <span id="doc-lock"><i class="<?php echo esc_attr( $lock_icon );?>"></i></span>
        </div>
        <?php
	}
	add_action( 'docdirect_map_controls', 'docdirect_map_controls');
}


/**
 * @add to wishlist button
 * @return 
 */
if (!function_exists('docdirect_get_wishlist_button')) {
	function docdirect_get_wishlist_button($post_id='',$echo=false,$view='v1'){
		global $current_user;
		if( isset( $post_id ) && $post_id != $current_user->ID ){
			$wishlist	= array();
			$wishlist    = get_user_meta($current_user->ID,'wishlist', true);
			$wishlist    = !empty($wishlist) && is_array( $wishlist ) ? $wishlist : array();
			
			if( isset( $view ) && $view === 'v2' ) {
				if( !empty( $post_id )&&  in_array( $post_id , $wishlist ) ){
					$wishlist_button	= '<a data-view_type="v2" class="doc-favoriteicon" href="javascript:;"><i class="fa fa-heart"></i></a>';
				} else{
					$wishlist_button	= '<a data-view_type="v2" class="doc-favoriteicon doc-notfavorite add-to-fav" data-wl_id="'.$post_id.'" href="javascript:;"><i class="fa fa-heart"></i></a>';
					
				}
			} else{
				if( !empty( $post_id )&&  in_array( $post_id , $wishlist ) ){
					$wishlist_button	= '<a data-view_type="v1" class="tg-dislike" href="javascript:;"><i class="fa fa-heart"></i></a>';
				} else{
					$wishlist_button	= '<a data-view_type="v1" class="tg-like add-to-fav" data-wl_id="'.$post_id.'" href="javascript:;"><i class="fa fa-heart"></i></a>';
				}
			}
			
			if( $echo == true ){
				echo force_balance_tags( $wishlist_button );
			} else{
				return force_balance_tags( $wishlist_button );
			}
		}
		
		return false;
	}
}





/**
 * @Privacy Settings
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_privacy_settings' ) ) {
	function docdirect_get_privacy_settings($user_identity) {
		global $current_user, $wp_roles,$userdata,$post;
		$docdirect_privacy	=  array();
		if(isset($user_identity) && $user_identity <> ''){
			$docdirect_privacy = get_user_meta($user_identity , 'privacy' , true);
		}
		return $docdirect_privacy;
	}
}

/**
 * @Get user rating stars
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_rating_stars' ) ) {
	function docdirect_get_rating_stars($review_data='',$return_type='echo',$show_rating='show') {
		global $current_user;
		ob_start();
		?>
        <div class="feature-rating user-star-rating">
            <span class="tg-stars star-rating">
                <span style="width:<?php echo esc_attr( $review_data['percentage'] );?>%"></span>
            </span>
            
            <?php 
				if( isset( $show_rating ) && $show_rating === 'show' ){
					if( !empty( $review_data['average_rating'] ) ){?>
					<em><?php echo number_format((float)$review_data['average_rating'], 1, '.', '');?><sub>/5</sub></em>
			<?php }}?>
        </div>
        <?php
		if( $return_type === 'return' ){
			return ob_get_clean();
		} else{
			echo ob_get_clean();
		}
	}
}

/**
 * @Get user rating stars v2
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_rating_stars_v2' ) ) {
	function docdirect_get_rating_stars_v2($review_data='',$return_type='echo',$show_rating='show') {
		global $current_user;
		ob_start();
		$percentage	= !empty( $review_data['percentage'] ) ?  $review_data['percentage'] : 0;
		?>
        	<li><span class="doc-stars"><span style="width:<?php echo esc_attr( $review_data['percentage'] );?>%"></span></span></li>
        <?php
		if( $return_type === 'return' ){
			return ob_get_clean();
		} else{
			echo ob_get_clean();
		}
	}
}

/**
 * @Set User Views
 * @return {}
 */
if ( ! function_exists( 'docdirect_set_user_views' ) ) {
	function docdirect_set_user_views($userID) {
		$count_key = 'doc_user_views_count';
		$count = get_user_meta($userID , $count_key , true);
		
		if ( isset($_COOKIE["user_views_" . $userID]) ) { 
			//Cookie already set, nothing to do
		} else{
			setcookie("user_views_" . $userID , 'user_views' , time() + 3600);

			if( $count=='' ){
				$count = 0;
				update_user_meta( $userID, $count_key, $count );
			}else{
				$count++;
				update_user_meta( $userID, $count_key, $count );
			}
		}
					
		
	}
	
	//To keep the count accurate, lets get rid of prefetching
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

/**
 * @Set User Views
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_user_views' ) ) {
	function docdirect_get_user_views($userID){
		$count_key = 'doc_user_views_count';
		$count = get_user_meta($userID , $count_key , true);
		if($count==''){
			$count = 0;
			update_user_meta( $userID, $count_key, $count );
			return $count;
		}
		return $count;
	}
}

/**
 * Check user type
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'docdirect_is_visitor' ) ) {
	function docdirect_is_visitor($user_id='') {
		global $current_user, $wp_roles,$userdata,$post;
		
		$user_identity	= $current_user->ID;	
		$user = get_userdata( $user_id );
    	
		if( !empty( $user->roles[0] ) && $user->roles[0] === 'visitor' ){
			return true;
		} else{
			return false;
		}
	}
	add_filter( 'docdirect_is_visitor', 'docdirect_is_visitor', 10, 3 );
}

/**
 * @Set User Views
 * @return {}
 */
if ( ! function_exists( 'docdirect_remove_parent_from_category' ) ) {
	add_action( 'admin_head-edit-tags.php', 'docdirect_remove_parent_from_category' );
	add_action( 'admin_head-term.php', 'docdirect_remove_parent_from_category' );
	function docdirect_remove_parent_from_category(){
		if ( 'locations' != $_GET['taxonomy']
			 && 'specialities' != $_GET['taxonomy']
			 && 'insurance' != $_GET['taxonomy']
			 && 'article_categories' != $_GET['taxonomy']
			 && 'doc_sports' != $_GET['taxonomy']
			 && 'sub_category' != $_GET['taxonomy']
		) {
			return;
		}
	
		$parent = 'parent()';
	
		if ( isset( $_GET['tag_ID'] ) && !empty( $_GET['tag_ID'] ) )
			$parent = 'parent().parent()';
	
		?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('label[for=parent]').<?php echo ( $parent ); ?>.remove();       
				});
			</script>
		<?php
	}
}
/**
 * @Set User Views
 * @return {}
 */
if ( ! function_exists( 'docdirect_date_24midnight' ) ) {
	function docdirect_date_24midnight($format,$ts){
	   if( date("Hi",$ts) == "0000") {
		  $replace = array(
			"H" => "24",
			"G" => "24",
			"i" => "00",
		  );
	
		  return date(
			str_replace(
			  array_keys($replace),
			  $replace, 
			  $format
			),
			$ts-60 // take a full minute off, not just 1 second
		  );
	   } else {
		  return date($format,$ts);
	   }
	}
}

/**
 * @parse URL
 * @return {}
 */
if ( ! function_exists( 'docdirect_parse_url' ) ) {
	function docdirect_parse_url($url){
		$input = trim($url, '/');
		
		// If scheme not included, prepend it
		if (!preg_match('#^http(s)?://#', $input)) {
			$input = 'http://' . $input;
		}
		
		$urlParts = parse_url($input);
		
		// remove www
		//$domain = preg_replace('/^www\./', '', $urlParts['host']);
		
		return !empty( $urlParts['host'] ) ? $urlParts['host'] : $url;
	}
}

/**
 * @Add http from URL
 * @return {}
 */
if ( ! function_exists( 'docdirect_add_http' ) ) {
	function docdirect_add_http($url) {
		$protolcol = is_ssl() ? "https" : "http";
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = $protolcol.':'. $url;
		}
		return $url;

	}
}

/**
 * @Remove http from URL
 * @return {}
 */
if ( ! function_exists( 'docdirect_remove_http' ) ) {
	function docdirect_remove_http($url){
		$str = preg_replace('#^https?://#', '', $url);
		return $str;
	}
}

/**
 * @get author slugs
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_users_base_slug' ) ) {
	function docdirect_get_users_base_slug(){
		$slug = 'user';
		if (function_exists('fw_get_db_settings_option')) {
			$slug = fw_get_db_settings_option('user_page_slug', $default_value = 'user');
		}
		
		$author_slug = $slug; // change slug name
		
		$author_levels	= array($author_slug);
		$args = array( 'posts_per_page' => '-1', 
					   'post_type' => 'directory_type', 
					   'post_status' => 'publish',
					   'suppress_filters' => false
				);
		
		$cust_query = get_posts($args);
		if( isset( $cust_query ) && !empty( $cust_query ) ) {
		  $author_levels	= array('');
		  $counter	= 0;
		  foreach ($cust_query as $key => $dir) {
			 $author_levels[]	= $dir->post_name;
		  }	
		}
		
		
		return $author_levels;
	}
}

/**
 * @prepare auhtor slugs
 * @return {}
 */
if ( ! function_exists( 'docdirect_prepare_users_base' ) ) {	
	add_action( 'init', 'docdirect_prepare_users_base' );
	function docdirect_prepare_users_base(){
		global $wp_rewrite;
		$author_levels = docdirect_get_users_base_slug();
		// Define the tag and use it in the rewrite rule
		add_rewrite_tag( '%author_level%', '(' . implode( '|', $author_levels ) . ')' );
		$wp_rewrite->author_base = '%author_level%';
		$wp_rewrite->flush_rules();
	}
}

/**
 * @refine author base if username and base matched eg : anything/anything
 * @return {}
 */
if ( ! function_exists( 'docdirect_author_rewrite_rules' ) ) {	
	add_filter( 'author_rewrite_rules', 'docdirect_author_rewrite_rules' );
	function docdirect_author_rewrite_rules( $author_rewrite_rules ){
		foreach ( $author_rewrite_rules as $pattern => $substitution ) {
			if ( FALSE === strpos( $substitution, 'author_name' ) ) {
				unset( $author_rewrite_rules[$pattern] );
			}
		}
		return $author_rewrite_rules;
	}
}

/**
 * @refine author base if username and base matched eg : anything/anything
 * @return {}
 */
if ( ! function_exists( 'docdirect_get_user_refined_link' ) ) {
	add_filter( 'author_link', 'docdirect_get_user_refined_link', 10, 3 );
	function docdirect_get_user_refined_link( $link, $author_id, $author_nicename ){
		$author_level = 'user';
		if ( 1 == $author_id ) {
			//return nothing
		} else {
			$db_directory_type	 = get_user_meta( $author_id, 'directory_type', true);
			if( !empty( $db_directory_type ) ){
				$postdata = get_post($db_directory_type); 
				$slug 	 = $postdata->post_name;
				$author_level = $slug;
			} else{
				$slug = 'user';
				if (function_exists('fw_get_db_settings_option')) {
					$slug = fw_get_db_settings_option('user_page_slug', $default_value = 'user');
				}
				$author_slug = $slug; // change slug name
				$author_level = $author_slug;
			}
		}
		
		$link = str_replace( '%author_level%', $author_level, $link );
		return $link;
	}
}

/**
 * @User manage columns 
 * @return 
 */
if ( !function_exists('docdirect_user_manage_user_columns') )  {
	add_filter('manage_users_columns', 'docdirect_user_manage_user_columns');
	
	function docdirect_user_manage_user_columns($column) {
		$column['type'] = esc_html__('User Type','docdirect');
		$column['status'] = esc_html__('Status','docdirect');
		return $column;
	}
}


/**
 * @verify users status
 * @return 
 */
if ( !function_exists('docdirect_change_status') )  {
	add_action('admin_action_docdirect_change_status', 'docdirect_change_status');
	function docdirect_change_status() {
		
		if (isset($_REQUEST['users']) && isset($_REQUEST['nonce'])) {
			$nonce = !empty( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
			$users = !empty( $_REQUEST['users'] ) ? $_REQUEST['users'] : '';
			
			if (wp_verify_nonce($nonce, 'docdirect_change_status_' . $users)) {
				$is_approved = get_user_meta($users, 'verify_user', true);
				if ( isset( $is_approved ) && $is_approved === 'on' ) {
					 $new_status = 'off';
					 $message_param = 'unapproved';
				} else {
					$new_status = 'on';
					$message_param = 'approved';
				}
				update_user_meta($users, 'verify_user', $new_status);
				$redirect = admin_url('users.php?updated=' . $message_param);
			} else {
				$redirect = admin_url('users.php?updated=docdirect_false');
			}
		} else {
			$redirect = admin_url('users.php?updated=docdirect_false');
		}
		wp_redirect($redirect);
	}
}

/**
 * @gete like and button button
 * @return 
 */
if (!function_exists('docdirect_get_likes_button')) {
	function docdirect_get_likes_button($user_id='',$echo=true){
		global $current_user;
		if( isset( $user_id ) && $user_id != $current_user->ID ){
			
			$likes	= array();
			$likes    = get_user_meta($current_user->ID,'user_likes', true);
			$likes    = !empty($likes) && is_array( $likes ) ? $likes : array();
			
			$count_key = 'doc_user_likes_count';
			$count     = get_user_meta($user_id , $count_key , true);
			
			$count	= !empty( $count ) ? $count : 0;
			
			if ( isset($_COOKIE["user_likes_" . $user_id]) ) { 
				$likes_button	= '<a href="javascript:;" class="user-liked"><i class="fa fa-thumbs-up"></i>'.$count.'</a>';
			} else{
				$likes_button	= '<a href="javascript:;" class="do-like-me" data-like_id="'.$user_id.'"><i class="fa fa-thumbs-up"></i>'.$count.'</a>';
			}
		

			if( $echo == true ){
				echo force_balance_tags( $likes_button );
			} else{
				return force_balance_tags( $likes_button );
			}
		}
		
		return false;
	}
}

/**
 * @Get likes
 * @return {}
 */
if ( ! function_exists( 'docdirect_set_likes' ) ) {
	function docdirect_set_likes(){
		$like_id	= !empty( $_POST['like_id'] ) ?  esc_attr( $_POST['like_id'] ) : 0;
		$json	= array();
		$data	= '';
		$count	= 0;
		$count_key = 'doc_user_likes_count';
		
		if( !empty( $like_id ) ) {
			$count     = get_user_meta($like_id , $count_key , true);
			$count	= !empty( $count ) ? $count : 0;
			
			if ( isset($_COOKIE["user_likes_" . $like_id]) ) { 
				//Cookie already set, nothing to do
			} else{
				setcookie("user_likes_" . $like_id , 'user_likes' , time()+31556926,'/');
				if( empty( $count ) ){
					$count = 1;
					update_user_meta( $like_id, $count_key, $count );
				}else{
					$count++;
					update_user_meta( $like_id, $count_key, $count );
				}
			}
			
			ob_start();
			
			echo '<a href="javascript:;" class="user-liked"><i class="fa fa-thumbs-up"></i>'.$count.'</a>';
			
			$data	= ob_get_clean();
			
			$type	= 'success';
		
		} else{
			$type	= 'error';
		}
		
		$json['html']	= $data;
		$json['type']	= $type;
		echo json_encode($json);
		die;
	}
	
	add_action('wp_ajax_docdirect_set_likes','docdirect_set_likes');
	add_action( 'wp_ajax_nopriv_docdirect_set_likes', 'docdirect_set_likes' );
}

/**
 * @get distance between two points
 * @return array
 */
if (!function_exists('docdirectGetDistanceBetweenPoints')) {
	function docdirectGetDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') {
		 $theta = $longitude1 - $longitude2;
		 $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		 $distance = acos($distance);
		 $distance = rad2deg($distance);
		 $distance = $distance * 60 * 1.1515; switch($unit) {
			  case 'Mi': break;
			  case 'Km' : $distance = $distance * 1.60934;
		 }
		 return (round($distance,2)).'&nbsp;'. strtolower( $unit );
	}
}

/**
 * @Sort by distance
 * @return array
 */
if (!function_exists('docdirect_search_by_distance_filter')) {
	add_action( 'pre_user_query', 'docdirect_search_by_distance_filter' );
	function docdirect_search_by_distance_filter( $user_query ) {
		global $wpdb;
	
		if( is_page_template('directory/user_search.php') ){
		
			if( !empty( $_GET['geo_location'] ) 
				&&
				isset( $_GET['sort_by'] )
				&& 
				$_GET['sort_by'] == 'distance'
			) {
				$Latitude   = !empty( $_GET['lat'] ) ? $_GET['lat'] : '';
				$Longitude  = !empty( $_GET['long'] ) ? $_GET['long'] : '';
				$geo_location	= $_GET['geo_location'];
				
				if( !empty( $Latitude ) && !empty( $Longitude ) ){
					$Latitude	 = $Latitude;
					$Longitude   = $Longitude;

				} else{
					$address	 = !empty($_GET['geo_location']) ? $_GET['geo_location'] : '';
					$prepAddr	= str_replace(' ','+',$address);

					$args = array(
						'timeout'     => 15,
						'headers' => array('Accept-Encoding' => ''),
						'sslverify' => false
					);

					$url	    = 'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
					$response   = wp_remote_get( $url, $args );
					$geocode	= wp_remote_retrieve_body($response);

					$output	  = json_decode($geocode);

					if( isset( $output->results ) && !empty( $output->results ) ) {
						$Latitude	 = $output->results[0]->geometry->location->lat;
						$Longitude   = $output->results[0]->geometry->location->lng;
					}
				}

			   if( !empty( $Latitude ) && !empty( $Longitude ) ){
				   $user_query->query_fields .= ", geo_search1.meta_value as lat, geo_search2.meta_value as lon, 
												( 3959 * acos( cos( radians( $Latitude ) ) 
												* cos( radians( geo_search1.meta_value ) ) 
												* cos( radians( geo_search2.meta_value ) 
												- radians( $Longitude ) ) 
												+ sin( radians( $Latitude ) ) 
												* sin( radians( geo_search1.meta_value ) ) ) ) * 1.60934 AS distance";  // additional fields 
												
				   $user_query->query_from .= " INNER JOIN ".$wpdb->prefix."usermeta AS geo_search1 ON ( ".$wpdb->prefix."users.ID = geo_search1.user_id ) AND geo_search1.meta_key = 'latitude' "; // additional joins here
				   $user_query->query_from .= " INNER JOIN ".$wpdb->prefix."usermeta AS geo_search2 ON ( ".$wpdb->prefix."users.ID = geo_search2.user_id ) AND geo_search2.meta_key = 'longitude' "; // additional joins here
				  
				   //$user_query->query_where .= ' distance '; // additional where clauses
				   $user_query->query_orderby  = ' ORDER BY distance ASC '; // additional sorting
				   //$user_query->query_limit .= ''; // if you need to adjust paging
			   }
			} else if( !empty( $_COOKIE['geo_location'] )
				&&
				isset( $_GET['sort_by'] )
				&& 
				$_GET['sort_by'] == 'distance'
			){
				$geo_location	= explode('|',$_COOKIE['geo_location']);
				
				$Latitude	= !empty( $geo_location[0] ) ? $geo_location[0] : '';
				$Longitude	= !empty( $geo_location[1] ) ? $geo_location[1] : '';
				
				if( !empty( $Latitude ) && !empty( $Longitude ) ){
				   $user_query->query_fields .= ", geo_search1.meta_value as lat, geo_search2.meta_value as lon, 
												( 3959 * acos( cos( radians( $Latitude ) ) 
												* cos( radians( geo_search1.meta_value ) ) 
												* cos( radians( geo_search2.meta_value ) 
												- radians( $Longitude ) ) 
												+ sin( radians( $Latitude ) ) 
												* sin( radians( geo_search1.meta_value ) ) ) ) * 1.60934 AS distance";  // additional fields 
												
				   $user_query->query_from .= " INNER JOIN ".$wpdb->prefix."usermeta AS geo_search1 ON ( ".$wpdb->prefix."users.ID = geo_search1.user_id ) AND geo_search1.meta_key = 'latitude' "; // additional joins here
				   $user_query->query_from .= " INNER JOIN ".$wpdb->prefix."usermeta AS geo_search2 ON ( ".$wpdb->prefix."users.ID = geo_search2.user_id ) AND geo_search2.meta_key = 'longitude' "; // additional joins here
				  
				   //$user_query->query_where .= ' distance '; // additional where clauses
				   $user_query->query_orderby  = ' ORDER BY distance ASC '; // additional sorting
				   //$user_query->query_limit .= ''; // if you need to adjust paging
			   }
			}
		
		  }
	  return $user_query;
	}
}

/**
 * @Get Filter for new Searrch page templates
 * @return array
 */
if (!function_exists('docdirect_search_filters')) {
	add_action( 'docdirect_search_filters', 'docdirect_search_filters' );
	function docdirect_search_filters() {
	global $wp_query;
	$zip_code	= isset( $_GET['zip'] ) ? $_GET['zip'] : '';
	$by_name	 = isset( $_GET['by_name'] ) ? $_GET['by_name'] : '';
	$args = array('posts_per_page' => '-1', 
				   'post_type' => 'directory_type', 
				   'post_status' => 'publish',
				   'suppress_filters' => false
			);
	
	$cust_query = get_posts($args);
	
	
	$dir_search_page 		= fw_get_db_settings_option('dir_search_page');
	$dir_search_pagination  = fw_get_db_settings_option('dir_search_pagination');
	$dir_longitude 			= fw_get_db_settings_option('dir_longitude');
	$dir_latitude 			= fw_get_db_settings_option('dir_latitude');
	$google_key 			= fw_get_db_settings_option('google_key');
	
	$dir_keywords 			= fw_get_db_settings_option('dir_keywords');
	$zip_code_search 		= fw_get_db_settings_option('zip_code_search');
	$dir_location 			= fw_get_db_settings_option('dir_location');
	$dir_radius 			= fw_get_db_settings_option('dir_radius');
	$language_search 		= fw_get_db_settings_option('language_search');
	$dir_search_cities 		= fw_get_db_settings_option('dir_search_cities');
	$dir_search_insurance 	= fw_get_db_settings_option('dir_search_insurance');
	$dir_phote 				= fw_get_db_settings_option('dir_phote');
	$dir_appointments 		= fw_get_db_settings_option('dir_appointments');
	
	
	$dir_longitude			= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude		 	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
	
	$insurance  	   = !empty( $_GET['insurance'] ) ? $_GET['insurance'] : '';
	$photos  	   	   = !empty( $_GET['photos'] ) ? $_GET['photos'] : '';
	$appointments      = !empty( $_GET['appointments'] ) ? $_GET['appointments'] : '';
	$city      		   = !empty( $_GET['city'] ) ? $_GET['city'] : '';
	
	
	if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
		$search_page 	 = get_permalink((int)$dir_search_page[0]);
	} else{
		$search_page 	 = '';
	}
	
	$languages_array	= docdirect_prepare_languages();//Get Language Array

	?>
	<div class="search-filters-wrap">
       <div class="doc-widget doc-widgetsearch">
          <div class="doc-widgetheading">
            <h2><?php esc_html_e('Narrow your search','docdirect');?></h2>
          </div>
          <div class="doc-widgetcontent">
              <fieldset>
                <?php if( isset( $dir_keywords ) && $dir_keywords === 'enable' ){?>
                  <div class="form-group">
                    <input type="text" class="form-control" value="<?php echo esc_attr( $by_name );?>" name="by_name" placeholder="<?php esc_html_e('Type Keyword...','docdirect');?>">
                  </div>
                <?php }?>
                <div class="form-group">
                  <div class="doc-select">
                    <select class="directory_type chosen-select" name="directory_type">
                      <option value=""><?php esc_html_e('Category','docdirect');?></option>
                      <?php
                        $parent_categories['categories']	= array();
                        $json				= array();
						$cat_json			= array();
                        $directories	 	= '';
                        if( isset( $cust_query ) && !empty( $cust_query ) ) {
                          $counter	= 0;
                          
                          foreach ($cust_query as $key => $dir) {
                            $counter++;
                            $title = get_the_title($dir->ID);
                            $dir_icon = fw_get_db_post_option($dir->ID, 'dir_icon', true);
                            $dir_map_marker = fw_get_db_post_option($dir->ID, 'dir_map_marker', true);
        					
							//Specialities
                            if( isset( $dir->ID ) ){
                                $attached_specialities = get_post_meta( $dir->ID, 'attached_specialities', true );
                                $subarray	= array();
                                if( isset( $attached_specialities ) && !empty( $attached_specialities ) ){
                                    foreach( $attached_specialities as $key => $speciality ){
                                        if( !empty( $speciality ) ) {
                                            $term_data	= get_term_by( 'id', $speciality, 'specialities');
                                            if( !empty( $term_data ) ) {
                                                $subarray[$term_data->slug] = $term_data->name;
                                            }
                                        }
                                    }
                                }
                                
                                $json[$dir->ID]	= $subarray;
                            }
							  
							//sub category
							if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){
								if (isset($dir->ID)) {
									$sub_categories = wp_get_post_terms($dir->ID, 'sub_category', array("fields" => "all"));

									$subarray = array();
									if (!empty($sub_categories)) {
										foreach ($sub_categories as $key => $sub_category) {
											if (!empty($sub_category)) {
												$subarray[$sub_category->slug] = htmlspecialchars_decode( $sub_category->name );
											}
										}
									}

									$cat_json[$dir->ID] = $subarray;
								}
                         		$sub_categories['sub_categories']	= $cat_json;
                          	}
                            
                            
                            $parent_categories['categories']	= $json;
							
                            
                            $selected	= '';
                            
                            if( !empty( $_GET['directory_type'] ) ) {
                                $directory_check = docdirect_get_page_by_slug( $_GET['directory_type'], 'directory_type','id' );
                            } else{
                               if (is_singular('directory_type')) {
									$directory_check = $wp_query->get_queried_object_id();
								} else {
									$directory_check = '';
								}
                            }
                        
                            if( isset( $directory_check ) && $directory_check == $dir->ID ){
                                $selected	= 'selected';
                            }
                            ?>
                            <option <?php echo esc_attr( $selected );?> data-dir_name="<?php echo esc_attr( $title );?>" id="<?php echo intval( $dir->ID );?>" value="<?php echo esc_attr( $dir->post_name );?>"><?php echo esc_attr( ucwords( $title ) );?></option>
                            <?php	
                          }
                        }
                     ?>	
                    </select>
                  </div>
                  <script>
				     
                    jQuery(document).ready(function() {
                        var Z_Editor = {};
                        Z_Editor.elements = {};
						Z_Editor.sub_categories = {};
                        window.Z_Editor = Z_Editor;
                        Z_Editor.elements = jQuery.parseJSON( '<?php echo addslashes(json_encode(  $parent_categories['categories']));?>' );
						Z_Editor.sub_categories = jQuery.parseJSON( '<?php echo addslashes(json_encode(  $sub_categories['sub_categories']));?>' );
						
						jQuery('select.directory_type').change(function(){
							var id		  = jQuery('option:selected', this).attr('id');		
							var dir_name	= jQuery(this).find(':selected').data('dir_name');

							if ( id === undefined || id === null) {
								jQuery( '.specialities-search-wrap' ).html('');
								jQuery( '.subcategories-search-wrap' ).html('');
							}
					
							if( Z_Editor.elements[id] ) {
								var load_subcategories = wp.template( 'load-subcategories' );
								var data = [];
								data['childrens']	 = Z_Editor.elements[id];
								var _options		 = load_subcategories(data);
								jQuery( '.specialities-search-wrap' ).html(_options);
							}
							
							if( scripts_vars.sub_category === 'enable' ) {
								if( Z_Editor.sub_categories[id]) {
									var load_chilcategories = wp.template( 'load-chilcategories' );
									var data = [];
									data['childrens']	 = Z_Editor.sub_categories[id];
									var _options		 = load_chilcategories(data);
									jQuery( '.subcategories-search-wrap' ).html(_options);
								}
							}
						});
						
						jQuery('select.directory_type').trigger('change');
                    });
                  </script> 
                  <script type="text/template" id="tmpl-load-subcategories">
                    <div class="doc-widget doc-widgetfilterspecialist">
                      <div class="doc-widgetheading">
                        <h2><?php esc_html_e('Filter By Specialities','docdirect'); ?></h2>
                      </div>
                      <div class="doc-widgetcontent">
                        <#
                            var _option	= '';
                            var browser_specialism = docdirectGetUrlParameter('speciality[]','yes');
                            if( !_.isEmpty(data['childrens']) ) {
                                _.each( data['childrens'] , function(element, index, attr) {
                                        var _checked	= '';
                                        if(jQuery.inArray(index,browser_specialism) !== -1){
                                            var _checked	= 'checked';
                                        }
                                     #>
                                     <div class="doc-checkbox">
                                        <input type="checkbox" name="speciality[]" {{_checked}} value="{{index}}" id="speciality-{{index}}">
                                        <label for="speciality-{{index}}">{{element}}</label>
                                     </div>
                                <#	
                                });
                            }
                        #>
                      </div>
                      <input type="submit" class="doc-btn" value="<?php esc_html_e('Refine Search','docdirect'); ?>">
                    </div>
                 </script> 
                 <?php if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){?>
					 <script type="text/template" id="tmpl-load-chilcategories">
					 	<# if( !_.isEmpty(data['childrens']) ) {#>
							<div class="doc-widget doc-widgetfilterspecialist">
							  <div class="doc-widgetheading">
								<h2><?php esc_html_e('Filter By Sub Category','docdirect'); ?></h2>
							  </div>
							  <div class="doc-widgetcontent">
								<#
									var _option	= '';
									var browser_specialism = docdirectGetUrlParameter('sub_category[]','yes');

									_.each( data['childrens'] , function(element, index, attr) {
											var _checked	= '';
											if(jQuery.inArray(index,browser_specialism) !== -1){
												var _checked	= 'checked';
											}
										 #>
										 <div class="doc-checkbox">
											<input type="checkbox" name="sub_category[]" {{_checked}} value="{{index}}" id="sub_category-{{index}}">
											<label for="sub_category-{{index}}">{{element}}</label>
										 </div>
									<#	
									});

								#>
							  </div>
							  <input type="submit" class="doc-btn" value="<?php esc_html_e('Refine Search','docdirect'); ?>">
							</div>
						<# } #>
					 </script>
                 <?php }?> 
                </div>
                <?php if( isset( $dir_search_insurance ) && $dir_search_insurance === 'enable' ){?>
                <div class="form-group">
                  <div class="doc-select">
                    <select name="insurance" class="chosen-select">
                        <option value=""><?php esc_attr_e('Select insurance','docdirect');?></option>
                        <?php docdirect_get_term_options($insurance,'insurance');?>
                    </select>
                  </div>
                </div>
                <?php }?>
                <?php if( isset( $dir_location ) && $dir_location === 'enable' ){?>
                  <div class="form-group">
                    <div class="tg-inputicon tg-geolocationicon tg-angledown">
                        <?php docdirect_locateme_snipt();?>
                        <script>
                            jQuery(document).ready(function(e) {
                                //init
                                jQuery.docdirect_init_map(<?php echo esc_js( $dir_latitude );?>,<?php echo esc_js( $dir_longitude );?>);
                            });
                        </script> 
                     </div>
                  </div>
                <?php }?>
                <?php if( !empty( $zip_code_search ) && $zip_code_search === 'enable' ){?>
                  <div class="form-group">
                    <input type="text" class="form-control" value="<?php echo esc_attr( $zip_code );?>" name="zip" placeholder="<?php esc_html_e('Search users by zip code','docdirect');?>">
                  </div>
                <?php }?>
                <?php if( !empty( $dir_search_cities ) && $dir_search_cities === 'enable' ){?>
                <div class="form-group">
                    <div class="doc-select">
                      <select name="city" class="chosen-select">
                        <option value=""><?php esc_attr_e('Select city','docdirect');?></option>
                        <?php docdirect_get_term_options($city,'locations');?>
                      </select>
                   </div>
                </div>
                <?php }?>
                <?php if( isset( $language_search ) && $language_search === 'enable' ){?>
                <?php  if( isset( $languages_array ) && !empty( $languages_array ) ){?>
                <div class="form-group">
                  <div class="doc-select">     
                     <select name="languages[]" class="chosen-select" data-placeholder="<?php esc_attr_e('Select languages','docdirect');?>" multiple>
                     <?php 
                        foreach( $languages_array as $key=>$value ){
                            $selected	= '';
                            if( !empty( $_GET['languages'] ) && in_array( $key , $_GET['languages']) ){
                                $selected	= 'selected';
                            }
                            ?>
                            <option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value );?></option>
                     <?php }?>
                    </select>
                   </div>
                </div>
                <?php }?>
                <?php }?>
                <?php do_action('docdirect_add_extra_filters','no');?>
                <?php if( isset( $dir_phote ) && $dir_phote === 'enable' ){?>
					<div class="doc-checkbox">
					  <input type="checkbox" name="photos" <?php echo isset( $photos ) && $photos === 'true' ? 'checked' : '';?> id="photos" value="true">
					  <label for="photos"><?php esc_html_e('All With Profile Photos','docdirect');?></label>
					</div>
                <?php }?>
                <?php if( isset( $dir_appointments ) && $dir_appointments === 'enable' ){?>
					<div class="doc-checkbox">
					  <input type="checkbox" name="appointments" <?php echo isset( $appointments ) && $appointments === 'true' ? 'checked' : '';?> id="appointment" value="true">
					  <label for="appointment" ><?php esc_html_e('Online Appointment','docdirect');?></label>
					</div>
                <?php }?>
                <div class="doc-btnarea">
                  <button class="doc-btn" type="submit"><?php esc_html_e('Apply Filter','docdirect');?></button>
                  <button class="doc-btn tg-btn-reset" type="reset"><?php esc_html_e('Reset Filter','docdirect');?></button>
                  <input type="hidden" name="view" value="<?php echo !empty($_GET['view'] ) ? $_GET['view'] : '';?>" />
                </div>
              </fieldset>
          </div>
        </div>
        <?php if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){?>
			<div class="subcategories-search-wrap"></div>
		<?php }?>
		<div class="specialities-search-wrap"></div>
	</div>
    <?php
	}
}

/**
 * @User address and geo location was same, so this function will fix address only one time
 * @return array
 */
if (!function_exists('docdirect_users_address_fix')) {
    function docdirect_users_address_fix() {
        if ( get_option('tg_address_updated') ) {
			return;
		}

		$query_args = array(
            'role' => 'professional',
        );
        
		$user_query = new WP_User_Query($query_args);
		foreach ($user_query->results as $user) {
			$new_address = get_user_meta($user->ID, 'user_address', true);
			$previuos_address = get_user_meta($user->ID, 'address', true);
			if( empty( $new_address ) ){
				update_user_meta($user->ID, 'user_address', $previuos_address); //Doctors
			}
		}
		
		update_option('tg_address_updated','yes');
	}
}


/**
* @refine author base if username and base matched eg : anything/anything
* @return {}
*/
if (!function_exists('docdirect_get_username')) {

    function docdirect_get_username($user_id='') {
		if( empty( $user_id ) ) {
			return esc_html__('unnamed', 'docdirect');;
		}
		
		$userdata	= get_userdata( $user_id );
		
		if( !empty( $userdata->first_name ) && !empty( $userdata->last_name ) ){
			return $userdata->first_name .' '. $userdata->last_name;
		} else if( !empty( $userdata->first_name ) && empty( $userdata->last_name ) ){
			return $userdata->first_name;
		} else if( empty( $userdata->first_name ) && !empty( $userdata->last_name ) ){
			return $userdata->last_name;
		} else {
			return esc_html__('unnamed', 'docdirect');
		}
	}
}


/**
 * @get packages settings
 * @return 
 */
if (!function_exists('docdirect_get_packages_setting')) {
	add_filter( 'docdirect_get_packages_setting','docdirect_get_packages_setting');
	function docdirect_get_packages_setting($default=''){
	  $packages_settings = get_option('docdirect_packages_settings');
	  $packages_settings =  !empty( $packages_settings ) ? $packages_settings : 'default';
	  
	  return $packages_settings;
	}
}

/**
 * @get packages settings
 * @return 
 */
if (!function_exists('docdirect_get_package_check')) {
	function docdirect_get_package_check($post_id,$key){
		if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
			$is_included= get_post_meta( $post_id, $key, true );
			return isset( $is_included ) && $is_included === 'yes' ? 'fa fa-check' : 'fa fa-close';
		} else{
			$is_included = fw_get_db_post_option($post_id, $key, true);
			return isset( $is_included ) && $is_included === true ? 'fa fa-check' : 'fa fa-close';
		}
	}
}


/**
 * @check settings for packages options
 * @return 
 */
if (!function_exists('docdirect_is_setting_enabled')) {
	function docdirect_is_setting_enabled($user_id, $filter_type){
		$data	= get_userdata( $user_id );
		$directory_type	= $data->directory_type;
		$booking_switch    = '';

		if(function_exists('fw_get_db_settings_option')) {
			$booking_switch    = fw_get_db_post_option($directory_type, 'bookings', true);
		}

		if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
			
			$package_expiry    = get_user_meta( $user_id, 'user_current_package_expiry', true);
			$current_package   = get_user_meta( $user_id, 'user_current_package', true);
			$current_date	= date('Y-m-d H:i:s');
			$is_included 	= '';
			
			$type	= get_post_type($current_package);

			if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
				if( !empty( $type ) && $type === 'product' ){
					$woo_key 	 	= apply_filters('docdirect_get_filter_key', $filter_type);
					$is_included 	= get_post_meta( $current_package, $woo_key, true );
					$is_included	= $is_included === 'yes' ? true : false;
				} else{
					if( !empty( $current_package ) ){
						$is_included = fw_get_db_post_option($current_package, $filter_type, true);
					}
				}
			} else {
				if( !empty( $current_package ) ){
					$is_included = fw_get_db_post_option($current_package, $filter_type, true);
				}
			}

			$is_included 	= !empty( $is_included ) ? $is_included  : false;
			
			if( isset( $is_included ) && $is_included === true ){
				if( !empty( $package_expiry ) && $package_expiry >  strtotime($current_date) ){
					return true;
				} else{
					return false;
				}
			} else{
				return false;
			}

		} else{
			
			if( isset( $filter_type ) && $filter_type === 'appointments' ){
				
				if( $booking_switch === 'enable' ){
					return true;
				} else{
					return false;  
				}
			} else{
				return true;
			}
	
		}
	}
	add_filter( 'docdirect_is_setting_enabled','docdirect_is_setting_enabled',10,2);
}

/**
 * @get packages key for WooCommerce
 * @return 
 */
if (!function_exists('docdirect_get_filter_key')) {
	add_filter('docdirect_get_filter_key','docdirect_get_filter_key',10,1);
	function docdirect_get_filter_key($key = ''){
		$filters	= array(
						'appointments'			=> 'dd_appointments',
						'profile_banner'		=> 'dd_banner',
						'insurance'		=> 'dd_insurance',
						'favorite'		=> 'dd_favorites',
						'team'			=> 'dd_teams',
						'schedules'		=> 'dd_hours',
						'articles'		=> 'dd_articles',
						'dd_qa'			=> 'dd_qa',
						'default'		=> 'default',
					);
		
		if( !empty( $filters[$key] )){
			return $filters[$key];
		} else{
			return $filters['default'];
		}
	}
}


/**
 * @User default avatar
 * @return 
 */
if (!function_exists('docdirect_user_profile_avatar')) {
	add_filter('get_avatar','docdirect_user_profile_avatar',10,5);
	function docdirect_user_profile_avatar($avatar = '', $id_or_email, $size = 80, $default = '', $alt = false ){

		if ( is_numeric( $id_or_email ) )
				$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;

		if ( empty( $user_id ) )
			return $avatar;

		$local_avatars =  docdirect_get_user_avatar(array('width'=>$size,'height'=>$size), $user_id);

		if ( empty( $local_avatars ) )
			return $avatar;

		$size = (int) $size;

		if ( empty( $alt ) )
			$alt = get_the_author_meta( 'display_name', $user_id );
		$author_class = is_author( $user_id ) ? ' current-author' : '' ;
		$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars . "' class='avatar photo' width='".$size."' height='".$size."'  />";

		return apply_filters( 'docdirect_get_user_avatar', $avatar );

	}
}

/**
 * @Reset Password Form
 * @return 
 */
if (!function_exists('docdirect_verify_user_account')) {

    function docdirect_verify_user_account() {
        global $wpdb;
        

        if ( !empty($_GET['key']) && !empty($_GET['verifyemail']) ) {
            $verify_key 	= esc_attr( $_GET['key'] );
            $user_email 	= esc_attr( $_GET['verifyemail'] );

            $user_identity = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_email = %s", $user_email));
			if( !empty( $user_identity ) ){
				$confirmation_key = get_user_meta(intval( $user_identity ), 'confirmation_key', true);
				if ( $confirmation_key === $verify_key ) {
					update_user_meta( $user_identity, 'confirmation_key', '');
					update_user_meta( $user_identity, 'verify_user', 'on');
					
					$script = "jQuery(document).on('ready', function () { jQuery.sticky(scripts_vars.account_verification, {classList: 'success', speed: 200, autoclose: 20000, position: 'top-right', }); });";
            		wp_add_inline_script('docdirect_functions', $script, 'after');
				}
			}
        }
    }

    add_action('docdirect_verify_user_account', 'docdirect_verify_user_account');
}

/**
 * @Demo Ready
 * @return {}
 */
if (!function_exists('docdirect_is_demo_site')) {
	function docdirect_is_demo_site($message=''){
		$message	= !empty( $message ) ? $message : esc_html__("Sorry! you are restricted to perform this action on demo site.",'docdirect' );
		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'themographics.com' ){
			$json['type']	   =  "error";
			$json['message']	=  $message;
			echo json_encode( $json );
			exit();
		}
	}
}

/**
 * @Reset Password Form
 * @return 
 */
if (!function_exists('docdirect_display_provider_category')) {

    function docdirect_display_provider_category($user_id) {
        global $wpdb;
		if( empty( $user_id ) ){return;}
		
        $provider_category = docdirect_get_provider_category($user_id);
		
		if( empty( $provider_category ) ){return;}
		
		if (function_exists('fw_get_db_settings_option')) {
			$bg_color = fw_get_db_post_option($provider_category, 'category_color', true);
			if (!empty($bg_color)) {
				$bg_color = 'style=background:' . $bg_color;
			}
		}

		$cat_url	= get_permalink($provider_category);
		$cat_title	= get_the_title($provider_category);
		
		ob_start();
		?>
		<?php if (!empty($provider_category)) { ?>
			<a class="doc-themetag tg-categorytag sp-av-link" <?php echo esc_attr($bg_color); ?> href="<?php echo esc_url($cat_url); ?>">
				<?php echo esc_attr($cat_title); ?>
			</a>
		<?php } ?>
		<?php
		echo ob_get_clean();
		
    }
    add_action('docdirect_display_provider_category', 'docdirect_display_provider_category',10,1);
}