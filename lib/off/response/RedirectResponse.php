<?php
namespace off\response;

class RedirectResponse extends AbstractResponse
{
	public function __construct($url)
	{
		$this->setHeader('Location: ' . $url);
	}

	public function respond(\off\request\Request $request)
	{
		$this->_sendHeaders();
	}
}
