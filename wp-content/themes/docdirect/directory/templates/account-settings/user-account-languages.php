<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$languages_array	= docdirect_prepare_languages();//Get Language Array
$db_languages   	= get_user_meta( $user_identity, 'languages', true);

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
<div class="tg-bordertop tg-haslayout">
	<div class="tg-formsection">
		<div class="tg-heading-border tg-small">
			<h3><?php esc_html_e('Language','docdirect');?></h3>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<select name="language[]" class="chosen-select" multiple>
						<option value=""><?php esc_attr_e('Select Languages','docdirect');?></option>
						<?php 
						if( isset( $languages_array ) && !empty( $languages_array ) ){

							foreach( $languages_array as $key=>$value ){
								$selected	= '';
								if( isset( $db_languages[$key] ) ){
									$selected	= 'selected';
								}
								?>
							<option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value );?></option>
						<?php }}?>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }?>