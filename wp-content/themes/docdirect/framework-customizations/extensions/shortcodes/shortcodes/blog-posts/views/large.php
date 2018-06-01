<?php if (!defined('FW')) die( 'Forbidden' );
/**
 * @var $atts
 */
?>
<div class="sc-blogs">
<div class="tg-view tg-blog-list">
		<?php
		global $paged;
		$pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
		$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
		//paged works on single pages, page - works on homepage
		$paged = max( $pg_page, $pg_paged );

		if (isset($atts['get_mehtod']['gadget']) && $atts['get_mehtod']['gadget'] === 'by_posts' && !empty($atts['get_mehtod']['by_posts']['posts'])) {
			$posts_in['post__in'] = !empty( $atts['get_mehtod']['by_posts']['posts'] ) ? $atts['get_mehtod']['by_posts']['posts'] : array();
			$order    = 'DESC';
			$orderby  = 'ID';
			$show_posts  = !empty($atts['get_mehtod']['by_posts']['show_posts']) ? $atts['get_mehtod']['by_posts']['show_posts'] : '-1';
		} else {
			$cat_sepration = array();
			$cat_sepration = $atts['get_mehtod']['by_cats']['categories'];
			$order    	 = !empty($atts['get_mehtod']['by_cats']['order']) ? $atts['get_mehtod']['by_cats']['order'] : 'DESC';
			$orderby  	 = !empty($atts['get_mehtod']['by_cats']['orderby']) ? $atts['get_mehtod']['by_cats']['orderby'] : 'ID';
			$show_posts  = !empty($atts['get_mehtod']['by_cats']['show_posts']) ? $atts['get_mehtod']['by_cats']['show_posts'] : '-1';

			if ( !empty($cat_sepration) ) {
				$slugs = array();
				foreach ($cat_sepration as $key => $value) {
					$term = get_term($value, 'category');
					$slugs[] = $term->slug;
				}

				$filterable = $slugs;
				$tax_query['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'terms' => $filterable,
						'field' => 'slug',
				));
			}
		}

		//total posts Query 
		$query_args = array(
			'posts_per_page' => -1,
			'post_type' => 'post',
			'order' => $order,
			'orderby' => $orderby,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1);

		//By Categories
		if (!empty($cat_sepration)) {
			$query_args = array_merge($query_args, $tax_query);
		}
		//By Posts 
		if (!empty($posts_in)) {
			$query_args = array_merge($query_args, $posts_in);
		}
		$query = new WP_Query($query_args);
		$count_post = $query->post_count;  

		//Main Query 
		$query_args = array(
			'posts_per_page' => $show_posts,
			'post_type' => 'post',
			'paged' => $paged,
			'order' => $order,
			'orderby' => $orderby,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1);

		//By Categories
		if (!empty($cat_sepration)) {
			$query_args = array_merge($query_args, $tax_query);
		}
		//By Posts 
		if (!empty($posts_in)) {
			$query_args = array_merge($query_args, $posts_in);
		}	
		$query = new WP_Query($query_args);
		
		while($query->have_posts()) : $query->the_post();
			global $post;
			$width  = '1170';
			$height = '400';
			$thumbnail	= docdirect_prepare_thumbnail($post->ID ,$width,$height);
		?>
		<article class="tg-post">
			<div class="tg-box">
				<figure class="tg-feature-img">
					<?php if( isset( $thumbnail ) && !empty( $thumbnail ) ){?>
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php echo sanitize_title( get_the_title() ); ?>"></a>
					<?php }?>
					<ul class="tg-metadata">
						<li><i class="fa fa-clock-o"></i><time datetime="<?php echo date_i18n('Y-m-d', strtotime(get_the_date('Y-m-d',$post->ID))); ?>"><?php echo date_i18n('d M, Y', strtotime(get_the_date('Y-m-d',$post->ID))); ?></time> </li>
						<li><i class="fa fa-comment-o"></i><a href="<?php echo esc_url( comments_link());?>">&nbsp;<?php comments_number( esc_html__('0 Comments','docdirect'), esc_html__('1 Comment','docdirect'), esc_html__('% Comments','docdirect') ); ?></a></li>
					</ul>
				</figure>
				<div class="tg-contentbox">
					<div class="tg-displaytable">
						<div class="tg-displaytablecell-large">
							<div class="tg-heading-border tg-small">
								<h3><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?> </a></h3>
							</div>
							<?php if( !empty( $atts['excerpt'] ) ){?>
								<div class="tg-description">
									<?php docdirect_prepare_excerpt($atts['excerpt'],'false',''); ?>
								</div>
							<?php }?>
						</div>
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"><span class="tg-show"><em class="icon-add"></em></span></a>
					</div>
					<?php
					  if (is_sticky()) :
					   echo '<div class="sticky-post-wrap">
								  <div class="sticky-txt">
								   <em class="tg-featuretext">'.esc_html__('Featured','docdirect').'</em>
								   <i class="fa fa-bolt"></i>
								  </div>
							 </div>';
					  endif;
					?>
				</div>
			</div>
		</article>
		<?php endwhile; wp_reset_postdata(); ?>
</div>
<?php if(isset($atts['show_pagination']) && $atts['show_pagination'] == 'yes') : ?>
	<?php docdirect_prepare_pagination($count_post,$show_posts);?>
<?php endif; ?>
</div>
