<?php
namespace controller;

class Index extends \bledo\mvc\BaseController
{
	protected function init()
       	{
		session_start();
	}
	
	public function index()
	{
		$resp = new \bledo\mvc\response\Phtml(VIEWDIR);
		//$resp->assign('main');
		return $resp;
	}
}
