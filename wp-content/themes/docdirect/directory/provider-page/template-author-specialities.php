<?php
/**
 *
 * Author specialities Template.
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

if( !empty( $author_profile->user_profile_specialities ) ) {?>
  <div class="tg-honourawards tg-listview-v3 user-section-style sp-icon-wrap">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('Specialties','docdirect');?></h2>
	</div>
	<div class="tg-doctor-profile">
		  <ul class="tg-tags">
			  <?php
				do_action('enqueue_unyson_icon_css');														
				foreach( $author_profile->user_profile_specialities as $key => $value ){
					$get_speciality_term = get_term_by('slug', $key, 'specialities');
					$speciality_title = '';
					$term_id = '';
					if (!empty($get_speciality_term)) {
						$speciality_title = $get_speciality_term->name;
						$term_id = $get_speciality_term->term_id;
					}

					$speciality_meta = array();
					if (function_exists('fw_get_db_term_option')) {
						$speciality_meta = fw_get_db_term_option($term_id, 'specialities');
					}

					$speciality_icon = array();
					if (!empty($speciality_meta['icon']['icon-class'])) {
						$speciality_icon = $speciality_meta['icon']['icon-class'];
					}
				 ?>
				<li>
					<div class="specialities-wrap">
						<span class="icon-sp">
							<?php 
							if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'icon-font') {
								if (!empty($speciality_icon)) { ?>
									<i class="<?php echo esc_attr($speciality_icon); ?>"></i>
								<?php 
								}
							} else if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'custom-upload') {
								if (!empty($speciality_meta['icon']['url'])) {
								?>
								<img src="<?php echo esc_url($speciality_meta['icon']['url']);?>">
							<?php }}?>
						</span>                    		
						<span><?php echo esc_attr( $value );?></span>
					</div>
				</li>
			  <?php }?>
		  </ul>
	  </div>
  </div>
<?php }