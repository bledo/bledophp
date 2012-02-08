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

namespace bledo\logger;

class Logger
{
	public static $levels = array(
		'off' => 0,
		'other' => 50,
		'debug' => 100,
		'info' => 200,
		'warning' => 300,
		'error' => 400,
		'all' => 1000,
	);

	protected $writers = array();

	/**
	 * 
	 * @var Logger
	 */
	protected static $_inst = null;

	/**
	 * Factory Function
	 * 
	 * @return Logger
	 */
	public static function getInstance()
	{
		if (is_null(self::$_inst))
		{
			self::$_inst = new Logger();
		} 

		return self::$_inst;
	}
	
	public function addWriter(\bledo\logger\Writer $back)
	{
		$this->writers[] = $back;
	}

	public static function parseLevel($str)
	{
		$l = @self::$levels[strtolower($str)];
		if (is_null($l))
		{
			$l = @self::$levels['other'];
		}
		return $l;
	}

	public function __call($level, $args)
	{
		$int_level = $this->parseLevel($level);
		$this->_log($int_level, $level, $args);
	}

	private function _log($int_level, $str_level, $args)
	{
		//
		foreach ($this->writers as $w) {
			$w->write($int_level, strtolower($str_level), $args);
		}
	}
	
	public function group() { $this->_log(40, 'group', func_get_args()); }
	public function groupEnd() { $this->_log(41, 'groupend', func_get_args()); }

	public function error() { $this->_log(400, 'error', func_get_args()); }
	public function err() { $this->_log(400, 'error', func_get_args()); }
	public function e() { $this->_log(400, 'error', func_get_args()); }

	public function warning() { $this->_log(300, 'warning', func_get_args()); }
	public function warn() { $this->_log(300, 'warning', func_get_args()); }
	public function w() { $this->_log(300, 'warning', func_get_args()); }

	public function info() { $this->_log(200, 'info', func_get_args()); }
	public function inf() { $this->_log(200, 'info', func_get_args()); }
	public function i() { $this->_log(200, 'info', func_get_args()); }

	public function debug() { $this->_log(100, 'debug', func_get_args()); }
	public function d() { $this->_log(100, 'debug', func_get_args()); }

	public static function isLoggable($int_cur_level, $int_level)
	{
		//
		if ($int_cur_level < 1 || $int_level < $int_cur_level)
		{
			return false;
		}
		return true;
	}
}


