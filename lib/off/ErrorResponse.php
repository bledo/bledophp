<?php
namespace off;

class ErrorResponse extends AbstractResponse
{
	protected $e;
	
	public function __construct(Exception $e) {
		$this->e = $e;
	}
	
	public function respond(Request $request) {
		$this->_sendHeaders();
		echo $this->e;
	}
}
