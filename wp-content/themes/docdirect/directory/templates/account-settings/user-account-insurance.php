<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);

if (function_exists('fw_get_db_settings_option')) {
	$insurance_switch  	  = fw_get_db_post_option( $db_directory_type, 'insurance', true );
}

$db_insurance		  = get_user_meta( $user_identity, 'insurance', true);


if( isset( $insurance_switch ) && $insurance_switch === 'enable' ) {
  if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){
	if( apply_filters('docdirect_is_setting_enabled',$user_identity,'insurance' ) === true ){?>
	<div class="tg-bordertop tg-haslayout">
		<div class="tg-formsection">
			<div class="tg-heading-border tg-small">
				<h3><?php esc_html_e('Insurance Plans','docdirect');?></h3>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<select name="insurance[]" class="chosen-select" multiple>
							<option value=""><?php esc_attr_e('Select insurance','docdirect');?></option>
							<?php docdirect_get_term_options($db_insurance,'insurance');?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }}}?>