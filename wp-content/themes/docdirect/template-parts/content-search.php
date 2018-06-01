<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Doctor Directory
 */
$section_width	 = 'col-xs-9';
?>
<div class="col-md-12 tg-search-for">
	<div class="border-left">
		<h3><?php printf( esc_attr( 'Search Results for: %s', 'docdirect' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
	</div><!-- .page-header -->
</div>
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
					
					$no_mediaClass	= 'media_none';
					if (isset($thumbnail) && $thumbnail != ''){
						$no_mediaClass	= 'media_none';
					}
			  ?>                         
			 <div class="col-md-12 tg-blogwidth tg-landing-page archive-post-area <?php echo sanitize_html_class( $stickyClass );?>">
				<article class="tg-post">
					<?php if( isset( $thumbnail ) && !empty( $thumbnail ) ){?>
                        <figure class="tg-post-img tg-haslayout">
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php echo sanitize_title( get_the_title()); ?>"></a>
                        </figure>	
                    <?php }?>
					<div class="tg-post-data tg-haslayout">
						<div class="tg-heading-border">
							<h3><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?> </a></h3>
						</div>
						<div class="tg-description">
							<p><?php echo docdirect_prepare_search_content(50); ?></p>
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
		?>
		<div class="search-none col-md-12">
			<p><?php  esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.','docdirect');?></p>
			<div class="tg-suggestions">
				<h4><?php  esc_html_e('Suggestions: ','docdirect');?> </h4>
				<ul>
					<li> <?php  esc_html_e('Make sure all words are spelled correctly ','docdirect');?> </li>
					<li> <?php  esc_html_e('Try more general keywords, especially if you are attempting a name ','docdirect');?> </li>
				</ul>
			</div>
            <div class="search-bar row">
                <div class="col-sm-6 col-xs-6 col-xs-offset-3">
                    <div class="form-group">
						<?php get_search_form(); ?>
                    </div>
               </div>
            </div>
		</div>
		<?php
		endif; 
		
		$qrystr = '';
		if ($wp_query->found_posts > get_option('posts_per_page')) {
		   if ( function_exists( 'docdirect_prepare_pagination' ) ) { 
				echo docdirect_prepare_pagination(wp_count_posts()->publish,get_option('posts_per_page'));
		 } 
		}
		?>
	</div>
</div>
<aside class="col-md-3 col-sm-4 col-xs-12 sidebar-section" id="sidebar">
	<div class="aside">
		<?php get_sidebar();?>
	</div>
</aside>