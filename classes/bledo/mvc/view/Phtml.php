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

namespace bledo\mvc\view;

class Phtml implements View
{
	/**
	 * @var string Directory where all the view templates are stored
	 */
	private $view_dir;

	/**
	 * @var array
	 */
	private $vals = array();

	public function __construct($viewDir)
	{
		$this->view_dir = $viewDir;
	}

	/**
	 *
	 * @return string
	 */
	public function get($k)
	{
		return @$this->vals[$k];
	}

	/**
	 *
	 * @return string
	 */
	public function __get($k) {
		return $this->get($k);
	}


	/**
	 *
	 * @param string $k
	 * @param mixed $k
	 */
	public function assign($k, $v=null) {
		$this->vals[$k] = $v;
	}

	/**
	 *
	 * @param string $k
	 * @param mixed $k
	 */
	public function __set($k, $v) {
		$this->assign($k, $v);
	}

	/**
	 *
	 * @param string $file The template to parse
	 * @return string
	 */
	public function fetch($file) {
		ob_start();
		$inc_file = $this->view_dir .'/'. trim($file, '/');
		if (is_file($inc_file))
		{
			include($inc_file);
		}
		return ob_get_clean();
	}

	/**
	 * Checks if a value is set
	 *
	 * @param string $k
	 * @return bool
	 */
	public function is_set($k) {
		return isset($this->vals[$k]);
	}

	/**
	 * Get all the values
	 *
	 * @return array
	 */
	public function getVAls() {
		return $this->vals;
	}
}


