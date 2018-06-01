<?php
/**
 * User Profile Main
 * return html
 */

global $profileuser;
$user_identity	= $profileuser->ID;
$db_schedules	= array();
$db_schedules   = get_user_meta( $user_identity, 'schedules', false);
$db_languages   = get_user_meta( $user_identity, 'languages', true);
$db_insurance	= get_user_meta( $user_identity, 'insurance', true);
$db_latitude    = get_user_meta( $user_identity, 'latitude', true);
$db_longitude   = get_user_meta( $user_identity, 'longitude', true);
$db_location			= get_user_meta( $user_identity, 'location', true);
$db_address	 			= get_user_meta( $user_identity, 'address', true);
$user_gallery	 		= get_user_meta( $user_identity, 'user_gallery', true);
$video_url	  			= get_user_meta( $user_identity, 'video_url', true);
$contact_form	  		= get_user_meta( $user_identity, 'contact_form', true);
$verify_user	  		= get_user_meta( $user_identity, 'verify_user', true);
$db_directory_type	 	= get_user_meta( $user_identity, 'directory_type', true);
$user_featured			= get_user_meta( $user_identity, 'user_featured', true);
$professional_statements= get_user_meta( $user_identity, 'professional_statements', true);
$current_package		= get_user_meta($user_identity, 'user_current_package', true);
$package_expiry			= get_user_meta($user_identity, 'user_current_package_expiry', true);
$db_subcategory		    = get_user_meta( $user_identity, 'doc_sub_categories', true);

//Map Settings
if (function_exists('fw_get_db_settings_option')) {
	$dir_longitude = fw_get_db_settings_option('dir_longitude');
	$dir_latitude = fw_get_db_settings_option('dir_latitude');
	$dir_datasize = fw_get_db_settings_option('dir_datasize');
	$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
} else{
	$dir_longitude = '-0.1262362';
	$dir_latitude = '51.5001524';
	$dir_datasize = '5242880'; // 5 MB
}

$db_latitude		= isset( $db_latitude ) && !empty( $db_latitude ) ? $db_latitude : $dir_latitude;
$db_longitude	= isset( $db_longitude ) && !empty( $db_longitude ) ? $db_longitude : $dir_longitude;

//Specialities
if( isset( $db_directory_type ) && !empty( $db_directory_type ) ) {
	$attached_specialities = get_post_meta( $db_directory_type, 'attached_specialities', true );
	$education_switch  	  = fw_get_db_post_option( $db_directory_type, 'education', true );
	$experience_switch  	  = fw_get_db_post_option( $db_directory_type, 'experience', true );
	$prices_switch  	  = fw_get_db_post_option( $db_directory_type, 'price_list', true );
	$specialities_list	 = docdirect_prepare_taxonomies('directory_type','specialities',0,'array');
	$current_userdata	   = get_userdata($user_identity);
}

if( empty( $attached_specialities )){
	$attached_specialities	= array();
}

//Privacy Settings
$privacy		= docdirect_get_privacy_settings($user_identity);

//Languages
$languages_array	= docdirect_prepare_languages();//Get Language Array
docdirect_enque_map_library();//init Map

$today = time();
?>
<div class="tg-formeditprofile tg-haslayout do-account-setitngs tg-form-privacy">
    <fieldset>
      	<?php do_action('docdirect_add_extra_fields_before',$user_identity);?>
       	<div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_html_e('Sub Categories','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <select name="subcategory[]" class="chosen-select" multiple>
								<?php docdirect_get_linked_term_options($db_subcategory,'sub_category',$db_directory_type);?>
							</select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_attr_e('Professional Statements','docdirect');?></h2>
                </div>
                <div class="form-group">  
                    <?php 
						$professional_statements = !empty($professional_statements) ? $professional_statements : '';
						$settings = array( 
							'editor_class' => 'professional_statements', 
							'teeny' => false, 
							'media_buttons' => false, 
							'textarea_rows' => 10,
							'quicktags' => true,
							'editor_height' => 300
							
						);
						
						wp_editor( $professional_statements, 'professional_statements', $settings );
					?>
            	</div>
            </div>
        </div>
        
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_attr_e('Current Package','docdirect');?></h2>
                </div>
                <div class="form-group">  
                   <div class="tg-privacy">
                   	<?php if( !empty( $current_package ) ) {?>
						<span class="tg-privacy-name"><?php echo get_the_title($current_package);?></span>
						 <?php if( !empty( $package_expiry ) && $package_expiry >  $today ){?>
							<span><strong><?php esc_html_e('Package Expire on','docdirect');?>&nbsp;:&nbsp;<?php echo date_i18n(get_option('date_format'),$package_expiry);?></strong></span>
						 <?php } else{?>
							<span><strong><?php esc_html_e('Your package has expired or you didn\'t purchase any package yet. Please renew your package to use site features.','docdirect');?></strong></span>
					 <?php }}?>
					 <div class="current-package-wrap">
					 	<select name="current_package" class="current_package">
					 	  <option value=""><?php esc_html_e('Select package','docdirect');?></option>
						  <?php 
							if( apply_filters('docdirect_get_theme_settings', 'payments') === 'woo' ){
								
								$args = array(
									'post_type' 		=> 'product',
									'posts_per_page' 	=> -1,
									'order' 			=> 'DESC',
									'orderby' 			=> 'ID',
									'post_status' 		=> 'publish',
									'ignore_sticky_posts' => 1,
									'suppress_filters'  => false
								);
							} else{
								$args = array('posts_per_page' => '-1', 
									'post_type' 		=> 'directory_packages', 
									'orderby' 			=> 'ID', 
									'post_status' 		=> 'publish',
									'suppress_filters'  => false
								);
							}
							
							$cust_query = get_posts($args);

							if (isset($cust_query) && is_array($cust_query) && !empty($cust_query)) {
								$ounterpack	= 0;	
								foreach ($cust_query as $key => $pack) {
									$active	= isset( $ounterpack ) && $ounterpack === 0 ? 'checked' : '';
									$active_package	= '';
									$active_class	= '';

									if( isset( $current_package ) && $current_package == $pack->ID ) {
										$active_package	= 'selected';
										$active_class	= '';
									}
								?>
								  <option value="<?php echo intval( $pack->ID );?>" <?php echo esc_attr( $active_package );?>><?php echo esc_attr( $pack->post_title );?></option>

							<?php }}?>
						 </select>
					 </div>
                  	<div class="featured-field">
                    	<span class="tg-privacy-name"><?php esc_html_e('Add number of days to update package expiry date','docdirect');?></span>
                        <input type="text" placeholder="<?php esc_html_e('Add number of days','docdirect');?>" name="package_days" class="featured_days" value="" />
                        <input type="hidden" class="package_time_stamp" name="package_time_stamp" value="<?php echo esc_attr( $package_expiry );?>" />
                    </div>
                    <?php if( !empty( $package_expiry ) ) {?>
                        <div class="featured-field featured_detail_wrap">
                            <span class="tg-privacy-name"><?php esc_html_e('Expiry Date','docdirect');?></span>
                            <div class="expiry-date-wrap">
                                <?php if( !empty( $package_expiry ) && $package_expiry > $today ){?>
                                    <p><?php echo esc_attr( date_i18n('l, F j, Y',$package_expiry ) );?> <?php esc_html_e('at','docdirect');?> <?php echo esc_attr( date_i18n('H:i A', $package_expiry ));?></p>
                                <?php } else{?>
                                	<p><?php esc_html_e('This has expired and expiry date was:','docdirect');?>&nbsp;<strong><?php echo esc_attr( date_i18n('l, F j, Y',$package_expiry ) );?> <?php esc_html_e('at','docdirect');?> <?php echo esc_attr( date_i18n('H:i A', $package_expiry ));?></strong></p>
                                <?php }?>
                                <?php if( !empty( $package_expiry ) && $package_expiry > $today ){?>
                                    <div class="custom_or"><?php esc_html_e('Remove specific number of days from package expiry date','docdirect');?></div>
                                    <input type="text" placeholder="<?php esc_html_e('Exclude number of days','docdirect');?>" name="package_exclude" class="featured_days" value="" />
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                   </div>
                </div>
            </div>
        </div>
        
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_attr_e('Featured User','docdirect');?></h2>
                </div>
                <div class="form-group">  
                    <div class="tg-privacy"> 
                      <span class="tg-privacy-name"><?php esc_html_e('Featured User','docdirect');?></span>
                      <p><?php esc_html_e('Please note that user will be featured when user will pay amount against package.You can add/remove number of days to update user featured expiry date.','docdirect');?></p>
                      <?php if( !empty( $user_featured ) && $user_featured > $today ){?>
                      	<p><?php esc_html_e('You can also remove a user from featured list just click on button just given below.','docdirect');?></p>
                      <?php }?>
                    <div class="featured-field">
                    	<span class="tg-privacy-name"><?php esc_html_e('Add number of days to set featured expiry date','docdirect');?></span>
                        <input type="text" placeholder="<?php esc_html_e('Add number of days','docdirect');?>" name="featured_days" class="featured_days" value="" />
                        <input type="hidden" class="feature_time_stamp" name="feature_time_stamp" value="<?php echo esc_attr( $user_featured );?>" />
                    </div>
                    <?php if( !empty( $user_featured ) ) {?>
                        <div class="featured-field featured_detail_wrap">
                            <span class="tg-privacy-name"><?php esc_html_e('Expiry Date','docdirect');?></span>
                            <div class="expiry-date-wrap">
                                <?php if( !empty( $user_featured ) && $user_featured > $today ){?>
                                    <p><?php echo esc_attr( date_i18n('l, F j, Y',$user_featured ) );?> <?php esc_html_e('at','docdirect');?> <?php echo esc_attr( date_i18n('H:i A', $user_featured ));?></p>
                                <?php } else{?>
                                <p><?php esc_html_e('This user has excluded from featured listings. Expiry date was:','docdirect');?>&nbsp;<strong><?php echo esc_attr( date_i18n('l, F j, Y',$user_featured ) );?> <?php esc_html_e('at','docdirect');?> <?php echo esc_attr( date_i18n('H:i A', $user_featured ));?></strong></p>
                                <?php }?>
                                <?php if( !empty( $user_featured ) && $user_featured > $today ){?>
                                    <div class="remove_featured_wrap">
                                    	<a href="javascript:;" class="remove_featured button button-primary" /><?php esc_html_e('Exclude From Featured','docdirect');?></a>
                                    </div>
                                    <div class="custom_or"><?php esc_html_e('OR remove specific number of days from expiry date','docdirect');?></div>
                                    <input type="text" placeholder="<?php esc_html_e('Exclude number of days','docdirect');?>" name="featured_exclude" class="featured_exclude" value="" />
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
           	</div>
        </div>
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_attr_e('User Type','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="user_type">
                    	<select name="directory_type" class="ajax-specialities">
                           <?php 
                                $posts_array = array();
                                $args = array('posts_per_page' => "-1", 
                                    'post_type' => 'directory_type', 
                                    'post_status' => 'publish', 
                                    'ignore_sticky_posts' => 1,
                                    'suppress_filters'  => false,
                                );
                        
                                $posts_query = get_posts($args);
                                if( isset( $posts_query ) && !empty( $posts_query ) ) {
                                foreach ($posts_query as $direcotry) {
									$user_type_selected = '';
									if( !empty( $db_directory_type )
										&&
										$db_directory_type == $direcotry->ID
									) {
										$user_type_selected = 'selected';
									}
								?>
                                    
                                    <option value="<?php echo intval( $direcotry->ID );?>" <?php echo esc_attr( $user_type_selected );?>><?php echo esc_attr( $direcotry->post_title );?></option>
                                <?php }}?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_attr_e('Specialities','docdirect');?></h2>
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
								?>
                            	<li>
									<div class="tg-checkbox user-selection">
                                        <div class="tg-packages active-user-type specialities-type">
                                            <input type="checkbox" <?php echo esc_attr( $checked );?> name="specialities[<?php echo esc_attr( $speciality->term_id );?>]" value="<?php echo esc_attr( $speciality->slug );?>" id="<?php echo esc_attr( $speciality->slug );?>">
                                            <label for="<?php echo esc_attr( $speciality->slug );?>"><?php echo esc_attr( $speciality->name );?></label>
                                        </div>
                                    </div>
									
                                </li>
                            <?php }}}?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_html_e('User Gallery','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="z_option_wraper">
                        <div class="z_field">
                            <div class="section-upload">
                                <div class="z-option-uploader z-gallery">
                                    <div class="zaraar-buttons">
                                        <span id="upload" class="button multi_open"><?php esc_html_e('Upload Gallery','docdirect');?></span>
                                        <span style="" id="reset_gallery" class="button remove-gallery" title="Upload"><?php esc_html_e('Remove All','docdirect');?></span>
                                    </div>
                                    <div class="gallery-container">
                                        <ul class="gallery-list">
                                            <?php 
                                            if (isset($user_gallery) && $user_gallery != '') {
                                               foreach( $user_gallery as $key => $value ){
												 ?>
													<li class="image" data-attachment_id="<?php echo esc_attr( $value['id'] );?>">
														<input type="hidden" value="<?php echo esc_attr( $value['id'] );?>" name="user_gallery[<?php echo esc_attr( $value['id'] );?>][attachment_id]">
														<input type="hidden" value="<?php echo esc_attr( $value['url'] );?>" name="user_gallery[<?php echo esc_attr( $value['id'] );?>][url]">
														<img src="<?php echo esc_url( $value['url']);?>" alt="<?php esc_html_e('Gallery','docdirect');?>" />
														<a href="javascript:;" class="del-node"  title=""><i class="fa fa-times"></i></a>
													</li>
                                                 <?php
                                                }
                                            }
                                            ?>
                                         </ul>
                                      </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tg-bordertop tg-haslayout tg-form-privacy">
            <div class="tg-formsection">
               <div class="parent-heading">
                    <h2><?php esc_html_e('Privacy Settings','docdirect');?></h2>
                </div>
                <div class="form-group">  
                    <div class="tg-privacy"> 
                      <div class="tg-iosstylcheckbox">
                        <input type="hidden" name="privacy[appointments]">
                        <input type="checkbox" class="privacy-switch" <?php echo isset( $privacy['appointments'] ) && $privacy['appointments'] === 'on' ? 'checked':'';?>  name="privacy[appointments]" id="tg-appointment">
                        <label for="tg-appointment" class="checkbox-label" data-private="<?php esc_html_e('Private','docdirect');?>" data-public="<?php esc_html_e('Public','docdirect');?>"></label>
                      </div>
                      <span class="tg-privacy-name"><?php esc_html_e('Appointments','docdirect');?></span>
                      <p><?php esc_attr_e('Make the appointment/bookings form visible on my public profile','docdirect');?></p>
                    </div>
                </div>
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
        	</div>
       	</div>
        
        <!--Honour & Awards-->
        <div class="tg-bordertop tg-haslayout">
          <div class="tg-formsection tg-honor-awards">
            <div class="parent-heading">
              <h2><?php esc_html_e('Honors & Awards','docdirect');?></h2>
            </div>
            <div class="tg-education-detail tg-haslayout">
              <table class="table-striped awards_wrap">
                <thead class="cf">
                  <tr>
                    <th><?php esc_html_e('Title','docdirect');?></th>
                    <th><?php esc_html_e('Year','docdirect');?></th>
                  </tr>
                </thead>
                <?php 
				$awards_list	= get_the_author_meta('awards',$user_identity);
				$counter	= 0;
				if( isset( $awards_list ) && !empty( $awards_list ) ) {
					foreach( $awards_list as $key	=> $value ){
					?>
                    <tbody class="awards_item">
                      <tr>
                        <td data-title="Code"><?php echo esc_attr( $value['name'] );?>
                          <div class="tg-table-hover award-action"> 
                            <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a>
                            <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                           </div>
                        </td>
                        <td data-title="Company"><?php echo esc_attr( date_i18n('F m, Y',strtotime( $value['date'] ) ) );?></td>
                      </tr>
                      <tr>
                        <td class="award-data edit-me-row"colspan="2">
                            <div class="tg-education-form tg-haslayout">
                            	<div class="award-data">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input class="form-control" value="<?php echo esc_attr( $value['name'] );?>" name="awards[<?php echo intval( $counter );?>][name]" type="text" placeholder="<?php esc_attr_e('Award Name','docdirect');?>">
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input class="form-control award_datepicker" id="award_datepicker" value="<?php echo esc_attr( $value['date'] );?>" name="awards[<?php echo intval( $counter );?>][date]" type="text" placeholder="<?php esc_attr_e('Award Date','docdirect');?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <textarea class="form-control" name="awards[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Award Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </td>
                      </tr>
                    </tbody>
                    <?php
						$counter++;
						}
					}
				?>
              </table>
              </div>
            <div class="col-sm-12">
               <div class="tg-addfield add-new-awards">
                  <button type="button">
                      <i class="fa fa-plus"></i>
                      <span><?php esc_html_e('Add Awards','docdirect');?></span>
                  </button>
               </div>
            </div>
          </div>
        </div>
        <?php if( isset( $education_switch ) && $education_switch === 'enable' ) {?>
        <!--Education-->
        <div class="tg-bordertop tg-haslayout">
  			<div class="tg-formsection tg-education">
                <div class="parent-heading">
                    <h2><?php esc_html_e('Education','docdirect');?></h2>
                </div>
                <div class="tg-education-detail tg-haslayout">
                  <table class="table-striped educations_wrap" id="table-striped">
                    <thead class="cf">
                      <tr>
                        <th><?php esc_html_e('Degree / Education Title','docdirect');?></th>
                        <th><?php esc_html_e('Institute','docdirect');?></th>
                        <th class="numeric"><?php esc_html_e('Year','docdirect');?></th>
                      </tr>
                    </thead>
				    <?php 
                    $education_list	= get_the_author_meta('education',$user_identity);
                    $counter	= 0;
                    if( isset( $education_list ) && !empty( $education_list ) ) {
                        foreach( $education_list as $key	=> $value ){
							if( !empty( $value['end_date'] ) ) {
								$end_date	= date_i18n('M,Y',strtotime( $value['end_date']));
							} else{
								$end_date	= esc_html__('Current','docdirect');
							}
                        $flag	= rand(1,9999);
                        ?>
                      	<tbody class="educations_item">
                          <tr>
                            <td data-title="Code"><?php echo esc_attr( $value['title'] );?>
                              <div class="tg-table-hover education-action"> 
                                  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
                                  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                              </div>
                            </td>
                            <td data-title="Company"><?php echo esc_attr( $value['institute'] );?></td>
                            <td data-title="Price" class="numeric"><?php echo esc_attr( date_i18n('M,Y',strtotime( $value['start_date'] ) ) );?>&nbsp;-&nbsp;<?php echo esc_attr( $end_date );?></td>
                          </tr>
                          <tr>
                           <td class="education-data edit-me-row" colspan="3">
                             <div class="education-data-wrap">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" value="<?php echo esc_attr( $value['title'] );?>" name="education[<?php echo intval( $counter );?>][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" value="<?php echo esc_attr( $value['institute'] );?>" name="education[<?php echo intval( $counter );?>][institute]" type="text" placeholder="<?php esc_attr_e('Institute','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control edu_start_date_<?php echo esc_attr( $flag );?>" id="edu_start_date" value="<?php echo esc_attr( $value['start_date'] );?>" name="education[<?php echo intval( $counter );?>][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control edu_end_date_<?php echo esc_attr( $flag );?>" id="edu_end_date" value="<?php echo esc_attr( $value['end_date'] );?>" name="education[<?php echo intval( $counter );?>][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <textarea class="form-control" name="education[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Education Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                    </div>
                                </div>
                                <script>
                                   jQuery(document).ready(function(e) {
                                    jQuery('.edu_start_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                       format:localize_vars.calendar_format,
                                      onShow:function( ct ){
                                       this.setOptions({
                                        maxDate:jQuery('.edu_end_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.edu_end_date_<?php echo esc_js( $flag );?>').val() ):false
                                       })
                                      },
                                      timepicker:false
                                     });
                                    jQuery('.edu_end_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                       format:localize_vars.calendar_format,
                                      onShow:function( ct ){
                                       this.setOptions({
                                        minDate:jQuery('.edu_start_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.edu_start_date_<?php echo esc_js( $flag );?>').val() ):false
                                       })
                                      },
                                      timepicker:false
                                     });
                                   }); 
                                </script>
                               </div>
                           </td>
                      	  </tr>
                         </tbody>
                       <?php
							$counter++;
							}
						}
					?>
                  </table>
                </div>
                <div class="col-sm-12">
                    <div class="tg-addfield add-new-educations">
                        <button type="button">
                            <i class="fa fa-plus"></i>
                            <span><?php esc_html_e('Add Education','docdirect');?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        <?php if( isset( $experience_switch ) && $experience_switch === 'enable' ) {?>
        <!--Experience-->
        <div class="tg-bordertop tg-haslayout">
  			<div class="tg-formsection tg-experience">
                <div class="parent-heading">
                    <h2><?php esc_html_e('Experience','docdirect');?></h2>
                </div>
                <div class="tg-education-detail tg-haslayout">
                  <table class="table-striped experiences_wrap educations_wrap" id="table-striped">
                    <thead class="cf">
                      <tr>
                        <th><?php esc_html_e('Experience Title','docdirect');?></th>
                        <th><?php esc_html_e('Company/Organization','docdirect');?></th>
                        <th class="numeric"><?php esc_html_e('Year','docdirect');?></th>
                      </tr>
                    </thead>
				    <?php 
                    $experience_list	= get_the_author_meta('experience',$user_identity);
                    $counter	= 0;
                    if( isset( $experience_list ) && !empty( $experience_list ) ) {
                        foreach( $experience_list as $key	=> $value ){
							if( !empty( $value['end_date'] ) ) {
								$end_date	= date_i18n('M,Y',strtotime( $value['end_date']));
							} else{
								$end_date	= esc_html__('Current','docdirect');
							}
                        $flag	= rand(1,9999);
                        ?>
                      	<tbody class="experiences_item">
                          <tr>
                            <td data-title="Code"><?php echo esc_attr( $value['title'] );?>
                              <div class="tg-table-hover experience-action"> 
                                  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
                                  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                              </div>
                            </td>
                            <td data-title="Company"><?php echo esc_attr( $value['company'] );?></td>
                            <td data-title="Price" class="numeric"><?php echo esc_attr( date_i18n('M,Y',strtotime( $value['start_date'] ) ) );?>&nbsp;-&nbsp;<?php echo esc_attr( $end_date );?></td> 
                          </tr>
                          <tr>
                           <td class="experience-data edit-me-row" colspan="3">
                             <div class="experience-data-wrap education-data-wrap">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" value="<?php echo esc_attr( $value['title'] );?>" name="experience[<?php echo intval( $counter );?>][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" value="<?php echo esc_attr( $value['company'] );?>" name="experience[<?php echo intval( $counter );?>][company]" type="text" placeholder="<?php esc_attr_e('Company/Organization','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control exp_start_date_<?php echo esc_attr( $flag );?>" id="exp_start_date" value="<?php echo esc_attr( $value['start_date'] );?>" name="experience[<?php echo intval( $counter );?>][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control exp_end_date_<?php echo esc_attr( $flag );?>" id="exp_end_date" value="<?php echo esc_attr( $value['end_date'] );?>" name="experience[<?php echo intval( $counter );?>][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <textarea class="form-control" name="experience[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Experience Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                    </div>
                                </div>
                                <script>
                                   jQuery(document).ready(function(e) {
                                    jQuery('.exp_start_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                       format:localize_vars.calendar_format,
                                      onShow:function( ct ){
                                       this.setOptions({
                                        maxDate:jQuery('.exp_end_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.exp_end_date_<?php echo esc_js( $flag );?>').val() ):false
                                       })
                                      },
                                      timepicker:false
                                     });
                                    jQuery('.exp_end_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                       format:localize_vars.calendar_format,
                                      onShow:function( ct ){
                                       this.setOptions({
                                        minDate:jQuery('.exp_start_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.exp_start_date_<?php echo esc_js( $flag );?>').val()):false
                                       })
                                      },
                                      timepicker:false
                                     });
                                   }); 
                                </script>
                               </div>
                           </td>
                      	  </tr>
                         </tbody>
                       <?php
							$counter++;
							}
						}
					?>
                  </table>
                </div>
                <div class="col-sm-12">
                    <div class="tg-addfield add-new-experiences">
                        <button type="button">
                            <i class="fa fa-plus"></i>
                            <span><?php esc_html_e('Add Experience','docdirect');?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        
        <?php if( isset( $prices_switch ) && $prices_switch === 'enable' ) {?>
        <!--Experience-->
        <div class="tg-bordertop tg-haslayout">
  			<div class="tg-formsection tg-prices">
                <div class="parent-heading">
                    <h2><?php esc_html_e('Price/Services List','docdirect');?></h2>
                </div>
                <div class="tg-education-detail tg-haslayout">
                  <table class="table-striped prices_wrap education-data-wrap" id="table-striped">
                    <thead class="cf">
                      <tr>
                        <th><?php esc_html_e('Title','docdirect');?></th>
                        <th><?php esc_html_e('Price','docdirect');?></th>
                      </tr>
                    </thead>
				    <?php 
                    $prices_list	= get_the_author_meta('prices_list',$user_identity);
                    $counter	= 0;
                    if( isset( $prices_list ) && !empty( $prices_list ) ) {
                        foreach( $prices_list as $key	=> $value ){
                        ?>
                      	<tbody class="prices_item">
                          <tr>
                            <td data-title="Code"><?php echo esc_attr( $value['title'] );?>
                              <div class="tg-table-hover prices-action"> 
                                  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
                                  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                              </div>
                            </td>
                            <td data-title="Price"><?php echo esc_attr( $value['price'] );?></td>
                          </tr>
                          <tr>
                           <td class="prices-data edit-me-row" colspan="3">
                             <div class="prices-data-wrap prices-data-wrap">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" value="<?php echo esc_attr( $value['title'] );?>" name="prices[<?php echo intval( $counter );?>][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" value="<?php echo esc_attr( $value['price'] );?>" name="prices[<?php echo intval( $counter );?>][price]" type="text" placeholder="<?php esc_attr_e('Price','docdirect');?>">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <textarea class="form-control" name="prices[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                    </div>
                                </div>
                               </div>
                           </td>
                      	  </tr>
                         </tbody>
                       <?php
							$counter++;
							}
						}
					?>
                  </table>
                </div>
                <div class="col-sm-12">
                    <div class="tg-addfield add-new-prices">
                        <button type="button">
                            <i class="fa fa-plus"></i>
                            <span><?php esc_html_e('Add Price/Service','docdirect');?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>       
        <?php }?>
        
        <!--Language-->
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_html_e('Language','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
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
        
        <!--Insurance-->
        <?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
               <div class="parent-heading">
                    <h2><?php esc_html_e('Insurance Plans','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <select name="insurance[]" class="chosen-select" multiple>
                                <option value=""><?php esc_attr_e('Select insurance','docdirect');?></option>
                                <?php docdirect_get_term_options($db_insurance,'insurance');?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection tg-videoprofile">
                <div class="parent-heading">
                    <h2><?php esc_html_e('video link','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <input class="form-control" name="video_url" value="<?php echo esc_url( $video_url );?>" type="url" placeholder="<?php esc_attr_e('Enter Url','docdirect');?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Locations-->
        <div class="tg-bordertop tg-haslayout">
            <div class="tg-formsection">
                <div class="parent-heading">
                    <h2><?php esc_html_e('Locations','docdirect');?></h2>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <select name="basics[location]" class="locations-select">
                                <option value=""><?php esc_attr_e('Select Location','docdirect');?></option>
                                <?php docdirect_get_term_options($db_location,'locations');?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group locate-me-wrap">
                            <input type="text" placeholder="<?php esc_html_e('Your location','docdirect');?>" value="<?php echo esc_attr( $db_address );?>" name="basics[address]" class="form-control" id="location-address" />
                            <a href="javascript:;" class="geolocate"><img src="<?php echo get_template_directory_uri();?>/images/geoicon.svg" width="16" height="16" class="geo-locate-me" alt="<?php esc_html_e('Locate me!','docdirect');?>"></a>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<p><strong><?php esc_attr_e('Latitude and Longitudes are compulsory to show that user on map and also for search on the basis of location','docdirect');?></strong></p>
						</div>
					</div>
                   
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                           	<span class="tg-privacy-name"><?php esc_attr_e('Latitude?','docdirect');?></span>
                            <input type="text" placeholder="<?php esc_attr_e('Latitude','docdirect');?>" value="<?php echo esc_attr( $db_latitude );?>" name="basics[latitude]" class="form-control featured_days" id="location-latitude" />
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <span class="tg-privacy-name"><?php esc_attr_e('Longitude?','docdirect');?></span>
                            <input type="text" placeholder="<?php esc_attr_e('Longitude','docdirect');?>" value="<?php echo esc_attr( $db_longitude );?>" name="basics[longitude]" class="form-control featured_days" id="location-longitude" />
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div id="location-pickr-map"></div>
                        </div>
                    </div>
                    
                    <script>
						jQuery(document).ready(function(e) {
							//init
							jQuery.docdirect_init_map(<?php echo esc_attr( $db_latitude );?>,<?php echo esc_attr( $db_longitude );?>);
						});
					</script>
                </div>
            </div>
        </div>
        <?php do_action('docdirect_add_extra_fields_after',$user_identity);?>
        <input type="hidden" name="current_user_id" class="current_user_id" value="<?php echo intval($user_identity);?>" >
    </fieldset>
</div>
<script type="text/template" id="tmpl-load-awards">
	<tbody class="awards_item new-added">
	  <tr>
		<td data-title="Code"><?php esc_html_e('Award Title','docdirect');?>
		  <div class="tg-table-hover award-action"> 
			<a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a>
			<a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		   </div>
		</td>
		<td data-title="Company"><?php esc_html_e('January 01, 2020','docdirect');?></td>
	  </tr>
	  <tr>
		<td class="award-data edit-me-row"colspan="2">
			<div class="tg-education-form tg-haslayout">
				<div class="award-data">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="form-group">
							<input class="form-control" value="" name="awards[{{data}}][name]" type="text" placeholder="<?php esc_attr_e('Award Name','docdirect');?>">
						</div>
					</div>
					<div class="col-md-8 col-sm-6 col-xs-12">
						<div class="form-group">
							<input class="form-control award_datepicker" id="award_datepicker" value="" name="awards[{{data}}][date]" type="text" placeholder="<?php esc_attr_e('Award Date','docdirect');?>">
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<textarea class="form-control" name="awards[{{data}}][description]" placeholder="<?php esc_attr_e('Award Description','docdirect');?>"></textarea>
						</div>
					</div>
				</div>
			</div>
		</td>
	  </tr>
	</tbody>
</script>
<script type="text/template" id="tmpl-load-educations">
	<tbody class="educations_item">
	  <tr>
		<td data-title="Code"><?php esc_attr_e('Title here','docdirect');?>
		  <div class="tg-table-hover education-action"> 
			  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
			  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		  </div>
		</td>
		<td data-title="Company"><?php esc_attr_e('Institute here','docdirect');?></td>
		<td data-title="Price" class="numeric"><?php esc_attr_e('Jan,2020 - Jan,2021','docdirect');?></td>
	  </tr>
	  <tr>
	   <td class="education-data edit-me-row" colspan="3">
		 <div class="education-data-wrap">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="education[{{data}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="education[{{data}}][institute]" type="text" placeholder="<?php esc_attr_e('Institute','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control edu_start_date_{{data}}" id="edu_start_date" value="" name="education[{{data}}][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control edu_end_date_{{data}}" id="edu_end_date" value="" name="education[{{data}}][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="education[{{data}}][description]" placeholder="<?php esc_attr_e('Education Description','docdirect');?>"></textarea>
				</div>
			</div>
		  </div>
	    </td>
	  </tr>
	</tbody>
</script>
<script type="text/template" id="tmpl-load-experiences">
	<tbody class="experiences_item">
	  <tr>
		<td data-title="Code"><?php esc_attr_e('Title here','docdirect');?>
		  <div class="tg-table-hover experience-action"> 
			  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
			  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		  </div>
		</td>
		<td data-title="Company"><?php esc_attr_e('Company/Organization Name','docdirect');?></td>
		<td data-title="Price" class="numeric"><?php esc_attr_e('Jan,2020 - Jan,2021','docdirect');?></td>
	  </tr>
	  <tr>
	   <td class="experience-data edit-me-row" colspan="3">
		 <div class="experience-data-wrap education-data-wrap">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="experience[{{data}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="experience[{{data}}][company]" type="text" placeholder="<?php esc_attr_e('Company/Organization','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control exp_start_date_{{data}}" id="exp_start_date" value="" name="experience[{{data}}][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control exp_end_date_{{data}}" id="exp_end_date" value="" name="experience[{{data}}][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="experience[{{data}}][description]" placeholder="<?php esc_attr_e('Experience Description','docdirect');?>"></textarea>
				</div>
			</div>
		  </div>
	    </td>
	  </tr>
</tbody>
</script>
<script type="text/template" id="tmpl-load-prices">
	<tbody class="prices_item">
	  <tr>
		<td data-title="Title"><?php esc_attr_e('Title','docdirect');?>
		  <div class="tg-table-hover prices-action"> 
			  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
			  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		  </div>
		</td>
		<td data-title="Price"><?php esc_attr_e('Price','docdirect');?></td>
	  </tr>
	  <tr>
	   <td class="prices-data edit-me-row" colspan="3">
		 <div class="prices-data-wrap prices-data-wrap">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="prices[{{data}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="prices[{{data}}][price]" type="text" placeholder="<?php esc_attr_e('Price','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="prices[{{data}}][description]" placeholder="<?php esc_attr_e('Description','docdirect');?>"></textarea>
				</div>
			</div>
		  </div>
	    </td>
	  </tr>
</tbody>
</script>