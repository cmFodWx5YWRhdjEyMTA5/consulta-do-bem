<?php
if (function_exists('fw_get_db_settings_option')) :
    $background = fw_get_db_settings_option('background');
    $comming_title = fw_get_db_settings_option('comming_title');
    $comming_description = fw_get_db_settings_option('comming_description');
    $maintenance = fw_get_db_settings_option('maintenance');
    $date = fw_get_db_settings_option('date');
    $formatted_date = date('m/d/Y H:i:s', strtotime( $date ));
endif;

$post_name	= docdirect_get_post_name();

if ( (isset($maintenance) && $maintenance == 'enable' and ! (is_user_logged_in()) )  || $post_name == "coming-soon") {
    $bg_image	= '';
	$background_comingsoon	= docdirect_comingsoon_background();
	if( isset( $background_comingsoon ) && !empty( $background_comingsoon ) ){
		$bg_image	= 'style="background: url('.$background_comingsoon.') no-repeat 50% 50%;"';
	}

	?>
	<main id="main" class="tg-main-section tg-haslayout tg-bgwhite" <?php echo ( $bg_image );?>>
	<div class="tg-displaytable">
		<div class="tg-displaytablecell">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<div class="tg-commingsoon-content tg-haslayout">
							<?php if (isset($comming_title) && !empty($comming_title)) { ?>
								<h1><?php echo esc_attr($comming_title); ?></h1> 
							<?php } ?>
							<?php if (isset($comming_description) && !empty($comming_description)) { ?>
								<h2><?php echo do_shortcode($comming_description); ?></h2>
							<?php } ?>
							<div class="tg-counterarea">
								<ul id="comming-countdown">
									<li><span class="days">00</span><p class="days_text"><?php esc_html_e('Days','docdirect');?></p></li>
									<li><span class="hours">00</span><p class="hours_text"><?php esc_html_e('Hours','docdirect');?></p></li>
									<li><span class="minutes">00</span><p class="minutes_text"><?php esc_html_e('Minutes','docdirect');?></p></li>
									<li><span class="seconds">00</span><p class="seconds_text"><?php esc_html_e('Seconds','docdirect');?></p></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready(function(e) {
			jQuery('#comming-countdown').countdown({
				date: '<?php echo esc_js( $formatted_date );?>',
				day: '<?php esc_html_e('Day','docdirect');?>',
				days: '<?php esc_html_e('Days','docdirect');?>',
				hour: '<?php esc_html_e('Hour','docdirect');?>',
				hours: '<?php esc_html_e('Hours','docdirect');?>',
				minute: '<?php esc_html_e('Minute','docdirect');?>',
				minutes: '<?php esc_html_e('Minutes','docdirect');?>',
				second: '<?php esc_html_e('Second','docdirect');?>',
				seconds: '<?php esc_html_e('Seconds','docdirect');?>'
			}, function () {
				//console.log('Error!');
			});
		});
	</script>
	</main>
    <?php
    die();
}
