<?php
/**
 * Copany Profile
 * return html
 */
global $current_user, $wp_roles,$userdata,$post;
$reference = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : $reference	= '';
$user_identity	= $current_user->ID;

 if (function_exists('fw_get_db_settings_option')) {
	$company_profile = fw_get_db_settings_option('company_profile');
	$support_box = fw_get_db_settings_option('support_box');
	$com_desscription = fw_get_db_settings_option('com_description');
	$com_logo = fw_get_db_settings_option('com_logo');
	$support_heading = fw_get_db_settings_option('support_heading');
	$support_address = fw_get_db_settings_option('support_address');
	$support_phone  = fw_get_db_settings_option('support_phone');
	$support_email  = fw_get_db_settings_option('support_email');
	$support_fax 	= fw_get_db_settings_option('support_fax');
}

$mainWrap	= 'col-xs-12 tg-findheatlhwidth';
$supportWrap	= 'col-xs-12 tg-expectwidth';
	
if( isset( $support_box ) && $support_box === 'enable' ){
	$mainWrap	= 'col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth';
	$supportWrap	= 'col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth';
}

if( isset( $company_profile ) && $company_profile === 'enable' ){
?>
<div class="tg-dashboard tg-haslayout">
	<div class="tg-docprofilechart tg-haslayout">
		<div class="<?php echo esc_attr( $mainWrap );?>">
			<div class="row">
				<div class="tg-docinfo tg-haslayout">
					<div class="tg-box">
						<div class="tg-heading-border tg-small">
							<h3><?php esc_html_e('welcome, ','docdirect');?><?php echo get_the_author_meta('display_name',$user_identity );?></h3>
						</div>
						<?php if( isset( $com_desscription ) && !empty( $com_desscription ) ){?>
						<div class="tg-description">
							<p><?php echo esc_attr( $com_desscription );?></p>
						</div>
						<?php }?>
						<div class="tg-bottominfo tg-haslayout">
							<div class="tg-regardsleft"> <em><?php esc_html_e('Regards,','docdirect');?></em> <strong><?php echo esc_attr(get_bloginfo('description')); ?></strong> </div>
							<?php if( isset( $com_logo['url'] ) && !empty( $com_logo['url'] ) ){?>
							<div class="tg-regardsright"> <strong class="logo"><img src="<?php echo esc_url($com_logo['url']); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>"></strong> </div>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if( isset( $support_box ) && $support_box === 'enable' ){?>
			<div class="<?php echo esc_attr( $supportWrap );?>">
				<div class="row">
					<div class="tg-support">
						<?php if( isset( $support_heading ) && !empty( $support_heading ) ){?>
							<div class="tg-heading-border tg-small">
								<h3><?php echo esc_attr( $support_heading );?></h3>
							</div>
						<?php }?>
						<ul class="tg-doccontactinfo">
							<?php if( isset( $support_address ) && !empty( $support_address ) ){?>
								<li><i class="fa fa-map-marker"></i><address><?php echo esc_attr( $support_address );?></address></li>
							<?php }?>
							<?php if( isset( $support_phone ) && !empty( $support_phone ) ){?>
								<li><i class="fa fa-phone"></i><a href="tel:<?php echo esc_attr( $support_phone );?>"><?php echo esc_attr( $support_phone );?></a></li>
							<?php }?>
							<?php if( isset( $support_email ) && !empty( $support_email ) ){?>
								<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $support_email );?>?Subject=Hello%20again" target="_top"><?php echo esc_attr( $support_email );?></a></li>
							<?php }?>
							<?php if( isset( $support_fax ) && !empty( $support_fax ) ){?>
								<li><i class="fa fa-fax"></i><span><?php echo esc_attr( $support_fax );?></span></li>
							<?php }?>
						</ul>
					</div>
				</div>
			</div>
		<?php }?>
	</div>
</div>
<?php
}
