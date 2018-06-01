<?php
if (!defined('FW'))
    die('Forbidden');

$columnClasses	= array();

$margin_top = '';
$margin_bottom = '';
$margin_left = '';
$margin_right = '';
$padding_top = '';
$padding_right = '';
$padding_bottom = '';
$padding_left = '';

$custom_classes = '';
$responsive_classes = '';
$extra_small = '';

$columnClasses[] = fw_ext_builder_get_item_width('page-builder', $atts['width'] . '/frontend_class');
$columnClasses[]	= 'builder-column';

if (isset($atts['responsive_switch']) && $atts['responsive_switch'] == 'on') {
    if (isset($atts['responsive_classes'])) : $columnClasses[] = $atts['responsive_classes'];
    endif;
}

if (isset($atts['extra_small_switch']) && $atts['extra_small_switch'] == 'on') {
    if (isset($atts['extra_small'])) : $columnClasses[] = $atts['extra_small'];
    endif;
}

/**
 * @Check if Margins is set and custom margin styling is enable.
 * @Get Margin Values
 */
if (isset($atts['margin_top']) && !empty($atts['margin_top'])) : $margin_top = 'margin-top:' . $atts['margin_top'] . 'px;';
endif;
if (isset($atts['margin_right']) && !empty($atts['margin_right'])) : $margin_right = 'margin-right:' . $atts['margin_right'] . 'px;';
endif;
if (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) : $margin_bottom = 'margin-bottom:' . $atts['margin_bottom'] . 'px;';
endif;
if (isset($atts['margin_left']) && !empty($atts['margin_left'])) : $margin_left = 'margin-left:' . $atts['margin_left'] . 'px;';
endif;
/**
 * @Check if Padding is set and custom padding styling is enable.
 * @Get Padding Values
 */
if (isset($atts['padding_top']) && $atts['padding_top'] != '') : $padding_top = 'padding-top:' . $atts['padding_top'] . 'px;';
endif;
if (isset($atts['padding_right']) && $atts['padding_right'] != '') : $padding_right = 'padding-right:' . $atts['padding_right'] . 'px;';
endif;
if (isset($atts['padding_bottom']) && $atts['padding_bottom'] != '') : $padding_bottom = 'padding-bottom:' . $atts['padding_bottom'] . 'px;';
endif;
if (isset($atts['padding_left']) && $atts['padding_left'] != '') : $padding_left = 'padding-left:' . $atts['padding_left'] . 'px;';
endif;



if (isset($atts['custom_classes'])) : $columnClasses[] = $atts['custom_classes'];
endif;
$section_style = ( $padding_top || $padding_right || $margin_right || $padding_bottom || $padding_bottom || $margin_left || $margin_bottom || $margin_top ) ? 'style="' . $margin_top . $margin_bottom . $padding_top . $padding_right . $padding_bottom . $padding_left . '"' : '';
?>
<div class="<?php echo implode(' ',$columnClasses); ?>" <?php echo ($section_style); ?>>
    <?php echo do_shortcode($content); ?>
</div>
