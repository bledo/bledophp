<?php
namespace  {

	$path = get_include_path();
	include(__DIR__.'/../../../bootstrap.php');
	set_include_path(get_include_path().PATH_SEPARATOR.$path);
}


namespace bledo\test {

	abstract class PhpUnit extends \PHPUnit_Framework_TestCase
	{
	}
}

namespace bledo\test {

	class Request implements \bledo\mvc\Request
	{
		protected $_args;
		protected $_cookies;
		protected $_parts;
		protected $_base;
		protected $_controller;
		protected $_action;

		public function __construct($uri, $args=array(), $basePath='', $cookies=array() )
		{
			$this->_uri = $uri;
			$this->_parts = parse_url($uri);
			$this->_args = $args;
			$this->_cookies = $cookies;
			$this->_base = $basePath;

			//
			$parts = explode('/', trim(str_replace($this->_base,'', $this->_parts['path']), '/') );

			// Controller
			$this->_controller = array_shift($parts);
			if (!$this->_controller) {
				$this->_controller = 'index';
			}

			// action
			$this->_action = array_shift($parts);
			if (!$this->_action) {
				$this->_action = 'index';
			}

			// path params
			$params = array();
			for (
				$key = array_shift($parts), $val = array_shift($parts);
				!is_null($key);
				$key = array_shift($parts),
				$val = array_shift($parts)
			)
			{
				$params[urldecode($key)] = urldecode($val);
			}

			//
			$this->_args = array_merge($params, $this->_args);
		}

		public function getPath()
		{
			return $this->_parts['path'];
		}

		public function getBasePath()
		{
			return $this->_base;
		}

		public function getScheme()
		{
			return $this->_parts['scheme'];
		}

		public function getHost()
		{
			return $this->_parts['host'];
		}

		public function getPort()
		{
			return $this->_parts['port'];
		}

		public function getUri()
		{
			$this->_uri;
		}

		public function getBaseUri()
		{
			$prot = empty($this->_parts['scheme']) ? 'http' : 'https';
			$port = ':'.$this->_parts['port'];
			if ( ($prot == 'https' && $port == ':443') || ($prot == 'http' && $port == ':80')) { $port = ''; }
			return $prot.'://'.$this->_parts['host'] .$port.$this->getBasePath();
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
			if (array_key_exists($k, $this->_args))
			{
				return $this->_args[$k];
			}

			return $def_val;
		}

		public function getCookie($k, $def_val=null)
		{
			if (array_key_exists($k, $this->_cookies))
			{
				return $this->_cookies[$k];
			}

			return $def_val;
		}
	}
}
