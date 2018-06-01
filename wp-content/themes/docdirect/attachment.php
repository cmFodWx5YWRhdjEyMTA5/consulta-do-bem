<?php
/**
 * The template for displaying all single posts.
 *
 * @package Doctor Directory
 */
get_header();
?>
<div class="blog-detail">
	<div class="post">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php while (have_posts()) : the_post(); ?>
						<div class="post-text attachment-text">
							<h1><?php the_title(); ?></h1>
							<h5><?php echo get_the_author_meta('nickname'); ?> | <?php echo get_the_date('d.m.y'); ?>|<?php docdirect_entry_footer(); ?> | <?php echo get_comments_number() ?> <?php esc_html_e('Comments','docdirect');?></h5>
							<?php echo wp_get_attachment_image( get_the_ID(),array(1140,289) ); ?>
							<?php the_content(); ?>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
			<?php
			// If comments are open or we have at least one comment, load up the comment template
			if (comments_open() || get_comments_number()) :
				comments_template();
			endif;
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
