<?php

if (!defined('FW')) {
    die('Forbidden');
}

/**
 * Enqueue Script on frontend
 * Check if this is not admin
 */
if (!is_admin()) {

    $fw_ext_instance = fw()->extensions->get('articles');
    wp_enqueue_script(
            'fw_ext_articles_callback', $fw_ext_instance->get_declared_URI('/static/js/fw_ext_articles_callbacks.js'), array('jquery'), '1.0', true
    );
}