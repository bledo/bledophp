<?php
namespace off\response;

class PhtmlResponse extends AbstractResponse
{
	/**
	 * 
	 * @var string
	 */
	public $layout = 'layout.php';
	
	/**
	 * 
	 * @var off\view\View
	 */
	public $view;
	
	/**
	 * 
	 * @var string
	 */
	public $actionView = false;
	
	/**
	 * 
	 * @param string $actionView Path to view file
	 * @param string $layout Layout file path
	 */
	public function __construct($actionView=null, $layout=null)
	{
		$this->view = new \off\view\PhtmlView();
		if (!empty($actionView)) {
			$this->actionView = $actionView;
		}
		
		//
		if (!empty($layout)) {
			$this->layout = $layout;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see off\response.Response::respond()
	 */
	public function respond(\off\request\Request $request)
	{
		//
		$this->_sendHeaders();
		
		//
		if (!$this->view->is_set('content'))
		{
			if ($this->actionView) {
				$view_file = $this->actionView;
			} else {
				$view_file = $request->getController().'/'.$request->getAction().'.php';
			}
			
			if (file_exists($view_file))
			{
				$this->view->assign('content', $this->view->fetch($view_file));
			}
		}

		//
		echo $this->view->fetch($this->layout);
	}
}
