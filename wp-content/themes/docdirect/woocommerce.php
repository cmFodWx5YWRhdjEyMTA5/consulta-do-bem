<?php
/**
 * @Woocommerce Listing
 * return 
 */

get_header();
global $post;

if(is_shop()){

	do_action('docdirect_product_loop_before');
	get_template_part( 'woocommerce/woocommerce-listing', 'page' );
	do_action('docdirect_product_loop_after');

} else if(is_single()){

	get_template_part( 'woocommerce/single-product', 'page' );

} else if(is_product_category() or is_product_tag()){
	
	get_template_part( 'woocommerce/archive-product', 'page' );

} else{ 
	do_action('docdirect_product_loop_before');?>
		<div class="shop-content">
			<?php if( function_exists('woocommerce_content') ) { woocommerce_content(); } ?>
		</div>
	<?php 
	do_action('docdirect_product_loop_after'); 
}
get_footer();