<?php
class Registry
{
	private $_arr = array();

	private static $_reg;
	public static function getInstance()
	{
		return self::$_reg;
	}

	private function __construct()
	{
	}

	public function set($k, $v)
	{
		self::$_arr[$k] = $v;
	}

	public function get($k)
	{
		return self::$_arr[$k];
	}
}
