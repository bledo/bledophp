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

namespace bledo\mvc\response;

class Phtml extends AbstractResponse implements \bledo\mvc\view\View
{
	/**
	 * 
	 * @var string
	 */
	public $layout;
	
	/**
	 * 
	 * @var string
	 */
	public $action_view;


	/**
	 * @var \bledo\mvc\view\Phtml
	 */
	private $phtml;
	
	/**
	 * 
	 * @param string $viewDir Path to directory containing view files
	 * @param string $layout Layout file path
	 * @param string $view Path to view file
	 */
	public function __construct($viewDir, $layout='layout.phtml', $view=null)
	{
		
		$this->phtml = new \bledo\mvc\view\Phtml($viewDir);
		$this->layout = $layout;
		$this->action_view = $view;
	}

	/**
	 * (non-PHPdoc)
	 * @see Response::respond()
	 */
	public function respond(\bledo\mvc\Request $request)
	{
		//
		$this->_sendHeaders();
		
		//
		if (!$this->is_set('content'))
		{
			if (empty($this->action_view)) {
				$this->action_view = $request->getController().'/'.$request->getAction().'.phtml';
			}
			
			$this->assign('content', $this->fetch($this->action_view));
		}
		
		//
		echo $this->fetch($this->layout);
	}


	//
	// \bledo\mvc\view\View implementation
	//

	/**
	 *
	 * @return string
	 */
	public function get($k)
	{
		return $this->phtml->get($k);
	}

	/**
	 *
	 * @param string $k
	 * @param mixed $k
	 */
	public function assign($k, $v=null) {
		$this->phtml->assign($k, $v);
	}

	/**
	 *
	 * @param string $file The template to parse
	 * @return string
	 */
	public function fetch($file) {
		return $this->phtml->fetch($file);
	}

	/**
	 * Checks if a value is set
	 *
	 * @param string $k
	 * @return bool
	 */
	public function is_set($k) {
		return $this->phtml->is_set($k);
	}

	/**
	 * Get all the values
	 *
	 * @return array
	 */
	public function getVals() {
		return $this->phtml->getVals();
	}
}
