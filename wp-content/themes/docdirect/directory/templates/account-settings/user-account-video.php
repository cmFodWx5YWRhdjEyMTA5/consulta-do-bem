<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$video_url	    = get_user_meta( $user_identity, 'video_url', true);

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
<div class="tg-bordertop tg-haslayout">
	<div class="tg-formsection tg-videoprofile">
		<div class="tg-heading-border tg-small">
			<h3><?php esc_html_e('video link','docdirect');?></h3>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<input class="form-control" name="video_url" value="<?php echo esc_url( $video_url );?>" type="url" placeholder="<?php esc_attr_e('Enter Url','docdirect');?>">
				</div>
			</div>
		</div>
	</div>
</div>
<?php }?>