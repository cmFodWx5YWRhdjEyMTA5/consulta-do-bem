<?php

/**
 * @Woocommerce Customization
 * return {}
 */
if (!class_exists('docdirect_woocommerace')) {

    class docdirect_woocommerace {

        function __construct() {

            //add_filter('woocommerce_enqueue_styles', '__return_false');
            add_filter('woocommerce_register_post_type_product', array(&$this, 'docdirect_label_woo'));
            add_action('woocommerce_product_options_general_product_data', array(&$this, 'docdirect_package_meta'));
            add_action('woocommerce_process_product_meta', array(&$this, 'docdirect_save_package_meta'));
			add_action( 'woocommerce_checkout_fields', array( &$this, 'docdirect_custom_checkout_update_customer' ), 10);
        }
		
		/**
		 * @Checkout First and last name 
		 * @return {}
		 */
		public function docdirect_custom_checkout_update_customer( $fields ){
			$user = wp_get_current_user();
			$first_name = $user ? $user->user_firstname : '';
			$last_name  = $user ? $user->user_lastname : '';
			$fields['billing']['billing_first_name']['default'] = $first_name;
			$fields['billing']['billing_last_name']['default']  = $last_name;
			return $fields;
		}
		
        /**
         * @Rename Product Menu
         * return {}
         */
        public function docdirect_label_woo($args) {
            $labels = array(
                'name' => esc_html__('Packages/Products', 'docdirect'),
                'singular_name' => esc_html__('Packages/Products', 'docdirect'),
                'menu_name' => esc_html__('Packages/Products', 'docdirect'),
                'add_new' => esc_html__('Add Package/Product', 'docdirect'),
                'add_new_item' => esc_html__('Add New Package/Product', 'docdirect'),
                'edit' => esc_html__('Edit Package/Product', 'docdirect'),
                'edit_item' => esc_html__('Edit Package/Product', 'docdirect'),
                'new_item' => esc_html__('New Package/Product', 'docdirect'),
                'view' => esc_html__('View Package/Product', 'docdirect'),
                'view_item' => esc_html__('View Package/Product', 'docdirect'),
                'search_items' => esc_html__('Search Packages/Product', 'docdirect'),
                'not_found' => esc_html__('No Packages/Products found', 'docdirect'),
                'not_found_in_trash' => esc_html__('No Packages/Products found in trash', 'docdirect'),
                'parent' => esc_html__('Parent Package/Product', 'docdirect')
            );

            $args['labels'] = $labels;
            $args['description'] = esc_html__('This is where you can add new tours to your store.', 'docdirect');
            return $args;
        }

        /**
         * @Package Meta save
         * return {}
         */
        public function docdirect_save_package_meta($post_id) {
            if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
				update_post_meta($post_id, 'dd_duration', esc_attr($_POST['dd_duration']));
				update_post_meta($post_id, 'dd_featured', esc_attr($_POST['dd_featured']));
				update_post_meta($post_id, 'dd_appointments', esc_attr($_POST['dd_appointments']));
				update_post_meta($post_id, 'dd_banner', esc_attr($_POST['dd_banner']));
				update_post_meta($post_id, 'dd_insurance', esc_attr($_POST['dd_insurance']));
				update_post_meta($post_id, 'dd_favorites', esc_attr($_POST['dd_favorites']));
				update_post_meta($post_id, 'dd_teams', esc_attr($_POST['dd_teams']));
				update_post_meta($post_id, 'dd_hours', esc_attr($_POST['dd_hours']));
				
				if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {
					update_post_meta($post_id, 'dd_articles', esc_attr($_POST['dd_articles']));
				}
				
				if ( function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
					if( apply_filters('docdirect_get_theme_settings', 'qa_restriction') === 'paid' ){
						update_post_meta($post_id, 'dd_qa', esc_attr($_POST['dd_qa']));
					}
				}
				
			}else{
				update_post_meta($post_id, 'dd_duration', esc_attr($_POST['dd_duration']));
			}
        }

        /**
         * @Package Meta
         * return {}
         */
        public function docdirect_package_meta($args) {
            global $woocommerce, $post;
			
			if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
				woocommerce_wp_text_input(
						array(
							'id' => 'dd_duration',
							'class' => 'dd_duration dd-woo-field',
							'label' => esc_html__('Package Duration', 'docdirect'),
							'placeholder' => '10',
							'desc_tip' => 'true',
							'description' => esc_html__('Add duration(days) for this package. Please add only integer value. eg : 30', 'docdirect'),
							'type' => 'number',
							'custom_attributes' => array(
								'step' => '1',
								'min' => '1'
							)
						)
				);
				
				woocommerce_wp_text_input(
						array(
							'id' => 'dd_featured',
							'class' => 'dd_featured  dd_provider dd-woo-field',
							'label' => esc_html__('Feature duration', 'docdirect'),
							'placeholder' => '',
							'desc_tip' => 'true',
							'description' => esc_html__('Add duration(days) for featured listing. Please add only integer value. eg : 30, leave it empty to exlude from featured listing', 'docdirect'),
							'type' => 'number',
							'custom_attributes' => array(
								'step' => '1',
								'min' => '1'
							)
						)
				);

				woocommerce_wp_select(
						array(
							'id' => 'dd_appointments',
							'class' => 'dd_appointments  dd_provider dd-woo-field',
							'label' => esc_html__('Appointments included?', 'docdirect'),
							'options' => array(
								'no' => esc_html__('No', 'docdirect'),
								'yes' => esc_html__('Yes', 'docdirect'),
							)
						)
				);

				woocommerce_wp_select(
						array(
							'id' => 'dd_banner',
							'class' => 'dd_banner dd_provider dd-woo-field',
							'label' => esc_html__('Profile banner included?', 'docdirect'),
							'options' => array(
								'no' => esc_html__('No', 'docdirect'),
								'yes' => esc_html__('Yes', 'docdirect'),
							)
						)
				);
				
				woocommerce_wp_select(
						array(
							'id' => 'dd_insurance',
							'class' => 'dd_insurance dd_provider dd-woo-field',
							'label' => esc_html__('Insurance included?', 'docdirect'),
							'options' => array(
								'no' => esc_html__('No', 'docdirect'),
								'yes' => esc_html__('Yes', 'docdirect'),
							)
						)
				);
				
				woocommerce_wp_select(
						array(
							'id' => 'dd_favorites',
							'class' => 'dd_favorites dd-woo-field',
							'label' => esc_html__('Favorites included?', 'docdirect'),
							'options' => array(
								'no' => esc_html__('No', 'docdirect'),
								'yes' => esc_html__('Yes', 'docdirect'),
							)
						)
				);

				woocommerce_wp_select(
						array(
							'id' => 'dd_teams',
							'class' => 'dd_teams dd_provider dd-woo-field',
							'label' => esc_html__('Teams included?', 'docdirect'),
							'options' => array(
								'no' => esc_html__('No', 'docdirect'),
								'yes' => esc_html__('Yes', 'docdirect'),
							)
						)
				);
				
				woocommerce_wp_select(
						array(
							'id' => 'dd_hours',
							'class' => 'dd_hours dd_provider dd-woo-field',
							'label' => esc_html__('Business hours included?', 'docdirect'),
							'options' => array(
								'no' => esc_html__('No', 'docdirect'),
								'yes' => esc_html__('Yes', 'docdirect'),
							)
						)
				);
				
				if ( function_exists('fw_get_db_settings_option') && fw_ext('articles')) {
					woocommerce_wp_text_input(
							array(
								'id' => 'dd_articles',
								'class' => 'dd_articles dd_provider dd-woo-field',
								'label' => esc_html__('Articles included?', 'docdirect'),
								'placeholder' => '',
								'desc_tip' => 'true',
								'description' => esc_html__('Add number of articles if your want to enable articles in this package. Leave it empty to exclude.', 'docdirect'),
								'type' => 'number',
								'custom_attributes' => array(
									'step' => '1',
									'min' => '0'
								)
							)
					);
				}
				if ( function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
					if( apply_filters('docdirect_get_theme_settings', 'qa_restriction') === 'paid' ){
						woocommerce_wp_select(
							array(
								'id' => 'dd_qa',
								'class' => 'dd_qa dd_provider',
								'label' => esc_html__('Question and Answers included?', 'docdirect'),
								'desc_tip' => 'true',
								'description' => esc_html__("If question and answers included then users will be able to post replies and also get questions on their profiles.", 'docdirect'),
								'options' => array(
									'no' => esc_html__('No', 'docdirect'),
									'yes' => esc_html__('Yes', 'docdirect'),
								)
							)
						);
					}
				}
			} else{
				woocommerce_wp_text_input(
						array(
							'id' => 'dd_duration',
							'class' => 'dd_duration dd-woo-field',
							'label' => esc_html__('Package Duration', 'docdirect'),
							'placeholder' => '10',
							'desc_tip' => 'true',
							'description' => esc_html__('Add duration(days) for this package. Please add only integer value. eg : 30', 'docdirect'),
							'type' => 'number',
							'custom_attributes' => array(
								'step' => '1',
								'min' => '1'
							)
						)
				);
			}
            
        }

    }

    new docdirect_woocommerace();
}