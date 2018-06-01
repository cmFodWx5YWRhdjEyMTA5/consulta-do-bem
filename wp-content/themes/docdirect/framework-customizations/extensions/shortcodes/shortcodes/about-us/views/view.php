<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();
?>

<div class="sc-about-us tg-healthcareonthego">
	<div class="tg-contentbox tg-haslayout">
		<?php if ( !empty($atts['title']) || !empty($atts['sub_title']) ) { ?>
			<div class="tg-heading-border">
				<?php if (!empty($atts['title'])) { ?>
					<h2><?php echo esc_attr($atts['title']); ?></h2>
				<?php } ?>
				<?php if (!empty($atts['sub_title'])) { ?>		
					<h3><?php echo esc_attr($atts['sub_title']); ?></h3>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( !empty($atts['description'])) { ?>
			<div class="tg-description">
				<p><?php echo esc_attr($atts['description']); ?></p>
			</div>
		<?php } ?>						
		<?php if (!empty($atts['lists'])) { ?>
			<ul>
				<?php foreach ($atts['lists'] as $list) {?>
					<li><?php echo esc_attr($list); ?></li>
				<?php }?>		
			</ul>
		<?php }?>
		<?php if (!empty( $atts['buttons'] )) {?>
			<div class="tg-btns">
				<?php foreach ($atts['buttons'] as $button) {
					if( isset( $button['title'] ) && !empty( $button['title'] ) ) {
					?>
						<a class="tg-btn" href="<?php echo esc_url($button['link']); ?>"><?php echo esc_attr($button['title']); ?></a>
				<?php }}?>
			</div>
		<?php }?>	
	</div>
</div>
