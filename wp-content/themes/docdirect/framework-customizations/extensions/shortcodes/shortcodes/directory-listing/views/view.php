<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */

$show_posts	= isset( $atts['show_posts'] ) && !empty( $atts['show_posts'] ) ? $atts['show_posts'] : '-1';
$uni_flag = fw_unique_increment();
$dir_search_page = fw_get_db_settings_option('dir_search_page');
if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
	$search_page 	 = get_permalink((int)$dir_search_page[0]);
} else{
	$search_page 	 = '';
}

$args = array('posts_per_page' => $show_posts, 
		'post_type' => 'directory_type', 
		'post_status' => 'publish',
		'suppress_filters' => false
	);
$cust_query = get_posts($args);
?>

<div class="sc-dir-types catagories-types">
  <div class="tg-findbycategorys">
    <div class="specialities-list">
      <ul>
        <?php 
		if( isset( $cust_query ) && !empty( $cust_query ) ) {
			  $counter	= 0;
			  $first_category	= '';
			  $json	= array();
			  $directories	= '';
			  $flag	= false;
			  $dir_search_page = fw_get_db_settings_option('dir_search_page');
			  
			  if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
			 	$search_page 	 = get_permalink((int)$dir_search_page[0]);
			  } else{
				$search_page 	 = '';
			  }

			  foreach ($cust_query as $key => $dir) {
				$counter++;
				$title = get_the_title($dir->ID);
				$dir_icon = fw_get_db_post_option($dir->ID, 'dir_icon', true);
				$dir_map_marker = fw_get_db_post_option($dir->ID, 'dir_map_marker', true);
				if( empty( $dir_icon ) ){
					$dir_icon	= 'icon-Hospitalmedicalsignalofacrossinacircle';
				}
				$user_query = new WP_User_Query( 
									array ( 
										'role' => 'professional',
										'order' => 'ASC',
										'meta_query' => array(
											'relation' => 'AND',
											array(
												'key'     => 'directory_type',
												'value'   => $dir->ID,
												'compare' => '='
											),
											array(
												'key'     => 'verify_user',
												'value'   => 'on',
												'compare' => '='
											),
										)
									)
								);
				?>
                <li id="dir-<?php echo esc_attr( $dir->ID );?>">
                  <div class="tg-checkbox user-selection">
                    <div class="tg-packages">
                      <a href="<?php echo esc_url( $search_page );?>?directory_type=<?php echo esc_attr( $dir->post_name );?>">
                          <label>
							  <?php if( !empty( $dir_icon ) ){?>
                                <i class="<?php echo esc_attr($dir_icon);?>"></i>
                              <?php }?>
                              <?php echo esc_attr( $title );?><span class="count"><?php echo intval( count($user_query->get_results()) );?></span>
                          </label>
                      </a>
                    </div>
                  </div>
                </li>
			<?php
                  }
            }
        ?>
      </ul>
    </div>
  </div>
</div>
