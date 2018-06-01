<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);

$user_url	= '';
if( isset( $db_directory_type ) && !empty( $db_directory_type ) ) {
	$current_userdata	   		= get_userdata($user_identity);
	$user_url					= $current_userdata->data->user_url;
}
?>
<div class="tg-bordertop tg-haslayout">
	<div class="tg-formsection">
		<div class="tg-heading-border tg-small">
			<h3><?php esc_attr_e('Basic Information','docdirect');?></h3>
		</div>
		<div class="row">
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[nickname]" value="<?php echo get_user_meta($user_identity,'nickname',true); ?>" type="text" placeholder="<?php esc_attr_e('Nick Name','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[first_name]" value="<?php echo get_user_meta($user_identity,'first_name',true); ?>" type="text" placeholder="<?php esc_attr_e('First Name','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[last_name]" value="<?php echo get_user_meta($user_identity,'last_name',true); ?>" type="text" placeholder="<?php esc_attr_e('Last Name','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[phone_number]" value="<?php echo get_user_meta($user_identity,'phone_number',true); ?>" type="text" placeholder="<?php esc_attr_e('Phone','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[fax]" value="<?php echo get_user_meta($user_identity,'fax',true); ?>" type="text" placeholder="<?php esc_attr_e('Fax','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[user_url]" value="<?php echo esc_attr( $user_url ); ?>" type="url" placeholder="<?php esc_attr_e('URL','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[tagline]" value="<?php echo get_user_meta($user_identity,'tagline',true); ?>" type="text" placeholder="<?php esc_attr_e('Tagline','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[zip]" value="<?php echo get_user_meta($user_identity,'zip',true); ?>" type="text" placeholder="<?php esc_attr_e('Zip/Postal Code','docdirect');?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="basics[user_address]" value="<?php echo get_user_meta($user_identity,'user_address',true); ?>" type="text" placeholder="<?php esc_attr_e('Address','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="basics[description]" placeholder="<?php esc_attr_e('Short description','docdirect');?>"><?php echo get_user_meta($user_identity,'description',true); ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>