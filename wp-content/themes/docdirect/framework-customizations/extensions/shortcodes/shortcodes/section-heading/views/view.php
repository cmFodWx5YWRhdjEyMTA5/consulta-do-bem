<?php if (!defined('FW')) die( 'Forbidden' );
/**
 * @var $atts
 */
?>

<?php if ( !empty($atts['heading']) || !empty($atts['description'])) { ?>
	<div class="col-sm-10 col-sm-offset-1 col-xs-12">
		<div class="tg-theme-heading">
			<?php if (isset($atts['heading']) && !empty($atts['heading'])) { ?>
				<h2><?php echo esc_attr($atts['heading']); ?></h2>
			<?php } ?>
			<?php if (isset($atts['description']) && !empty($atts['description'])) { ?>
				<span class="tg-roundbox"></span>
				<div class="tg-description">
					<p><?php echo esc_attr($atts['description']); ?></p>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>									