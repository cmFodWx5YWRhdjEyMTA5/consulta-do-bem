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
$delete_account_text 	  = fw_get_db_settings_option('delete_account_text');
$privacy				  = docdirect_get_privacy_settings($url_identity);
?>
<div class="tg-myaccount tg-haslayout privacy-settings">
    <div class="tg-heading-border tg-small">
        <h2><?php esc_html_e('Privacy Settings','docdirect');?></h2>
    </div>
    <div class="privacy-wraper">
    <form action="#" method="post" class="tg-form-privacy">
        <?php if( apply_filters('docdirect_is_setting_enabled',$url_identity,'appointments' ) === true ){?>
        <div class="form-group">  
            <div class="tg-privacy"> 
              <div class="tg-iosstylcheckbox">
                <input type="hidden" name="privacy[appointments]">
                <input type="checkbox" class="privacy-switch" <?php echo isset( $privacy['appointments'] ) && $privacy['appointments'] === 'on' ? 'checked':'';?>  name="privacy[appointments]" id="tg-appointment">
                <label for="tg-appointment" class="checkbox-label" data-private="<?php esc_html_e('Private','docdirect');?>" data-public="<?php esc_html_e('Public','docdirect');?>"></label>
              </div>
              <span class="tg-privacy-name"><?php esc_html_e('Bookings','docdirect');?></span>
              <p><?php esc_attr_e('Make the appointment/bookings form visible on my public profile','docdirect');?></p>
            </div>
        </div>
        <?php }?>
        <div class="form-group">  
            <div class="tg-privacy"> 
              <div class="tg-iosstylcheckbox">
                <input type="hidden" name="privacy[phone]">
                <input type="checkbox" class="privacy-switch" <?php echo isset( $privacy['phone'] ) && $privacy['phone'] === 'on' ? 'checked':'';?>  name="privacy[phone]" id="tg-phone">
                <label for="tg-phone" class="checkbox-label" data-private="<?php esc_attr_e('Private','docdirect');?>" data-public="<?php esc_attr_e('Public','docdirect');?>"></label>
              </div>
              <span class="tg-privacy-name"><?php esc_html_e('Phone Number','docdirect');?></span>
              <p><?php esc_attr_e('Make the phone visible on my public profile','docdirect');?></p>
            </div>
        </div>
        <div class="form-group">  
            <div class="tg-privacy"> 
              <div class="tg-iosstylcheckbox">
                <input type="hidden" name="privacy[email]">
                <input type="checkbox" class="privacy-switch" <?php echo isset( $privacy['email'] ) && $privacy['email'] === 'on' ? 'checked':'';?>  name="privacy[email]" id="tg-email">
                <label for="tg-email" class="checkbox-label" data-private="<?php esc_attr_e('Private','docdirect');?>" data-public="<?php esc_attr_e('Public','docdirect');?>"></label>
              </div>
              <span class="tg-privacy-name"><?php esc_html_e('Email','docdirect');?></span>
              <p><?php esc_attr_e('Make the email address visible on my public profile','docdirect');?></p>
            </div>
        </div>
        <div class="form-group">  
            <div class="tg-privacy"> 
              <div class="tg-iosstylcheckbox">
                <input type="hidden" name="privacy[contact_form]">
                <input type="checkbox" class="privacy-switch" <?php echo isset( $privacy['contact_form'] ) && $privacy['contact_form'] === 'on' ? 'checked':'';?>  name="privacy[contact_form]" id="tg-contact_form">
                <label for="tg-contact_form" class="checkbox-label" data-private="<?php esc_attr_e('Private','docdirect');?>" data-public="<?php esc_attr_e('Public','docdirect');?>"></label>
              </div>
              <span class="tg-privacy-name"><?php esc_html_e('Contact Form','docdirect');?></span>
              <p><?php esc_attr_e('Make the contact form visible on my public profile','docdirect');?></p>
            </div>
        </div>
        <?php if( apply_filters('docdirect_is_setting_enabled',$url_identity,'schedules' ) === true ){?>
			<div class="form-group">  
				<div class="tg-privacy"> 
				  <div class="tg-iosstylcheckbox">
					<input type="hidden" name="privacy[opening_hours]">
					<input type="checkbox" class="privacy-switch" <?php echo isset( $privacy['opening_hours'] ) && $privacy['opening_hours'] === 'on' ? 'checked':'';?>  name="privacy[opening_hours]" id="tg-opening_hours">
					<label for="tg-opening_hours" class="checkbox-label" data-private="<?php esc_attr_e('Private','docdirect');?>" data-public="<?php esc_attr_e('Public','docdirect');?>"></label>
				  </div>
				  <span class="tg-privacy-name"><?php esc_html_e('Opening Hours','docdirect');?></span>
				  <p><?php esc_attr_e('Show or hide opening hours.','docdirect');?></p>
				</div>
			</div>
        <?php }?>
   </form>
</div>