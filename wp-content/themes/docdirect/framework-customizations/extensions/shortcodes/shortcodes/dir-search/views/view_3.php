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
$btn_title	= isset( $atts['btn_title'] ) && !empty( $atts['btn_title'] ) ? $atts['btn_title'] : esc_html__('Search','docdirect');
$def_latitude	= isset( $atts['latitude'] ) && !empty( $atts['latitude'] ) ? $atts['latitude'] : '51.5001524';
$def_longitude	= isset( $atts['longitude'] ) && !empty( $atts['longitude'] ) ? $atts['longitude'] : '-0.1262362';
docdirect_init_dir_map();//init Map

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
	$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
	$dir_latitude = fw_get_db_settings_option('dir_latitude');
	$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
	$dir_keywords = fw_get_db_settings_option('dir_keywords');
	$zip_code_search 		 = fw_get_db_settings_option('zip_code_search');
} else{
	$dir_location = '';
	$dir_keywords = '';
	$dir_longitude = '-0.1262362';
	$dir_latitude  = '51.5001524';
	$dir_keywords = '';
	$zip_code_search = '';
}

docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map
?>
<div class="sc-dir-search v3 tg-haslayout">
  <div id="tg-homebanner" class="tg-homebanner tg-overflowhidden tg-haslayout">
    <div id="map_canvas" class="tg-location-map tg-haslayout"></div>
    <?php do_action('docdirect_map_controls');?>
    <div id="gmap-noresult"></div>
    <div class="tg-banner-content">
      <div class="tg-displaytable">
        <div class="tg-displaytablecell">
          <div class="container">
            <form class="form-searchdoctors directory-map" action="<?php echo esc_url( $search_page);?>" method="get" id="directory-map">   
            <div class="col-md-3 col-sm-5 col-xs-3 tg-verticalmiddle">
              <div class="row">
                <?php if( isset( $cust_query ) && !empty( $cust_query ) ) {?>
                <ul id="tg-sortable" class="tg-tabs-nav tg-sortable" role="tablist">
                  <?php 
				  $counter	= 0;
                  $first_category	= '';
				  $json	= array();
				  $directories	= '';
				  $flag	= false;
				  $current_directory	= '';
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
					    $directories	=  array();
					  
						if ( ! empty( $user_query->results ) ) {
							$flag	= true;
							$directories['status']	= 'found';
							
							
							foreach ( $user_query->results as $user ) {
								$latitude	= get_user_meta( $user->ID, 'latitude', true);
								$longitude	= get_user_meta( $user->ID, 'longitude', true);
								$featured_date	= get_user_meta($user->ID, 'user_featured', true);
								$current_date = date('Y-m-d H:i:s');
								$avatar = apply_filters(
										'docdirect_get_user_avatar_filter',
										 docdirect_get_user_avatar(array('width'=>270,'height'=>270), $user->ID),
										 array('width'=>270,'height'=>270) //size width,height
									);
								
								$privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settings
								
								if( !empty( $latitude ) && !empty( $longitude ) ) {
									$directories_array['latitude']	= $latitude;
									$directories_array['longitude']	= $longitude;
									$directories_array['title']	= $user->display_name;
									$directories_array['name']	 = $user->first_name.' '.$user->last_name;
									$directories_array['email']	 = $user->user_email;
									$directories_array['phone_number']	 = $user->phone_number;
									$directories_array['address']	 = $user->user_address;
									$directories_array['group']	= $slug;
									$featured_string   = $featured_date;
									$current_string	= strtotime( $current_date );
									$featured_user	 = '';
									$featured_user	.= docdirect_get_wishlist_button($user->ID,false);
                
									if( isset( $featured_string ) && $featured_string > $current_string ){
										$featured_user	.= docdirect_get_featured_tag(false); 
									}
									
									$featured_user	.= docdirect_get_verified_tag(false,$user->ID);
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
									$infoBox	.= '<figure class="tg-docimg"><a class="userlink" href="'.get_author_posts_url($user->ID).'"><img src="'.esc_url( $avatar ).'" alt="'.esc_attr__('User','docdirect').'"><span class="tg-show"><em class="icon-add"></em></span></a>'.$featured_user.'</figure>';
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
										$infoBox	.= '<li> <i class="fa fa-envelope"></i> <em><a href="mailto:'.$directories_array['email'].'?Subject=hello" target="_top">'.$directories_array['email'].'</a></em> </li>';
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
							}
						}  else if( $flag === false ){
							$directories['status']	= 'empty';
						}
						
						$active	= '';
						$checked	= '';
						
						if( $counter === 1 ){ 
							$current_directory = get_the_title($dir->ID);
							$active	= 'active';
							$first_category	= $dir->ID;
							$checked	= 'checked';
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
						
						
						$array['categories']	= $json;
						
                        ?>
                         <li data-dir_name="<?php echo esc_attr( $title );?>" data-id="<?php echo intval( $dir->ID );?>" class="<?php echo sanitize_html_class( $active );?> current-directory">
                         	<input type="radio" <?php echo esc_attr( $checked );?> name="directory_type" id="<?php echo intval( $dir->ID );?>" value="<?php echo esc_attr( $slug );?>" />
                            <label for="<?php echo intval( $dir->ID );?>">
                                <i class="<?php echo esc_attr( $dir_icon );?>"></i> 
                                <span class="tg-category"> 
                                    <span class="tg-category-name"><?php echo esc_attr( $title );?></span>
                                    <span class="tg-category-doctores"><?php echo intval( count($user_query->get_results()) );?> <?php esc_html_e('Records','docdirect');?></span> 
                                </span> 
                            </label>
                          </li>
                 <?php }?>
                </ul>
                <script>
					jQuery(document).ready(function() {
						var Z_Editor = {};
						Z_Editor.elements = {};
						window.Z_Editor = Z_Editor;
						Z_Editor.elements = jQuery.parseJSON( '<?php echo addslashes(json_encode($array['categories']));?>' );
						
						jQuery('.current_directory').html("<?php echo esc_js( $current_directory );?>");
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
                <?php } else{
					  $directories['status']	= 'empty'; 
				}?>
              </div>
            </div>
            <div class="col-md-9 col-sm-7 col-xs-9 tg-verticalmiddle">
              <div class="row">
                  <fieldset>
                    <div class="tab-content tg-tab-content">
                      <div role="tabpanel" class="tab-pane active" id="nephrologist">
                        <div class="row">
                          <div class="col-md-6 col-sm-12 col-xs-12 tg-verticalmiddle">
                            <div class="form-group"> 
                              <span class="select">
                                  <select class="group subcats chosen-select" name="speciality[]">
                                    <option value=""><?php echo !empty( $current_directory ) ? $current_directory : '';?>&nbsp;<?php esc_html_e('Specialities','docdirect');?></option>
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
                              </span>
                            </div>
                            <?php if( isset( $dir_keywords ) && $dir_keywords === 'enable' ){?>
                                <div class="form-group">
                                  <input type="text" name="by_name" class="form-control" placeholder="<?php esc_attr_e('Type Keyword...','docdirect');?>">
                                </div>
                            <?php }?>
                            <?php if( !empty( $zip_code_search ) && $zip_code_search === 'enable' ){?>
                                <div class="form-group">
                                  <input type="text" name="zip" class="form-control" placeholder="<?php esc_attr_e('Search users by zip code','docdirect');?>">
                                </div>
                            <?php }?>
                            <?php if( isset( $dir_location ) && $dir_location === 'enable' ){?>
                                <div class="form-group">
                                    <div class="locate-me-wrap">
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
                            <div class="form-group"> 
                                <input type="submit" name="search" class="search-verticle-form tg-btn pull-right" value="<?php echo esc_attr( $btn_title );?>">
                            </div>
                          </div>
                          <div class="col-md-6 col-sm-12 col-xs-12 tg-verticalmiddle">
                            <h1><?php esc_attr_e('FIND YOUR','docdirect');?><span><?php esc_attr_e('NEAREST','docdirect');?></span>
                            <em class="current_directory"></em></h1>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="show-search"><i class="fa fa-search"></i></div>
  </div>
</div>
