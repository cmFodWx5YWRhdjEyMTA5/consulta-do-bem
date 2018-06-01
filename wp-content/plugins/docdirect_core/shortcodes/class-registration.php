<?php
/**
 * @File Type: Registration & Login
 * @return {}
 */
if( ! class_exists('SC_Authentication') ) {
	
	class SC_Authentication {

		public function __construct() {
			add_shortcode('user_authentication', array(&$this,'shortCodeCallBack' ) );
			add_shortcode('user_authentication_page', array(&$this,'user_authentication_page' ) );
		}
		
		/**
		 * @User Authentication
		 * @return {HTML}
		 **/
		 public function shortCodeCallBack() {
			$enable_resgistration	= '';
			$enable_login		= '';
			$captcha_settings		= '';
			$terms_link		= '';
			
			if(function_exists('fw_get_db_settings_option')) {
				$enable_resgistration = fw_get_db_settings_option('registration', $default_value = null);
				$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
				$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
				$terms_link = fw_get_db_settings_option('terms_link', $default_value = null);
			}
			?>
            <div class="modal fade tg-user-modal" tabindex="-1" role="dialog">
				<div class="tg-modal-content">
					<ul class="tg-modaltabs-nav" role="tablist">
						<li role="presentation" class="active"><a href="#tg-signin-formarea" aria-controls="tg-signin-formarea" role="tab" data-toggle="tab"><?php esc_html_e('Sign In','docdirect_core');?></a></li>
						<li role="presentation"><a href="#tg-signup-formarea" class="trigger-signup-formarea" aria-controls="tg-signup-formarea" role="tab" data-toggle="tab"><?php esc_html_e('Sign Up','docdirect_core');?></a></li>
					</ul>
					<div class="tab-content tg-haslayout">
						<div role="tabpanel" class="tab-pane tg-haslayout active" id="tg-signin-formarea">
							<?php if( $enable_login == 'enable' ) {
								if( apply_filters('docdirect_is_user_logged_in','check_user') === false ) {
									
									//Demo Ready
									$demo_username	= '';
									$demo_pass		= '';
									if( isset( $_SERVER["SERVER_NAME"] ) && $_SERVER["SERVER_NAME"] === 'themographics.com' ){
										$demo_username	= 'demo';
										$demo_pass		= 'demo';
									}
									
									if(function_exists('fw_get_db_settings_option')){
										$site_key = fw_get_db_settings_option('site_key');
									} else {
										$site_key = '';
									}
									
									?>
                                    <form class="tg-form-modal tg-form-signin do-login-form">
                                        <fieldset>
                                            <div class="form-group">
                                                <input type="text" name="username" value="<?php echo esc_attr( $demo_username );?>" placeholder="<?php esc_html_e('Username/Email Address','docdirect_core');?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" value="<?php echo esc_attr( $demo_pass );?>" class="form-control" placeholder="<?php esc_html_e('Password','docdirect_core');?>">
                                            </div>
                                            <div class="form-group tg-checkbox">
                                                <label>
                                                    <input type="checkbox" class="form-control">
                                                    <?php esc_html_e('Remember Me','docdirect_core');?>
                                                </label>
                                                <a class="tg-forgot-password" href="javascript:;">
                                                    <i><?php esc_html_e('Forgot Password', 'docdirect_core'); ?></i>
                                                    <i class="fa fa-question-circle"></i>
                                                </a>
                                            </div>
                                            <?php 
											if( isset( $captcha_settings ) 
													&& $captcha_settings === 'enable' 
												) {
											?>
                                                <div class="domain-captcha">
                                                    <div id="recaptcha_signin"></div>
                                                </div>
                                            <?php }?>
                                            <button class="tg-btn tg-btn-lg do-login-button"><?php esc_html_e('LOGIN now','docdirect_core');?></button>
                                        </fieldset>
                                    </form>
                                 <?php }
								} else{?>
								<div class="tg-form-modal">
									<p class="alert alert-info theme-notification"><?php esc_html_e('Sign In is disabled by administrator','docdirect_core');?></p>
								</div>
							<?php }?>
						</div>
						<div role="tabpanel" class="tab-pane tg-haslayout" id="tg-signup-formarea">
							<?php 
							if( $enable_resgistration == 'enable') {
								if( apply_filters('docdirect_is_user_logged_in','check_user') === false ) {?>
								<form class="tg-form-modal tg-form-signup do-registration-form">
								<fieldset>
									<div class="form-group">
										<div class="tg-radiobox user-selection active-user-type">
											<input type="radio" checked="checked" name="user_type" value="professional" id="professional">
											<label for="professional"><?php esc_html_e('Professional','docdirect_core');?></label>
										</div>
										<div class="tg-radiobox user-selection active-user-type visitor-type">
											<input type="radio" name="user_type" value="visitor" id="visitor">
											<label for="visitor"><?php esc_html_e('Visitor','docdirect_core');?></label>
										</div>
									</div>
									<div class="form-group user-types"> 
										<span class="select">
											<select name="directory_type">
												<option value="0"><?php esc_html_e('Select User Type','docdirect_core');?></option>
													<?php 
													$posts_array = array();
													$args = array('posts_per_page' => "-1", 
														'post_type' => 'directory_type', 
														'order' => 'DESC', 
														'orderby' => 'ID', 
														'post_status' => 'publish', 
														'ignore_sticky_posts' => 1,
														'suppress_filters'  => false,
													);
											
													$posts_query = get_posts($args);
													if( isset( $posts_query ) && !empty( $posts_query ) ) {
													foreach ($posts_query as $direcotry) {?>
														
														<option value="<?php echo intval( $direcotry->ID );?>"><?php echo esc_attr( $direcotry->post_title );?></option>
													<?php }}?>
											</select>
										</span> 
									</div>
									<div class="form-group">
										<input type="text" name="username" class="form-control" placeholder="<?php esc_html_e('Username','docdirect_core');?>">
									</div>
									<div class="form-group">
										<input type="email" name="email" class="form-control" placeholder="<?php esc_html_e('Email','docdirect_core');?>">
									</div>
									<div class="form-group">
										<input type="text" name="first_name" placeholder="<?php esc_html_e('First Name','docdirect_core');?>" class="form-control">
									</div>
									<div class="form-group">
										<input type="text" name="last_name" placeholder="<?php esc_html_e('Last Name','docdirect_core');?>" class="form-control">
									</div>
									<div class="form-group">
										<input type="text" name="phone_number" class="form-control" placeholder="<?php esc_html_e('Phone Number','docdirect_core');?>">
									</div>
									<div class="form-group">
										<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password','docdirect_core');?>">
									</div>
									<div class="form-group">
										<input type="password" name="confirm_password" class="form-control" placeholder="<?php esc_html_e('Confirm Password','docdirect_core');?>">

									</div>
									<div class="form-group tg-checkbox">
										<input name="terms"  type="hidden" value="0"  />
										<label>
                                        	<input name="terms" class="form-control" type="checkbox">
											<?php if( !empty( $terms_link ) ){?>
												<a target="_blank" href="<?php echo esc_url( $terms_link );?>" title="<?php esc_attr_e('Terms','docdirect_core');?>">
											<?php esc_html_e(' I agree with the terms and conditions','docdirect_core');?></a>
                                            <?php } else {?>
                                            	<?php esc_html_e(' I agree with the terms and conditions','docdirect_core');?>
                                            <?php }?>
                                        </label>
									</div>
                                    <?php 
									if( isset( $captcha_settings ) 
											&& $captcha_settings === 'enable' 
										) {
									?>
										<div class="domain-captcha">
											<div id="recaptcha_signup"></div>
										</div>
									<?php }?>
                                    
									<button class="tg-btn tg-btn-lg  do-register-button" type="button"><?php esc_html_e('Create an Account','docdirect_core');?></button>
								</fieldset>
							</form>
                            <?php }
							} else{?>
								<div class="tg-form-modal">
									<p class="alert alert-info theme-notification"><?php esc_html_e('Registration is disabled by administrator','docdirect_core');?></p>
								</div>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
            <?php
		}
		
		/**
		 * @User Authentication page
		 * @return {HTML}
		 **/
		 public function user_authentication_page() {
			$enable_resgistration	= '';
			$enable_login		= '';
			$captcha_settings		= '';
			$terms_link		= '';
			$dir_profile_page = '';
			
			if(function_exists('fw_get_db_settings_option')) {
				$enable_resgistration = fw_get_db_settings_option('registration', $default_value = null);
				$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
				$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
				$terms_link = fw_get_db_settings_option('terms_link', $default_value = null);
				$dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
			}

			$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
			?>
			<div class="authentication-page-template">
                <?php 
				if( is_user_logged_in() ) {
					global $current_user;	
					
					$username	= $current_user->first_name.' '.$current_user->last_name;
					?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="doc-myaccount-content">
                            <p><?php esc_html_e('Hello','docdirect_core');?> <strong><?php echo esc_attr( $username );?></strong> (<?php esc_html_e('not','docdirect_core');?> <?php echo esc_attr( $username );?>? <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e('Sign out','docdirect_core');?></a>)</p>
                            
                            <p><?php esc_html_e('You can view your dashboard here','docdirect_core');?>&nbsp;<a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'dashboard', $current_user->ID); ?>"><?php esc_html_e('View','docdirect_core');?></a></p>
                            
                        </div>
                     </div>
				<?php } else{?>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="Login-page-wrap tg-haslayout">
                            <div class="doc-section-heading"><h2><?php esc_html_e('Sign In','docdirect_core');?></h2><span><?php esc_html_e('Sign In with your username and password','docdirect_core');?></span></div>
                            <?php if( $enable_login == 'enable' ) {
                                if( apply_filters('docdirect_is_user_logged_in','check_user') === false ) {
                                    
                                    //Demo Ready
                                    $demo_username	= '';
                                    $demo_pass		= '';
                                    if( isset( $_SERVER["SERVER_NAME"] ) 
                                        && $_SERVER["SERVER_NAME"] === 'themographics.com' ){
                                        $demo_username	= 'demo';
                                        $demo_pass		= 'demo';
                                    }
                                    
                                    if(function_exists('fw_get_db_settings_option')){
                                        $site_key = fw_get_db_settings_option('site_key');
                                    } else {
                                        $site_key = '';
                                    }
            
                                    $forgot_passwrod	= wp_lostpassword_url('/');
                                    
                                    ?>
                                    <form class="tg-form-modal tg-form-signin do-login-form">
                                        <fieldset>
                                            <div class="form-group">
                                                <input type="text" name="username" value="<?php echo esc_attr( $demo_username );?>" placeholder="<?php esc_html_e('User Name','docdirect_core');?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" value="<?php echo esc_attr( $demo_pass );?>" class="form-control" placeholder="<?php esc_html_e('Password','docdirect_core');?>">
                                            </div>
                                            <div class="form-group tg-checkbox">
                                                <label>
                                                    <input type="checkbox" class="form-control">
                                                    <?php esc_html_e('Remember Me','docdirect_core');?>
                                                </label>
                                                <a class="tg-forgot-password" href="javascript:;">
                                                    <i><?php esc_html_e('Forgot Password','docdirect_core');?></i>
                                                    <i class="fa fa-question-circle"></i>
                                                </a>	
                                            </div>
                                            <?php 
                                            if( isset( $captcha_settings ) 
                                                    && $captcha_settings === 'enable' 
                                                ) {
                                            ?>
                                                <div class="domain-captcha">
                                                    <div id="recaptcha_signin"></div>
                                                </div>
                                            <?php }?>
                                            <button class="tg-btn tg-btn-lg do-login-button"><?php esc_html_e('LOGIN now','docdirect_core');?></button>
                                        </fieldset>
                                    </form>
                                 <?php }
                                } else{?>
                                <div class="tg-form-modal">
                                    <p class="alert alert-info theme-notification"><?php esc_html_e('Sign In is disabled by administrator','docdirect_core');?></p>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="registration-page-wrap tg-haslayout">
                            <div class="doc-section-heading"><h2><?php esc_html_e('Sign Up','docdirect_core');?></h2><span><?php esc_html_e('Sign Up as Vistor or professional.','docdirect_core');?></span></div>
                            <?php 
                            if( $enable_resgistration == 'enable') {
                                if( apply_filters('docdirect_is_user_logged_in','check_user') === false ) {?>
                                <form class="tg-form-modal tg-form-signup do-registration-form">
                                <fieldset>
                                    <div class="form-group">
                                        <div class="tg-radiobox user-selection active-user-type">
                                            <input type="radio" checked="checked" name="user_type" value="professional" id="professional">
                                            <label for="professional"><?php esc_html_e('Professional','docdirect_core');?></label>
                                        </div>
                                        <div class="tg-radiobox user-selection active-user-type visitor-type">
                                            <input type="radio" name="user_type" value="visitor" id="visitor">
                                            <label for="visitor"><?php esc_html_e('Visitor','docdirect_core');?></label>
                                        </div>
                                    </div>
                                    <div class="form-group user-types"> 
                                        <span class="select">
                                            <select name="directory_type">
                                                <option value="0"><?php esc_html_e('Select User Type','docdirect_core');?></option>
                                                    <?php 
                                                    $posts_array = array();
                                                    $args = array('posts_per_page' => "-1", 
                                                        'post_type' => 'directory_type', 
                                                        'order' => 'DESC', 
                                                        'orderby' => 'ID', 
                                                        'post_status' => 'publish', 
                                                        'ignore_sticky_posts' => 1,
                                                        'suppress_filters'  => false,
                                                    );
                                            
                                                    $posts_query = get_posts($args);
                                                    if( isset( $posts_query ) && !empty( $posts_query ) ) {
                                                    foreach ($posts_query as $direcotry) {?>
                                                        
                                                        <option value="<?php echo intval( $direcotry->ID );?>"><?php echo esc_attr( $direcotry->post_title );?></option>
                                                    <?php }}?>
                                            </select>
                                        </span> 
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control" placeholder="<?php esc_html_e('Username','docdirect_core');?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="<?php esc_html_e('Email','docdirect_core');?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="first_name" placeholder="<?php esc_html_e('First Name','docdirect_core');?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="last_name" placeholder="<?php esc_html_e('Last Name','docdirect_core');?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="phone_number" class="form-control" placeholder="<?php esc_html_e('Phone Number','docdirect_core');?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password','docdirect_core');?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confirm_password" class="form-control" placeholder="<?php esc_html_e('Confirm Password','docdirect_core');?>">
            
                                    </div>
                                    <div class="form-group tg-checkbox">
                                        <input name="terms"  type="hidden" value="0"  />
                                        <label>
                                            <input name="terms" class="form-control" type="checkbox">
                                            <?php if( !empty( $terms_link ) ){?><a target="_blank" href="<?php echo esc_url( $terms_link );?>" title="<?php esc_attr_e('Terms','docdirect_core');?>">
                                            <?php esc_html_e(' I agree with the terms and conditions','docdirect_core');?></a>
                                            <?php } else {?>
                                                <?php esc_html_e(' I agree with the terms and conditions','docdirect_core');?>
                                            <?php }?>
                                        </label>
                                    </div>
                                    <?php 
                                    if( isset( $captcha_settings ) 
                                            && $captcha_settings === 'enable' 
                                        ) {
                                    ?>
                                        <div class="domain-captcha">
                                            <div id="recaptcha_signup"></div>
                                        </div>
                                    <?php }?>
                                    
                                    <button class="tg-btn tg-btn-lg  do-register-button" type="button"><?php esc_html_e('Create an Account','docdirect_core');?></button>
                                </fieldset>
                            </form>
                            <?php }
                            } else{?>
                                <div class="tg-form-modal">
                                    <p class="alert alert-info theme-notification"><?php esc_html_e('Registration is disabled by administrator','docdirect_core');?></p>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                <?php }?> 
			</div>		
            <?php
		}
	}
	
  	new SC_Authentication();	
}