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
