<?php
namespace off\request;

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
