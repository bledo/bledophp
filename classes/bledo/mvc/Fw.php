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

namespace bledo\mvc;
class Fw
{
	public static $conf_default_controller = 'index';
	public static $conf_default_controller_action = 'index';
	public static $conf_default_error_controller = 'error';
	public static $conf_controller_namespace = 'controller';
	public static $conf_controller_suffix = '';
	public static $conf_controller_prefix = '';
	public static $conf_base_url;

	public static function run()
       	{
		ob_start();
		$response = null;
		try
		{
			// REDIRECT_URL : mod_rewrite
			if (array_key_exists('REDIRECT_URL', $_SERVER))
			{
				$path_info = $_SERVER['REDIRECT_URL'];
			} 
			else // PATH_INFO index.php/some/path
			{
				$path_info = (string) @$_SERVER['PATH_INFO'];
			}

			// remove base url
			$path_info	= str_replace(trim(self::$conf_base_url, '/'), '', trim($path_info, '/'));

			// Request
			$request	= new \bledo\mvc\Request($path_info, self::$conf_default_controller, self::$conf_default_controller_action);

			// check controller name
			$controller = $request->getController();
			if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]*/i', $controller))
			{
				throw new \bledo\mvc\PageNotFoundException("page $controller not found");
			}

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

		echo ob_get_clean();
		$response->respond($request);
	}


	public function getController($name)
	{
		$obj_name = self::$conf_controller_namespace.'\\'.self::$conf_controller_prefix.ucfirst(strtolower($name)).self::$conf_controller_suffix;
		ltrim($obj_name, '\\');
		$obj = new $obj_name;
		return $obj;
	}

}


