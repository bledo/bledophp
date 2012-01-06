<?php
namespace off;


class ViewResponse extends AbstractResponse
{
	/**
	 * 
	 * @var string
	 */
	public $layout;
	
	/**
	 * 
	 * @var View
	 */
	public $view;
	
	/**
	 * 
	 * @var string
	 */
	public $action_view;
	
	/**
	 * 
	 * @param string $action_view Path to view file
	 * @param string $layout Layout file path
	 */
	public function __construct(View $view, $layout, $action_view=null)
	{
		$this->view = $view;
		$this->layout = $layout;
		$this->action_view = $action_view;
	}

	/**
	 * (non-PHPdoc)
	 * @see Response::respond()
	 */
	public function respond(Request $request)
	{
		//
		$this->_sendHeaders();
		
		//
		if (!$this->view->is_set('content'))
		{
			if (empty($this->action_view)) {
				$this->action_view = $request->getController().'/'.$request->getAction().'.php';
			}
			
			$this->view->assign('content', $this->view->fetch($this->action_view));
		}
			
		//
		echo $this->view->fetch($this->layout);
	}
}
