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

namespace bledo;

class Autoload
{
	private static $fwp;
	private static $len;
	private static $defaultAutoLoad;
	private static $throwException;

	public static function enable($defaultAutoLoad=false, $throwException=false)
	{
		self::$defaultAutoLoad = $defaultAutoLoad;
		self::$throwException = $throwException;
		self::$fwp = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR); // framework path
		self::$len = strlen(__NAMESPACE__);

		spl_autoload_register(array('\bledo\Autoload', 'autoload'));
	}

	public static function autoload($class)
	{
		//$alf = function($class) use ($fwp, $len, $defaultAutoLoad, $throwException) {
		$found = false;
		if (__NAMESPACE__ == substr($class, 0, self::$len))
		{
			// framework class
			$file = self::$fwp .DIRECTORY_SEPARATOR.str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
			if (is_file($file)) { $found = true; }
		}
		else
		{
			// non framework class
			$file = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';

			if (!self::$defaultAutoLoad) { return false; }
			foreach (explode(PATH_SEPARATOR, get_include_path()) as $path)
			{
				if (is_file($path.DIRECTORY_SEPARATOR.$file)) {
					$found = true;
				}
			}
		}

		if ($found)
		{
			include($file);
		}
		else
		{
			if (self::$throwException) {
				throw new \Exception('Class '.$class.' not found');
			}
		}

		//};
	}

}
