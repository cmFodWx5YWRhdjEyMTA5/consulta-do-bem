<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();
?>
<div class="sc-tabs">
	<div class="tg-skill-box">
		<?php if ( !empty($atts['heading']) ) { ?>
			<h2><?php echo esc_attr($atts['heading']); ?></h2>
		<?php } ?>
		<div id="tg-ourskill-<?php echo esc_attr( $uni_flag );?>" class="tg-skill-group">
			<?php
			if (isset($atts['skillset'])) {
				foreach ($atts['skillset'] as $skills) {
					$skill_highlight = isset($skills['skill_highlight']) && $skills['skill_highlight'] == true ? 'active' : '';
					?>
				<div class="tg-skill <?php echo sanitize_html_class($skill_highlight); ?>">
					<span class="tg-skill-name"><?php echo esc_attr($skills['skill_name']); ?></span>
					<div class="tg-skill-holder tg-border" data-percent="<?php echo esc_attr($skills['percentage']); ?>%">
						<div class="tg-skill-bar">
							<span><?php echo esc_attr($skills['percentage']); ?>%</span>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
	<script>
		jQuery(document).ready(function (e) {
			try {
				jQuery('#tg-ourskill-<?php echo esc_js( $uni_flag );?>').appear(function () {
					jQuery('.tg-skill-holder').each(function () {
						jQuery(this).find('.tg-skill-bar').animate({
							width: jQuery(this).attr('data-percent')
						}, 2500);
					});
				});
			} catch (err) {
			}
	
		});
	</script>
</div>