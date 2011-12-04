<?php
class IndexController extends \off\ActionController
{
	public function indexAction()
	{
		$resp = new \off\ViewResponse();

		$resp->view->content = 'hello';

		return $resp;
	}
}
