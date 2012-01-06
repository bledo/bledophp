<?php
namespace off;

class JsonResponse extends AbstractResponse
{
	public $json;
	public function __construct($arr=array())
	{
		$this->json = $arr;
	}

	public function respond(Request $request)
	{
		$this->setHeader('Content-Type: application/json');
		$this->_sendHeaders();
		echo json_encode($this->json);
	}
}
