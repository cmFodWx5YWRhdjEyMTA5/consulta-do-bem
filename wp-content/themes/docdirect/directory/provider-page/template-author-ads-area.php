<?php
/**
 *
 * Author Header Template.
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
$username 		= docdirect_get_username($author_profile->ID);

if (is_active_sidebar('user-page-top')) {?>
  <div class="tg-doctors-list tg-haslayout user-ad-top">
	<?php dynamic_sidebar('user-page-top'); ?>
  </div>
<?php }