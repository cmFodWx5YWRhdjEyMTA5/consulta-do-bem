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

if( !empty( $author_profile->languages ) ) {?>
  <div class="tg-honourawards tg-listview-v3 user-section-style">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('Languages','docdirect');?></h2>
	</div>
	<div class="tg-doctor-profile">
		  <ul class="tg-tags">
			<?php 
			if( !empty( $author_profile->languages ) ) {
				$languages	= docdirect_prepare_languages();
				$user_languages	 = array();
				foreach( $author_profile->languages as $key => $value ){
				?>
			<li><a href="javascript:;" class="tg-btn"><?php echo esc_attr( $languages[$key] );?></a></li>
			<?php }} else{?>
			 <li><a href="javascript:;" class="tg-btn"><?php esc_html_e( 'No Languages selected yet.','docdirect' );?></a></li>
			<?php }?>
		  </ul>
	  </div>
  </div>
<?php }