<?php
/**
 * Fuel
 *
 *  Function.php uses autoloading to load all the php files inside of the 
 *  libraries directory while maintaining the fuel namespace
 *
 */

spl_autoload_register( 'autoloader' );
function autoloader( $class_name )
{
	$class_name = ltrim( $class_name, '\\' );

	//Do not require if not part of the Fuel theme
	if ( strpos( $class_name, 'fuel' ) !== 0 )
		return;
	$path = 'library' . DIRECTORY_SEPARATOR;
	$class_name = str_replace( 'fuel\\', '', $class_name );
	require_once( $path . $class_name . '.php' );
}

$fuel_theme = new fuel\main();

add_action( 'after_setup_theme', array( $fuel_theme, 'setup' ) );
