<?php
namespace off\response;

abstract class AbstractResponse implements Response
{
	/**
	 * @var array
	 */
	protected $_headers = array();
	
	/**
	 * (non-PHPdoc)
	 * @see off\response.Response::setHeader()
	 */
	public function setHeader($str) {
		$this->_headers[] = $str;
	}
	
	/**
	 * prints out all headers
	 */
	protected function _sendHeaders()
	{
		foreach ($this->_headers as $header) {
			header($header);
		}
	}
}