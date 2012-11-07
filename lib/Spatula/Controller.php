<?php
class Spatula_Controller {
    
    protected $_action = 'index';
    protected $_module = 'index';
    protected $_request;
    
    public function __construct($module) {
        $this->_module = strtolower($module);
    }


    protected function _preDispatch()
    {
        
    }
    
    protected function _postDispatch()
    {
        
    }
    
    public function getActionClass()
    {
        $actionName = Spatula_Util::camelize($this->getActionName(), true);
        $moduleName = Spatula_Util::camelize($this->getModuleName(), true);
        $classNameArray = array(
            $moduleName,
            'controller',
            'action',
            $actionName
        );
        
        $classNameArray = array_map('ucfirst', $classNameArray);
        
        $className = implode('_', $classNameArray);
        
        if(!class_exists($className))
        {
            $className = 'Spatula_Controller_Action_404';
        }
        
        return new $className($this);
    }
    
    public function getActionName()
    {
        return is_null($this->getRequest()->getAction()) ? $this->_action : $this->getRequest()->getAction();
    }
    
    public function getModuleName()
    {
        return is_null($this->getRequest()->getModule()) ? $this->_module : $this->getRequest()->getModule();
    }
    
    /**
     *
     * @return Spatula_Request 
     */
    public function getRequest()
    {
        return Spatula_Request::getInstance();
    }
    
    public function dispatch()
    {        
        $this->_preDispatch();
        $this->getActionClass()->dispatch();        
        $this->_postDispatch();
    }
}