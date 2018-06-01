<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
/**
 * @Get Background Color
 */
$bg_color = '';
if (!empty($atts['background_color'])) {
    $bg_color = 'background-color:' . $atts['background_color'] . ';';
}


/**
 * @Get Background Image
 */
$bg_image = '';
if (isset($atts['background_image']['data']['icon']) && !empty($atts['background_image']['data']['icon'])) {
    $bg_image = 'background-image:url(' . $atts['background_image']['data']['icon'] . ');';
}


/**
 * @Parallax Configurations
 * @Parallax Classes
 * @Parallax Image
 * @Parallax Bleed
 * @Parallax Speed
 */
$paralaxx_configuration = '';
$parallax_classes = '';
$parallax_image = '';
$parallax_bleed = isset($atts['parallax_bleed']) && !empty($atts['parallax_bleed']) ? $atts['parallax_bleed'] : '0';
$parallax_speed = isset($atts['parallax_speed']) && !empty($atts['parallax_speed']) ? $atts['parallax_speed'] : '0.2';


/**
 * @Check if Parallax is on
 * @Set the Parallax Configuration Settings
 */
if (isset($atts['parallax']) && $atts['parallax'] === 'on' && isset($atts['background_image']['data']['icon']) && !empty($atts['background_image']['data']['icon'])) {
	$parallax_url	= $atts['background_image']['data']['icon'];
	$parallax_url	= docdirect_add_http($parallax_url);
	
	if ( is_ssl() ) {
		$parallax_url = preg_replace("/^http:/i", "https:", $parallax_url);
	} else{
		$parallax_url = $parallax_url;
	}
	
    $paralaxx_configuration = 'data-speed="' . $parallax_speed . '" data-bleed="' . $parallax_bleed . '" data-appear-top-offset= "600" data-parallax="scroll" data-position="center 0" data-image-src="' . $parallax_url . '"';
    $section_extra_classes[] = 'parallax-window';
    $bg_image = '';
}

/**
 * @Margin top, right, bottom, left
 * @Padding top, right, bottom, left
 * @Background Repeat
 * @Background Position x and y
 * @Custom ID
 * @Custom Classes
 */
$margin_top = '';
$margin_right = '';
$margin_bottom = '';
$margin_left = '';
$padding_top = '';
$padding_right = '';
$padding_bottom = '';
$padding_left = '';
$bg_repeat = '';
$bg_position_x = '';
$bg_position_y = '';
$custom_id = '';
$custom_classes = '';
$section_extra_classes	= array();



/**
 * @Check if Margins is set and custom margin styling is enable.
 * @Get Margin Values
 */
if (isset($atts['margin_top']) && $atts['margin_top'] != '') : $margin_top = 'margin-top:' . $atts['margin_top'] . 'px;';
endif;
if (isset($atts['margin_right']) && $atts['margin_right'] != '') : $margin_right = 'margin-right:' . $atts['margin_right'] . 'px;';
endif;
if (isset($atts['margin_bottom']) && $atts['margin_bottom'] != '') : $margin_bottom = 'margin-bottom:' . $atts['margin_bottom'] . 'px;';
endif;
if (isset($atts['margin_left']) && $atts['margin_left'] != '') : $margin_left = 'margin-left:' . $atts['margin_left'] . 'px;';
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


/**
 * @Check if Bacground repeat is selected and set the background repear value.
 * @Check if the Custom Section id is set and get the custom id.
 * @Check if the Custom Classes is Set and get the custom classes for section.
 */
if (isset($atts['background_repeat'])) : $bg_repeat = 'background-repeat:' . $atts['background_repeat'] . ';';
endif;
if (isset($atts['custom_id'])) : $custom_id = $atts['custom_id'];
endif;
if (isset($atts['custom_classes'])) : $custom_classes = $atts['custom_classes'];
endif;


/**
 * @Assign the Custom ID 
 * @Set the Background Position in x
 * @Set the Background Position in y
 * @Set the Background Position
 */
$custom_id = isset($atts['custom_id']) && !empty($atts['custom_id']) ? 'id="' . $atts['custom_id'] . '"' : '';
$bg_position_x = isset($atts['positioning_x']) && !empty($atts['positioning_x']) ? $atts['positioning_x'] . '%' : '0%';
$bg_position_y = isset($atts['positioning_y']) && !empty($atts['positioning_y']) ? $atts['positioning_y'] . '%' : '100%';
$bg_positioning = 'background-position:' . $bg_position_x . ' ' . $bg_position_y;


/**
 * 
 */
$bg_video_data_attr = '';
$section_extra_classes	= array();
if (!empty($atts['video'])) {
    $filetype = wp_check_filetype($atts['video']);
    $filetypes = array('mp4' => 'mp4', 'ogv' => 'ogg', 'webm' => 'webm', 'jpg' => 'poster');
    $filetype = array_key_exists((string) $filetype['ext'], $filetypes) ? $filetypes[$filetype['ext']] : 'video';
    $bg_video_data_attr = 'data-wallpaper-options="' . fw_htmlspecialchars(json_encode(array('source' => array($filetype => $atts['video'])))) . '"';
    $section_extra_classes[] = ' background-video';
}

$section_extra_classes[] = $custom_classes;

if (isset($atts['parallax']) && $atts['parallax'] === 'off') {
    $section_style = ( $bg_color || $bg_image || $padding_top || $padding_right || $padding_bottom || $padding_left || $margin_top || $margin_right || $margin_bottom || $margin_left ) ? 'style="' . $bg_color . $bg_image . $margin_top . $margin_right . $margin_bottom . $margin_left . $padding_top . $padding_right . $padding_bottom . $padding_left . $bg_repeat . $bg_positioning . '; background-size: cover;"' : '';
} else {
    $section_style = ( $bg_color || $bg_image || $padding_top || $padding_right || $padding_bottom || $padding_left || $margin_top || $margin_right || $margin_bottom || $margin_left ) ? 'style="' . $bg_color . $bg_image . $margin_top . $margin_right . $margin_bottom . $margin_left . $padding_top . $padding_right . $padding_bottom . $padding_left . $bg_repeat . $bg_positioning . '"' : '';
}


if( isset( $atts['is_fullwidth'] ) && $atts['is_fullwidth'] === 'stretch_section' ){
	$section_extra_classes[] = 'stretch_section';
	$full_width_section	= '<div class="section-current-width"></div>';
}else if( isset( $atts['is_fullwidth'] ) && $atts['is_fullwidth'] === 'stretch_section_contents' ){
	$section_extra_classes[] = 'stretch_section_contents stretch_section stretch_data';
	$full_width_section	= '<div class="section-current-width"></div>';
} else if( isset( $atts['is_fullwidth'] ) && $atts['is_fullwidth'] === 'stretch_section_contents_corner' ){
	$section_extra_classes[] = 'stretch_section_contents_corner stretch_section stretch_data';
	$full_width_section	= '<div class="section-current-width"></div>';
} else{
	$section_extra_classes[] = 'default';
	$full_width_section	= '';
}

//Siebars
$sidebar_status	= 'off';
if( isset( $atts['sidebar']['gadget'] ) && $atts['sidebar']['gadget'] === 'left' ){
	$sidebar	= $atts['sidebar']['left']['left_sidebar'];
	$section_position	= 'pull-right';
	$sidebar_position	= 'pull-left';
	$sidebar_status	= 'on';
} elseif( isset( $atts['sidebar']['gadget'] ) && $atts['sidebar']['gadget'] === 'right' ){
	$sidebar	= $atts['sidebar']['right']['right_sidebar'];
	$sidebar_status	= 'on';
	$section_position	= 'pull-left';
	$sidebar_position	= 'pull-right';
}

?>
<?php if( isset( $sidebar_status ) && $sidebar_status === 'on' ){?>
	<section id="tg-twocolumns-upper" class="tg-twocolumns section-with-sidebar tg-haslayout">
        <div class="col-xs-12 col-sm-12 col-md-9 <?php echo sanitize_html_class( $section_position );?>">
          <div id="tg-content-upper" class="tg-content tg-haslayout">
              <section  class="tg-main-section haslayout <?php echo implode(' ',$section_extra_classes ) ?>" <?php echo ($custom_id); ?> <?php echo ($section_style); ?> <?php echo ($bg_video_data_attr); ?> <?php echo ($paralaxx_configuration); ?>>
                <div class="builder-items">
                    <?php if (isset($atts['section_heading']) && !empty($atts['section_heading'])) { ?>
                        <h2 class="tg-section-title"><?php echo esc_attr($atts['section_heading']); ?></h2>
                    <?php } ?>
                    <?php echo do_shortcode($content); ?>
                </div>
            </section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12  col-md-3 <?php echo sanitize_html_class( $sidebar_position );?>">
           <aside id="tg-sidebar-upper" class="tg-sidebar tg-haslayout">
                <?php if( !function_exists('dynamic_sidebar') || !dynamic_sidebar($sidebar) ) : endif;?>
           </aside>
        </div>
    </section>
<?php } else{?>
    <section  class="tg-main-section haslayout <?php echo implode(' ',$section_extra_classes ) ?>" <?php echo ($custom_id); ?> <?php echo ($section_style); ?> <?php echo ($bg_video_data_attr); ?> <?php echo ($paralaxx_configuration); ?>>
        <div class="builder-items">
            <?php if (isset($atts['section_heading']) && !empty($atts['section_heading'])) { ?>
                <h2 class="tg-section-title"><?php echo esc_attr($atts['section_heading']); ?></h2>
            <?php } ?>
            <?php echo do_shortcode($content); ?>
        </div>
    </section>
    <?php echo ( $full_width_section );?>
<?php }?>
