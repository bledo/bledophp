<?php
namespace bledo\test;

class FwTest extends PhpUnit
{
	public function testOutput()
	{
		$_SERVER['SERVER_NAME'] = 'localhost';
		$_SERVER['SERVER_PORT'] = 80;
		$_SERVER['REQUEST_URI'] = '/index/index';
		$_SERVER['SERVER_PROTOCOL'] = 'http';
		ob_start();
		\bledo\mvc\Fw::run();
		$x = ob_get_clean();
	}
}
