<?php
class IndexController extends df_Controller
{
	public function indexAction()
	{
		$this->view->content = 'Hello';
	}
}
