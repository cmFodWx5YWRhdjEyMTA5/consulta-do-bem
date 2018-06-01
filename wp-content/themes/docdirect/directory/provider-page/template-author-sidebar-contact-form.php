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
$privacy		= docdirect_get_privacy_settings($author_profile->ID); //Privacy settings

if( !empty( $privacy['contact_form'] )
  && 
	$privacy['contact_form'] == 'on'
) {
   ?>
  <div class="tg-usercontatnow">
	<h3><?php esc_html_e('contact now','docdirect');?></h3>
	<div class="tg-widgetcontent doc-contact">
		<form class="contact_form tg-usercontactform">
			<fieldset>
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="form-group">
							<input type="text" name="username" placeholder="<?php esc_attr_e('Name','docdirect');?>" class="form-control">
						</div>
					</div>
					<div class="col-sm-12 col-xs-12">
						<div class="form-group">
							<input type="email" name="useremail" placeholder="<?php esc_attr_e('Email','docdirect');?>" class="form-control">
						</div>
					</div>
					<div class="col-sm-12 col-xs-12">
						<div class="form-group">
							<input type="text" name="userphone" placeholder="<?php esc_attr_e('Number','docdirect');?>" class="form-control">
						</div>
					</div>
					<div class="col-sm-12 col-xs-12">
						<div class="form-group">
							<input type="text" name="usersubject" placeholder="<?php esc_attr_e('Subject','docdirect');?>" class="form-control">
						</div>
					</div>
					<div class="col-sm-12 col-xs-12">
						<div class="form-group">
							<textarea name="user_description" placeholder="<?php esc_attr_e('Message','docdirect');?>" class="form-control"></textarea>
						</div>
					</div>	
					<div class="col-sm-12 col-xs-12">
						<input type="hidden" name="email_to" value="<?php echo esc_attr( $author_profile->user_email );?>" class="form-control">
						<button class="tg-btn contact_me" type="submit"><?php esc_html_e('Send','docdirect');?></button>
						<?php wp_nonce_field('docdirect_contact_me', 'user_security'); ?>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
  </div>
<?php }?>

