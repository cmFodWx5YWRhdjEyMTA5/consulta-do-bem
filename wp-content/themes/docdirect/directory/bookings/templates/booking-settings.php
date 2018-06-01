<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}
$user_disable_stripe	= '';
$user_disable_paypal	= '';

if(function_exists('fw_get_db_settings_option')) {
	$user_disable_stripe = fw_get_db_settings_option('user_disable_stripe', $default_value = null);
	$user_disable_paypal = fw_get_db_settings_option('user_disable_paypal', $default_value = null);
	$booking_confirmed_default = fw_get_db_settings_option('confirm_booking', $default_value = null);
	$booking_approved_default = fw_get_db_settings_option('approve_booking', $default_value = null);
	$booking_cancelled_default = fw_get_db_settings_option('cancel_booking', $default_value = null);
}
			
$email_logo 		= apply_filters(
							'docdirect_single_image_filter',
							 docdirect_get_single_image(array('width'=>150,'height'=>150), $user_identity) ,
							 array('width'=>150,'height'=>150) //size width,height=
						);


//Default template for booking confirmation
if( empty( $booking_confirmed_default ) ){
	$booking_confirmed_default	= 'Hey %customer_name%!<br/>

						This is confirmation that you have booked "%service%"<br/> with %provider%
						We will let your know regarding your booking soon.<br/><br/>
						
						Thank you for choosing our company.<br/><br/>
						
						Sincerely,<br/>
						%logo%';
}


//Default template for booking cancellation
if( empty( $booking_cancelled_default ) ){
	$booking_cancelled_default	= 'Hi %customer_name%!<br/>

							 adasdsadThis is confirmation that your booking regarding "%service%" with %provider% has cancelled.<br/>

							 We are very sorry to process your booking right now.<br/><br/>

							 Sincerely,<br/>
							 %logo%';
}
//Default template for booking Approved
if( empty( $booking_approved_default ) ){
	$booking_approved_default	= 'Hey %customer_name%!<br/>

							This is confirmation that your booking regarding "%service%" with %provider% has approved.<br/>

							We are waiting you at "%address%" on %appointment_date% at %appointment_time%.<br/><br/><br/>

							Sincerely,<br/>
							%logo%';
}

$confirmation_title_default = esc_html__('Your Appointment Confirmation','docdirect');
$approved_title_default	 = esc_html__('Your Appointment Approved','docdirect');
$cancelled_title_default	= esc_html__('Your Appointment Cancelled','docdirect');

$confirmation_title	    = get_user_meta( $user_identity, 'confirmation_title', true);
$approved_title	    	= get_user_meta( $user_identity, 'approved_title', true);
$cancelled_title	    = get_user_meta( $user_identity, 'cancelled_title', true);

$booking_cancelled	    = get_user_meta( $user_identity, 'booking_cancelled', true);
$booking_confirmed	    = get_user_meta( $user_identity, 'booking_confirmed', true);
$booking_approved	    = get_user_meta( $user_identity, 'booking_approved', true);

$thank_you	     		= get_user_meta( $user_identity, 'thank_you', true);
$schedule_message	    = get_user_meta( $user_identity, 'schedule_message', true);
$email_media 			= get_user_meta($user_identity , 'email_media' , true);

$currency	    		= get_user_meta( $user_identity, 'currency', true);
$currency_symbol	    = get_user_meta( $user_identity, 'currency_symbol', true);

$confirmation_title	= !empty( $confirmation_title ) ? $confirmation_title : $confirmation_title_default;
$approved_title		= !empty( $approved_title ) ? $approved_title : $approved_title_default;
$cancelled_title	   = !empty( $cancelled_title ) ? $cancelled_title : $cancelled_title_default;

$booking_cancelled	= !empty( $booking_cancelled ) ? $booking_cancelled : $booking_cancelled_default;
$booking_confirmed	= !empty( $booking_confirmed ) ? $booking_confirmed : $booking_confirmed_default;
$booking_approved	 = !empty( $booking_approved ) ? $booking_approved : $booking_approved_default;

$currencies	= docdirect_prepare_currency_symbols();
$currencies_array	= array();
foreach($currencies as $key => $value ){
	$currencies_array[$key] = $value['name'].'-'.$value['code'];
}


//Payments
$paypal_enable	= get_user_meta( $user_identity, 'paypal_enable', true);
$paypal_email_id	= get_user_meta( $user_identity, 'paypal_email_id', true);
$stripe_enable	= get_user_meta( $user_identity, 'stripe_enable', true);
$stripe_secret	= get_user_meta( $user_identity, 'stripe_secret', true);
$stripe_publishable	= get_user_meta( $user_identity, 'stripe_publishable', true);
$stripe_site	= get_user_meta( $user_identity, 'stripe_site', true);
$paypal_enable	= get_user_meta( $user_identity, 'paypal_enable', true);
$stripe_decimal	= get_user_meta( $user_identity, 'stripe_decimal', true);

?>
<div class="doc-booking-emails dr-bookings">
    <div class="tg-haslayout">
        <form action="#" name="email-settings" class="email-settings">
        <div class="tg-formsection">
            <div class="email-settings-tabs">
                <div class="tg-heading-border tg-small">
                    <h3><?php esc_html_e('General Settings','docdirect');?></h3>
                </div>
                <div class="appointment-cancelled  booking-email-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_html_e('Schedule Message','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><?php esc_html_e('It will be shown when customer will be on booking form available schedules.','docdirect');?></p>
                        </div>
                        <div class="email-contents">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <?php 
                                            $schedule_message = !empty($schedule_message) ? $schedule_message : '';
                                            $settings = array( 
                                                'editor_class' => 'schedule_message', 
                                                'teeny' => true, 
                                                'media_buttons' => false, 
                                                'textarea_rows' => 10,
                                                'quicktags' => true,
                                                'editor_height' => 300
                                                
                                            );
                                            
                                            wp_editor( $schedule_message, 'schedule_message', $settings );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="appointment-cancelled  booking-email-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_html_e('Thank You Message','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><?php esc_html_e('It will be shown on booking completion.','docdirect');?></p>
                        </div>
                        <div class="email-contents">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <?php 
                                            $thank_you = !empty($thank_you) ? $thank_you : '';
                                            $settings = array( 
                                                'editor_class' => 'thank_you', 
                                                'teeny' => true, 
                                                'media_buttons' => false, 
                                                'textarea_rows' => 10,
                                                'quicktags' => true,
                                                'editor_height' => 300
                                                
                                            );
                                            
                                            wp_editor( $thank_you, 'thank_you', $settings );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tg-formsection">
            <div class="email-settings-tabs">
                <div class="tg-heading-border tg-small">
                    <h3><?php esc_html_e('Email Settings','docdirect');?></h3>
                </div>
             	<div class="email-logo tg-editimg booking-email-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_attr_e('Upload your email logo','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <figure class="tg-docimg"> 
                            <span class="user-email"><img width="150" src="<?php echo esc_url( $email_logo );?>" alt="<?php esc_html_e('Avatar','docdirect');?>"  /></span>
                            <?php if( isset( $email_media ) && !empty( $email_media ) ) {?>
                                <a href="javascript:;" class="tg-deleteimg del-email"><i class="fa fa-plus"></i></a>
                            <?php }?>
                            <a href="javascript:;" id="upload-email" class="tg-uploadimg upload-email"><i class="fa fa-upload"></i></a> 
                            <div id="plupload-container"></div>
                        </figure>
                        <div class="tg-instructions">
                            <p><strong><?php esc_html_e('Note','docdirect');?>:</strong>&nbsp;-- <?php esc_html_e('Please upload your brand logo for email, otherwise logo will be skipped.','docdirect');?></p>
                        </div>
                    </div>
                </div>
             	<div class="appointment-email booking-email-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_attr_e('Booking Confirmation','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><strong>%customer_name%</strong>&nbsp;-- <?php esc_html_e('To display customer name','docdirect');?></p>
                            <p><strong>%service%</strong>&nbsp;-- <?php esc_html_e('To display appointment service','docdirect');?></p>
							<p><strong>%provider%</strong>&nbsp;-- <?php esc_html_e('To display provider name','docdirect');?></p>
                            <p><strong>%logo%</strong>&nbsp;-- <?php esc_html_e('Logo','docdirect');?></p>
                        </div>
                        <div class="email-contents">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                    	<input type="text" name="confirmation_title" value="<?php echo esc_attr($confirmation_title);?>" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <?php 
                                            $booking_confirmed = !empty($booking_confirmed) ? $booking_confirmed : '';
                                            $settings = array( 
                                                'editor_class' => 'booking_confirmed', 
                                                'teeny' => true, 
                                                'media_buttons' => false, 
                                                'textarea_rows' => 10,
                                                'quicktags' => false,
                                                'editor_height' => 300
                                                
                                            );
                                            wp_editor( $booking_confirmed, 'booking_confirmed', $settings );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             	<div class="appointment-cancelled booking-email-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_attr_e('Booking Cancelled','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><strong>%customer_name%</strong>&nbsp;-- <?php esc_html_e('To display customer name','docdirect');?></p>
                            <p><strong>%service%</strong>&nbsp;-- <?php esc_html_e('To display appointment service','docdirect');?></p>
							<p><strong>%provider%</strong>&nbsp;-- <?php esc_html_e('To display provider name','docdirect');?></p>
                            <p><strong>%logo%</strong>&nbsp;-- <?php esc_html_e('Logo','docdirect');?></p>
                        </div>
                        <div class="email-contents">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                    	<input type="text" name="cancelled_title" value="<?php echo esc_attr($cancelled_title);?>" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <?php 
                                            $booking_cancelled = !empty($booking_cancelled) ? $booking_cancelled : '';
                                            $settings = array( 
                                                'editor_class' => 'booking_cancelled', 
                                                'teeny' => true, 
                                                'media_buttons' => false, 
                                                'textarea_rows' => 10,
                                                'quicktags' => false,
                                                'editor_height' => 300
                                                
                                            );
                                            
                                            wp_editor( $booking_cancelled, 'booking_cancelled', $settings );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             	<div class="appointment-cancelled  booking-email-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_attr_e('Booking Approved','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><strong>%customer_name%</strong>&nbsp;-- <?php esc_html_e('To display customer name','docdirect');?></p>
                            <p><strong>%service%</strong>&nbsp;-- <?php esc_html_e('To display appointment service','docdirect');?></p>
                            <p><strong>%provider%</strong>&nbsp;-- <?php esc_html_e('To display provider name','docdirect');?></p>
                            <p><strong>%address%</strong>&nbsp;-- <?php esc_html_e('Your company/individual address.','docdirect');?></p>
                            <p><strong>%appointment_date%</strong>&nbsp;-- <?php esc_html_e('Appointment Date','docdirect');?></p>
                            <p><strong>%appointment_time%</strong>&nbsp;-- <?php esc_html_e('Appointment Time','docdirect');?></p>
                            <p><strong>%logo%</strong>&nbsp;-- <?php esc_html_e('Logo','docdirect');?></p>
                        </div>
                        <div class="email-contents">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                    	<input type="text" name="approved_title" value="<?php echo esc_attr($approved_title);?>" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <?php 
                                            $booking_approved = !empty($booking_approved) ? $booking_approved : '';
                                            $settings = array( 
                                                'editor_class' => 'booking_approved', 
                                                'teeny' => true, 
                                                'media_buttons' => false, 
                                                'textarea_rows' => 10,
                                                'quicktags' => true,
                                                'editor_height' => 300
                                                
                                            );
                                            
                                            wp_editor( $booking_approved, 'booking_approved', $settings );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
       </div>
       <?php 
	   if ( ( isset( $user_disable_stripe ) && $user_disable_stripe === 'on' ) 
	   		|| 
			( isset( $user_disable_paypal ) && $user_disable_paypal === 'on'  )
		){?>
       <div class="tg-formsection">
      	 <div class="email-settings-tabs">
            <div class="tg-heading-border tg-small">
                <h3><?php esc_html_e('Currency Settings','docdirect');?></h3>
            </div>
            <div class="booking-email-wrap booking-currency-wrap">
                <div class="tg-small doc-tab-link">
                    <h3><?php esc_html_e('Set Currency','docdirect');?></h3>
                </div>
                <div class="tab-data">
                    <div class="email-params">
                        <p><?php esc_html_e('It will be used in booking payment methods.','docdirect');?></p>
                    </div>
                    <div class="email-contents">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <select name="currency" class="chosen-select currency_symbol">
                                        <option value=""><?php esc_attr_e('Select Currency','docdirect');?></option>
                                        <?php 
                                        if( !empty( $currencies_array ) ){
                                            
                                            foreach( $currencies_array as $key => $value ){
                                                $selected	= '';
                                                if( isset( $currency ) && $currency == $key ){
                                                    $selected	= 'selected';
                                                }
                                                ?>
                                            <option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $value );?></option>
                                        <?php }}?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="currency_symbol" name="currency_symbol" value="<?php echo esc_attr($currency_symbol);?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       </div>
       <div class="tg-formsection">
        <div class="email-settings-tabs">
            <?php if ( isset( $user_disable_paypal ) && $user_disable_paypal === 'on' ) {?>
                <div class="tg-heading-border tg-small">
                    <h3><?php esc_html_e('Payment Settings','docdirect');?></h3>
                </div>
                <div class="booking-email-wrap booking-currency-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_html_e('PayPal Settings','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><?php esc_html_e('It will be used in booking payment methods.','docdirect');?></p>
                        </div>
                        <div class="email-contents tg-form-privacy">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">  
                                        <div class="tg-paypal-gateway"> 
                                          <div class="tg-iosstylcheckbox">
                                            <input type="hidden" name="paypal_enable">
                                            <input type="checkbox" <?php echo isset( $paypal_enable ) && $paypal_enable === 'on' ? 'checked':'';?>  name="paypal_enable" id="tg-paypal_enable">
                                            <label for="tg-paypal_enable" class="checkbox-label" data-private="<?php esc_attr_e('Disable','docdirect');?>" data-public="<?php esc_attr_e('Enable','docdirect');?>"></label>
                                          </div>
                                          <span class="tg-privacy-name"><?php esc_html_e('PayPal','docdirect');?></span>
                                          <p><?php esc_attr_e('Please enable PayPal gateway for booking payments.','docdirect');?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <span class="tg-privacy-name"><?php esc_html_e('Paypal Business Email
    ','docdirect');?></span>
                                        <input type="text" placeholder="<?php esc_html_e('Paypal Business Email
    ','docdirect');?>" class="paypal_email_id" name="paypal_email_id" value="<?php echo esc_attr($paypal_email_id);?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
            <?php if ( isset( $user_disable_stripe ) && $user_disable_stripe === 'on' ) {?>
                <div class="booking-email-wrap booking-currency-wrap">
                    <div class="tg-small doc-tab-link">
                        <h3><?php esc_html_e('Stripe( Credit Cards ) Settings','docdirect');?></h3>
                    </div>
                    <div class="tab-data">
                        <div class="email-params">
                            <p><?php esc_html_e('It will be used in booking payment methods.','docdirect');?></p>
                        </div>
                        <div class="email-contents tg-form-privacy">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">  
                                        <div class="tg-paypal-gateway"> 
                                          <div class="tg-iosstylcheckbox">
                                            <input type="hidden" name="stripe_enable">
                                            <input type="checkbox" <?php echo isset( $stripe_enable ) && $stripe_enable === 'on' ? 'checked':'';?>  name="stripe_enable" id="tg-stripe_enable">
                                            <label for="tg-stripe_enable" class="checkbox-label" data-private="<?php esc_attr_e('Disable','docdirect');?>" data-public="<?php esc_attr_e('Enable','docdirect');?>"></label>
                                          </div>
                                          <span class="tg-privacy-name"><?php esc_html_e('Stripe','docdirect');?></span>
                                          <p><?php esc_attr_e('Please enable Stripe gateway for booking payments.','docdirect');?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <span class="tg-privacy-name"><?php esc_html_e('Secret Key','docdirect');?></span>
                                        <input type="text" placeholder="<?php esc_html_e('Secret Key','docdirect');?>" class="stripe_secret" name="stripe_secret" value="<?php echo esc_attr($stripe_secret);?>" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <span class="tg-privacy-name"><?php esc_html_e('Publishable Key','docdirect');?></span>
                                        <input type="text" placeholder="<?php esc_html_e('Publishable Key','docdirect');?>" class="stripe_publishable" name="stripe_publishable" value="<?php echo esc_attr($stripe_publishable);?>" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        
                                        <span class="tg-privacy-name"><?php esc_html_e('Site Name','docdirect');?></span>
                                        <input type="text" placeholder="<?php esc_html_e('Site Name','docdirect');?>" class="stripe_site" name="stripe_site" value="<?php echo esc_attr($stripe_site);?>" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">  
                                        <div class="tg-paypal-gateway"> 
                                          <span class="tg-privacy-name"><?php esc_html_e('Decimals','docdirect');?></span>
                                          <select name="stripe_decimal" class="stripe_decimal">
                                            <option value="2" <?php echo isset($stripe_decimal) && $stripe_decimal == 2 ? 'selected' : '';?>>2</option>
                                            <option value="0" <?php echo isset($stripe_decimal) && $stripe_decimal == 0 ? 'selected' : '';?>>0</option>
                                          </select>
                                          <p>
                                            <?php echo wp_kses( __( 'Please check this page: <a href="https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support" target="_blank"> DECIMAL INFO </a> <br> If your currency listed in this page please use decimal number 0', 'docdirect' ),array(
                                                'a' => array(
                                                    'href' => array(),
                                                    'title' => array()
                                                ),
                                                'br' => array(),
                                                'em' => array(),
                                                'strong' => array(),
                                            ));?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
       </div>
       <?php }?>
       <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <button type="submit" class="tg-btn update-email-settings"><?php esc_html_e('Update','docdirect');?></button>
        </div>
       </div>
    </form>
</div>
</div>