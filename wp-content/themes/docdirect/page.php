<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Doctor Directory
 */

get_header(); 
$tg_sidebar	 	 = 'full';
$section_width	 = 'col-xs-12';
if (function_exists('fw_ext_sidebars_get_current_position')) {
	$current_position = fw_ext_sidebars_get_current_position();
	if( $current_position !== 'full' &&  ( $current_position == 'left' || $current_position == 'right' ) ) {
		$tg_sidebar	= $current_position;
		$section_width	= 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
	}
}

if( isset( $tg_sidebar ) && ( $tg_sidebar == 'full' ) ){
		while ( have_posts() ) : the_post();?>
			<div class="container">
				<div class="row">
					<?php 
						do_action('docdirect_prepare_section_wrapper_before');
							the_content();
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								echo '<div class="col-xs-12">';
								comments_template();
								echo '</div>';
							endif;
						do_action('docdirect_prepare_section_wrapper_after');
					?>
				</div>
			</div>
		<?php 
		endwhile;
} else{ 

 if( isset( $tg_sidebar ) && $tg_sidebar == 'right' ) {
	$aside_class = 'pull-right';
        $content_class = 'pull-left';
 }else{
	$aside_class = 'pull-left';
        $content_class = 'pull-right';
 }
?> 
<div class="container">
	<div class="row">
		<?php do_action('docdirect_prepare_section_wrapper_before');?>
		<div class="<?php echo esc_attr( $section_width );?> <?php echo sanitize_html_class($content_class); ?>  page-section">
			<div class="row">
				<?php
					while ( have_posts() ) : the_post();
							the_content();
							// If comments are open or we have at least one comment, load up the comment template.
							
							if ( comments_open() || get_comments_number() ) :
								echo '<div class="col-xs-12">';
									comments_template();
								echo '</div>';
							endif;;
					endwhile;
				?>
			</div>
		</div>
		
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 aside sidebar-section <?php echo sanitize_html_class($aside_class); ?>" id="sidebar">
			<aside id="tg-sidebar" class="tg-sidebar tg-haslayout">
				<?php echo fw_ext_sidebars_show('blue'); ?>
			</aside>
		</div>
		
		<?php do_action('docdirect_prepare_section_wrapper_after');?>
	</div>
</div>
<?php }?>
<?php get_footer(); ?>