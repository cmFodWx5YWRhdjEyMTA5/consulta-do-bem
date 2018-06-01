<?php
/**
 *
 * Author Video Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $current_user;
$author_profile = $wp_query->get_queried_object();

if( isset( $author_profile->video_url ) && !empty( $author_profile->video_url ) ) {?>
  <div class="tg-presentationvideo">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('Presentation Video','docdirect');?></h2>
	</div>
	<?php
		$height = 200;
		$width  = 368;
		$post_video = $author_profile->video_url;
		$url = parse_url( $post_video );
		if ($url['host'] == $_SERVER["SERVER_NAME"]) {
			echo '<div class="tg-video doc-haslayout">';
			echo do_shortcode('[video width="' . $width . '" height="' . $height . '" src="' . $post_video . '"][/video]');
			echo '</div>';
		} else {

			if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
				echo '<div class="tg-video doc-haslayout">';
				$content_exp = explode("/", $post_video);
				$content_vimo = array_pop($content_exp);
				echo '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
></iframe>';
				echo '</div>';
			} elseif ($url['host'] == 'soundcloud.com') {
				$video = wp_oembed_get($post_video, array('height' => $height));
				$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
				echo '<div class="tg-video doc-haslayout">';
				$video = str_replace($search, '', $video);
				echo str_replace('&', '&amp;', $video);
				echo '</div>';
			} else {
				echo '<div class="tg-video doc-haslayout">';
				$content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
				echo '<iframe width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
				echo '</div>';
			}
		}
	?>
  </div>
<?php }