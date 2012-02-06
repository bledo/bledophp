<?php
namespace controller;

class Error extends \bledo\mvc\BaseController
{
	public function errorResponse(\bledo\mvc\Request $req, \Exception $e)
	{
		$headers = array();
		try
		{
			throw $e;
		}
		catch (\bledo\mvc\PageNotFoundException $e)
		{
			$headers[] = $_SERVER['SERVER_PROTOCOL'].' 404 Not Found';
			$view = 'error/404.phtml';
			$error = '';
		}
		catch (\Exception $e)
		{
			$headers[] = $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error';
			$view = 'error/500.phtml';
			//$error = (string) $e;
			$error = '';
		}

		// setup layout & view
		$resp = new \bledo\mvc\response\Phtml(VIEWDIR, 'error.phtml', $view);

		// error str
		$resp->assign('error', $error);

		// headers
		foreach ($headers as $header) {
			$resp->setHeader($header);
		}

		return $resp;
	}
}
