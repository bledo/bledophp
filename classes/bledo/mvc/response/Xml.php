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

namespace bledo\mvc\response;

class Xml extends \bledo\mvc\response\AbstractResponse
{
	protected $_xml;
	public function __construct($xml)
	{
		$this->_xml = $xml;
	}
	
	/**
	 * 
	 * @param \bledo\mvc\Request $request
	 */
	public function respond(\bledo\mv\Request $request)
	{
		$this->setHeader("Content-type: text/xml");
		$this->_sendHeaders();
		
		if (is_array($this->_xml))
		{
			if (count($this->_xml) > 1)
			{
				//echo $this->_arrayToXml($this->_xml, new \SimpleXMLElement('<root/>'))->asXML();
				$root = 'root';
				$arr = $this->_xml;
			}
			else
			{
				list($root, $arr) = each($this->_xml);
			}
			echo $this->_arrayToXml($arr, new \SimpleXMLElement('<'.$root.'/>'))->asXML();
		}
		else if ($this->_xml instanceof \SimpleXMLElement )
		{
			echo $this->_xml->asXML();
		}
		else if(is_string($this->_xml))
		{
			$xml = simplexml_load_string($this->_xml);
			echo $xml->asXML();
		}
		else
		{
			throw new \Exception("Unknown XML format");
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $arr
	 * @param \SimpleXMLElement $xml
	 */
	protected function _arrayToXml(array $arr, \SimpleXMLElement $xml)
	{
		foreach ($arr as $k => $v) {
			is_array($v)
			? $this->_arrayToXml($v, $xml->addChild($k))
			: $xml->addChild($k, $v);
		}
		return $xml;
	}
}

