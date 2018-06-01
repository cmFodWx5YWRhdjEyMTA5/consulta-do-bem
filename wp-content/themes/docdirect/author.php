<?php
/**
 * The template for displaying user detail
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Doctor Directory
 */
global $wp_query,$current_user;
$author_profile = $wp_query->get_queried_object();
do_action('docdirect_update_profile_hits',$author_profile->ID); //Update Profile Hits
docdirect_set_user_views($author_profile->ID); //Update profile views
get_header();//Include Headers

if ( apply_filters( 'docdirect_is_visitor', $author_profile->ID ) === false ) {	
$directory_type	= $author_profile->directory_type;
$uni_flag 		  = rand(1,9999);
$privacy		= docdirect_get_privacy_settings($author_profile->ID); //Privacy settings

docdirect_enque_map_library();//init Map
docdirect_enque_rating_library();//rating

$apointmentClass	= 'appointment-disabled';
if( !empty( $privacy['appointments'] )
	&& 
	$privacy['appointments'] == 'on'
 ) {
	$apointmentClass	= 'appointment-enabled';
	if( function_exists('docdirect_init_stripe_script') ) {
		//Strip Init
		docdirect_init_stripe_script();
	}
	
	if( isset( $current_user->ID ) 
	 && 
		$current_user->ID != $author_profile->ID
	){
		$apointmentClass	= 'appointment-enabled';
	} else{
		$apointmentClass	= 'appointment-disabled';
	}
}

docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map


$is_profile_visible	= 'yes';
if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
	$package_expiry    = get_user_meta( $author_profile->ID, 'user_current_package_expiry', true);
	$current_package   = get_user_meta( $author_profile->ID, 'user_current_package', true);
	$current_date	= date('Y-m-d H:i:s');
	if( !empty( $package_expiry ) && $package_expiry >  strtotime($current_date) ){
		$is_profile_visible	= 'yes';
	} else{
		$is_profile_visible	= 'no';
	}
}

$display_settings	= docdirect_profile_display_settings();
$display_settings	= apply_filters('docdirect_filter_profile_display_settings',$display_settings);
	
if( isset( $is_profile_visible ) && $is_profile_visible === 'yes' )	{?>
<?php get_template_part('directory/provider-page/template-author', 'header'); ?>
<div class="container">
  <div class="row">
	<div class="tg-userdetail <?php echo sanitize_html_class( $apointmentClass );?>">
	  <?php get_template_part('directory/provider-page/template-author', 'sidebar'); ?>
	  <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
		<div class="tg-haslayout provider-sections">
			<?php
				foreach( $display_settings as $key => $value  ){
					get_template_part('directory/provider-page/template-author', $key);
				}
			?>
		</div>
	  </div>
	</div>
  </div>
</div>
<?php } else{?>
	<div class="container">
		 <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('You are not allowed to view this page. This users has expired or didn\'t subscribed to any package' ,'docdirect'));?>
	</div>
<?php }?>

<?php } else{?>
	<div class="container">
         <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('Oops! you are not allowed to access this page.','docdirect'));?>
    </div>
<?php }?>
<?php get_footer();?>
<?php
if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'appointments' ) === true ){
	if( isset( $current_user->ID ) 
		&& 
		$current_user->ID != $author_profile->ID
		&&
		is_user_logged_in()
	){
	
		if( !empty( $privacy['appointments'] )
		  && 
			$privacy['appointments'] == 'on'
	 ) {
	
	?>
	<div class="modal fade tg-appointmentpopup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg tg-modalcontent" role="document">
		<form action="#" method="post" class="appointment-form">
		  <fieldset class="booking-model-contents">
			<ul class="tg-navdocappointment" role="tablist">
			  <li class="active"><a href="javascript:;" class="bk-step-1"><?php esc_html_e('1. choose service','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-2"><?php esc_html_e('2. available schedule','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-3"><?php esc_html_e('3. your contact detail','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-4"><?php esc_html_e('4. Payment Mode','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-5"><?php esc_html_e('5. Finish','docdirect');?></a></li>
			</ul>
			<div class="tab-content tg-appointmenttabcontent" data-id="<?php echo esc_attr( $author_profile->ID );?>">
			  <div class="tab-pane active step-one-contents" id="one">
				<?php docdirect_get_booking_step_one($author_profile->ID,'echo');?>
			  </div>
			  <div class="tab-pane step-two-contents" id="two">
				<?php docdirect_get_booking_step_two_calender($author_profile->ID,'echo');?>
			  </div>
			  <div class="tab-pane step-three-contents" id="three"></div>
			  <div class="tab-pane step-four-contents" id="four"></div>
			  <div class="tab-pane step-five-contents" id="five"></div>
			  <div class="tg-btnbox booking-step-button">
				  <button type="button" class="tg-btn bk-step-prev"><?php esc_html_e('Previous','docdirect');?></button>
				  <button type="button" class="tg-btn bk-step-next"><?php esc_html_e('next','docdirect');?></button>
				</div>
			</div>
		  </fieldset>
		</form>
	  </div>
	</div>
<?php }}}?>