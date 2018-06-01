<?php
/**
 * File Type: Order
 */
if( ! class_exists('TG_Orders') ) {
	
	class TG_Orders {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_order'));
			add_action( 'add_meta_boxes', array(&$this, 'tg_orders_add_meta_box'), 10,1);
			add_filter('manage_docdirectorders_posts_columns', array(&$this, 'orders_columns_add'));
			add_action('manage_docdirectorders_posts_custom_column', array(&$this, 'orders_columns'),10, 2);				
		}
		
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_order(){
			if( apply_filters('docdirect_get_theme_settings', 'payments') === 'custom' ){
				$this->prepare_post_type();
			}
		}
		
		/**
		 * @Prepare Post Type
		 * @return {}
		 */
		public function prepare_post_type(){
			$labels = array(
				'name' 				 => esc_html__( 'Orders', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Orders', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Order', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Order', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Order', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Order', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Order', 'docdirect_core' ),
				'view'               => esc_html__( 'View Order', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Order', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Order', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Order found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Order found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Order', 'docdirect_core' ),
			);
			$args = array(
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
				'rewrite'			  => array('slug' => 'orders', 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => false,
			); 
			register_post_type( 'docdirectorders' , $args );
			  
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function orders_columns_add($columns) {
			unset($columns['date']);
			$columns['package'] 			= esc_html__('Package','docdirect_core');
			$columns['payment_option'] 		= esc_html__('Payment Method','docdirect_core');
			$columns['user'] 		= esc_html__('User','docdirect_core');
			$columns['price'] 		= esc_html__('Price','docdirect_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function orders_columns($name) {
			global $post;
			
			$package 		= '';
			$payment_method = '';
			$user_identity	= '';
			$price   		= '';
			$currency   	= '';
					
			if (function_exists('fw_get_db_settings_option')) {
				$currency_sign = fw_get_db_settings_option('currency_select');
				$package 		  = fw_get_db_post_option($post->ID, 'package', true);
				$payment_method   = fw_get_db_post_option($post->ID, 'payment_method', true);
				$user_identity 	= fw_get_db_post_option($post->ID, 'payment_user', true);
				$price   		    = fw_get_db_post_option($post->ID, 'price', true);
				$currency 		 = fw_get_db_post_option($post->ID,'mc_currency', true);
				$package		  = get_the_title($package);
				$user 			 = get_user_by( 'id', intval($user_identity) );
			}
			
			$currency = !empty( $mc_currency ) && $mc_currency != 1 ? $mc_currency : $currency_sign;
			
			switch ($name) {
				case 'package':
					echo ( $package );
				break;
				case 'payment_option':
					 echo esc_attr( docdirect_prepare_payment_type( 'value',$payment_method ) );
				break;
				case 'user':
					if( isset( $user->data ) && !empty( $user->data ) ){
						echo esc_attr( $user->data->display_name );
					}
				break;
				case 'price':
					echo ( $currency.$price );
				break;		
				
			}
		}
		
		
		/**
		 * @Init Meta Boxes
		 * @return {post}
		 */
		public function tg_orders_add_meta_box($post_type){
			if ($post_type == 'docdirectorders') {
				add_meta_box(
					'tg_orders_info',
					esc_html__( 'ORDER INFO', 'docdirect_core' ),
					array(&$this,'tg_orders_meta_box_orderinfo'),
					'docdirectorders',
					'side',
					'high'
				);
				
			}
		}
		
		/**
		 * @Init Order info
		 * @return {post}
		 */
		public function tg_orders_meta_box_orderinfo(){
			global $post;
			
			if (function_exists('fw_get_db_settings_option')) {
				$currency_sign = fw_get_db_settings_option('currency_select');
				$order_id = get_the_title($post->ID);
				$transaction_id = fw_get_db_post_option($post->ID, 'transaction_id', true);
				$mc_currency = fw_get_db_post_option($post->ID, 'mc_currency', true);
				$order_status = fw_get_db_post_option($post->ID, 'order_status', true);
				$package = fw_get_db_post_option($post->ID, 'package', true);
				$payment_method = fw_get_db_post_option($post->ID, 'payment_method', true);
				$price = fw_get_db_post_option($post->ID, 'price', true);
				$payment_date = fw_get_db_post_option($post->ID, 'payment_date', true);
				$expiry_date = fw_get_db_post_option($post->ID, 'expiry_date', true);
				$payment_user = fw_get_db_post_option($post->ID, 'payment_user', true);
				
				$currency_sign = !empty( $mc_currency ) && $mc_currency != 1 ? $mc_currency : $currency_sign;
				
				$transaction_id = !empty( $transaction_id ) && $transaction_id != 1 ? $transaction_id : esc_html__('NILL','docdirect_core');
				$order_status = !empty( $order_status ) ? docdirect_prepare_order_status('value',$order_status) : esc_html__('NILL','docdirect_core');
				$package = !empty( $package )  && $package != 1 ?  get_the_title( $package ) : esc_html__('NILL','docdirect_core');
				$payment_method = !empty( $payment_method ) ? docdirect_prepare_payment_type('value',$payment_method)  : esc_html__('NILL','docdirect_core');
				
				$price = !empty( $price ) && $price != 1 ? $currency_sign.$price : esc_html__('NILL','docdirect_core');
				
				$payment_date = !empty( $payment_date ) && $payment_date != 1 ? date('d M, Y',strtotime($payment_date)) : esc_html__('NILL','docdirect_core');
				$expiry_date = !empty( $expiry_date )  && $expiry_date != 1 ? date('d M, Y',strtotime($expiry_date)) : esc_html__('NILL','docdirect_core');
				
				if( !empty( $payment_user ) ) {
					$user 	= get_user_by( 'id', intval( $payment_user ) );
				}
				
			} else{
				$order_id = esc_html__('NILL','docdirect_core');
				$currency_sign = esc_html__('NILL','docdirect_core');
				$transaction_id = esc_html__('NILL','docdirect_core');
				$order_status = esc_html__('NILL','docdirect_core');
				$package = esc_html__('NILL','docdirect_core');
				$payment_method = esc_html__('NILL','docdirect_core');
				$price = esc_html__('NILL','docdirect_core');
				$payment_date = esc_html__('NILL','docdirect_core');
				$expiry_date = esc_html__('NILL','docdirect_core');
				$user = esc_html__('NILL','docdirect_core');
			}
			
			?>
				<ul class="order-info">
					<li>
						<strong><?php esc_html_e('Order ID','docdirect_core');?></strong>
						<span><?php echo esc_attr( $order_id );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Transaction ID','docdirect_core');?></strong>
						<span><?php echo esc_attr( $transaction_id );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Payment Status','docdirect_core');?></strong>
						<span><?php echo esc_attr( $order_status );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Payment Option','docdirect_core');?></strong>
						<span><?php echo esc_attr( $payment_method );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Package','docdirect_core');?></strong>
						<span><?php echo esc_attr( $package );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Package Price','docdirect_core');?></strong>
						<span><?php echo esc_attr( $price );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Purchase Date','docdirect_core');?></strong>
						<span><?php echo esc_attr( $payment_date );?></span>
					</li>
					<li>
						<strong><?php esc_html_e('Expiry Date','docdirect_core');?></strong>
						<span><?php echo esc_attr( $expiry_date );?></span>
					</li>
					<?php if( isset( $user->data ) && !empty( $user->data ) ){?>
						<li>
							<strong><?php esc_html_e('User','docdirect_core');?></strong>
							<span><a href="<?php echo get_edit_user_link($payment_user);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $user->data->display_name );?></a></span>
						</li>
					<?php }?>
				</ul>
			<?php
		}
		
		
	}
	
  	new TG_Orders();	
}