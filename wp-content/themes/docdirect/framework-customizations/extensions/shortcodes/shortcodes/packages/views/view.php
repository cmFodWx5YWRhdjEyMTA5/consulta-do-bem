<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$uniq_flag = fw_unique_increment();
?>
<div class="sc-packages">
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
	<div class="tg-packageswidth">
		<div class="tg-packages">
			<?php if (isset($atts['featured']) && $atts['featured'] == 1 ) { ?>
				<span class="tg-featuredicon"><em class="fa fa-bolt"></em></span>
			<?php } ?>
			<?php if (isset($atts['pac_title']) && !empty($atts['pac_title'])) { ?>
				<h2><?php echo esc_attr($atts['pac_title']); ?></h2>
			<?php } ?>
			<?php if (isset($atts['pac_subtitle']) && !empty($atts['pac_subtitle'])) { ?>
				<h3><?php echo esc_attr($atts['pac_subtitle']); ?></h3>
			<?php } ?>
			<?php if (isset($atts['price']) && !empty($atts['price'])) { ?>
				<strong><i><?php echo esc_attr($atts['currency']); ?></i><?php echo esc_attr($atts['price']); ?></strong>
			<?php } ?>
			<?php if (isset($atts['duration']) && !empty($atts['duration'])) { ?>
				<p><?php echo esc_attr($atts['duration']); ?></p>
			<?php } ?>
			<?php if (isset($atts['rating']) && !empty($atts['rating'])) { ?>
			<span class="tg-stars">
				<?php for( $i= 0; $i < $atts['rating']; $i++  ){?>
					<i class="fa fa-star"></i>
				<?php }?>
			</span>
			<?php }?>
			<?php if (isset($atts['features']) && !empty($atts['features'])) { ?>
				<ul>
					<?php foreach( $atts['features'] as $key => $value ){?>
						<li><?php echo esc_attr( $value );?></li>
					<?php }?>
				</ul>
			<?php }?>
			<?php if (isset($atts['button_title']) && !empty($atts['button_title'])) { ?>
				<a class="tg-btn" target="_blank" href="<?php echo esc_url( $atts['link'] );?>"><?php echo esc_attr( $atts['button_title'] );?></a>
			<?php }?>
		</div>
	</div>
</div>

