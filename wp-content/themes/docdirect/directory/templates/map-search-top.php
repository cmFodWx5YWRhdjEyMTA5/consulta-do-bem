<?php
/**
 * Map Search Postion Top
 * return html
 */

global $current_user, $wp_roles,$userdaResetta,$post,$wp_query;
get_header();
$zip_code	= isset( $_GET['zip'] ) ? $_GET['zip'] : '';
$by_name	 = isset( $_GET['by_name'] ) ? $_GET['by_name'] : '';
$args = array('posts_per_page' => '-1', 
			   'post_type' => 'directory_type', 
			   'post_status' => 'publish',
			   'suppress_filters' => false
			);

$cust_query = get_posts($args);
docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map

$search_page_map 		= fw_get_db_settings_option('search_page_map');
$dir_search_page 		= fw_get_db_settings_option('dir_search_page');
$dir_keywords 			= fw_get_db_settings_option('dir_keywords');
$dir_location 			= fw_get_db_settings_option('dir_location');
$language_search 		= fw_get_db_settings_option('language_search');
$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
$dir_search_view 		= fw_get_db_settings_option('dir_search_view');
$dir_search_pagination  = fw_get_db_settings_option('dir_search_pagination');
$dir_longitude 			= fw_get_db_settings_option('dir_longitude');
$dir_latitude 			= fw_get_db_settings_option('dir_latitude');
$google_key 			= fw_get_db_settings_option('google_key');
$zip_code_search 		= fw_get_db_settings_option('zip_code_search');
$dir_search_cities 		= fw_get_db_settings_option('dir_search_cities');
$dir_search_insurance 		= fw_get_db_settings_option('dir_search_insurance');
$dir_longitude			= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
$dir_latitude		 	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
	

$listing_view	= 'tg-list-view';
if( isset( $dir_search_view ) && $dir_search_view === 'grid' ){
	$listing_view	= 'tg-grid-view';
}

//Demo User Only 
if( isset( $_GET['view'] ) && $_GET['view'] === 'grid' ){
	$listing_view	= 'tg-grid-view';
}

if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
	$search_page 	 = get_permalink((int)$dir_search_page[0]);
} else{
	$search_page 	 = '';
}

$languages_array	= docdirect_prepare_languages();//Get Language Array

//Search center point
$direction	= docdirect_get_location_lat_long();

if( isset( $search_page_map ) && $search_page_map === 'enable' ){

?>
<div class="map-top">
    <div class="row tg-divheight">
        <div class="tg-mapbox">
            <div id="map_canvas" class="tg-location-map tg-haslayout"></div>
            <?php do_action('docdirect_map_controls');?>
            <div id="gmap-noresult"></div>
        </div>
    </div>
</div>
<?php }?>
<?php if( have_posts() ) {?>
    <div class="container">
        <div class="row">
            <?php 
                while ( have_posts() ) : the_post();
                    the_content();
                endwhile;
            ?>
        </div>
    </div>
<?php }?>
<div class="container">
  <div class="row">
      <form class="form-refinesearch tg-haslayout">
      <div class="col-md-4 col-sm-12 col-xs-12 pull-left">
          <div class="tg-doctors-list tg-haslayout">
            <div class="tg-refinesearcharea">
              <div class="tg-heading-border tg-small">
                <h2><?php esc_html_e('refine search','docdirect');?></h2>
              </div>
              <fieldset>
                  <div class="row">
                    <?php if( isset( $dir_keywords ) && $dir_keywords === 'enable' ){?>
                    <div class="col-md-12 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <input type="text" class="form-control" value="<?php echo esc_attr( $by_name );?>" name="by_name" placeholder="<?php esc_html_e('Type Keyword...','docdirect');?>">
                      </div>
                    </div>
                    <?php }?>
                    <?php if( isset( $dir_location ) && $dir_location === 'enable' ){?>
                    <div class="col-md-12 col-sm-6 col-xs-6">
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
                    <?php if( !empty( $zip_code_search ) && $zip_code_search === 'enable' ){?>
                    <div class="col-md-12 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <input type="text" class="form-control" value="<?php echo esc_attr( $zip_code );?>" name="zip" placeholder="<?php esc_html_e('Search users by zip code','docdirect');?>">
                      </div>
                    </div>
                    <?php }?>
                    <?php if( !empty( $language_search ) && $language_search === 'enable' ){?>
                    <div class="col-md-12 col-sm-6 col-xs-6">
                        <div class="form-group"> 
                            <span class="doc-select">
                                <select class="group language-selelct" name="language">
                                  <option value=""><?php esc_html_e('Select language','docdirect');?></option>
                                  <?php 
                                	if( isset( $languages_array ) && !empty( $languages_array ) ){
                                    
                                    foreach( $languages_array as $key=>$value ){
                                        $selected	= '';
                                        if( isset( $_GET['language'] ) && $_GET['language'] == $key ){
                                            $selected	= 'selected';
                                        }
                                        ?>
                                    <option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value );?></option>
                                <?php }}?>
                                </select>
                            </span> 
                        </div>
                    </div>
                    <?php }?>
                    <?php if( isset( $dir_search_insurance ) && $dir_search_insurance === 'enable' ){?>
					<div class="col-md-12 col-sm-6 col-xs-6">
						<div class="form-group">
						  <div class="doc-select">
							<select name="insurance" class="chosen-select">
								<option value=""><?php esc_attr_e('Select insurance','docdirect');?></option>
								<?php docdirect_get_term_options($insurance,'insurance');?>
							</select>
						  </div>
						</div>
					</div>
					<?php }?>
                    <?php if( !empty( $dir_search_cities ) && $dir_search_cities === 'enable' ){?>
                    <div class="col-md-12 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="doc-select">
                              <select name="city" class="chosen-select">
                                <option value=""><?php esc_attr_e('Select city','docdirect');?></option>
                                <?php docdirect_get_term_options($city,'locations');?>
                              </select>
                           </div>
                        </div>
                    </div>
                    <?php }?>
                    <div class="col-md-12 col-sm-6 col-xs-6">
                      <div class="form-group"> 
                        <span class="select">
                            <select class="directory_type chosen-select" name="directory_type">
                              <option value=""><?php esc_html_e('Category','docdirect');?></option>
                              <?php
                                $parent_categories['categories']	= array();
								if( !empty( $_GET['directory_type'] ) ) {
									$first_category = docdirect_get_page_by_slug( $_GET['directory_type'], 'directory_type','id' );
								} else{
									$first_category = '';
								}
				
                                $json			 	= array();
								$sub_categories		= array();
                                $directories	 	= '';
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
                        </span> 
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
											var data 			 = [];
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
									<# if( !_.isEmpty(data['childrens']) ) { #>
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
                    </div>
                    <?php do_action('docdirect_add_extra_filters','included_wrap');?>
                    <div class="col-sm-6 col-xs-6">
                      <button type="submit" class="tg-btn pull-left"><?php esc_html_e('Search','docdirect');?></button>
                      <input type="hidden" name="view" value="<?php echo isset( $_GET['view'] ) ? $_GET['view'] : '';?>" />
                    </div>
					<div class="col-sm-6 col-xs-6">
                      <button type="reset" class="tg-btn tg-btn-reset pull-right"> <i class="fa fa-rotate-left"></i> <span><?php esc_html_e('Reset','docdirect');?></span> </button>
                    </div>
                  </div>
                </fieldset>
            </div>
            <?php if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){?>
            	<div class="subcategories-search-wrap"></div>
            <?php }?>
            <div class="specialities-search-wrap"></div>
          </div>
          <?php if (is_active_sidebar('search-page-sidebar')) {?>
          <div class="tg-doctors-list tg-haslayout doc-sidebar">
          	<?php dynamic_sidebar('search-page-sidebar'); ?>
          </div>
          <?php }?>
      </div>
      <div class="col-md-8 col-sm-12 col-xs-12 pull-right">
      	<div class="tg-doctors-list tg-haslayout">
        	<div class="tg-doclisthead">
              <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-6">
                  <div class="form-group"> 
                    <span class="select">
                        <select name="sort_by" class="sort_by" id="sort_by">
                          <option value=""><?php esc_html_e('Sort By','docdirect');?></option>
                          <option value="recent" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'recent' ? 'selected' : '';?>><?php esc_html_e('Most recent','docdirect');?></option>
                          <option value="featured" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'featured' ? 'selected' : '';?>><?php esc_html_e('Featured','docdirect');?></option>
                          <option value="title" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'title' ? 'selected' : '';?>><?php esc_html_e('Alphabetical','docdirect');?></option>
                          <option value="distance" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'distance' ? 'selected' : '';?>><?php esc_html_e('Sort By Distance','docdirect');?></option>
                          <option value="likes" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'likes' ? 'selected' : '';?>><?php esc_html_e('Sort By Likes','docdirect');?></option>
                      </select>
                    </span> 
                   </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-6">
                  <div class="form-group"> 
                    <span class="select">
                        <select class="order_by" name="order" id="order">
                          <option value="ASC" <?php echo isset( $_GET['order'] ) && $_GET['order'] == 'ASC' ? 'selected' : '';?>><?php esc_html_e('ASC','docdirect');?></option>
                          <option value="DESC" <?php echo isset( $_GET['order'] ) && $_GET['order'] == 'DESC' ? 'selected' : '';?>><?php esc_html_e('DESC','docdirect');?></option>
                        </select>
                    </span> 
                   </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-6">
                  <div class="form-group"> 
                    <span class="select">
                        <select name="per_page" class="per_page">
                            <option value=""><?php esc_html_e('Per Page','docdirect');?></option>
                            <option value="10" <?php echo isset( $_GET['order'] ) && $_GET['order'] == '10' ? 'selected' : '';?>>10</option>
                            <option value="20" <?php echo isset( $_GET['order'] ) && $_GET['order'] == '20' ? 'selected' : '';?>>20</option>
                            <option value="50" <?php echo isset( $_GET['order'] ) && $_GET['order'] == '50' ? 'selected' : '';?>>50</option>
                            <option value="70" <?php echo isset( $_GET['order'] ) && $_GET['order'] == '70' ? 'selected' : '';?>>70</option>
                            <option value="100" <?php echo isset( $_GET['order'] ) && $_GET['order'] == '100' ? 'selected' : '';?>>100</option>
                         </select>
                    </span> 
                   </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <ul class="tg-listing-views">
                    <li class="<?php echo isset( $dir_search_view ) && $dir_search_view === 'grid' ? 'active' : '';?>"><a href="javascript:;" class="grid"><i class="fa fa-th-large"></i></a></li>
                    <li class="<?php echo isset( $dir_search_view ) && $dir_search_view === 'list' ? 'active' : '';?>"><a href="javascript:;" class="list"><i class="fa fa-th-list"></i></a></li>
                  </ul>
                </div>
              </div>
            </div>
      	<div class="tg-view <?php echo sanitize_html_class( $listing_view );?>">
          <div class="row">
          	<?php
				$user_query  = new WP_User_Query($query_args);
			  	$directories	=  array();
			  	
			  	$directories['status']	= 'none';
			  	$directories['lat']  = floatval ( $direction['lat'] );
				$directories['long'] = floatval ( $direction['long'] );
			  
				if ( ! empty( $user_query->results ) ) {
					$directories['status']	= 'found';

					if( isset( $directory_type ) && !empty( $directory_type ) ) {
						$title = get_the_title($directory_type);
						$postdata = get_post($directory_type); 
						$slug 	 = $postdata->post_name;
					} else{
						$title = '';
						$slug = '';
					}

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
							
						$avatar_grid = apply_filters(
								'docdirect_get_user_avatar_filter',
								 docdirect_get_user_avatar(array('width'=>370,'height'=>200), $user->ID) ,
								 array('width'=>370,'height'=>200) //size width,height=
							);
							
						$privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settin

						$directories_array['latitude']	 	= $latitude;
						$directories_array['longitude']		= $longitude;
						$directories_array['fax']		  	= $user->fax;
						$directories_array['description']   = $user->description;
						$directories_array['title']			= $user->display_name;
						$directories_array['name']	 	 	= $user->first_name.' '.$user->last_name;
						$directories_array['email']	 		= $user->user_email;
						$directories_array['phone_number']  = $user->phone_number;
						$directories_array['address']	  	= $user->user_address;
						$directories_array['group']			= $slug;
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

						$infoBox	= '<div class="tg-map-marker">';
						$infoBox	.= '<figure class="tg-docimg"><a class="userlink"  target="_blank" href="'.get_author_posts_url($user->ID).'"><img src="'.esc_url( $avatar ).'" alt="'.esc_attr( $directories_array['name'] ).'"></a>';
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

						?>
						<article class="tg-doctor-profile user-<?php echo intval( $user->ID );?> doc-cat-pos">
						  <div class="tg-box">
							<figure class="tg-docprofile-img">
								<a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>"></a>
								<a href="<?php echo get_author_posts_url($user->ID); ?>"  class="grid-avatar"><img src="<?php echo esc_attr( $avatar_grid );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>"></a>
								<?php do_action('docdirect_display_provider_category',$user->ID);?>

								<?php docdirect_get_wishlist_button($user->ID,true);?>
								<?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
									<?php docdirect_get_featured_tag(true);?>
								<?php }?>
								<?php docdirect_get_verified_tag(true,$user->ID);?>
								<?php
								 if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){
									docdirect_get_rating_stars($review_data,'echo');
								 }
								?>
							</figure>
							<div class="tg-docprofile-content">

							  <div class="tg-heading-border tg-small">
								<h3><a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo esc_attr( $directories_array['name'] );?></a></h3>
							  </div>
							  <?php if( !empty( $directories_array['description'] ) ){?>
								  <div class="tg-description">
									<p><?php echo substr($directories_array['description'], 0, 147);?></p>
								  </div>
							  <?php }?>
							  <ul class="tg-doccontactinfo">
								<?php if( !empty( $directories_array['address'] ) ) {?>
									<li><i class="fa fa-map-marker"></i><address>
									<?php echo docdirect_prepare_address(35,$directories_array['address']);?></address></li>
								<?php }?>
								<?php if( !empty( $directories_array['phone_number'] ) 
										  &&
											!empty( $privacy['phone'] )
										  && 
											$privacy['phone'] == 'on'
									) {?>
									<li><i class="fa fa-phone"></i><span><?php echo esc_attr( $directories_array['phone_number'] );?></span></li>
								<?php }?>
								<?php if( !empty( $directories_array['email'] ) 
										  &&
											!empty( $privacy['email'] )
										  && 
											$privacy['email'] == 'on'
									) {?>
									<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $directories_array['email']);?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $directories_array['email']);?></a></li>
								<?php }?>
								<?php if( !empty( $directories_array['fax'] ) ) {?>
									<li><i class="fa fa-fax"></i><span><?php echo esc_attr( $directories_array['fax']);?></span></li>
								<?php }?>
								<?php if( !empty( $user->user_url ) ) {?>
									<li><i class="fa fa-link"></i><a href="<?php echo esc_url( $user->user_url);?>"><?php echo docdirect_parse_url( $user->user_url);?></a></li>
								<?php }?>
							  </ul>
							</div>
						  </div>
						</article>
                    <?php
					}
				 } else{?>
					<div class="col-xs-12"><?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Result Found.','docdirect'));?></div>
				<?php }?>
                <?php if( isset( $search_page_map ) && $search_page_map === 'enable' ){?>
				<script>
					jQuery(document).ready(function() {
						 /* Init Markers */
						docdirect_init_map_script(<?php echo json_encode( $directories );?>);
					});
				</script>
                <?php }?> 
          	</div>
            
        </div>
        <?php 
		//Pagination
		if( $total_users > $limit ) {?>
          <div class="tg-btnarea">
                <?php docdirect_prepare_pagination($total_users,$limit);?>
          </div>
        <?php }?>
      </div>
    </div>
   	  </form>
  </div>
</div>
	
<?php
get_footer();

