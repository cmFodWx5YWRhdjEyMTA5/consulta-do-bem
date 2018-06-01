<?php
if (!class_exists('Docdirect_MailChimp')) {

    class Docdirect_MailChimp {

        function __construct() {
            add_action('wp_ajax_nopriv_subscribe_mailchimp', array(&$this, 'docdirect_subscribe_mailchimp'));
            add_action('wp_ajax_subscribe_mailchimp', array(&$this, 'docdirect_subscribe_mailchimp'));
        }

        /**
         * @get Mail chimp list
         *
         */
        public function docdirect_mailchimp_list($apikey) {
            $MailChimp = new Docdirect_OATH_MailChimp($apikey);
            $mailchimp_list = $MailChimp->docdirect_call('lists/list');
            return $mailchimp_list;
        }

        /**
         * @get Mail chimp list
         *
         */
        public function docdirect_subscribe_mailchimp() {
            global $counter;
            $mailchimp_key = '';
            $mailchimp_list = '';
            $json = array();

            if (function_exists('fw_get_db_settings_option')) :
                $mailchimp_key = fw_get_db_settings_option('mailchimp_key');
                $mailchimp_list = fw_get_db_settings_option('mailchimp_list');
            endif;
			
            if ( !empty($_POST['email']) and $mailchimp_key != '') {
                if ($mailchimp_key <> '') {
                    $MailChimp = new Docdirect_OATH_MailChimp($mailchimp_key);
                }

                $email = esc_attr( $_POST['email'] );

                if (isset($_POST['name']) && !empty($_POST['name'])) {
                    $name = esc_attr( $_POST['name'] );
                } else {
                    $name = '';
                }

                if (isset($_POST['lname']) && !empty($_POST['lname'])) {
                    $lname = esc_attr( $_POST['lname'] );
                } else {
                    $lname = '';
                }

                if (empty($mailchimp_list)) {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('No list selected yet! please contact administrator', 'docdirect_core');
                    die;
                }
                //https://apidocs.mailchimp.com/api/1.3/listsubscribe.func.php
                $result = $MailChimp->docdirect_call('lists/subscribe', array(
                    'id' => $mailchimp_list,
                    'email' => array('email' => $email),
                    'merge_vars' => array('NAME' => $name, 'LNAME' => $lname),
                    'double_optin' => false,
                    'update_existing' => false,
                    'replace_interests' => false,
                    'send_welcome' => true,
                ));
                if ($result <> '') {
                    if (isset($result['status']) and $result['status'] == 'error') {
                        $json['type'] = 'error';
                        $json['message'] = $result['error'];
                    } else {
                        $json['type'] = 'success';
                        $json['message'] = esc_html__('Subscribe Successfully', 'docdirect_core');
                    }
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur, Please try again later.', 'docdirect_core');
            }
            echo json_encode($json);
            die();
        }

    }

    new Docdirect_MailChimp();
}