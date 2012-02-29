<?php
namespace bledo\test;

class JsonResponse extends PhpUnit
{
	public function testBody()
	{
		$req = new Request('http://localhost/index/index');
		$resp = new \bledo\mvc\response\Json(array('a'=>'b', 'c'=>'d', 'e'=>array('f'=>'g')));
		$o = json_decode($resp->getBody($req));
		$this->assertTrue($o->a == 'b');
		$this->assertTrue($o->c == 'd');
		$this->assertTrue($o->e->f == 'g');
	}

	public function testHeaders()
	{
		$req = new Request('http://localhost/index/index');

		$resp = new \bledo\mvc\response\Json(array('a'=>'b'));

		// find content-type
		$found = false;
		foreach ($resp->getHeaders($req) as $h) {
			if ($h == 'Content-Type: application/json') {
				$found = true;
			}
		}
		$this->assertTrue($found);
	}
}
