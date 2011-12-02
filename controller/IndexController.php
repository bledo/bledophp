<?php
class IndexController extends off_Controller
{
	public function indexAction()
	{
		$this->view->content = 'Hello';
	}
}
