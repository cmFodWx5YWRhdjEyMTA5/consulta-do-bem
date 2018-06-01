<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$uniq_flag = fw_unique_increment();
$button_title	= isset( $atts['button_title'] ) && empty( $atts['button_title'] ) ? $atts['button_title'] : esc_html__('Send','docdirect');
?>
<div class="sc-contact-form">
	<div class="tg-refinesearcharea contact_wrap">
		<?php if (isset($atts['heading']) && !empty($atts['heading'])) { ?>
		<div class="tg-heading-border tg-small">
			<h2><?php echo esc_attr($atts['heading']); ?></h2>
		</div>
		<?php } ?>	
		<form class="form-refinesearch tg-haslayout contact_form">
			<div class="message_contact theme-notification"></div>
			<fieldset>
				<div class="row form-data" data-success="<?php echo esc_attr($atts['success']);?>" data-error="<?php echo esc_attr($atts['error']);?>" data-email="<?php echo esc_attr($atts['email_to']);?>">
					<?php wp_nonce_field('docdirect_submit_contact', 'security'); ?>
					<div class="col-sm-6">
						<div class="form-group">
							<input type="text" class="form-control" name="subject" placeholder="<?php esc_attr_e('Subject','docdirect');?>">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<input type="text" class="form-control" name="username" placeholder="<?php esc_attr_e('Name','docdirect');?>">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<input type="email" class="form-control" name="useremail" placeholder="<?php esc_attr_e('Email','docdirect');?>">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<input type="text" class="form-control" name="phone" placeholder="<?php esc_attr_e('Phone','docdirect');?>">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<textarea class="form-control" name="description" placeholder="<?php esc_attr_e('Message','docdirect');?>"></textarea>
						</div>
					</div>
					<div class="col-sm-6">
						<button type="submit" class="tg-btn contact_now"><?php echo esc_attr($button_title);?></button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>				
</div>

