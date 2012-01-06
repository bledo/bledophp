<?php
namespace off;



interface Controller {
	/**
	 * 
	 * @param Request $req
	 * @return Response
	 */
	public function getResponse(Request $req);
	
	/**
	 * 
	 * @param Request $req
	 * @param \Exception $e
	 * @return Response
	 */
	public function errorResponse(Request $req, \Exception $e);
}

