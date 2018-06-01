<?php
/**
 *
 * User Packages
 *
 * @package   Listingo
 * @author    Themographics
 * @link      http://themographics.com/
 * @since 1.0
 */
get_header();
global $current_user,$woocommerce;
$current_date	= date('Y-m-d H:i:s');

$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= intval( $_GET['identity'] );
}

$article_limit = 0;
if (function_exists('fw_get_db_settings_option')) {
	$article_limit = fw_get_db_settings_option('article_limit');
}
$article_limit = !empty( $article_limit ) ? $article_limit  : 0;

$current_package	= get_user_meta($url_identity, 'user_current_package', true);
$package_expiry		= get_user_meta($url_identity, 'user_current_package_expiry', true);
$user_featured		= get_user_meta($url_identity, 'user_featured', true);

$package_expiry	= !empty( $package_expiry ) ? date( 'Y-m-d', $package_expiry ) : '';

if( !empty( $package_expiry ) && strtotime( $package_expiry )  > strtotime( $current_date ) ) {
	$package_title	= !empty( $current_package ) ? get_the_title($current_package) : esc_html__('NILL','docdirect');
}else{
	$package_title	=  esc_html__('NILL','docdirect');
}
?>

<div class="tg-formtheme">
  <div class="pacl-wrap">
    <div class="tg-pkgexpireyandcounter">
	  <div class="tg-pkgexpirey">
	  	<span><?php esc_html_e('Current Package','docdirect');?></span>
		<h3><?php echo esc_attr($package_title);?></h3>
	  </div>	
      <?php if( !empty( $package_expiry ) && strtotime( $package_expiry )  > strtotime( $current_date ) ) {?>
		  <div class="tg-timecounter tg-expireytimecounter">
			<div id="tg-countdown" class="tg-countdown"></div>
			<div id="tg-note" class="tg-note"></div>
		  </div>
		  <?php wp_add_inline_script('docdirect_user_profile', 'docdirect_package_counter("'.esc_attr($package_expiry).'");'); ?>
      <?php }?>
    </div>
    <div class="tg-dashboardbox">
      <div class="tg-dashboardtitle">
        <h2><?php esc_html_e('Update Your Package', 'docdirect'); ?></h2>
      </div>
      <div class="tg-packagesbox">
        <div class="tg-pkgplans">
          <div class="row">
            <?php
			if (class_exists('WooCommerce')) {
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'order' => 'DESC',
					'orderby' => 'ID',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'suppress_filters'  => false
				);

				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						global $product;
						$dd_duration 	= get_post_meta( $product->get_id(), 'dd_duration', true );
						$dd_featured	= get_post_meta( $product->get_id(), 'dd_featured', true );
						$dd_articles 	= get_post_meta( $product->get_id(), 'dd_articles', true );
						$dd_articles	= intval( $dd_articles ) + intval( $article_limit );
					?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 pull-left">
					  <div class="tg-packages">
						<div class="tg-pkgplanhead">
						  <h3><?php the_title();?></h3>
						  <h4><?php echo force_balance_tags( $product->get_price_html() ); ?> <em><?php esc_html_e( 'for','docdirect' );?>&nbsp;<?php echo intval($dd_duration);?>&nbsp;<?php esc_html_e( 'days','docdirect' );?></em></h4>
						</div>
						 
						<ul>
					   	   <?php if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){?>
						   		<li><i class="fa fa-check"></i><span><?php echo esc_html_e( 'Featured listing','docdirect' );?>&nbsp;x&nbsp;<?php echo intval($dd_featured);?>&nbsp;<?php esc_html_e( 'Day(s)','docdirect' );?></span></li>
							   <li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_appointments');?>"></i><span><?php echo esc_html_e( 'Appointments','docdirect' );?></span></li>
							   <li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_banner');?>"></i><span><?php echo esc_html_e( 'Profile banner','docdirect' );?></span></li>
							   <li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_insurance');?>"></i><span><?php echo esc_html_e( 'Insurance settings','docdirect' );?></span></li>
							   <li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_favorites');?>"></i><span><?php echo esc_html_e( 'Favorite listings','docdirect' );?></span></li>
							   <li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_teams');?>"></i><span><?php echo esc_html_e( 'Teams management','docdirect' );?></span></li>
							   <li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_hours');?>"></i><span><?php echo esc_html_e( 'Opening Hours/Schedules','docdirect' );?></span></li>
							   
							   <?php if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {?>
							   		<li><i class="fa fa-check"></i><span><?php echo esc_html_e( 'Number of articles','docdirect' );?>&nbsp;(<?php echo intval($dd_articles);?>)</span></li>
							   <?php }?>
							   <?php if ( function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {?>
							   		<li><i class="<?php echo docdirect_get_package_check($product->get_id(),'dd_qa');?>"></i><span><?php echo esc_html_e( 'Question and Answers','docdirect' );?></span></li>
							   <?php }?>
						   <?php }else{?>
						   		<li><i class="fa fa-check"></i><span><?php echo esc_html_e( 'Featured listing','docdirect' );?>&nbsp;x&nbsp;<?php echo intval($dd_duration);?>&nbsp;<?php esc_html_e( 'Day(s)','docdirect' );?></span></li>
						   <?php }?>
						</ul>
						<button class="tg-btn woo-renew-package" data-key="<?php echo intval($product->get_id());?>"><?php esc_html_e( 'Buy/Renew Now','docdirect' );?></button>
					   </div>
					</div>
					<?php
					endwhile;
				} else {?>
					 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-left">
						<?php DoctorDirectory_NotificationsHelper::warning(esc_html__('No packages created yet.', 'docdirect')); ?>
					 </div>
				<?php }
				wp_reset_postdata();
			} else{?>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-left">
					<?php DoctorDirectory_NotificationsHelper::warning(esc_html__('Please install WooCommerce plugin', 'docdirect')); ?>
				 </div>
			<?php }?> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>