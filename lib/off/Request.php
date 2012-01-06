<?php
namespace off;

class Request
{
	protected $_controller;
	protected $_action;
	protected $_get = array();
	protected $_post = array();
	protected $_cookie = array();

	public function __construct($request_url, $default_controller, $default_action)
	{
		$parts = explode('/', trim($request_url, '/'));

		// Controller
		$this->_controller = array_shift($parts);
		if (!$this->_controller) {
			$this->_controller	= $default_controller;
		}

		// action
		$this->_action = array_shift($parts);
		if (!$this->_action) {
			$this->_action		= $default_action;
		}

		// path params
		for (
			$key = array_shift($parts), $val = array_shift($parts);
			!is_null($key);
			$key = array_shift($parts),
			$val = array_shift($parts)
		)
		{
			$this->_get[urldecode($key)] = urldecode($val);
		}

		if (!preg_match('/^\w*$/', $this->_action) || !preg_match('/^\w*$/', $this->_controller))
		{
			throw new PageNotFoundException("Page $this->_controller/$this->_action not found");
		}
	}

	public function setParam($k, $v)
	{
		$this->_get[$k] = $v;
	}

	public function setPost($k, $v)
	{
		$this->_post[$k] = $v;
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
		else if (array_key_exists($k, $this->_get))
		{
			return $this->_get[$k];
		}

		return $def_val;
	}

	public function getPost($k, $def_val=null)
	{
		if (array_key_exists($k, $_POST))
		{
			return $_POST[$k];
		}
		else if (array_key_exists($k, $this->_post))
		{
			return $this->_post[$k];
		}

		return $def_val;
	}

	public function setCookie($name, $value)
	{
		$this->_cookie[$name] = $value;
	}

	public function getCookie($k, $def_val=null)
	{
		if (array_key_exists($k, $_COOKIE))
		{
			return $_COOKIE[$k];
		}
		else if (array_key_exists($k, $this->_cookie))
		{
			return $this->_cookie[$k];
		}

		return $def_val;
	}
}
