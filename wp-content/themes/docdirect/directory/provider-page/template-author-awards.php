<?php
/**
 *
 * Author awards Template.
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
$directory_type	= $author_profile->directory_type;

if(function_exists('fw_get_db_settings_option')) {
	$awards_switch    = fw_get_db_post_option($directory_type, 'awards', true);
}
 
if( isset( $awards_switch ) 
	  && $awards_switch === 'enable' 
	  && !empty( $author_profile->awards )
  ){?>
  <div class="tg-userexperience tg-honourawards">
	<div class="tg-userheading">
	  <h2><i class="fa fa-trophy"></i><?php esc_html_e('Honours & Awards','docdirect');?></h2>
	</div>
	<ul>
		<?php 
		if( !empty( $author_profile->awards ) ) {
			foreach( $author_profile->awards as $key => $value ){
				$period	= '';
				if( !empty( $value['date'] ) ){
					if( !empty( $value['date'] ) ){
						$period	= '('.date_i18n(get_option('date_format'),strtotime( $value['date'])).')';
					}
				}
			?>
			<li>
				<div class="tg-dotstyletitle">
				  <h3><?php echo esc_attr( $value['name'] );?>&nbsp;&nbsp;<?php echo esc_attr( $period );?></h3>
				</div> 
				<div class="tg-description">
				  <p><?php echo esc_attr( $value['description'] );?></p>
				</div>
			</li>
		   <?php }
		  } else{?>
			<li><p><?php esc_html_e('No awards added yet.','docdirect');?></p></li>
		  <?php }?>
	</ul>
  </div>
<?php }