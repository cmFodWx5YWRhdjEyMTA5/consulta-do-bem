<?php if (!defined('FW')) die('Forbidden');

class FW_Shortcode_Blog_Posts extends FW_Shortcode
{
	protected function _render($atts, $content = null, $tag = '')
	{
		$view_path = $this->locate_path('/views/'.$atts['blog_view'].'.php');
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

