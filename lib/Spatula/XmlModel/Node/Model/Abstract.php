<?php
abstract class Spatula_XmlModel_Node_Model_Abstract extends Spatula_XmlModel_Node_Abstract
{
    public function getByKey($key)
    {                
        return $this->getDataSource()->offsetGet('content')->getByKey($key);
    }
    
    public function toArray()
    {
        $array = array();
        foreach (parent::toArray() AS $id => $val)
        {
            $node = $this->getDataSource()->offsetGet('content')->getByKey($id);
            if(!is_null($node))
            {
                $array[$id] = $this->getDataSource()->offsetGet('content')->getByKey($id);
            }            
        }
        
        return $array;
    }
}