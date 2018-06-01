<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$uniq_flag = fw_unique_increment();
?>
<div class="sc-work">
<?php if ( !empty($atts['heading']) && !empty($atts['description'])) { ?>
<div class="col-sm-8 col-sm-offset-2 col-xs-12">
	<div class="tg-section-head tg-haslayout">
		<?php if (isset($atts['heading']) && !empty($atts['heading'])) { ?>
		<div class="tg-section-heading tg-haslayout">
			<h2><?php echo esc_attr($atts['heading']); ?></h2>
		</div>
		<?php } ?>
		<?php if (isset($atts['description']) && !empty($atts['description'])) { ?>
			<div class="tg-description tg-haslayout">
				<p><?php echo esc_attr($atts['description']); ?></p>
			</div>
		<?php } ?>
	</div>
</div>
<?php } ?>					
<div class="tg-howwework tg-haslayout">
	<?php if (isset($atts['banner']['url']) && !empty($atts['banner']['url'])) { ?>
		<div class="col-lg-6 col-md-7 col-sm-12 col-xs-12 tg-verticalmiddle">
			<figure>
				<img src="<?php echo esc_url( $atts['banner']['url'] );?>" alt="<?php echo esc_attr($heading['title']); ?>">
			</figure>
		</div>
	<?php }?>
	<div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 tg-verticalmiddle">
		<div class="tg-textbox">
			<?php if (isset($atts['work_description']) && !empty($atts['work_description'])) { ?>
			<div class="tg-description">
				<p><?php echo esc_attr($atts['work_description']); ?></p>
			</div>
			<?php }?>
			<?php if (isset($atts['work_popup']) && !empty( $atts['work_popup'] )) {?>
			<ul>
				<?php 
				$counter	= 0;  
				foreach ($atts['work_popup'] as $work) {
					$counter++;
					?>
					<li>
						<span class="tg-count"><?php echo sprintf("%02d", $counter);?></span>
						<div class="tg-workdata">
							<?php if (isset($work['title']) && !empty($work['title'])) { ?>
							<h3><?php echo esc_attr($work['title']); ?></h3>
							<?php }?>
							<?php if (isset($work['description']) && !empty($work['description'])) { ?>
							<div class="tg-work-description">
								<p><?php echo esc_attr($work['description']); ?></p>
							</div>
							<?php }?>
						</div>
					</li>
				<?php }?>
			</ul>
			<?php }?>
			<?php if (isset($atts['button_title']) && !empty($atts['button_title'])) { ?>
			<a class="tg-btn" href="<?php echo esc_attr($atts['link']); ?>"><?php echo esc_attr($atts['button_title']); ?></a>
			<?php }?>
		</div>
	</div>
</div>
</div>

