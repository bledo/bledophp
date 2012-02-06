<?php
namespace bledo\mvc;

/**
 * Base controller class.
 *
 * Use this class as the base for other controllers
 */
class BaseController implements Controller
{
	/**
	 * 
	 * @var \bledo\mvc\Request
	 */
	protected $request;
	
	/**
	 * Method to place initialization code.
	 *
	 * This method runs before any action 
	 */
	protected function init()
	{
	}

	/**
	 *
	 * @param \bledo\mvc\Request $req
	 * @return bledo\mvc\response\Response
	 */
	public final function getResponse(\bledo\mvc\Request $req)
	{
		$this->request	= $req;
		$action			= $req->getAction();
		$controller		= $req->getController();

		//
		//
		$response = $this->init();
		if (!empty($response)) {
			return $response;
		}

		//
		//
		$ref = new \ReflectionClass($this);
		if ( !$ref->hasMethod($action) )
		{
			throw new PageNotFoundException("Page $controller/$action not found");
		}
		
		$mRef = $ref->getMethod($action);
		if (
			 !$mRef->isPublic() ||
			 $mRef->isStatic() ||
			 $mRef->isAbstract() ||
			 $mRef->isConstructor() ||
			 $mRef->isDestructor()
		)
		{
			throw new PageNotFoundException("Page $controller/$action not found");
		}

		return $this->$action();
	}

	/**
	 * 
	 * Enter description here ...
	 * @param \bledo\mvc\Request $req
	 * @param \Exception $e
	 * @throws Exception
	 * @return bledo\mvc\response\Response
	 */
	public function errorResponse(\bledo\mvc\Request $req, \Exception $e)
	{
		throw $e;
	}
}
