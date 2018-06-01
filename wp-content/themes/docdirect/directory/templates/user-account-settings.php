<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map
$profile_settings	= docdirect_profile_settings();
$profile_settings	= apply_filters('docdirect_filter_profile_settings',$profile_settings);
?>
<form class="tg-formeditprofile tg-haslayout do-account-setitngs">
    <?php 
		foreach( $profile_settings as $key => $value  ){
			get_template_part('directory/templates/account-settings/user-account', $key);
		}
	?>
    <?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
        <input type="hidden" class="txt-professional" value="txt-professional">
    <?php }else{?>
        <input type="hidden" class="txt-visitor" value="txt-visitor">
    <?php }?>
    <button type="submit" class="tg-btn process-account-settings"><?php esc_attr_e('update','docdirect');?></button>
</form>