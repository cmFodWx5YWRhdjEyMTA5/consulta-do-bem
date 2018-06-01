<?php
/**
 *
 * Author Video Template.
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
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
	$theme_type = fw_get_db_settings_option('theme_type');
	$theme_color = fw_get_db_settings_option('theme_color');
}

$review_data	= docdirect_get_everage_rating ( $author_profile->ID );

//rating star color
if ( isset( $theme_type) && $theme_type === 'custom') {
	if ( !empty( $theme_color ) ) {
		$rating_color	= $theme_color;
	} else{
		$rating_color	= '#7dbb00';
	}
} else {
	$rating_color	= '#7dbb00';
}

?>
<div role="tabpanel" class="tg-tabpane tab-pane active" id="reviews">
<?php if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
  <div class="tg-userreviews">
	<div class="tg-userheading">
	  <h2><?php echo intval( apply_filters('docdirect_count_reviews',$author_profile->ID) );?>&nbsp;&nbsp;<?php esc_html_e('Review(s)','docdirect');?></h2> 
	</div>
	<?php if( !empty( $review_data['by_ratings'] ) ) {?>
	<div class="tg-ratingbox">
	  <div class="tg-averagerating">
		<h3><?php esc_html_e('Average Rating','docdirect');?></h3>
		<em><?php echo number_format((float)$review_data['average_rating'], 1, '.', '');?></em>
		<span class="tg-stars"><?php docdirect_get_rating_stars($review_data,'echo','hide');?></span>
	  </div>
	  <div id="tg-userskill" class="tg-userskill">
		<?php 
			foreach( $review_data['by_ratings'] as $key => $value ){
				$final_rate = 0;
				if( !empty( $value['rating'] ) && !empty( $value['rating'] ) ) {
					$get_sum	  = $value['rating'];
					$get_total	= $value['total'];
					$final_rate	= $get_sum/$get_total*100;
				} else{
					$final_rate	= 0;
				}

			?>
			<div class="tg-skill"> 
			  <span class="tg-skillname"><?php echo intval( $key+1 );?> <?php esc_html_e('Stars','docdirect');?></span> 
			  <span class="tg-skillpercentage"><?php echo intval($final_rate/5);?>%</span>
			  <div class="tg-skillbox">
				<div class="tg-skillholder" data-percent="<?php echo intval($final_rate/5);?>%">
				  <div class="tg-skillbar"></div>
				</div>
			  </div>
			</div>
		<?php }?>
	  </div>
	</div>
	<?php }?>
	<ul class="tg-reviewlisting">
	<?php if( apply_filters('docdirect_count_reviews',$author_profile->ID) > 0 ){
	global $paged;
	if (empty($paged)) $paged = 1;
	$show_posts    = get_option('posts_per_page') ? get_option('posts_per_page') : '-1';        

	$meta_query_args = array('relation' => 'AND',);
	$meta_query_args[] = array(
							'key' 	   => 'user_to',
							'value' 	 => $author_profile->ID,
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);

	$args = array('posts_per_page' => "-1", 
		'post_type' => 'docdirectreviews', 
		'order' => 'DESC', 
		'orderby' => 'ID', 
		'post_status' => 'publish', 
		'ignore_sticky_posts' => 1,
		'suppress_filters'  => false
	);

	$args['meta_query'] = $meta_query_args;

	$query 		= new WP_Query( $args );
	$count_post = $query->post_count;        

	//Main Query	
	$args 		= array('posts_per_page' => $show_posts, 
		'post_type' => 'docdirectreviews', 
		'paged' => $paged, 
		'order' => 'DESC', 
		'orderby' => 'ID', 
		'post_status' => 'publish', 
		'ignore_sticky_posts' => 1
	);

	$args['meta_query'] = $meta_query_args;

	$query 		= new WP_Query($args);
	if( $query->have_posts() ){
		while($query->have_posts()) : $query->the_post();
			global $post;
			$user_rating = fw_get_db_post_option($post->ID, 'user_rating', true);
			$user_from = fw_get_db_post_option($post->ID, 'user_from', true);
			$review_date  = fw_get_db_post_option($post->ID, 'review_date', true);
			$user_data 	  = get_user_by( 'id', intval( $user_from ) );

			$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user_from),
							 array('width'=>150,'height'=>150) //size width,height
						);

			$user_name	= '';
			if( !empty( $user_data ) ) {
				$user_name	= $user_data->first_name.' '.$user_data->last_name;
			}

			if( empty( $user_name ) && !empty( $user_data ) ){
				$user_name	= $user_data->user_login;
			}

			$percentage	= $user_rating*20;

		?>
		<li>
			<div class="tg-review">
			  <figure class="tg-reviewimg"> 
				<a href="<?php echo get_author_posts_url($user_from); ?>"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('Reviewer','docdirect');?>"></a>
			  </figure>
			  <div class="tg-reviewcontet"> 
				<div class="tg-reviewhead">
				  <div class="tg-reviewheadleft">
					<h3><a href="<?php echo get_author_posts_url($user_from); ?>"><?php echo esc_attr( $user_name );?></a></h3>
					<span><?php echo human_time_diff( strtotime( $review_date ), current_time('timestamp') ) .'&nbsp;'.esc_html__('ago','docdirect'); ?></span> </div>
				  <div class="tg-reviewheadright tg-stars star-rating">
					<span style="width:<?php echo esc_attr( $percentage );?>%"></span>
				  </div>
				</div>
				<div class="tg-description">
				  <p><?php the_content();?></p>
				</div>
			  </div>
			</div>
		  </li>
		<?php 
			endwhile; wp_reset_postdata();
		}else{?>
			<li class="noreviews-found"> <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Reviews Found.','docdirect'));;?></li>
		<?php }
	} else{?>
		<li class="noreviews-found"> <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Reviews Found.','docdirect'));;?></li>
	<?php }?>

	</ul>
	<?php 
	if( isset( $current_user->ID ) 
		&& 
		$current_user->ID != $author_profile->ID 
	){?>
	<div class="tg-leaveyourreview">
	  <div class="tg-userheading">
		<h2><?php esc_html_e('Leave Your Review','docdirect');?></h2>
	  </div>
	  <?php if( apply_filters('docdirect_is_user_logged_in','check_user') === true ){?>
	  <div class="message_contact  theme-notification"></div>
	  <form class="tg-formleavereview form-review">
		<fieldset>
		  <div class="row">
			<div class="col-sm-6">
			  <div class="form-group">
				<input type="text" name="user_subject" class="form-control" placeholder="<?php esc_attr_e('Subject','docdirect');?>">
			  </div>
			</div>
			<div class="col-sm-6">
			  <div class="tg-stars"><div id="jRate"></div><span class="your-rate"><strong><?php esc_html_e('Excellent','docdirect');?></strong></span></div>
			  <script type="text/javascript">
			jQuery(function () {
				var that = this;
				var toolitup = jQuery("#jRate").jRate({
					rating: 3,
					min: 0,
					max: 5,
					precision: 1,
					startColor: "<?php echo esc_js( $rating_color );?>",
					endColor: "<?php echo esc_js( $rating_color );?>",
					backgroundColor: "#DFDFE0",
					onChange: function(rating) {
						jQuery('.user_rating').val(rating);
						if( rating == 1 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_1);
						} else if( rating == 2 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_2);
						} else if( rating == 3 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_3);
						} else if( rating == 4 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_4);
						} else if( rating == 5 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_5);
						}
					},
					onSet: function(rating) {
						jQuery('.user_rating').val(rating);
						if( rating == 1 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_1);
						} else if( rating == 2 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_2);
						} else if( rating == 3 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_3);
						} else if( rating == 4 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_4);
						} else if( rating == 5 ){
							jQuery('.your-rate strong').html(scripts_vars.rating_5);
						}
					}
				});
			});
		</script>
			</div>
			<div class="col-sm-12">
			  <div class="form-group">
				<textarea class="form-control" name="user_description" placeholder="<?php esc_attr_e('Review Description *','docdirect');?>"></textarea>
			  </div>
			</div>
			<div class="col-sm-12">
			  <button class="tg-btn make-review" type="submit"><?php esc_html_e('Submit Review','docdirect');?></button>
			  <input type="hidden" name="user_rating" class="user_rating" value="" />
			  <input type="hidden" name="user_to" class="user_to" value="<?php echo esc_attr( $author_profile->ID );?>" />
			</div>
		  </div>
		</fieldset>
	  </form>
	  <?php } else{?>
		<span><a href="javascript:;" class="tg-btn" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('Please Login To add Review','docdirect');?></a></span>
  <?php }?>
	</div>
	<?php }?>
  </div>
<?php }?>
</div>