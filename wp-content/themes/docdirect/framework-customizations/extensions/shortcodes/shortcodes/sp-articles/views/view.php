<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
global $paged;
$pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);

$show_posts = !empty($atts['show_posts']) ? $atts['show_posts'] : '-1';
$order 		= !empty($atts['order']) ? $atts['order'] : 'DESC';
$orderby 	= !empty($atts['orderby']) ? $atts['orderby'] : 'ID';

$tax_query	= array();

if (isset($atts['get_mehtod']['gadget']) && $atts['get_mehtod']['gadget'] === 'by_posts' && !empty($atts['get_mehtod']['by_posts']['posts'])) {
    $posts_in['post__in'] = !empty($atts['get_mehtod']['by_posts']['posts']) ? $atts['get_mehtod']['by_posts']['posts'] : array();
    
} else {
    $cat_sepration = array();
    $cat_sepration = $atts['get_mehtod']['by_cats']['categories'];
	$article_categories = $atts['get_mehtod']['by_cats']['article_categories'];

    if (!empty($cat_sepration)) {
        $query_relation = array('relation' => 'OR',);
		$category_args = array();
        foreach ($cat_sepration as $key => $value) {
            $category_args[] = array(
				'key' 		=> 'provider_category',
				'value' 	=> $value,
				'compare'   => '='
			);
        }
		
    }
	
	if ( !empty($article_categories) ) {
		$slugs = array();
		foreach ($article_categories as $key => $value) {
			$term = get_term($value, 'article_categories');
			$slugs[] = $term->slug;
		}

		$filterable = $slugs;
		$tax_query['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'article_categories',
				'terms' => $filterable,
				'field' => 'slug',
		));
	}
}

//total posts Query 
$query_args = array(
    'posts_per_page' => -1,
    'post_type' => 'sp_articles',
    'order' => $order,
    'orderby' => $orderby,
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1);

//By provider Categories
if (!empty($cat_sepration)) {
	$meta_query_args	= array();
	$query_relation = array('relation' => 'OR',);
    $meta_query_args[] = array_merge($query_relation, $category_args);
	$query_args['meta_query'] = $meta_query_args;
}

//By article Categories
if (!empty($tax_query)) {
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
    'post_type' => 'sp_articles',
    'paged' => $paged,
    'order' => $order,
    'orderby' => $orderby,
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1);

//By Categories
if (!empty($cat_sepration)) {
	$meta_query_args	= array();
    $query_relation = array('relation' => 'OR',);
    $meta_query_args[] = array_merge($query_relation, $category_args);
	$query_args['meta_query'] = $meta_query_args;
}

//By article Categories
if (!empty($tax_query)) {
	$query_args = array_merge($query_args, $tax_query);
}

//By Posts 
if (!empty($posts_in)) {
    $query_args = array_merge($query_args, $posts_in);
}

$query = new WP_Query($query_args);
?>
<div class="sp-sc-articles tg-haslayout">
    <?php if (!empty($atts['news_heading']) || !empty($atts['news_description'])) { ?>
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-push-1 col-lg-8 col-lg-push-2">
            <div class="doc-section-head">
                <?php if (!empty($atts['news_heading']) || !empty($atts['sub_heading'])) { ?>
                    <div class="doc-section-heading">
                        <?php if (!empty($atts['news_heading'])) { ?>
                        	<h2><?php echo esc_attr($atts['news_heading']); ?></h2>
                        <?php } ?>
                        <?php if (!empty($atts['sub_heading'])) { ?>
							<span><?php echo esc_attr($atts['sub_heading']); ?></span>
						<?php } ?>
                    </div>
                <?php } ?>
                
                <?php if (!empty($atts['news_description'])) { ?>
                    <div class="doc-description">
                        <?php echo wpautop(do_shortcode($atts['news_description'])); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
	<div class="tg-newsandposts tg-bloggird">
		<div class="row">
			<?php
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$height = 200;
					$width  = 370;

					$author_id = $post->post_author;;

					$post_thumbnail_id = get_post_thumbnail_id($post->ID);
					$thumbnail = docdirect_prepare_thumbnail($post->ID, $width, $height);

					$thumb_meta = array();
					if (!empty($post_thumbnail_id)) {
						$thumb_meta = docdirect_get_image_metadata($post_thumbnail_id);
					}
					$image_title = !empty($thumb_meta['title']) ? $thumb_meta['title'] : 'no-name';
					$image_alt = !empty($thumb_meta['alt']) ? $thumb_meta['alt'] : $image_title;
					
					$author_name = docdirect_get_username($author_id);
					$author_avatar = apply_filters(
						'docdirect_get_user_avatar_filter',
						 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $author_id),
						 array('width'=>150,'height'=>150) //size width,height
					);
					
					?>
					<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
						<article class="tg-post">
							<figure class="tg-featuredimg">
								<a href="<?php echo esc_url(get_permalink()); ?>">
									<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($image_alt); ?>">
								</a>
							</figure>
							<div class="tg-title">
								<h3><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_attr(get_the_title()); ?></a></h3>
							</div>
							<div class="tg-serviceprovidercontent">
								<div class="tg-companylogo">
									<a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
										<img src="<?php echo esc_url($author_avatar); ?>" alt="<?php esc_html_e('author', 'docdirect'); ?>">
									</a>
								</div>
								<div class="tg-companycontent">
									<div class="tg-title">
										<h2 class="sp-written-by"><?php esc_html_e('Written by', 'docdirect'); ?>&nbsp;<a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"><?php echo esc_attr($author_name); ?></a></h2>
									</div>
									<h3><?php  echo '<time datetime="' . date_i18n('Y-m-d', strtotime(get_the_date('Y-m-d', $post->ID))) . '"><span>' . date_i18n(get_option('date_format'), strtotime(get_the_date('Y-m-d', $post->ID))) . '</span></time>';;?></h3>
								</div>
							</div>
						</article>
					</div>
					<?php
				} wp_reset_postdata();
			}
			?>
			<?php if ( isset($atts['show_pagination']) && $atts['show_pagination'] == 'yes' && $count_post > $show_posts ) : ?>
				<div class="col-md-12">
					<?php docdirect_prepare_pagination($count_post, $show_posts); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

</div>