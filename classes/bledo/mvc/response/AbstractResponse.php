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

namespace bledo\mvc\response;

abstract class AbstractResponse implements Response
{
	/**
	 * @var array
	 */
	protected $_headers = array();

	/**
	 * @var array
	 */
	protected $_cookie = array();
	
	/**
	 * (non-PHPdoc)
	 * @see bledo.mvc.Response::setHeader()
	 */
	public function setHeader($str) {
		$this->_headers[] = $str;
	}

	/**
	 * (non-PHPdoc)
	 * @see bledo.mvc.Response::setCookie()
	 */
	public function setCookie($name, $value, $expire=0, $path='', $domain='', $secure=false, $httponly=false)
	{
		$cookie = new \bledo\mvc\Cookie();
		$cookie->name = $name;
		$cookie->value = $value;
		$cookie->expire = $expire;
		$cookie->path = $path;
		$cookie->domain = $domain;
		$cookie->secure = $secure;
		$cookie->httponly = $httponly;
		$this->_cookie[$name] = $cookie;
	}

	public function getHeaders(\bledo\mvc\Request $request)
	{
		return $this->_headers;
	}

	public function getCookies(\bledo\mvc\Request $request)
	{
		return $this->_cookie;
	}
}
