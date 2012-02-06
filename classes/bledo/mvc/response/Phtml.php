<?php
namespace bledo\mvc\response;


class Phtml extends AbstractResponse implements \bledo\mvc\View
{
	/**
	 * 
	 * @var string
	 */
	public $layout;
	
	/**
	 * 
	 * @var string
	 */
	public $action_view;
	
	/**
	 * 
	 * @param string $viewDir Path to directory containing view files
	 * @param string $layout Layout file path
	 * @param string $view Path to view file
	 */
	public function __construct($viewDir, $layout='layout.phtml', $view=null)
	{
		$this->view_dir = $viewDir;
		$this->layout = $layout;
		$this->action_view = $view;
	}

	/**
	 * (non-PHPdoc)
	 * @see Response::respond()
	 */
	public function respond(\bledo\mvc\Request $request)
	{
		//
		$this->_sendHeaders();
		
		//
		if (!$this->is_set('main'))
		{
			if (empty($this->action_view)) {
				$this->action_view = $request->getController().'/'.$request->getAction().'.phtml';
			}
			
			$this->assign('main', $this->fetch($this->action_view));
		}
		

		//
		echo $this->fetch($this->layout);
	}



	//
	// \bledo\mvc\View implementation
	//

	/**
	 * @var string Directory where all the view templates are stored
	 */
	private $view_dir;

	/**
	 * @var array
	 */
	private $vals = array();

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
	public function assign($k, $v=null) {
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
		$inc_file = $this->view_dir .'/'. trim($file, '/');
		if (is_file($inc_file))
		{
			include($inc_file);
		}
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
