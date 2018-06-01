<?php
/**
 *  Template Name: Dashboard
 * 
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;
if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}
 
if( isset( $_GET['identity'] ) && $_GET['identity'] != $user_identity ) {
	do_action('docdirect_update_profile_hits',$url_identity); //Update Profile Hits
}

do_action('docdirect_is_user_active',$url_identity);
do_action('docdirect_is_user_verified',$url_identity);

get_header();

$rtl_class	= '';
if( is_rtl() ){
	$rtl_class = 'pull-right ';
}
?>
<div class="container">
	<div class="row">
		<?php if (is_active_sidebar('user-dashboard-top')) {?>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="tg-haslayout ads-dashboard-top">
                <?php dynamic_sidebar('user-dashboard-top'); ?>
              </div>
          </div>
        <?php }?>
		<?php if( apply_filters( 'docdirect_do_check_user_existance', $url_identity ) ){?>
		<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 <?php echo sanitize_html_class( $rtl_class );?>">
			<aside id="tg-sidebar" class="dashboard-sidebar">
				<?php $dir_obj->docdirect_get_avatar();?>
				<?php $dir_obj->docdirect_profile_menu();?>
                <?php if (is_active_sidebar('user-dashboard-sidebar')) {?>
                  <div class="tg-doctors-list tg-haslayout">
                    <?php dynamic_sidebar('user-dashboard-sidebar'); ?>
                  </div>
                <?php }?>
			</aside>
		</div>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 pull-right">
			<?php
				if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'schedules' 
					&& $url_identity == $user_identity 
					&& apply_filters('docdirect_do_check_user_type',$url_identity ) === true
				    && apply_filters('docdirect_is_setting_enabled',$url_identity,'schedules' ) === true
				){
					get_template_part('directory/templates/user','schedules');
				} else if( isset( $_GET['ref'] )
					 && $_GET['ref'] === 'invoices' 
					 && $url_identity == $user_identity 
					 && apply_filters('docdirect_do_check_user_type',$url_identity ) === true
				 ){
					get_template_part('directory/templates/user','payments');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'settings' 
					&& $url_identity == $user_identity 
				){
					get_template_part('directory/templates/user','account-settings');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'bookings' 
					&& $url_identity == $user_identity
					&& apply_filters('docdirect_do_check_user_type',$url_identity ) === true 
					&& apply_filters('docdirect_is_setting_enabled',$url_identity,'appointments' ) === true 
				){
					get_template_part('directory/bookings/templates/user','bookings');
				} else if (( isset($_GET['ref']) && $_GET['ref'] === 'articles' ) 
						   && ( isset($_GET['mode']) && $_GET['mode'] === 'listing' ) 
						   && $url_identity == $user_identity
				) {
					if ( post_type_exists( 'sp_articles' ) ) {
						do_action('render_article_listing_view');
					} else{
						DoctorDirectory_NotificationsHelper::informations(esc_html__('Sorry, This feature is compatible with latest version of DocDirect Core Plugin( Since release 3.5 ). Please contact to your site administrator for this issue.','docdirect'));	
					}
				} else if (( isset($_GET['ref']) && $_GET['ref'] === 'articles' ) 
						   && ( isset($_GET['mode']) && $_GET['mode'] === 'add' ) 
						   && $url_identity == $user_identity
				) {	
					if ( post_type_exists( 'sp_articles' ) ) {
						do_action('render_article_add_view');
					} else{
						DoctorDirectory_NotificationsHelper::informations(esc_html__('Sorry, This feature is compatible with latest version of DocDirect Core Plugin( Since release 3.5 ). Please contact to your site administrator for this issue.','docdirect'));	
					}
				} else if (( isset($_GET['ref']) && $_GET['ref'] === 'articles' ) 
						   && ( isset($_GET['mode']) && $_GET['mode'] === 'edit' ) 
						   && $url_identity == $user_identity
				) {
					if ( post_type_exists( 'sp_articles' ) ) {
						do_action('render_article_edit_view');
					} else{
						DoctorDirectory_NotificationsHelper::informations(esc_html__('Sorry, This feature is compatible with latest version of DocDirect Core Plugin( Since release 3.5 ). Please contact to your site administrator for this issue.','docdirect'));	
					}
				}else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'teams' 
					&& $url_identity == $user_identity
					&& apply_filters('docdirect_do_check_user_type',$url_identity ) === true 
					&& apply_filters('docdirect_do_check_teams',$url_identity ) === true  
					&& apply_filters('docdirect_is_setting_enabled',$url_identity,'team' ) === true
				){
					get_template_part('directory/templates/user','teams');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'booking-schedules' 
					&& $url_identity == $user_identity 
					&& apply_filters('docdirect_do_check_user_type',$url_identity ) === true  
					&& apply_filters('docdirect_is_setting_enabled',$url_identity,'appointments' ) === true 
				){
					get_template_part('directory/bookings/templates/booking','schedules');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'booking-settings' 
					&& $url_identity == $user_identity
					&& apply_filters('docdirect_do_check_user_type',$url_identity ) === true  
					&& apply_filters('docdirect_is_setting_enabled',$url_identity,'appointments' ) === true
				){
					get_template_part('directory/bookings/templates/booking','settings');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'wishlist' 
					&& $url_identity == $user_identity   
				){
					if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true){
						if( apply_filters('docdirect_is_setting_enabled',$user_identity,'favorite' ) === true ){
							get_template_part('directory/templates/user','favourites');
						}
					} else{
						get_template_part('directory/templates/user','favourites');
					}
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'security' 
					&& $url_identity == $user_identity 
				){
					get_template_part('directory/templates/user','security-settings');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'privacy-settings' 
					&& $url_identity == $user_identity
					&& apply_filters('docdirect_do_check_user_type',$url_identity ) === true  
				){
					get_template_part('directory/templates/user','privacy-settings');
				} else if( isset( $_GET['ref'] ) 
					&& $_GET['ref'] === 'favourites' 
					&& $url_identity == $user_identity 
				){
					get_template_part('directory/templates/user','account-settings');
				}else{
					get_template_part('directory/templates/dashboard','company'); //Show when current user
					get_template_part('directory/templates/user','profile');
				}
			?>
		</div>
		<?php } else{?>
			<div class="col-xs-12">
				<?php DoctorDirectory_NotificationsHelper::warning(esc_html__('No kiddies please!','docdirect'));?>
			</div>
		<?php }?>
        
	</div>
</div>

<!----------------------------------------------------
 * Undercore HTML Tempaltes
 ------------------------------------------------- -->
<script type="text/template" id="tmpl-load-gallery">
	<figure>
		<a href="javascript:;"><img height="56" width="56" src="{{data.url}}"></a>
		<div class="tg-img-hover" data-attachment_id="{{data.attachment_id}}">
			<a href="javascript:;" data-attachment="{{data.attachment_id}}"><i class="fa fa-plus"></i><i class="fa fa-refresh fa-spin"></i></a>
		</div>
		<input type="hidden" value="{{data.attachment_id}}" name="user_gallery[{{data.attachment_id}}][attachment_id]">
		<input type="hidden" value="{{data.url}}" name="user_gallery[{{data.attachment_id}}][url]">
	</figure>
</script>
<!--Awards-->
<script type="text/template" id="tmpl-load-awards">
	<tbody class="awards_item new-added">
	  <tr>
		<td data-title="Code"><?php esc_html_e('Award Title','docdirect');?>
		  <div class="tg-table-hover award-action"> 
			<a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a>
			<a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		   </div>
		</td>
		<td data-title="Company"><?php esc_html_e('January 01, 2020','docdirect');?></td>
	  </tr>
	  <tr>
		<td class="award-data edit-me-row"colspan="2">
			<div class="tg-education-form tg-haslayout">
				<div class="award-data">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="form-group">
							<input class="form-control" value="" name="awards[{{data}}][name]" type="text" placeholder="<?php esc_attr_e('Award Name','docdirect');?>">
						</div>
					</div>
					<div class="col-md-8 col-sm-6 col-xs-12">
						<div class="form-group">
							<input class="form-control award_datepicker" id="award_datepicker" value="" name="awards[{{data}}][date]" type="text" placeholder="<?php esc_attr_e('Award Date','docdirect');?>">
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<textarea class="form-control" name="awards[{{data}}][description]" placeholder="<?php esc_attr_e('Award Description','docdirect');?>"></textarea>
						</div>
					</div>
				</div>
			</div>
		</td>
	  </tr>
	</tbody>
</script>
<script type="text/template" id="tmpl-append-awards">
	<# if( _.isArray(data) && ! _.isEmpty(data) ) { #>
	<table class="table-striped awards_wrap">
		<thead class="cf">
		  <tr>
			<th><?php esc_html_e('Title','docdirect');?></th>
			<th><?php esc_html_e('Year','docdirect');?></th>
		  </tr>
		</thead>
		<# _.each( data , function( element, index, attr ) { #>
			
			<tbody class="awards_item new-added">
			  <tr>
				<td data-title="Code">{{element.name}}
				  <div class="tg-table-hover award-action"> 
					<a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a>
					<a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
				   </div>
				</td>
				<td data-title="Company">{{element.date_formated}}</td>
			  </tr>
			  <tr>
				<td class="award-data edit-me-row"colspan="2">
					<div class="tg-education-form tg-haslayout">
						<div class="award-data">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<div class="form-group">
									<input class="form-control" value="{{element.name}}" name="awards[{{index}}][name]" type="text" placeholder="<?php esc_attr_e('Award Name','docdirect');?>">
								</div>
							</div>
							<div class="col-md-8 col-sm-6 col-xs-12">
								<div class="form-group">
									<input class="form-control award_datepicker" id="award_datepicker" value="{{element.date}}" name="awards[{{index}}][date]" type="text" placeholder="<?php esc_attr_e('Award Date','docdirect');?>">
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<textarea class="form-control" name="awards[{{index}}][description]" placeholder="<?php esc_attr_e('Award Description','docdirect');?>">{{element.description}}</textarea>
								</div>
							</div>
						</div>
					</div>
				</td>
			  </tr>
			</tbody>
			
		<# } ); #>
	<# } #>
</script>
<!--Education-->
<script type="text/template" id="tmpl-load-educations">
	<tbody class="educations_item">
	  <tr>
		<td data-title="Code"><?php esc_attr_e('Title here','docdirect');?>
		  <div class="tg-table-hover education-action"> 
			  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
			  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		  </div>
		</td>
		<td data-title="Company"><?php esc_attr_e('Institute here','docdirect');?></td>
		<td data-title="Price" class="numeric"><?php esc_attr_e('Jan,2020 - Jan,2021','docdirect');?></td>
	  </tr>
	  <tr>
	   <td class="education-data edit-me-row" colspan="3">
		 <div class="education-data-wrap">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="education[{{data}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="education[{{data}}][institute]" type="text" placeholder="<?php esc_attr_e('Institute','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control edu_start_date_{{data}}" id="edu_start_date" value="" name="education[{{data}}][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control edu_end_date_{{data}}" id="edu_end_date" value="" name="education[{{data}}][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="education[{{data}}][description]" placeholder="<?php esc_attr_e('Education Description','docdirect');?>"></textarea>
				</div>
			</div>
		  </div>
	    </td>
	  </tr>
	</tbody>
</script>
<script type="text/template" id="tmpl-append-educations">
	<# if( _.isArray(data) && ! _.isEmpty(data) ) { #>
		<table class="table-striped educations_wrap" id="table-striped">
		<thead class="cf">
		  <tr>
			<th><?php esc_html_e('Degree / Education Title','docdirect');?></th>
			<th><?php esc_html_e('Institute','docdirect');?></th>
			<th class="numeric"><?php esc_html_e('Year','docdirect');?></th>
		  </tr>
		</thead>
		<# _.each( data , function( element, index, attr ) { #>
		<tbody class="educations_item">
		  <tr>
			<td data-title="Code">{{element.title}}
			  <div class="tg-table-hover education-action"> 
				  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
				  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
			  </div>
			</td>
			<td data-title="Company">{{element.institute}}</td>
			<td data-title="Price" class="numeric">{{element.start_date_formated}} - {{element.end_date_formated}}</td>
		  </tr>
		  <tr>
		   <td class="education-data edit-me-row" colspan="3">
			 <div class="education-data-wrap">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control" value="{{element.title}}" name="education[{{index}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control" value="{{element.institute}}" name="education[{{index}}][institute]" type="text" placeholder="<?php esc_attr_e('Institute','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control edu_start_date_{{index}}" id="edu_start_date" value="{{element.start_date}}" name="education[{{index}}][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control edu_end_date_{{index}}" id="edu_end_date" value="{{element.end_date}}" name="education[{{index}}][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<textarea class="form-control" name="education[{{index}}][description]" placeholder="<?php esc_attr_e('Education Description','docdirect');?>">{{element.description}}</textarea>
					</div>
				</div>
			  </div>
			</td>
		  </tr>
		</tbody>
		<# } ); #>
	<# } #>
</script>

<!--Experience-->
<script type="text/template" id="tmpl-load-experiences">
	<tbody class="experiences_item">
	  <tr>
		<td data-title="Code"><?php esc_attr_e('Title here','docdirect');?>
		  <div class="tg-table-hover experience-action"> 
			  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
			  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		  </div>
		</td>
		<td data-title="Company"><?php esc_attr_e('Company/Organization Name','docdirect');?></td>
		<td data-title="Price" class="numeric"><?php esc_attr_e('Jan,2020 - Jan,2021','docdirect');?></td>
	  </tr>
	  <tr>
	   <td class="experience-data edit-me-row" colspan="3">
		 <div class="experience-data-wrap">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="experience[{{data}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="experience[{{data}}][company]" type="text" placeholder="<?php esc_attr_e('Company/Organization','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control exp_start_date_{{data}}" id="exp_start_date" value="" name="experience[{{data}}][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control exp_end_date_{{data}}" id="exp_end_date" value="" name="experience[{{data}}][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="experience[{{data}}][description]" placeholder="<?php esc_attr_e('Experience Description','docdirect');?>"></textarea>
				</div>
			</div>
		  </div>
	    </td>
	  </tr>
	</tbody>
</script>
<script type="text/template" id="tmpl-append-experiences">
	<# if( _.isArray(data) && ! _.isEmpty(data) ) { #>
		<table class="table-striped experience_wrap" id="table-striped">
		<thead class="cf">
		  <tr>
			<th><?php esc_html_e('Experience Title','docdirect');?></th>
			<th><?php esc_html_e('Company/Organization','docdirect');?></th>
			<th class="numeric"><?php esc_html_e('Year','docdirect');?></th>
		  </tr>
		</thead>
		<# _.each( data , function( element, index, attr ) { #>
		<tbody class="experiences_item">
		  <tr>
			<td data-title="Code">{{element.title}}
			  <div class="tg-table-hover experience-action"> 
				  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
				  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
			  </div>
			</td>
			<td data-title="Company">{{element.company}}</td>
			<td data-title="Price" class="numeric">{{element.start_date_formated}} - <# if(! _.isEmpty(element.end_date) ) { #>{{element.end_date_formated}} <# } else { #><?php esc_html_e('Current','docdirect');?><# } #></td>
		  </tr>
		  <tr>
		   <td class="experience-data edit-me-row" colspan="3">
			 <div class="experience-data-wrap">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control" value="{{element.title}}" name="experience[{{index}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control" value="{{element.company}}" name="experience[{{index}}][company]" type="text" placeholder="<?php esc_attr_e('Company/Organization','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control edu_start_date_{{index}}" id="exp_start_date" value="{{element.start_date}}" name="experience[{{index}}][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control exp_end_date_{{index}}" id="exp_end_date" value="{{element.end_date}}" name="experience[{{index}}][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<textarea class="form-control" name="experience[{{index}}][description]" placeholder="<?php esc_attr_e('Experience Description','docdirect');?>">{{element.description}}</textarea>
					</div>
				</div>
			  </div>
			</td>
		  </tr>
		</tbody>
		<# } ); #>
	<# } #>
</script>

<!--Price List-->
<script type="text/template" id="tmpl-load-prices">
	<tbody class="prices_item">
	  <tr>
		<td data-title="Code"><?php esc_attr_e('Title','docdirect');?>
		  <div class="tg-table-hover prices-action"> 
			  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
			  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
		  </div>
		</td>
		<td data-title="Company"><?php esc_attr_e('Price','docdirect');?></td>
	  </tr>
	  <tr>
	   <td class="prices-data edit-me-row" colspan="3">
		 <div class="prices-data-wrap">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="prices[{{data}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<input class="form-control" value="" name="prices[{{data}}][price]" type="text" placeholder="<?php esc_attr_e('Price','docdirect');?>">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<textarea class="form-control" name="prices[{{data}}][description]" placeholder="<?php esc_attr_e('Description','docdirect');?>"></textarea>
				</div>
			</div>
		  </div>
	    </td>
	  </tr>
	</tbody>
</script>
<script type="text/template" id="tmpl-append-prices">
	<# if( _.isArray(data) && ! _.isEmpty(data) ) { #>
		<table class="table-striped prices_wrap" id="table-striped">
		<thead class="cf">
		  <tr>
			<th><?php esc_html_e('Title','docdirect');?></th>
			<th><?php esc_html_e('Price','docdirect');?></th>
		  </tr>
		</thead>
		<# _.each( data , function( element, index, attr ) { #>
		<tbody class="prices_item">
		  <tr>
			<td data-title="Code">{{element.title}}
			  <div class="tg-table-hover prices-action"> 
				  <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
				  <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
			  </div>
			</td>
			<td data-title="Company">{{element.price}}</td>
		  </tr>
		  <tr>
		   <td class="prices-data edit-me-row" colspan="3">
			 <div class="prices-data-wrap">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control" value="{{element.title}}" name="prices[{{index}}][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<input class="form-control" value="{{element.price}}" name="prices[{{index}}][price]" type="text" placeholder="<?php esc_attr_e('Price','docdirect');?>">
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<textarea class="form-control" name="prices[{{index}}][description]" placeholder="<?php esc_attr_e('Description','docdirect');?>">{{element.description}}</textarea>
					</div>
				</div>
			  </div>
			</td>
		  </tr>
		</tbody>
		<# } ); #>
	<# } #>
</script>

<?php
get_footer();
?>
<!--************************************
        Theme Modal Box Start
*************************************-->
<div class="modal fade tg-modalmanageteam tg-categoryModal" tabindex="-1">
    <div class="modal-dialog tg-modaldialog" role="document">
        <div class="modal-content tg-modalcontent">
            <div class="tg-modalhead">
                <h2><?php esc_html_e('Invite New User','docdirect');?></h2>
            </div>
            <div class="tg-modalbody">
                <form class="tg-themeform">
                    <fieldset>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="<?php esc_html_e('Enter Email ID','docdirect');?>">
                        </div>
                        <textarea name="message" placeholder="<?php esc_html_e('Invitation Message','docdirect');?>"></textarea>
                    </fieldset>
                </form>
            </div>
            <div class="tg-modalfoot">
                <a href="javascript:;" class="tg-btn invite-users" type="submit"><?php esc_html_e('Invite Now','docdirect');?></a>
            </div>
        </div>
    </div>
</div>
