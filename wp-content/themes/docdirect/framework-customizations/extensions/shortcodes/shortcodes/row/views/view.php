<?php if (!defined('FW')) die('Forbidden'); ?>
<?php
	//$success_row_start	 	= '<div class="row">';
	//$success_row_end	 	 	= '</div>';
	$success_row_start	 	= '';
	$success_row_end	 	 	= '';
			
	$success_sidebar	 	 = 'full';
	if (function_exists('fw_ext_sidebars_get_current_position')) {
		$current_position = fw_ext_sidebars_get_current_position();
		if( $current_position !== 'full' &&  ( $current_position == 'left' || $current_position == 'right' ) ) {
			$success_row_start	 	= '';
			$success_row_end	 	 	= '';
		}
	}
 echo ( $success_row_start );
	 echo do_shortcode($content);
 echo ( $success_row_end );

