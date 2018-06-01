<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Doctor Directory
 */
get_header();
?>
<?php
if (!function_exists('fw_get_db_settings_option')) {
    $image_banner['url'] 	= get_template_directory_uri().'/images/404.jpg';
	$search_text 	= esc_html__('Perhaps searching can help.', 'docdirect');
	$title 			= esc_html__('we are sorry!', 'docdirect');
	$sub_title 			= esc_html__('The page you requested cannot be found.', 'docdirect');
    $description 	= esc_html__('', 'docdirect');
} else {
	$image_banner 	= fw_get_db_settings_option('404_image');
    $search_text 	= fw_get_db_settings_option('search_text');
	$title 			= fw_get_db_settings_option('404_title');
	$sub_title 			= fw_get_db_settings_option('404_message');
    $description 		= fw_get_db_settings_option('404_description');
}

?>
<div class="container">
	<div class="row">
		<div class="tg-404 tg-haslayout">
			<?php if( isset( $image_banner['url'] ) && !empty( $image_banner['url'] ) ) {?>
			<div class="col-md-6 col-sm-12">
				<figure>
					<img class="floating" src="<?php echo esc_url($image_banner['url']); ?>" alt="<?php esc_html_e('404 Page','docdirect'); ?>">
				</figure>
			</div>
			<?php }?>
			<div class="col-md-6 col-sm-12">
				<div class="tg-contentbox">
					<div class="tg-heading-border">
						<?php if( isset( $title ) && !empty( $title ) ) {?>
							<h2><?php echo force_balance_tags($title); ?></h2>
						<?php }?>
						<?php if( isset( $sub_title ) && !empty( $sub_title ) ) {?>
							<h3><?php echo force_balance_tags($sub_title); ?></h3>
						<?php }?>
					</div>
					<?php if( isset( $description ) && !empty( $description ) ) {?>
						<div class="tg-description"><p><?php echo esc_attr($description); ?></p></div>
					<?php }?>
					<div class="tg-refinesearcharea">
						<?php if( isset( $search_text ) && !empty( $search_text ) ) {?>
							<div class="tg-heading-border tg-small"><h2><?php echo force_balance_tags($search_text); ?></h2></div>
						<?php }?>
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
