<?php
/**
 *
 * Author Gallery Template.
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
$user_gallery	  = $author_profile->user_gallery;

if( !empty( $user_gallery ) ){?>
  <div class="tg-userphotogallery">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('Photo Gallery','docdirect');?></h2>
	</div>
	<ul>
	<?php 
	  foreach( $user_gallery as $key => $value ){
		  $thumbnail	= docdirect_get_image_source($value['id'],150,150);
		  $orignal	  	= docdirect_get_image_source($value['id'],0,0);
		  if( !empty( $thumbnail ) ){
		?>
		<li>
			<figure>
			   <a href="<?php echo esc_url( $orignal );?>" data-rel="prettyPhoto[iframe]"><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( get_the_title( $value['id'] ) );?>">
				<figcaption><span class="icon-add"></span></figcaption>
			   </a>
			</figure>
		</li>
	 <?php }}?>
	</ul>
  </div>
<?php }