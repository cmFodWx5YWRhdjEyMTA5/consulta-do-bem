<?php
/**
 * User Security Settings
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;
if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}
$delete_account_text 		= fw_get_db_settings_option('delete_account_text');
$profile_status = get_user_meta($url_identity , 'profile_status' , true);
?>
<div class="tg-myaccount tg-haslayout">
	<div class="tg-refinesearcharea">
		<div class="tg-heading-border tg-small">
			<h2><?php esc_html_e('change password','docdirect');?></h2>
		</div>
		<form class="form-resetpassword forgot-password-form tg-haslayout">
			<fieldset>
				<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="form-group">
							<input type="password" name="old_passowrd" placeholder="<?php esc_attr_e('Old password','docdirect');?>">
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="form-group">
							<input type="password" name="new_passowrd" placeholder="<?php esc_attr_e('New password','docdirect');?>">
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="form-group">
							<input type="password" name="confirm_password" placeholder="<?php esc_attr_e('Retype password','docdirect');?>">
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<button type="submit" class="tg-btn do-change-password"><?php esc_attr_e('Update','docdirect');?></button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="tg-bordertop tg-haslayout">
		<div class="tg-deleteaccount tg-haslayout">
			<div class="tg-heading-border tg-small">
				<h2><?php esc_html_e('Delete/De-Activate Account','docdirect');?></h2>
			</div>
			<?php if( !empty( $delete_account_text ) ) {?>
				<div class="tg-description">
					<p><?php echo esc_attr( $delete_account_text );?></p>
				</div>
			<?php }?>
			<form class="form-resetpassword form-deleteaccount tg-haslayout">
				<fieldset>
					<div class="row">
						<?php if( isset( $profile_status ) && $profile_status == 'active' ) {?>
							<div class="col-sm-12">
								<textarea class="form-control"  name="message" placeholder="<?php esc_attr_e('Why leave us','docdirect');?>"></textarea>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="password" name="old_password" placeholder="<?php esc_attr_e('Old password','docdirect');?>">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="password" name="confirm_password" placeholder="<?php esc_attr_e('Retype password','docdirect');?>">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 tg-padding-15">
								<button type="submit" data-action="deleteme" class="tg-btn tg-btn-lg do-process-account"><?php esc_html_e('Delete now','docdirect');?></button>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 tg-padding-15">
								
								<button type="submit"  data-action="deactivateme" class="tg-btn tg-btn-lg do-process-account"><?php esc_html_e('Deactivate now','docdirect');?></button>
							</div>
						<?php } else{?>
							<div class="col-md-6 col-sm-6 col-xs-12 tg-padding-15">
								<?php wp_nonce_field('docdirect_deleteme_nounce', 'account-process'); ?>
								<button type="submit"  data-action="activateme" class="tg-btn tg-btn-lg do-process-account"><?php esc_html_e('Activate now','docdirect');?></button>
							</div>
						<?php }?>
						<?php wp_nonce_field('docdirect_deleteme_nounce', 'account-process'); ?>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>