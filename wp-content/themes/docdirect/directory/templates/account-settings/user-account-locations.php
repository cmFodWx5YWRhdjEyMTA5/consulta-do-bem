<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
if (function_exists('fw_get_db_settings_option')) {
	$dir_longitude = fw_get_db_settings_option('dir_longitude');
	$dir_latitude  = fw_get_db_settings_option('dir_latitude');
	$dir_longitude	= !empty( $dir_longitude ) ? $dir_longitude : '-0.1262362';
	$dir_latitude	= !empty( $dir_latitude ) ? $dir_latitude : '51.5001524';
} else{
	$dir_longitude = '-0.1262362';
	$dir_latitude  = '51.5001524';
}

$db_latitude    = get_user_meta( $user_identity, 'latitude', true);
$db_longitude   = get_user_meta( $user_identity, 'longitude', true);
$db_location	= get_user_meta( $user_identity, 'location', true); 
$db_address		= get_user_meta( $user_identity, 'address', true); 


$db_latitude	= !empty( $db_latitude ) ? $db_latitude : $dir_latitude;
$db_longitude	= !empty( $db_longitude ) ? $db_longitude : $dir_longitude;

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
    <div class="tg-bordertop tg-haslayout">
        <div class="tg-formsection">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_html_e('Locations','docdirect');?></h3>
            </div>
            <div class="row map-container">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <span class="doc-select">
                            <select name="basics[location]" class="locations-select">
                                <option value=""><?php esc_attr_e('Select Location','docdirect');?></option>
                                <?php docdirect_get_term_options($db_location,'locations');?>
                            </select>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group locate-me-wrap">
                        <input type="text" value="<?php echo esc_attr( $db_address );?>" name="basics[address]" class="form-control" id="location-address" placeholder="<?php esc_html_e('Your location','docdirect');?>" />
                        <a href="javascript:;" class="geolocate"><img src="<?php echo get_template_directory_uri();?>/images/geoicon.svg" width="16" height="16" class="geo-locate-me" alt="<?php esc_html_e('Locate me!','docdirect');?>"></a>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<p><strong><?php esc_attr_e('Latitude and Longitudes are compulsory to show that user on map and also for search on the basis of location','docdirect');?></strong></p>
						</div>
					</div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <input type="text" placeholder="<?php esc_attr_e('Latitude','docdirect');?>" value="<?php echo esc_attr( $db_latitude );?>" name="basics[latitude]" class="form-control" id="location-latitude" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <input type="text" placeholder="<?php esc_attr_e('Longitude','docdirect');?>" value="<?php echo esc_attr( $db_longitude );?>" name="basics[longitude]" class="form-control" id="location-longitude" />
                    </div>
                </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div id="location-pickr-map"></div>
                    </div>
                </div>
                
                <script>
                    jQuery(document).ready(function(e) {
                        //init
                        jQuery.docdirect_init_map(<?php echo esc_js( $db_latitude );?>,<?php echo esc_js( $db_longitude );?>);
                    });
                </script>
            </div>
        </div>
    </div>
<?php }?>