<?php
/**
 *
 * Author Prices Template.
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
	$price_switch    = fw_get_db_post_option($directory_type, 'price_list', true);
}

if( isset( $price_switch ) 
  && $price_switch === 'enable' 
  && !empty( $author_profile->prices_list )
){?>
<div class="prices-list-wrap">
	<div class="tg-companyfeaturebox tg-services">
	  <div class="tg-userheading">
		<h2><i class="fa fa-money" aria-hidden="true"></i><?php esc_html_e('Prices/Services List','docdirect');?></h2>
	  </div>
	  <div id="tg-accordion" class="tg-accordion">
		<?php 
		foreach( $author_profile->prices_list as $key => $value ){
			if( !empty( $value['title'] ) ){
			?>
			<div class="tg-service tg-panel">
			  <div class="tg-accordionheading">
				<h4><span><?php echo force_balance_tags( $value['title'] );?></span><span><?php echo esc_attr( $value['price'] );?></span></h4>
			  </div>
			  <div class="tg-panelcontent" style="display: none;">
				<div class="tg-description">
				  <p><?php echo force_balance_tags( $value['description'] );?></p>
				</div>
			  </div>
			</div>
		<?php }}?>
	  </div>
	</div>
</div>	
<?php }