<?php
abstract class Spatula_Controller_Action_Abstract
{
    protected $_render = true;
    protected $_controller;
    protected $_template_name;
    protected $_template;
    
    abstract public function execute();
    
    public function __construct(Spatula_Controller $controller) {
        $this->_controller = $controller;
    }
    
    /**
     *
     * @return Spatula_Controller 
     */
    public function getController()
    {
        return $this->_controller;
    }
    
    /**
     * @return Spatula_Template
     */
    public function getTemplate()
    {
        if(is_null($this->_template))
        {
            $this->_template = new Spatula_Template($this);
        }
        
        return $this->_template;
    }
    
    public function getTemplateName()
    {
        if(is_null($this->_template_name))
        {
            $this->_template_name = sprintf('%s/%s', $this->getController()->getModuleName(), $this->getController()->getActionName());
        }
        
        return $this->_template_name;
    }
    
    /**
     *
     * @param type $templateName
     * @return \Spatula_Controller_Action_Abstract 
     */
    public function setTemplateName($templateName)
    {
        $this->_template_name = $templateName;
        return $this;
    }

    protected function _doExecute()
    {
        $this->_preExecute();
        $this->execute();
        $this->_postExecute();
    }
    
    public function disableRender()
    {
        $this->_render = false;
    }
    
    public function enableRender()
    {
        $this->_render = true;
    }
    
    public function dispatch()
    {
        $this->_preDispatch();
        $this->_doExecute();
        if($this->_render == true)
        {
            $this->getTemplate()->render(true);
        }
        $this->_postDispatch();
    }
    
    protected function _preExecute()
    {
        $this->getTemplate();
    }
    
    protected function _postExecute()
    {
        
    }
    
    protected function _preDispatch()
    {
        
    }
    
    protected function _postDispatch()
    {
        
    }
}