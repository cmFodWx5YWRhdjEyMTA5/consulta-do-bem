<?php
/**
 * User Schedules
 * return html
 */


global $profileuser;
$user_identity= $profileuser->ID;
$db_schedules	= array();
$db_schedules = get_user_meta( $user_identity, 'schedules', true);

$checked	= '';
if( isset( $db_schedules['all'] ) && $db_schedules['all'] === 'on' ) {
	$checked	= 'checked';
}

$schedules	= array(
	'mon'	=> esc_html__('Monday','docdirect'),
	'tue'	=> esc_html__('Tuesday','docdirect'),
	'wed'	=> esc_html__('Wednesday','docdirect'),
	'thu'	=> esc_html__('Thursday','docdirect'),
	'fri'	=> esc_html__('Friday','docdirect'),
	'sat'	=> esc_html__('Saturday','docdirect'),
	'sun'	=> esc_html__('Sunday','docdirect'),
);
?>
<div class="tg-docschedule tg-haslayout">
	<fieldset>
		<div class="parent-heading">
			<h2><?php esc_html_e('User Schedules','docdirect');?></h2>
		</div>
		<div class="form-docschedule" id="form-docschedule">
			<?php 
			if( isset( $schedules ) && !empty( $schedules ) ) {
				foreach( $schedules as $key => $value )	{
					
					$start_time	= isset( $db_schedules[$key.'_start'] ) ? $db_schedules[$key.'_start'] : '';
					$end_time	= isset( $db_schedules[$key.'_end'] ) ? $db_schedules[$key.'_end'] : '';
					
				?>
					<div class="form-group">
						<label><?php echo esc_attr( $value );?></label>
					</div>
					<div class="form-group">
						<input type="text" name="schedules[<?php echo esc_attr( $key );?>_start]" value="<?php echo esc_attr( $start_time );?>" class="form-control schedule-pickr" placeholder="<?php esc_html_e('start time','docdirect');?>">
						<i class="fa fa-clock-o"></i>
					</div>
					<div class="form-group">
						<input type="text" name="schedules[<?php echo esc_attr( $key );?>_end]" value="<?php echo esc_attr( $end_time );?>" class="form-control schedule-pickr" placeholder="<?php esc_html_e('end time','docdirect');?>">
						<i class="fa fa-clock-o"></i>
					</div>
			<?php }}?>
		</div>
	</fieldset>
</div>