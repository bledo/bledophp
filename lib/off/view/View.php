<?php
namespace off\view;

interface View
{
	/**
	 *
	 * @param string $k
	 * @return mixed
	 */
	public function get($k);
	
	/**
	 *
	 * @param string $k
	 * @param mixed $k
	 */
	public function assign($k, $v);

	/**
	 *
	 * @param string $file The template to parse
	 * @return string
	 */
	public function fetch($file);

	/**
	 * Checks if a value is set
	 *
	 * @param string $k
	 * @return bool
	 */
	public function is_set($k);
}
