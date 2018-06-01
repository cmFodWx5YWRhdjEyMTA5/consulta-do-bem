<?php
/**
 *
 * Author Sidebar Profile avatar Template.
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

$avatar = apply_filters(
				'docdirect_get_user_avatar_filter',
				 docdirect_get_user_avatar(array('width'=>365,'height'=>365), $author_profile->ID),
				 array('width'=>365,'height'=>365) //size width,height
			);

$current_date 	  	= date('Y-m-d H:i:s');
$current_string		= strtotime( $current_date );
$featured_string   	= $author_profile->user_featured;
?>
<figure class="tg-userimg detail-avatar"> 
	<a href="<?php echo esc_url(get_author_posts_url($author_profile->ID));?>"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $author_profile->first_name.' '.$author_profile->last_name );?>"></a>
	<?php do_action('docdirect_display_provider_category',$author_profile->ID);?>
  <figcaption>
	<ul class="tg-featureverified">
	  <?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
			<li class="tg-featuresicon"><a href="javascript:;"><i class="fa fa-bolt"></i><span><?php esc_html_e('featured','docdirect');?></span></a></li>
	  <?php }?>
	  <?php docdirect_get_verified_tag(true,$author_profile->ID,'simple');?>
	  
	</ul>
  </figcaption>
</figure>