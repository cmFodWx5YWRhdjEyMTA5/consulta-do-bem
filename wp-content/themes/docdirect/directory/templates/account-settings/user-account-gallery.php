<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
	<div class="tg-editprofile tg-haslayout">
		<div class="col-md-12 col-sm-12 col-xs-12 tg-expectwidth">
			<div class="row">
				<div class="tg-otherphotos">
					<div class="tg-heading-border tg-small">
						<h3><a href="javascript:;"><?php esc_html_e('Other Photos','docdirect');?></a></h3>

					</div>
					<div class="gallery-button">
						<div id="plupload-container-gallery"><button type="button" id="attach-gallery" class="tg-btn tg-btn-lg"><?php esc_html_e('Choose Photos','docdirect');?></button></div>
					</div>
					<div id="tg-photoscroll" class="tg-photoscroll">
						<div class="form-group">
							<ul class="tg-otherimg doc-user-gallery" id="gallery-sortable-container">
								<?php 
								$user_gallery	 = get_user_meta( $user_identity, 'user_gallery', true);
								$counter	= 0;
								if( isset( $user_gallery ) && !empty( $user_gallery ) ) {
									foreach( $user_gallery as $key	=> $value ){
								?>
								<li class="gallery-item gallery-thumb-item">
									<figure> 
										<a href="javascript:;"><img width="100" height="100" src="<?php echo esc_attr( $value['url'] );?>" alt="<?php esc_attr_e('Gallery','docdirect');?>"></a>
										<div class="tg-img-hover"><a href="javascript:;" data-attachment="<?php echo esc_attr( $value['id'] );?>"><i class="fa fa-plus"></i><i class='fa fa-refresh fa-spin'></i></a></div>

									</figure>
									<input type="hidden" value="<?php echo esc_attr( $value['id'] );?>" name="user_gallery[<?php echo esc_attr( $value['id'] );?>][attachment_id]">
									<input type="hidden" value="<?php echo esc_attr( $value['url'] );?>" name="user_gallery[<?php echo esc_attr( $value['id'] );?>][url]">
								</li>
								<?php }}?>

							</ul>

						</div>
					</div>
					<div id="errors-log-gallery"></div>
				</div>
			</div>
		</div>
	</div>
<?php }?>