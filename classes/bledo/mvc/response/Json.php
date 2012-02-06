<?php
namespace bledo\mvc\response;

class Json extends AbstractResponse
{
	public $json;
	public function __construct($arr=array())
	{
		$this->json = $arr;
	}

	public function respond(\bledo\mvc\Request $request)
	{
		$this->setHeader('Content-Type: application/json');
		$this->_sendHeaders();
		echo json_encode($this->json);
	}
}
