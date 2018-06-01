<?php
/**
 * @Class footers
 *
 */
if (!class_exists('docdirect_footers')) {

    class docdirect_footers {

        function __construct() {
            add_action('docdirect_prepare_footers', array(&$this, 'docdirect_prepare_footer'));
        }

        /**
         * @Prepare Top Strip
         * @return {}
         */
        public function docdirect_prepare_footer() {
			$footer_copyright		= '&copy;'.date('Y').esc_html__(' | All Rights Reserved ','docdirect').get_bloginfo();
			$enable_widget_area	= '';
			$enable_registration		= '';
			$enable_login		= '';
			
			if(function_exists('fw_get_db_settings_option')) {
				$footer_type	= fw_get_db_settings_option('footer_type', $default_value = null);
				$enable_widget_area	= fw_get_db_settings_option('enable_widget_area', $default_value = null);
				$footer_copyright 	= fw_get_db_settings_option('footer_copyright', $default_value = null);
				$enable_registration = fw_get_db_settings_option('registration', $default_value = null);
				$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
			}
			?>
			</main>
            <?php 
				if( isset( $footer_type['gadget'] ) && $footer_type['gadget'] === 'footer_v2' ){
					$this->docdirect_prepare_footer_v2();
					
					//Forgot password 
					if (!is_user_logged_in()) {
						do_shortcode('[user_lostpassword]'); 
					}
					
					//Check if Auth Key is not empty then call the hook
					if(!empty($_GET['activation_key'])){
						$verify_key = $_GET['activation_key'];
						do_action('docdirect_welcome_page', $verify_key);
					}

					//Reset Model
					if ( !empty($_GET['key']) 
						&& 
						( isset( $_GET['action'] ) && $_GET['action'] == "reset_pwd" )
						&&
						( !empty( $_GET['login'] ) )
					) {
					   do_action('docdirect_reset_password_form'); 
					}
					
				} else {?>
                <footer id="footer" class="tg-haslayout footer-v1">
                    <?php if(isset( $enable_widget_area ) && $enable_widget_area ==='on' ) {?>
                        <div class="tg-threecolumn">
                            <div class="container">
                                <div class="row">
                                    <?php if ( is_active_sidebar( 'footer-column-1' ) ) { ?>
                                        <div class="col-sm-4">
                                            <div class="tg-footercol"><?php dynamic_sidebar( 'footer-column-1' ); ?></div>
                                        </div>
                                    <?php } ?>
                                    <?php if ( is_active_sidebar( 'footer-column-2' ) ) { ?>
                                        <div class="col-sm-4">
                                            <div class="tg-footercol"><?php dynamic_sidebar( 'footer-column-2' ); ?></div>
                                        </div>
                                    <?php } ?>
                                    <?php if ( is_active_sidebar( 'footer-column-3' ) ) { ?>
                                        <div class="col-sm-4">
                                            <div class="tg-footercol"><?php dynamic_sidebar( 'footer-column-3' ); ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    <?php if(isset( $footer_copyright ) && $footer_copyright !='' ) {?>
                    <div class="tg-footerbar tg-haslayout">
                        <div class="tg-copyrights">
                            <p><?php echo  force_balance_tags( $footer_copyright );?></p>
                        </div>
                    </div>
                    <?php }?>
                </footer>
                <?php 
				//Account Verifications Model
				if ( !empty($_GET['key']) && !empty($_GET['verifyemail']) ) {
					do_action('docdirect_verify_user_account');
				}
				
				//Registration modal		
				if( ( isset( $enable_login ) && $enable_login === 'enable' ) 
                            ||  ( isset( $enable_registration ) && $enable_registration === 'enable' ) 
                      ) {
                    do_shortcode('[user_authentication]'); //Code Moved To due to plugin territory
                }
						
				//Forgot password 
				if (!is_user_logged_in()) {
					do_shortcode('[user_lostpassword]'); 
				}
						
				//Check if Auth Key is not empty then call the hook
				if(!empty($_GET['activation_key'])){
					$verify_key = $_GET['activation_key'];
					do_action('docdirect_welcome_page', $verify_key);
				}

				//Reset Model
				if ( !empty($_GET['key']) 
					&& 
					( isset( $_GET['action'] ) && $_GET['action'] == "reset_pwd" )
					&&
					( !empty( $_GET['login'] ) )
				) {
				   do_action('docdirect_reset_password_form'); 
				}
			}?>
		</div>
        <?php 
		}
		
		/**
         * @Prepare Top Strip
         * @return {}
         */
        public function docdirect_prepare_footer_v2() {
			$footer_copyright		= '&copy;'.date('Y').esc_html__(' | All Rights Reserved ','docdirect').get_bloginfo();
			$enable_widget_area	= '';
			$enable_registration		= '';
			$enable_login		= '';
			$footer_type		= '';
			
			if(function_exists('fw_get_db_settings_option')) {
				$footer_type	= fw_get_db_settings_option('footer_type', $default_value = null);
				$enable_widget_area	= fw_get_db_settings_option('enable_widget_area', $default_value = null);
				$footer_copyright 	= fw_get_db_settings_option('footer_copyright', $default_value = null);
				$enable_registration = fw_get_db_settings_option('registration', $default_value = null);
				$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
			}

			?>
            <footer id="doc-footer" class="doc-footer doc-haslayout footer-v2">
              <?php if( !empty( $footer_type['footer_v2']['newsletter']) && $footer_type['footer_v2']['newsletter'] === 'enable' ){?>
              <div class="doc-newsletter">
                <div class="container">
                  <div class="row">
                    <div class="col-sm-offset-1 col-sm-10">
                      <?php if( !empty( $footer_type['footer_v2']['newsletter_title'] ) || $footer_type['footer_v2']['newsletter_desc'] ){?>
                      <div class="col-sm-5">
                        <div class="doc-newslettercontent">
                          <?php if( !empty( $footer_type['footer_v2']['newsletter_title'] ) ){?><h2><?php echo esc_attr( $footer_type['footer_v2']['newsletter_title'] );?></h2> <?php }?>
                          <?php if( $footer_type['footer_v2']['newsletter_desc'] ){?>
                          <div class="doc-description">
                            <p><?php echo esc_attr( $footer_type['footer_v2']['newsletter_desc'] );?></p>
                          </div>
                           <?php }?>
                        </div>
                      </div>
                      <?php }?>
                      <div class="col-sm-7">
                        <form class="doc-formtheme doc-formnewsletter">
                          <fieldset>
                            <div class="form-group">
                              <input type="email" name="email" class="form-control" placeholder="<?php esc_attr_e('Enter Email Here','docdirect');?>">
                            </div>
                            <a class="doc-btnsubscribe subscribe_me" href="javascript:;"><i class="fa fa-paper-plane"></i></a>
                          </fieldset>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php }?>
              <?php if(isset( $enable_widget_area ) && $enable_widget_area ==='on' ) {?>
                <div class="doc-footermiddlebar">
                    <div class="container">
                        <div class="row">
                            <div class="doc-fcols">
                                <?php if ( is_active_sidebar( 'footer-column-1' ) ) { ?>
                                    <div class="col-sm-4 col-xs-12">
                                       <div class="doc-fcol"><?php dynamic_sidebar( 'footer-column-1' ); ?></div>
                                    </div>
                                <?php } ?>
                                <?php if ( is_active_sidebar( 'footer-column-2' ) ) { ?>
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="doc-fcol"><?php dynamic_sidebar( 'footer-column-2' ); ?></div>
                                    </div>
                                <?php } ?>
                                <?php if ( is_active_sidebar( 'footer-column-3' ) ) { ?>
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="doc-fcol"><?php dynamic_sidebar( 'footer-column-3' ); ?></div>
                                    </div>
                                <?php } ?>
                             </div>
                        </div>
                    </div>
                </div>
              <?php }?>
              <div class="doc-footerbottombar">
                <div class="container">
                  <?php if( !empty( $footer_type['footer_v2']['footer_menu']) && $footer_type['footer_v2']['footer_menu'] === 'enable' ){?>
                  <nav class="doc-footernav">
                    <?php docdirect_headers::docdirect_prepare_navigation('footer-menu', '', '', '0'); ?>
                  </nav>
                  <?php }?>
                  <?php if(isset( $footer_copyright ) && $footer_copyright !='' ) {?><p class="doc-copyrights"><?php echo  force_balance_tags( $footer_copyright );?></p><?php }?>
                </div>
              </div>
            </footer>
            <?php 
				//Account Verifications Model
				if ( !empty($_GET['key']) && !empty($_GET['verifyemail']) ) {
					do_action('docdirect_verify_user_account');
				}
				
				//Registration modal
				if( ( isset( $enable_login ) && $enable_login === 'enable' ) 
                            ||  ( isset( $enable_registration ) && $enable_registration === 'enable' ) 
                      ) {
                    do_shortcode('[user_authentication]'); //Code Moved To due to plugin territory
                }
		}
		
    }

    new docdirect_footers();
}