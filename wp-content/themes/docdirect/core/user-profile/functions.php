<?php
/**
 * User Admin Profile
 * return html
 */


/**
 * @User Public Profile
 * @return {}
 */
if (!function_exists('docdirect_edit_user_profile_edit')) {

    function docdirect_edit_user_profile_edit($user_identity) {
        $display_img_url = '';
        $display = $display_image = 'block';
        
		$display_img_url = docdirect_get_user_avatar(0, $user_identity->ID,'userprofile_media');
		$thumb_id	= get_user_meta($user_identity->ID, 'userprofile_media', true);
        
		if ( empty( $display_img_url )) {
            $display_image = 'elm-display-none';
        }
		
		//Banner
		$display_banner_url = '';
        $display_banner = 'block';
        
		$display_banner_url = docdirect_get_user_banner(0, $user_identity->ID,'userprofile_banner');
		$banner_thumb_id	= get_user_meta($user_identity->ID, 'userprofile_banner', true);
        
		if ( empty( $display_banner_url )) {
            $display_banner = 'elm-display-none';
        }
		
        ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th> <?php esc_html_e('Display Photo', 'docdirect'); ?></th>
                    <td>
                        <input type="hidden" name="userprofile_media" class="media-image" id="userprofile_media"  value="<?php echo intval( $thumb_id ); ?>" />
                        <input type="button" id="upload-user-avatar" class="button button-secondary" value="<?php esc_html_e('Uplaod Public Avatar','docdirect');?>" />
                    </td>
                </tr>
                <tr id="avatar-wrap" class="<?php echo esc_attr($display_image); ?>">
                    <td class="backgroud-image">
                        <a href="javascript:;" class="delete-auhtor-media"><i class="fa fa-times"></i></a>
						<img class="avatar-src-style" height="100px" src="<?php echo esc_url($display_img_url); ?>" id="avatar-src" />
						
                    </td>
                </tr>
            </tbody>
        </table>
        <?php if( apply_filters('docdirect_do_check_user_type',$user_identity->ID ) === true ){?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th> <?php esc_html_e('Profile banner', 'docdirect'); ?></th>
                    <td>
                        <input type="hidden" name="userprofile_banner" class="media-image" id="userprofile_banner"  value="<?php echo intval( $banner_thumb_id ); ?>" />
                        <input type="button" id="upload-user-banner" class="button button-secondary" value="<?php esc_html_e('Profile banner','docdirect');?>" />
                    </td>
                </tr>
                <tr id="banner-wrap" class="<?php echo esc_attr($display_banner); ?>">
                    <td class="backgroud-image">
                        <a href="javascript:;" class="delete-auhtor-banner"><i class="fa fa-times"></i></a>
						<img class="banner-src-style" height="100px" src="<?php echo esc_url($display_banner_url); ?>" id="banner-src" />
						
                    </td>
                </tr>
            </tbody>
        </table>
        <?php }?>
        <?php
		if( apply_filters('docdirect_do_check_user_type',$user_identity->ID ) === true ){
			get_template_part('core/user-profile/user','schedules');
			get_template_part('core/user-profile/user','account-settings');
		}
    }

}

/**
 * @User Public Profile Save
 * @return {}
 */
if (!function_exists('docdirect_personal_options_save')) {

    function docdirect_personal_options_save($user_identity) {
        $current_date	= date('Y-m-d H:i:s');
		$offset = get_option('gmt_offset') * intval(60) * intval(60);
		$current_date	= strtotime($current_date) + $offset;
		
		
		$userprofile_media = !empty( $_POST['userprofile_media'] ) ? intval( $_POST['userprofile_media'] ) : '';
        update_user_meta($user_identity, 'userprofile_media', $userprofile_media);

		//Banner
		$userprofile_banner = !empty( $_POST['userprofile_banner'] ) ? intval( $_POST['userprofile_banner'] ) : '';
        update_user_meta($user_identity, 'userprofile_banner', $userprofile_banner);
		
		//Featured
		update_user_meta( $user_identity, 'user_featured', esc_attr( $_POST['feature_time_stamp'] ) );

		if( !empty( $_POST['featured_days'] )  ){
			$user_featured_date    = get_user_meta( $user_identity, 'user_featured', true);
			$duration	    	   = !empty( $_POST['featured_days'] ) ? intval( $_POST['featured_days'] ) : 0;

			if( !empty( $user_featured_date ) && $user_featured_date > $current_date ){
				$featured_date	= strtotime("+".$duration." days", $user_featured_date);
				$featured_date	= date('Y-m-d H:i:s',$featured_date);
			} else{
				$current_date	= date('Y-m-d H:i:s');
				$duration	    = !empty( $_POST['featured_days'] ) ? intval( $_POST['featured_days'] ) : 0;
				$featured_date	= strtotime("+".$duration." days", strtotime($current_date));
				$featured_date	= date('Y-m-d H:i:s',$featured_date);
			}
			
			$featured_date	= strtotime($featured_date) + $offset;
			update_user_meta($user_identity,'user_featured', $featured_date ); //Update Expiry
		} else if( !empty( $_POST['featured_exclude'] ) ){
			$user_featured_date    = get_user_meta( $user_identity, 'user_featured', true);
			$duration	    	  = !empty( $_POST['featured_exclude'] ) ? intval( $_POST['featured_exclude'] ) : 0;
			
			if( isset( $user_featured_date ) && !empty( $user_featured_date ) ){
				$featured_date	= strtotime("-".$duration." days", $user_featured_date);
				$featured_date	= date('Y-m-d H:i:s',$featured_date);
			} 
			
			$featured_date	= strtotime($featured_date) + $offset;
			update_user_meta($user_identity,'user_featured',$featured_date); //Update Expiry
		}
		
		//package expiry
		update_user_meta( $user_identity, 'user_current_package_expiry', esc_attr( $_POST['package_time_stamp'] ) );
		if( !empty( $_POST['package_days'] )  ){
			$user_package_date    = get_user_meta( $user_identity, 'user_current_package_expiry', true);
			$duration	    	   = !empty( $_POST['package_days'] ) ? intval( $_POST['package_days'] ) : 0;

			if( !empty( $user_package_date ) && $user_package_date > $current_date ){
				$package_date	= strtotime("+".$duration." days", $user_package_date);
				$package_date	= date('Y-m-d H:i:s',$package_date);
			} else{
				$current_date	= date('Y-m-d H:i:s');
				$duration	    = !empty( $_POST['package_days'] ) ? intval( $_POST['package_days'] ) : 0;
				$package_date	= strtotime("+".$duration." days", strtotime($current_date));
				$package_date	= date('Y-m-d H:i:s',$package_date);
			}
			
			$package_date	= strtotime($package_date) + $offset;			
			update_user_meta($user_identity,'user_current_package_expiry',$package_date); //Update Expiry
		} else if( !empty( $_POST['package_exclude'] ) ){
			$user_package_date    = get_user_meta( $user_identity, 'user_current_package_expiry', true);
			$duration	    	  = !empty( $_POST['package_exclude'] ) ? intval( $_POST['package_exclude'] ) : 0;
			
			if( !empty( $user_package_date ) ){
				$package_date	= strtotime("-".$duration." days", $user_package_date);
				$package_date	= date('Y-m-d H:i:s',$package_date);
			} 
			
			$package_date	= strtotime($package_date) + $offset;			
			update_user_meta($user_identity,'user_current_package_expiry',$package_date); //Update Expiry
		}
		
		
		//Update Schedules
		$schedules	= !empty( $_POST['schedules'] ) && is_array( $_POST['schedules'] ) ? docdirect_sanitize_array( $_POST['schedules'] ) : array();
		update_user_meta( $user_identity, 'schedules', $schedules );
		
		//Update Professional Statements
		$professional_statements	= !empty( $_POST['professional_statements'] ) ? docdirect_sanitize_wp_editor( $_POST['professional_statements'] ) : '' ;
                                            
		update_user_meta( $user_identity, 'professional_statements', $professional_statements );
		update_user_meta( $user_identity, 'video_url', esc_url( $_POST['video_url'] ) );
		update_user_meta( $user_identity, 'directory_type', esc_attr( $_POST['directory_type'] ) );
		update_user_meta( $user_identity, 'show_admin_bar_front', false );
		
		//current package
		if( !empty( $_POST['current_package'] ) ){
			update_user_meta( $user_identity, 'user_current_package', esc_attr($_POST['current_package']) );
		}
		
		
		//Update General settings
		if( !empty( $_POST['basics'] ) && is_array($_POST['basics']) ){
			foreach( $_POST['basics'] as $key => $value ){
				update_user_meta( $user_identity, $key, esc_attr( $value ) );
			}
		}
		
		if( !empty( $_POST['privacy'] ) ){
			update_user_meta( $user_identity, 'privacy', docdirect_sanitize_array( $_POST['privacy'] ) );
		}
		
		//Awawrds
		$awards	= array();
		if( !empty( $_POST['awards'] ) ){
			
			$counter	= 0;
			foreach( $_POST['awards'] as $key=>$value ){
				$awards[$counter]['name']	= 	esc_attr( $value['name'] ); 
				$awards[$counter]['date']	= 	esc_attr( $value['date'] );
				$awards[$counter]['date_formated']	= 	date_i18n('d M, Y',strtotime(esc_attr( $value['date'])));  
				$awards[$counter]['description']	= 	esc_attr( $value['description'] ); 
				$counter++;
			}
			$json['awards']	= $awards;
		}
		update_user_meta( $user_identity, 'awards', $awards );
		
		//Gallery
		$user_gallery	= array();
		if( !empty( $_POST['user_gallery'] ) ){
			$counter	= 0;
			foreach( $_POST['user_gallery'] as $key=>$value ){
				$user_gallery[$value['attachment_id']]['url']	= 	esc_url( $value['url'] ); 
				$user_gallery[$value['attachment_id']]['id']	= 	esc_attr( $value['attachment_id']); 
				$counter++;
			}	
		}
		update_user_meta( $user_identity, 'user_gallery', $user_gallery );
		
		//Education
		$educations	= array();
		if( !empty( $_POST['education'] ) ){
			$counter	= 0;
			foreach( $_POST['education'] as $key=>$value ){
				$educations[$counter]['title']		 = esc_attr( $value['title'] ); 
				$educations[$counter]['institute']	 = esc_attr( $value['institute'] ); 
				$educations[$counter]['start_date']	 = esc_attr( $value['start_date'] ); 
				$educations[$counter]['end_date']	 = esc_attr( $value['end_date'] ); 
				$educations[$counter]['start_date_formated']	= date_i18n('M,Y',strtotime(esc_attr($value['start_date']))); 
				$educations[$counter]['end_date_formated']	    = date_i18n('M,Y',strtotime(esc_attr($value['end_date']))); 
				$educations[$counter]['description']			= esc_attr( $value['description'] ); 
				$counter++;
			}
			
			$json['education']	= $educations;
			
		}
		update_user_meta( $user_identity, 'education', $educations );
		
		//Experience
		$experiences	= array();
		if( !empty( $_POST['experience'] ) ){
			$counter	= 0;
			foreach( $_POST['experience'] as $key=>$value ){
				if( !empty( $value['title'] ) && !empty( $value['company'] ) ) {
					$experiences[$counter]['title']			= 	esc_attr( $value['title'] ); 
					$experiences[$counter]['company']	 	= 	esc_attr( $value['company'] ); 
					$experiences[$counter]['start_date']	= 	esc_attr( $value['start_date'] ); 
					$experiences[$counter]['end_date']	  	= 	esc_attr( $value['end_date'] ); 
					$experiences[$counter]['start_date_formated']  = date_i18n('M,Y',strtotime(esc_attr( $value['start_date']))); 
					$experiences[$counter]['end_date_formated']	= date_i18n('M,Y',strtotime(esc_attr( $value['end_date']))); 
					$experiences[$counter]['description']	= 	esc_attr( $value['description'] ); 
					$counter++;
				}
			}
			$json['experience']	= $experiences;
		}
		update_user_meta( $user_identity, 'experience', $experiences );
		
		//Experience
		$prices	= array();
		if( !empty( $_POST['prices'] ) ){
			$counter	= 0;
			foreach( $_POST['prices'] as $key=>$value ){
				if( !empty( $value['title'] ) ) {
					$prices[$counter]['title']	= 	esc_attr( $value['title'] ); 
					$prices[$counter]['price']	 = 	esc_attr( $value['price'] ); 
					$prices[$counter]['description']	= 	esc_attr( $value['description'] ); 
					$counter++;
				}
			}
			$json['prices_list']	= $prices;
		}
		
		update_user_meta( $user_identity, 'prices_list', $prices );
		
		//Specialities
		$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);
		if( isset( $db_directory_type ) && !empty( $db_directory_type ) ) {
			$specialities_list	 = docdirect_prepare_taxonomies('directory_type','specialities',0,'array');
		}
		
		
		$specialities	= array();
		if( !empty( $_POST['specialities'] ) ) {
			$submitted_specialities	= docdirect_sanitize_array($_POST['specialities']);

			if( isset( $specialities_list ) && !empty( $specialities_list ) ){
				$counter	= 0;
				foreach( $specialities_list as $key => $speciality ){
					if( isset( $submitted_specialities ) && in_array( $speciality->slug, $submitted_specialities ) ){
						update_user_meta( $user_identity, $speciality->slug, $speciality->slug );
						$specialities[$speciality->slug]	= $speciality->name;
					}else{
						update_user_meta( $user_identity, $speciality->slug, '' );
					}

					$counter++;
				}
			}
		}

		update_user_meta( $user_identity, 'user_profile_specialities', $specialities );
		
		
		//Languages
		$languages	= array();
		if( !empty( $_POST['language'] ) ){
			$counter	= 0;
			foreach( $_POST['language'] as $key=>$value ){
				$db_value	 = esc_attr($value);
				$languages[$db_value]	= 	$db_value; 
				$counter++;
			}
		}
		
		update_user_meta( $user_identity, 'languages', $languages );
		
		
		//Insurance
		$insurance	= array();
		if( !empty( $_POST['insurance'] ) ){
			$counter	= 0;
			foreach( $_POST['insurance'] as $key=>$value ){
				$db_value	 = esc_attr($value);
				$insurance[$db_value]	= 	$db_value; 
				$counter++;
			}
			
			$insurance	= array_filter($insurance);
		}
		
		update_user_meta( $user_identity, 'insurance', $insurance );
		
		do_action('docdirect_do_update_profile_backend_settings',$_POST,$user_identity); //save settings
    }

}


/**
 * @Get User Avatar
 * @return {}
 */
if (!function_exists('docdirect_get_user_avatar')) {
    function docdirect_get_user_avatar($sizes = array(), $user_identity = '') {
        extract(shortcode_atts(array(
			"width" => '300',
			"height" => '300',
			),
		$sizes));
		
		if ($user_identity != '') {
            $thumb_id	= get_user_meta($user_identity, 'userprofile_media', true);
			if( isset( $thumb_id ) && !empty( $thumb_id ) ) {
				$thumb_url = wp_get_attachment_image_src($thumb_id, array($width, $height), true);
				if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src($thumb_id, "full", true);
					return $thumb_url[0];
				}
			}
			return false;
        }
		return false;
    }
}

/**
 * @Get User Avatar
 * @return {}
 */
if (!function_exists('docdirect_get_user_banner')) {
    function docdirect_get_user_banner($sizes = array(), $user_identity = '') {
        extract(shortcode_atts(array(
			"width"  => '300',
			"height" => '300',
			),
		$sizes));
		
		if ($user_identity != '') {
            $thumb_id	= get_user_meta($user_identity, 'userprofile_banner', true);
			if( isset( $thumb_id ) && !empty( $thumb_id ) ) {
				$thumb_url = wp_get_attachment_image_src($thumb_id, array($width, $height), true);
				if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src($thumb_id, "full", true);
					return $thumb_url[0];
				}
			}
			return false;
        }
		return false;
    }
}

/**
 * @Get Single image
 * @return {}
 */
if (!function_exists('docdirect_get_single_image')) {
    function docdirect_get_single_image($sizes = array(), $user_identity = '') {
        extract(shortcode_atts(array(
				"width" => '300',
				"height" => '300',
			),
		$sizes));
		
		if ($user_identity != '') {
            $thumb_id	= get_user_meta($user_identity, 'email_media', true);
			if( isset( $thumb_id ) && !empty( $thumb_id ) ) {
				$thumb_url = wp_get_attachment_image_src($thumb_id, array($width, $height), true);
				if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src($thumb_id, "full", true);
					return $thumb_url[0];
				}
			}
			return false;
        }
		return false;
    }
}

/**
 * @Import Users
 * @return {}
 */
if (!function_exists('docdirect_import_users')) {
	function  docdirect_import_users(){
		
		$permalink = add_query_arg( 
								array(
									'&type=file',
								)
							);	
		
		//Import users via file
		if ( !empty( $_FILES['users_csv']['tmp_name'] ) ) {
			$import_user	= new DocDirect_Import_User();
			$import_user->docdirect_import_user();
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e('User Imported Successfully','docdirect');?></p>
			</div>
			<?php
		}
		
		?>
        <h3 class="theme-name"><?php esc_html_e('Import Directory Users','docdirect');?></h3>
        <div id="import-users" class="import-users">
            <div class="theme-screenshot">
                <img alt="<?php esc_attr_e('Import Users','docdirect');?>" src="<?php echo get_template_directory_uri();?>/core/images/users.jpg">
            </div>
			<h3 class="theme-name"><?php esc_html_e('Import Users','docdirect');?></h3>
            <div class="user-actions">
                <a href="javascript:;" class="button button-primary doc-import-users"><?php esc_html_e('Import','docdirect');?></a>
            </div>
		</div>
        <div id="import-users" class="import-users custom-import">
            <form method="post" action="<?php echo cus_prepare_final_url('file','import_users'); ?>"  enctype="multipart/form-data">
				<div class="theme-screenshot">
					<img alt="<?php esc_attr_e('Import Users','docdirect');?>" src="<?php echo get_template_directory_uri();?>/core/images/users.jpg">
				</div>
				<h3 class="theme-name">
					<input id="upload-dummy-csv" type="file" name="users_csv" >
					<label for="upload-dummy-csv" class="button button-primary upload-dummy-csv"><?php esc_html_e('Choose File','docdirect');?></lable>
				</h3>
				<div class="user-actions">
					<input type="submit" class="button button-primary" value="<?php esc_html_e('Import From File','docdirect');?>">
					
				</div>
            </form>
		</div>
        <?php
	}
}

/**
 * @Add New Users meta
 * @return {}
 */
if (!function_exists('docdirect_save_custom_user_profile_fields')) {
	function docdirect_save_custom_user_profile_fields($user_id){
		# again do this only if you can
		if(!current_user_can('manage_options'))
			return false;
		
		$full_name = docdirect_get_username($user_id);
		
		# save my custom field
		update_user_meta( $user_id, 'full_name', $full_name );
		update_user_meta( $user_id, 'verify_user', 'on');
		update_user_meta( $user_id, 'show_admin_bar_front', false );
	}
	add_action('user_register', 'docdirect_save_custom_user_profile_fields');
}

/**
 * @Get Currencies Symbol
 * @return {}
 */
if (!function_exists('docdirect_get_specialities_ajax')) {

    function docdirect_get_specialities_ajax() {
        $user_identity	= !empty( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : '';
		
		$json = array();
		
		$sp_id	= intval( $_POST['id'] );
		
		if( empty( $sp_id ) ) {
			$json['type']	= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later.','docdirect');
			$json['data']		= $data;
			echo json_encode($json);
			die;
		}
		
		$attached_specialities = get_post_meta( $sp_id, 'attached_specialities', true );
		
		
		
		$specialities_list	 = docdirect_prepare_taxonomies('directory_type','specialities',0,'array');
		
		ob_start();
		
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
			<?php }
			}
		} else{?>
			<li>
				<div class="tg-checkbox user-selection">
					<div class="tg-packages active-user-type specialities-type">
						<label><?php esc_html_e('No specialities found','docdirect');?></label>
					</div>
				</div>

			</li>	
		<?php
		}
		
		$data	= ob_get_clean();
		
		$json['type']	= 'success';
		$json['message']	= esc_html__('found.','docdirect');
		$json['data']		= $data;
		echo json_encode($json);
		die;
		
    }

    add_action('wp_ajax_docdirect_get_specialities_ajax' , 'docdirect_get_specialities_ajax');
}


/**
 * @init            tab url
 * @package         Tailors Online
 * @subpackage      tailors-online/admin/partials
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('cus_prepare_final_url')) {

    function cus_prepare_final_url($tab='',$page='import_users') {
		$permalink = '';
		$permalink = add_query_arg( 
								array(
									'?page'	=>   urlencode( $page ) ,
									'tab'	=>   urlencode( $tab ) ,
								)
							);	
		
		return esc_url( $permalink );
	}
}