<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$today = time();
$users_type	= $atts['user_type'];
$show_users	= !empty( $atts['show_users'] ) ? $atts['show_users'] : 10;
$order		 = !empty( $atts['order'] ) ? $atts['order'] : 'DESC';
$uniq_flag = fw_unique_increment();
$is_verify	= 'on';
$query_args	= array(
					'role'  => 'professional',
					'order' => $order,
					'number' => $show_users 
				 );

if( isset( $users_type ) && !empty( $users_type ) && $users_type !='all' ) {
	$meta_query_args[] = array(
						'key'     => 'directory_type',
						'value'   => (int)$users_type,
						'compare' => '='
					);
}


//Verify user
$meta_query_args[] = array(
						'key'     => 'verify_user',
						'value'   => (string)$is_verify,
						'compare' => '='
					);
$meta_query_args[] = array(
						'key'     => 'user_featured',
						'value'   => $today,
						'type' => 'numeric',
						'compare' => '>'
					);

if( !empty( $meta_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$meta_query_args	= array_merge( $query_relation,$meta_query_args );
	$query_args['meta_query'] = $meta_query_args;
}
																					
$query_args['meta_key']	   = 'user_featured';
$query_args['orderby']	   = 'meta_value';	
$user_query  = new WP_User_Query($query_args);	

$flag	= rand(1,9999);	
?>
<div class="sc-featured-users-v2">
    <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-12">
      <div class="doc-section-head">
        <?php if ( !empty($atts['heading']) && !empty($atts['heading']) ) { ?>
        <div class="doc-section-heading">
          <?php if (!empty($atts['heading'])) { ?>
                <h2><?php echo esc_attr($atts['heading']); ?></h2>
          <?php } ?>
          <?php if (!empty($atts['sub_heading'])) { ?>
                <span><?php echo esc_attr($atts['sub_heading']); ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if (!empty($atts['description'])) { ?>
            <div class="doc-description">
                <p><?php echo esc_attr($atts['description']); ?></p>
            </div>
        <?php } ?>
      </div>
    </div>
    <?php if ( ! empty( $user_query->results ) ) {?>
	<div class="doc-featurelisting">
	  <div id="doc-featureslider-<?php echo esc_attr( $flag );?>" class="doc-featureslider owl-carousel">
		<?php
			if ( ! empty( $user_query->results ) ) {
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
						
					$privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settin
					
					if( !empty( $latitude ) && !empty( $longitude ) ) {
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
					?>
				  <div class="doc-featurelist item">
					<figure class="doc-featureimg"> 
						<?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
							<?php docdirect_get_featured_tag(true,'v2');?>
						<?php }?>
						<?php docdirect_get_verified_tag(true,$user->ID,'','v2');?>
						<a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>"></a>
						<?php docdirect_get_verified_tag(true,$user->ID);?>
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
				 <?php }
				 }
			}
		 ?>
	  </div>
		<script>
			jQuery(document).ready(function(e) {
				jQuery("#doc-featureslider-<?php echo esc_js( $flag );?>").owlCarousel({
					items:3,
					rtl: <?php docdirect_owl_rtl_check();?>,
					nav: false,
					dots: true,
					autoplay: true,
      	  			rewind:true,
					navText : ['<i class="doc-btnprev icon-arrows-1"></i>','<i class="doc-btnnext icon-arrows"></i>'],
					responsive:{
						0:{items:1},
						481:{items:2},
						991:{items:2},
						1200:{items:3},
						1280:{items:4},
					}
				});
			});
		</script>
	</div>
	<?php }?>
</div>
