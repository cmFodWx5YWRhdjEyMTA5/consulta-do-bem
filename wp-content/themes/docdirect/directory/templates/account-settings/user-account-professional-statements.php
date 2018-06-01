<?php
/**
 * User Profile Main
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$user_identity  = $current_user->ID;
$professional_statements	 = get_user_meta( $user_identity, 'professional_statements', true);

if( apply_filters('docdirect_do_check_user_type',$user_identity ) === true ){?>
    <div class="tg-bordertop tg-haslayout">
        <div class="tg-formsection">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_html_e('Professional Statements','docdirect');?></h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="email-params">
                        <p><strong><?php esc_html_e('It will be shown in user detail page below user short description.','docdirect');?></strong></p>
                    </div>
                    <div class="form-group">
                        <?php 
                            $professional_statements = !empty($professional_statements) ? $professional_statements : '';
                            $settings = array( 
                                'editor_class' => 'professional_statements', 
                                'teeny' => true, 
                                'media_buttons' => false, 
                                'textarea_rows' => 10,
                                'quicktags' => true,
                                'editor_height' => 300,
                            );
                            
                            wp_editor( $professional_statements, 'professional_statements', $settings );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }