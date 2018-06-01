<?php 
/**
 * @Import Users
 * @return{}
 */     

if ( !class_exists('DocDirect_Import_User') ) {    
    class DocDirect_Import_User {        
        function __construct(){
            // Constructor Code here..
   		}
		
		/*
		 * @import users
		 */
		public function docdirect_import_user(){
		
			global $wpdb, $wpdb_data_table;
		
			
		if ( ! defined( 'IS_IU_CSV_DELIMITER' ) )
			define ( 'IS_IU_CSV_DELIMITER', ',' );
	
			// User data fields list used to differentiate with user meta
			$userdata_fields       = array(
				'ID', 
				'user_login', 
				'user_pass',
				'user_email', 
				'user_url', 
				'user_nicename',
				'display_name', 
				'user_registered', 
				'first_name',
				'last_name', 
				'nickname', 
				'description',
				'rich_editing', 
				'comment_shortcuts', 
				'admin_color',
				'use_ssl', 
				'show_admin_bar_front', 
				'show_admin_bar_admin',
				'role'
			);

			$wp_user_table		= $wpdb->prefix.'users';
			$wp_usermeta_table	= $wpdb->prefix.'usermeta';	
			
			if ( isset( $_FILES['users_csv']['tmp_name'] ) ) {
				$filename              = $_FILES['users_csv']['tmp_name'];
				$file_handle = fopen( $filename, 'r' );
			} else{
				$file_handle = fopen( DocDirectGlobalSettings::get_plugin_path().'/import-users/users.csv', 'r' );
			}
			
	
			// Loop through the file lines
			$csv_reader = new ReadCSV( $file_handle, IS_IU_CSV_DELIMITER, "\xEF\xBB\xBF" ); // Skip any UTF-8 byte order mark.
	
			$first = true;
			$rkey = 0;
			while ( ( $line = $csv_reader->get_row() ) !== NULL ) {
	
				// If the first line is empty, abort
				// If another line is empty, just skip it
				if ( empty( $line ) ) {
					if ( $first )
						break;
					else
						continue;
				}
	
				// If we are on the first line, the columns are the headers
				if ( $first ) {
					$headers = $line;
					$first = false;
					continue;
				} else{
					$data = array_map("utf8_encode", $line); //Encoding other than english language
				}
	
				// Separate user data from meta
				$userdata = $usermeta = array();
				foreach ( $data as $ckey => $column ) {
					$column_name = $headers[$ckey];
					$column = trim( $column );
	
					if ( in_array( $column_name, $userdata_fields ) ) {
						$userdata[$column_name] = $column;
					} else {
						$usermeta[$column_name] = $column;
					}
				}

				// If no user data, bailout!
				if ( empty( $userdata ) )
					continue;
	
				// Something to be done before importing one user?
				//do_action( 'is_iu_pre_user_import', $userdata, $usermeta );
	
				$user = $user_id = false;
	
				if ( isset( $userdata['r_id'] ) ) {
					$user = get_user_by( 'ID', $userdata['r_id'] );
				}
	
				if ( ! $user ) {
					if ( isset( $userdata['user_login'] ) )
						$user = get_user_by( 'login', $userdata['user_login'] );
	
					if ( ! $user && isset( $userdata['user_email'] ) )
						$user = get_user_by( 'email', $userdata['user_email'] );
				}
				
				$update = false;
				if ( $user ) {
					$userdata['ID'] = $user->ID;
					$update = true;
				}
	
				// If creating a new user and no password was set, let auto-generate one!
				if ( ! $update && $update == false  && empty( $userdata['user_pass'] ) ) {
					$userdata['user_pass'] = wp_generate_password( 12, false );
				}
	
				/*if ( $update )
					$user_id = wp_update_user( $userdata );
				else
					$user_id = wp_insert_user( $userdata );*/
				
				if (isset($update)&& $update == true) {
					$userdata['ID']	= $usermeta['r_id'];
					//$sql = "UPDATE $wp_user_table SET VALUE=".$value_to_store." WHERE USER_ID=$wp_userid AND FIELD_ID=".$ef_details["ID"];
					$user_id = wp_update_user( $userdata );
				} else {
					
					$display_name	= '';
					$display_name	= $userdata['first_name'].' '.$userdata['last_name'];
					if( $userdata['display_name'] && $userdata['display_name'] !='' ){
						$display_name	= $userdata['display_name'];
					}
					
					$sql = "INSERT INTO $wp_user_table (ID, 
														user_login, 
														user_pass, 
														user_email, 
														user_registered,
														user_status, 
														display_name, 
														user_nicename, 
														user_url
														) VALUES ('".$usermeta['r_id']."',
														'".$userdata['user_login']."',
														'".md5($userdata['user_pass'])."',
														'".$userdata['user_email']."',
														'".date('Y-m-d H:i:s')."',
														0,
														'".$display_name."',
														'".$userdata['user_nicename']."',
														'".$userdata['user_url']."'
													)";
					$wpdb->query($sql);
					$lastid = $wpdb->insert_id;
					$new_user = new WP_User( $lastid );
					$new_user->set_role( 'professional' );	
					$user_id =	$lastid;
					
					// Include again meta fields
					$usermeta['description']	= $userdata['description'];
					$usermeta['first_name']	    = $userdata['first_name'];
					$usermeta['last_name']	    = $userdata['last_name'];
					$usermeta['nickname']	    = $userdata['user_nicename'];
					
					update_user_meta( $user_id, 'rich_editing', 'true' );
					update_user_meta( $user_id, 'user_type', $userdata['role'] ); //update user type
					update_user_meta( $user_id, 'show_admin_bar_front', false );
					update_user_meta( $user_id, 'full_name', esc_attr( $userdata['first_name'].' '.$userdata['last_name'] ) );
					
				}
				
				
				// Is there an error o_O?
				if ( is_wp_error( $user_id ) ) {
					$errors[$rkey] = $user_id;
				} else {
					// If no error, let's update the user meta too!
					
					docdirect_get_week_array();
					$schedules	= array( 'sun_start',
										 'sun_end',
										 'mon_start',
										 'mon_end',
										 'tue_start',
										 'tue_end',
										 'wed_start',
										 'wed_end',
										 'thu_start',
										 'thu_end',
										 'fri_start',
										 'fri_end',
										 'sat_start',
										 'sat_end',
									   );
					$db_schedules	= array();
					if ( $usermeta ) {
						foreach ( $usermeta as $metakey => $metavalue ) {
							
							$metavalue = maybe_unserialize( $metavalue );
							
							if( in_array($metakey,$schedules) ) {
								$db_schedules[$metakey]	=  $metavalue; 
							} else {
								
								
								//Languages
								if( $metakey == 'languages' && !empty( $metavalue ) ){
									$languages	= explode(',',$metavalue);
									$db_languages	= array();
									foreach($languages as $key => $value){
										$db_languages[$value] = trim( $value );
									}

									update_user_meta( $user_id, $metakey, $db_languages );
								}else if( $metakey == 'user_gallery' && !empty( $metavalue ) ){
									$user_gallery	= explode(',',$metavalue);
									$db_user_gallery = array();
									
									foreach( $user_gallery as $key => $value ){
										$thumbnail = docdirect_get_image_source($value, 150, 150);
										$db_user_gallery[$value]['url'] = $thumbnail;
										$db_user_gallery[$value]['id']  = $value;
									}

									update_user_meta( $user_id, $metakey, $db_user_gallery );
								}else if( $metakey == 'privacy' && !empty( $metavalue ) ){
									$awards	= array();
									$user_privacy	= explode('[[]]',$metavalue);
									$user_privacy	= array_diff( $user_privacy, array('') );

									$db_user_privacy = array();
									$counter	= 0;
									foreach( $user_privacy as $key => $value ){
										$privacy_data	= explode(':',$value);
										if( !empty( $privacy_data ) ) {
											$db_user_privacy[trim( $privacy_data[0])]	= trim( $privacy_data[1] ); 
											$counter++;
										}
									}
									
									update_user_meta( $user_id, $metakey, $db_user_privacy );
								}else if( $metakey == 'awards' && !empty( $metavalue ) ){
									$awards	= array();
									$user_awards	= explode('[[[award]]]',$metavalue);
									$user_awards	= array_diff( $user_awards, array( '' ) );

									$db_user_awards = array();
									$counter	= 0;
									foreach( $user_awards as $key => $value ){
										$award_data	= explode('[[]]',$value);
										if( !empty( $award_data ) ) {
											$db_user_awards[$counter]['name']	= trim( $award_data[0] ); 
											$db_user_awards[$counter]['date']	= trim( $award_data[1] );
											$db_user_awards[$counter]['date_formated']  = date('d M, Y',strtotime($award_data[1]));  
											$db_user_awards[$counter]['description']	= addslashes($award_data[2]); 
											$counter++;
										}
									}
									update_user_meta( $user_id, $metakey, $db_user_awards );
								}else if( $metakey == 'education' && !empty( $metavalue ) ){
									$educations	= array();
									$user_educations	= explode('[[[education]]]',$metavalue);
									$user_educations	= array_diff( $user_educations, array( '' ) );

									$db_user_educations = array();
									$counter	= 0;
									foreach( $user_educations as $key => $value ){
										$education_data	= explode('[[]]',$value);
										if( !empty( $education_data ) ) {
											$db_user_educations[$counter]['title']	= trim( $education_data[0] );
											$db_user_educations[$counter]['institute']	 = trim( $education_data[1] );
											$db_user_educations[$counter]['start_date']	= trim( $education_data[2] );
											$db_user_educations[$counter]['end_date']	  = trim( $education_data[3] );
											$db_user_educations[$counter]['start_date_formated']  = date('M,Y',strtotime($education_data[2])); 
											$db_user_educations[$counter]['end_date_formated']	= date('M,Y',strtotime($education_data[3])); 
											$db_user_educations[$counter]['description']	= trim( $education_data[4] ); 
					
											$counter++;
										}
									}
									
									update_user_meta( $user_id, $metakey, $db_user_educations );
								}else if( $metakey == 'experience' && !empty( $metavalue ) ){
									$experiences	= array();
									$user_experiences	= explode('[[[experience]]]',$metavalue);
									$user_experiences	= array_diff( $user_experiences, array( '' ) );

									$db_user_experiences = array();
									$counter	= 0;
									foreach( $user_experiences as $key => $value ){
										$experience_data	= explode('[[]]',$value);
										if( !empty( $experience_data ) ) {
											$db_user_experiences[$counter]['title']	= trim( $experience_data[0] );
											$db_user_experiences[$counter]['company']	 = trim( $experience_data[1] );
											$db_user_experiences[$counter]['start_date']	= trim( $experience_data[2] );
											$db_user_experiences[$counter]['end_date']	  = trim( $experience_data[3] );
											$db_user_experiences[$counter]['start_date_formated']  = date('M,Y',strtotime($experience_data[2])); 
											$db_user_experiences[$counter]['end_date_formated']	= date('M,Y',strtotime($experience_data[3])); 
											$db_user_experiences[$counter]['description']	= trim( $experience_data[4] ); 
					
											$counter++;
										}
									}
									
									update_user_meta( $user_id, $metakey, $db_user_experiences );
								}else if( $metakey == 'specialities' && !empty( $metavalue ) ){
									
									//Specialities
									$db_directory_type	 = get_user_meta( $user_id, 'directory_type', true);
									if( !empty( $db_directory_type ) ) {
										$specialities_list	 = docdirect_prepare_taxonomies('directory_type','specialities',0,'array');
									}

									$specialities	= array();
									$submitted_specialities	= explode(',',$metavalue);

									if( isset( $specialities_list ) && !empty( $specialities_list ) ){
										$counter	= 0;
										foreach( $specialities_list as $key => $speciality ){
											if( isset( $submitted_specialities ) 
												&& is_array( $submitted_specialities ) 
												&& in_array( $speciality->slug, $submitted_specialities ) 
											 ){
												update_user_meta( $user_id, $speciality->slug, esc_attr( $speciality->slug ) );
												$specialities[$speciality->slug]	= $speciality->name;
											}else{
												update_user_meta( $user_id, $speciality->slug, '' );
											}

											$counter++;
										}
									}

									update_user_meta( $user_id, 'user_profile_specialities', $specialities );
									
								}else if( $metakey == 'geo_address' && !empty( $metavalue ) ){
									update_user_meta( $user_id, 'address', trim( $metavalue ) );
								} else{
									update_user_meta( $user_id, $metakey, trim( $metavalue ) );
								}
								
							}
							
						}
						
						update_user_meta( $user_id, 'schedules', $db_schedules );
					
					}
	
					// If we created a new user, maybe set password nag and send new user notification?
					if ( ! $update ) {
					}
				}
	
				$rkey++;
			}
			
			//Import booking dummy contents
			if( function_exists('docdirect_update_users') ) {
				//docdirect_update_users();
			}
			
			fclose( $file_handle );
		}
	}
}