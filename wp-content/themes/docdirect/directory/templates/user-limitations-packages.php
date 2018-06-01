<?php
/**
 * User Packages and settings
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;
$current_date	= date('Y-m-d H:i:s');

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

if (function_exists('fw_get_db_settings_option')) {
	$currency_select = fw_get_db_settings_option('currency_select');
	$currency_sign = fw_get_db_settings_option('currency_sign');
	$paypal_enable = fw_get_db_settings_option('paypal_enable');
	$enable_strip = fw_get_db_settings_option('enable_strip');
	$authorize_enable = fw_get_db_settings_option('authorize_enable');
} else{
	$currency_select = 'USD';
	$currency_sign = '$';
	$paypal_enable = '';
	$enable_strip = '';
	$authorize_enable = '';
}

if( empty( $currency_select ) ){
	$currency_select = 'USD';
	$currency_sign   = '$';
}

$current_package	= get_user_meta($url_identity, 'user_current_package', true);
$package_expiry		= get_user_meta($url_identity, 'user_current_package_expiry', true);
$user_featured		= get_user_meta($url_identity, 'user_featured', true);

$article_limit = 0;
if (function_exists('fw_get_db_settings_option')) {
	$article_limit = fw_get_db_settings_option('article_limit');
}
$article_limit = !empty( $article_limit ) ? $article_limit  : 0;

$package_expiry	= !empty( $package_expiry ) ? date( 'Y-m-d', $package_expiry ) : '';
$package_counter	= !empty( $package_expiry ) ? date( 'M d, Y H:i:s', strtotime( $package_expiry ) ) : '';
$package_title	= !empty( $current_package ) ? get_the_title($current_package) : esc_html__('NILL','docdirect');

if( isset( $enable_strip ) && $enable_strip === 'on' ) {
	//Strip Init
	docdirect_init_stripe_script();
}
?>
<div class="tg-heading-border tg-small">
	<h3><?php esc_html_e('Packages','docdirect');?></h3>
</div>
<div class="packages-payments">
    <div class="tg-pkgexpireyandcounter">
	  <div class="tg-pkgexpirey"><span><?php esc_html_e('Current Package','docdirect');?></span>
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
    <form action="#" method="post" class="renew-package">
        <div class="row">		
        <?php	
            $args = array('posts_per_page' => '-1', 
				'post_type' => 'directory_packages', 
				'orderby' => 'ID', 
				'post_status' => 'publish',
				'suppress_filters' => false
			);
            $cust_query = get_posts($args);
        
            if (isset($cust_query) && is_array($cust_query) && !empty($cust_query)) {
                $ounterpack	= 0;	
                foreach ($cust_query as $key => $pack) {
                    $active				= isset( $ounterpack ) && $ounterpack === 0 ? 'checked' : '';
                    $price 				= fw_get_db_post_option($pack->ID, 'price', true);
                    $duration 			= fw_get_db_post_option($pack->ID, 'duration', true);
                    $featured 			= fw_get_db_post_option($pack->ID, 'featured', true);
                    $pac_subtitle 		= fw_get_db_post_option($pack->ID, 'pac_subtitle', true);
                    $short_description  = fw_get_db_post_option($pack->ID, 'short_description', true);
					$articles 			= fw_get_db_post_option($pack->ID, 'articles', true);
					$questions 			= fw_get_db_post_option($pack->ID, 'dd_qa', true);
					
					$articles			= intval( $articles ) + intval( $article_limit );
					$featured_expiry	= fw_get_db_post_option($pack->ID, 'featured_expiry', true);
					
					$active_package		= '';
					$active_class		= '';
					
					if( isset( $current_package ) && $current_package == $pack->ID ) {
						$active_package	= 'checked';
						$active_class	= '';
					}
                    ?>
                        
                    <div class="col-md-4 col-sm-6 col-xs-6 tg-packageswidth">
                        <div class="tg-checkbox">
                            <input type="radio" <?php echo esc_attr( $active_package );?> name="packs" value="<?php echo esc_attr( $pack->ID );?>" id="pack-<?php echo esc_attr( $pack->ID );?>">
                            <label for="pack-<?php echo esc_attr( $pack->ID );?>">
                                <div class="tg-packages <?php echo esc_attr( $active_class );?>">
                                    
                                    <?php if( isset( $featured ) && !empty( $featured ) ){?>
                                        <span class="tg-featuredicon"><em class="fa fa-bolt"></em></span>
                                    <?php }?>
                                    <h2><?php echo esc_attr( get_the_title($pack->ID) );?></h2>
                                    <?php if( isset( $pac_subtitle ) && !empty( $pac_subtitle ) ){?>
                                    <h3><?php echo esc_attr( $pac_subtitle );?></h3>
                                    <?php }?>
                                    <strong><i><?php echo esc_attr( $currency_sign );?></i><?php echo esc_attr( $price );?></strong>
                                    <?php if( isset( $duration ) && !empty( $duration ) ){?>
                                        <p><?php echo esc_attr( $duration );?><?php echo ( $duration > 1 ? esc_html__( ' Days','docdirect' ) : esc_html__( 'Day','docdirect' ));?></p>
                                    <?php }?>
                                    <?php if( isset( $short_description ) && !empty( $short_description ) ){?>
                                        <p><?php echo esc_attr( $short_description );?></p>
                                    <?php }?>
									<ul>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'featured_listing');?>"></i><span><?php echo esc_html_e( 'Featured listing','docdirect' );?>&nbsp;x&nbsp;<?php echo intval($featured_expiry);?>&nbsp;<?php esc_html_e( 'Day(s)','docdirect' );?></span></li>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'appointments');?>"></i><span><?php echo esc_html_e( 'Appointments','docdirect' );?></span></li>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'profile_banner');?>"></i><span><?php echo esc_html_e( 'Profile banner','docdirect' );?></span></li>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'insurance');?>"></i><span><?php echo esc_html_e( 'Insurance settings','docdirect' );?></span></li>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'favorite');?>"></i><span><?php echo esc_html_e( 'Favorite listings','docdirect' );?></span></li>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'team');?>"></i><span><?php echo esc_html_e( 'Teams management','docdirect' );?></span></li>
									   <li><i class="<?php echo docdirect_get_package_check($pack->ID,'schedules');?>"></i><span><?php echo esc_html_e( 'Opening Hours/Schedules','docdirect' );?></span></li>
									   
									   <?php if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {?>
									   		<li><i class="fa fa-check"></i><span><?php echo esc_html_e( 'Number of articles','docdirect' );?>&nbsp;(<?php echo intval($articles);?>)</span></li>
									   <?php }?>
									   <?php if ( function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {?>
									  	 <li><i class="<?php echo docdirect_get_package_check($pack->ID,'dd_qa');?>"></i><span><?php echo esc_html_e( 'Question and Answers','docdirect' );?></span></li>
									   <?php }?>
									</ul>
                                    <span class="tg-btn-invoices selected-package"><?php echo esc_html_e( 'Select Package','docdirect' );?></span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <?php $ounterpack++;
                }
            }
            ?>
        </div>
        <div class="gateways-settings">
        <div class="notification_wrap"><div class="notification_text"></div></div>
        <div class="membership-price-header"><?php esc_html_e('Payment Options','docdirect');?></div>
        <div class="system-gateway">
            <label for="doc-payment-bank"><input checked="checked" name="gateway" type="radio" id="doc-payment-bank" value="bank"><?php esc_html_e('Bank Transfer','docdirect');?></label>
            <div class="doc-desc">
                <p><?php esc_html_e('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won\'t be approved until the funds have cleared in our account.','docdirect');?></p>
            </div>
        </div>
        <?php if( isset( $paypal_enable ) && $paypal_enable === 'on' ) {?>
            <div class="system-gateway">
                <label for="doc-payment-paypal"><input name="gateway" type="radio" id="doc-payment-paypal" value="paypal"><?php esc_html_e('Paypal','docdirect');?></label>
            </div>
        <?php }?>
        <?php if( isset( $enable_strip ) && $enable_strip === 'on' ) {?>
            <div class="system-gateway">
                <label for="doc-payment-strip"><input name="gateway" type="radio" id="doc-payment-strip" value="stripe"><?php esc_html_e('Credit Card( Stripe )','docdirect');?></label>
            </div>
        <?php }?>
        <?php if( isset( $authorize_enable ) && $authorize_enable === 'on' ) {?>
            <div class="system-gateway">
                <label for="doc-payment-authorize"><input name="gateway" type="radio" id="doc-payment-authorize" value="authorize"><?php esc_html_e('Authorize.Net','docdirect');?></label>
            </div>
        <?php }?>
        <div class="system-gateway">
            <?php wp_nonce_field('docdirect_renew_nounce', 'renew-process'); ?>
            <button type="submit" class="tg-btn do-process-payment"><?php esc_html_e('Subscribe Now','docdirect');?></button>
        </div>
    </div>
    </form>
</div>