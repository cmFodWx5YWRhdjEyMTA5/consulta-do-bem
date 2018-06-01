<?php

if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

/**
 * @init all widgets
 * @return widget
 */


get_template_part('/inc/widgets/flickr-widget'); //Flickr Widget
get_template_part('/inc/widgets/address-widget'); //Get-in-touch
get_template_part('/inc/widgets/image-widget'); //Image Widget
get_template_part('/inc/widgets/accordion-widget'); //Accordion Widget
get_template_part('/inc/widgets/recent-posts-widget'); //Recent Posts Widget
get_template_part('/inc/widgets/featured-user-widget'); //Featured users Widget
get_template_part('/inc/widgets/latest-listings-widget'); //Latest users Widget
get_template_part('/inc/widgets/ads'); //Latest users Widget
get_template_part('/inc/widgets/class-recent-questions'); //Recent question
get_template_part('/inc/widgets/class-answers-questions-statics'); //question statics