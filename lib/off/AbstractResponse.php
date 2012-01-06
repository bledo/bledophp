<?php
namespace off;

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
	 * @see off.Response::setHeader()
	 */
	public function setHeader($str) {
		$this->_headers[] = $str;
	}

	/**
	 * (non-PHPdoc)
	 * @see off.Response::setCookie()
	 */
	public function setCookie($name, $value, $expire=0, $path='', $domain='', $secure=false, $httponly=false)
	{
		$this->_cookie[] = array($name, $value, $expire, $path, $domain, $secure, $httponly);
	}
	
	/**
	 * prints out all headers
	 */
	protected function _sendHeaders()
	{
		//
		foreach ($this->_headers as $header) {
			header($header);
		}

		//
		foreach ($this->_cookie as $c) {
			call_user_func_array('setcookie', $c);
		}
	}

}
