<?php
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

