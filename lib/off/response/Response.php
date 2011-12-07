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
}
