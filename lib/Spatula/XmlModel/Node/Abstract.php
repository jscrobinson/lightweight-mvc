<?php

class Spatula_XmlModel_Node_Abstract implements ArrayAccess {

    protected $_node;
    protected $_dom;
    protected $_xpath;
    protected $_data;
    protected $_key = 'id';
    protected $_dataSource;
    protected $_attributePrefix = '__';

    public function __construct($node, $dataSource) {
        $this->_init($node, $dataSource);
    }

    public function getByKey($key) {
        if(isset($this->_data[$key]))
        {
            return $this->_data[$key];
        }
        return null;
    }

    /**
     *
     * @return DOMDocument 
     */
    public function getDom() {
        if (is_null($this->_dom)) {
            $this->_dom = $this->getNode()->ownerDocument;
        }

        return $this->_dom;
    }

    public function getXpath() {
        if (is_null($this->_xpath)) {
            $this->_xpath = new DOMXPath($this->getDom());
        }

        return $this->_xpath;
    }

    /**
     *
     * @return DOMNode 
     */
    public function getNode() {
        return $this->_node;
    }

    protected function _init($node, $dataSource) {
        $this->_node = $node;
        $this->_dataSource = $dataSource;
        $this->_buildArray();
    }

    protected function _buildArray() {
        $xpath = $this->getNode()->getNodePath();
        $nodeArray = array();
        $nodeArray = $this->_nodeToArray($this->getNode());
        $this->_data = array();
        foreach (current($nodeArray) AS $key => $value) {
            foreach ($value AS $k => $v) {                
                if (empty($v)) {
                    $value[$k] = null;
                }
            }
            if (isset($value[$this->_attributePrefix . $this->_key])) {
                $id = $value[$this->_attributePrefix . $this->_key];
                unset($value[$this->_attributePrefix . $this->_key]);
                $this->_data[$id] = $value;
            } else {
                $this->_data[] = $value;
            }
        }
    }

    /**
     *
     * @return Spatula_XmlModel_Abstract 
     */
    public function getDataSource() {
        return $this->_dataSource;
    }

    public function toArray() {
        return $this->_data;
    }

    function _nodeToArray($node) {
        $occurance = array();
        $result = array();
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                @$occurance[$child->nodeName]++;
            }
        }


        if (in_array($node->nodeType, array(XML_TEXT_NODE))) {
            $result = utf8_encode(html_entity_decode(htmlentities($node->nodeValue, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'ISO-8859-15'));
        } elseif ($node->hasChildNodes() && $node->childNodes->item(0)->nodeType == XML_CDATA_SECTION_NODE) {
            $result = utf8_encode(html_entity_decode(htmlentities($node->childNodes->item(0)->nodeValue, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'ISO-8859-15'));
        } else {
            if ($node->hasChildNodes()) {
                $children = $node->childNodes;
                $content = '';
                for ($i = 0; $i < $children->length; $i++) {
                    $child = $children->item($i);
                    
                    if ($child->nodeName != '#text') {
                        if ($occurance[$child->nodeName] > 1) {
                            $result[$child->nodeName][] = $this->_nodeToArray($child);
                        } else {
                            $result[$child->nodeName] = $this->_nodeToArray($child);
                        }
                    } else if ($child->nodeName == '#text' || $child->nodeType == XML_CDATA_SECTION_NODE) {
                        $text = utf8_encode($child->nodeValue);


                        if (trim($text) != '') {
                            $result = $text;
                        }
                    }
                }
            }

            if ($node->hasAttributes()) {
                $attributes = $node->attributes;

                if (!is_null($attributes)) {
                    foreach ($attributes as $key => $attr) {
                        $result[$this->_attributePrefix . $attr->name] = $attr->value;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset) {
        return isset($this->_data[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset) {
        return $this->_data[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value) {
        $this->_data[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset) {
        unset($this->_data[$offset]);
    }

}