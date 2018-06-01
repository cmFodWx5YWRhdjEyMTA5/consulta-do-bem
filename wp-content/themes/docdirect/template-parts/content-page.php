<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Doctor Directory
 */

$section_width	 = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
?>

<div class="<?php echo esc_attr( $section_width );?> page-section">
	<div class="blog-grid blog-detail row">
		<?php 
			global $paged;
			$tg_get_excerpt	= get_option('rss_use_excerpt');
			get_option('posts_per_page');
			
			if ( have_posts() ) : 
				if (empty($paged)) {
					$paged = 1;
				}
				 
				if (!isset($_GET["s"])) {
						$_GET["s"] = '';
				}
				
				while ( have_posts() ) : the_post(); 
					global $post;
					$width = '1170';
					$height = '450';
					$title_limit = 1000;
					$thumbnail 	 = docdirect_prepare_thumbnail( $post->ID, $width, $height );
					$image_src = docdirect_prepare_thumbnail($post->ID, 'full');
					
					$stickyClass	= '';
					if( is_sticky() && !is_singular() ) {
						$stickyClass	= 'sticky';
					}
					
					$no_mediaClass	= '';
					if ( empty( $thumbnail ) ){
						$no_mediaClass	= 'media_none';
					}
				?>                         
				<div class="col-md-12 tg-blogwidth tg-landing-page archive-post-area <?php echo sanitize_html_class( $stickyClass );?>">
					<article class="tg-post">
						<?php if( isset( $thumbnail ) && !empty( $thumbnail ) ){?>
							<figure class="tg-post-img tg-haslayout">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php echo sanitize_title( get_the_title() ); ?>"></a>
							</figure>	
						<?php }?>
						<div class="tg-post-data tg-haslayout">
							<div class="tg-heading-border">
								<h3><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?> </a></h3>
							</div>
							<div class="tg-description">
								<p><?php docdirect_prepare_excerpt(400,'false',''); ?></p>
							</div>
							<a class="tg-btn" href="<?php esc_url( the_permalink() ); ?>"><?php esc_html_e('Read More ','docdirect');?>&raquo;</a>
							<?php
							  if (is_sticky() && !is_singular()) :
							  echo '<div class="sticky-post-wrap">
										  <div class="sticky-txt">
										   <em class="tg-featuretext">'.esc_html__('Featured','docdirect').'</em>
										   <i class="fa fa-bolt"></i>
										  </div>
									 </div>';
							  endif;
						 	?>
						</div>
						
					</article>
				</div>
			<?php 
			endwhile; 
			wp_reset_postdata();
		else:
			 esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.','docdirect');
		endif; 
		echo '<div class="col-md-12">';
			$qrystr = '';
			if ($wp_query->found_posts > get_option('posts_per_page')) {
				 if ( function_exists( 'docdirect_prepare_pagination' ) ) { 
						echo docdirect_prepare_pagination(wp_count_posts()->publish,get_option('posts_per_page'));
				 } 
			}
		echo '</div>';
		?>
	</div>
</div>
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 aside sidebar-section" id="sidebar">
	<aside id="tg-sidebar" class="tg-sidebar tg-haslayout">
		<?php get_sidebar();?>
	</aside>
</div>