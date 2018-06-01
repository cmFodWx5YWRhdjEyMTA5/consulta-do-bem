<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();
$args = array('posts_per_page' => '-1', 
			   'post_type' => 'directory_type', 
			   'post_status' => 'publish',
			   'suppress_filters' => false
		);

//By categories 
if (!empty($atts['categories'])) {
	$posts_in['post__in'] = $atts['categories'];
    $args = array_merge($args, $posts_in);
}

$cust_query = get_posts($args);
docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map
$dir_search_page = fw_get_db_settings_option('dir_search_page');
if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
	$search_page 	 = get_permalink((int)$dir_search_page[0]);
} else{
	$search_page 	 = '';
}

if (function_exists('fw_get_db_settings_option')) {
	$dir_location = fw_get_db_settings_option('dir_location');
	$dir_keywords = fw_get_db_settings_option('dir_keywords');
	$dir_longitude = fw_get_db_settings_option('dir_longitude');
	$dir_latitude = fw_get_db_settings_option('dir_latitude');
	$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
	$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
} else{
	$dir_location = '';
	$dir_keywords = '';
	$dir_longitude = '-0.1262362';
	$dir_latitude  = '51.5001524';
}



?>

<div class="sc-dir-search v2">
  <div id="tg-homebanner" class="tg-homebanner tg-haslayout">
    <div id="map_canvas" class="tg-location-map tg-haslayout"></div>
    <?php do_action('docdirect_map_controls');?>
    <div id="gmap-noresult"></div>
    <div class="tg-searcharea tg-haslayout">
      <div class="container">
        <div class="row">
          <form class="tg-searchform directory-map" action="<?php echo esc_url( $search_page);?>" method="get" id="directory-map">
            <?php if( isset( $dir_keywords ) && $dir_keywords === 'enable' ){?>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <div class="form-group">
                <input type="text" name="by_name" placeholder="<?php esc_html_e('Type Keyword...','docdirect');?>" class="form-control">
              </div>
            </div>
            <?php }?>
            <?php if( isset( $dir_location ) && $dir_location === 'enable' ){?>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <div class="form-group">
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
            <div class="col-md-2 col-sm-2 col-xs-12">
              <div class="form-group"> <span class="select">
                <select class="directory_type chosen-select" name="directory_type">
                  <option value="">
                  	<?php esc_html_e('Categories','docdirect');?>
                  </option>
				<?php
				$parent_categories	= array();
				$direction	= docdirect_get_location_lat_long();
				$parent_categories['categories']	= array();
				$first_category	= '';
				$json			  = array();
				$flag	= false;
				
				$directories_array	=  array();
				$directories	=  array();
				$directories['status']	= 'none';
				$directories['lat']  = floatval ( $direction['lat'] );
				$directories['long'] = floatval ( $direction['long'] );
					
				if( isset( $cust_query ) && !empty( $cust_query ) ) {
					  $counter	= 0;
					  foreach ($cust_query as $key => $dir) {
							$counter++;
							$title = get_the_title($dir->ID);
							$dir_icon = fw_get_db_post_option($dir->ID, 'dir_icon', true);
							$dir_map_marker = fw_get_db_post_option($dir->ID, 'dir_map_marker', true);
							if( empty( $dir_icon ) ){
								$dir_icon	= 'icon-Hospitalmedicalsignalofacrossinacircle';
							}
							
							$user_query = new WP_User_Query( 
												array ( 
													'role' => 'professional',
													'order' => 'ASC',
													'meta_query' => array(
														'relation' => 'AND',
														array(
															'key'     => 'directory_type',
															'value'   => $dir->ID,
															'compare' => '='
														),
														array(
															'key'     => 'verify_user',
															'value'   => 'on',
															'compare' => '='
														),
													)
												)
											);
							
						  	
						  	
							$postdata = get_post($dir->ID); 
							$slug 	 = $postdata->post_name;
						  
							if ( ! empty( $user_query->results ) ) {
								$directories['status']	= 'found';
								$flag	= true;
								
								
								foreach ( $user_query->results as $user ) {
									$latitude	   = get_user_meta( $user->ID, 'latitude', true);
									$longitude	  = get_user_meta( $user->ID, 'longitude', true);
									$directory_type = get_user_meta( $user->ID, 'directory_type', true);
									$dir_map_marker = fw_get_db_post_option($directory_type, 'dir_map_marker', true);
									$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
									$featured_date  = get_user_meta($user->ID, 'user_featured', true);
									$current_date   = date('Y-m-d H:i:s');
									$avatar = apply_filters(
											'docdirect_get_user_avatar_filter',
											 docdirect_get_user_avatar(array('width'=>270,'height'=>270), $user->ID),
											 array('width'=>270,'height'=>270) //size width,height
										);

									$privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settin

									$directories_array['latitude']	 = $latitude;
									$directories_array['longitude']	= $longitude;
									$directories_array['fax']		  = $user->fax;
									$directories_array['description']  = $user->description;
									$directories_array['title']		= $user->display_name;
									$directories_array['name']	 	 = $user->first_name.' '.$user->last_name;
									$directories_array['email']	 	= $user->user_email;
									$directories_array['phone_number'] = $user->phone_number;
									$directories_array['address']	  = $user->user_address;
									$directories_array['group']		= $slug;
									$featured_string   = $featured_date;
									$current_string	= strtotime( $current_date );
									$review_data	= docdirect_get_everage_rating ( $user->ID );
									$get_username	= docdirect_get_username( $user->ID );
									$get_username	= docdirect_get_username( $user->ID );

									if( isset( $dir_map_marker['url'] ) && !empty( $dir_map_marker['url'] ) ){
										$directories_array['icon']	 = $dir_map_marker['url'];
									} else{
										if( !empty( $dir_map_marker_default['url'] ) ){
											$directories_array['icon']	 = $dir_map_marker_default['url'];
										} else{
											$directories_array['icon']	 	   = get_template_directory_uri().'/images/map-marker.png';
										}
									}

									$infoBox	= '<div class="tg-map-marker">';
									$infoBox	.= '<figure class="tg-docimg"><a class="userlink" target="_blank" href="'.get_author_posts_url($user->ID).'"><img src="'.esc_url( $avatar ).'" alt="'.esc_attr( $directories_array['name'] ).'"></a>';
									$infoBox	.= docdirect_get_wishlist_button($user->ID,false);

									if( isset( $featured_string ) && $featured_string > $current_string ){
										$infoBox	.= docdirect_get_featured_tag(false); 
									}

									$infoBox	.= docdirect_get_verified_tag(false,$user->ID);

									if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){
										$infoBox	.= docdirect_get_rating_stars($review_data,'return');
									}

									$infoBox	.= '</figure>';

									$infoBox	.= '<div class="tg-mapmarker-content">';
									$infoBox	.= '<div class="tg-heading-border tg-small">';
									$infoBox	.= '<h3><a class="userlink" target="_blank" href="'.get_author_posts_url($user->ID).'">'.$directories_array['name'].'</a></h3>';
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
										$infoBox	.= '<li> <i class="fa fa-home"></i> <address>'.$directories_array['address'].'</address> </li>';
									}

									$infoBox	.= '</ul>';
									$infoBox	.= '</div>';
									$infoBox	.= '</div>';

									$directories_array['html']['content']	= $infoBox;
									$directories['users_list'][]	= $directories_array;
								}
							} else if( $flag === false ){
								$directories['status']	= 'empty';
							}
							
							$active	= '';
							if( $counter === 1 ){ 
								$active	= 'active';
								$first_category	= $dir->ID;
							}
							
							//Prepare categories
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
							
							
							$parent_categories['categories']	= $json;
							?>
                  			<option id="<?php echo intval( $dir->ID );?>" data-dir_name="<?php echo esc_attr( $title );?>" value="<?php echo esc_attr( $dir->post_name );?>"><?php echo esc_attr( ucwords( $title ) );?></option>
                  <?php }?>
                  <?php } else{
					  $directories['status']	= 'empty'; 
				}?>
                </select>
                </span> 
                <script>
					jQuery(document).ready(function() {
						var Z_Editor = {};
						Z_Editor.elements = {};
						window.Z_Editor = Z_Editor;
						Z_Editor.elements = jQuery.parseJSON( '<?php echo addslashes(json_encode($parent_categories['categories']));?>' );
						
						/* Init Markers */
						docdirect_init_map_script(<?php echo json_encode( $directories );?>);
					});
				</script> 
                <script type="text/template" id="tmpl-load-subcategories">
					<option value="">{{data['parent']}} - <?php esc_html_e('Specialities','docdirect');?></option>
					<#
						var _option	= '';
						if( !_.isEmpty(data['childrens']) ) {
							_.each( data['childrens'] , function(element, index, attr) { #>
								 <option value="{{index}}">{{element}}</option>
							<#	
							});
						}
					#>
				</script> 
              </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
              <div class="form-group"> <span class="select">
                <select class="group subcats chosen-select" name="speciality[]">
                  <option value="">
                  <?php esc_html_e('Specialities','docdirect');?>
                  </option>
                  <?php 
					if( isset( $first_category ) ){
						$attached_specialities = get_post_meta( $first_category, 'attached_specialities', true );
						if( isset( $attached_specialities ) && !empty( $attached_specialities ) ){
							foreach( $attached_specialities as $key => $speciality ){
								if( !empty( $speciality ) ) {
									$term_data	= get_term_by( 'id', $speciality, 'specialities');
									if( !empty( $term_data ) ) {?>
	  									<option value="<?php echo esc_attr( $term_data->slug);?>"><?php echo esc_attr( $term_data->name );?></option>
	  <?php
									}
								}
							}
						}
					}
					?>
                </select>
                </span> </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-4">
              <div class="form-group"> <a  href="javascript:;"></a>
                <input type="submit" id="search_banner" class="tg-btn" value="<?php esc_html_e('Search','docdirect');?>" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
