<?php
/**
 * The template for displaying search results pages.
 *
 * @package Doctor Directory
 */

get_header(); ?>
<div class="container">
    <div class="row">
        <div class="tg-inner-content haslayout">
			<?php
			/**
			 * Run the loop for the search to output the results.
			 * If you want to overload this in a child theme then include a file
			 * called content-search.php and that will be used instead.
			 */
				get_template_part( 'template-parts/content', 'search' );
			?>
		</div>
    </div>
</div>
<?php get_footer(); ?>
