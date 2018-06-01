<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
if (function_exists('fw_get_db_settings_option')) {
	$dir_datasize  = fw_get_db_settings_option('dir_datasize');
} else{
	$dir_datasize = '5242880'; // 5 MB
}

$user_identity  = $current_user->ID;
$current_date   = date('Y-m-d H:i:s');
$avatar 		= apply_filters(
					'docdirect_get_user_avatar_filter',
					 docdirect_get_user_avatar(array('width'=>270,'height'=>270), $user_identity) ,
					 array('width'=>270,'height'=>270) //size width,height=
				);

$banner 		= apply_filters(
					'docdirect_get_user_avatar_filter',
					 docdirect_get_user_banner(array('width'=>270,'height'=>270), $user_identity) ,
					 array('width'=>270,'height'=>270) //size width,height=
				);
				
$is_banner	= docdirect_get_user_banner(0, $user_identity,'userprofile_banner');
$is_avatar	= docdirect_get_user_avatar(0, $user_identity,'userprofile_media');

$section_column	= 'col-md-12 col-sm-12 col-xs-12';
if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){
	$section_column	= 'col-md-12 col-sm-12 col-xs-12';
}

?>
<div class="tg-editprofile tg-haslayout">
	<div class="<?php echo esc_attr( $section_column );?> tg-findheatlhwidth">
		<div class="row">
			<div class="tg-editimg">
				<div class="tg-editimg-avatar">
					<div class="tg-heading-border tg-small">
						<h3><?php esc_html_e('upload photo','docdirect');?></h3>
					</div>
					<div class="tg-haslayout">
						<figure class="tg-docimg"> 
							<span class="user-avatar"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('Avatar','docdirect');?>"  /></span>
							<?php if( isset( $is_avatar ) && !empty( $is_avatar ) ) {?>
								<a href="javascript:;" class="tg-deleteimg del-avatar"><i class="fa fa-plus"></i></a>
							<?php }?>
							<div id="plupload-container">
								<a href="javascript:;" id="upload-profile-avatar" class="tg-uploadimg upload-avatar"><i class="fa fa-upload"></i></a> 
							</div>
						</figure>
						<div class="tg-uploadtips">
							<h4><?php esc_html_e('tips for uploading','docdirect');?></h4>
							<ul class="tg-instructions">
								<li><?php esc_html_e('Max Upload Size: ','docdirect');?><?php docdirect_format_size_units($dir_datasize,'print');?></li>
								<li><?php esc_html_e('Dimensions: 370x377','docdirect');?></li>
								<li><?php esc_html_e('Extensions: JPG,JPEG,PNG,GIF','docdirect');?></li>
							</ul>
						</div>
					</div>
					<div id="errors-log"></div>
				</div>
				<?php 
				if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true
					 && apply_filters('docdirect_is_setting_enabled',$user_identity,'profile_banner' ) === true
					){?>
				<div class="tg-editimg-banner">
					<div class="tg-heading-border tg-small">
						<h3><?php esc_html_e('Upload Banner','docdirect');?></h3>
					</div>
					<div class="tg-haslayout">
						<figure class="tg-docimg"> 
							<span class="user-banner"><img src="<?php echo esc_url( $banner );?>" alt="<?php esc_html_e('Avatar','docdirect');?>"  /></span>
							<?php if( isset( $is_banner ) && !empty( $is_banner ) ) {?>
								<a href="javascript:;" class="tg-deleteimg del-banner"><i class="fa fa-plus"></i></a>
							<?php }?>
							<div id="plupload-container-banner">
								<a href="javascript:;" id="upload-profile-banner" class="tg-uploadimg upload-banner"><i class="fa fa-upload"></i></a>
							</div>
						</figure>
						<div class="tg-uploadtips">
							<h4><?php esc_html_e('tips for uploading','docdirect');?></h4>
							<ul class="tg-instructions">
								<li><?php esc_html_e('Max Upload Size: ','docdirect');?><?php docdirect_format_size_units($dir_datasize,'print');?></li>
								<li><?php esc_html_e('Dimensions: 1920x450','docdirect');?></li>
								<li><?php esc_html_e('Extensions: JPG,JPEG,PNG,GIF','docdirect');?></li>
							</ul>
						</div>
					</div>
					<div id="errors-log"></div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>