<?php
class IndexController extends \off\controller\ActionController
{
	public function indexAction()
	{
		$resp = new \off\response\PhtmlResponse();
		$resp->view->content = 'hello ' . $this->request->getParam('name');
		return $resp;
	}
}
