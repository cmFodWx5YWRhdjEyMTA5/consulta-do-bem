<?php

if (!defined('FW')) {
    die('Forbidden');
}


/**
 * Return the questions listing view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_questions_view')) {

    function fw_ext_get_render_questions_view() {
        return fw()->extensions->get('questionsanswers')->render_questions_view();
    }

}

/**
 * Return the questions add view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_add_questions_view')) {

    function fw_ext_get_render_add_questions_view() {
        return fw()->extensions->get('questionsanswers')->render_questions_add();
    }

}

/**
 * Return the answers view.
 * @return string
 */
if (!function_exists('fw_ext_get_render_answers_view')) {

    function fw_ext_get_render_answers_view() {
        return fw()->extensions->get('questionsanswers')->render_answers_view();
    }

}