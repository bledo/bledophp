<?php
namespace bledo\mvc;

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
	public function assign($k, $v=null);

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

	/**
	 * Get all the values
	 *
	 * @return array
	 */
	public function getVals();
}
