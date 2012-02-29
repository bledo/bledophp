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

class HttpRequest implements Request
{
	protected $_controller;
	protected $_action;
	protected $_params = array();
	protected $_post = array();
	protected $_cookie = array();

	protected $_scheme;
	protected $_host;
	protected $_port;
	protected $_path;
	protected $_base_path;

	public function __construct($request_url, $base_path, $default_controller, $default_action)
	{
		$parts = parse_url($request_url);
		$this->_scheme = $parts['scheme'];
		$this->_host = $parts['host'];
		$this->_port = $parts['port'];
		$this->_path = $parts['path'];
		$this->_base_path = $base_path;

		//
		$parts = explode('/', trim(str_replace($this->_base_path,'', $this->_path), '/') );

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

	public function getBasePath() {
		return $this->_base_path;
	}

	public function getPath() {
		return $this->_path;
	}

	public function getScheme() {
		return $this->_scheme;
	}

	public function getHost() {
		return $this->_host;
	}

	public function getPort() {
		return $this->_port;
	}

	private function _getRequestUri()
	{
		$prot = empty($_SERVER['HTTPS']) ? 'http' : 'https';
		$port = ':'.$_SERVER['SERVER_PORT'];
		if ( ($prot == 'https' && $port == ':443') || ($prot == 'http' && $port == ':80')) { $port = ''; }
		return $prot.'://'.$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}

	public function getUri() {
		return $this->_getRequestUri();
	}

	public function getBaseUri() {
		$prot = empty($_SERVER['HTTPS']) ? 'http' : 'https';
		$port = ':'.$_SERVER['SERVER_PORT'];
		if ( ($prot == 'https' && $port == ':443') || ($prot == 'http' && $port == ':80')) { $port = ''; }
		return $prot.'://'.$_SERVER['SERVER_NAME'].$port.$this->getBasePath();
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
