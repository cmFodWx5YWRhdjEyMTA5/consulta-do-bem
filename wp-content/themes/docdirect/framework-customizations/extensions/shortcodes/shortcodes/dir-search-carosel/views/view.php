<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();

$args = array('posts_per_page' => '-1', 
			   'post_type' => 'directory_type', 
			   'post_status' => 'publish',
			   'suppress_filters' => false
		);


$cust_query = get_posts($args);
docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map
$dir_search_page = fw_get_db_settings_option('dir_search_page');

if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
	$search_page 	 = get_permalink((int)$dir_search_page[0]);
} else{
	$search_page 	 = '';
}

if (function_exists('fw_get_db_settings_option')) {
	$dir_location = fw_get_db_settings_option('dir_location');
	$dir_keywords = fw_get_db_settings_option('dir_keywords');
	$dir_longitude = fw_get_db_settings_option('dir_longitude');
	$dir_latitude = fw_get_db_settings_option('dir_latitude');
	$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
} else{
	$dir_location = '';
	$dir_keywords = '';
	$dir_longitude = '-0.1262362';
	$dir_latitude  = '51.5001524';
}

$gallery	= $atts['gallery'];
if( empty( $gallery ) ){
	$gallery	=  array();
	$gallery[0]['url']	= get_template_directory_uri().'/images/banner.jpg';
}
$flag	= rand(1,9999);
?>
<div class="tg-banner-holder">
    <div class="tg-banner-content">
        <div class="container">
            <?php if( !empty( $atts['title'] ) ){?>
            <div class="tg-heading-border">
                <h1><span><?php echo esc_attr( $atts['title'] );?></span><div class="dynamic-title"></div></h1>
            </div>
            <?php }?>
            <div class="tg-searcharea-v2">
                <form class="tg-searchform directory-map" action="<?php echo esc_url( $search_page);?>" method="get" id="directory-map">
                    <fieldset>
                       <?php if( isset( $dir_keywords ) && $dir_keywords === 'enable' ){?>
                          <div class="form-group">
                            <input type="text" name="by_name" placeholder="<?php esc_html_e('Type Keyword...','docdirect');?>" class="form-control">
                          </div>
                        <?php }?>
                        <?php if( isset( $dir_location ) && $dir_location === 'enable' ){?>
                        <div class="form-group">
                            <div class="locate-me-wrap">
                               <?php docdirect_locateme_snipt();?>
								<script>
                                    jQuery(document).ready(function(e) {
                                        //init
                                        jQuery.docdirect_init_map(<?php echo esc_js( $dir_latitude );?>,<?php echo esc_js( $dir_longitude );?>);
                                    });
                                </script> 

                            </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                            <span class="select">
                               <select class="directory_type chosen-select" name="directory_type">
                                    <option value=""><?php esc_html_e('All','docdirect');?></option>
                                    <?php 
										$directories	   = array();
										$first_category	= '';
										$json			  = array();
										$flag	= false;
								   		
								   
										if( isset( $cust_query ) && !empty( $cust_query ) ) {
											  $counter	= 0;
											  foreach ($cust_query as $key => $dir) {
													$counter++;
													$title = get_the_title($dir->ID);
													
													
													$active	= '';
												  	$selected	= '';
													
												  	if( $counter === 1 ){ 
														$current_directory = get_the_title($dir->ID);
														$active	= 'active';
														$first_category	= $dir->ID;
														$selected	= 'selected=selected';
													}
													
													//Prepare categories
													if( isset( $dir->ID ) ){
														$attached_specialities = get_post_meta( $dir->ID, 'attached_specialities', true );
														$subarray	= array();
														if( isset( $attached_specialities ) && !empty( $attached_specialities ) ){
															foreach( $attached_specialities as $key => $speciality ){
																if( !empty( $speciality ) ) {
																	$term_data	= get_term_by( 'id', $speciality, 'specialities');
																	if( !empty( $term_data ) ) {
																		$subarray[$term_data->slug] = $term_data->name;
																	}
																}
															}
														}
														
														$json[$dir->ID]	= $subarray;
													}
							
													$parent_categories['categories']	= $json;
													
													?>
													<option <?php echo esc_attr( $selected );?> id="<?php echo intval( $dir->ID );?>" data-dir_name="<?php echo esc_attr( $title );?>" value="<?php echo esc_attr( $dir->post_name );?>"><?php echo esc_attr( ucwords( $title ) );?></option>
										  <?php }?>
										  <?php } else{
											  $directories['status']	= 'empty'; 
										}?>
                                </select>
                            </span>
                            <script>
								jQuery(document).ready(function() {
									var Z_Editor = {};
									Z_Editor.elements = {};
									window.Z_Editor = Z_Editor;
									Z_Editor.elements = jQuery.parseJSON( '<?php echo addslashes(json_encode($parent_categories['categories']));?>' );
									jQuery('.dynamic-title').html("<?php echo esc_js( $current_directory );?>");
								});
							</script> 
							<script type="text/template" id="tmpl-load-subcategories">
								<#
									var _option	= '';
									if( !_.isEmpty(data['childrens']) ) {#>
										<option value="">{{data['parent']}} - <?php esc_html_e('Specialities','docdirect');?></option>
										<# _.each( data['childrens'] , function(element, index, attr) { #>
											 <option value="{{index}}">{{element}}</option>
										<#	
										});
									} else {#>
										<option value=""><?php esc_html_e('All','docdirect');?></option>
									<# }
								#>
							</script> 
                        </div>
                        <div class="form-group">
                            <span class="select">
                                <select class="group subcats chosen-select" name="speciality[]">
                                  <option value="">
                                  	<?php esc_html_e('Specialities','docdirect');?>
                                  </option>
                                  <?php 
                                    if( isset( $first_category ) ){
                                        $attached_specialities = get_post_meta( $first_category, 'attached_specialities', true );
                                        if( isset( $attached_specialities ) && !empty( $attached_specialities ) ){
                                            foreach( $attached_specialities as $key => $speciality ){
                                                if( !empty( $speciality ) ) {
                                                    $term_data	= get_term_by( 'id', $speciality, 'specialities');
                                                    if( !empty( $term_data ) ) {?>
                                                        <option value="<?php echo esc_attr( $term_data->slug);?>"><?php echo esc_attr( $term_data->name );?></option>
                      <?php
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </span>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="search_banner" class="tg-btn" value="<?php esc_html_e('search','docdirect');?>" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div id="searchbanner-<?php echo esc_attr( $flag );?>" class="tg-homeslidertwo owl-carousel">
        <?php 
		if( !empty( $gallery ) ){
            foreach( $gallery as $key => $value ){?>
            <div class="item">
                <figure>
                    <img src="<?php echo esc_url( $value['url'] );?>" alt="<?php esc_attr_e( 'Search','docdirect' );?>">
                </figure>
            </div>
        <?php }}?>
    </div>
    <script>
		jQuery(document).ready(function(e) {
            jQuery("#searchbanner-<?php echo esc_js( $flag );?>").owlCarousel({
				items:1,
				nav:true,
				rtl: <?php docdirect_owl_rtl_check();?>,
				loop: true,
				dots: false,
				autoplay: false,
				navText : ["<i class='tg-prev fa fa-angle-left'></i>", "<i class='tg-next fa fa-angle-right'></i>"],
			});
        });
	</script>
</div>
