<?php
/**
 * User Schedules
 * return html
 */


global $current_user, $wp_roles,$userdata,$post;
$user_identity= $current_user->ID;
$db_schedules	= array();
$db_schedules = get_user_meta( $user_identity, 'schedules', true);
$time_format = get_user_meta( $user_identity, 'time_format', true);

$checked	= '';
if( isset( $db_schedules['all'] ) && $db_schedules['all'] === 'on' ) {
	$checked	= 'checked';
}

$schedules	= docdirect_get_week_array();
$time_format	= get_option('time_format');
$time_format	= !empty( $time_format ) ? $time_format : 'g:i A';
?>
<div class="tg-docschedule tg-haslayout">
	<div class="tg-heading-border tg-small">
		<h3><?php esc_html_e('update Schedule','docdirect');?></h3>
	</div>
    <p><strong><?php esc_html_e('Note: Leave fields empty to show  day closed.','docdirect');?></strong></p>
    
	<form class="form-docschedule" id="form-docschedule">
		<fieldset class="row">
        	
			<?php 
			if( isset( $schedules ) && !empty( $schedules ) ) {
				foreach( $schedules as $key => $value )	{
					
					$start_time	= isset( $db_schedules[$key.'_start'] ) ? $db_schedules[$key.'_start'] : '';
					$end_time	= isset( $db_schedules[$key.'_end'] ) ? $db_schedules[$key.'_end'] : '';
					
				?>
				<div class="col-xs-12">
					<div class="row">
						<div class="col-md-2 col-sm-12 col-xs-12">
							<div class="form-group">
								<label><?php echo esc_attr( $value );?></label>
							</div>
						</div>
						<div class="col-md-5 col-sm-6 col-xs-12">
							<div class="form-group">
								<div class="tg-inputicon">
									<input type="text" name="schedules[<?php echo esc_attr( $key );?>_start]" value="<?php echo esc_attr( $start_time );?>" class="form-control schedule-pickr" placeholder="<?php esc_html_e('start time','docdirect');?>">
									<i class="fa fa-clock-o"></i>
								</div>
							</div>
						</div>
						<div class="col-md-5 col-sm-6 col-xs-12">
							<div class="form-group">
								<div class="tg-inputicon">
									<input type="text" name="schedules[<?php echo esc_attr( $key );?>_end]" value="<?php echo esc_attr( $end_time );?>" class="form-control schedule-pickr" placeholder="<?php esc_html_e('end time','docdirect');?>">
									<i class="fa fa-clock-o"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php }}?>
			<div class="col-sm-offset-2 col-sm-10 col-xs-12">
				<div class="form-group">
					<select name="time_format" class="form-control">
						<option value="12hour" <?php echo isset( $time_format ) && $time_format === '12hour' ? 'selected' : '';?>><?php esc_html_e("Show Time in 12-hour clock",'docdirect');?></option>
						<option value="24hour" <?php echo isset( $time_format ) && $time_format === '24hour' ? 'selected' : '';?>><?php esc_html_e("Show Time in 24-hour clock",'docdirect');?></option>
					</select>
				</div>
				<button type="submit" class="tg-btn pull-left update-schedules"><?php esc_html_e('update','docdirect');?></button>
			</div>
		</fieldset>
	</form>
</div>
<script>
	jQuery(document).ready(function(e) {
		//Time Picker
		jQuery('.schedule-pickr').datetimepicker({
		  datepicker:false,
		  format:'H:i'
		});
	});
</script>