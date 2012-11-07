<?php

abstract class Spatula_XmlModel_Abstract extends ArrayObject
{
    protected $_dom;
    protected $_xmlSource;
    protected $_xmlName;
    protected $_rootKey = 'site';
    protected $_xpath;
    
    public function __construct() {
        $this->_init(); 
    }
    
    public function toArray($childrenAsArray = false)
    {
        $array = (array) $this;
        if($childrenAsArray)
        {
            foreach ($array AS $k => $v)
            {
                if(method_exists($v, 'toArray'))
                {
                    $array[$k] = $v->toArray();
                }

            }
        }
        
        
        return $array;
    }
    
    protected function _init()
    {
        if(is_null($this->_xmlName))
        {
            throw new Exception('XMLModels must have a source path set on them.');
        }
        $this->_xmlSource = file_get_contents(XML_PATH . DIRECTORY_SEPARATOR . $this->_xmlName . '.xml');
        $this->getDom();
    }
    
    /**
     *
     * @return DOMDocument 
     */
    public function getDom()
    {
        if(is_null($this->_dom))
        {
            $this->_dom = new DOMDocument('1.0', 'UTF-8');
            $this->_dom->loadXML($this->_xmlSource);
            $this->_setArray();
        }
        
        return $this->_dom;
    }
    
    /**
     *
     * @return DOMXPath 
     */
    public function getXpath()
    {
        if(is_null(($this->_xpath)))
        {
            $this->_xpath = new DOMXPath($this->getDom());
        }
        
        return $this->_xpath;
    }
    
    protected function _setArray()
    {
        foreach ($this->getXpath()->query('/site/*') AS $node)
        {
            $nodeName = $node->nodeName;
            $className = $this->getModelClassName($nodeName);            
            if(class_exists($className))
            {
                $this[$nodeName] = new $className($node, $this);
            } else {
                $this[$nodeName] = new Spatula_XmlModel_Node($node, $this);
            }
        }
    }
    
    public function getModelClassName($nodeName)
    {
        $classNameParts = array(
            $this->_xmlName,
            'XmlModel',
            'Node',
            $nodeName
        );
        
        $classNameParts = array_map('ucfirst', $classNameParts);
        $className = implode('_', $classNameParts);
        
        return $className;
    }
    
}