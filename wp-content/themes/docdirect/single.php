<?php
/**
 * The template for displaying all single posts.
 *
 * @package Doctor Directory
 */
do_action('docdirect_post_views', get_the_ID(),'set_blog_view');
get_header();

$docdirect_sidebar = 'full';
$section_width = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
if (function_exists('fw_ext_sidebars_get_current_position')) {
    $current_position = fw_ext_sidebars_get_current_position();
    if ($current_position != 'full' && ( $current_position == 'left' || $current_position == 'right' )) {
        $docdirect_sidebar = $current_position;
        $section_width = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
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
		<div id="tg-towcolumns" class="tg-haslayout">
			<div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
				<?php
					while (have_posts()) : the_post();
						global $post;	
						if (function_exists('docdirect_init_owl_script')) {
							docdirect_init_owl_script();
						}
						
						$user_ID = get_the_author_meta('ID');
						$udata = get_userdata( $user_ID );
						$registered = $udata->user_registered;
						
						if( !empty( $user_ID ) ){
							$userprofile_media = apply_filters(
								'docdirect_get_user_avatar_filter',
								 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user_ID),
								 array('width'=>150,'height'=>150) //size width,height
							);
						}
					
						$height = 450;
						$width  = 1170;
						$thumbnail = docdirect_prepare_thumbnail($post->ID, $width, $height);
						$image_src = docdirect_prepare_thumbnail($post->ID, 'full');
						$user_id   = get_the_author_meta('ID');
						$user_url  = get_author_posts_url($user_id);
						$flag = rand(99,99999);
						
						if (!function_exists('fw_get_db_post_option')) {
							$blog_settings = '';
							$enable_author_info = '';
							$enable_comments = '';
							$enable_sharing = '';
						} else {
							$blog_settings = fw_get_db_post_option($post->ID, 'post_settings', true);
							$enable_sharing = fw_get_db_post_option($post->ID, 'enable_sharing', true);
							
							$enable_author_info = fw_get_db_settings_option('enable_author_info');
							$enable_comments = fw_get_db_settings_option('enable_comments');
							
							if( isset( $enable_comments ) && $enable_comments === 'enable' ) {
								$enable_comments = fw_get_db_post_option($post->ID, 'enable_comments', true);
							}
							
							if( isset( $enable_author_info ) && $enable_author_info === 'enable' ) {
								$enable_author_info = fw_get_db_post_option($post->ID, 'enable_author_info', true);
							}
						}
						
						
						if (isset($blog_settings['gadget']) && $blog_settings['gadget'] == 'gallery') {
							$blogClass = 'tg-gallery-post';
						} else if (isset($blog_settings['gadget']) && $blog_settings['gadget'] == 'video') {
							$url = parse_url($post_video);
							if ($url['host'] == 'soundcloud.com') {
								$blogClass = 'tg-audio-post';
							} else {
								$blogClass = 'tg-video-post';
							}
						} else {
							$blogClass = '';
						}
						
						
						$blog_post_gallery	= array();
						$post_video	= '';
						if( isset( $blog_settings['gallery']['blog_post_gallery'] )  && !empty( $blog_settings['gallery']['blog_post_gallery'] ) ){
							$blog_post_gallery	= $blog_settings['gallery']['blog_post_gallery'];
						}
						
						if( isset( $blog_settings['video']['blog_video_link'] )  && !empty( $blog_settings['video']['blog_video_link'] ) ){
							$post_video	= $blog_settings['video']['blog_video_link'];
						}
						
						?>
						<div id="tg-content" class="tg-content tg-post-detail tg-overflowhidden <?php echo sanitize_html_class( $blogClass );?>">
							<article class="tg-post tg-haslayout">
								<?php if (isset($blog_settings['gadget']) && $blog_settings['gadget'] == 'image' && !empty($thumbnail)) { ?>
									<figure class="tg-post-img">
                                        <a href="<?php echo esc_url( get_the_permalink() ); ?>"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php the_title(); ?>"></a>
                                        <ul class="tg-metadata">
                                            <li><i class="fa fa-clock-o"></i><time datetime="<?php echo date_i18n('Y-m-d', strtotime(get_the_date('Y-m-d',$post->ID))); ?>"><?php echo date_i18n('d M, Y', strtotime(get_the_date('Y-m-d',$post->ID))); ?></time> </li>
                                            <li><i class="fa fa-comment-o"></i><a href="<?php echo esc_url( comments_link());?>">&nbsp;<?php comments_number( esc_html__('0 Comments','docdirect'), esc_html__('1 Comment','docdirect'), esc_html__('% Comments','docdirect') ); ?></a></li>
                                        </ul>
                                        <?php the_tags( '<div class="tg-tags"><span>'.esc_html__('Tags:','docdirect').'</span><ul class="tg-tag"><li>', '</li><li>', '</li></ul></div>' ); ?>
                                        </figure>
								<?php
								} elseif (isset($blog_settings['gadget']) && $blog_settings['gadget'] === 'gallery' && !empty($blog_post_gallery) && $blog_post_gallery != '') {
									$uniq_flag = rand(99,99999);
									?>
										<figure class="tg-post-img">
                                            <div id="tg-post-slider-<?php echo esc_attr($uniq_flag); ?>" class="post-slider">
                                            <?php
                                            foreach ($blog_post_gallery as $blog_gallery) {
                                                $attachment_id = $blog_gallery['attachment_id'];
                                                $image_data = wp_get_attachment_image_src($attachment_id, 'docdirect_blog_listing');
                                                if (isset($image_data) && !empty($image_data) && $image_data[0] != '') {
                                                    ?>
                                                <div class="item">
                                                    <figure><img src="<?php echo esc_url($image_data[0]); ?>" alt="<?php echo get_bloginfo('name'); ?>"></figure>
                                                </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                            </div>
											<script>
											jQuery(document).ready(function () {
												jQuery("#tg-post-slider-<?php echo esc_js($uniq_flag); ?>").owlCarousel({
													items:3,
													rtl: <?php docdirect_owl_rtl_check();?>,
													nav: true,
													dots: false,
													autoplay: true,
													navText : ['<i class="doc-btnprev icon-arrows-1"></i>','<i class="doc-btnnext icon-arrows"></i>'],
												});
											});
										</script>
                                       </figure> 
									<?php
									} elseif (isset($blog_settings['gadget']) && $blog_settings['gadget'] === 'video') {
			
										$height = 450;
										$width  = 1140;
										$url = parse_url( $post_video );
										if ( isset( $url["SERVER_NAME"] ) 
											&& isset( $url["host"] ) 
											&& $url['host'] == $_SERVER["SERVER_NAME"]
										) {
											echo '<figure class="tg-post-img"><div class="video">';
											echo do_shortcode('[video width="' . $width . '" height="' . $height . '" src="' . $post_video . '"][/video]');
											echo '</div></figure>';
										} else {
			
											if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
												echo '<figure class="tg-post-img"><div class="video">';
												$content_exp = explode("/", $post_video);
												$content_vimo = array_pop($content_exp);
												echo '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
						></iframe></figure>';
												echo '</div>';
											} elseif ($url['host'] == 'soundcloud.com') {
												$height = 205;
												$width = 1140;
												$video = wp_oembed_get($post_video, array('height' => $height));
												$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
												echo '<figure class="tg-post-img"><div class="audio">';
												$video = str_replace($search, '', $video);
												echo str_replace('&', '&amp;', $video);
												echo '</div></figure>';
											} else {
												echo '<figure class="tg-post-img"><div class="video">';
												$content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
												echo '<iframe width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
												echo '</div></figure>';
											}
										}
									}
								?>
								<div class="tg-post-data tg-haslayout">
									<div class="tg-heading-border tg-small">
										<h2><?php the_title(); ?></h2>
									</div>
									<div class="tg-description">
                                        <?php echo do_shortcode( nl2br( get_the_content() ) );?>
									</div>
								</div>	
								<?php if( isset( $enable_sharing ) && $enable_sharing === 'enable' ){?>
									<div class="social-share">
										<?php docdirect_prepare_social_sharing('false','','false','',$thumbnail);?>
									</div>
								<?php }?>	
							</article>
							<?php if ( isset($enable_author_info) && $enable_author_info === 'enable' ) { ?>
							<div class="tg-about-author tg-haslayout">
								<figure class="tg-author-pic tg-border">
									<?php
										if (!empty($userprofile_media) ) {
											echo '<img src="' . esc_url($userprofile_media) . '" alt="'.esc_attr__('Author Avatar','docdirect').'">';
										} else {
											echo get_avatar($user_ID, 80);
										}
									?>
								</figure>
								<div class="tg-author-content">
									<h3><?php esc_html_e('About Author', 'docdirect'); ?></h3>
                                    <div class="tg-description">
                                        <p><?php echo get_the_author_meta('description'); ?></p>
                                    </div>
								</div>
							</div>
							<?php }?>
							<?php
								if( !empty( $enable_comments ) && $enable_comments === 'enable' ){	
									if (comments_open() || get_comments_number()) :
										comments_template();
									endif;
								}
							?>
						</div>
				<?php
					endwhile;
					wp_reset_postdata();
				?>
			</div>
			
			<?php if (function_exists('fw_ext_sidebars_get_current_position')) { ?>
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 sidebar-section <?php echo sanitize_html_class($aside_class); ?>">
				<aside id="tg-sidebar" class="tg-sidebar tg-haslayout"><?php echo fw_ext_sidebars_show('blue'); ?></aside>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
