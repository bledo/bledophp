<?php
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
