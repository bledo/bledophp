<?php
class ErrorController extends off_Controller
{
	public function handleError($e)
	{
		$view->content = 'Error loading : ' . "$controller/$action";
		echo $view->fetch('template.php');
	}
}
