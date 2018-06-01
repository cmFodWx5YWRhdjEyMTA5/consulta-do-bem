<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
?>
<div class="sc-contactinfo">
	<div class="tg-info">
		<div class="tg-infobox tg-border">
			<div class="tg-displaytable">
				<div class="tg-displaytablecell">
					<?php if (isset($atts['icon']) && !empty($atts['icon'])) { ?>
						<i class="<?php echo esc_attr($atts['icon']); ?>"></i>
					<?php } ?>
					<div class="tg-heading-border">
						<?php if (isset($atts['title']) && !empty($atts['title'])) { ?>
						<h3><?php echo esc_attr($atts['title']); ?></h3>
						<?php } ?>
						<?php 
							if (isset($atts['description']) && !empty($atts['description'])) {
								foreach( $atts['description'] as $key=> $value ){
							 ?>
							<span><?php echo force_balance_tags($value); ?></span>
						<?php }} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>