<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);
if( !empty( $db_directory_type ) ) {
	$experience_switch  	  = fw_get_db_post_option( $db_directory_type, 'experience', true );
}

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){
    if( isset( $experience_switch ) && $experience_switch === 'enable' ) {?>
    <!--Experience-->
    <div class="tg-bordertop tg-haslayout">
        <div class="tg-formsection tg-experience">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_html_e('Experience','docdirect');?></h3>
            </div>
            <div class="tg-education-detail tg-haslayout">
              <table class="table-striped experiences_wrap" id="table-striped">
                <thead class="cf">
                  <tr>
                    <th><?php esc_html_e('Experience Title','docdirect');?></th>
                    <th><?php esc_html_e('Company/Organization','docdirect');?></th>
                    <th class="numeric"><?php esc_html_e('Year','docdirect');?></th>
                  </tr>
                </thead>
                <?php 
                $experience_list	= get_the_author_meta('experience',$user_identity);
                $counter	= 0;
                if( !empty( $experience_list ) ) {
                    foreach( $experience_list as $key	=> $value ){
                    $flag	= rand(1,9999);
                    
                    if( !empty( $value['end_date'] ) ) {
                        $end_date	= date_i18n('M,Y',strtotime( $value['end_date']));
                    } else{
                        $end_date	= esc_html__('Current','docdirect');
                    }
                    ?>
                    <tbody class="experiences_item">
                      <tr>
                        <td data-title="Code"><?php echo esc_attr( $value['title'] );?>
                          <div class="tg-table-hover experience-action"> 
                              <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
                              <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                          </div>
                        </td>
                        <td data-title="Company"><?php echo esc_attr( $value['company'] );?></td>
                        <td data-title="Price" class="numeric"><?php echo esc_attr( date_i18n('M,Y',strtotime( $value['start_date'] ) ) );?>&nbsp;-&nbsp;<?php echo esc_attr( $end_date );?></td>
                      </tr>
                      <tr>
                       <td class="experience-data edit-me-row" colspan="3">
                         <div class="experience-data-wrap">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" value="<?php echo esc_attr( $value['title'] );?>" name="experience[<?php echo intval( $counter );?>][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" value="<?php echo esc_attr( $value['company'] );?>" name="experience[<?php echo intval( $counter );?>][company]" type="text" placeholder="<?php esc_attr_e('Company/Organization','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control exp_start_date_<?php echo esc_attr( $flag );?>" id="exp_start_date" value="<?php echo esc_attr( $value['start_date'] );?>" name="experience[<?php echo intval( $counter );?>][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control exp_end_date_<?php echo esc_attr( $flag );?>" id="exp_end_date" value="<?php echo esc_attr( $value['end_date'] );?>" name="experience[<?php echo intval( $counter );?>][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <textarea class="form-control" name="experience[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Experience Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                </div>
                            </div>
                            <script>
                               jQuery(document).ready(function(e) {
                                jQuery('.exp_start_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                   format:scripts_vars.calendar_format,
                                  onShow:function( ct ){
                                   this.setOptions({
                                    maxDate:jQuery('.exp_end_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.exp_end_date_<?php echo esc_js( $flag );?>').val()):false
                                   })
                                  },
                                  timepicker:false
                                 });
                                jQuery('.exp_end_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                   format:scripts_vars.calendar_format,
                                  onShow:function( ct ){
                                   this.setOptions({
                                    minDate:jQuery('.exp_start_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.exp_start_date_<?php echo esc_js( $flag );?>').val() ):false
                                   })
                                  },
                                  timepicker:false
                                 });
                               }); 
                            </script>
                           </div>
                       </td>
                      </tr>
                     </tbody>
                   <?php
                        $counter++;
                        }
                    }
                ?>
              </table>
            </div>
            <div class="col-sm-12">
                <div class="tg-addfield add-new-experiences">
                    <button type="button">
                        <i class="fa fa-plus"></i>
                        <span><?php esc_html_e('Add Experience','docdirect');?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php }
}