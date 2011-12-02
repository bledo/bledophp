<?php
class ErrorController extends df_Controller
{
	public function dispatch($controller, $action,  $view)
	{
		$view->content = 'Error loading : ' . "$controller/$action";
		echo $view->fetch('template.php');
	}
}
