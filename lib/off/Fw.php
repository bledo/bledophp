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

namespace off;
class Fw
{
	public static $conf_default_controller = 'index';
	public static $conf_default_controller_action = 'index';
	public static $conf_default_error_controller = 'error';
	public static $conf_controller_namespace = 'controller';
	public static $conf_controller_suffix = 'Controller';
	public static $conf_controller_prefix = '';
	public static $conf_base_url;

	public static function run() {
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

			// remove base url
			$path_info	= str_replace(trim(self::$conf_base_url, '/'), '', trim($path_info, '/'));

			// Request
			$request	= new Request($path_info, self::$conf_default_controller, self::$conf_default_controller_action);

			// Controller
			$obj		= self::getController($request->getController());

			// Response
			try
			{
				$response	= $obj->getResponse($request);
			}
			catch (\Exception $e)
			{
				$response  = $obj->errorResponse($request, $e);
			}
		}
		catch (\Exception $e)
		{
			$obj = self::getController(self::$conf_default_error_controller);
			$response = $obj->errorResponse($request, $e);
		}

		$response->respond($request);
	}

	public static function enableAutoload($defaultAutoLoad=false, $throwException=false)
	{
		static $fwp = null;
		static $len = null;

		if ($fwp == null)
		{
			$fwp = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR); // framework path
			$len = strlen(__NAMESPACE__);
		}

		$alf = function($class) use ($fwp, $len, $defaultAutoLoad, $throwException)
		{
			if (__NAMESPACE__ == substr($class, 0, $len))
			{
				// framework class
				$file = $fwp .DIRECTORY_SEPARATOR.str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
			}
			else
			{
				// non framework class
				if (!$defaultAutoLoad) { return false; }
				$file = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
			}

			$inc = include($file);

			if ($inc === false && $throwException) {
				throw new \Exception('Class '.$class.' not found');
			}
		};

		spl_autoload_register($alf);
	}

	public function getController($name)
	{
		$obj_name = self::$conf_controller_namespace.'\\'.self::$conf_controller_prefix.ucfirst(strtolower($name)).self::$conf_controller_suffix;
		ltrim($obj_name, '\\');
		$obj = new $obj_name;
		return $obj;
	}

}


