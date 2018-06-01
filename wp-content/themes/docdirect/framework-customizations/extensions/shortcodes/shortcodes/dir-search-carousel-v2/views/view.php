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
	$dir_keywords 			= fw_get_db_settings_option('dir_keywords');
	$zip_code_search 		= fw_get_db_settings_option('zip_code_search');
	$dir_location 			= fw_get_db_settings_option('dir_location');
	$dir_radius 			= fw_get_db_settings_option('dir_radius');
	$dir_geo 				= fw_get_db_settings_option('dir_geo');
	$language_search 		= fw_get_db_settings_option('language_search');
	$dir_search_cities 		= fw_get_db_settings_option('dir_search_cities');
	$dir_search_insurance 	= fw_get_db_settings_option('dir_search_insurance');
	$dir_phote 				= fw_get_db_settings_option('dir_phote');
	
	$dir_latitude = fw_get_db_settings_option('dir_latitude');
	$dir_latitude = fw_get_db_settings_option('dir_latitude');
	$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
} else{
	$dir_keywords = '';
	$zip_code_search = '';
	$dir_location = '';
	$dir_radius = '';
	$language_search = '';
	$dir_search_cities = '';
	$dir_geo = '';
	
	$dir_longitude = '-0.1262362';
	$dir_latitude  = '51.5001524';
}
$flagslider	= rand(1,9999);

$languages_array	= docdirect_prepare_languages();//Get Language Array

$banner_class	= 'doc-bannercontent';
if( empty( $atts['bg']['url'] ) ){
	$banner_class	= 'doc-bannercontent-without';
}

$isadvance_filter	= 'advance-filter-disabled';
if( !empty( $atts['advance_filters'] ) && $atts['advance_filters'] === 'enable' ){
	$isadvance_filter	= 'advance-filter-enabled';
}
?>

<div id="doc-homebannerslider" class="doc-homebannerslider doc-haslayout <?php echo esc_attr( $isadvance_filter );?>">
	<figure class="doc-bannerimg">
		<?php if( !empty( $atts['bg']['url'] ) ){?>
			<img src="<?php echo esc_url( $atts['bg']['url'] );?>" alt="<?php esc_html_e( 'Search Filters','docdirect' );?>">
		<?php }?>
		<figcaption class="<?php echo esc_attr( $banner_class );?>">
			<div class="container">
				<div class="row">
					<div class="col-sm-offset-1 col-sm-10 col-xs-offset-0 col-xs-12">
						<form class="doc-formtheme doc-formadvancesearch" action="<?php echo esc_url( $search_page);?>" method="get">
							<div id="doc-homecatagoryslider-<?php echo esc_attr($flagslider);?>" class="doc-homecatagoryslider owl-carousel">
								<?php 
								$directories			= array();
								$first_category			= '';
								$json					= array();
								$flag					= false;
								if( isset( $cust_query ) && !empty( $cust_query ) ) {
									$counter	= 0;
									foreach ($cust_query as $key => $dir) {
											$counter++;
											$title		= get_the_title($dir->ID);
											$checked	= '';
											$active		= '';
											if( $counter === 1 ){ 
												$current_directory = get_the_title($dir->ID);
												$active	= 'active';
												$first_category	= $dir->ID;
												$checked	= 'checked';
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
												$json[$dir->ID] = $subarray;
											}
											$parent_categories['categories']	= $json;
											?>
										<div class="item" data-id="<?php echo intval( $dir->ID );?>" data-dir_name="<?php echo esc_attr( $title );?>"> 
											<input name="directory_type" <?php echo esc_attr( $checked );?> id="input-<?php echo intval( $dir->ID );?>" data-dir_name="<?php echo esc_attr( $title );?>" type="radio" value="<?php echo esc_attr( $dir->post_name );?>">
											<span><?php esc_html_e( 'find the nearest','docdirect' );?></span>
											<h1><?php echo get_the_title($dir->ID);?></h1> 
										</div>
									<?php }} else{
										$directories['status']	= 'empty'; 
								}?>
							</div>
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
								<option value="">{{data['parent']}} - <?php esc_html_e('Specialities','docdirect');?></option>
								<#
									var _option = '';
									if( !_.isEmpty(data['childrens']) ) {
										_.each( data['childrens'] , function(element, index, attr) { #>
											 <option value="{{index}}">{{element}}</option>
										<#
										});
									}
								#>
							</script>
							<script>
								jQuery(document).ready(function(e) {
									jQuery('#doc-openclose-<?php echo esc_js($flagslider);?>').on('click', function(event){
										event.preventDefault();
										var _this	= jQuery(this);
										jQuery('#doc-advancesearch-<?php echo esc_js($flagslider);?>').slideToggle();

										if( _this.parents('.doc-homebannerslider').hasClass('doc-filter-closed') ){
											_this.parents('.doc-homebannerslider').removeClass('doc-filter-closed');
										} else{
											_this.parents('.doc-homebannerslider').addClass('doc-filter-closed');
											jQuery('html, body').animate({
												scrollTop: jQuery("#doc-homebannerslider").offset().top
											}, 2000);
										}
									});

									var owl = jQuery("#doc-homecatagoryslider-<?php echo esc_js($flagslider);?>");
									owl.owlCarousel({
										items:1,
										nav:true,
										rtl: <?php docdirect_owl_rtl_check();?>,
										loop: true,
										dots: false,
										autoplay: false,
										navText : ['<i class="doc-btnprev fa fa-angle-left"></i>','<i class="doc-btnnext fa fa-angle-right"></i>'],
									});
									
									owl.on('changed.owl.carousel', function(event) {
										var current = event.item.index;
										var id = jQuery(event.target).find(".owl-item").eq(current).find(".item").data('id');;
										var dir_name = jQuery(event.target).find(".owl-item").eq(current).find(".item").data('dir_name');
										if( Z_Editor.elements[id] ) {
											var load_subcategories = wp.template( 'load-subcategories' );
											var data = [];
											data['childrens'] = Z_Editor.elements[id];
											data['parent'] = dir_name;
											var _options = load_subcategories(data);
											jQuery( '.subcats' ).html(_options);
											jQuery('.subcats').trigger("chosen:updated");
										}
										jQuery('#input-'+id).prop('checked','checked');
									})
								});
							</script>
							<div class="doc-bannersearcharea">
								<fieldset>
									<div class="doc-fieldsetholder">
									<?php if( isset( $dir_keywords ) && $dir_keywords === 'enable' ){?>
										<div class="form-group">
										   <input type="text" name="by_name" placeholder="<?php esc_html_e('Type Keyword...','docdirect');?>" class="form-control">
										</div>
									<?php }?>
									<?php if( isset( $dir_location ) && $dir_location === 'enable' ){?>
										<div class="form-group">
										  <div class="tg-inputicon tg-geolocationicon tg-angledown">
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
									  <div class="doc-select choosen-custom">
										<select class="group subcats chosen-select" name="speciality[]">
										  <option value=""><?php esc_html_e('Specialities','docdirect');?></option>
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
									  </div>
									</div>
									</div>
									<button type="submit" class="doc-btnformsearch"><i class="fa fa-search"></i></button>
								</fieldset>
								<?php if( !empty( $atts['advance_filters'] ) && $atts['advance_filters'] === 'enable' ){?>
								<fieldset id="doc-advancesearch-<?php echo esc_attr($flagslider);?>">
									<legend><?php esc_html_e('Advanced search','docdirect');?></legend>
									<div class="row doc-rowmargin">
									  <?php if( isset( $language_search ) && $language_search === 'enable' ){?>
										  <?php  if( isset( $languages_array ) && !empty( $languages_array ) ){?>
										  <div class="col-sm-4 col-xs-6 doc-columnpadding">
											<div class="form-group">
											  <div class="doc-select">
												<select name="languages[]" class="chosen-select">
												 <option value=""><?php esc_attr_e('Select language','docdirect');?></option>
												 <?php 
													foreach( $languages_array as $key=>$value ){
														$selected	= '';
														if( !empty( $_GET['languages'] ) && in_array( $key , $_GET['languages']) ){
															$selected	= 'selected';
														}
														?>
														<option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value );?></option>
												 <?php }?>
												</select>
											  </div>
											</div>
										  </div>
										  <?php }?>
									  <?php }?>
									  <?php if( isset( $dir_search_insurance ) && $dir_search_insurance === 'enable' ){?>
									  <div class="col-sm-4 col-xs-6 doc-columnpadding">
										<div class="form-group">
										  <div class="doc-select">
											  <select name="insurance" class="chosen-select">
												<option value=""><?php esc_attr_e('Select insurance','docdirect');?></option>
												<?php docdirect_get_term_options('','insurance');?>
											  </select>
										   </div>
										</div>
									  </div>
									  <?php }?>
									  <?php if( isset( $dir_search_cities ) && $dir_search_cities === 'enable' ){?>
									  <div class="col-sm-4 col-xs-6 doc-columnpadding">
										<div class="form-group">
										  <div class="doc-select">
											  <select name="city" class="chosen-select">
												<option value=""><?php esc_attr_e('Select city','docdirect');?></option>
												<?php docdirect_get_term_options('','locations');?>
											  </select>
										   </div>
										</div>
									  </div>
									  <?php }?>
									  <div class="col-sm-4 col-xs-6 doc-columnpadding">
										<div class="form-group">
										  <div class="doc-select">
											  <select name="sort_by" class="sort_by" id="sort_by">
												  <option value=""><?php esc_html_e('Sort By','docdirect');?></option>
												  <option value="recent" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'recent' ? 'selected' : '';?>><?php esc_html_e('Most recent','docdirect');?></option>
												  <option value="featured" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'featured' ? 'selected' : '';?>><?php esc_html_e('Featured','docdirect');?></option>
												  <option value="title" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'title' ? 'selected' : '';?>><?php esc_html_e('Alphabetical','docdirect');?></option>
												  <option value="distance" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'distance' ? 'selected' : '';?>><?php esc_html_e('Sort By Distance','docdirect');?></option>
												  <option value="likes" <?php echo isset( $_GET['sort_by'] ) && $_GET['sort_by'] == 'likes' ? 'selected' : '';?>><?php esc_html_e('Sort By Likes','docdirect');?></option>
											  </select>
											</div>
										</div>
									  </div>
									  <div class="col-sm-4 col-xs-6 doc-columnpadding">
										<div class="form-group">
										  <div class="doc-select">
											  <span class="doc-select">
												<select class="order_by" name="order" id="order">
												  <option value="ASC" <?php echo isset( $_GET['order'] ) && $_GET['order'] == 'ASC' ? 'selected' : '';?>><?php esc_html_e('ASC','docdirect');?></option>
												  <option value="DESC" <?php echo isset( $_GET['order'] ) && $_GET['order'] == 'DESC' ? 'selected' : '';?>><?php esc_html_e('DESC','docdirect');?></option>
												</select>
											  </span> 
											</div>
										</div>
									  </div>
									  <?php if( isset( $dir_phote ) && $dir_phote === 'enable' ){?>
										  <div class="col-sm-4 col-xs-6 doc-columnpadding">
											<div class="form-group"> 
											  <span class="doc-checkbox">
												  <input type="checkbox" name="photos" id="photos" value="true">
												  <label for="photos"><?php esc_html_e('Search All With Profile Photos','docdirect');?></label>
											  </span> 
											</div>
										  </div>
									  <?php }?>
									  <div class="col-sm-12 col-xs-12 doc-columnpadding">
										<div class="doc-btnarea">
										  <button class="doc-btn" type="submit"><?php esc_html_e('Apply Filter','docdirect');?></button>
										  <button class="doc-btn tg-btn-reset" type="reset"><?php esc_html_e('Reset Filter','docdirect');?></button>
										</div>
									  </div>
									</div>
								</fieldset>
								<a id="doc-openclose-<?php echo esc_attr($flagslider);?>" class="doc-openclose" href="javascript:;"><i class="fa fa-angle-down"></i></a>
								<?php }?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</figcaption>
	</figure>
</div>

