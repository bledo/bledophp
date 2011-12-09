<?php
error_reporting(E_ALL);

/* Defaults */
//$conf_default_controller = 'index';
//$conf_default_action = 'index';
$conf_default_error_controller = 'error';

/* Include Path */
set_include_path( __DIR__ . '/../controller' . PATH_SEPARATOR .  __DIR__ . '/../view' . PATH_SEPARATOR  .  __DIR__ . '/../action'. PATH_SEPARATOR  .  __DIR__ . '/../lib' . PATH_SEPARATOR  . get_include_path());


/* Autoload */
spl_autoload_register(function($class){
	$file = str_replace(array('_', '\\'), '/', $class) . '.php';
	foreach(explode(PATH_SEPARATOR, get_include_path()) as $path)
	{
		if (is_file("$path/$file")) { include("$path/$file"); break; }
	}
});

