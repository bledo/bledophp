<?php
use off\controller\ActionController;
use off\request\Request;
use off\response\PhtmlResponse;

class ErrorController extends ActionController
{
	public function errorResponse(Request $req, \Exception $e)
	{
		$resp = new PhtmlResponse();
		try {
			throw $e;
	       	}
		catch (Exception $e)
		{
			$resp->view->content = (string) $e->getMessage();
		}

		return $resp;
	}
}
