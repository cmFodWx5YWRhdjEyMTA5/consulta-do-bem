<?php
/**
 * Email Helper For Theme
 * Long Description.
 * @since    1.0.0
 */
if( ! class_exists( 'DocDirectProcessEmail' ) ) {
	class DocDirectProcessEmail{
		
		public function __construct(){
			add_filter( 'wp_mail_content_type', array(&$this,'docdirect_set_content_type') );
			add_filter( 'wp_mail_from', array(&$this,'docdirect_wp_mail_from') );
			add_filter( 'wp_mail_from_name', array(&$this,'docdirect_wp_mail_from_name') );
			add_filter( 'retrieve_password_message', array(&$this,'docdirect_reset_password_message'), null, 2);
		}
		
		/**
		 * Email Headers From
		 *
		 *
		 * @since    1.0.0
		 */
		public function docdirect_wp_mail_from($email) {
			if (function_exists('fw_get_db_settings_option')) {
				$email_from_id = fw_get_db_settings_option('email_from_id');
            }
			
			if( !empty( $email_from_id ) ) {
				return $email_from_id;
			} else{
				return 'info@no-reply.com';
			}
		}
		
		/**
		 * Email Headers From name
		 *
		 *
		 * @since    1.0.0
		 */
		public function docdirect_wp_mail_from_name($name) {
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

			if (function_exists('fw_get_db_settings_option')) {
				$email_from_name = fw_get_db_settings_option('email_from_name');
            }
			
			if( !empty( $email_from_name ) ) {
				return $email_from_name;
			} else{
				return $blogname;
			}
		}

		/**
		 * Email Content type
		 *
		 *
		 * @since    1.0.0
		 */
		public function docdirect_set_content_type(){
			return "text/html";
		}
		
		/**
		 * Get Email Header
		 *
		 * Return email header html
		 *
		 * @since    1.0.0
		 */
		 public function prepare_email_headers( $name = '' ) {
			global $current_user;
			ob_start();
			?>
            <table class="body-wrap" bgcolor="#f6f6f6" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0; padding: 20px;">
              <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
                <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
                <td class="container" bgcolor="#FFFFFF" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; display: block !important; max-width: 800px !important; Margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0;"><!-- content -->
                  
                  <div class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; display: block; max-width: 800px; margin: 0 auto; padding: 0;">
                    <table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0; padding: 0;">
                      <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
                        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
            <?php
			return ob_get_clean();	
		 }
	
		/**
		 * Get Email Footer
		 *
		 * Return email footer html
		 *
		 * @since    1.0.0
		 */
		 public function prepare_email_footers( $params ='' ) {
			global $current_user;
			ob_start();
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			?>
            
          <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;"><?php esc_html_e('Thanks, have a lovely day.','docdirect');?></p>
          <p style="border-top:1px dotted rgba(158, 158, 158, 0.73);font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 10px 0 0 0;">&copy;<?php echo date('Y');?><?php esc_html_e(' | All Rights Reserved','docdirect');?> <a href="<?php echo esc_url(home_url('/')); ?>" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; color: #348eda; margin: 0; padding: 0;"><?php echo esc_attr( $blogname );?></a></p></td>
                  </tr>
                </table>
              </div>
          
              <!-- /content --></td>
              <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
              </tr>
            </table>
            <!-- /body --><!-- footer -->
            <table class="footer-wrap" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; width: 100%; margin: 0; padding: 0;">
              <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
                <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
                <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
              </tr>
            </table>

            <?php
			return ob_get_clean();
		 }
		 
		
		
		/**
		 * @Registration
		 *
		 * @since 1.0.0
		 */
		 public function process_get_logo( $params = '' ) {
			 //Get Logo
			if (function_exists('fw_get_db_settings_option')) {
				$main_logo = fw_get_db_settings_option('main_logo');
				$email_logo = fw_get_db_settings_option('email_logo');
            }
			
			if (isset($email_logo['url']) && !empty($email_logo['url'])) {
				$logo = $email_logo['url'];
			} else if (isset($main_logo['url']) && !empty($main_logo['url'])) {
				$logo = $main_logo['url'];
			} else {
				$logo = get_template_directory_uri() . '/images/logo.png';
			}
			
			$logo	= docdirect_add_http($logo);
			return '<img width="100" src="'.esc_url( $logo ).'" alt="'.esc_html__('email-header','docdirect').'" />';
		 }
		 
		
		/**
		 * @Email Verification
		 *
		 * @since 1.0.0
		 */
		public function process_email_verification($params = '') {
			global $current_user;
			extract($params);

			$name = docdirect_get_username($user_identity);

			$subject_default = esc_html__('Account Verification', 'docdirect');
			$email_content_default = 'Hi %name%!<br/>

										<p><strong>Verify Your Account</strong></p>
										<p>You account has created with given below email address:</p>
										<p>Email Address: %account_email%</p>
										<p>If this was a mistake, just ignore this email and nothing will happen.</p>
										<p>To verifiy your account, click below link:</p>
										<p><a style="color: #fff; padding: 0 50px; margin: 0 0 15px; font-size: 20px; font-weight: 600; line-height: 60px; border-radius: 8px; background: #5dc560; vertical-align: top; display: inline-block; font-family: "Work Sans", Arial, Helvetica, sans-serif; text-decoration: none;" href="%link%">Verify</a></p><br />
										Sincerely,<br/>
										%logo%
								';

			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('ave_subject');
				$ave_content = fw_get_db_settings_option('ave_content');
			}

			//set defalt contents
			if (empty($ave_content)) {
				$ave_content = $email_content_default;
			}

			//set defalt subject
			if (empty($subject)) {
				$subject = $subject_default;
			}

			$logo		   = $this->process_get_logo();

			$ave_content = str_replace("%name%", $name, $ave_content); //Replace Name
			$ave_content = str_replace("%account_email%", $email, $ave_content); //Replace email
			$ave_content = str_replace("%link%", $verify_link, $ave_content); //Replace Link
			$ave_content = str_replace("%logo%", nl2br($logo), $ave_content); //Replace logo

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= '<p>' . $ave_content . '</p>';
			$body .= '</div>';
			$body .= '</div>';
			$body 			.= $this->prepare_email_footers();
			wp_mail($email, $subject, $body);
		}
		 
		 /**
		 * @Forgot Password
		 *
		 * @since 1.0.0
		 */
		 public function docdirect_reset_password_message( $message, $key ) {
			
			$content_default = 'Hey %name%!<br/>
										%message%<br/><br/>
										Sincerely,<br/>
										%logo%';
			
			$user_key	= sanitize_text_field( $_POST['user_login'] );
			if ( strpos($user_key, '@') ) {
				$user_data = get_user_by('email', trim($user_key));
			} else {
				$login = $user_key;
				$user_data = get_user_by('login', $login);
			}
		
			$user_login = $user_data->user_login;
			$forgot_content = esc_html__('The password for the following account has been requested to be reset:','docdirect'). "\r\n\r\n";
			$forgot_content .= network_site_url() . "\r\n\r\n";
			$forgot_content .= sprintf(__('Username: %s','docdirect'), $user_login) . "\r\n\r\n";
			$forgot_content .= esc_html__('If this message was sent in error, please ignore this email.','docdirect') . "\r\n\r\n";
			$forgot_content .= esc_html__('To reset your password, visit the following address:','docdirect');
			$forgot_content .= '(' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ")\r\n";
		
			
			$logo		   = $this->process_get_logo();
			$content_default = str_replace("%name%", nl2br($user_login), $content_default); //Replace Name
			$content_default = str_replace("%message%", nl2br($forgot_content), $content_default); //Replace content
			$content_default = str_replace("%logo%", nl2br($logo), $content_default); //Replace logo
			
			$body			 = '';
			$body			.= $this->prepare_email_headers($user_login);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$content_default.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			
			return $body;
		
		}
		
		/**
         * @Lost password
         *
         * @since 1.0.0
         */
        public function process_lostpassword_email($params = '') {
            global $current_user;
            extract($params);

            $name = $username;

            $subject = 'Forgot Password!';
            $email_content_default = 'Hey %name%!<br/>

									<p><strong>Lost Password reset</strong></p>
									<p>Someone requested that the password be reset for the following account:</p>
									<p>Email Address: %account_email%</p>
									<p>If this was a mistake, just ignore this email and nothing will happen.</p>
									<p>To reset your password, click reset link below:</p>
									<p><a href="%link%">Reset</a></p>
									Sincerely,<br/>
									';

            if (function_exists('fw_get_db_post_option')) {
                $subject = fw_get_db_settings_option('lp_subject');
                $lp_content = fw_get_db_settings_option('lp_content');
            }

            //set defalt contents
            if (empty($lp_content)) {
                $lp_content = $email_content_default;
            }

            $logo = $this->process_get_logo();

            $lp_content = str_replace("%name%", nl2br($name), $lp_content); //Replace Name
            $lp_content = str_replace("%account_email%", nl2br($email), $lp_content); //Replace email
            $lp_content = str_replace("%link%", nl2br($link), $lp_content); //Replace Link
            $lp_content = str_replace("%logo%", nl2br($logo), $lp_content); //Replace Logo

            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $admin_email = get_option('admin_email', 'info@themographics.com');

            $email_headers = "From: no-reply <info@no-reply.com>\r\n";
            $email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
            $email_headers .= "MIME-Version: 1.0\r\n";
            $email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $attachments = '';
            $body = '';
            $body .= $this->prepare_email_headers($name);


            $body .= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">' . $lp_content . '</p>';
            $body .= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

            $body .= $this->prepare_email_footers();
            wp_mail($email, $subject, $body);
            return true;
        }

		
		/**
		 * @Registration
		 *
		 * @since 1.0.0
		 */
		 public function process_registeration_email( $params = '' ) {
			global $current_user;
			
			extract( $params );
			
			$name	= $first_name.' '.$last_name;
			
			$subject = 'Thank you for registering!';
			$register_content_default = 'Hey %name%!<br/>

									Thanks for registering at DocDirect. You can now login to manage your account using the following credentials:
									<br/>
									Username: %username%<br/>
									Password: %password%<br/>
									
									Sincerely,<br/>
									%logo%';
				
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('register_subject');
				$register_content = fw_get_db_settings_option('register_content');
			}
			
			//set defalt contents
			if( empty( $register_content ) ){
				$register_content = $register_content_default;
			}
			
			$logo		   = $this->process_get_logo();

			$register_content = str_replace("%name%", nl2br($name), $register_content); //Replace Name
			$register_content = str_replace("%username%", nl2br($username), $register_content); //Replace username
			$register_content = str_replace("%password%", nl2br($password), $register_content); //Replace password
			$register_content = str_replace("%email%", nl2br($email), $register_content); //Replace email
			$register_content = str_replace("%logo%", nl2br($logo), $register_content); //Replace Logo
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.force_balance_tags( $register_content ).'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($email, $subject, $body);
			
			return true;
		 }
		 
		 /**
		 * @Registration admin email
		 *
		 * @since 1.0.0
		 */
		 public function process_registeration_admin_email( $params = '' ) {
			global $current_user;
			
			extract( $params );
			
			$name	= $first_name.' '.$last_name;
			$user_link	= get_author_posts_url( $user_identity );
			
			$subject = 'New Registration!';
			$register_content_default = 'Hey<br/>

									A new user "%username%" with email address "%email%" has registered on your website. Please login to check user detail.
									<br/>
									You can check user detail at: %link%<br/>
									
									Sincerely,<br/>
									%logo%';
			
			$admin_email = '';	
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('admin_register_subject');
				$admin_email = fw_get_db_settings_option('admin_email');
				$register_content = fw_get_db_settings_option('admin_register_content');
			}
			
			//set defalt contents
			if( empty( $register_content ) ){
				$register_content = $register_content_default;
			}
			
			$logo		   = $this->process_get_logo();

			$register_content = str_replace("%username%", nl2br($name), $register_content); //Replace username
			$register_content = str_replace("%email%", nl2br($email), $register_content); //Replace email
			$register_content = str_replace("%link%", ($user_link), $register_content); //Replace email
			$register_content = str_replace("%logo%", nl2br($logo), $register_content); //Replace Logo
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
			if( !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $admin_email) ) { 
				$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			}
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$register_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';;
			$body 			.= $this->prepare_email_footers();
			wp_mail($admin_email, $subject, $body);
			
			//return true;
		 }
		 
		 /**
		 * @Rating
		 *
		 * @since 1.0.0
		 */
		 public function process_rating_email( $params = '' ) {
			global $current_user;
			
			extract( $params );

			$subject = 'New rating received!';
			$rating_content_default = 'Hey %name%!<br/>

									A new rating has been received, Detail for rating is given below:
									<br/>
									Rating: %rating%<br/>
									Rating From: %rating_from%<br/>
									Reason: %reason%<br/>
									Comment: <br/>
									---------------------------------------<br/>
									You can view this at %link%
									
									Sincerely,<br/>
									%logo%';
				
			
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('rating_subject');
				$rating_content = fw_get_db_settings_option('rating_content');
			}
			
			//set defalt contents
			if( empty( $rating_content ) ){
				$rating_content = $rating_content_default;
			}
			
			$rating_from	= '<a href="'.$link_from.'"  alt="'.esc_html__('Rating from','docdirect').'">'.$username_from.'</a>';
			$link		   = '<a href="'.$link_to.'" alt="'.esc_html__('User link','docdirect').'">'.$link_to.'</a>';
			$logo		   = $this->process_get_logo();
			
			$rating_content = str_replace("%rating%", nl2br($rating), $rating_content); //Replace rating
			$rating_content = str_replace("%reason%", nl2br($reason), $rating_content); //Replace reason
			$rating_content = str_replace("%name%", nl2br($username_to), $rating_content); //Replace name
			
			$rating_content = str_replace("%rating_from%", nl2br($rating_from), $rating_content); //Replace email
			$rating_content = str_replace("%link%", nl2br($link), $rating_content); //Replace email
			$rating_content = str_replace("%logo%", nl2br($logo), $rating_content); //Replace logo
			
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);

			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$rating_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
			
			return true;
		 }
		 
		/**
		 * @Invitation
		 *
		 * @since 1.0.0
		 */
		 public function process_invitation_email( $params = '' ) {
			global $current_user;
			
			extract( $params );

			$subject = 'You have invitation for signup!';
			$invitation_content_default = 'Hi,<br/>

									%username% has invited you to signup at %link%. You have invitation message given below
									<br/>
									%message%
									<br/>
									Sincerely,<br/>
									%logo%';
				
			
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('invitation_subject');
				$invitation_content = fw_get_db_settings_option('invitation_content');
			}
			
			//set defalt contents
			if( empty( $invitation_content ) ){
				$invitation_content = $invitation_content_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$blogname 		= wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$link		   = '<a href="'.$link.'" alt="'.esc_html__('Site Link','docdirect').'">'.$blogname.'</a>';
			
			$invitation_content = str_replace("%username%", nl2br($username), $invitation_content); //Replace username
			$invitation_content = str_replace("%message%", nl2br($message), $invitation_content); //Replace message
			$invitation_content = str_replace("%link%", nl2br($link), $invitation_content); //Replace link
			$invitation_content = str_replace("%logo%", nl2br($logo), $invitation_content); //Replace logo
			
			
			
			
			$attachments	= '';
			$body			= '';
			$body			.= $this->prepare_email_headers('');

			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$invitation_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		 }
		 
		 
		 /**
		 * @Invoice
		 *
		 * @since 1.0.0
		 */
		 public function process_invoice_email( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$subject = 'Thank you for purchasing package!';
			$payment_content_default = 'Hey %name%!<br/>

									Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:
									<br/>
									Invoice ID: %invoice%<br/>
									Package Name: %package_name%<br/>
									Payment Amount: %amount%<br/>
									Payment Status: %status%<br/>
									Payment Method: %method%<br/>
									Purchase Date: %date%<br/>
									Expiry Date: %expiry%<br/>
									Address: %address%<br/>
									
									Sincerely,<br/>
									%logo%';
				
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('invoice_subject');
				$payment_content = fw_get_db_settings_option('payment_content');
			}
			
			//set defalt contents
			if( empty( $payment_content ) ){
				$payment_content = $payment_content_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$payment_content = str_replace("%name%", nl2br($name), $payment_content); //Replace Name
			$payment_content = str_replace("%invoice%", nl2br($invoice), $payment_content); //Replace invoice id
			$payment_content = str_replace("%amount%", nl2br($amount), $payment_content); //Replace amount
			$payment_content = str_replace("%package_name%", nl2br($package_name), $payment_content); //Replace amount
			$payment_content = str_replace("%status%", nl2br($status), $payment_content); //Replace status
			$payment_content = str_replace("%method%", nl2br($method), $payment_content); //Replace payment method
			$payment_content = str_replace("%date%", nl2br($date), $payment_content); //Replace expiry date
			$payment_content = str_replace("%expiry%", nl2br($expiry), $payment_content); //Replace password
			$payment_content = str_replace("%address%", nl2br($address), $payment_content); //Replace email
			$payment_content = str_replace("%logo%", nl2br($logo), $payment_content); //Replace logo

			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$payment_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($mail_to, $subject, $body);
		 }
		 
		/**
		 * @Appointment Confirmation
		 *
		 * @since 1.0.0
		 */
		 public function process_appointment_confirmation_email( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');
		
			$user_from	= get_post_meta($post_id, 'bk_user_from', true);
			$user_to	  = get_post_meta($post_id, 'bk_user_to', true);
			
			
			$customer_name	    = get_post_meta($post_id, 'bk_username', true);
			$email_to		    = get_post_meta($post_id, 'bk_useremail', true);
			$bk_booking_date	= get_post_meta($post_id, 'bk_booking_date', true);
			$bk_slottime		= get_post_meta($post_id, 'bk_slottime', true);
			$bk_service		    = get_post_meta($post_id, 'bk_service', true);
			$bk_category		= get_post_meta($post_id, 'bk_category', true);
			
			$user_from_data	= get_userdata($user_from);
			$user_to_data	  = get_userdata($user_to);
			$booking_services	= get_user_meta($user_to , 'booking_services' , true);
			$address			 = get_user_meta($user_to , 'address' , true);
			$service	= $booking_services[$bk_service]['title'];
			 
			$provider_name 		= docdirect_get_username($user_to);
			
			$time = explode('-',$bk_slottime);
			$appointment_date	= date_i18n($date_format,strtotime($bk_booking_date) );
			$appointment_time	= date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );
			
			
			$subject_default = esc_html__('Your Appointment Confirmation','docdirect');
			$booking_confirmed_default = 'Hey %customer_name%!<br/>

						This is confirmation that you have booked "%service%"<br/> with %provider%
						We will let your know regarding your booking soon.<br/><br/>
						
						Thank you for choosing our company.<br/><br/>
						
						Sincerely,<br/>
						%logo%';

			$subject = get_user_meta($user_to , 'confirmation_title' , true);
			$booking_confirmed = get_user_meta($user_to , 'booking_confirmed' , true);

			
			//set defalt subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}
			
			//set defalt contents
			if( empty( $booking_confirmed ) ){
				$booking_confirmed = $booking_confirmed_default;
			}
			
			 
			$provider	= '<a href="'.get_author_posts_url($user_to).'"  alt="'.esc_html__('provider','docdirect').'">'.$provider_name.'</a>';
			 
			$logo		   = $this->process_get_logo();
			
			$booking_confirmed = str_replace("%customer_name%", nl2br($customer_name), $booking_confirmed); //Replace Name
			$booking_confirmed = str_replace("%service%", nl2br($service), $booking_confirmed); //Replace service
			$booking_confirmed = str_replace("%provider%", nl2br($provider), $booking_confirmed); //Replace provider name
			$booking_confirmed = str_replace("%logo%", nl2br($logo), $booking_confirmed); //Replace logo
			
			
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($customer_name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$booking_confirmed.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
			
			return true;
		 }
		 
		 /**
		 * @Appointment Email To Professional User
		 *
		 * @since 1.0.0
		 */
		 public function process_appointment_confirmation_admin_email( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');
		
			$user_from	= get_post_meta($post_id, 'bk_user_from', true);
			$user_to	  = get_post_meta($post_id, 'bk_user_to', true);
			
			$customer_name	= get_post_meta($post_id, 'bk_username', true);
			$email_to		 = get_post_meta($post_id, 'bk_useremail', true);
			$bk_booking_date	= get_post_meta($post_id, 'bk_booking_date', true);
			$bk_slottime		= get_post_meta($post_id, 'bk_slottime', true);
			$bk_service		 = get_post_meta($post_id, 'bk_service', true);
			$bk_category		= get_post_meta($post_id, 'bk_category', true);
			
			$user_from_data	= get_userdata($user_from);
			$user_to_data	  = get_userdata($user_to);

			$booking_services	= get_user_meta($user_to , 'booking_services' , true);
			$address			 = get_user_meta($user_to , 'address' , true);
			$service			 = $booking_services[$bk_service]['title'];
			
			$time = explode('-',$bk_slottime);
			$appointment_date	= date_i18n($date_format,strtotime($bk_booking_date) );
			$appointment_time	= date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );
			 
			$user_from_name 		= docdirect_get_username($user_from);
			
			
			$subject_default = esc_html__('A new Appointment has received!','docdirect');
			$booking_confirmed_default = 'Hello<br/>

						This is confirmation that you have received a new appointment from %user_from%.<br/>
						To view detail please login to your dashboard and check it<br/><br/>
						
						Thank you<br/><br/>
						
						Sincerely,<br/>';
			
			
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('appointment_subject');
				$appointment_content = fw_get_db_settings_option('appointment_content');
			}
			
			//set defalt contents
			if( empty( $subject ) ){
				$subject = $subject_default;
			}
			 
			//set defalt contents
			if( empty( $appointment_content ) ){
				$booking_confirmed = $booking_confirmed_default;
			} else{
				$booking_confirmed = $appointment_content;
			}
			
			$logo		   = $this->process_get_logo();
			 
			$booking_confirmed = str_replace("%user_from%", nl2br($user_from_name), $booking_confirmed); //Replace provider name
			$booking_confirmed = str_replace("%logo%", nl2br($logo), $booking_confirmed); //Replace logo
			 
			
			
			$blogname 		= wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_to	= !empty( $user_to_data->user_email ) ? $user_to_data->user_email : $admin_email; 
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers('');
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$booking_confirmed.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
			return true;
		 }
		 
		/**
		 * @Appointment Approved
		 *
		 * @since 1.0.0
		 */
		 public function process_appointment_approved_email( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');
		
			$user_from	= get_post_meta($post_id, 'bk_user_from', true);
			$user_to	  = get_post_meta($post_id, 'bk_user_to', true);
			
			$customer_name	= get_post_meta($post_id, 'bk_username', true);
			$email_to		 = get_post_meta($post_id, 'bk_useremail', true);
			$bk_booking_date	= get_post_meta($post_id, 'bk_booking_date', true);
			$bk_slottime		= get_post_meta($post_id, 'bk_slottime', true);
			$bk_service		 = get_post_meta($post_id, 'bk_service', true);
			$bk_category		= get_post_meta($post_id, 'bk_category', true);
			
			$user_from_data	= get_userdata($user_from);
			$user_to_data	  = get_userdata($user_to);
			$booking_services	= get_user_meta($user_to , 'booking_services' , true);
			$address			 = get_user_meta($user_to , 'address' , true);
			$service	= $booking_services[$bk_service]['title'];
			 
			$provider_name 		= docdirect_get_username($user_to);
			
			$time = explode('-',$bk_slottime);
			$appointment_date	= date_i18n($date_format,strtotime($bk_booking_date) );
			$appointment_time	= date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );
			
			$subject_default = 'Your Appointment Approved';
			$booking_approved_default = 'Hey %customer_name%!<br/>

						This is confirmation that your booking regarding "%service%" with %provider% has approved.<br/>
						
						We are waiting you at "%address%" on %appointment_date% at %appointment_time%.<br/><br/><br/>
						
						Sincerely,<br/>
						%logo%';
				
			if (function_exists('fw_get_db_post_option')) {
				$subject = get_user_meta($user_to , 'approved_title' , true);
				$booking_approved = get_user_meta($user_to , 'booking_approved' , true);
			}
			
			//set defalt contents
			if( empty( $subject ) ){
				$subject = $subject_default;
			}
			
			//set defalt title
			if( empty( $booking_approved ) ){
				$booking_approved = $booking_approved_default;
			}
			
			$provider	= '<a href="'.get_author_posts_url($user_to).'"  alt="'.esc_html__('provider','docdirect').'">'.$provider_name.'</a>';
			$logo		   = $this->process_get_logo();
			
			$booking_approved = str_replace("%customer_name%", nl2br($customer_name), $booking_approved); //Replace Name
			$booking_approved = str_replace("%service%", nl2br($service), $booking_approved); //Replace service
			$booking_approved = str_replace("%provider%", nl2br($provider), $booking_approved); //Replace provider
			$booking_approved = str_replace("%address%", nl2br($address), $booking_approved); //Replace address
			$booking_approved = str_replace("%appointment_date%", nl2br($appointment_date), $booking_approved); //Replace date
			$booking_approved = str_replace("%appointment_time%", nl2br($appointment_time), $booking_approved); //Replace time
			$booking_approved = str_replace("%logo%", nl2br($logo), $booking_approved); //Replace logo

			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($customer_name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$booking_approved.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body	.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
			
			return true;
		 }
		 
		/**
		 * @Appointment Cancelled
		 *
		 * @since 1.0.0
		 */
		 public function process_appointment_cancelled_email( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');
		
			$user_from	= get_post_meta($post_id, 'bk_user_from', true);
			$user_to	  = get_post_meta($post_id, 'bk_user_to', true);
			
			$customer_name		= get_post_meta($post_id, 'bk_username', true);
			$email_to		 	= get_post_meta($post_id, 'bk_useremail', true);
			$bk_booking_date	= get_post_meta($post_id, 'bk_booking_date', true);
			$bk_slottime		= get_post_meta($post_id, 'bk_slottime', true);
			$bk_service		 	= get_post_meta($post_id, 'bk_service', true);
			$bk_category		= get_post_meta($post_id, 'bk_category', true);
			
			$user_from_data		 = get_userdata($user_from);
			$user_to_data	  	 = get_userdata($user_to);
			$booking_services	 = get_user_meta($user_to , 'booking_services' , true);
			$address			 = get_user_meta($user_to , 'address' , true);
			$service			 = $booking_services[$bk_service]['title'];
			$provider_name 		 = docdirect_get_username($user_to);
			
			$time = explode('-',$bk_slottime);
			$appointment_date	= date_i18n($date_format,strtotime($bk_booking_date) );
			$appointment_time	= date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );
			
			$subject_default = 'Your Appointment Cancelled';
			$booking_cancelled_default = 'Hi %customer_name%!<br/>

						 This is confirmation that your booking regarding "%service%" with %provider% has cancelled.<br/>
						
						 We are very sorry to process your booking right now.<br/><br/>
						
						 Sincerely,<br/>
						 %logo%';
				
			if (function_exists('fw_get_db_post_option')) {
				$subject = get_user_meta($user_to , 'cancelled_title' , true);
				$booking_cancelled = get_user_meta($user_to , 'booking_cancelled' , true);
			}
			
			//set defalt contents
			if( empty( $subject ) ){
				$subject = $subject_default;
			}
			
			//set defalt title
			if( empty( $booking_cancelled ) ){
				$booking_cancelled = $booking_cancelled_default;
			}
			
			$provider	= '<a href="'.get_author_posts_url($user_to).'"  alt="'.esc_html__('provider','docdirect').'">'.$provider_name.'</a>';
			$logo		   = $this->process_get_logo();
			
			$booking_cancelled = str_replace("%customer_name%", nl2br($customer_name), $booking_cancelled); //Replace Name
			$booking_cancelled = str_replace("%service%", nl2br($service), $booking_cancelled); //Replace Service
			$booking_cancelled = str_replace("%provider%", nl2br($provider), $booking_cancelled); //Replace provider
			$booking_cancelled = str_replace("%logo%", nl2br($logo), $booking_cancelled); //Replace logo
			
			
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($customer_name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$booking_cancelled.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body 			.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
			
			return true;
		 }
		 
		 
		 /**
		 * @User Contact Form 
		 *
		 * @since 1.0.0
		 */
		 public function process_contact_user_email( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$email_subject = !empty( $email_subject ) ? $email_subject : "(" . $bloginfo . ")".esc_html__('Contact Form Received','docdirect');
			
			$contact_default = 'Hello,<br/>

						A person has contact you, description of message is given below.<br/><br/>
						Subject : %subject%<br/>
						Name : %name%<br/>
						Email : %email%<br/>
						Phone Number : %phone%<br/>
						Message : %message%<br/><br/><br/>
						
						Sincerely,<br/>';
			
			
			if (function_exists('fw_get_db_post_option')) {
				$contact_subject = fw_get_db_settings_option('contact_subject');
				$contact_content = fw_get_db_settings_option('contact_content');
			}
			
			//set contents
			if( empty( $contact_content ) ){
				$contact_content = $contact_default;
			}
			
			//email title
			if( !empty( $contact_subject ) ){
				$email_subject = $contact_subject;
			} else{
				$email_subject = $email_subject;
			}
			 
			$contact_content = str_replace("%subject%", nl2br($subject), $contact_content); //Replace Subject
			$contact_content = str_replace("%name%", nl2br($name), $contact_content); //Replace Name
			$contact_content = str_replace("%email%", nl2br($email), $contact_content); //Replace email
			$contact_content = str_replace("%phone%", nl2br($phone), $contact_content); //Replace phone
			$contact_content = str_replace("%message%", nl2br($message), $contact_content); //Replace message
		
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$contact_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body	.= $this->prepare_email_footers();
			wp_mail($email_to, $email_subject, $body);
			
			return true;
		 }
		 
		/**
		 * @Site Contact Form 
		 *
		 * @since 1.0.0
		 */
		 public function process_contact_form( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$email_subject = !empty( $email_subject ) ? $email_subject : "(" . $bloginfo . ")".esc_html__('Contact Form Received','docdirect');
			$contact_default = 'Hello,<br/>

						A person has contact you, description of message is given below.<br/><br/>
						Subject : %subject%<br/>
						Name : %name%<br/>
						Email : %email%<br/>
						Phone Number : %phone%<br/>
						Message : %message%<br/><br/><br/>
						
						Sincerely,<br/>';
			
			
			if (function_exists('fw_get_db_post_option')) {
				$contact_subject = fw_get_db_settings_option('contact_subject');
				$contact_content = fw_get_db_settings_option('contact_content');
			}
			
			//set defalt contents
			if( empty( $contact_content ) ){
				$contact_content = $contact_default;
			}
			
			//set defalt title
			if( !empty( $contact_subject ) ){
				$email_subject = $contact_subject;
			} else{
				$email_subject = $email_subject;
			}
			 
			$contact_content = str_replace("%subject%", nl2br($subject), $contact_content); //Replace Subject
			$contact_content = str_replace("%name%", nl2br($name), $contact_content); //Replace Name
			$contact_content = str_replace("%email%", nl2br($email), $contact_content); //Replace email
			$contact_content = str_replace("%phone%", nl2br($phone), $contact_content); //Replace phone
			$contact_content = str_replace("%message%", nl2br($message), $contact_content); //Replace message
		
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			
			$email_headers = "From: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "Reply-To: no-reply <info@no-reply.com>\r\n";
			$email_headers .= "MIME-Version: 1.0\r\n";
			$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);
			
			
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$contact_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body	.= $this->prepare_email_footers();
			wp_mail($email_to, $email_subject, $body);
			
			return true;
		 }
		
		/**
		 * @Claim admin email
		 *
		 * @since 1.0.0
		 */
		 public function process_claim_admin_email( $params = '' ) {
			global $current_user;			
			extract( $params );
			
			$subject = 'A user has claimed!';
			$claim_content_default = 'Hi,<br/>
										%claimed_user% has claimed by %claimed_by%
										<br/><br/>
										Message is given below.
										<br/>
											%message%
										<br/>
										Sincerely,<br/>
									';
			
			$admin_email = '';	
			if (function_exists('fw_get_db_post_option')) {
				$subject = fw_get_db_settings_option('claim_subject');
				$admin_email = fw_get_db_settings_option('claim_admin_email');
				$claim_content = fw_get_db_settings_option('claim_content');
			}
			
			//set defalt contents
			if( empty( $claim_content ) ){
				$claim_content = $claim_content_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$claimed_user	= '<a href="'.$claimed_user_link.'"  alt="'.esc_html__('Claimed','docdirect').'">'.$claimed_user_name.'</a>';
			$claimed_by	= '<a href="'.$claimed_by_link.'"  alt="'.esc_html__('Claimed By','docdirect').'">'.$claimed_by_name.'</a>';
			 
			$claim_content = str_replace("%claimed_user%", nl2br($claimed_user), $claim_content); //Claimed user
			$claim_content = str_replace("%claimed_by%", nl2br($claimed_by), $claim_content); //Claimed by
			$claim_content = str_replace("%message%", nl2br($message), $claim_content); //Message
			$claim_content = str_replace("%logo%", nl2br($logo), $claim_content); //Replace Logo
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
			if( !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $admin_email) ) { 
				$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			}
			
			$attachments	  = '';
			$body			 = '';
			$body			.= $this->prepare_email_headers($name);
	
			$body			.= ' <p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">'.$claim_content.'</p>';
			$body			.= '<table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: auto !important; Margin: 0 0 10px; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
<td style="font-family: \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; margin: 0; padding: 0;" align="center" bgcolor="#348eda" valign="top">
                
                </td>
              </tr></table>';

			$body	.= $this->prepare_email_footers();
			wp_mail($admin_email, $subject, $body);
		 }
		
		 /**
		 * @Send email to provider to inform that a question has posted.
		 *
		 * @since 1.0.0
		 */
		public function process_question_email($params = '') {
			global $current_user;

			extract($params);
			$username = docdirect_get_username($user_id);

			$subject_default = esc_html__('A question has posted on your profile', 'docdirect');
			$qa_default = 'Hi %name%,<br/>
								A person has posted aquestion at your profile, question is given below.<br/><br/>

								%question%

								<br/>
								%logo%';

			if (function_exists('fw_get_db_settings_option')) {
				$qa_subject = fw_get_db_settings_option('qa_subject');
				$qa_content = fw_get_db_settings_option('qa_content');
			}

			//set defalt contents
			if (empty($qa_content)) {
				$qa_content = $qa_default;
			}

			//set defalt title
			if (empty($qa_subject)) {
				$qa_subject = $subject_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$qa_content = str_replace("%name%", $username, $qa_content); //Replace Name
			$qa_content = str_replace("%question%", $question_title, $qa_content); //Replace email
			$qa_content = str_replace("%logo%", nl2br($logo), $qa_content); //Replace Logo

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= '<p>' . $qa_content . '</p>';
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();

			$user_data = get_userdata($user_id);
			$email_to  = $user_data->user_email;
			wp_mail($email_to, $qa_subject, $body);                 
		}

		/**
		 * @Send email to provider to inform that a question has posted.
		 *
		 * @since 1.0.0
		 */
		public function process_answer_email($params = '') {
			global $current_user;

			extract($params);
			$username = docdirect_get_username($answer_author);
			$name 	  = docdirect_get_username($question_author);

			$subject_default = esc_html__('New answer posted on your question', 'docdirect');
			$answers_default = 'Hi %name%,<br/>
							A person with name "%username%" has posted an answers on your question ( %question% ), To view all answers please follow given below link<br/><br/>

							%link%

							<br/>
							%signature%';


			if (function_exists('fw_get_db_settings_option')) {
				$answers_subject = fw_get_db_settings_option('answer_subject');
				$answers_content = fw_get_db_settings_option('answer_content');
			}

			//set defalt contents
			if (empty($answers_content)) {
				$answers_content = $answers_default;
			}

			//set defalt title
			if (empty($answers_subject)) {
				$answers_subject = $subject_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$answers_content = str_replace("%name%", $name, $answers_content); //Replace Name
			$answers_content = str_replace("%username%", $username, $answers_content); //Replace Name
			$answers_content = str_replace("%question%", $question_title, $answers_content); //Replace question title
			$answers_content = str_replace("%link%", $link, $answers_content); //Replace link
			$answers_content = str_replace("%logo%", nl2br($logo), $answers_content); //Replace Logo

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= '<p>' . $answers_content . '</p>';
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();

			$user_data = get_userdata($question_author);
			$email_to  = $user_data->user_email;
			wp_mail($email_to, $answers_subject, $body);                 
		}
		
		/**
		 * @Send email to admin when any user delete account
		 *
		 * @since 1.0.0
		 */
		public function delete_user_account($params = '') {
			global $current_user;

			extract($params);
			$username = docdirect_get_username($user_identity);
			

			$subject_default = esc_html__('Delete Account', 'docdirect');
			$delete_content_default = 'Hi,<br/>

								An existing user has deleted account due to following reason: 
								<br/>
								%reason%
								<br/>

								Sincerely,<br/>
								%logo%';


			if (function_exists('fw_get_db_settings_option')) {
				$delete_subject= fw_get_db_settings_option('delete_subject');
				$delete_content = fw_get_db_settings_option('delete_content');
				$admin_email = fw_get_db_settings_option('delete_email');
			}

			//set defalt contents
			if (empty($delete_content)) {
				$delete_content = $delete_content_default;
			}

			//set defalt title
			if (empty($delete_subject)) {
				$delete_subject = $subject_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$delete_content = str_replace("%username%", $username, $delete_content); //Replace Name
			$delete_content = str_replace("%reason%", $reason, $delete_content); //Replace question title
			$delete_content = str_replace("%email%", $email, $delete_content); //Replace link
			$delete_content = str_replace("%logo%", nl2br($logo), $delete_content); //Replace Logo
			
			if( empty( $admin_email ) ) { 
				$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			}
			
			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= '<p>' . $delete_content . '</p>';
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();
			wp_mail($admin_email, $delete_subject, $body);                 
		}
		
		/**
		 * @Send email to admin to approve article
		 *
		 * @since 1.0.0
		 */
		public function approve_article($params = '') {
			global $current_user;
			extract($params);

			$subject_default = esc_html__('Article Needs Approval', 'docdirect');
			$article_content_default = 'Hi,<br/>

										A provider has publish an article with the name "%article_name%" and needs approval. below is link to approve.
										<br/>
										<br/>
										<a style="color: #fff; padding: 0 50px; margin: 0 0 15px; font-size: 20px; font-weight: 600; line-height: 60px; border-radius: 8px; background: #5dc560; vertical-align: top; display: inline-block;" href="%link%">Approve</a>

										Sincerely,<br/>
										%logo%';


			if (function_exists('fw_get_db_settings_option')) {
				$article_subject= fw_get_db_settings_option('article_subject');
				$article_content = fw_get_db_settings_option('article_content');
				$admin_email = fw_get_db_settings_option('article_email');
			}

			//set defalt contents
			if (empty($article_content)) {
				$article_content = $article_content_default;
			}

			//set defalt title
			if (empty($article_subject)) {
				$article_subject = $subject_default;
			}
			
			$logo		   = $this->process_get_logo();
			
			$article_content = str_replace("%link%", $link, $article_content); //Replace link
			$article_content = str_replace("%article_name%", $article_name, $article_content); 
			$article_content = str_replace("%logo%", nl2br($logo), $article_content); //Replace Logo
			
			if( empty( $admin_email ) ) { 
				$admin_email	= get_option( 'admin_email' ,'info@themographics.com' );
			}
			
			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= '<p>' . $article_content . '</p>';
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();
			wp_mail($admin_email, $article_subject, $body);                 
		}

	}
	new DocDirectProcessEmail();
}