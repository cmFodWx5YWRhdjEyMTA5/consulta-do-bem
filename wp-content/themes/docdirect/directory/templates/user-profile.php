<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

//Get profile hits
$year	= date('y');
$month	= date('m');
$profile_hits = get_user_meta($url_identity , 'profile_hits' , true);
$months_array	= docdirect_get_month_array(); //Get Month  Array
$hits_data	= '';
if( isset( $profile_hits[$year] ) && !empty( $profile_hits[$year] ) ){
	$current_hits	= $profile_hits[$year];
	foreach( $months_array as $key => $value ){
		$hits_data[$key]	= 0;
		if( isset( $current_hits[$key] ) ) {
			$hits_data[$key]	= $current_hits[$key];
		}
	}	
} else{
	foreach( $months_array as $key => $value ){
		$hits_data[$key]	= 0;
	}
}
?>

<div class="tg-graph tg-haslayout">
	<div class="tg-profilehits">
		<div class="tg-heading-border tg-small">
			<h3><?php esc_html_e('Profile Hits','docdirect');?></h3>
		</div>
		<canvas id="canvas" class="canvas"></canvas>
	</div>
</div>
<?php if( apply_filters('docdirect_do_check_user_type',$url_identity ) === true ){?>
<div class="tg-docrank tg-haslayout">
	<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
		<div class="tg-costumerreview">
			<div class="tg-heading-border tg-small">
				<h3><?php esc_html_e('Customer Reviews','docdirect');?></h3>
			</div>
			<div id="tg-reviewscrol" class="tg-reviewscrol">
				<ul class="tg-reviews">
					<?php if( apply_filters('docdirect_count_reviews',$url_identity) > 0 ){    
						//Main Query	
						$meta_query_args = array('relation' => 'AND',);
						$meta_query_args[] = array(
												'key' 	   => 'user_to',
												'value' 	 => $url_identity,
												'compare'   => '=',
												'type'	  => 'NUMERIC'
											);
								
						$args = array('posts_per_page' => -1, 
							'post_type' => 'docdirectreviews', 
							'paged' => $paged, 
							'order' => 'DESC', 
							'orderby' => 'ID', 
							'post_status' => 'publish', 
							'ignore_sticky_posts' => 1
						);
						
						$args['meta_query'] = $meta_query_args;
						
						$average_rating	= 0;
						$average_count	= 0;
						$query 		= new WP_Query($args);
						while($query->have_posts()) : $query->the_post();
							global $post;
							$user_rating = fw_get_db_post_option($post->ID, 'user_rating', true);
							$user_from = fw_get_db_post_option($post->ID, 'user_from', true);
							$review_date = fw_get_db_post_option($post->ID, 'review_date', true);
							$user_data 	  = get_user_by( 'id', intval( $user_from ) );
							
							$avatar = apply_filters(
											'docdirect_get_user_avatar_filter',
											 docdirect_get_user_avatar(array('width'=>140,'height'=>89), $user_from),
											 array('width'=>140,'height'=>89) //size width,height
										);
							
							if( !empty( $user_data ) ) {
							$user_name	= $user_data->first_name.' '.$user_data->last_name;
					
							if( empty( $user_name ) ){
								$user_name	= $user_data->user_login;
							}
							
							$percentage	= $user_rating*20;
							
							$average_rating	= $average_rating + $user_rating;
							$average_count++;
						?>
                        <li>
                            <div class="tg-review">
                                <figure class="tg-reviwer-img"> 
                                     <a href="<?php echo get_author_posts_url($user_from); ?>"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('Reviewer','docdirect');?>"></a>
                                </figure>
                                <div class="tg-reviewcontet">
                                    <div class="comment-head">
                                        <div class="pull-left">
                                            <h3><?php echo esc_attr( $user_name );?></h3>
                                        </div>
                                        <span><?php esc_html_e('Posted on ','docdirect');?><?php echo date('F d, Y',strtotime( $review_date ));?><?php esc_html_e(' at ','docdirect');?><?php echo date('H:i a',strtotime( $review_date ));?></span>
                                        <div class="tg-stars star-rating pull-left">
                                            <span style="width:<?php echo esc_attr( $percentage );?>%"></span>
                                        </div>
                                    </div>
                                    <div class="tg-description">
                                        <?php docdirect_prepare_excerpt(100,'false',''); ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                     	<?php
						}
					 		endwhile; wp_reset_postdata();
					} else{?>
                    	 <li class="noreviews-found"> <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Reviews Found.','docdirect'));;?></li>
                    <?php }?>
				</ul>
			</div>
		</div>
	</div>
    <?php 
	if( isset( $average_rating ) && $average_rating > 0 ){
		$percentage	= ( $average_rating/ $average_count)*20;
		?>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="tg-heading-border tg-small">
                    <h3><?php esc_html_e('Overall Rank','docdirect');?></h3>
                </div>
                <div class="tg-ratingbox">
                    <div class="tg-stars star-rating">
                        <span style="width:<?php echo esc_attr( $percentage );?>%"></span>
                    </div>
                    <strong><?php esc_html_e('very good','docdirect');?></strong> </div>
                <a class="tg-btn" href="<?php echo get_author_posts_url($url_identity); ?>"><?php esc_html_e('Read More','docdirect');?></a> 
            </div>
        </div>
    <?php }?>
</div>
<?php }?>
<script>
	var lineChartData  = {
		labels: <?php echo json_encode( array_values( $months_array ) );?>,
		datasets: [
			{
				label: "<?php esc_html_e('Profile Hits','docdirect');?>",
				fillColor : "rgba(220,220,220,0)",
				strokeColor : "rgba(203,202,201,1)",
				pointColor : "rgba(93,89,85,1)",
				pointStrokeColor : "rgba(238,238,238,1)",
				pointHighlightFill : "rgba(125,187,0,1)",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : <?php echo json_encode( array_values( $hits_data ) );?>
		},
		]
	};
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
	}
</script>

