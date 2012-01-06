<?php
namespace off;

class PhtmlView implements View
{
	/**
	 * @var array
	 */
	private $vals = array();
	
	public function __construct($view_dir)
	{
		$this->view_dir = $view_dir;
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
		include($this->view_dir .'/'. trim($file, '/'));
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
