<?php
namespace controller;

class Rep extends \bledo\mvc\BaseController
{
	/**
	 * @var \response\Phtml
	 */
	private $_response = null;
	
	/**
	 * 
	 * @return \response\Phtml
	 */
	protected function response()
	{
		if (is_null($this->_response))
		{
			$this->_response = new \response\Phtml('layout-rep.phtml');
		}
		return $this->_response;
	}
	
	protected function init()
	{
		//$log = Logger::getInstance();
		//include(__DIR__ . '/../../htdocs/CBSDirectRep/md_session_init.inc');
		//include(__DIR__ . '/../../htdocs/common/login_init.php');
		session_start();
		
		// if user not logged in, redirect to login page
		// -- allow dologinAction and loginAction to be 
		//    accessed without authentication.
		if ( $this->request->getController() != 'rep'
			 && in_array( $this->request->getAction(), array('dologin', 'login')) )
		if (empty($_SESSION['Representative']))
		{
			Logger::getInstance()->warn('User not authenticated...redirecting to login page');
			return new \response\Redirect('/app/rep/login');
		}
	}
	
	public function index()
	{
		return $this->response();
	}
	
	public function login()
	{
		$resp = $this->response();
		$resp->view->assign('username', @$_COOKIE['rep_username']);
		return $resp;
	}
	
	public function dologin()
	{
		$log = Logger::getInstance();
		$log->info(__METHOD__);
		try
		{
			$log->debug($_POST);
			$_SESSION['Representative'] = \md\model\SalesRep::auth($_POST['username'], $_POST['password']);
			$_COOKIE['rep_username'] = $_POST['username'];
			
			$log->info('Loggin successful.  Redirecting to rep site');
			return new \response\Redirect('/app/rep');
		}
		catch (\md\model\RecordNotFoundException $e)
		{
			$log->warn('Login unsuccessful. Redirecting to login page');
			return new \response\Redirect('/app/rep/login');
		}
	}
}


