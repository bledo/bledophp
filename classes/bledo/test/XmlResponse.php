<?php
namespace bledo\test;

class XmlResponse extends PhpUnit
{
	public function testBody()
	{
		$req = new Request('http://localhost/index/index');
		$resp = new \bledo\mvc\response\Xml(array('a'=>'b', 'c'=>'d', 'e'=>array('f'=>'g')));


		$xml = simplexml_load_string( $resp->getBody($req) );

		$this->assertEquals($xml->a ,'b');
		$this->assertEquals($xml->c, 'd');
		$this->assertEquals($xml->e->f, 'g');
	}

	public function testHeader()
	{
		$req = new Request('http://localhost/index/index');

		$resp = new \bledo\mvc\response\Xml(array('a'=>'b'));

		// find content-type
		$found = false;
		foreach ($resp->getHeaders($req) as $h) {
			if ($h == 'Content-Type: application/xml') {
				$found = true;
			}
		}
		$this->assertTrue($found);
	}
}
