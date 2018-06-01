<?php
/**
 * File Type: Appointments
 */
if( ! class_exists('TG_Appointments') ) {
	
	class TG_Appointments {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_appointment'));
			add_action( 'add_meta_boxes', array(&$this, 'tg_appointments_add_meta_box'), 10,1);
			add_filter('manage_docappointments_posts_columns', array(&$this, 'appointments_columns_add'));
			add_action('manage_docappointments_posts_custom_column', array(&$this, 'appointments_columns'),10, 2);				
		}
		
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_appointment(){
			$this->prepare_post_type();
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$labels = array(
				'name' 				 => esc_html__( 'Appointments', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Appointments', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Appointment', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Appointment', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Appointment', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Appointment', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Appointment', 'docdirect_core' ),
				'view'               => esc_html__( 'View Appointment', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Appointment', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Appointment', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Appointment found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Appointment found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Appointment', 'docdirect_core' ),
			);
			$args = array(
				'capabilities'       => array( 'create_posts' => false ), //Hide add New Button
				'labels'			  => $labels,
				'description'         => esc_html__( '', 'docdirect_core' ),
				'public'              => false,
				'supports'            => array( 'title'),
				'show_ui'             => true,
				'capability_type'     => 'post',
				'show_in_nav_menus'   => false, 
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'show_in_menu' => 'edit.php?post_type=directory_type',
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'menu_position' 	  => 10,
				'rewrite'			  => array('slug' => 'appointments', 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => false,
			); 
			register_post_type( 'docappointments' , $args );
			  
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function appointments_columns_add($columns) {
			unset($columns['date']);
			$columns['apt_from'] 			= esc_html__('Appointment From','docdirect_core');
			$columns['apt_to'] 		= esc_html__('Appointment To','docdirect_core');
			$columns['apt_contact'] 		= esc_html__('Contact Number','docdirect_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function appointments_columns($name) {
			global $post;
			
			$apt_from 		= '';
			$apt_to = '';
			$apt_contact	= '';
					
			if (function_exists('fw_get_db_settings_option')) {
				$apt_from 	   = fw_get_db_post_option($post->ID, 'bk_user_from', true);
				$apt_to   		= fw_get_db_post_option($post->ID, 'bk_user_to', true);
				$apt_contact 	= fw_get_db_post_option($post->ID, 'bk_userphone', true);
				$apt_from_user  = get_user_by( 'id', intval($apt_from) );
				$apt_to_user 	= get_user_by( 'id', intval($apt_to) );
			}
			
			switch ($name) {
				case 'apt_from':
					if( isset( $apt_from_user->data ) && !empty( $apt_from_user->data ) ){
						echo esc_attr( $apt_from_user->data->display_name );
					}
				break;
				case 'apt_to':
					if( isset( $apt_to_user->data ) && !empty( $apt_to_user->data ) ){
						echo esc_attr( $apt_to_user->data->display_name );
					}
				break;
				case 'apt_contact':
					echo ( $apt_contact );
				break;
				
			}
		}
		
		
		/**
		 * @Init Meta Boxes
		 * @return {post}
		 */
		public function tg_appointments_add_meta_box($post_type){
			if ($post_type == 'docappointments') {
				add_meta_box(
					'tg_appointments_info',
					esc_html__( 'Appointment Info', 'docdirect_core' ),
					array(&$this,'docdirect_meta_box_appointmentinfo'),
					'docappointments',
					'side',
					'high'
				);
				
			}
			
			if ( $post_type == 'docappointments' ) {
				add_meta_box(
					'tg_appointments_detail',
					esc_html__( 'Appointment Detail', 'docdirect_core' ),
					array(&$this,'docdirect_appointment_detail'),
					'docappointments',
					'normal',
					'high'
				);
			}
		}
		
		/**
		 * @Init Appointment detail
		 * @return {post}
		 */
		public function docdirect_appointment_detail(){
			global $post;
			
			if ( function_exists('fw_get_db_settings_option') 
				 &&
				 !empty( $post->ID ) 
				 && isset( $_GET['post'] ) 
				 && !empty( $_GET['post'] )
			) {
				
				$bk_payment_date = get_post_meta($post->ID, 'bk_payment_date', true);
				$bk_transaction_status = get_post_meta($post->ID, 'bk_transaction_status', true);
				$bk_paid_amount = get_post_meta($post->ID, 'bk_paid_amount', true);
				$bk_user_from = get_post_meta($post->ID, 'bk_user_from', true);
				$payment_status = get_post_meta($post->ID, 'payment_status', true);
				$bk_status = get_post_meta($post->ID, 'bk_status', true);
				$bk_timestamp = get_post_meta($post->ID, 'bk_timestamp', true);
				$bk_user_to = get_post_meta($post->ID, 'bk_user_to', true);
				$bk_payment = get_post_meta($post->ID, 'bk_payment', true);
				$bk_booking_note = get_post_meta($post->ID, 'bk_booking_note', true);
				$bk_useremail = get_post_meta($post->ID, 'bk_useremail', true);
				$bk_userphone = get_post_meta($post->ID, 'bk_userphone', true);
				$bk_username = get_post_meta($post->ID, 'bk_username', true);
				$bk_subject = get_post_meta($post->ID, 'bk_subject', true);
				$bk_slottime = get_post_meta($post->ID, 'bk_slottime', true);
				$bk_booking_date = get_post_meta($post->ID, 'bk_booking_date', true);
				$bk_currency = get_post_meta($post->ID, 'bk_currency', true);
				$bk_service = get_post_meta($post->ID, 'bk_service', true);
				$bk_category = get_post_meta($post->ID, 'bk_category', true);
				$bk_code = get_post_meta($post->ID, 'bk_code', true);
				
				
				$services_cats = get_user_meta($bk_user_to , 'services_cats' , true);
				$booking_services = get_user_meta($bk_user_to , 'booking_services' , true);
				

				$purchase_on	 = date('d M, y',strtotime( $bk_payment_date ));
				$bk_user_from	= get_user_by( 'id', intval( $bk_user_from ) );
				$bk_user_to	  = get_user_by( 'id', intval( $bk_user_to ) );
				$payment_amount  = $bk_currency.$bk_paid_amount;
				
				$bk_booking_date	 = date('d M, y',strtotime( $bk_booking_date ));
				
				
				
				$date_format = get_option('date_format');
				$time_format = get_option('time_format');
				$time = explode('-',$bk_slottime);
				
			} else{
				$bk_payment_date = esc_html__('NILL','docdirect_core');
				$bk_transaction_status = esc_html__('NILL','docdirect_core');
				$bk_paid_amount = esc_html__('NILL','docdirect_core');
				$bk_user_from = esc_html__('NILL','docdirect_core');
				$payment_status = esc_html__('NILL','docdirect_core');
				$bk_timestamp = esc_html__('NILL','docdirect_core');
				$bk_user_to = esc_html__('NILL','docdirect_core');
				$bk_booking_note = esc_html__('NILL','docdirect_core');
				$bk_useremail = esc_html__('NILL','docdirect_core');
				$bk_userphone = esc_html__('NILL','docdirect_core');
				$bk_username = esc_html__('NILL','docdirect_core');
				$bk_subject = esc_html__('NILL','docdirect_core');
				$bk_slottime = esc_html__('NILL','docdirect_core');
				$bk_booking_date = esc_html__('NILL','docdirect_core');
				$bk_currency	= esc_html__('NILL','docdirect_core');
				$bk_service	= '';
				$bk_category	= '';
				$bk_code	= '';
				$payment_amount  = esc_html__('NILL','docdirect_core');
				
			}
			?>
			<ul class="invoice-info">
				<li>
					<strong><?php esc_html_e('Tracking id','docdirect_core');?></strong>
					<span><?php echo esc_attr( $bk_code );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Appointment Date','docdirect_core');?></strong>
					<span><?php echo esc_attr( $bk_booking_date );?></span>
				</li>
			 	<?php if( !empty( $services_cats[$bk_category] ) ){?>
                    <li>
                        <strong><?php esc_html_e('Appointment Category','docdirect_core');?></strong>
                        <span><?php echo esc_attr( $services_cats[$bk_category] );?></span>
                    </li>
                <?php }?>
                <?php if( !empty( $booking_services[$bk_service] ) ){?>
                    <li>
                        <strong><?php esc_html_e('Appointment Service','docdirect_core');?></strong>
                        <span><?php echo esc_attr( $booking_services[$bk_service]['title'] );?></span>
                    </li>
                <?php }?>
                <li>
                    <strong><?php esc_html_e('Appointment Fee','docdirect_core');?></strong>
                    <span><?php echo esc_attr( $payment_amount );?></span>
                </li>
                <li>
					<strong><?php esc_html_e('Contact Number','docdirect_core');?></strong>
					<span><?php echo esc_attr( $bk_userphone );?></span>
				</li>
				<?php if( !empty( $bk_user_from->data ) ){?>
					<li>
						<strong><?php esc_html_e('User From','docdirect_core');?></strong>
						<span><a href="<?php echo get_edit_user_link($bk_user_from->data->ID);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $bk_user_from->data->display_name );?></a></span>
					</li>
				<?php }?>
                <?php if( !empty( $bk_user_to->data ) ){?>
					<li>
						<strong><?php esc_html_e('User To','docdirect_core');?></strong>
						<span><a href="<?php echo get_edit_user_link($bk_user_to->data->ID);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $bk_user_to->data->display_name );?></a></span>
					</li>
				<?php }?>
                <?php if( !empty( $bk_status ) ){?>
				<li>
					<strong><?php esc_html_e('Booking Status','docdirect_core');?></strong>
					<span><?php echo esc_attr( ucwords( $bk_status ) );?></span>
				</li>
                <?php }?>
                <?php if( !empty( $bk_transaction_status ) ){?>
				<li>
					<strong><?php esc_html_e('Payment Status','docdirect_core');?></strong>
					<span><?php echo esc_attr( docdirect_prepare_order_status( 'value',$bk_transaction_status ) );?></span>
				</li>
                <?php }?>
                <?php if( !empty( $bk_payment ) ){?>
				<li>
					<strong><?php esc_html_e('Payment Method','docdirect_core');?></strong>
					<span><?php echo esc_attr( docdirect_prepare_payment_type( 'value',$bk_payment ) );?></span>
				</li>
                <?php }?>
				<?php if( !empty( $time[0] ) && !empty( $time[1] ) ){?>
                <li>
					<strong><?php esc_html_e('Metting Time','docdirect_core');?></strong>
					<span><?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>&nbsp;-&nbsp;<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></span>
				</li>
                <?php }?>
                <li>
					<strong><?php esc_html_e('Note','docdirect_core');?></strong>
					<span><?php echo esc_attr( $bk_booking_note );?></span>
				</li>
			</ul>
			<?php
		}
		
		/**
		 * @Init Appointment info
		 * @return {post}
		 */
		public function docdirect_meta_box_appointmentinfo(){
			global $post;
			
			if (function_exists('fw_get_db_settings_option')) {
				$bk_code = fw_get_db_post_option($post->ID, 'bk_code', true);
				$bk_code	= !empty( $bk_code ) ? $bk_code : esc_html__('NILL','docdirect_core');
			} else{
				$bk_code = esc_html__('NILL','docdirect_core');
			}
			
			?>
			<ul class="invoice-info side-panel-info">
				<li>
					<strong><?php esc_html_e('Booking Code','docdirect_core');?></strong>
					<span><?php echo esc_attr( $bk_code );?></span>
				</li>
			</ul>
			<?php
		}
		
		
	}
	
  	new TG_Appointments();	
}