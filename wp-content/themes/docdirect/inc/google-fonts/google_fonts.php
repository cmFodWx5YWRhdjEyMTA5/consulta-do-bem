<?php
/**
 * Hook For Google Fonts
 */

/**
 * @Prepare font urls
 * @return 
 */
if(!function_exists('_action_theme_process_google_fonts')) {
    function _action_theme_process_google_fonts(){
        $include_from_google = array();
        $google_fonts = fw_get_google_fonts();

        $body_font = fw_get_db_settings_option('body_font');
		$h1_font = fw_get_db_settings_option('h1_font');
        $h2_font = fw_get_db_settings_option('h2_font');
        $h3_font = fw_get_db_settings_option('h3_font');
        $h4_font = fw_get_db_settings_option('h4_font');
        $h5_font = fw_get_db_settings_option('h5_font');
		$h6_font = fw_get_db_settings_option('h6_font');

        // if is google font
        if( isset($google_fonts[$h1_font['family']]) ){
            $include_from_google[$h1_font['family']] = $google_fonts[$h1_font['family']];
        }
        if( isset($google_fonts[$h2_font['family']]) ){
            $include_from_google[$h2_font['family']] = $google_fonts[$h2_font['family']];
        }
        if( isset($google_fonts[$h3_font['family']]) ){
            $include_from_google[$h3_font['family']] = $google_fonts[$h3_font['family']];
        }
        if( isset($google_fonts[$h4_font['family']]) ){
            $include_from_google[$h4_font['family']] = $google_fonts[$h4_font['family']];
        }
        if( isset($google_fonts[$h5_font['family']]) ){
            $include_from_google[$h5_font['family']] = $google_fonts[$h5_font['family']];
        }
		
		if( isset($google_fonts[$h6_font['family']]) ){
            $include_from_google[$h6_font['family']] = $google_fonts[$h6_font['family']];
        }
		
		if( isset($google_fonts[$body_font['family']]) ){
            $include_from_google[$body_font['family']] = $google_fonts[$body_font['family']];
        }

        $google_fonts_links = docdirect_prepare_google_fonts($include_from_google);
        update_option( 'fw_theme_google_fonts_link', $google_fonts_links );
    }
	
    add_action('fw_settings_form_saved', '_action_theme_process_google_fonts', 999, 2);
}

/**
 * @Get remote fonts
 * @return 
 */
if (!function_exists('docdirect_prepare_google_fonts')) {
    function docdirect_prepare_google_fonts($include_from_google) {
        $fonts_url = '';
		/**
         * Get remote fonts
         * @param array $include_from_google
         */
        if ( ! sizeof( $include_from_google ) ) {
            return '';
        }

		$font_families	= array();
        foreach ( $include_from_google as $font => $font_family ) {
			
		   /* Translators: If there are characters in your language that are not
			* supported by Font Faamily, translate this to 'off'. Do not translate
			* into your own language.
			$font_on_off	= '';
			$font_on_off = _x( 'on', $font.' font: on or off', 'docdirect' );
			if ( 'off' !== $font_on_off ) {}
			*/
			
			$font_families[]	= $font.':'.$font_family['variants'][0];
        }
		
		$query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
		
		
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		
		return esc_url_raw( $fonts_url );
    }
}

/**
 * @Get URL Google Font
 * @return 
 */
if (!function_exists('docdirect_theme_fonts_url')) {
    function docdirect_theme_fonts_url() {
        $fonts_url = '';
		/**
         * Print google fonts link
         */
		// @ These fonts are get from databases, Functionality is done in given above function name as : docdirect_prepare_google_fonts
        $fonts_url = get_option('fw_theme_google_fonts_link', '');
        if( isset( $fonts_url ) &&  $fonts_url != ''){
        	return esc_url_raw( $fonts_url );
		}
    }
}

/**
 * @Enque Google Font
 * @return 
 */
if (!function_exists('docdirect_enqueue_google_fonts')) {
	function docdirect_enqueue_google_fonts() {
		
		if ( !is_admin() ) {
			wp_enqueue_style( 'docdirect_theme_google_font', docdirect_theme_fonts_url(), array(), null );
		}
		
	}
	add_action( 'wp_enqueue_scripts', 'docdirect_enqueue_google_fonts' );
}