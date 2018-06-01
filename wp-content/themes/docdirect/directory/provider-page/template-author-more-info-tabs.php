<?php
/**
 *
 * Author Tabs Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
$author_profile = $wp_query->get_queried_object();

$directory_type	= $author_profile->directory_type;
if(function_exists('fw_get_db_settings_option')) {
	$questions    = fw_get_db_post_option($directory_type, 'qa', true);
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
}

if( ( isset( $questions )  && $questions === 'enable' ) || ( isset( $reviews_switch ) && $reviews_switch === 'enable' ) ){?>
  <div class="detail-panel-wrap tg-haslayout tg-companyfeaturebox">
   <div class="tg-companyfeaturebox tg-reviews">
		<ul class="tg-reviewstabs" role="tablist">
			<li role="presentation" class="active"><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab"><?php esc_html_e('Reviews', 'docdirect'); ?></a></li>
			<?php  
				if( isset( $questions )  && $questions === 'enable' ){
					if (function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
						if( apply_filters('docdirect_get_theme_settings', 'qa_restriction') === 'paid' ){
						   if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'dd_qa' ) === true ){?>
							<li role="presentation"><a href="#consult" aria-controls="consult" role="tab" data-toggle="tab"><?php esc_html_e('Consult Q&A', 'docdirect'); ?></a></li>
						   <?php }?>
						<?php } else{?>
							<li role="presentation"><a href="#consult" aria-controls="consult" role="tab" data-toggle="tab"><?php esc_html_e('Consult Q&A', 'docdirect'); ?></a></li>
					  <?php }
					}
				}
			?>
		</ul>
		<div class="tg-tabcontent tab-content">
			<?php 
				if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){
					get_template_part('directory/provider-page/template-author', 'reviews');
				}
			?>
			<?php  
				if( isset( $questions )  && $questions === 'enable' ){
					if (function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
						if( apply_filters('docdirect_get_theme_settings', 'qa_restriction') === 'paid' ){
						   if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'dd_qa' ) === true ){?>
							<div role="tabpanel" class="tg-tabpane tab-pane" id="consult">
								<?php do_action('render_questions_listing_view'); ?>
							</div>
						   <?php }?>
						<?php } else{?>
							<div role="tabpanel" class="tg-tabpane tab-pane" id="consult">
								<?php do_action('render_questions_listing_view'); ?>
							</div>
					  <?php }
					}
				}
			?>
		</div>
	</div>
  </div>
<?php }?>