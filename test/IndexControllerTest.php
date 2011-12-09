<?php
class IndexControllerTest extends \off\test\Controller
{
	protected $controller;

	public function setUp()
	{
		$this->controller = new IndexController();
	}

	public function testIndexAction()
	{
		$request = new off\request\Request('index/index/name/ricardo');
		$resp = $this->controller->getResponse($request);
		$this->assertTrue($resp instanceof \off\response\PhtmlResponse);
		$this->assertTrue($resp->view->content == 'hello ricardo');
	}
}
