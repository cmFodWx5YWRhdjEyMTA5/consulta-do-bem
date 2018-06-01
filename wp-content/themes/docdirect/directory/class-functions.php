<?php
/**
* @ Docdirect Functions
* @ return {}
* @ Version 1.0.0
*/

if( !class_exists( 'DocDirect_Scripts' ) ) {
	class DocDirect_Scripts{
		protected static $instance = null;
			
		public function __construct() {
			//Do something
		}
		
		
		/**
		 * @Returns the *Singleton* instance of this class.
		 * @return Singleton The *Singleton* instance.
		 */
        public static function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }
		
		/**	
		 * @Profile Menu
		 * @Returns Dashoboard Menu
		 */
        public function docdirect_profile_menu( $menu_type="dashboard" ) {
			global $current_user, $wp_roles,$userdata,$post;
			$reference = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : $reference	= '';
			$mode = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
			$user_identity	= $current_user->ID;
			$user_role = get_user_meta('roles',$user_identity );
			
			$url_identity	= $user_identity;
			if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
				$url_identity	= $_GET['identity'];
			}

			$dir_profile_page = '';
			if (function_exists('fw_get_db_settings_option')) {
                $dir_profile_page  = fw_get_db_settings_option('dir_profile_page', $default_value = null);
				$directory_type	   = get_user_meta( $user_identity, 'directory_type', true);
				$article_switch    = fw_get_db_post_option($directory_type, 'articles', true);
            }

			$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
			$db_user_type	= get_user_meta( $url_identity, 'user_type', true);
			
			ob_start();
			
			if ( is_user_logged_in() ){ 
            if( isset( $menu_type ) && $menu_type === 'dashboard' ) {?>
            <div class="tg-widget tg-widget-accordions">
                <h3><?php esc_html_e('Dashboard','docdirect');?></h3>
                <ul class="docdirect-menu">
                    <?php if( $url_identity ==  $user_identity ) { ?>
                        <li class="<?php echo ( $reference === 'dashboard' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'dashboard', $user_identity); ?>"><?php esc_html_e('My Profile','docdirect');?></a></li>
                        <?php if ( class_exists('bbPress') ) {?>
                        	<li><a href="<?php echo esc_url( bbp_get_user_profile_url( $user_identity ) ); ?>"><?php esc_html_e('Go to forum profile','docdirect');?></a></li>
                        <?php }?>
                        <li class="<?php echo ( $reference === 'settings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'settings', $user_identity); ?>"><?php esc_html_e('Profile Settings','docdirect');?></a></li>
						<?php 
						if( apply_filters('docdirect_do_check_teams',$user_identity ) === true
							&& apply_filters('docdirect_is_setting_enabled',$user_identity,'team' ) === true
						){?>
                        	<li class="<?php echo ( $reference === 'teams' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'teams', $user_identity); ?>"><?php esc_html_e('Manage Teams','docdirect');?></a></li>
                        <?php }?>
                        <?php if( isset( $article_switch ) && $article_switch === 'enable' ){?>
							<?php if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) { ?>
								<li class="tg-privatemessages tg-hasdropdown <?php echo ( $reference === 'articles' ? 'tg-active' : ''); ?>">
									<a id="tg-btntoggle" class="tg-btntoggle" href="javascript:">
										<span><?php esc_html_e('Manage Articles', 'docdirect'); ?></span>
										<em class="tg-totalmessages"><?php echo intval(docdirect_get_total_articles_by_user($user_identity)); ?></em>
									</a>
									<ul class="tg-emailmenu">
										<li class="<?php echo ( $mode === 'listing' ? 'tg-active' : ''); ?>">
											<a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $user_identity, '', 'listing'); ?>">
												<span><?php esc_html_e('Article listing', 'docdirect'); ?></span>
											</a>
										</li>
										<li class="<?php echo ( $mode === 'add' ? 'tg-active' : ''); ?>">
											<a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $user_identity, '', 'add'); ?>">
												<span><?php esc_html_e('Add New Article', 'docdirect'); ?></span>
											</a>
										</li>
									</ul>
								</li>
							<?php } ?>
                      	<?php } ?>
                        <?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true){?>
							<?php if( apply_filters('docdirect_is_setting_enabled',$user_identity,'favorite' ) === true ){?>
								<li class="<?php echo ( $reference === 'wishlist' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'wishlist', $user_identity); ?>"><?php esc_html_e('Favourites','docdirect');?></a></li>
							<?php }?>
                        <?php } else{?>
							<li class="<?php echo ( $reference === 'wishlist' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'wishlist', $user_identity); ?>"><?php esc_html_e('Favourites','docdirect');?></a></li>
						<?php }?>
                       
                        <?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
                            <li class="<?php echo ( $reference === 'invoices' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'invoices', $user_identity); ?>"><?php esc_html_e('Invoices & Packages','docdirect');?></a></li>
                        <?php }?>
                        <?php 
						if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true
							&& apply_filters('docdirect_is_setting_enabled',$user_identity,'schedules' ) === true
						){?>
                            <li class="<?php echo ( $reference === 'schedules' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'schedules', $user_identity); ?>"><?php esc_html_e('My Schedules','docdirect');?></a></li>
                        <?php }?>
                        <?php 
						if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true 
						    && apply_filters('docdirect_is_setting_enabled',$user_identity,'appointments' ) === true
						){?>
                            <li class="<?php echo ( $reference === 'bookings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'bookings', $user_identity); ?>"><?php esc_html_e('Booking Listings','docdirect');?></a></li>
                        <?php }?>
                        <?php 
							if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true 
							    && apply_filters('docdirect_is_setting_enabled',$user_identity,'appointments' ) === true
						){?>
                            <li class="<?php echo ( $reference === 'booking-schedules' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'booking-schedules', $user_identity); ?>"></i><?php esc_html_e('Booking Schedules','docdirect');?></a></li>
                        <?php }?>
                        <?php 
							if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true 
							    && apply_filters('docdirect_is_setting_enabled',$user_identity,'appointments' ) === true
						){?>
                            <li class="<?php echo ( $reference === 'booking-settings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'booking-settings', $user_identity); ?>"><?php esc_html_e('Booking Settings','docdirect');?></a></li>
                        <?php }?>
                        
                        <li class="<?php echo ( $reference === 'security' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'security', $user_identity); ?>"><?php esc_html_e('Security Settings','docdirect');?></a></li>
                        
						<?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
                        <li class="<?php echo ( $reference === 'privacy-settings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'privacy-settings', $user_identity); ?>"><?php esc_html_e('Privacy Settings','docdirect');?></a></li>
                        <?php }?>
                        
                        <?php if ( is_user_logged_in() ) {?>
                            <li><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e('Logout','docdirect');?></a></li>
                        <?php }?>
                        
                        
                    <?php } else{ ?>
                        <li class=""><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'dashboard', $user_identity); ?>"><?php esc_html_e('Go to your profile','docdirect');?></a></li>
                    <?php }?>
                </ul>
            </div>
            <?php } else{
					$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user_identity) //size width,height
						);
				?>
            	
                <ul>
                    <li class="<?php echo ( $reference === 'dashboard' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'dashboard', $user_identity); ?>"><i class="fa fa-line-chart"></i><?php esc_html_e('Profile','docdirect');?></a></li>
                    <?php if ( class_exists('bbPress') ) {?>
						<li><a href="<?php echo esc_url( bbp_get_user_profile_url( $user_identity ) ); ?>"><i class="fa fa-comments" aria-hidden="true"></i><?php esc_html_e('Go to forum profile','docdirect');?></a></li>
					<?php }?>
                    <li class="<?php echo ( $reference === 'settings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'settings', $user_identity); ?>"><i class="fa fa-gears"></i><?php esc_html_e('Profile Settings','docdirect');?></a></li>
                    <?php 
					if( apply_filters('docdirect_do_check_teams',$user_identity ) === true 
						&& apply_filters('docdirect_is_setting_enabled',$user_identity,'team' ) === true
					){?>
                    	<li class="<?php echo ( $reference === 'teams' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'teams', $user_identity); ?>"><i class="fa fa-user"></i><?php esc_html_e('Manage Teams','docdirect');?></a></li>
                    <?php }?>
                    <?php if( isset( $article_switch ) && $article_switch === 'enable' ){?>
						<?php if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) { ?>
							<li class="tg-privatemessages tg-hasdropdown <?php echo ( $reference === 'articles' ? 'tg-active' : ''); ?>">
								<a id="tg-btntoggle" class="tg-btntoggle" href="javascript:;">
									<i class="fa fa-pencil"></i>
									<?php esc_html_e('Manage Articles', 'docdirect'); ?>
									<em class="tg-totalmessages"><?php echo intval(docdirect_get_total_articles_by_user($user_identity)); ?></em>
								</a>
								<ul class="tg-emailmenu">
									<li class="<?php echo ( $mode === 'listing' ? 'tg-active' : ''); ?>">
										<a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $user_identity, '', 'listing'); ?>">
											<span><?php esc_html_e('Article listing', 'docdirect'); ?></span>
										</a>
									</li>
									<li class="<?php echo ( $mode === 'add' ? 'tg-active' : ''); ?>">
										<a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $user_identity, '', 'add'); ?>">
											<span><?php esc_html_e('Add New Article', 'docdirect'); ?></span>
										</a>
									</li>
								</ul>
							</li>
						<?php } ?>
                  	<?php } ?>
                    <?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true){?>
                    	<?php if( apply_filters('docdirect_is_setting_enabled',$user_identity,'favorite' ) === true ){?>
                    		<li class="<?php echo ( $reference === 'wishlist' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'wishlist', $user_identity); ?>"><i class="fa fa-heart"></i><?php esc_html_e('Favourites','docdirect');?></a></li>
                    	<?php }?>
                    <?php } else{?>
						<li class="<?php echo ( $reference === 'wishlist' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'wishlist', $user_identity); ?>"><i class="fa fa-heart"></i><?php esc_html_e('Favourites','docdirect');?></a></li>
					<?php }?>
                    
                    
					<?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
                        <li class="<?php echo ( $reference === 'invoices' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'invoices', $user_identity); ?>"><i class="fa fa-money"></i><?php esc_html_e('Invoices & Packages','docdirect');?></a></li>
                    <?php }?>
                    <?php 
					if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true
					   && apply_filters('docdirect_is_setting_enabled',$user_identity,'schedules' ) === true
					){?>
                        <li class="<?php echo ( $reference === 'schedules' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'schedules', $user_identity); ?>"><i class="fa fa-list"></i><?php esc_html_e('My Schedules','docdirect');?></a></li>
                    <?php }?>

                    <?php 
					if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true 
						&& apply_filters('docdirect_is_setting_enabled',$user_identity,'appointments' ) === true
					){?>
                        <li class="<?php echo ( $reference === 'bookings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'bookings', $user_identity); ?>"><i class="fa fa-book"></i><?php esc_html_e('Booking Listings','docdirect');?></a></li>
                    <?php }?>
                    <?php 
					if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true 
						&& apply_filters('docdirect_is_setting_enabled',$user_identity,'appointments' ) === true 
					){?>
                        <li class="<?php echo ( $reference === 'booking-schedules' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'booking-schedules', $user_identity); ?>"><i class="fa fa-calendar-check-o"></i><?php esc_html_e('Booking Schedules','docdirect');?></a></li>
                    <?php }?>
                    <?php 
					if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true 
						&& apply_filters('docdirect_is_setting_enabled',$user_identity,'appointments' ) === true
					){?>
                        <li class="<?php echo ( $reference === 'booking-settings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'booking-settings', $user_identity); ?>"><i class="fa fa-cog"></i><?php esc_html_e('Booking Settings','docdirect');?></a></li>
                    <?php }?>
                    
                    <li class="<?php echo ( $reference === 'security' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'security', $user_identity); ?>"><i class="fa fa-lock"></i><?php esc_html_e('Security Settings','docdirect');?></a></li>
                    <?php if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
                    
                    <li class="<?php echo ( $reference === 'privacy-settings' ? 'active':'');?>"><a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'privacy-settings', $user_identity); ?>"><i class="fa fa-eye"></i><?php esc_html_e('Privacy Settings','docdirect');?></a></li>
                    
                    <?php }?>
					
                       <?php if ( is_user_logged_in() ) {?>
                        <li><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><i class="fa fa-sign-in"></i><?php esc_html_e('Logout','docdirect');?></a></li>
                    <?php }?>
                </ul>
			<?php
				}
			}
			echo ob_get_clean();
        }
		
		/**
		 * @Generate Menu Link
		 * @Returns 
		 */
		public static  function docdirect_profile_menu_link($profile_page = '', $slug = '', $user_identity = '',$return=false,$mode = '', $id = ''){
			if( !isset($profile_page) or $profile_page == '' ){
				$permalink = home_url('/').'?author='.$user_identity;
			} else {
				$query_arg['ref'] = urlencode($slug);

                //mode
                if (!empty($mode)) {
                    $query_arg['mode'] = urlencode($mode);
                }
				
                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

                $query_arg['identity'] = urlencode($user_identity);

                $permalink = add_query_arg(
                        $query_arg, esc_url(get_permalink($profile_page)
                        )
                );

			}
			if( $return ){
				return esc_url( $permalink );
			} else{
				echo esc_url( $permalink );
			}
			
		}
		
		/**
		 * @Generate Menu Link
		 * @Returns 
		 */
		public function docdirect_get_avatar(){
			global $current_user, $wp_roles,$userdata,$post;
			$reference = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : $reference	= '';
			$user_identity	= $current_user->ID;
			$current_date = date('Y-m-d H:i:s');
			
			$user_identity	= $user_identity;
			if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
				$user_identity	= $_GET['identity'];
			}
			
			$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>270,'height'=>270), $user_identity) //size width,height
						);
			
			$featured_date	= get_user_meta($user_identity, 'user_featured', true);
			
			$featured_string    = $featured_date;
			$current_string		= strtotime( $current_date );
			$tagline   		    = get_user_meta( $user_identity, 'tagline', true);
			$first_name   		   = get_user_meta( $user_identity, 'first_name', true);
			$last_name   		   = get_user_meta( $user_identity, 'last_name', true);
			$display_name   		   = get_user_meta( $user_identity, 'display_name', true);
			
			if( !empty( $first_name ) || !empty( $last_name ) ){
				$username	= $first_name.' '.$last_name;
			} else{
				$username	= $display_name;
			}
		?>
			<div class="tg-widget tg-widget-doctor">
				<figure class="tg-docprofile-img">
					<figcaption>
						<h4><a target="_blank" title="<?php esc_html_e('View Profile','docdirect');?>" href="<?php echo get_author_posts_url($user_identity); ?>"><?php echo esc_attr( $username );?></a></h4>
						<?php if ( isset( $tagline ) && !empty( $tagline ) ) :  ?>
							<span><?php echo esc_attr($tagline); ?></span>
						<?php endif; ?>
					</figcaption>
                    <?php docdirect_get_wishlist_button($current_user->ID,true);?>
					<?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
                        <?php docdirect_get_featured_tag(true);?>
                    <?php }?>
                    <?php docdirect_get_verified_tag(true,$current_user->ID);?>
                    <a><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('Avatar','docdirect');?>"  /></a>
				</figure>
			</div>
		<?php
		}

		/**
		 * @get user info
		 * @return 
		 */
		public function docdirect_do_process_userinfo(){
			global $current_user, $wp_roles,$userdata,$post;
			$reference = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : $reference	= '';
			$user_identity	= $current_user->ID;
			
			if (isset($main_logo['url']) && !empty($main_logo['url'])) {
				$logo = $main_logo['url'];
			} else {
				$logo = get_template_directory_uri() . '/images/logo.png';
			}
			?>
			<div class="tg-dashboard tg-haslayout">
				<div class="tg-docprofilechart tg-haslayout">
					<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth">
						<div class="row">
							<div class="tg-docinfo tg-haslayout">
								<div class="tg-box">
									<div class="tg-heading-border tg-small">
										<h3><?php esc_html_e('welcome, ','docdirect');?><?php echo get_user_meta('display_name',$user_identity,true );?></h3>
									</div>
									<div class="tg-description">
										<p><?php echo get_user_meta('description',$user_identity,true );?></p>
									</div>
									<div class="tg-bottominfo tg-haslayout">
										<div class="tg-regardsleft"> <em><?php esc_html_e('Regards,','docdirect');?></em> <strong><?php echo esc_attr(get_bloginfo('description')); ?></strong> </div>
										<div class="tg-regardsright"> <strong class="logo"><img class="<?php echo esc_attr( $image_classes );?>" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>"></strong> </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth">
						<div class="row">
							<div class="tg-support">
								<div class="tg-heading-border tg-small">
									<h3><?php esc_html_e('get support','docdirect');?></h3>
								</div>
								<ul class="tg-doccontactinfo">
									<?php if ( get_user_meta('address',$user_identity,true) ) :  ?>
										<li><i class="fa fa-map-marker"></i><address><?php echo get_user_meta('address',$user_identity,true); ?></address></li>
									<?php endif; ?>
									<?php if ( get_user_meta('phone',$user_identity) ) :  ?>
										<li><i class="fa fa-phone"></i><a href="tel:<?php echo get_user_meta('phone',$user_identity,true); ?>"><?php echo get_user_meta('phone',$user_identity,true); ?></a></li>
									<?php endif; ?>
									<?php if ( get_user_meta('email',$user_identity) ) :  ?>
										<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo get_user_meta('email',$user_identity,true); ?>?Subject=Hello%20again" target="_top"><?php echo get_user_meta('email',$user_identity,true); ?></a></li>
									<?php endif; ?>
									<?php if ( get_user_meta('fax',$user_identity) ) :  ?>
										<li><i class="fa fa-fax"></i><span><?php echo get_user_meta('fax',$user_identity,true); ?></span></li>
									<?php endif; ?>
								</ul>
								<div class="profile-social-icons">
									<?php if ( get_user_meta('facebook',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('facebook',$user_identity,true); ?>"><i class="fa fa-facebook"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('twitter',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('twitter',$user_identity,true); ?>"><i class="fa fa-twitter"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('linkedin',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('linkedin',$user_identity,true); ?>"><i class="fa fa-linkedin"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('pinterest',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('pinterest',$user_identity,true); ?>"><i class="fa fa-pinterest"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('google_plus',$user_identity) ) :  ?>
										<span><a href="<?php echo get_user_meta('google_plus',$user_identity,true); ?>"><i class="fa fa-google-plus"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('tumblr',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('tumblr',$user_identity,true); ?>"><i class="fa fa-tumblr"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('instagram',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('instagram',$user_identity,true); ?>"><i class="fa fa-instagram"></i></a></span>
									<?php endif; ?>
									<?php if ( get_user_meta('skype',$user_identity,true) ) :  ?>
										<span><a href="<?php echo get_user_meta('skype',$user_identity,true); ?>"><i class="fa fa-skype"></i></a></span>
									<?php endif; ?>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		
	}
	new DocDirect_Scripts();
}
?>