<?php
/**
 *
 * Author Teams Template.
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

if ( ! empty( $author_profile->teams_data ) && apply_filters('docdirect_do_check_teams',$author_profile->ID ) === true   ) {
  if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'team' ) === true ){
	  $limit = 500;
	  if (empty($paged)) $paged = 1;
	  $offset = ($paged - 1) * $limit;
	  $teams    = $author_profile->teams_data;
	  $teams    = !empty($teams) && is_array( $teams ) ? $teams : array();

	if( !empty( $teams ) ){
	?>
	<div class="our-teams-wrap">
	  <div class="tg-companyfeaturebox tg-ourteam">
		  <div class="tg-userheading">
			<h2><?php esc_html_e('Our Team','docdirect'); ?></h2>
		  </div>
		  <ul class="tg-teammembers">
			<?php 
				$total_users = (int)count($teams); //Total Users              
				$query_args	= array(
										'role'  => 'professional',
										'order' => 'DESC',
										'orderby' => 'ID',
										'include' => $teams
									 );

				$query_args['number']	= $limit;
				$query_args['offset']	= $offset;

				$user_query  = new WP_User_Query($query_args);
				if ( ! empty( $user_query->results ) ) {
				  foreach ( $user_query->results as $user ) {

					$user_link = get_author_posts_url($user->ID);
					$username = docdirect_get_username($user->ID);
					$user_email = $user->user_email;
					$avatar = apply_filters(
										'docdirect_get_user_avatar_filter',
										 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user->ID),
										 array('width'=>150,'height'=>150) //size width,height
									);
				?>
				<li data-id="<?php echo esc_attr( $user->ID );?>" id="team-<?php echo esc_attr( $user->ID );?>">
					<div class="tg-teammember">
						<figure><a href="<?php echo esc_url( $user_link );?>"><img width="60" height="60" src="<?php echo esc_url( $avatar );?>"></a></figure>
						<div class="tg-memberinfo">
							<h5><a href="<?php echo esc_url( $user_link );?>"><?php echo esc_attr( $username );?></a></h5>
							<a href="<?php echo esc_url( $user_link );?>"><?php esc_html_e('View Full Profile','docdirect'); ?></a>
						</div>
					</div>
				</li>
			 <?php }}?>
		  </ul>
	  </div>
	  <?php 
		//Pagination
		if( ( isset( $total_users ) && isset( $limit )  )
			&&
			$total_users > $limit 
		) {?>
		  <div class="tg-btnarea">
				<?php docdirect_prepare_pagination($total_users,$limit);?>
		  </div>
	  <?php }?>
  </div>
<?php }}}?>
