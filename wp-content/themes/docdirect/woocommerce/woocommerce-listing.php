<?php
/**
 * @Woocommerce Product Listing
 * @return {}
 */
 
 

$content_div	= 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
?>	
<div class="container">
    <div class="row">
        <div id="tg-twocolumns" class="tg-twocolumns">
			<div class="<?php echo esc_attr( $content_div );?>">
			  <div id="tg-content" class="tg-content">
                <div class="tg-products tg-productsgrid">
                    <div class="tg-featureproducts">
						<div class="row">
							<?php 
								if ( have_posts() ) {
									  while ( have_posts() ) : the_post(); 
											get_template_part( 'woocommerce/layouts/grid', 'layout' ); 
									  endwhile; 
								} else{
									esc_html_e('No Product Found.','docdirect');
								}
							?>
						</div>
					</div>
					<?php wc_get_template( 'loop/pagination.php' );?>
			    </div>
			   </div>
            </div>	
		</div>
	</div>
</div>
