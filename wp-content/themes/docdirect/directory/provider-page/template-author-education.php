<?php
/**
 *
 * Author Education Template.
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
$directory_type	= $author_profile->directory_type;

if(function_exists('fw_get_db_settings_option')) {
	$education_switch    = fw_get_db_post_option($directory_type, 'education', true);
}

if( isset( $education_switch ) 
  && $education_switch === 'enable'
  && !empty( $author_profile->education )
){?>
  <div class="tg-userexperience tg-userqualification">
	<div class="tg-userheading">
	  <h2><i class="fa fa-graduation-cap"></i><?php esc_html_e('Education','docdirect');?></h2>
	</div>
	<ul>
	<?php 
		foreach( $author_profile->education as $key => $value ){
			$start_year	= '';
			$end_year	= '';
			$period	= '';
			if( !empty( $value['start_date'] ) || !empty( $value['end_date'] ) ){
				if( !empty( $value['start_date'] ) ){
					$start_year	= date_i18n('M, Y',strtotime( $value['start_date']));
				}

				if( !empty( $value['end_date'] ) ){
					$end_year	= date_i18n('M, Y',strtotime( $value['end_date']));
				} else{
					$end_year	= esc_html__('Current','docdirect');
				} 

				if( !empty( $start_year ) || !empty( $end_year ) ){
					$period	= '('.$start_year.'&nbsp;-&nbsp;'.$end_year.')';
				}
			}
		?>
		<li>
			<div class="tg-dotstyletitle">
			  <h3><?php echo esc_attr( $value['title'] );?><strong>&nbsp;&nbsp;</strong><?php echo esc_attr( $period );?></h3>
			  <span><?php echo esc_attr( $value['institute'] );?></span> 
			</div>
			<div class="tg-description">
			  <p><?php echo esc_attr( $value['description'] );?></p>
			</div>
	   </li>
	  <?php }?>
	</ul>
  </div>
<?php }