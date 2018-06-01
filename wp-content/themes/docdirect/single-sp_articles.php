<?php
/**
 *
 * The template used for displaying default article post style
 *
 * @package   Service Providers
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
do_action('docdirect_post_views', get_the_ID(),'article_views');
get_header();
global $post;


$docdirect_sidebar = 'full';
$section_width = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
if (function_exists('fw_ext_sidebars_get_current_position')) {
    $current_position = fw_ext_sidebars_get_current_position();
    if ($current_position != 'full' && ( $current_position == 'left' || $current_position == 'right' )) {
        $docdirect_sidebar = $current_position;
        $section_width = 'col-lg-8 col-md-8 col-sm-7 col-xs-12';
    }
}

if (isset($docdirect_sidebar) && $docdirect_sidebar == 'right') {
    $aside_class = 'pull-right';
    $content_class = 'pull-left';
} else {
    $aside_class = 'pull-left';
    $content_class = 'pull-right';
}

?>
<div class="container">
    <div class="row">
        <div id="tg-twocolumns" class="tg-twocolumns article-detail-page">
            <div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        global $post, $thumbnail, $image_alt;
                        $height = 400;
                        $width = 1180;
                        $user_ID = get_the_author_meta('ID');
                        $user_url = get_author_posts_url($user_ID);
                        $thumbnail = docdirect_prepare_thumbnail($post->ID, $width, $height);
                        $post_thumbnail_id = get_post_thumbnail_id($post->ID);
						$post_view_count    = get_post_meta($post->ID, 'article_views', true);

                        $udata = get_userdata($user_ID);
                        $registered = $udata->user_registered;

                        $avatar = apply_filters(
                                'docdirect_get_media_filter', docdirect_get_user_avatar(array('width' => 100, 'height' => 100), $user_ID), array('width' => 100, 'height' => 100)
                        );
						

                        $thumb_meta = array();
                        if (!empty($post_thumbnail_id)) {
                            $thumb_meta = docdirect_get_image_metadata($post_thumbnail_id);
                        }
                        $image_title = !empty($thumb_meta['title']) ? $thumb_meta['title'] : 'no-name';
                        $image_alt = !empty($thumb_meta['alt']) ? $thumb_meta['alt'] : $image_title;
                        ?>
                        <div id="tg-content" class="tg-content">
                            <article class="tg-post tg-detailpage tg-postdetail">
                                <?php if (!empty($thumbnail)) { ?>
                                    <figure class="tg-themepost-img">
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                                    </figure>
                                <?php } ?>
                                <div class="tg-postcontent">
                                    <div class="tg-title">
                                        <h3><?php docdirect_get_post_title($post->ID); ?></h3>
                                    </div>
                                    <ul class="tg-postmatadata">
                                        <li>
                                            <a href="javascript:;">
                                                <i class="fa fa-user"></i><span>
                                                    <?php
														esc_html_e(' Written by', 'docdirect');
														echo '&nbsp;'.esc_attr(get_the_author()).'&nbsp;';
														esc_html_e('for', 'docdirect');
														echo '&nbsp;'.get_bloginfo('name');
                                                    ?>
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="javascript:;"><?php docdirect_get_post_date($post->ID); ?></a></li>
                                         <li><a href="javascript:;"><i class="fa fa-eye"></i><span><?php echo intval($post_view_count); ?></span></a></li>
                                    </ul>
                                </div>
                                <div class="tg-description article-detail-wrap">
                                    <?php
										the_content();
										wp_link_pages(array(
											'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'docdirect') . '</span>',
											'after' => '</div>',
											'link_before' => '<span>',
											'link_after' => '</span>',
										));
										edit_post_link(esc_html__('Edit', 'docdirect'), '<span class="edit-link">', '</span>');
                                    ?>
                                </div>
                            </article>
							<div class="social-share">
								<?php docdirect_prepare_social_sharing('false','','false','',$thumbnail);?>
							</div>
                            <div class="tg-author">
                                <?php if (!empty($avatar)) { ?>
                                    <figure>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <img src="<?php echo esc_url($avatar); ?>" alt="<?php esc_attr_e('Avatar', 'docdirect'); ?>"></a>
                                    </figure>
                                <?php } ?>
                                <div class="tg-authorcontent">
                                    <div class="tg-authorbox">
                                        <div class="tg-authorhead">
                                            <div class="tg-leftbox">
                                                <div class="tg-name">
                                                    <h4><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo get_the_author(); ?></a></h4>
                                                    <span><?php esc_html_e('Author Since', 'docdirect'); ?>:&nbsp; <?php echo date_i18n(get_option('date_format'), strtotime($registered)); ?></span>
                                                </div>
                                            </div>
                                            <?php
                                            $facebook = get_the_author_meta('facebook', $user_ID);
                                            $twitter = get_the_author_meta('twitter', $user_ID);
                                            $pinterest = get_the_author_meta('pinterest', $user_ID);
                                            $linkedin = get_the_author_meta('linkedin', $user_ID);
                                            $tumblr = get_the_author_meta('tumblr', $user_ID);
                                            $google = get_the_author_meta('google', $user_ID);
                                            $instagram = get_the_author_meta('instagram', $user_ID);
                                            $skype = get_the_author_meta('skype', $user_ID);
                                            ?>
                                            <div class="tg-rightbox">
                                                <?php
                                                if (!empty($facebook) || !empty($twitter) || !empty($pinterest) || !empty($linkedin) || !empty($tumblr) || !empty($google) || !empty($instagram) || !empty($skype)
                                                ) {
                                                    ?>
                                                    <ul class="tg-socialicons">
                                                        <?php if (isset($facebook) && !empty($facebook)) { ?>
                                                            <li class="tg-facebook">
                                                                <a href="<?php echo esc_url(get_the_author_meta('facebook', $user_ID)); ?>">
                                                                    <i class="fa fa-facebook"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($twitter) && !empty($twitter)) { ?>
                                                            <li class="tg-twitter">
                                                                <a href="<?php echo esc_url(get_the_author_meta('twitter', $user_ID)); ?>">
                                                                    <i class="fa fa-twitter"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($pinterest) && !empty($pinterest)) { ?>
                                                            <li class="tg-pinterest">
                                                                <a href="<?php echo esc_url(get_the_author_meta('pinterest', $user_ID)); ?>">
                                                                    <i class="fa fa-pinterest-p"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($linkedin) && !empty($linkedin)) { ?>
                                                            <li class="tg-linkedin">
                                                                <a href="<?php echo esc_url(get_the_author_meta('linkedin', $user_ID)); ?>">
                                                                    <i class="fa fa-linkedin"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($tumblr) && !empty($tumblr)) { ?>
                                                            <li class="tg-tumblr">
                                                                <a href="<?php echo esc_url(get_the_author_meta('tumblr', $user_ID)); ?>">
                                                                    <i class="fa fa-tumblr"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($google) && !empty($google)) { ?>
                                                            <li class="tg-googleplus">
                                                                <a href="<?php echo esc_url(get_the_author_meta('google', $user_ID)); ?>">
                                                                    <i class="fa fa-google-plus"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($instagram) && !empty($instagram)) { ?>
                                                            <li class="tg-dribbble">
                                                                <a href="<?php echo esc_url(get_the_author_meta('instagram', $user_ID)); ?>">
                                                                    <i class="fa fa-instagram"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (isset($skype) && !empty($skype)) { ?>
                                                            <li  class="tg-skype">
                                                                <a href="<?php echo esc_url(get_the_author_meta('skype', $user_ID)); ?>">
                                                                    <i class="fa fa-skype"></i>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="tg-description">
                                            <p><?php echo nl2br(get_the_author_meta('description', $user_ID)); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php if (function_exists('fw_ext_sidebars_get_current_position')) { ?>
			<div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 pull-right sidebar-section <?php echo sanitize_html_class($aside_class); ?>">
				<aside id="tg-sidebar" class="tg-sidebar tg-haslayout">
				 	<?php get_sidebar('articles');?>
					<?php echo fw_ext_sidebars_show('blue'); ?>
				</aside>
			</div>
			<?php } ?>
        </div>
    </div>
</div>
<?php
get_footer();
