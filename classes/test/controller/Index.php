<?php
namespace test\controller;

class Index extends \bledo\test\PhpUnit
{
	protected $controller;

	public function setUp()
	{
		$this->controller = new \controller\Index();
	}

	public function testIndexAction()
	{
		$request = new \bledo\mvc\request\Request('index/index/name/ricardo', 'index', 'index');
		$resp = $this->controller->getResponse($request);
		$this->assertTrue($resp instanceof \bledo\mvc\response\Phtml);
	}
}
