<?php
namespace off\view;

class PhtmlView implements View
{
	/**
	 * @var array
	 */
	private $vals;
	
	public function __construct($assoc=array())
	{
		$this->vals = $assoc;
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
	public function assign($k, $v) {
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
		include($file);
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
}
