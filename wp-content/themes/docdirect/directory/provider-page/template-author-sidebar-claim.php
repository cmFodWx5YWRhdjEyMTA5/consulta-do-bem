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
if(function_exists('fw_get_db_settings_option')) {
	$claims_switch    = fw_get_db_post_option($directory_type, 'claims', true);
}


if( !empty( $claims_switch )
	&&
	$claims_switch === 'enable'

){
	if( isset( $current_user->ID ) 
		&& $current_user->ID != $author_profile->ID
		&& is_user_logged_in()        
	){
	?>
	<div class="claim-box tg-widget tg-claimreport">
		<div class="tg-widgetcontent doc-claim">
			<h3><?php esc_html_e('Claim/Report This User','docdirect');?></h3>
			<form class="tg-haslayout claim_form tg-claimform">
				<fieldset>
					<div class="form-group">
						<input type="text" name="subject" placeholder="<?php esc_attr_e('Subject*','docdirect');?>" class="form-control">
					</div>
					<div class="form-group">
						<textarea name="report" placeholder="<?php esc_attr_e('Report Detail','docdirect');?>" class="form-control"></textarea>
					</div>
					<button class="tg-btn report_now" type="submit"><?php esc_html_e('report now','docdirect');?></button>
					<?php wp_nonce_field('docdirect_claim', 'security'); ?>
					<input type="hidden" name="user_to" class="user_to" value="<?php echo esc_attr( $author_profile->ID );?>" />
				</fieldset>
			</form>
		</div>
	</div>
	<?php } else if( $current_user->ID != $author_profile->ID ){?>
		<div class="claim-box">
			<a class="tg-btn tg-btn-lg"data-toggle="modal" data-target=".tg-user-modal" href="javascript:;">
				<i class="fa fa-exclamation-triangle"></i>
				<?php esc_html_e('Claim This User','docdirect');?>
			</a>
		</div>
<?php }
}
