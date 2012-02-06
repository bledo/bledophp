<?php
/*
Copyright 2011,2012 Ricardo Ramirez, The ClickPro.com LLC

This file is part of Bledo Framework.

Bledo Framework is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Bledo Framework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Bledo Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace bledo\mvc;

class Request
{
	protected $_controller;
	protected $_action;
	protected $_params = array();
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
			$this->_params[urldecode($key)] = urldecode($val);
		}

		if (!preg_match('/^\w*$/', $this->_action) || !preg_match('/^\w*$/', $this->_controller))
		{
			throw new PageNotFoundException("Page $this->_controller/$this->_action not found");
		}
	}

	public function setParam($k, $v)
	{
		$this->_params[$k] = $v;
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
		else if (array_key_exists($k, $_POST))
		{
			return $_POST[$k];
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
