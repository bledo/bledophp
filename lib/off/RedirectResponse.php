<?php
namespace off;

class RedirectResponse extends AbstractResponse
{
	public function __construct($url)
	{
		$this->setHeader('Location: ' . $url);
	}

	public function respond(Request $request)
	{
		$this->_sendHeaders();
	}
}
