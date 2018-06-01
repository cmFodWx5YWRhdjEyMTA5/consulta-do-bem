<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);
if( isset( $db_directory_type ) && !empty( $db_directory_type ) ) {
	$specialities_list	 	= docdirect_prepare_taxonomies('directory_type','specialities',0,'array');
	$attached_specialities  = get_post_meta( $db_directory_type, 'attached_specialities', true );
}

if( empty( $attached_specialities )){
	$attached_specialities	= array();
} 

do_action('enqueue_unyson_icon_css');

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
    <div class="tg-bordertop tg-haslayout">
        <div class="tg-formsection">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_attr_e('Specialities','docdirect');?></h3>
            </div>
            <div class="row">
                <div class="specialities-list">
                    <ul>
                        <?php 
                        if( isset( $specialities_list ) && !empty( $specialities_list ) ){
                            foreach( $specialities_list as $key => $speciality ){
                                $db_speciality	= get_user_meta( $user_identity, $speciality->slug, true);
                                $checked	= '';
                                if( isset( $db_speciality ) && !empty( $db_speciality ) && $db_speciality === $speciality->slug ){
                                    $checked	= 'checked';
                                }
                                
                                if( in_array( $speciality->term_id , $attached_specialities ) ) {
									$get_speciality_term = get_term_by('slug', $speciality->slug, 'specialities');
									$speciality_title = '';
									$term_id = '';
									if (!empty($get_speciality_term)) {
										$speciality_title = $get_speciality_term->name;
										$term_id = $get_speciality_term->term_id;
									}

									$speciality_meta = array();
									if (function_exists('fw_get_db_term_option')) {
										$speciality_meta = fw_get_db_term_option($term_id, 'specialities');
									}

									$speciality_icon = array();
									if (!empty($speciality_meta['icon']['icon-class'])) {
										$speciality_icon = $speciality_meta['icon']['icon-class'];
									}
                            ?>
                            <li>
                                <div class="tg-checkbox user-selection">
                                    <div class="tg-packages active-user-type specialities-type">
                                        <input type="checkbox" class="speciality-count" <?php echo esc_attr( $checked );?> name="specialities[<?php echo esc_attr( $speciality->term_id );?>]" value="<?php echo esc_attr( $speciality->slug );?>" id="<?php echo esc_attr( $speciality->slug );?>">
                                        <label for="<?php echo esc_attr( $speciality->slug );?>">
                                        	<?php 
											if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'icon-font') {
												if (!empty($speciality_icon)) { ?>
													<i class="<?php echo esc_attr($speciality_icon); ?>"></i>
												<?php 
												}
											} else if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'custom-upload') {
												if (!empty($speciality_meta['icon']['url'])) {
												?>
												<img src="<?php echo esc_url($speciality_meta['icon']['url']);?>">
											<?php }}?>
                                        	<?php echo esc_attr( $speciality->name );?>
                                        </label>
                                    </div>
                                </div>
                                
                            </li>
                        <?php }}}?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
 <?php }