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

error_reporting(E_ALL);

/* Defaults */
$default_controller = 'index';
$default_action = 'index';
$default_error_controller = 'error';

/* Include Path */
set_include_path( __DIR__ . '/../controller' . PATH_SEPARATOR .  __DIR__ . '/../view' . PATH_SEPARATOR . get_include_path() );


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

@list($controller, $action) = explode('/', $_SERVER['PATH_INFO']);
$controller	= !preg_match('/^\w*$/', $controller) ? $default_error_controller : $controller;
$action		= trim($action) ?: $default_controller;
$controller	= trim($controller) ?: $default_action;

$view = new df_View();
$view->controller = $controller;
$view->action = $action;

$obj_name	= ucfirst(strtolower($controller)).'Controller';
$obj_name	= !class_exists ( $obj_name, true ) ? ucfirst($default_error_controller).'Controller' : $obj_name;
$obj		= new $obj_name();

$obj->dispatch($controller, $action, $view);

exit;
//
// Done
//


/**
 * Base controller class.
 *
 * Use this class as the base for other controllers
 */
class df_Controller
{
	/**
	 * @var df_View
	 */
	protected $view;

	/**
	 *
	 * @param string $controller
	 * @param string $action
	 * @param df_View $view
	 */
	public function dispatch($controller, $action,  $view)
	{
		$this->view = $view;

		$method = $action.'Action';

		//
		$this->init();

		//
		if ( method_exists($this, $method) )
		{
			$this->$method();
		}

		//
		if (!$this->view->is_set('content'))
		{
			$view_file = $controller.'/'.$action.'.php';
			if (file_exists($view_file))
			{
				$this->view->content = $this->view->fetch($view_file);
			}
		}

		//
		echo $this->view->fetch('template.php');
	}

	/**
	 * Method to place initialization code.
	 *
	 * This method is run before the action
	 */
	public function init()
	{
	}
}

class df_View
{
	/**
	 * @var array
	 */
	protected $vals = array();

	/**
	 *
	 * @return string
	 */
	public function __get($k) {
		return @$this->vals[$k];
	}

	/**
	 *
	 * @param string $k
	 * @param mixed $k
	 */
	public function __set($k, $v) {
		$this->vals[$k] = $v;
	}

	/**
	 *
	 * @param string $file The template to parse
	 * @return string
	 */
	public function fetch($file) {
		ob_start();
		include($file);
		return ob_get_clean();
	}

	/**
	 * Checks if a value is set
	 *
	 * @param string $k
	 * @return bool
	 */
	public function is_set($k) {
		return isset($this->vals[$k]);
	}
}


