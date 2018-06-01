<?php
/**
 * Share template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

global $yith_wcwl;

$share_url  = $yith_wcwl->get_wishlist_url();
$share_url .= get_option( 'permalink-structure' ) != '' ? '&amp;user_id=' : '?user_id=';
$share_url .= get_current_user_id();

$title = esc_html__('Share this:', 'docdirect');
?>
<div class="share-icon">
 <h3><?php echo esc_attr( $title );?></h3>
 <ul>
 <li> <a href="http://www.facebook.com/sharer.php?u=<?php echo esc_attr( $share_url ); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>

 <li> <a href="http://twitter.com/home?status=<?php echo esc_attr( $title ); ?> - <?php echo esc_url( $share_url ); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
 <li> <a href="https://plus.google.com/share?url=<?php echo esc_url( $share_url ); ?>&title=<?php echo esc_attr( $title ); ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
 <li> <a href="http://linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( $share_url ); ?>&title=<?php echo esc_attr( $title ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
 <li> <a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( $share_url ); ?>&description=<?php echo esc_attr( $title ); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
 <li> <a href="mailto:?subject=<?php echo esc_attr( $title ); ?>&body=<?php echo esc_url( $share_url ); ?>"><i class="fa fa-envelope"></i></a></li>
</div>