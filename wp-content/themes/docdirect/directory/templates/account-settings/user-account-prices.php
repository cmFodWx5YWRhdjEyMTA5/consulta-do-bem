<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$db_directory_type	 = get_user_meta( $user_identity, 'directory_type', true);
if( !empty( $db_directory_type ) ) {
	$price_list_switch  	  = fw_get_db_post_option( $db_directory_type, 'price_list', true );
}

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){
	if( isset( $price_list_switch ) && $price_list_switch === 'enable' ) {?>
    <div class="tg-bordertop tg-haslayout">
        <div class="tg-formsection tg-prices">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_html_e('Prices/Services List','docdirect');?></h3>
            </div>
            <div class="tg-education-detail tg-haslayout">
              <table class="table-striped prices_wrap" id="table-striped">
                <thead class="cf">
                  <tr>
                    <th><?php esc_html_e('Title','docdirect');?></th>
                    <th><?php esc_html_e('Price','docdirect');?></th>
                  </tr>
                </thead>
                <?php 
                $prices_list	= get_the_author_meta('prices_list',$user_identity);
                $counter	= 0;
                if( !empty( $prices_list ) ) {
                    foreach( $prices_list as $key	=> $value ){
                    ?>
                    <tbody class="prices_item">
                      <tr>
                        <td data-title="Title"><?php echo esc_attr( $value['title'] );?>
                          <div class="tg-table-hover prices-action"> 
                              <a href="javascript:;" class="delete-me"><i class="tg-delete fa fa-close"></i></a> 
                              <a href="javascript:;" class="edit-me"><i class="tg-edit fa fa-pencil"></i></a> 
                          </div>
                        </td>
                        <td data-title="Company"><?php echo esc_attr( $value['price'] );?></td>
                      </tr>
                      <tr>
                       <td class="prices-data edit-me-row" colspan="3">
                         <div class="experience-data-wrap">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" value="<?php echo esc_attr( $value['title'] );?>" name="prices[<?php echo intval( $counter );?>][title]" type="text" placeholder="<?php esc_attr_e('Title','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" value="<?php echo esc_attr( $value['price'] );?>" name="prices[<?php echo intval( $counter );?>][price]" type="text" placeholder="<?php esc_attr_e('Price','docdirect');?>">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <textarea class="form-control" name="prices[<?php echo intval( $counter );?>][description]" placeholder="<?php esc_attr_e('Description','docdirect');?>"><?php echo esc_attr( $value['description'] );?></textarea>
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
                <div class="tg-addfield add-new-prices">
                    <button type="button">
                        <i class="fa fa-plus"></i>
                        <span><?php esc_html_e('Add Prices/Services','docdirect');?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php }
}