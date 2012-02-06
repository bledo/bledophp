<?php
namespace bledo\mvc\response;

class Error extends AbstractResponse
{
	protected $e;
	
	public function __construct(Exception $e) {
		$this->e = $e;
	}
	
	public function respond(\bledo\mvc\Request $request) {
		$this->_sendHeaders();
		echo $this->e;
	}
}
