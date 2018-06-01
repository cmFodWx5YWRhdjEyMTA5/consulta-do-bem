<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$color = isset( $atts['color'] ) && $atts['color'] !='' ? $atts['color'] : '#5d5955';
?>

<div class="doc-registercontent">
    <div class="doc-leftarea">
        <?php if( !empty( $atts['cta_title'] ) ) {?><h3><?php echo do_shortcode($atts['cta_title']); ?></h3><?php }?>
		<?php if( !empty( $atts['cta_text'] ) ) {?><span><?php echo do_shortcode($atts['cta_text']); ?></span><?php }?>
    </div>
    <?php if( !empty( $atts['cta_button_text'] ) ) {?>
    <div class="doc-rightarea">
        <a class="doc-btn"  href="<?php echo esc_attr($atts['cta_button_link']); ?>"><?php echo esc_attr(!empty( $atts['cta_button_text'] ) ? $atts['cta_button_text'] : 'Read more'); ?></a>
    </div>
    <?php }?>
</div>