<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  		= $current_user->ID;
$db_subcategory		    = get_user_meta( $user_identity, 'doc_sub_categories', true);
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);

if( apply_filters('docdirect_get_theme_settings', 'sub_category') === 'enable' ){
	if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
	<div class="tg-bordertop tg-haslayout">
		<div class="tg-formsection">
			<div class="tg-heading-border tg-small">
				<h3><?php esc_html_e('Sub Categories','docdirect');?></h3>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<select name="subcategory[]" class="chosen-select" multiple>
							<?php docdirect_get_linked_term_options($db_subcategory,'sub_category',$db_directory_type);?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }}?>