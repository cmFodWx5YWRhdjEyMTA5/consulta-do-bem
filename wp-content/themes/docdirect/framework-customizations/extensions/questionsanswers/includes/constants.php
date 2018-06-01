<?php

/**
 *  Contants
 */
if (!function_exists('fw_ext_questionsanswers_sp_prepare_constants')) {

    function fw_ext_questionsanswers_sp_prepare_constants() {
		$is_loggedin = 'false';
        if (is_user_logged_in()) {
            $is_loggedin = 'true';
        }
		
        wp_localize_script('fw_ext_questionsanswers_callback', 'fw_ext_questionsanswers_scripts_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
			'login_beofer_vote' => esc_html__('Please login before add your vote.','docdirect'),
			'is_loggedin' => $is_loggedin,
			'no_more' => '<p>'.esc_html__('No more posts found.','docdirect').'</p>',
        ));
    }

    add_action('wp_enqueue_scripts', 'fw_ext_questionsanswers_sp_prepare_constants', 90);
}