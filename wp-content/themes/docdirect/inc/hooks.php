<?php
/**
 * Hook For Google Fonts
 */
 
/**
 * @Mailchimp List
 * @return 
 */
if (!function_exists('docdirect_mailchimp_list')) {
    function docdirect_mailchimp_list() {
		$mailchimp_list[]='';
		$mailchimp_list[0] = 'Select List';
		$mailchimp_option	= '';
		if (!function_exists('fw_get_db_settings_option')) {
			$mailchimp_option	= '';
		} else {
			$default_value	= 'b1c640ffabcea48f48530987ffdae147-us11';

			$mailchimp_option	= fw_get_db_settings_option('mailchimp_key', $default_value);
			if( isset( $mailchimp_option ) && !empty( $mailchimp_option ) ) {
				$mailchimp_option = $mailchimp_option;
			} else{
				$mailchimp_option = '';
			}
		}

		if($mailchimp_option <> ''){
			if( class_exists('Docdirect_MailChimp') ) {
				$mailchim_obj = new Docdirect_MailChimp();
				$lists = $mailchim_obj->docdirect_mailchimp_list($mailchimp_option);
				if(is_array($lists) && isset($lists['data'])){
					foreach($lists['data'] as $list){
						if(!empty($list['name'])) :
							$mailchimp_list[$list['id']] = $list['name'];
						endif;
					}
				}
			}
		}
	
		return $mailchimp_list;
	}
}

/**
 * @Section wraper before
 * @return 
 */
if (!function_exists('docdirect_prepare_section_wrapper_before')) {
	function docdirect_prepare_section_wrapper_before(){
		echo '<div class="main-page-wrapper tg-haslayout">';
	}
	
	add_action('docdirect_prepare_section_wrapper_before','docdirect_prepare_section_wrapper_before');
}
/**
 * @Section wraper after
 * @return 
 */
if (!function_exists('docdirect_prepare_section_wrapper_after')) {
	function docdirect_prepare_section_wrapper_after(){
			echo '</div>';
	}
	
	add_action('docdirect_prepare_section_wrapper_after','docdirect_prepare_section_wrapper_after');
}


/**
 * @Next Prevoius Links
 * @return 
 */
if ( ! function_exists( 'docdirect_do_preocess_next_previous_link' ) ) {
 function docdirect_do_preocess_next_previous_link($post_type = 'post'){
		global $post;
		$prevous_post_id 		= $next_post_id = '';
		$post_type 		= get_post_type($post->ID);
		$count_posts	= wp_count_posts( "$post_type" )->publish;
		$args = array(
		   'posts_per_page'  => -1,
		   'order'           => 'ASC',
		   'post_type'       => "$post_type",
		); 
		
		$all_posts = get_posts( $args );
		
		$ids 		 = array();
		foreach ($all_posts as $current_post) {
		   $ids[] = $current_post->ID;
		}
		$current_index = array_search($post->ID, $ids);
		
		if(isset($ids[$current_index-1])){
			$prevous_post_id = $ids[$current_index-1];
		} 
		
		if(isset($ids[$current_index+1])){
			$next_post_id = $ids[$current_index+1];
		} 
		
		?>
		<div class="buttons">
			<?php
			if (isset($prevous_post_id) && !empty($prevous_post_id) && $prevous_post_id >=0 ) {
			   ?>
			   <a class="prev-post" href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><?php esc_html_e('Previous Post','docdirect');?></a>
		   <?php
			}
			if (isset($next_post_id) && !empty($next_post_id) ) {?>
				<a class="next-post" href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><?php esc_html_e('Next Post','docdirect');?></a>
			<?php }?>
		</div>
		<?php 
	 wp_reset_postdata();
  }
  add_action('do_preocess_next_previous_link','docdirect_do_preocess_next_previous_link');
}


/**
 * @User Profile Social Icons
 * @return 
 */

if ( ! function_exists( 'docdirect_user_social_mehthods' ) ) {
	
	function docdirect_user_social_mehthods( $userfields ) {
		$userfields['user_address']		= esc_html__('User Address','docdirect');
		$userfields['zip']			= esc_html__('Zip Code','docdirect');
		$userfields['tagline']		= esc_html__('Tag Line','docdirect');	
		$userfields['phone_number']	= esc_html__('Phone Number','docdirect');
		$userfields['fax']			= esc_html__('Fax','docdirect');
		$userfields['facebook']		= esc_html__('Facebook','docdirect');	
		$userfields['twitter']		= esc_html__('Twitter','docdirect');
		$userfields['linkedin']		= esc_html__('Linkedin','docdirect');
		$userfields['pinterest']	= esc_html__('Pinterest','docdirect');
		$userfields['google_plus']	= esc_html__('Google Plus','docdirect');
		$userfields['instagram']	= esc_html__('Instagram','docdirect');
		$userfields['tumblr']		= esc_html__('Tumblr','docdirect');
		$userfields['skype']		= esc_html__('Skype','docdirect');
		return $userfields;
	}
	add_filter('user_contactmethods', 'docdirect_user_social_mehthods', 10, 1);
}



/**
 * @User Profile Social Icons
 * @return 
 */

if ( ! function_exists( 'docdirect_replace_reply_link_class' ) ) {
	
	function docdirect_replace_reply_link_class($class){
		$class = str_replace("class='comment-reply-link", "class='tg-btn", $class);
		return $class;
	}
	add_filter('comment_reply_link', 'docdirect_replace_reply_link_class');
}


/**
 * @Add Body Class
 * @return 
 */
if (!function_exists('docdirect_content_classes')) {
	function docdirect_content_classes( $classes ) {
		$post_name	= docdirect_get_post_name();
		if( ( isset( $maintenance ) && $maintenance == 'enable' && !is_user_logged_in() ) || $post_name == "coming-soon" ){
			$classes[] = 'comming-soon-bg ';
		} else{
			if( is_home() || is_front_page() ){
				$classes[] = 'home ';
			}
		}
		
		$classes[] = docdirect_get_post_name('true'); //Demo Purpose
		
		//RTL
		if( is_rtl() ){
			$classes[] = 'rtl-enabled ';
		}
		
		//Header Classes
		if( is_home() || is_front_page() ) {
			$classes[] = 'mec-home ';
		} else{
			$classes[] = 'tg-inner-header ';
		}
		return $classes;
	
	}
	add_filter( 'body_class', 'docdirect_content_classes', 1 );
}


/**
 * @Validaet Email
 * @return {}
 */
if ( ! function_exists( 'docdirect_isValidEmail' ) ) {
	function docdirect_isValidEmail($email){ 
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function docdirect_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	
	if(function_exists('fw_get_db_settings_option')){
		$maintenance = fw_get_db_settings_option('maintenance');
	} else {
		$maintenance = '';
	}
	
	$post_name	= docdirect_get_post_name();
	if( ( isset( $maintenance ) && $maintenance == 'enable' && !is_user_logged_in() ) || $post_name === "coming-soon" ){
		$classes[] = 'tg-comming-soon';
	}
	
	return $classes;
}
add_filter( 'body_class', 'docdirect_body_classes' );

/**
 * @Product Image 
 * @return {}
 */
if (!function_exists('_prepare_post_thumbnail')) {
	function _prepare_post_thumbnail($object, $atts){
		extract(shortcode_atts(array(
			"width" => '300',
			"height" => '300',
			),
		$atts));
		
		if( isset( $object ) && !empty( $object ) ){
			return $object;
		} else{
			$object_url	= get_template_directory_uri().'/images/user'.$width.'x'.$height.'.jpg';
			return '<img width="'.$width.'" height="'.$height.'" src="'.esc_url( $object_url ).'" alt="'.esc_html__('Placeholder','docdirect').'">';
		}
	}
	add_filter( '_prepare_post_thumbnail', '_prepare_post_thumbnail', 10, 3 );
}

/**
 * @Product Image 
 * @return {}
 */
if (!function_exists('docdirect_get_user_avatar_fallback')) {
	function docdirect_get_user_avatar_fallback($object, $atts=array()){
		extract(shortcode_atts(array(
			"width" => '300',
			"height" => '300',
			),
		$atts));
		
		if( isset( $object ) 
			&& !empty( $object ) 
			&& $object != NULL 
		){
			return $object;
		} else{
			return get_template_directory_uri().'/images/user'.$width.'x'.$height.'.jpg';
		}
	}
	
	add_filter( 'docdirect_get_user_avatar_filter', 'docdirect_get_user_avatar_fallback', 10, 3 );
}

/**
 * @Product Image 
 * @return {}
 */
if (!function_exists('docdirect_get_single_image_fallback')) {
	function docdirect_get_single_image_fallback($object, $atts=array()){
		extract(shortcode_atts(array(
			"width" => '300',
			"height" => '300',
			),
		$atts));

		if( isset( $object ) && !empty( $object ) && $object != NULL ){
			return $object;
		} else{
			return get_template_directory_uri().'/images/user'.$width.'x'.$height.'.jpg';
		}
	}
	
	add_filter( 'docdirect_single_image_filter', 'docdirect_get_single_image_fallback', 10, 3 );
}


/**
 * @post type view
 * @return 
 */
if (!function_exists('docdirect_remove_row_actions')) {
	function docdirect_remove_row_actions( $actions ){
		if( get_post_type() === 'tg_slider' || 
			get_post_type() === 'docdirectinvoices' || 
			get_post_type() === 'docdirectorders' || 
			get_post_type() === 'docdirectreviews' || 
			get_post_type() === 'directory_packages' ||
			get_post_type() === 'directory_type' ) {
			unset( $actions['view'] );
		}
		return $actions;
	}
	add_filter( 'post_row_actions', 'docdirect_remove_row_actions', 10, 1 );
}


/**
 * @Get Currencies Symbol
 * @return {}
 */
if (!function_exists('docdirect_get_currency_symbol')) {

    function docdirect_get_currency_symbol() {
        $code          = sanitize_text_field( $_POST['code'] );
        $currency_list = docdirect_prepare_currency_symbols();
        echo ( $currency_list[$code]['symbol'] );
        die();
    }

    add_action('wp_ajax_docdirect_get_currency_symbol' , 'docdirect_get_currency_symbol');
}

/**
 * @Get Page/Post Slug
 * @return {}
 */
if (!function_exists('docdirect_get_the_slug')) {
	function docdirect_get_the_slug( $id=null ){
	  if( empty($id) ):
		global $post;
		if( empty($post) )
		  return ''; // No global $post var available.
		$id = $post->ID;
	  endif;
	
	  $slug = basename( get_permalink($id) );
	  return $slug;
	}
}


/**
 * @Admin Menu 
 * @return 
 */
if (!function_exists('docdirect_theme_options')) {
	add_action('admin_bar_menu', 'docdirect_theme_options', 1000);
	function docdirect_theme_options(){
		global $wp_admin_bar;
		if(!is_super_admin() || !is_admin_bar_showing()) return;
		
		$url = admin_url();
		
		// Add Parent Menu
		$argsParent=array(
			'id' => 'docdirect_setup',
			'title' => esc_html__('DocDirect Settings','docdirect'),
			'href' => false
		);
		
		$wp_admin_bar->add_node( $argsParent );	
	
		// Add Sub Menus
		$args = array();
	
		
		
		array_push($args,array(
			'id' => 'manage_sidebars',
			'parent' => 'docdirect_setup',
			'title' => esc_html__('Manage Sidebars','docdirect'),
			'href' => $url.'widgets.php',
			'meta' => array('target' => '_self')
		));
			
		
		if( class_exists( 'DocDirectGlobalSettings' ) ) {
			array_push($args,array(
				'id' => 'import_users',
				'parent' => 'docdirect_setup',
				'title' => esc_html__('Import Users','docdirect'),
				'href' => $url.'edit.php?post_type=directory_type&page=import_users',
				'meta' => array('target' => '_self')
			));
			
			array_push($args,array(
				'id' => 'invoices',
				'parent' => 'docdirect_setup',
				'title' => esc_html__('Invoices','docdirect'),
				'href' => $url.'edit.php?post_type=docdirectinvoices',
				'meta' => array('target' => '_self')
			));
			
			array_push($args,array(
				'id' => 'orders',
				'parent' => 'docdirect_setup',
				'title' => esc_html__('Orders','docdirect'),
				'href' => $url.'edit.php?post_type=docdirectorders',
				'meta' => array('target' => '_self')
			));
		}
		
		if ( function_exists('fw_get_db_post_option') ) {
			array_push($args,array(
				'id' => 'demo_import',
				'parent' => 'docdirect_setup',
				'title' => esc_html__('Demo Import','docdirect'),
				'href' => $url.'tools.php?page=fw-backups-demo-content',
				'meta' => array('target' => '_self')
			));
			array_push($args,array(
				'id' => 'theme_options',
				'parent' => 'docdirect_setup',
				'title' => esc_html__('Theme Options','docdirect'),
				'href' => $url.'themes.php?page=fw-settings',
				'meta' => array('target' => '_self')
			));
		}
		
		array_push($args,array(
			'id' => 'manage_menus',
			'parent' => 'docdirect_setup',
			'title' => esc_html__('Manage Menus','docdirect'),
			'href' => $url.'nav-menus.php',
			'meta' => array('target' => '_self')
		));
			
		sort($args);
		for($a=0;$a<sizeOf($args);$a++){
			$wp_admin_bar->add_node($args[$a]);
		}
	}
}


/**
 * @Footer Menu
 * @return 
 */
if (!function_exists('docdirect_theme_admin_footer_links')) {
	function docdirect_theme_admin_footer_links () {
		$theme_version = wp_get_theme();
		echo '<a href="'.$theme_version->get('ThemeURI').'" title="'.$theme_version->get('Name').'" target="_blank">'.$theme_version->get('Name').' '.$theme_version->get('Version').'</a>';
	}
	add_filter('admin_footer_text', 'docdirect_theme_admin_footer_links');
}


/**
 * @Footer Menu
 * @return 
 */
if (!function_exists('docdirect_set_email_content_type')) {
	add_filter( 'wp_mail_content_type', 'docdirect_set_email_content_type' );
	function docdirect_set_email_content_type () {
		return "text/html";
	}
}
