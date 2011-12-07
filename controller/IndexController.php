<?php
class IndexController extends \off\controller\ActionController
{
	public function indexAction()
	{
		$resp = new \off\response\PhtmlResponse();
		//$resp->view->content = print_r($this->request);
		return $resp;
	}
}
