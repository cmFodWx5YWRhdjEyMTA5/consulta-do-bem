<?php
/**
 * File Type: Functions
 */
if( ! class_exists('TG_PluginFunction') ) {
	
	class TG_PluginFunction {
		
		protected static $instance = null;
		 
		public function __construct() {
			//
		}
		
		
		public function getExtraClass( $el_class ) {
			$output = '';
			if ( $el_class != '' ) {
				$output = " " . str_replace( ".", "", $el_class );
			}

			return $output;
		}
	
	}
	
  	new TG_PluginFunction();	
}