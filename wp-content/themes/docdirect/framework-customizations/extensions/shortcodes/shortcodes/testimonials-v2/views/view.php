<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$uniq_flag = fw_unique_increment();
$autoplay	= !empty( $atts['auto'] ) ? $atts['auto'] : 'false';
?>
<div class="sc-testimonials">
    <?php if ( !empty($atts['heading']) && !empty($atts['sub_heading']) && !empty($atts['description']) ) { ?>
        <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-12">
          <div class="doc-section-head">
            <?php if ( !empty($atts['heading']) && !empty($atts['heading']) ) { ?>
            <div class="doc-section-heading">
              <?php if (!empty($atts['heading'])) { ?>
                    <h2><?php echo esc_attr($atts['heading']); ?></h2>
              <?php } ?>
              <?php if (!empty($atts['sub_heading'])) { ?>
                    <span><?php echo esc_attr($atts['sub_heading']); ?></span>
              <?php } ?>
            </div>
            <?php } ?>
            <?php if (!empty($atts['description'])) { ?>
                <div class="doc-description">
                    <p><?php echo esc_attr($atts['description']); ?></p>
                </div>
            <?php } ?>
          </div>
        </div>	
    <?php } ?>
    
    <div id="doc-testimonialsslider-<?php echo esc_attr( $uniq_flag );?>" class="doc-testimonialsslider doc-testimonials owl-carousel">
      <?php 
	  	if ( !empty($atts['data']) ) { 
	  		foreach( $atts['data'] as $key => $value ) {
				$name	= !empty( $value['name'] ) ? $value['name'] : '';	
				$designation	= !empty( $value['designation'] ) ? $value['designation'] : '';	
				$description	= !empty( $value['description'] ) ? $value['description'] : '';	
				$image	= !empty( $value['image'] ) ? $value['image'] : '';	
				$video_url	= !empty( $value['video_url'] ) ? $value['video_url'] : '';	
				
				if( !empty( $image['attachment_id'] ) ){
					$banner	= docdirect_get_image_source($image['attachment_id'],470,305);
				} else{
					$banner	= get_template_directory_uri().'/images/review.jpg';;
				}
			?>
			<div class="item doc-testimonial">
				<?php if( !empty( $video_url ) ) {?>
				<div class="col-xs-12  col-sm-6 doc-verticalmiddle">
					<div class="doc-videoarea">
						<div class="doc-video">
							<figure>
								<?php
									$height = 321;
									$width  = 570;
									$post_video	= $video_url;
									$url = parse_url( $video_url );
									if ( isset( $url["SERVER_NAME"] ) 
										&& isset( $url["host"] ) 
										&& $url['host'] == $_SERVER["SERVER_NAME"]
									) {
										echo '<div class="video">';
										echo do_shortcode('[video width="' . $width . '" height="' . $height . '" src="' . $post_video . '"][/video]');
										echo '</div>';
									} else {
			
										if ( isset( $url["host"] ) 
											&& ( $url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com' )
										) {
											echo '<div class="video">';
											$content_exp = explode("/", $post_video);
											$content_vimo = array_pop($content_exp);
											echo '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
					></iframe>';
											echo '</div>';
										} elseif ( isset( $url["host"] ) && $url['host'] == 'soundcloud.com') {
											$height = 205;
											$width = 1140;
											$video = wp_oembed_get($post_video, array('height' => $height));
											$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
											echo '<div class="audio">';
											$video = str_replace($search, '', $video);
											echo str_replace('&', '&amp;', $video);
											echo '</div>';
										} else {
											echo '<div class="video">';
											$content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
											echo '<iframe width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
											echo '</div>';
										}
									}
								?>
							</figure>
						</div>
					</div>
				</div>
				<?php }?>
				<div class="col-xs-12 col-sm-6 doc-verticalmiddle">
					<?php if( !empty( $description ) ) {?>
						<blockquote><q><?php echo force_balance_tags($description);?></q></blockquote>
					<?php }?>
					<div class="doc-clientdetail">
						<figure class="doc-clientimg"> <img src="<?php echo esc_url( $banner );?>" alt="<?php esc_html_e('reviewer','docdirect');?>"> </figure>
						<?php if( !empty( $name ) || !empty( $designation ) ) {?>
						<div class="doc-clientinfo">
							<?php if( !empty( $name ) ) {?><h3><?php echo esc_attr($name);?></h3><?php }?>
							<?php if( !empty( $designation ) ) {?><span><?php echo esc_attr($designation);?></span> <?php }?>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
      <?php }} ?>
    </div>
    <script>
		jQuery(document).ready(function(e) {
			jQuery("#doc-testimonialsslider-<?php echo esc_attr( $uniq_flag );?>").owlCarousel({
				items:1,
				nav:true,
				rtl: <?php docdirect_owl_rtl_check();?>,
				loop: true,
				dots: false,
				autoplay: <?php echo esc_js($autoplay);?>,
				navText : ['<i class="doc-btnprev fa fa-angle-left"></i>','<i class="doc-btnnext fa fa-angle-right"></i>'],
			});
		});
	</script>
</div>