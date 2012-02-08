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

class FileWriter implements Writer
{
	protected $_level;
	protected $_file;
	protected $_fd = false;
	protected $_max_size;

	public function __construct()
	{
		$this->_level = Logger::parseLevel('warning');
		$this->_max_size = 1024 * 1024 * 1024; // 1G file
	}

	public function setFile($file)
	{
		$this->_file = $file;
	}

	public function setMaxSize($bites)
	{
		$this->_max_size = $bites;
	}

	public function __destruct()
	{
		if ($this->_fd)
		{
			fclose($this->_fd);
		}
	}

	public function write($int_level, $str_level, $args)
	{
		//
		if (!Logger::isLoggable($this->_level, $int_level))
		{
			return;
		}

		//
		if (!$this->_fd) {
			$this->_fd = @fopen($this->_file, 'a');
			if (!$this->_fd) { return; }
		}

		//
		$this->_checkAndRotateFile();


		//
		$msg = '';
		foreach ($args as $arg)
		{
			if ($arg instanceof Exception) {
				$msg .= ' ' . (string) $arg;
			}
			else if (is_array($arg) || is_object($arg)) {
				$msg .= ' ' . print_r($arg, true);
			}
			else {
				$msg .= ' ' . (string) $arg;
			}
		}

		fwrite($this->_fd, date('Y-m-d H:i:s') .' ['. strtoupper($str_level).']' . $msg."\n");
	}

	private function _checkAndRotateFile()
	{
		if (!$this->_fd) {
			return;
		}

		//
		$arr = fstat($this->_fd);
		if (!$arr) {
			return;
		}

		//
		$size = $arr['size'];
		if ($size < $this->_max_size) {
			return;
		}

		//
		fclose($this->_fd);
		rename($this->_file, $this->_file . '-' . date('Y-m-d_H_i_s'));
		$this->_fd = fopen($this->_file, 'a');
	}

	public function setLevel($level)
	{
		$this->_level = Logger::parseLevel($level);
	}
}
