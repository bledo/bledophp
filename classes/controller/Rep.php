<?php
/*
Copyright 2011,2012 Ricardo Ramirez, The ClickPro.com LLC

This file is part of Bledo Framework.

Bledo Framework is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Bledo Framework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Bledo Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

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


