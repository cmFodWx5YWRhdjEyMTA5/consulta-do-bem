<?php
/**
 * Map Search Postion Top
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
get_header();

$search_page_map 		= fw_get_db_settings_option('search_page_map');
$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map

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
    <div id="doc-twocolumns" class="doc-twocolumns doc-listview"> 
      <?php if( !empty( $found_title ) ) {?>
      	<span class="doc-searchresult"><?php echo force_balance_tags( $found_title );?></span>
      <?php }?>
      <form class="doc-formtheme doc-formsearchwidget search-result-form">
          <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 pull-right">
              <div id="doc-content" class="doc-content">
                <div class="doc-doctorlisting">
                  <div class="doc-pagehead">
                    <div class="doc-sortby"> 
                     <span class="doc-select">
                      <select name="sort_by" class="sort_by" id="sort_by">
                          <option value=""><?php esc_html_e('Sort By','docdirect');?></option>
                          <option value="recent" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'recent' ? 'selected' : '';?>><?php esc_html_e('Most recent','docdirect');?></option>
                          <option value="featured" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'featured' ? 'selected' : '';?>><?php esc_html_e('Featured','docdirect');?></option>
                          <option value="title" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'title' ? 'selected' : '';?>><?php esc_html_e('Alphabetical','docdirect');?></option>
                          <option value="distance" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'distance' ? 'selected' : '';?>><?php esc_html_e('Sort By Distance','docdirect');?></option>
                          <option value="likes" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'likes' ? 'selected' : '';?>><?php esc_html_e('Sort By Likes','docdirect');?></option>
                      </select>
                      </span>
                      <span class="doc-select">
                        <select class="order_by" name="order" id="order">
                          <option value="ASC" <?php echo isset( $_GET['order'] ) && $_GET['order'] == 'ASC' ? 'selected' : '';?>><?php esc_html_e('ASC','docdirect');?></option>
                          <option value="DESC" <?php echo isset( $_GET['order'] ) && $_GET['order'] == 'DESC' ? 'selected' : '';?>><?php esc_html_e('DESC','docdirect');?></option>
                        </select>
                      </span> 
                      <span class="doc-select">
                           <select name="per_page" class="per_page">
                            <option value=""><?php esc_html_e('Per Page','docdirect');?></option>
                            <option value="10" <?php echo isset( $_GET['per_page'] ) && $_GET['per_page'] == '10' ? 'selected' : '';?>>10</option>
                            <option value="20" <?php echo isset( $_GET['per_page'] ) && $_GET['per_page'] == '20' ? 'selected' : '';?>>20</option>
                            <option value="50" <?php echo isset( $_GET['per_page'] ) && $_GET['per_page'] == '50' ? 'selected' : '';?>>50</option>
                            <option value="70" <?php echo isset( $_GET['per_page'] ) && $_GET['per_page'] == '70' ? 'selected' : '';?>>70</option>
                            <option value="100" <?php echo isset( $_GET['per_page'] ) && $_GET['per_page'] == '100' ? 'selected' : '';?>>100</option>
                          </select>
                      </span> 
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="row">
                    <div class="col-sm-12">
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
                            $longitude	   = get_user_meta( $user->ID, 'longitude', true);
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
							$directories_array['longitude']	 = $longitude;
							$directories_array['fax']		 = $user->fax;
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
    
                            ?>
                            <div class="doc-featurelist" class="user-<?php echo intval( $user->ID );?>">
                                <figure class="doc-featureimg"> 
                                    <?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
                                        <?php docdirect_get_featured_tag(true,'v2');?>
                                    <?php }?>
                                    <?php docdirect_get_verified_tag(true,$user->ID,'','v2');?>
                                    <a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar">
                                    	<img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>">
                                    </a>
                                    <?php do_action('docdirect_display_provider_category',$user->ID);?>
                                </figure>
                                <div class="doc-featurecontent">
                                  <div class="doc-featurehead"> 
                                    <?php docdirect_get_wishlist_button($user->ID,true,'v2');?>
                                    <h2><a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar"><?php echo ( $get_username );?></a></h2>
                                    <?php if( !empty( $user->tagline ) ) {?>
                                        <span><?php echo esc_attr( $user->tagline );?></span>
                                    <?php }?>
                                    <ul class="doc-matadata">
                                      
                                      <li><?php docdirect_get_likes_button($user->ID);?></li>
                                      <?php
                                         if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){
                                            docdirect_get_rating_stars_v2($review_data,'echo');
                                         }
                                        ?>
                                    </ul>
                                  </div>
                                  <?php if( !empty( $directories_array['description'] ) ){?>
                                      <div class="doc-description">
                                        <p><?php echo substr($directories_array['description'], 0, 147);?></p>
                                      </div>
                                  <?php }?>
                                  <ul class="doc-addressinfo">
                                    <?php if( !empty( $directories_array['address'] ) ) {?>
                                    <li> <i class="fa fa-map-marker"></i>
                                      <address><?php echo esc_attr( $directories_array['address'] );?></address>
                                    </li>
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

                                    <?php 
									if( !empty( $user->latitude ) && !empty( $user->longitude ) ){	
										if( !empty( $_GET['geo_location'] ) ) {
											$args = array(
												'timeout'     => 15,
												'headers' => array('Accept-Encoding' => ''),
												'sslverify' => false
											);
											
											$address	 = !empty($_GET['geo_location']) ? $_GET['geo_location'] : '';
											$prepAddr	 = str_replace(' ','+',$address);
								
											$url	 = 'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
											$response   = wp_remote_get( $url, $args );
											$geocode	= wp_remote_retrieve_body($response);
											$output	  = json_decode($geocode);
											
											if( isset( $output->results ) && !empty( $output->results ) ) {
												$Latitude	= $output->results[0]->geometry->location->lat;
												$Longitude  = $output->results[0]->geometry->location->lng;
												$distance	= docdirectGetDistanceBetweenPoints($Latitude,$Longitude,$user->latitude,$user->longitude,'Km');
											}
										}
										?>
										<?php if( !empty( $distance ) ) {?>
											<li class="dynamic-locations"><i class='fa fa-globe'></i><span><?php esc_html_e('within','docdirect');?>&nbsp;<?php echo esc_attr($distance);?></span></li>
										<?php } else{?>
											<li class="dynamic-location-<?php echo intval($user->ID);?>"></li>
											<?php  
													wp_add_inline_script( 'docdirect_functions', 'if ( window.navigator.geolocation ) {
														window.navigator.geolocation.getCurrentPosition(
															function(pos) {
																jQuery.cookie("geo_location", pos.coords.latitude+"|"+pos.coords.longitude, { expires : 365 });
																var with_in	= _get_distance(pos.coords.latitude, pos.coords.longitude, '.esc_js($user->latitude).','. esc_js($user->longitude).',"K");
																jQuery(".dynamic-location-'.intval($user->ID).'").html("<i class=\'fa fa-globe\'></i><span>"+scripts_vars.with_in+_get_round(with_in, 2)+scripts_vars.kilometer+"</i></span>");
																
															}
														);
													}
												' );
												}
											?>
									<?php }?>
                                  </ul>
                                </div>
                            </div>
                        <?php 
                        }
                     } else{?>
                        <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Result Found.','docdirect'));?>
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
                </div>
                <?php 
                //Pagination
                if( $total_users > $limit ) {?>
                    <?php docdirect_prepare_pagination($total_users,$limit);?>
                <?php }?>
              </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 pull-left">
              <aside id="doc-sidebar" class="doc-sidebar">
                <?php do_action( 'docdirect_search_filters' );?>
                <?php if (is_active_sidebar('search-page-sidebar')) {?>
                  <div class="tg-doctors-list tg-haslayout">
                    <?php dynamic_sidebar('search-page-sidebar'); ?>
                  </div>
                <?php }?>
              </aside>
            </div>
          </div>
      </form>
    </div>
</div>
	
<?php
get_footer();

