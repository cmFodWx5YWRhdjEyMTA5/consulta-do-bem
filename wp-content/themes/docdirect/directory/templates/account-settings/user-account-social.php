<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
?>
<div class="tg-bordertop tg-haslayout">
	<div class="tg-formsection">
		<div class="tg-heading-border tg-small">
			<h3><?php esc_html_e('Social Settings','docdirect');?></h3>
		</div>
		<p><strong><?php esc_html_e('Note: Leave them empty to hide social icons at detail page.','docdirect');?></strong></p>
		<div class="row">
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[facebook]" value="<?php echo get_user_meta($user_identity,'facebook',true); ?>" type="text" placeholder="<?php esc_attr_e('Facebook','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[twitter]" value="<?php echo get_user_meta($user_identity,'twitter',true); ?>" type="text" placeholder="<?php esc_attr_e('Twitter','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[linkedin]" value="<?php echo get_user_meta($user_identity,'linkedin',true); ?>" type="text" placeholder="<?php esc_attr_e('Linkedin','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[pinterest]" value="<?php echo get_user_meta($user_identity,'pinterest',true); ?>" type="text" placeholder="<?php esc_attr_e('Pinterest','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[google_plus]" value="<?php echo get_user_meta($user_identity,'google_plus',true); ?>" type="text" placeholder="<?php esc_attr_e('Google Plus','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[instagram]" value="<?php echo get_user_meta($user_identity,'instagram',true); ?>" type="text" placeholder="<?php esc_attr_e('Instagram','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[tumblr]"  value="<?php echo get_user_meta($user_identity,'tumblr',true); ?>"type="text" placeholder="<?php esc_attr_e('Tumblr','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="socials[skype]"  value="<?php echo get_user_meta($user_identity,'skype',true); ?>"type="text" placeholder="<?php esc_attr_e('Skype','docdirect');?>">
				</div>
			</div>
		</div>
	</div>
</div>