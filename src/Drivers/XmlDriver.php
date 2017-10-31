<?php

namespace Unity\Component\Config\Drivers;

use SimpleXMLElement;
use Unity\Contracts\Config\Drivers\IDriver;

/**
 * Class XmlDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class XmlDriver implements IDriver
{
    /**
     * Loads and returns the configs array.
     *
     * @param $xmlfile
     *
     * @return array
     */
    public function load($xmlfile) : array
    {
        $simpleXML = simplexml_load_file($xmlfile);

        return $this->xml2Array($simpleXML);
    }

    /**
     * Returns supported extensions.
     *
     * @return array
     */
    public function extensions() : array
    {
        return ['xml'];
    }

    /**
     * Converts xml nodes to array.
     *
     * @param SimpleXMLElement $xml
     *
     * @return array
     */
    protected function xml2Array($xml)
    {
        $data = [];

        foreach ($xml->children() as $xmlChildren) {
            $childrenName = $xmlChildren->getName();

            /*
             * If this xml node has children's
             * we're going to resolve them
             * recursively.
             */
            if (count($xmlChildren->children())) {
                /*
                 * Nodes with the same name are merged.
                 * Last children's wins.
                 */
                if (array_key_exists($childrenName, $data)) {
                    $innerData = $this->xml2Array($xmlChildren);

                    $data[$childrenName] = array_merge($data[$childrenName], $innerData);
                } else {
                    $data[$childrenName] = $this->xml2Array($xmlChildren);
                }
            } else {
                $data[$childrenName] = strval($xmlChildren);
            }
        }

        return $data;
    }
}
