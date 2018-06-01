<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post,$paged;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

if (function_exists('fw_get_db_settings_option')) {
	$currency_select = fw_get_db_settings_option('currency_select');
} else{
	$currency_select = 'USD';
}


if (empty($paged)) $paged = 1;
$limit = get_option('posts_per_page');


$meta_query_args[] = array(
							'key'     => 'bk_user_to',
							'value'   => $current_user->ID,
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);

if( !empty( $_GET['by_date'] ) ){
	$meta_query_args[] = array(
							'key'     => 'bk_timestamp',
							'value'   => strtotime($_GET['by_date']),
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);
}
										

$show_posts    = get_option('posts_per_page') ? get_option('posts_per_page') : '-1';           
$args 		= array( 'posts_per_page' => -1, 
					 'post_type' => 'docappointments', 
					 'post_status' => 'publish', 
					 'ignore_sticky_posts' => 1,
					);
						
if( !empty( $meta_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$meta_query_args	= array_merge( $query_relation,$meta_query_args );
	$args['meta_query'] = $meta_query_args;
}

$query 		= new WP_Query( $args );
$count_post = $query->post_count;   
$args 		= array( 'posts_per_page' => $show_posts, 
					 'post_type' => 'docappointments', 
					 'post_status' => 'publish', 
					 'ignore_sticky_posts' => 1,
					 'order'	=> 'DESC',
					 'orderby'	=> 'ID',
					 'paged' => $paged, 
					);


if( !empty( $meta_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$meta_query_args	= array_merge( $query_relation,$meta_query_args );
	$args['meta_query'] = $meta_query_args;
}

$dir_profile_page = '';
if (function_exists('fw_get_db_settings_option')) {
	$dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
}

$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';	
?>

<div class="doc-booking-listings dr-bookings">
  <div class="tg-dashboard tg-docappointmentlisting tg-haslayout">
    <div class="tg-heading-border tg-small">
      <h3><?php esc_html_e('Appointments','docdirect');?></h3>
    </div>
    <form class="tg-formappointmentsearch" action="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'bookings', $user_identity); ?>" method="get">
      <fieldset>
        <h4><?php esc_html_e('Search Here','docdirect');?>:</h4>
        <div class="form-group">
          <input type="hidden" class="" value="bookings" name="ref">
          <input type="hidden" class="" value="<?php echo intval( $user_identity ); ?>" name="identity">
          <input type="text" class="form-control booking-search-date" value="<?php echo isset( $_GET['by_date'] ) && !empty( $_GET['by_date'] ) ? $_GET['by_date'] : '';?>" name="by_date" placeholder="<?php esc_html_e('Search by date','docdirect');?>">
          <button type="submit"><i class="fa fa-search"></i></button>
        </div>
      </fieldset>
    </form>
    <div class="tg-appointmenttable">
      <table class="table">
        <thead class="thead-inverse">
          <tr>
            <th><?php esc_html_e('id.','docdirect');?></th>
            <th><?php esc_html_e('Subject','docdirect');?></th>
            <th><?php esc_html_e('Phone','docdirect');?></th>
            <th><?php esc_html_e('More Detail','docdirect');?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php 
			$query 		= new WP_Query($args);
			$services_cats = get_user_meta($user_identity , 'services_cats' , true);
			$booking_services = get_user_meta($user_identity , 'booking_services' , true);
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');
			
			$counter	= 0;
			if( $query->have_posts() ):
			 while($query->have_posts()) : $query->the_post();
			 	global $post;
				
				$counter++;
			    $bk_code          = get_post_meta($post->ID, 'bk_code',true);
				$bk_category      = get_post_meta($post->ID, 'bk_category',true);
				$bk_service       = get_post_meta($post->ID, 'bk_service',true);
				$bk_booking_date  = get_post_meta($post->ID, 'bk_booking_date',true);
				$bk_slottime 	  = get_post_meta($post->ID, 'bk_slottime',true);
				$bk_subject       = get_post_meta($post->ID, 'bk_subject',true);
				$bk_username      = get_post_meta($post->ID, 'bk_username',true);
				$bk_userphone 	  = get_post_meta($post->ID, 'bk_userphone',true);
				$bk_useremail     = get_post_meta($post->ID, 'bk_useremail',true);
				$bk_booking_note  = get_post_meta($post->ID, 'bk_booking_note',true);
				$bk_payment       = get_post_meta($post->ID, 'bk_payment',true);
				$bk_user_to       = get_post_meta($post->ID, 'bk_user_to',true);
				$bk_timestamp     = get_post_meta($post->ID, 'bk_timestamp',true);
				$bk_status        = get_post_meta($post->ID, 'bk_status',true);
				$bk_user_from     = get_post_meta($post->ID, 'bk_user_from',true);
				$bk_currency 	  = get_post_meta($post->ID, 'bk_currency', true);
				$bk_paid_amount   = get_post_meta($post->ID, 'bk_paid_amount', true);
				$bk_transaction_status = get_post_meta($post->ID, 'bk_transaction_status', true);
				
				$payment_amount  = $bk_currency.$bk_paid_amount;
				
				$time = explode('-',$bk_slottime);
				
				$trClass	= 'booking-odd';
				if( $counter % 2 == 0 ){
					$trClass	= 'booking-even';
				}  
		  ?>
          <tr class="<?php echo esc_attr( $trClass );?> booking-<?php echo intval( $post->ID );?>">
            <td data-name="id"><?php echo esc_attr( $bk_code );?></td>
            <td data-name="subject"><?php echo esc_attr( $bk_subject );?></td>
            <td data-name="phone"><?php echo esc_attr( $bk_userphone );?></td>
            <td data-name="notes"><a class="get-detail" href="javascript:;"><i class="fa fa-sticky-note-o"></i></a></td>
            <td>
            	<?php if( isset( $bk_status ) && $bk_status == 'approved' ){?>
                	<a class="tg-btncheck appointment-actioned fa fa-check" href="javascript:;"><?php esc_html_e('Approved','docdirect');?></a> 
                <?php }else if( isset( $bk_status ) && $bk_status == 'cancelled' ){?>
                	<a class="tg-btncheck appointment-actioned fa fa-times" href="javascript:;"><?php esc_html_e('Cancelled','docdirect');?></a> 
                <?php }else {?>
                     <a class="tg-btncheck get-process" data-type="approve" data-id="<?php echo intval( $post->ID );?>" href="javascript:;"><?php esc_html_e('Approve','docdirect');?></a> 
                     <a class="tg-btnclose get-process" data-type="cancel" data-id="<?php echo intval( $post->ID );?>" href="javascript:;"><?php esc_html_e('Cancel','docdirect');?></a>
                <?php }?>
               
            </td>
          </tr>
          <tr class="tg-appointmentdetail bk-elm-hide">
            <td colspan="6">
                <div class="appointment-data-wrap">
                    <ul class="tg-leftcol">
                      <li> 
                            <strong><?php esc_html_e('tracking id','docdirect');?>:</strong> 
                            <span><?php echo esc_attr( $bk_code );?></span> 
                      </li>
                      <li>
                            <strong><?php esc_html_e('Category','docdirect');?>:</strong>
                            <?php if( !empty( $services_cats[$bk_category] ) ){?>
                                <span><?php echo esc_attr( $services_cats[$bk_category] );?></span>
                            <?php }?>
                      </li>
                      <li> 
                            <strong><?php esc_html_e('Service','docdirect');?>:</strong>
                            <?php if( !empty( $booking_services[$bk_service] ) ){?>
                                <span><?php echo esc_attr( $booking_services[$bk_service]['title'] );?></span>
                            <?php }?>
                      </li>
                      <li> <strong><?php esc_html_e('Phone','docdirect');?>:</strong> <span><?php echo esc_attr( $bk_userphone );?></span> </li>
                      <li> <strong><?php esc_html_e('User Name','docdirect');?>:</strong> <span><?php echo esc_attr( $bk_username );?></span> </li>
                      <li> <strong><?php esc_html_e('Email','docdirect');?>:</strong> <span><?php echo esc_attr( $bk_useremail );?></span> </li>
                      <li> 
                            <strong><?php esc_html_e('Appointment date','docdirect');?>:</strong> 
                            <?php if( !empty( $bk_booking_date ) ){?>
                                <span><?php echo date($date_format,strtotime($bk_booking_date));?></span> 
                            <?php }?>
                      </li>
                      <li> 
                            <strong><?php esc_html_e('Meeting Time','docdirect');?>:</strong> 
                            <span><?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>&nbsp;-&nbsp;<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></span> 
                      </li>
                      <li> 
                            <strong><?php esc_html_e('Status','docdirect');?>:</strong>
                            <span><?php echo esc_attr( docdirect_prepare_order_status( 'value',$bk_status ) );?></span> 
                      </li>
                      <li> 
                            <strong><?php esc_html_e('Payment Type','docdirect');?>:</strong>
                            <span><?php echo esc_attr( docdirect_prepare_payment_type( 'value',$bk_payment ) );?></span> 
                      </li>
                      <?php if( !empty( $payment_amount ) ){?>
                          <li> 
                                <strong><?php esc_html_e('Appointment Fee','docdirect');?>:</strong>
                                <span><?php echo esc_attr( $payment_amount );?></span>
                          </li>
                      <?php }?>
                      <?php if( !empty( $bk_transaction_status ) ){?>
                          <li> 
                             <strong><?php esc_html_e('Payment Status','docdirect');?>:</strong>
                             <span><?php echo esc_attr( docdirect_prepare_order_status( 'value',$bk_transaction_status ) );?></span>
                          </li>
                      <?php }?>
                    </ul>
                    <div class="tg-rightcol"> <strong><?php esc_html_e('notes:','docdirect');?></strong>
                      <?php if( !empty( $bk_booking_note ) ){?>
                          <div class="tg-description">
                            <p><?php echo esc_attr( $bk_booking_note );?></p>
                          </div>
                      <?php }?>
                    </div>
                  </div>
              </td>
          </tr>
          <?php 
		  endwhile; wp_reset_postdata(); 
		  else:
		  ?>
		  <tr>
			<td colspan="6">
				<?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No appointments found.','docdirect'));?>
			</td>
		  </tr>
		<?php endif;?>
        </tbody>
      </table>
      <div class="col-md-xs-12">
		<?php 
            if( $count_post > $limit ) {
                docdirect_prepare_pagination($count_post,$limit);
            }
        ?>
      </div>
    </div>
  </div>
</div>
<script type="text/template" id="tmpl-status-approved">
	<a class="tg-btncheck appointment-actioned fa fa-check" href="javascript:;"><?php esc_html_e('Approved','docdirect');?></a> 
</script>