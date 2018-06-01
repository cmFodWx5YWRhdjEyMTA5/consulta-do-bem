<?php
/**
 *
 * Author Sidebar Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $wp_query, $current_user;
/**
 * Get User Queried Object Data
 */
$author_profile = $wp_query->get_queried_object();
?>
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
	<aside id="tg-sidebar" class="tg-sidebar">
	  <div class="tg-widget tg-widgetuserdetail">
		<?php get_template_part('directory/provider-page/template-author-sidebar', 'avatar'); ?>
		<?php get_template_part('directory/provider-page/template-author-sidebar', 'contact-info'); ?>
	  </div>
	  <?php get_template_part('directory/provider-page/template-author-sidebar', 'claim'); ?>
	  <?php if (is_active_sidebar('user-page-sidebar')) {?>
		  <div class="tg-doctors-list tg-haslayout">
			<?php dynamic_sidebar('user-page-sidebar'); ?>
		  </div>
	   <?php }?>
	</aside>
</div>