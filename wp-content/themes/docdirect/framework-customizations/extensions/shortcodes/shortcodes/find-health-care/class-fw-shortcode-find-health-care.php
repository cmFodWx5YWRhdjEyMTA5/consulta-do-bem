<?php if (!defined('FW')) die('Forbidden');

class FW_Shortcode_Find_Health_Care extends FW_Shortcode
{
	protected function _render($atts, $content = null, $tag = '')
	{
		if( isset( $atts['type'] ) && $atts['type'] == 'speciality' ){
			$type = $atts['type'];
		} else{
			$type = 'locations';
		}
		
		$view_path = $this->locate_path('/views/'.$type.'.php');
		return fw_render_view( $view_path, array(
			'atts'    => $atts,
			'content' => $content,
			'tag'     => $tag
		) );
	}
	private function get_error_msg()
	{
		return '<b>Something went wrong</b>';
	}
}

