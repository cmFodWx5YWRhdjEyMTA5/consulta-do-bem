<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Doctor Directory
 */
get_header();

$section_width	 = 'col-lg-9 col-md-9 col-sm-12 col-xs-12';
?>

<div class="container">
  <div class="row">
	<div class="tg-inner-content haslayout">
		<div class="<?php echo esc_attr( $section_width );?> page-section">
			<div class="blog-grid blog-detail row">
				<?php
				global $paged;
				$tg_get_excerpt	= get_option('rss_use_excerpt');
				if (is_author()) {
					global $author;
					$userdata = get_userdata($author);
				}
				if (category_description() || is_tag() || (is_author() && isset($userdata->description) && !empty($userdata->description))) {
					echo '<article class="widget orgnizer">';
					if (is_author()) {
						
						$userprofile_media	= get_the_author_meta( 'userprofile_media', $author->ID );
						?>
						<figure>
							<?php if ( !empty( $userprofile_media ) ) { ?>
								<div class="author-img">
									<img src="<?php echo esc_url($userprofile_media)?>" alt="<?php esc_attr_e('Author Avatar','docdirect');?>" />
								</div>
							<?php } ?>
						</figure>
						<div class="left-sp">
							<h5><a><?php echo esc_attr($userdata->display_name); ?></a></h5>
							<p><?php echo balanceTags($userdata->description, true); ?></p>
						</div>
						<?php
					} elseif (is_category()) {
						$category_description = category_description();
						if (!empty($category_description)) {
							?>
							<div class="left-sp">
								<p><?php echo category_description(); ?></p>
							</div>
						<?php } ?>
						<?php
					} elseif (is_tag()) {
						$tag_description = tag_description();
						if (!empty($tag_description)) {
							?>
							<div class="left-sp">
								<p><?php echo apply_filters('tag_archive_meta', $tag_description); ?></p>
							</div>
							<?php
						}
					}
					echo '</article>';
				}
		
				if (empty($paged)) {
					$paged = 1;
				}
		
				if (!isset($_GET["s"])) {
					$_GET["s"] = '';
				}
		
				$taxonomy = 'category';
				$taxonomy_tag = 'post_tag';
				$args_cat = array();
		
				if (is_author()) {
					$args_cat = array('author' => $wp_query->query_vars['author']);
					$post_type = array('post');
				} elseif (is_date()) {
					if (is_month() || is_year() || is_day() || is_time()) {
						$args_cat = array('m' => $wp_query->query_vars['m'], 'year' => $wp_query->query_vars['year'], 'day' => $wp_query->query_vars['day'], 'hour' => $wp_query->query_vars['hour'], 'minute' => $wp_query->query_vars['minute'], 'second' => $wp_query->query_vars['second']);
					}
					$post_type = array('post');
				} else if ((isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy']))) {
					$taxonomy = $wp_query->query_vars['taxonomy'];
					$taxonomy_category = '';
					$taxonomy_category = $wp_query->query_vars[$taxonomy];
		
					$taxonomy = 'category';
					$args_cat = array();
					$post_type = 'post';
				} else if (is_category()) {
					$taxonomy = 'category';
					$args_cat = array();
					$category_blog = $wp_query->query_vars['cat'];
					$post_type = 'post';
					$args_cat = array('cat' => "$category_blog");
				} else if (is_tag()) {
					$taxonomy = 'category';
					$args_cat = array();
					$tag_blog = $wp_query->query_vars['tag'];
					$post_type = 'post';
					$args_cat = array('tag' => "$tag_blog");
				} else {
					$taxonomy = 'category';
					$args_cat = array();
					$post_type = 'post';
				}
				$args = array(
					'post_type' => $post_type,
					'paged' => $paged,
					'post_status' => 'publish',
					'order' => 'DESC',
					'orderby' => 'ID',
				);
				?>

	   
				<?php
				$args = array_merge($args_cat, $args);
				$custom_query = new WP_Query($args);
				if ($custom_query->have_posts()):
					while ($custom_query->have_posts()) : $custom_query->the_post();
						global $post;
						$width = '1170';
						$height = '450';
						$title_limit = 1000;
						$thumbnail = docdirect_prepare_thumbnail($post->ID, $width, $height);
						$image_src = docdirect_prepare_thumbnail($post->ID, 'full');
						$stickyClass	= '';
						
						if( is_sticky() && !is_singular() ) {
							$stickyClass	= 'sticky';
						}
						
						docdirect_init_share_script();
						
						$no_mediaClass	= '';
						if ( empty( $thumbnail ) ){
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
				else:
					esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'docdirect');
				endif;
				echo '<div class="col-md-12">';
				$qrystr = '';
				if ($wp_query->found_posts > get_option('posts_per_page')) {
					if (function_exists('docdirect_prepare_pagination')) {
						echo docdirect_prepare_pagination(wp_count_posts()->publish, get_option('posts_per_page'));
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
	</div>
  </div>
</div>
<?php get_footer(); ?>
