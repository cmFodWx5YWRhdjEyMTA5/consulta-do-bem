<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);
if( !empty( $db_directory_type ) ) {
	$education_switch  	  = fw_get_db_post_option( $db_directory_type, 'education', true );
}

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){
    if( isset( $education_switch ) && $education_switch === 'enable' ) {?>
    <!--Education-->
    <div class="tg-bordertop tg-haslayout">
        <div class="tg-formsection tg-education">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_html_e('Education','docdirect');?></h3>
            </div>
            <div class="tg-education-detail tg-haslayout">
              <table class="table-striped educations_wrap" id="table-striped">
                <thead class="cf">
                  <tr>
                    <th><?php esc_html_e('Degree / Education Title','docdirect');?></th>
                    <th><?php esc_html_e('Institute','docdirect');?></th>
                    <th class="numeric"><?php esc_html_e('Year','docdirect');?></th>
                  </tr>
                </thead>
                <?php 
                $education_list	= get_the_author_meta('education',$user_identity);
                $counter	= 0;
                if( !empty( $education_list ) ) {
                    foreach( $education_list as $key	=> $value ){
                        if( !empty( $value['end_date'] ) ) {
                            $end_date	= date_i18n('M,Y',strtotime( $value['end_date']));
                        } else{
                            $end_date	= esc_html__('Current','docdirect');
                        }
                    
                    $flag	= rand(1,9999);
                    ?>
                    <tbody class="educations_item">
                      <tr>
                        <td data-title="Code"><?php echo esc_attr( $value['title'] );?>
                          <div class="tg-table-hover education-action"> 
                              <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
                              <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                          </div>
                        </td>
                        <td data-title="Company"><?php echo esc_attr( $value['institute'] );?></td>
                        <td data-title="Price" class="numeric"><?php echo esc_attr( date_i18n('M,Y',strtotime( $value['start_date'] ) ) );?>&nbsp;-&nbsp;<?php echo esc_attr( $end_date );?></td>
                      </tr>
                      <tr>
                       <td class="education-data edit-me-row" colspan="3">
                         <div class="education-data-wrap">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" value="<?php echo esc_attr( $value['title'] );?>" name="education[<?php echo intval( $counter );?>][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" value="<?php echo esc_attr( $value['institute'] );?>" name="education[<?php echo intval( $counter );?>][institute]" type="text" placeholder="<?php esc_attr_e('Institute','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control edu_start_date_<?php echo esc_attr( $flag );?>" id="edu_start_date" value="<?php echo esc_attr( $value['start_date'] );?>" name="education[<?php echo intval( $counter );?>][start_date]" type="text" placeholder="<?php esc_attr_e('Start Date','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control edu_end_date_<?php echo esc_attr( $flag );?>" id="edu_end_date" value="<?php echo esc_attr( $value['end_date'] );?>" name="education[<?php echo intval( $counter );?>][end_date]" type="text" placeholder="<?php esc_attr_e('End Date','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <textarea class="form-control" name="education[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Education Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                </div>
                            </div>
                            <script>
                               jQuery(document).ready(function(e) {
                                jQuery('.edu_start_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                   format:scripts_vars.calendar_format,
                                  onShow:function( ct ){
                                   this.setOptions({
                                    maxDate:jQuery('.edu_end_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.edu_end_date_<?php echo esc_js( $flag );?>').val()):false
                                   })
                                  },
                                  timepicker:false
                                 });
                                jQuery('.edu_end_date_<?php echo esc_js( $flag );?>').datetimepicker({
                                   format:scripts_vars.calendar_format,
                                  onShow:function( ct ){
                                   this.setOptions({
                                    minDate:jQuery('.edu_start_date_<?php echo esc_js( $flag );?>').val()? _change_date_format( jQuery('.edu_start_date_<?php echo esc_js( $flag );?>').val()):false
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
                <div class="tg-addfield add-new-educations">
                    <button type="button">
                        <i class="fa fa-plus"></i>
                        <span><?php esc_html_e('Add Education','docdirect');?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php }
}