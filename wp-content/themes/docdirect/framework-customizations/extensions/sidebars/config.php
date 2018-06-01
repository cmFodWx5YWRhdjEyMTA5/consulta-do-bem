<?php

$cfg = array();

$cfg['sidebar_positions'] = array(
	'full' => array(
		'icon_url' => 'full.png',
		'sidebars_number' => 0
	),
	'left' => array(
		'icon_url' => 'left.png',
		'sidebars_number' => 1
	),
	'right' => array(
		'icon_url' => 'right.png',
		'sidebars_number' => 1
	),
	
);

$cfg['dynamic_sidebar_args'] = array(
	'before_widget' => '<div class="tg-widget %2$s" id="%1$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>',
);