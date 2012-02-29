<?php
namespace bledo\test;

class RedirectResponse extends PhpUnit
{
	public function testBody()
	{
		$req = new Request('http://localhost/index/index');
		$resp = new \bledo\mvc\response\Redirect('/some/address');

		$this->assertEquals($resp->getBody($req),  '');
	}

	public function testHeader()
	{
		$req = new Request('http://localhost/index/index');

		$resp = new \bledo\mvc\response\Redirect('/some/address');

		// find content-type
		$found = false;
		foreach ($resp->getHeaders($req) as $h) {
			if ($h == 'Location: /some/address') {
				$found = true;
			}
		}
		$this->assertTrue($found);
	}
}
