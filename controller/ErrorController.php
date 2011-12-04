<?php
class ErrorController extends \off\ActionController
{
	public function errorResponse($req, $e)
	{
		$resp = new ViewResponse();
		$resp->view->content = (string) $e;
		return $resp;
	}
}
