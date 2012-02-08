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

class FirePhpWriter implements Writer
{
	private $_fp = false; // FirePhp
	private $_level;

	public function __construct()
	{
		$this->_level = Logger::parseLevel('all');
	}

	public function setLevel($level) {
		$this->_level = Logger::parseLevel($level);
	}

	public function write($int_level, $str_level, $args)
	{

		if (!$this->_fp) {
			$this->_fp = FirePHP::getInstance(true);
			if (!$this->_fp) {
				return;
			}
		}


		if (!Logger::isLoggable($this->_level, $int_level))
		{
			return;
		}


		//
		// levels
		//
		switch($str_level)
		{
			case 'error':
				$this->_fp->error(@$args[0], @$args[1], @$args[2]);
				break;
			case 'warning':
				$this->_fp->warn(@$args[0], @$args[1], @$args[2]);
				break;
			case 'info':
				$this->_fp->info(@$args[0], @$args[1], @$args[2]);
				break;

			case 'debug':
			default: 
				$this->_fp->log(@$args[0], @$args[1], @$args[2]);
				break;
		}
	}
}
