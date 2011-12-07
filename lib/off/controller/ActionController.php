<?php
namespace off\controller;

use off\request\Request;
use off\exception\PageNotFoundException;

/**
 * Base controller class.
 *
 * Use this class as the base for other controllers
 */
class ActionController implements Controller
{
	/**
	 * 
	 * @var Request
	 */
	protected $request;
	
	/**
	 * Method to place initialization code.
	 *
	 * This method runs before any action 
	 */
	public function init()
	{
	}

	/**
	 *
	 * @param Request $req
	 * @return Response
	 */
	public function getResponse(Request $req)
	{
		$this->request	= $req;
		$action			= $req->getAction();
		$controller		= $req->getController();

		//
		//
		$method = $action.'Action';
		if ( !method_exists($this, $method) )
		{
			throw new PageNotFoundException("Page $controller/$action not found");
		}

		//
		//
		$this->init();
		return $this->$method();
	}

	/**
	 * 
	 * Enter description here ...
	 * @param Request $req
	 * @param \Exception $e
	 * @throws Exception
	 * @return Response
	 */
	public function errorResponse(Request $req, \Exception $e)
	{
		throw $e;
	}
}
