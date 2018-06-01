<?php
/**
 *
 * Author Sidebar Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $wp_query, $current_user;
/**
 * Get User Queried Object Data
 */
$author_profile = $wp_query->get_queried_object();
$directory_type	= $author_profile->directory_type;
$privacy		= docdirect_get_privacy_settings($author_profile->ID); //Privacy settings

$facebook	  	= isset( $author_profile->facebook ) ? $author_profile->facebook : '';
$twitter	   	= isset( $author_profile->twitter ) ? $author_profile->twitter : '';
$linkedin	  	= isset( $author_profile->linkedin ) ? $author_profile->linkedin : '';
$pinterest	 	= isset( $author_profile->pinterest ) ? $author_profile->pinterest : '';
$google_plus   	= isset( $author_profile->google_plus ) ? $author_profile->google_plus : '';
$instagram	 	= isset( $author_profile->instagram ) ? $author_profile->instagram : '';
$tumblr	    	= isset( $author_profile->tumblr ) ? $author_profile->tumblr : '';
$skype	  	 	= isset( $author_profile->skype ) ? $author_profile->skype : '';

$avatar = apply_filters(
				'docdirect_get_user_avatar_filter',
				 docdirect_get_user_avatar(array('width'=>365,'height'=>365), $author_profile->ID),
				 array('width'=>365,'height'=>365) //size width,height
			);
?>

<div class="tg-usercontactinfo">
  <h3><?php esc_html_e('Contact Details','docdirect');?></h3>
  <ul class="tg-doccontactinfo">
	<?php if( !empty( $author_profile->user_address ) ) {?>
		<li> <i class="fa fa-map-marker"></i> <address><?php echo esc_attr( $author_profile->user_address );?></address> </li>
	<?php }?>
	<?php if( !empty( $author_profile->user_email ) 
			  &&
			  !empty( $privacy['email'] )
			  && 
			  $privacy['email'] == 'on'
	) {?>
		<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $author_profile->user_email );?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $author_profile->user_email );?></a></li>
	<?php }?>
	<?php if( !empty( $author_profile->phone_number ) 
			  &&
			  !empty( $privacy['phone'] )
			  && 
			  $privacy['phone'] == 'on'
	) {?>
		<li> <i class="fa fa-phone"></i> <span><?php echo esc_attr( $author_profile->phone_number );?></span> </li>
	<?php }?>
	<?php if( !empty( $author_profile->fax ) ) {?>
		<li><i class="fa fa-fax"></i> <span><?php echo esc_attr( $author_profile->fax );?></span> </li>
	<?php }?>
	<?php if( !empty( $author_profile->skype ) ) {?> 
		<li><i class="fa fa-skype"></i><span><?php echo esc_attr( $author_profile->skype );?></span></li>
	<?php }?>
	<?php if( !empty( $author_profile->user_url ) ) {?>
		<li><i class="fa fa-link"></i><a href="<?php echo esc_url( $author_profile->user_url );?>" target="_blank"><?php echo docdirect_parse_url( $author_profile->user_url);?></a></li>
	<?php }?>
  </ul>
  <?php 
	if(  !empty( $facebook ) 
		 || !empty( $facebook ) 
		 || !empty( $twitter ) 
		 || !empty( $linkedin ) 
		 || !empty( $pinterest ) 
		 || !empty( $google_plus ) 
		 || !empty( $instagram ) 
		 || !empty( $tumblr ) 
		 || !empty( $skype ) 
	){?>
	<ul class="tg-socialicon-v2">
		<?php if(  !empty( $facebook ) ) {?>
			<li class="tg-facebook"><a href="<?php echo esc_url($facebook);?>"><i class="fa fa-facebook-f"></i></a></li>
		<?php }?>
		<?php if(  !empty( $twitter ) ) {?>
		<li class="tg-twitter"><a href="<?php echo esc_url($twitter);?>"><i class="fa fa-twitter"></i></a></li>
		<?php }?>
		<?php if(  !empty( $linkedin ) ) {?>
		<li class="tg-linkedin"><a href="<?php echo esc_url($linkedin);?>"><i class="fa fa-linkedin"></i></a></li>
		<?php }?>
		<?php if(  !empty( $pinterest ) ) {?>
		<li class="tg-pinterest"><a href="<?php echo esc_url($pinterest);?>"><i class="fa fa-pinterest-p"></i></a></li>
		<?php }?>
		<?php if(  !empty( $google_plus ) ) {?>
		<li class="tg-googleplus"><a href="<?php echo esc_url($google_plus);?>"><i class="fa fa-google-plus"></i></a></li>
		<?php }?>
		<?php if(  !empty( $instagram ) ) {?>
		<li class="tg-instagram"><a href="<?php echo esc_url($instagram);?>"><i class="fa fa-instagram"></i></a></li>
		<?php }?>
		<?php if(  !empty( $tumblr ) ) {?>
		<li class="tg-tumblr"><a href="<?php echo esc_url($tumblr);?>"><i class="fa fa-tumblr"></i></a></li>
		<?php }?>
		<?php if(  !empty( $skype ) ) {?>
		<li class="tg-skype"><a href="skype:<?php echo esc_attr($skype);?>?call"><i class="fa fa-skype"></i></a></li>
		<?php }?>
	</ul>
	<?php }?>
	<?php if( !empty( $author_profile->user_address ) ){?>
		<a class="tg-btn tg-btn-lg" href="http://maps.google.com/maps?saddr=&amp;daddr=<?php echo esc_attr( $author_profile->user_address );?>" target="_blank"><?php esc_html_e('get directions','docdirect');?></a>
	<?php }?>
	<?php get_template_part('directory/provider-page/template-author-sidebar', 'business-hours'); ?>
	<?php docdirect_prepare_profile_social_sharing($avatar,$author_profile->ID,$author_profile->description);?>
	<?php get_template_part('directory/provider-page/template-author-sidebar', 'contact-form'); ?>
</div>

	