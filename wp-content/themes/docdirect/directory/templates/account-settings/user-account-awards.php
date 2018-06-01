<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);

if( !empty( $db_directory_type ) ) {
	$awards_switch    = fw_get_db_post_option($db_directory_type, 'awards', true);
}

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
    <!--Awards-->
    <?php if( isset( $awards_switch ) && $awards_switch === 'enable' ) {?>
    <div class="tg-bordertop tg-haslayout">
      <div class="tg-formsection tg-honor-awards">
        <div class="tg-heading-border tg-small">
          <h3><?php esc_html_e('Honors & Awards','docdirect');?></h3>
        </div>
        <div class="tg-education-detail tg-haslayout">
          <table class="table-striped awards_wrap">
            <thead class="cf">
              <tr>
                <th><?php esc_html_e('Title','docdirect');?></th>
                <th><?php esc_html_e('Year','docdirect');?></th>
              </tr>
            </thead>
            <?php 
            $awards_list	= get_the_author_meta('awards',$user_identity);
            $counter	= 0;
            if( isset( $awards_list ) && !empty( $awards_list ) ) {
                foreach( $awards_list as $key	=> $value ){
                ?>
                <tbody class="awards_item">
                  <tr>
                    <td data-title="Code"><?php echo esc_attr( $value['name'] );?>
                      <div class="tg-table-hover award-action"> 
                        <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a>
                        <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                       </div>
                    </td>
                    <td data-title="Company"><?php echo esc_attr( date_i18n('F m, Y',strtotime( $value['date'] ) ) );?></td>
                  </tr>
                  <tr>
                    <td class="award-data edit-me-row"colspan="2">
                        <div class="tg-education-form tg-haslayout">
                            <div class="award-data">
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input class="form-control" value="<?php echo esc_attr( $value['name'] );?>" name="awards[<?php echo intval( $counter );?>][name]" type="text" placeholder="<?php esc_attr_e('Award Name','docdirect');?>">
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input class="form-control award_datepicker" id="award_datepicker" value="<?php echo esc_attr( $value['date'] );?>" name="awards[<?php echo intval( $counter );?>][date]" type="text" placeholder="<?php esc_attr_e('Award Date','docdirect');?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="awards[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Award Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
                                        </div>
                                    </div>
                                </div>
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
           <div class="tg-addfield add-new-awards">
              <button type="button">
                  <i class="fa fa-plus"></i>
                  <span><?php esc_html_e('Add Awards','docdirect');?></span>
              </button>
           </div>
        </div>
      </div>
    </div>
	<?php }
}