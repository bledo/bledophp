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
namespace off {

	class PageNotFoundException extends \Exception
	{
	}

	interface Controller {
		public function getResponse(Request $req);
		public function errorResponse(Request $req, \Exception $e);
	}


	/**
	 * Base controller class.
	 *
	 * Use this class as the base for other controllers
	 */
	class ActionController implements Controller
	{
		/**
		 * @var View
		 */
		protected $view;

		/**
		 *
		 * @param Request $req
		 * @return Response
		 */
		public function getResponse(Request $req)
		{
			$action = $req->getAction();
			$controller = $req->getController();

			//
			$method = $action.'Action';
			if ( !method_exists($this, $method) )
			{
				throw new PageNotFoundException("Page $controller/$action not found");
			}

			//
			$this->init();

			return $this->$method();
		}

		public function errorResponse(Request $req, \Exception $e)
		{
			throw $e;
		}

		/**
		 * Method to place initialization code.
		 *
		 * This method runs before any action
		 */
		public function init()
		{
		}
	}

	class View
	{
		/**
		 * @var array
		 */
		protected $vals = array();

		/**
		 *
		 * @return string
		 */
		public function get($k)
		{
			return @$this->vals[$k];
		}

		/**
		 *
		 * @return string
		 */
		public function __get($k) {
			return $this->get($k);
		}


		/**
		 *
		 * @param string $k
		 * @param mixed $k
		 */
		public function assign($k, $v) {
			$this->vals[$k] = $v;
		}

		/**
		 *
		 * @param string $k
		 * @param mixed $k
		 */
		public function __set($k, $v) {
			$this->assign($k, $v);
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

	class Request
	{
		protected $_controller;
		protected $_action;
		protected $_params = array();

		public function __construct($path_info, $def_controller, $def_action)
		{
			$parts = explode('/', trim($path_info, '/'));

			// Controller
			$this->_controller = array_shift($parts);
			if (!$this->_controller) {
				$this->_controller	= $def_controller;
			}

			// action
			$this->_action = array_shift($parts);
			if (!$this->_action) {
				$this->_action		= $def_action;
			}

			// path params
			for (
				$key = array_shift($parts), $val = array_shift($parts);
				!is_null($key);
				$key = array_shift($parts),
				$val = array_shift($parts)
			)
			{
				$this->_params[urldecode($key)] = urldecode($val);
			}

			if (!preg_match('/^\w*$/', $this->_action) || !preg_match('/^\w*$/', $this->_controller))
			{
				throw new PageNotFoundException("Page $this->_controller/$this->_action not found");
			}
		}

		public function getController()
		{
			return $this->_controller;
		}

		public function getAction()
		{
			return $this->_action;
		}

		public function getParam($k, $def_val=null)
		{
			if (array_key_exists($k, $_GET))
			{
				return $_GET[$k];
			}
			else if (array_key_exists($k, $this->_params))
			{
				return $this->_params[$k];
			}

			return $def_val;
		}

		public function getPost($k, $def_val=null)
		{
			if (!array_key_exists($k, $_POST))
			{
				return $def_val;
			}

			return $_POST[$k];
		}
	}

	interface Response
	{
		public function respond(\off\Request $request);
	}

	class RedirectResponse implements Response
	{
		protected $_url;
		public function __construct($url)
		{
			$this->_url = $url;
		}

		public function respond(\off\Request $request)
		{
			header('Location: ' . $this->_url);
		}
	}

	class ViewResponse implements Response
	{
		public $template = 'layout.php';
		public $view;
		public function __construct()
		{
			$this->view = new View();
		}

		public function respond(\off\Request $request)
		{
			//
			if (!$this->view->is_set('content'))
			{
				$view_file = $controller.'/'.$action.'.php';
				if (file_exists($view_file))
				{
					$this->view->assign('content', $this->view->fetch($view_file));
				}
			}

			//
			echo $this->view->fetch($this->template);
		}
	}

}

namespace {

//
// Run code
//
error_reporting(E_ALL);

/* Defaults */
$conf_default_controller = 'index';
$conf_default_action = 'index';
$conf_default_error_controller = 'error';



/* Include Path */
set_include_path( __DIR__ . '/../controller' . PATH_SEPARATOR .  __DIR__ . '/../view' . PATH_SEPARATOR  .  __DIR__ . '/../action');


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

	$request	= new off\Request($path_info, $conf_default_controller, $conf_default_action);

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

}
