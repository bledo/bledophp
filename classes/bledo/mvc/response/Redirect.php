<?php
namespace bledo\mvc\response;

class Redirect extends AbstractResponse
{
	public function __construct($url)
	{
		$this->setHeader('Location: ' . $url);
	}

	public function respond(\bledo\mvc\Request $request)
	{
		$this->_sendHeaders();
	}
}
