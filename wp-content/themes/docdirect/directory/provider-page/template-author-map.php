<?php
/**
 *
 * Author Header Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $current_user;
$author_profile = $wp_query->get_queried_object();
$username 		= docdirect_get_username($author_profile->ID);
$directory_type	= $author_profile->directory_type;

if(function_exists('fw_get_db_settings_option')) {
	$dir_map_marker    = fw_get_db_post_option($directory_type, 'dir_map_marker', true);
	$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
}

if( !empty( $dir_map_marker['url'] ) ){
	$dir_map_marker  = $dir_map_marker['url'];
} else{
	if( !empty( $dir_map_marker_default['url'] ) ){
		$dir_map_marker	 = $dir_map_marker_default['url'];
	} else{
		$dir_map_marker	 	   = get_template_directory_uri().'/images/map-marker.png';
	}
}

$review_data	= docdirect_get_everage_rating ( $author_profile->ID );

if( !empty( $author_profile->latitude ) && !empty( $author_profile->longitude ) ) {?>
<div class="tg-section-map">
	<div id="map_canvas" class="tg-location-map tg-haslayout"></div>
	<?php do_action('docdirect_map_controls');?>
	<?php
	$directories		= array();
	$directories_array	= array();
	
	$directories['status']					= 'found';
	$directories_array['latitude']			= $author_profile->latitude;
	$directories_array['longitude']			= $author_profile->longitude;
	$directories_array['title']				= $author_profile->display_name;
	$directories_array['name']	 			= $author_profile->first_name.' '.$author_profile->last_name;
	$directories_array['email']	 		 	= $author_profile->user_email;
	$directories_array['phone_number']	 	= $author_profile->phone_number;
	$directories_array['address']	 		= $author_profile->user_address;
	$directories_array['group']				= '';
	$directories_array['icon']	 	   		= $dir_map_marker;
	$avatar = apply_filters(
								'docdirect_get_user_avatar_filter',
								 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $author_profile->ID),
								 array('width'=>150,'height'=>150) //size width,height
							);

	$infoBox	= '<div class="tg-mapmarker">';
	$infoBox	.= '<figure><img width="60" heigt="60" src="'.esc_url( $avatar ).'" alt="'.esc_attr__('User','docdirect').'"></figure>';
	$infoBox	.= '<div class="tg-mapmarkercontent">';
	$infoBox	.= '<h3><a href="'.get_author_posts_url($author_profile->ID).'">'.$directories_array['name'].'</a></h3>';

	if( !empty( $author_profile->tagline ) ) {
		$infoBox	.= '<span>'.$author_profile->tagline.'</span>';
	}


	$infoBox	.= '<ul class="tg-likestars">';

	if( isset( $reviews_switch ) && $reviews_switch === 'enable' && !empty( $review_data )){
		$infoBox	.= '<li>'.docdirect_get_rating_stars($review_data,'return','hide').'</li>';
	}
	$infoBox	.= '<li>'.docdirect_get_wishlist_button($author_profile->ID,false).'</li>';
	$infoBox	.= '<li>'.docdirect_get_user_views($author_profile->ID).'&nbsp;'.esc_html__('view(s)','docdirect').'</li>';

	$infoBox	.= '</ul>';
	$infoBox	.= '</div>';

	$directories_array['html']['content']	= $infoBox;
	$directories['users_list'][]	= $directories_array;
	?>
	<script>
		jQuery(document).ready(function() {
			docdirect_init_detail_map_script(<?php echo json_encode( $directories );?>);
		});	
	</script>
</div> 
<?php }?>