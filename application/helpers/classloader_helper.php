<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if(!function_exists('ci_class_loader')){
	/**
	* ci_class_loader - load class in 'services' path
	*
	* @access    public
    * @param    string
	* @return    object
	*/
	function ci_class_loader( $path ) {
		require_once( $path.'.php' );

		$name      = end( explode( '/', $path ) );
		$class     = ucfirst( $name );
		$ci        =& get_instance();
		$ci->$name = new $class();
	    return $ci->$name;
	}
}
?>
