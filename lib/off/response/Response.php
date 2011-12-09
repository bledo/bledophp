<?php
namespace off\response;

interface Response
{
	/**
	 * 
	 * @param string $str
	 */
	public function setHeader($str);
	
	/**
	 * 
	 * @param \off\request\Request $request
	 */
	public function respond(\off\request\Request $request);

	/**
	 * Read : http://us.php.net/manual/en/function.setcookie.php
	 * 
	 * @param string $name
	 * @param string $value
	 * @param int $expire
	 * @param string $path
	 * @param string $domain
	 * @param bool $secure
	 * @param bool $httponly
	 */
	public function setCookie($name, $value, $expire=0, $path='', $domain='', $secure=false, $httponly=false);
}
