<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$color = isset( $atts['color'] ) && $atts['color'] !='' ? $atts['color'] : '#5d5955';
?>
<div class="tg-buynowbox">
	<h3 style="color:<?php echo esc_attr( $color );?>"><?php echo do_shortcode($atts['cta_text']); ?></h3>
	<?php if( !empty($atts['cta_button_text']) ){?>
    	<a class="tg-btn" style="color:<?php echo esc_attr( $color );?>" href="<?php echo esc_attr($atts['cta_button_link']); ?>"><?php echo esc_attr(!empty( $atts['cta_button_text'] ) ? $atts['cta_button_text'] : 'Read more'); ?></a>
    <?php }?>
</div>