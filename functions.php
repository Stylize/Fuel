<?php
/**
 * Rocket Fuel
 * - Sidebar
 * - Custom Walkers
 */

spl_autoload_register('autoloader');
function autoloader($class_name)
{
	$class_name = ltrim($class_name, '\\');

	if (strpos($class_name, 'fuel') !== 0)
		return;
	$path = 'library' . DIRECTORY_SEPARATOR;
	$class_name = str_replace('fuel\\', '', $class_name);
	require_once($path . $class_name . '.php');
}

$fuel_theme = new fuel\main();

//[> Do theme setup on the 'after_setup_theme' hook. <]
add_action( 'after_setup_theme', array( $fuel_theme, 'setup' ) );
