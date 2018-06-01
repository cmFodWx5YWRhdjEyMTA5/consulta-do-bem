<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
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

$services_cats = get_user_meta($user_identity , 'services_cats' , true);
$booking_services = get_user_meta($user_identity , 'booking_services' , true);
$custom_slots = get_user_meta($user_identity , 'custom_slots' , true);
$currency_symbol	       = get_user_meta( $user_identity, 'currency_symbol', true);

$currency_symbol	= !empty( $currency_symbol ) ? ' ('.$currency_symbol.')' : '';
if( !empty( $custom_slots ) ){
	$custom_slot_list	= json_decode( $custom_slots,true );
} else{
	$custom_slot_list = array();
}

$custom_slot_list	= docdirect_prepare_seprate_array($custom_slot_list);
?>
<div class="doc-booking-settings dr-bookings">
    <div class="tg-haslayout">
        <div class="booking-settings-data">
            <div class="tg-dashboard tg-docappointment tg-haslayout">
              <ul class="tg-navdocappointment" role="tablist">
                <li role="presentation" class="active"><a href="#one" aria-controls="one" role="tab" data-toggle="tab"><?php esc_html_e('offer service(s) and fee','docdirect');?></a></li>
                <li role="presentation"><a href="#two" aria-controls="two" role="tab" data-toggle="tab"><?php esc_html_e('Time slot(s)','docdirect');?></a></li>
                <li role="presentation"><a href="#three" aria-controls="three" role="tab" data-toggle="tab"><?php esc_html_e('Custom time slot(s)','docdirect');?></a></li>
              </ul>
              <div class="tab-content tg-appointmenttabcontent">
                <div role="tabpanel" class="tab-pane active" id="one">
                  <div class="tg-heading-border tg-small">
                    <h3><?php esc_html_e('offer service(s) and fee','docdirect');?></h3>
                  </div>
                  <div class="row">
                    <div class="col-md-4 col-sm-12">
                      <div class="tg-doccategoties">
                        <div class="bk-category-wrapper">
                            <?php 
								if( !empty( $services_cats ) ) {
									foreach( $services_cats as $key => $value ){
										?>
                                        <div class="bk-category-item">
                                            <div class="tg-doccategory">
                                                <span class="tg-catename"><?php echo esc_attr( $value );?></span>
                                                <span class="tg-catelinks">
                                                    <a href="javascript:;" class="bk-edit-category"><i class="fa fa-edit"></i></a>
                                                    <a href="javascript:;" data-type="db-delete" data-key="<?php echo esc_attr( $key );?>" class="bk-delete-category"><i class="fa fa-trash-o"></i></a>
                                                </span>
                                            </div>
                                            <div class="tg-editcategory bk-current-category bk-elm-hide">
                                                <div class="form-group">
                                                    <input  data-key="<?php echo esc_attr( $key );?>" type="text" value="<?php echo esc_attr( $value );?>" class="form-control service-category-title" name="categoryname" placeholder="<?php esc_attr_e('Category Title','docdirect');?>">
                                                </div>
                                                <div class="form-group tg-btnarea">
                                                <button class="tg-update bk-maincategory-add" data-key="<?php echo esc_attr( $key );?>" data-type="update" type="submit"><?php esc_html_e('Update Now','docdirect');?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
									}
								}
							?>
                        </div>
                        <a id="search_banner" class="tg-btn tg-btn-lg bk-add-category-item" href="javascript:;"><?php esc_html_e('Add Category','docdirect');?></a>
                      </div> 
                    </div>
                    <div class="col-md-8 col-sm-12">
                      <div class="tg-subdoccategoties">
                        <h4><?php esc_html_e('Add Services','docdirect');?></h4>
                        <button class="tg-btn bk-add-service-item"><?php esc_html_e('Add Service','docdirect');?></button>
                        
                        <div class="bk-services-wrapper">
                        	<?php 
								if( !empty( $booking_services ) ) {
									foreach( $booking_services as $key => $value ){
										?>
                                        <div class="bk-service-item">
                                            <div class="tg-subdoccategory"> 
                                                <span class="tg-catename"><?php echo esc_attr( $value['title'] );?></span> 
                                                <span class="tg-catelinks"> 
                                                    <a href="javascript:;" class="bk-edit-service"><i class="fa fa-edit"></i></a>
                                                    <a href="javascript:;"  data-type="db-delete" data-key="<?php echo esc_attr( $key );?>" class="bk-delete-service"><i class="fa fa-trash-o"></i></a>
                                                </span> 
                                                <span class="tg-serviceprice"><?php echo esc_attr( $value['price'] );?></span> 
                                            </div>
                                            <div class="tg-editcategory bk-current-service bk-elm-hide">
                                              <div class="form-group">
                                                <div class="tg-select">
                                                    <select name="service_category" class="service_category">
                                                        <option value=""><?php esc_html_e('Select category','docdirect');?></option>
															<?php 
															if( !empty( $services_cats ) ) {
																foreach( $services_cats as $cat_key => $cat ){
																	$selected	= $cat_key == $value['category'] ? 'selected': '';
																	?>
                                                            	<option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $cat_key );?>"><?php echo esc_attr( $cat );?></option>
															<?php
                                                                    }
                                                                }
                                                            ?>
                                                    </select>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <input type="text"  value="<?php echo esc_attr( $value['title'] );?>" class="form-control service-title" name="service_title" placeholder="<?php esc_html_e('Service Title','docdirect');?>">
                                              </div>
                                              <div class="form-group">
                                                <input type="text" value="<?php echo esc_attr( $value['price'] );?>" class="form-control service-price" name="service_price" placeholder="<?php esc_attr_e('Add Price','docdirect');?> <?php echo esc_attr( $currency_symbol );?>">
                                              </div>
                                              <div class="form-group tg-btnarea">
                                              <button class="tg-update  bk-service-add" data-key="<?php echo esc_attr( $key );?>" data-type="update" type="submit"><?php esc_html_e('Update Now','docdirect');?></button>
                                            </div></div>
                                        </div>
                                        <?php
									}
								}
							?>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="two">
                  <div class="tg-heading-border tg-small">
                    <h3><?php esc_html_e('appointment schedule','docdirect');?></h3>
                  </div>
                  <div class="tg-daytimeslot" data-day="sun">
                    <div class="tg-dayname"> 
                      <strong><?php esc_html_e('sunday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="mon"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('sun','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                  <div class="tg-daytimeslot" data-day="mon">
                    <div class="tg-dayname"> <strong><?php esc_html_e('Monday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="mon"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('mon','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                  <div class="tg-daytimeslot" data-day="tue">
                    <div class="tg-dayname"> <strong><?php esc_html_e('Tuesday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="tue"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('tue','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                  <div class="tg-daytimeslot"  data-day="wed">
                    <div class="tg-dayname"> <strong><?php esc_html_e('Wednesday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="wed"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('wed','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                  <div class="tg-daytimeslot"  data-day="thu">
                    <div class="tg-dayname"> <strong><?php esc_html_e('Thursday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="thu"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('thu','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                  <div class="tg-daytimeslot"  data-day="fri">
                    <div class="tg-dayname"> <strong><?php esc_html_e('Friday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="fri"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('fri','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                  <div class="tg-daytimeslot" data-day="sat">
                    <div class="tg-dayname"> <strong><?php esc_html_e('Saturday','docdirect');?></strong>
                      <ul class="tg-links">
                        <li><a href="javascript:;" class="add-default-slots" data-key="sat"><?php esc_html_e('Add Time Slots','docdirect');?></a></li>
                      </ul>
                    </div>
                    <div class="tg-timeslots"> 
                        <div class="timeslots-data-area">
                        	<?php docdirect_get_default_slots('sat','echo');?>
                        </div>
                        <div class="timeslots-form-area">
                        </div>
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="three">
                  <div class="tg-heading-border tg-small">
                    <h3><?php esc_attr_e('Custom time slot(s)','docdirect');?></h3>
                  </div>
                  <div class="custom-slots-main tg-timeslots">
                      <form action="#" method="post" class="custom-slots-main" >
                          <div class="custom-timeslots-dates_wrap">
                          	<?php
							if( !empty( $custom_slot_list[0] ) ){
								foreach( $custom_slot_list  as $key => $value ){
									
									$start_date			 = !empty( $value['cus_start_date'] ) ? $value['cus_start_date'] : '';
									$end_date			   = !empty( $value['cus_end_date'] ) ? $value['cus_end_date'] : '';
									$disable_appointment	=  !empty( $value['disable_appointment'] ) ? $value['disable_appointment'] : '';
									
									if( !empty( $start_date ) && !empty( $end_date ) ){
										$date_formate	= date_i18n( 'M, d Y',strtotime( $start_date ) ) .'-'. date_i18n( 'M, d Y',strtotime( $end_date ) );
									} else if( !empty( $start_date ) && empty( $end_date ) ){
										$date_formate	= date_i18n( 'M, d Y',strtotime( $start_date ) );
									} else if( empty( $start_date ) && !empty( $end_date ) ){
										$date_formate	= date_i18n( 'M, d Y',strtotime( $end_date ) );
									} else{
										$date_formate	= esc_html__('Custom Slots','docdirect');
									}
								?>
                                <div class="tg-daytimeslot">
                                  <div class="custom-time-periods">
                                    <div class="tg-dayname"> 
                                      <a class="tg-deleteslot delete-slot-date" href="javascript:;"><i class="fa fa-close"></i></a>
                                      <strong>
                                        <?php echo esc_attr( $date_formate );?>
                                      </strong>
                                      <ul class="tg-links">
                                        <li>
                                          <a href="javascript:;" class="add-custom-timeslots">
                                            <?php esc_html_e('Add Slots','docdirect');?>
                                          </a>
                                        </li>
                                      </ul>
                                    </div>
                                    <div class="tg-timeslots tg-fieldgroup">
                                      <div class="tg-timeslotswrapper">
                                        <div class="form-group tg-calender">
                                          <input type="hidden" class="custom_time_slots" name="custom_time_slots" value="<?php echo htmlentities(stripslashes(json_encode($value['custom_time_slots']))); ?>" />
              							  <input type="hidden" class="custom_time_slot_details" name="custom_time_slot_details" value="<?php echo htmlentities(stripslashes(json_encode($value['custom_time_slot_details']))); ?>" />
                                          <input type="text" class="form-control slots-datepickr" name="cus_start_date" placeholder="<?php esc_attr_e('Start Date','docdirect');?>" value="<?php echo esc_attr( $start_date );?>" />
                                        </div>
                                        <div class="form-group tg-calender">
                                          <input type="text"  value="<?php echo esc_attr( $end_date );?>" class="form-control slots-datepickr" name="cus_end_date" placeholder="<?php esc_attr_e('End Date','docdirect');?>" />
                                        </div>
                                        <div class="form-group">
                                          <div class="tg-select">
                                            <select name="disable_appointment" class="disable_appointment">
                                              <option value="enable" <?php echo isset( $disable_appointment ) && $disable_appointment == 'enable' ? 'selected' : '';?>>
                                                <?php esc_attr_e('Enable Appointment','docdirect');?>
                                              </option>
                                              <option value="disable" <?php echo isset( $disable_appointment ) && $disable_appointment == 'disable' ? 'selected' : '';?>>
                                                <?php esc_attr_e('Disbale Appointment','docdirect');?>
                                              </option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="custom-timeslots-data-area">
                                      	<?php 
											if (!empty($value['custom_time_slots'])){
												
												if (is_array($value['custom_time_slots'])){
													$timeslots = $value['custom_time_slots'];
												} else{
													$timeslots = json_decode($value['custom_time_slots'],true);
												}

												if ( isset($value['custom_time_slot_details']) 
													&& 
													is_array($value['custom_time_slot_details'])
												){
													$timeslots_details = $value['custom_time_slot_details'];
												} else if(isset($value['custom_time_slot_details'])){
													$timeslots_details = json_decode($value['custom_time_slot_details'],true);
												}

												$json	=  array();
												$json['timeslot']	 = $timeslots;
												$json['timeslot_details']  = $timeslots_details;
												docdirect_get_custom_slots($json,'echo');
											}
										?>
                                      </div>
                                      <div class="custom-timeslots-data"></div>
                                    </div>
                                  </div>
                                </div>
							<?php }}?>
                          </div>
                      </form>
                  </div>
                  <div class="tg-daytimeslot custom-slots-action">
                     <button class="tg-btn bk-add-dates" type="button"><?php esc_html_e('Add Dates','docdirect');?></button>
                     <input type="hidden" class="custom_timeslots_object" name="custom_timeslots_object" value="<?php echo esc_attr( $custom_slots );?>"  />
                     
                     <button class="tg-btn bk-save-custom-slots" type="button"><?php esc_html_e('Save Time Slots','docdirect');?></button>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<!----------------------------------------------
 * Main categories Templates
 * return HTML
------------------------------------------------>
<script type="text/template" id="tmpl-append-service-category">
	<div class="bk-category-item">
		<div class="tg-doccategory">
			<span class="tg-catename"><?php esc_html_e('Category Title','docdirect');?></span>
			<span class="tg-catelinks">
				<a href="javascript:;" class="bk-edit-category"><i class="fa fa-edit"></i></a>
				<a href="javascript:;"  data-type="new-delete" data-key="" class="bk-delete-category"><i class="fa fa-trash-o"></i></a>
			</span>
		</div>
		<div class="tg-editcategory bk-current-category bk-elm-hide">
			<div class="form-group">
				<input type="text" class="form-control service-category-title" name="categoryname" placeholder="<?php esc_html_e('Category Title','docdirect');?>">
			</div>
			<div class="form-group tg-btnarea">
			<button class="tg-update bk-maincategory-add" data-key="new" data-type="add" type="submit"><?php esc_html_e('Update Now','docdirect');?></button>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-update-service-category">
	<div class="tg-doccategory">
		<span class="tg-catename">{{data.title}}</span>
		<span class="tg-catelinks">
			<a href="javascript:;" class="bk-edit-category"><i class="fa fa-edit"></i></a>
			<a href="javascript:;" data-type="db-delete" data-key="{{data.key}}" class="bk-delete-category"><i class="fa fa-trash-o"></i></a>
		</span>
	</div>
	<div class="tg-editcategory bk-current-category bk-elm-hide">
		<div class="form-group">
			<input type="text" data-key="{{data.key}}" value="{{data.title}}" class="form-control service-category-title" name="categoryname" placeholder="<?php esc_html_e('Category Title','docdirect');?>">
		</div>
		<button class="tg-update bk-maincategory-add" data-key="{{data.key}}" data-type="update" type="submit"><?php esc_html_e('Update Now','docdirect');?></button>
		
	</div>
</script>

<!----------------------------------------------
 * Services Templates
 * return HTML
------------------------------------------------>

<script type="text/template" id="tmpl-append-service">
	<div class="bk-service-item">
		<div class="tg-subdoccategory"> 
			<span class="tg-catename"><?php esc_html_e('Service Title','docdirect');?></span> 
			<span class="tg-catelinks"> 
				<a href="javascript:;" class="bk-edit-service"><i class="fa fa-edit"></i></a>
				<a href="javascript:;"  data-type="new-delete" data-key="" class="bk-delete-service"><i class="fa fa-trash-o"></i></a>
			</span> 
			<span class="tg-serviceprice">0.00</span> 
		</div>
		<div class="tg-editcategory bk-current-service bk-elm-hide">
		  <div class="form-group">
			<div class="tg-select">
				<select name="service_category" class="service_category">
					<option value=""><?php esc_html_e('Select category','docdirect');?></option>
					<# _.each( data , function( element, index, attr ) { #>
						<option value="{{index}}">{{element}}</option>
					<# } ); #>
				</select>
			</div>
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control service-title" name="service_title" placeholder="<?php esc_html_e('Service Title','docdirect');?>">
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control service-price" name="service_price" placeholder="<?php esc_html_e('Add Price','docdirect');?><?php echo esc_attr( $currency_symbol );?>">
		  </div>
		  <div class="form-group tg-btnarea">
		  <button class="tg-update  bk-service-add" data-key="new" data-type="add" type="submit"><?php esc_html_e('Update Now','docdirect');?></button>
		</div></div>
	</div>
</script>

<script type="text/template" id="tmpl-update-service">
	<div class="tg-subdoccategory"> 
		<span class="tg-catename">{{data.service_title}}</span> 
		<span class="tg-catelinks"> 
			<a href="javascript:;" class="bk-edit-service"><i class="fa fa-edit"></i></a>
			<a href="javascript:;"  data-type="db-delete" data-key="{{data.key}}" class="bk-delete-service"><i class="fa fa-trash-o"></i></a>
		</span> 
		<span class="tg-serviceprice">{{data.service_price}}</span> 
	</div>
	<div class="tg-editcategory bk-current-service bk-elm-hide">
	  <div class="form-group">
		<div class="tg-select">
			<select name="service_category" class="service_category">
				<option value=""><?php esc_html_e('Select category','docdirect');?></option>
				<# _.each( data.cats , function( element, index, attr ) { #>
					<# if( index == data.service_category ){ #>
						<option selected value="{{index}}">{{element}}</option>
					<# } else { #>
						<option value="{{index}}">{{element}}</option>
					<# } #>
				<# } ); #>
			</select>
		</div>
	  </div>
	  <div class="form-group">
		<input type="text" value="{{data.service_title}}" class="form-control service-title" name="service_title" placeholder="<?php esc_html_e('Service Title','docdirect');?>">
	  </div>
	  <div class="form-group">
		<input type="text" value="{{data.service_price}}" class="form-control service-price" name="service_price" placeholder="<?php esc_html_e('Add Price','docdirect');?> <?php echo esc_attr( $currency_symbol );?>">
	  </div>
	  <div class="form-group tg-btnarea">
	  <button class="tg-update  bk-service-add" data-key="{{data.key}}" data-type="update" type="submit"><?php esc_html_e('Update Now','docdirect');?></button>
	</div></div>
</script>


<script type="text/template" id="tmpl-append-options">
	<option value=""><?php esc_html_e('Select category','docdirect');?></option>
	<# _.each( data , function( element, index, attr ) { #>
		<option value="{{index}}">{{element}}</option>
	<# } ); #>
</script>

<!----------------------------------------------
 * Default Time Slots
 * return HTML
------------------------------------------------>
<script type="text/template" id="tmpl-default-slots">
	<div class="tg-timeslotswrapper">
		<div class="form-group">
		  <input type="text" name="slot_title" class="form-control" name="title" placeholder="<?php esc_attr_e('Title (Optional)','docdirect');?>">
		</div>
		<div class="form-group">
		  <div class="tg-select">
			<select name="start_time" class="start_time">
			  <option value=""><?php esc_attr_e('Start Time','docdirect');?></option>
			  <option value="0000">12:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0100">1:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0200">2:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0300">3:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0400">4:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0500">5:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0600">6:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0700">7:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0800">8:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0900">9:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="1000">10:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="1100">11:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="1200">12:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1300">1:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1400">2:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1500">3:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1600">4:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1700">5:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1800">6:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1900">7:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2000">8:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2100">9:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2200">10:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2300">11:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2400">12:00 <?php esc_attr_e('am (night)','docdirect');?></option>
			</select>
		  </div>
		</div>
		<div class="form-group">
		  <div class="tg-select">
			<select name="end_time" class="end_time">
			  <option value=""><?php esc_attr_e('End Time','docdirect');?></option>
			  <option value="0000">12:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0100">1:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0200">2:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0300">3:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0400">4:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0500">5:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0600">6:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0700">7:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0800">8:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="0900">9:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="1000">10:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="1100">11:00 <?php esc_attr_e('am','docdirect');?></option>
			  <option value="1200">12:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1300">1:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1400">2:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1500">3:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1600">4:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1700">5:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1800">6:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="1900">7:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2000">8:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2100">9:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2200">10:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2300">11:00 <?php esc_attr_e('pm','docdirect');?></option>
			  <option value="2400">12:00 <?php esc_attr_e('am (night)','docdirect');?></option>
			</select>
		  </div>
		</div>
		<div class="form-group">
		  <div class="tg-select">
			<select name="meeting_time" class="meeting_time">
				<option value=""><?php esc_attr_e('Meeting Time','docdirect');?></option>
				<option value="60">1 <?php esc_attr_e('hours','docdirect');?></option>
				<option value="90">1 <?php esc_attr_e('hour','docdirect');?>, 30 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="120">2 <?php esc_attr_e('hours','docdirect');?></option>
				<option value="45">45 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="30">30 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="20">20 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="15">15 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="10">10 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="5">5 <?php esc_attr_e('minutes','docdirect');?></option>
			</select>
		  </div>
		</div>
		<div class="form-group">
		  <div class="tg-select">
			<select name="padding_time" class="padding_time">
				<option value=""><?php esc_attr_e('Padding/Break Time','docdirect');?></option>
				<option value="90">1 <?php esc_attr_e('hour','docdirect');?>, 30 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="5">5 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="10">10 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="15">15 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="20">20 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="30">30 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="45">45 <?php esc_attr_e('minutes','docdirect');?></option>
				<option value="60">1 <?php esc_attr_e('hour','docdirect');?></option>
			</select>
		  </div>
		</div>
		<div class="tg-btnbox">
		  <button type="submit" class="tg-btn save-time-slots"><?php esc_html_e('save','docdirect');?></button>
		  <button type="submit" class="tg-btn remove-slots-form"><?php esc_html_e('Cancel','docdirect');?></button>
		</div>
	  </div>
	</div>
</script>

<script type="text/template" id="tmpl-no-slots">
	<span class="tg-notimeslotmessage">
		<p><?php esc_html_e('NO TIME SLOTS','docdirect');?></p>
	</span>
</script>

<!----------------------------------------------
 * Custom Time Slots
 * return HTML
------------------------------------------------>
<script type="text/template" id="tmpl-custom-timelines">
	<div class="tg-daytimeslot">
	  <div class="custom-time-periods">
		<div class="tg-dayname"> 
		  <a class="tg-deleteslot delete-slot-date" href="javascript:;"><i class="fa fa-close"></i></a>
		  <strong><?php esc_html_e('custom slot','docdirect');?></strong>
		  <ul class="tg-links">
			<li><a href="javascript:;" class="add-custom-timeslots"><?php esc_html_e('Add Slots','docdirect');?></a></li>
		  </ul>
		</div>
        <div class="tg-timeslots tg-fieldgroup">
		  <div class="tg-timeslotswrapper">
			<div class="form-group tg-calender">
			  <input type="hidden" class="custom_time_slots" name="custom_time_slots" value="" />
              <input type="hidden" class="custom_time_slot_details" name="custom_time_slot_details" value="" />
			  <input type="text" class="form-control slots-datepickr" name="cus_start_date" placeholder="<?php esc_attr_e('Start Date','docdirect');?>" />
			</div>
			<div class="form-group tg-calender">
			  <input type="text" class="form-control slots-datepickr" name="cus_end_date" placeholder="<?php esc_attr_e('End Date','docdirect');?>" />
			</div>
			<div class="form-group">
			  <div class="tg-select">
				<select name="disable_appointment" class="disable_appointment">
					<option value="enable"><?php esc_attr_e('Enable Appointment','docdirect');?></option>
					<option value="disable"><?php esc_attr_e('Disbale Appointment','docdirect');?></option>
				</select>
			  </div>
			</div>
		  </div>
		  <div class="custom-timeslots-data-area"></div>
		  <div class="custom-timeslots-data"></div>
		</div>
	</div>
</div>
</script>

<script type="text/template" id="tmpl-custom-slots">
	<div class="tg-timeslotswrapper">
		<form action="#" method="post" class="time-slots-form">
			<div class="form-group">
			  <input type="text" name="slot_title" class="form-control" name="title" placeholder="<?php esc_attr_e('Title (Optional)','docdirect');?>">
			</div>
			<div class="form-group">
			  <div class="tg-select">
				<select name="start_time" class="start_time">
				  <option><?php esc_attr_e('Start Time','docdirect');?></option>
				  <option value="0000">12:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0100">1:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0200">2:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0300">3:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0400">4:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0500">5:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0600">6:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0700">7:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0800">8:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0900">9:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="1000">10:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="1100">11:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="1200">12:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1300">1:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1400">2:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1500">3:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1600">4:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1700">5:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1800">6:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1900">7:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2000">8:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2100">9:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2200">10:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2300">11:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2400">12:00 <?php esc_attr_e('am (night)','docdirect');?></option>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <div class="tg-select">
				<select name="end_time" class="end_time">
				  <option><?php esc_attr_e('End Time','docdirect');?></option>
				  <option value="0000">12:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0100">1:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0200">2:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0300">3:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0400">4:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0500">5:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0600">6:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0700">7:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0800">8:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="0900">9:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="1000">10:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="1100">11:00 <?php esc_attr_e('am','docdirect');?></option>
				  <option value="1200">12:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1300">1:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1400">2:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1500">3:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1600">4:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1700">5:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1800">6:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="1900">7:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2000">8:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2100">9:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2200">10:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2300">11:00 <?php esc_attr_e('pm','docdirect');?></option>
				  <option value="2400">12:00 <?php esc_attr_e('am (night)','docdirect');?></option>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <div class="tg-select">
				<select name="meeting_time" class="meeting_time">
					<option><?php esc_attr_e('Meeting Time','docdirect');?></option>
					<option value="60">1 <?php esc_attr_e('hours','docdirect');?></option>
					<option value="90">1 <?php esc_attr_e('hour','docdirect');?>, 30 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="120">2 <?php esc_attr_e('hours','docdirect');?></option>
					<option value="45">45 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="30">30 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="20">20 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="15">15 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="10">10 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="5">5 <?php esc_attr_e('minutes','docdirect');?></option>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <div class="tg-select">
				<select name="padding_time" class="padding_time">
					<option><?php esc_attr_e('Padding/Break Time','docdirect');?></option>
					<option value="90">1 <?php esc_attr_e('hour','docdirect');?>, 30 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="5">5 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="10">10 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="15">15 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="20">20 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="30">30 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="45">45 <?php esc_attr_e('minutes','docdirect');?></option>
					<option value="60">1 <?php esc_attr_e('hour','docdirect');?></option>
				</select>
			  </div>
			</div>
			<div class="tg-btnbox">
			  <button type="submit" class="tg-btn save-custom-time-slots"><?php esc_html_e('save','docdirect');?></button>
			  <button type="submit" class="tg-btn remove-slots-form"><?php esc_html_e('Cancel','docdirect');?></button>
			</div>
		  </div>
		</div>
   </div>
</script>
