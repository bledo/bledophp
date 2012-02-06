<?php
namespace bledo\mvc;

interface Controller {
	/**
	 * 
	 * @param \bledo\mvc\Request $req
	 * @return bledo\mvc\response\Response
	 */
	public function getResponse(\bledo\mvc\Request $req);
	
	/**
	 * 
	 * @param \bledo\mvc\Request $req
	 * @param \Exception $e
	 * @return bledo\mvc\response\Response
	 */
	public function errorResponse(\bledo\mvc\Request $req, \Exception $e);
}

