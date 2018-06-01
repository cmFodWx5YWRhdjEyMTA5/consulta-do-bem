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

$order   = 'DESC';
$orderby = 'ID';
$show_posts = get_option('posts_per_page');

//total posts Query 
$query_args = array(
    'posts_per_page' => -1,
    'post_type' => 'sp_questions',
    'order' => $order,
    'orderby' => $orderby,
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1);

$query = new WP_Query($query_args);
$count_post = $query->post_count;

//Main Query 
$query_args = array(
    'posts_per_page' => $show_posts,
    'post_type' => 'sp_questions',
    'paged' => $paged,
    'order' => $order,
    'orderby' => $orderby,
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1);

$query = new WP_Query($query_args);
?>
<div class="sp-sc-post-questions tg-haslayout">
    <?php if (!empty($atts['heading']) || !empty($atts['description'])) { ?>
        <div class="col-xs-12 col-sm-12 col-md-12">
			<?php if (!empty($atts['heading'])) { ?>
				<h2><?php echo esc_attr($atts['heading']); ?></h2>
			<?php } ?>
			<div class="sp-searchQBox">
			  <input type="text" name="search_string" id="ask_search_question" value="" class="form-control field-control" placeholder="<?php esc_html_e('E.g. I am a 25yr old male &amp; have backache for last 2 months', 'docdirect');?>">
			  <input class="submitquestion" data-toggle="modal" id="ask_btn" type="button" data-target=".AskQ" value="<?php esc_html_e('SUBMIT QUESTION', 'docdirect');?>">
			</div>
        </div>
    <?php } ?>
    <?php if (!empty($atts['show_recent']) && $atts['show_recent'] === 'yes') { ?>
    <div class="tg-content tg-companyfeaturebox">
        <?php
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $post;
                $question_by = get_post_meta($post->ID, 'question_by', true);
				$question_to = get_post_meta($post->ID, 'question_to', true);
				$category = get_post_meta($post->ID, 'question_cat', true);
	
				$category_icon = '';
				if (function_exists('fw_get_db_post_option') && !empty( $category )) {
					$category_icon = fw_get_db_post_option($category, 'dir_icon', true);
				}
                ?>
                <div class="col-xs-12 col-sm-12 col-md-6  col-lg-6 tg-verticaltop">
					<div class="tg-question">
						<div class="tg-questioncontent">
							<div class="tg-answerholder spq-v2">
								<?php if (!empty($category_icon)) {?>
									<figure class="tg-docimg"><span class="<?php echo esc_attr($category_icon); ?> tg-categoryicon"></span></figure>
								<?php }?>
								<div class="tg-questionbottom">
									<div class="sp-title-holder">
										<h4><a href="<?php echo esc_url(get_permalink()); ?>"> <?php echo esc_attr(get_the_title()); ?> </a></h4>
										<?php if (!empty($category)) { ?>
											<a class="tg-themetag tg-categorytag" href="javascript:;">
												<?php echo esc_attr(get_the_title($category)); ?>
											</a>
										<?php } ?>
									</div>
									<?php fw_ext_get_total_votes_and_answers_html($post->ID);?>
								</div>
							</div>
						</div>
						<div class="tg-matadatahelpfull">
							<?php fw_ext_get_views_and_time_html($post->ID);?>
							<?php fw_ext_get_votes_html($post->ID,esc_html__('Is this helpful?', 'docdirect'));?>
						</div>
					</div>
                </div>
                <?php
            } wp_reset_postdata();
        } 
        ?>
        <?php if (isset($count_post) && $count_post > $show_posts) : ?>
            <div class="col-md-12">
                <?php docdirect_prepare_pagination($count_post, $show_posts); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php }?>
    
</div>