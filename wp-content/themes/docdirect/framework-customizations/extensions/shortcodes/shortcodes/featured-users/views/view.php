<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */

$today = time();
$users_type	= $atts['user_type'];
$show_users	= !empty( $atts['show_users'] ) ? $atts['show_users'] : 10;
$order		 = !empty( $atts['order'] ) ? $atts['order'] : 'DESC';
$uniq_flag = fw_unique_increment();
$query_args	= array(
					'role'  => 'professional',
					'order' => $order,
					'number' => $show_users 
				 );

if( isset( $users_type ) && !empty( $users_type ) && $users_type !='all' ) {
	$meta_query_args[] = array(
						'key'     => 'directory_type',
						'value'   => $users_type,
						'compare' => '='
					);
}


//Verify user
$meta_query_args[] = array(
						'key'     => 'verify_user',
						'value'   => 'on',
						'compare' => '='
					);
$meta_query_args[] = array(
						'key'     => 'user_featured',
						'value'   => $today,
						'type' => 'numeric',
						'compare' => '>'
					);

if( !empty( $meta_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$meta_query_args	= array_merge( $query_relation,$meta_query_args );
	$query_args['meta_query'] = $meta_query_args;
}


$query_args['meta_key']	   = 'user_featured';
$query_args['orderby']	   = 'meta_value';	
$user_query  = new WP_User_Query($query_args);	

$flag	= rand(1,9999);	
?>
<div class="sc-featured-users">
	<?php if ( !empty($atts['heading']) || !empty($atts['description'])) { ?>
        <div class="col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="tg-section-head tg-haslayout">
                <?php if (!empty($atts['heading'])) { ?>
                    <div class="tg-section-heading tg-haslayout">
                        <h2><?php echo esc_attr($atts['heading']); ?></h2>
                    </div>
                <?php } ?>
                <?php if (!empty($atts['description'])) { ?>
                    <div class="tg-description tg-haslayout">
                        <p><?php echo esc_attr($atts['description']); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>	
    
    <?php
		if ( ! empty( $user_query->results ) ) {
		?>
       <div id="tg-featuredlist-<?php echo esc_attr( $uniq_flag );?>" class="tg-featuredlist-slider tg-featuredlist-slider-v2 owl-carousel tg-haslayout">
		<?php
            foreach ( $user_query->results as $user ) {
				$privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settings
				$directory_type = get_user_meta( $user->ID, 'directory_type', true);
				$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
				$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>275,'height'=>191), $user->ID),
							 array('width'=>275,'height'=>191) //size width,height
						);
					
				$first_name   		   = get_user_meta( $user->ID, 'first_name', true);
				$last_name   		   = get_user_meta( $user->ID, 'last_name', true);
				$display_name   	   = get_user_meta( $user->ID, 'display_name', true);
				
				if( !empty( $first_name ) || !empty( $last_name ) ){
					$username	= $first_name.' '.$last_name;
				} else{
					$username	= $display_name;
				}
				
				$featured_string	= $user->user_featured;
				$current_date 	  = date('Y-m-d H:i:s');
				$current_string	= strtotime( $current_date );
				$data	= docdirect_get_everage_rating ( $user->ID );
				$username	= docdirect_get_username( $user->ID );
								
				?>
                <div class="item">
                    <figure>
                        <a href="<?php echo get_author_posts_url($user->ID); ?>"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('User','docdirect');?>"></a>
                        <?php docdirect_get_wishlist_button($user->ID,true);?>
						<?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
							<?php docdirect_get_featured_tag(true);?>
                        <?php }?>
                        <?php docdirect_get_verified_tag(true,$user->ID);?>
                        <?php do_action('docdirect_display_provider_category',$user->ID);?>
                    </figure>
                    <div class="tg-contentbox">
                        <h3><a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo esc_attr( $username );?></a></h3>
                        <?php if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
                        <div class="feature-rating">
                            <span class="tg-stars star-rating">
                                <span style="width:<?php echo esc_attr( $data['percentage'] );?>%"></span>
                            </span>
                            <?php if( !empty( $data['average_rating'] ) ){?><em><?php echo number_format((float)$data['average_rating'], 1, '.', '');?><sub>/5</sub></em><?php }?>
                        </div>
                        <?php }?>
                        <?php if( !empty( $user->user_address ) ) {?>
                            <address><?php echo esc_attr( $user->user_address );?></address>
                        <?php }?>
                        <?php if( !empty( $user->phone_number ) 
								  &&
								  !empty( $privacy['phone'] )
								  && 
								  $privacy['phone'] == 'on'
						) {?>
                           <div class="tg-phone"><i class="fa fa-phone"></i> <em><?php echo esc_attr( $user->phone_number );?></em></div>
                        <?php }?>
                    </div>
                </div>
                <?php
			}
			?>
      </div>
      <script>
	  	jQuery(document).ready(function(e) {
            jQuery("#tg-featuredlist-<?php echo esc_js( $uniq_flag );?>").owlCarousel({
				items:3,
				rtl: <?php docdirect_owl_rtl_check();?>,
				nav: false,
				dots: true,
				autoplay: true,
				rewind:true,
				navText : ['<i class="doc-btnprev icon-arrows-1"></i>','<i class="doc-btnnext icon-arrows"></i>'],
				responsive:{
					0:{items:1},
					481:{items:2},
					991:{items:2},
					1200:{items:3},
					1280:{items:4},
				}
			});
        });
	  </script>
	  <?php
      } else{
		  DoctorDirectory_NotificationsHelper::informations(esc_html__('No users Found.','docdirect'));
      }
	  ?>
</div>				
