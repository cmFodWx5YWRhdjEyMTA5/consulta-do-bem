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

$directory_type		= $author_profile->directory_type;
$featured_string    = $author_profile->user_featured;
$username 		= docdirect_get_username($author_profile->ID);
$review_data	= docdirect_get_everage_rating ( $author_profile->ID );
$banner			= docdirect_get_user_banner(array('width'=>1920,'height'=>450), $author_profile->ID);
$privacy		= docdirect_get_privacy_settings($author_profile->ID); //Privacy settings
if(function_exists('fw_get_db_settings_option')) {
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
}

$banner_parallax	= '';
if( !empty( $banner ) ){
	$banner_parallax	= 'data-appear-top-offset="600" data-parallax="scroll" data-image-src="'.$banner.'"';
}

?>
<div class="tg-userbanner-content">
	<h1><?php echo esc_attr( $username );?></h1>
	<?php if( !empty( $author_profile->tagline ) ) {?>
		<span><?php echo esc_attr( $author_profile->tagline );?></span>
	<?php }?>
	<ul class="tg-likestars">
		<?php if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
			<li><?php docdirect_get_rating_stars($review_data,'echo');?></li>
		<?php }?>
		<li><?php docdirect_get_wishlist_button($author_profile->ID,true);?></li>
		<li><span><?php echo intval( docdirect_get_user_views($author_profile->ID) );?>&nbsp;<?php esc_html_e('view(s)','docdirect');?></span></li> 
	</ul>
	<?php 
	if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'appointments' ) === true ){ 
		 if( !empty( $privacy['appointments'] )
			  && 
			  $privacy['appointments'] == 'on'
		 ) {
			 if( isset( $current_user->ID ) 
				 && 
					$current_user->ID != $author_profile->ID
				 &&
					is_user_logged_in()
			 ){
			?>
				<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-appointmentpopup"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
			<?php 
			}  else if( $current_user->ID != $author_profile->ID ){?>
				<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
	<?php }}}?>
</div>