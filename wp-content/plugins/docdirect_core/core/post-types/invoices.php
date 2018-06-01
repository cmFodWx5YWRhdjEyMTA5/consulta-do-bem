<?php
/**
 * File Type: Invoice
 */
if( ! class_exists('TG_Invoices') ) {
	
	class TG_Invoices {
	
		public function __construct() {
			global $pagenow;
			add_action('init', array(&$this, 'init_invoice'));
			add_action( 'add_meta_boxes', array(&$this, 'tg_invoices_add_meta_box'), 10,1);
			add_filter('manage_docdirectinvoices_posts_columns', array(&$this, 'invoices_columns_add'));
			add_action('manage_docdirectinvoices_posts_custom_column', array(&$this, 'invoices_columns'),10, 2);			
		}
		
		
		/**
		 * @Init Post Type
		 * @return {post}
		 */
		public function init_invoice(){
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
				'name' 				 => esc_html__( 'Invoices', 'docdirect_core' ),
				'all_items'			 => esc_html__( 'Invoices', 'docdirect_core' ),
				'singular_name'      => esc_html__( 'Invoice', 'docdirect_core' ),
				'add_new'            => esc_html__( 'Add Invoice', 'docdirect_core' ),
				'add_new_item'       => esc_html__( 'Add New Invoice', 'docdirect_core' ),
				'edit'               => esc_html__( 'Edit', 'docdirect_core' ),
				'edit_item'          => esc_html__( 'Edit Invoice', 'docdirect_core' ),
				'new_item'           => esc_html__( 'New Invoice', 'docdirect_core' ),
				'view'               => esc_html__( 'View Invoice', 'docdirect_core' ),
				'view_item'          => esc_html__( 'View Invoice', 'docdirect_core' ),
				'search_items'       => esc_html__( 'Search Invoice', 'docdirect_core' ),
				'not_found'          => esc_html__( 'No Invoice found', 'docdirect_core' ),
				'not_found_in_trash' => esc_html__( 'No Invoice found in trash', 'docdirect_core' ),
				'parent'             => esc_html__( 'Parent Invoice', 'docdirect_core' ),
			);
			$args = array(
				'capabilities'       => array( 'create_posts' => false ), //Hide add New Button
				'map_meta_cap'       => true,  // Enable View mode 
				
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
				'rewrite'			  => array('slug' => 'invoices', 'with_front' => true),
				'query_var'           => false,
				'has_archive'         => false,
				
			); 
			register_post_type( 'docdirectinvoices' , $args );
			  
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function invoices_columns_add($columns) {
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
		public function invoices_columns($name) {
			global $post;
			
			$package 		= '';
			$payment_option = '';
			$user 			= '';
			$price   		= '';
				
			if (function_exists('fw_get_db_settings_option')) {
				$package 		  = fw_get_db_post_option($post->ID, 'package_id', true);
				$payment_method   = fw_get_db_post_option($post->ID, 'payment_method', true);
				$user_identity 			  = fw_get_db_post_option($post->ID, 'user_identity', true);
				$price   		    = fw_get_db_post_option($post->ID, 'payment_gross', true);
				$currency 		  = fw_get_db_post_option($post->ID,'mc_currency', true);
				$package		  = get_the_title($package);
			}
			
			switch ($name) {
				case 'package':
					echo ( $package );
				break;
				case 'payment_option':
					 echo esc_attr( docdirect_prepare_payment_type( 'value',$payment_method ) );
				break;
				case 'user':
					echo ( the_author_meta('nickname',$user_identity) );
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
		public function tg_invoices_add_meta_box($post_type){
			if ( $post_type == 'docdirectinvoices' ) {
				add_meta_box(
					'tg_invoices_info',
					esc_html__( 'INVOICE INFO', 'docdirect_core' ),
					array(&$this,'tg_invoices_meta_box_invoiceinfo'),
					'docdirectinvoices',
					'side',
					'high'
				);
			}
			
			if ( $post_type == 'docdirectinvoices' ) {
				add_meta_box(
					'tg_invoices_detail',
					esc_html__( 'INVOICE DETAIL', 'docdirect_core' ),
					array(&$this,'tg_invoices_meta_box_invoicedetail'),
					'docdirectinvoices',
					'normal',
					'high'
				);
			}
		}
		
		/**
		 * @Init Invoice info
		 * @return {post}
		 */
		public function tg_invoices_meta_box_invoicedetail(){
			global $post;
			
			if (function_exists('fw_get_db_settings_option') && !empty( $post->ID ) && isset( $_GET['post'] ) && !empty( $_GET['post'] ) ) {
				$user_identity = fw_get_db_post_option($post->ID, 'user_identity', true);
				$package_id = fw_get_db_post_option($post->ID, 'package_id', true);
				$txn_id = fw_get_db_post_option($post->ID, 'txn_id', true);
				$payment_gross = fw_get_db_post_option($post->ID, 'payment_gross', true);
				$item_name = fw_get_db_post_option($post->ID, 'item_name', true);
				$payer_email = fw_get_db_post_option($post->ID, 'payer_email', true);
				$mc_currency = fw_get_db_post_option($post->ID, 'mc_currency', true);
				$address_name = fw_get_db_post_option($post->ID, 'address_name', true);
				$ipn_track_id = fw_get_db_post_option($post->ID, 'ipn_track_id', true);
				$transaction_status = fw_get_db_post_option($post->ID, 'transaction_status', true);
				$payment_method = fw_get_db_post_option($post->ID, 'payment_method', true);
				$full_address = fw_get_db_post_option($post->ID, 'full_address', true);
				$first_name = fw_get_db_post_option($post->ID, 'first_name', true);
				$last_name = fw_get_db_post_option($post->ID, 'last_name', true);
				$payment_method = fw_get_db_post_option($post->ID, 'payment_method', true);
				$purchase_on = fw_get_db_post_option($post->ID, 'purchase_on', true);
				
				$package_name	= get_the_title( $package_id );
				$purchase_on	= date('d M, y',strtotime( $purchase_on ));
				$user 			= get_user_by( 'id', intval( $user_identity ) );
				$payment_amount	= $mc_currency.$payment_gross;
				
			} else{
				$user_identity = esc_html__('NILL','docdirect_core');
				$package_id = esc_html__('NILL','docdirect_core');
				$txn_id = esc_html__('NILL','docdirect_core');
				$payment_gross = esc_html__('NILL','docdirect_core');
				$item_name = esc_html__('NILL','docdirect_core');
				$payer_email = esc_html__('NILL','docdirect_core');
				$mc_currency = esc_html__('NILL','docdirect_core');
				$ipn_track_id = esc_html__('NILL','docdirect_core');
				$transaction_status = esc_html__('NILL','docdirect_core');
				$payment_method = esc_html__('NILL','docdirect_core');
				$full_address = esc_html__('NILL','docdirect_core');
				$first_name = esc_html__('NILL','docdirect_core');
				$last_name = esc_html__('NILL','docdirect_core');
				$payment_method = esc_html__('NILL','docdirect_core');
				$purchase_on = esc_html__('NILL','docdirect_core');
				
				$package_name	= esc_html__('NILL','docdirect_core');
				$payment_amount	= '';
			}
			
			?>
			<ul class="invoice-info">
				<li>
					<strong><?php esc_html_e('Transaction ID','docdirect_core');?></strong>
					<span><?php echo esc_attr( $txn_id );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Package','docdirect_core');?></strong>
					<span><?php echo esc_attr( $package_name );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Payment Amount','docdirect_core');?></strong>
					<span><?php echo esc_attr( $mc_currency.$payment_gross );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Payment Status','docdirect_core');?></strong>
					<span><?php echo esc_attr( docdirect_prepare_order_status( 'value',$transaction_status ) );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Payment Method','docdirect_core');?></strong>
					<span><?php echo esc_attr( docdirect_prepare_payment_type( 'value',$payment_method ) );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Payment Date','docdirect_core');?></strong>
					<span><?php echo esc_attr( $purchase_on );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Payer Name','docdirect_core');?></strong>
					<span><?php echo esc_attr( $first_name.' '.$last_name );?></span>
				</li>
				<li>
					<strong><?php esc_html_e('Payer Address','docdirect_core');?></strong>
					<span><?php echo esc_attr( $full_address );?></span>
				</li>
				<?php if( isset( $user->data ) && !empty( $user->data ) ){?>
					<li>
						<strong><?php esc_html_e('User','docdirect_core');?></strong>
						<span><a href="<?php echo get_edit_user_link($user_identity);?>" target="_blank" title="<?php esc_html__('Click for user details','docdirect_core');?>"><?php echo esc_attr( $user->data->display_name );?></a></span>
					</li>
				<?php }?>
			</ul>
			<?php
		}
		
		/**
		 * @Init Invoice info
		 * @return {post}
		 */
		public function tg_invoices_meta_box_invoiceinfo(){
			global $post;
			
			if (function_exists('fw_get_db_settings_option')) {
				$invoice_id = fw_get_db_post_option($post->ID, 'txn_id', true);
			} else{
				$invoice_id = esc_html__('NILL','docdirect_core');
			}
			
			?>
			<ul class="invoice-info">
				<li>
					<strong><?php esc_html_e('Invoice ID','docdirect_core');?></strong>
					<span><?php echo esc_attr( $invoice_id );?></span>
				</li>
			</ul>
			<?php
		}
		
		
	}
	
  	new TG_Invoices();	
}