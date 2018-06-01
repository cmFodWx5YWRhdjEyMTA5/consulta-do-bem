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
$professional_statements	  	 = isset( $author_profile->professional_statements ) ? $author_profile->professional_statements : '';
?>
<div class="tg-aboutuser">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('About','docdirect');?>&nbsp;<?php echo esc_attr( $username );?></h2>
	</div>
	<?php if( !empty( $author_profile->description ) ) {?>
	  <div class="tg-description">
		<p><?php echo wpautop( nl2br( $author_profile->description ) );?></p>
	  </div>
	<?php }?>
	<?php if( !empty( $professional_statements ) ){?>
		<div class="professional-statements">
			<?php echo do_shortcode( nl2br( $professional_statements));?>
		</div>
	<?php }?>
</div>