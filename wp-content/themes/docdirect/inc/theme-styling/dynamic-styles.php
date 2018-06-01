<?php
if (function_exists('fw_get_db_settings_option')) {
	$theme_type = fw_get_db_settings_option('theme_type');
	$theme_color = fw_get_db_settings_option('theme_color');
	$body_background_color = fw_get_db_settings_option('background_color');
	$enable_typo = fw_get_db_settings_option('enable_typo');
	$background = fw_get_db_settings_option('background');
	$custom_css = fw_get_db_settings_option('custom_css');
	$body_font = fw_get_db_settings_option('body_font');
	$h1_font = fw_get_db_settings_option('h1_font');
	$h2_font = fw_get_db_settings_option('h2_font');
	$h3_font = fw_get_db_settings_option('h3_font');
	$h4_font = fw_get_db_settings_option('h4_font');
	$h5_font = fw_get_db_settings_option('h5_font');
	$h6_font = fw_get_db_settings_option('h6_font');
}
?>

<style>
	<?php echo (isset($custom_css)) ? $custom_css : ''; ?>
	<?php if (isset($enable_typo) && $enable_typo == 'on') { ?>
		body,p,ul,li {
			font-size:<?php echo (isset($body_font['size'])) ? $body_font['size'] : '100%'; ?>px;
			font-family:<?php echo (isset($body_font['family'])) ? $body_font['family'] : 'Lato'; ?>;
			font-style:<?php echo (isset($body_font['style'])) ? $body_font['style'] : ''; ?>;
			color:<?php echo (isset($body_font['color'])) ? $body_font['color'] : '#000'; ?>;
		}
		body h1{
			font-size:<?php echo (isset($h1_font['size'])) ? $h1_font['size'] : ''; ?>px !important;
			line-height:<?php echo (isset($h1_font['size'])) ? $h1_font['size'] : ''; ?>px !important;
			font-family:<?php echo (isset($h1_font['family'])) ? $h1_font['family'] : ''; ?>;
			font-style:<?php echo (isset($h1_font['style'])) ? $h1_font['style'] : ''; ?>;
			color:<?php echo (isset($h1_font['color'])) ? $h1_font['color'] : ''; ?>;
		}
		body h2{
			font-size:<?php echo (isset($h2_font['size'])) ? $h2_font['size'] : ''; ?>px !important;
			line-height:<?php echo (isset($h2_font['size'])) ? $h2_font['size'] : ''; ?>px !important;
			font-family:<?php echo (isset($h2_font['family'])) ? $h2_font['family'] : ''; ?>;
			font-style:<?php echo (isset($h2_font['style'])) ? $h2_font['style'] : ''; ?>;
			color:<?php echo (isset($h2_font['color'])) ? $h2_font['color'] : ''; ?>;
		}
		body h3{
			font-size:<?php echo (isset($h3_font['size'])) ? $h3_font['size'] : ''; ?>px !important;
			line-height:<?php echo (isset($h3_font['size'])) ? $h3_font['size'] : ''; ?>px !important;
			font-family:<?php echo (isset($h3_font['family'])) ? $h3_font['family'] : ''; ?>;
			font-style:<?php echo (isset($h3_font['style'])) ? $h3_font['style'] : ''; ?>;
			color:<?php echo (isset($h3_font['color'])) ? $h3_font['color'] : ''; ?>;
		}
		body h4{
			font-size:<?php echo (isset($h4_font['size'])) ? $h4_font['size'] : ''; ?>px !important;
			line-height:<?php echo (isset($h4_font['size'])) ? $h4_font['size'] : ''; ?>px !important;
			font-family:<?php echo (isset($h4_font['family'])) ? $h4_font['family'] : ''; ?>;
			font-style:<?php echo (isset($h4_font['style'])) ? $h4_font['style'] : ''; ?>;
			color:<?php echo (isset($h4_font['color'])) ? $h4_font['color'] : ''; ?>;
		}
		body h5{
			font-size:<?php echo (isset($h5_font['size'])) ? $h5_font['size'] : ''; ?>px !important;
			font-size:<?php echo (isset($h5_font['size'])) ? $h5_font['size'] : ''; ?>px !important;
			font-family:<?php echo (isset($h5_font['family'])) ? $h5_font['family'] : ''; ?>;
			font-style:<?php echo (isset($h5_font['style'])) ? $h5_font['style'] : ''; ?>;
			color:<?php echo (isset($h5_font['color'])) ? $h5_font['color'] : ''; ?>;
			color:<?php echo (isset($h5_font['color'])) ? $h5_font['color'] : ''; ?>;
		}
		body h6{
			font-size:<?php echo (isset($h6_font['size'])) ? $h6_font['size'] : ''; ?>px !important;
			font-size:<?php echo (isset($h6_font['size'])) ? $h6_font['size'] : ''; ?>px !important;
			font-family:<?php echo (isset($h6_font['family'])) ? $h6_font['family'] : ''; ?>;
			font-style:<?php echo (isset($h6_font['style'])) ? $h6_font['style'] : ''; ?>;
			color:<?php echo (isset($h6_font['color'])) ? $h6_font['color'] : ''; ?>;
		}
	<?php } ?>
	<?php if (isset($theme_type) && $theme_type === 'custom') { ?>

		/* =============================================
										new color css 
				============================================= */
		<?php if (isset($body_background_color) && $body_background_color != '') { ?>
			body{background-color:<?php echo esc_attr($body_background_color); ?> !important;}
		<?php } ?>

		/*Body Text Color End*/
		<?php if (isset($theme_color) && $theme_color != '') { ?>
				.tg-btn:after,
				.tg-tabs-nav li input[type="radio"]:checked + label .tg-category-name:after,
				.tg-tabs-nav li label:hover .tg-category-name:after,
				.tg-tabs-nav li label:before,
				.tg-searchform .form-group button[type="submit"],
				.navbar-header .navbar-toggle,
				.tg-listing-views li.active a,
				.tg-listing-views li a:hover,
				.tg-listsorttype ul li a:hover,
				#comming-countdown li:last-child,
				.tg-pagination ul li a:hover,
				.tg-pagination ul li:first-child a:hover,
				.tg-pagination ul li:last-child a:hover,
				.tg-pagination ul li:first-child:after,
				.tg-pagination ul li:last-child:before,
				.tg-pagination ul li.active a,
				.tg-widget ul li a:hover i,
				.tg-reactivate .tg-btn,
				.tg-widget.tg-widget-accordions .panel.active .tg-panel-heading:after,
				.tg-widget.tg-widget-accordions .tg-panel-heading:hover:after,
				.tg-mapmarker-content .tg-heading-border:after,
				.tg-on-off .candlestick-wrapper .candlestick-bg .candlestick-toggle,
				.tg-table-hover .tg-edit,
				.tg-schedule-slider .owl-controls.clickable .owl-next:hover,
				.tg-schedule-slider .owl-controls.clickable .owl-prev:hover,
				.specialities-list ul li .tg-checkbox input[type="checkbox"]:checked + label,
				.specialities-list ul li .tg-checkbox input[type="radio"] + label,
				#confirmBox h1,
				#confirmBox .button:after,
				.geodistance_range .ui-slider-range,
				.tg-packageswidth .tg-checkbox input[type=checkbox]:checked + label,
				.tg-radiobox input[type="radio"]:checked + label,
				.pin,
				.tg-share-icons .tg-socialicon li a,
				.tg-btn-list:hover,
				.tg-featuredtags .tg-featured,
				.tg-topbar .tg-login-logout,
				.tg-topbar .tg-login-logout:after,
				.tg-searcharea-v2 .tg-searchform select,
				.tg-features-listing > li:hover > .tg-main-features .tg-feature-head,
				.tg-featuredtags .tg-featured,
				.tg-btn-list:hover,
				.tg-homeslidertwo .tg-searcharea-v2 .tg-searchform .tg-btn,
				.tg-topbar .tg-login-logout:before,
				.tg-catagory a:hover,
				.tg-navdocappointment li a:hover:before,
				.tg-navdocappointment li.active a:before,
				.tg-subdoccategory.tg-edit,
				.tg-subdoccategory:hover,
				.tg-doccategory.tg-edit,
				.tg-doccategory:hover,
				.tg-appointmenttable .table > tbody tr:hover,
				.tg-showdetail,
				.tg-appointmenttime .tg-dayname,
				.tg-radio input[type=radio]:checked + label,
				.tg-appointmenttime .tg-doctimeslot.tg-available .tg-box:hover,
				.tg-iosstylcheckbox input[type=checkbox]:checked + label,
				.tg-iosstylcheckbox label:before,
				.tg-skillbar,
				.tg-userrating li:hover label,
				.tg-radio input[type="radio"]:checked + label,
				.tg-userrating li:after,

				/*4.0*/

				.doc-btn:hover,
				.doc-btn:after,
				.doc-btnformsearch,
				.doc-newsletter,
				.doc-btnsubscribe,
				.doc-footermiddlebar > figure,
				.doc-featuredicon,
				.doc-btnview:hover,
				.doc-pagination ul li a:hover,
				.doc-skillbar,
				.doc-testimonials.owl-theme .owl-controls .owl-buttons div:hover,
				.doc-widgetlistingslider.owl-theme .owl-controls .owl-buttons div:hover
				.contact-form .fw_form_fw_form input[type="submit"]:hover,
				.tg-description input[type="submit"]:hover,
				.tg-btn-invoices:hover,
				.tg-btn:hover,
				#confirmBox .button:hover,
				.docdirect-loader > div,
				.doc-topbar.doc-topbar-v1,
				.doc-header .doc-topbar,
				.owl-carousel .owl-nav .owl-prev:hover,
				.owl-carousel .owl-nav .owl-next:hover,
				.owl-carousel .owl-dot:hover span,
				.owl-carousel .owl-dot.active span,
				.search-verticle-form,
				.search-verticle-form:hover,
				.sc-dir-types .tg-packages:hover,
				.tg-page-wrapper .bbp-search-form .button, 
				.tg-page-wrapper .bbp-submit-wrapper .button.submit,
				.tg-uploadhead,
				.doc-pagination ul li a.active,
				.tg-question .tg-categoryicon,
				.tg-timecounter,
				.woocommerce input.button.alt,
				.tg-searchform .form-group input[type="submit"].tg-btn
				{background:<?php echo esc_attr($theme_color); ?>;}

				.tg-tags li a.tg-btn:hover,
				.make-appointment-btn,
				.search-verticle-form,
				.search-verticle-form:hover,
				#bbpress-forums #bbp-search-form input[type="submit"],
				.button.logout-link, #bbpress-forums #bbp-search-form input[type="submit"], 
				#bbpress-forums + #bbp-search-form > div input[type="submit"],
				.tg-widget #bbp-search-form > div input[type=submit],
				.tg-tablejoblidting tr:before,
				/*Production*/
				.chosen-container .chosen-results li.highlighted,
				.chosen-container-multi .chosen-choices li.search-choice
				{background:<?php echo esc_attr($theme_color); ?> !important;}
				.tg-banner-holder .tg-searcharea-v2 .tg-searchform .tg-btn,
				.chosen-container-multi .chosen-choices li.search-choice:hover,
				.doc-formadvancesearch .doc-btnarea .doc-btn:hover,
				.doc-formsearchwidget .doc-btnarea .doc-btn:hover,
				.doc-formleavereview fieldset .doc-btn:hover,
				.sc-dir-types .tg-packages:hover
				{
					border-color:<?php echo esc_attr($theme_color); ?> !important;
					background:<?php echo esc_attr($theme_color); ?> !important;
				}

				.tg-on-off input:checked+label{box-shadow: inset 0 0 0 20px <?php echo esc_attr($theme_color); ?> !important;;}

				/*Theme Text Color*/
				a,
				p a,
				p a:hover,
				a:hover,
				a:focus,
				a:active,
				.tg-breadcrumb li a:hover,
				h1 a:hover,
				h2 a:hover,
				h3 a:hover,
				h4 a:hover,
				h5 a:hover,
				h6 a:hover,
				p a,
				.tg-nav ul li a:hover,
				.tg-tabs-nav li label:hover i,
				.tg-tabs-nav li input[type="radio"]:checked + label i,
				.form-searchdoctors h1 em,
				.tg-healthcareonthego ul li:after,
				.tg-findbycategory ul li:hover a,
				.tg-findbycategory ul li:hover:after,
				.tg-findbycategory ul li:last-child a,
				.tg-post .tg-description blockquote:before,
				.tg-post .tg-description blockquote:after,
				.tg-metadata li a:hover,
				.tg-info i,
				.tg-docinfo .tg-stars i,
				.tg-img-hover a,
				.tg-widget ul li .tg-docinfo a:hover,
				.tg-widget ul li a:hover,
				.tg-widget ul li:hover:after,
				.tg-docprofile-content h3 a:hover,
				.tg-contactus .tg-search-categories a:hover,
				.tg-tab-widget .tab-content .tg-stars i,
				.tg-ratingbox .tg-stars i,
				.tg-packages .tg-stars i,
				.tg-otherphotos h3 a:hover,
				.tg-addfield button:hover i,
				.tg-addfield button:hover span,
				.tg-post-detail .tg-post .tg-tags .tg-tag li a:hover,
				.tg-blog-grid .tg-post .tg-contentbox h3 a:hover,
				.tg-reviewcontet .comment-head h3 a:hover,
				.tg-support h3 a:hover,
				.tg-reviewcontet .comment-head .tg-stars i,
				.tg-widget ul li time,
				.tg-nav .navbar-collapse ul li a:hover,
				.tg-doceducation .tg-findbycategory ul li:hover a,
				.tg-login-logout > li > a:hover,
				.tg-login-logout li ul > li > a:hover,
				.tg-login-logout li ul > li > a:hover i,
				.tg-packageswidth .tg-checkbox input[type=radio]:checked + label .tg-featuredicon em,
				.tg-widget.tg-datatype-two ul li a:hover:after,
				.tg-widget.tg-datatype ul li a:hover:after,
				.tg-schedule-widget-v2 ul li.current a,
				.tg-schedule-widget-v2 ul li.current a:after,
				.tg-schedule-widget-v2 ul li.current a:before,
				.tg-listbox .tg-listdata h4 a:hover,
				.tg-sub-featured li:hover a span,
				.tg-sub-featured li:hover a em,
				.tg-listbox .tg-listdata h4 a:hover,
				.tg-homeslidertwo.owl-theme .owl-prev:hover i,
				.tg-homeslidertwo.owl-theme .owl-next:hover i,
				.tg-counter i,
				.tg-counter .timer,
				.tg-appointmenttable .table > tbody tr:hover .tg-btncheck,
				.tg-showdetail .tg-btncheck,
				.tg-doctorhead .tg-heading-border h3 a,
				.tg-usercontact ul li:hover a em,
				.tg-usercontact ul li:hover a i,
				.tg-userschedule ul li:hover span,
				.tg-userschedule ul li:hover em,
				.tg-userschedule ul li span:after,
				.tg-averagerating em,
				.tg-reviewhead .tg-reviewheadleft h3:hover a,
				.tg-radio input[type="radio"] + label:after,
				.tg-userschedule ul li.current,
				.tg-userschedule ul li.current span,
				.tg-userschedule ul li.current em,
				.tg-usercontactinfo .tg-doccontactinfo li:last-child a,
				.contact-form .fw_form_fw_form input[type="submit"],
				/*4.0*/
				.doc-section-heading span,
				.doc-matadata li:hover a,
				.doc-matadata li:hover a i,
				.doc-featurehead h2 a:hover,
				.doc-featurehead span,
				.doc-addressinfo li a,
				.doc-breadcrumb li a:hover,
				.doc-doctorname span,
				.doc-widgetdoctorlisting ul li.doc-btnviewall a,
				.doc-widgetdoctorlisting ul li.doc-btnviewall a i,
				.doc-breadcrumb li.doc-active a,
				.doc-searchresult em,
				.doc-userphotogallery ul li a .img-hover span,
				.doc-averagerating em,
				.doc-homecatagoryslider.owl-theme .owl-controls .owl-buttons div:hover,
				.user-liked i,
				.doc-nav > ul > li > a:hover,
				.doc-languages .tg-socialicon li a,
				.tg-stars.star-rating span:before,
				.doc-homecatagoryslider.owl-carousel .owl-nav div:hover,
				.doc-homecatagoryslider.owl-carousel .owl-nav div:hover i,
				.doc-usermenu li a:hover,
				.doc-navigation li a:hover,
				.tg-companycontent .tg-title h3:hover a,
				.woocommerce-MyAccount-navigation ul li a:hover,
				.woocommerce-MyAccount-navigation ul li.is-active a
				
				{ color: <?php echo esc_attr($theme_color); ?>;}

				.tg-doctor-detail2 .tg-findbycategory ul li a:hover,
				.docdirect-menu li.active a,
				.tg-sub-featured li:hover a span,
				.tg-sub-featured li:hover a em,
				.tg-listbox .tg-listdata h4 a:hover,
				.tg-homeslidertwo.owl-theme .owl-prev:hover i,
				.tg-homeslidertwo.owl-theme .owl-next:hover i,
				.tg-counter i,
				.tg-counter .timer,
				.tg-usercontactinfo .tg-doccontactinfo li:last-child a,
				.doc-nav > ul > li.current-menu-item a, 
				.tg-doctor-detail2 .tg-findbycategory ul li a:hover, 
				.user-liked i,
				.tg-radiobox input[type=radio]:checked + label:before, 
				.tg-packageswidth .tg-checkbox input[type=checkbox]:checked + label:before, 
				.tg-packageswidth .tg-checkbox input[type=radio]:checked + label:before,
				.sp-icon-wrap .tg-tags li .tg-btn:hover,
				#menu-userfull-links li:hover a,
				#menu-userfull-links li a:hover,
				.doc-footer .tg-docname a
				{color: <?php echo esc_attr($theme_color); ?> !important;}


				.tg-btn,
				.tg-nav ul li ul,
				.tg-theme-heading .tg-roundbox,
				.tg-threecolumn,
				.tg-show,
				.tg-searchform .form-group .tg-btn.tg-advance-search:hover,
				.tg-listsorttype ul li a:hover,
				.tg-featuredicon,
				.tg-tab-widget ul.tg-nav-tabs li.active a,
				.tg-tab-widget ul.tg-nav-tabs li a:hover,
				#confirmBox .button,
				.tg-form-modal .tg-radiobox input[type="radio"]:checked + label,
				.tg-pagination ul li a:hover,
				.tg-nav-v2 > div > ul > li a:hover,
				.tg-nav-v2 > div > ul > li.active a,
				.tg-userrating li:hover label,
				.tg-radio input[type="radio"] + label,
				.tg-radio input[type="radio"]:checked + label,
				.tg-mapmarker figure,

				/*4.0*/
				.doc-header,
				.doc-featuredicon:after,
				.doc-featuredicon:before,
				.doc-pagination ul li a:hover,
				.doc-testimonials.owl-theme .owl-controls .owl-buttons div:hover,
				.doc-widgetlistingslider.owl-theme .owl-controls .owl-buttons div:hover,
				.doc-nav ul li ul,
				.contact-form .fw_form_fw_form input[type="submit"],
				.tg-description input[type="submit"],
				.tg-btn-invoices,
				.tg-btn,
				.tg-appointmenttime .tg-doctimeslot .tg-box,
				.tg-appointmenttime .tg-doctimeslot.tg-available .tg-box:hover,
				#menu-userfull-links li:hover a,
				#menu-userfull-links li a:hover,
				.doc-pagination ul li a.active
				{border-color:<?php echo esc_attr($theme_color); ?>;}

				.geodistance_range .ui-slider-handle,
				.owl-carousel .owl-nav .owl-prev:hover,
				.owl-carousel .owl-nav .owl-next:hover{border-color:<?php echo esc_attr($theme_color); ?> !important;}

				.tg-map-marker .tg-docimg .tg-show,
				.tg-docimg .tg-uploadimg{border-bottom-color: <?php echo esc_attr($theme_color); ?>;}
				.tg-login-logout li ul, 
				.tg-nav ul,
				.tg-login-logout li ul{border-top-color: <?php echo esc_attr($theme_color); ?> !important;}

				.pulse:after{
				  -webkit-box-shadow: 0 0 1px 2px <?php echo esc_attr($theme_color); ?>;
				  box-shadow: 0 0 1px 2px <?php echo esc_attr($theme_color); ?>;
				}

		<?php } ?>
	<?php } ?>
</style>

