<?php
/*
Copyright 2011 Ricardo Ramirez, The ClickPro.com LLC

This file is part of One File Framework

One File Framework is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

One File Framework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with One File Framework. If not, see <http://www.gnu.org/licenses/>.
*/

//
// Run code
//
include(__DIR__ . '/config/config.php');


//
// Autoload
//
spl_autoload_register(function($class){
	$file = str_replace(array('_', '\\'), '/', $class) . '.php';
	foreach(explode(PATH_SEPARATOR, get_include_path()) as $path)
	{
		if (is_file("$path/$file")) { include("$path/$file"); break; }
	}
});


$response = null;

try
{
	// REDIRECT_URL : mod_rewrite
	if (array_key_exists('REDIRECT_URL', $_SERVER))
	{
		$path_info = $_SERVER['REDIRECT_URL'];
	} 
	else // PATH_INFO off.php/some/path
	{
		$path_info = (string) @$_SERVER['PATH_INFO'];
	}

	$request	= new off\request\Request($path_info, $conf_default_controller, $conf_default_action);

	$obj_name	= ucfirst(strtolower( $request->getController() )).'Controller';
	$obj_name	= !class_exists ( $obj_name, true ) ? ucfirst($conf_default_error_controller).'Controller' : $obj_name;
	$obj		= new $obj_name();

	$response	= $obj->getResponse($request);

}
catch (\Exception $e)
{
	try
	{
		$response  = $obj->errorResponse($request, $e);
	}
	catch (\Exception $e)
	{
		$errCtrlClsName = ucfirst($conf_default_error_controller).'Controller';
		if (!class_exists($errCtrlClsName))
		{
			throw $e; // retrhrow
		}

		$errCtrl = new $errCtrlClsName();
		$response = $errCtrl->errorResponse($request, $e);
	}
}

$response->respond($request);
exit;
//
// Done
//

