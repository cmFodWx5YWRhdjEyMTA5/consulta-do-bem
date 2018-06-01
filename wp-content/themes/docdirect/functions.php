<?php
/**
 * @theme Functionality Files
 * @return 
 */
require_once ( get_template_directory() . '/inc/helpers/theme-setup.php'); //Theme setup
require_once ( get_template_directory() . '/inc/helpers/general-helpers.php'); //Theme functionalty
require_once ( get_template_directory() . '/inc/notifications/email_notifications.php'); //Theme functionalty
require_once ( get_template_directory() . '/inc/helpers/currencies.php'); //Currencies
require_once ( get_template_directory() . '/inc/helpers/languages.php'); //Currencies
require_once ( get_template_directory() . '/inc/base-classes/class-framework.php'); //Base Functionality
require_once ( get_template_directory() . '/inc/base-classes/class-messages-helper.php'); //For Site Notifications
require_once ( get_template_directory() . '/inc/headers/class-headers.php');
require_once ( get_template_directory() . '/inc/footers/class-footers.php');
require_once ( get_template_directory() . '/inc/subheaders/class-subheaders.php');
require_once ( get_template_directory() . '/inc/template-tags.php');
require_once ( get_template_directory() . '/inc/extras.php');
require_once ( get_template_directory() . '/inc/customizer.php');
require_once ( get_template_directory() . '/inc/constants.php');
require_once ( get_template_directory() . '/inc/jetpack.php');
require_once ( get_template_directory() . '/inc/google-fonts/google_fonts.php'); // goole fonts
require_once ( get_template_directory() . '/inc/hooks.php');
require_once ( get_template_directory() . '/plugins/install-plugin.php');
require_once ( get_template_directory() . '/framework-customizations/includes/option-types.php');
require_once ( get_template_directory() . '/directory/class-functions.php');
require_once ( get_template_directory() . '/directory/functions.php');
require_once ( get_template_directory() . '/directory/hooks.php');
require_once ( get_template_directory() . '/core/user-profile/functions.php');
require_once ( get_template_directory() . '/inc/redius-search/location_check.php');
require_once ( get_template_directory() . '/inc/widgets/init.php'); //widgets
require_once ( get_template_directory() . '/directory/bookings/functions.php'); //Booking
require_once ( get_template_directory() . '/directory/bookings/hooks.php'); //Booking
require_once ( get_template_directory() . '/directory/data-importer/importer.php'); //Dummy data importer
require_once ( get_template_directory() . '/inc/class-woocommerce.php'); //woocommerce class
require_once ( get_template_directory() . '/directory/woo-hooks.php'); //woocommerce hooks
