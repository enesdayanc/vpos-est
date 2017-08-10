<?php

namespace Enesdayanc\VPosEst\Helper;


use DOMDocument;

class XMLBuilder extends DOMDocument
{
    public $rootElement;

    public function __construct($tag = "CC5Request")
    {
        parent::__construct("1.0");
        $element = $this->createElement($tag);
        $this->rootElement = $element;
        $this->appendChild($this->rootElement);
    }

    public function root()
    {
        return $this->rootElement;
    }

    public function createElementWithTextNode($tagName, $nodeValue)
    {
        if ($nodeValue == null) {
            $nodeValue = "";
        }
        $element = $this->createElement(strval($tagName));
        $node = $this->createTextNode(strval($nodeValue));
        $element->appendChild($node);
        return $element;
    }

    public function createElementsWithTextNodes($arguments)
    {
        $resultArray = array();
        foreach ($arguments as $k => $v) {
            array_push($resultArray, $this->createElementWithTextNode($k, $v));
        }
        return $resultArray;
    }

    public function appendListOfElementsToElement($element, $elements)
    {
        /* Appends list of DOM elements to the given DOM element. */
        foreach ($elements as $ele) {
            $element->appendChild($ele);
        }
    }

    public function __toString()
    {
        return $this->saveXML();
    }

    public static function get_data($xmlObj, $tag)
    {
        $elements = $xmlObj->getElementsByTagName($tag);
        if ($elements->length > 0) {
            $item = $elements->item(0);
            $childiren = $item->childNodes;
            if ($childiren->length > 0) {
                return $childiren->item(0)->nodeValue;
            }
            return "";
        }
        return "";
    }
}