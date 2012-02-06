<?php
/*
Copyright 2011,2012 Ricardo Ramirez, The ClickPro.com LLC

This file is part of Bledo Framework.

Bledo Framework is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Bledo Framework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Bledo Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

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
