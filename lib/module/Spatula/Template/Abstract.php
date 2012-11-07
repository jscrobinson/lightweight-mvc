<?php
abstract class Spatula_Template_Abstract extends ArrayObject {
    
    protected $_action;
    protected $_template_name;
	
	protected $_elements = array();
    
    public function __construct(Spatula_Controller_Action_Abstract $action, $array = array()) {
        $this->_action = $action;
    }
    
    public function getAction()
    {
        return $this->_action;
    }
    
    public function getTemplateName()
    {
        if(is_null($this->_template_name))
        {
            $this->_template_name = $this->getAction()->getTemplateName();
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
    
    public function getTemplatePath($withExtension = true)
    {
        $templateName = $this->getTemplateName();
		if($withExtension)
		{
			$templateName = $templateName . '.phtml';
		} else {
			$templateName = $templateName;
		}
        $templatePath = TEMPLATE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $templateName );
        
        return $templatePath;
    }
    
    public function render($echo = false)
    {
        if(!$echo)
        {
            ob_start();
        }
        
        include $this->getTemplatePath();
        
        if(!$echo)
        {
            $contents = ob_get_clean();
            return $contents;
        }
    }
	
	/**
	 * 
	 * @param type $id
	 * @param type $tagName
	 * @param type $attributes
	 * @return \Spatula_Template_Abstract
	 */
	public function addElement($id, $tagName, $attributes)
	{
		if(!isset($this->_elements[$tagName]))
			$this->_elements[$tagName] = array();
		
		$this->_elements[$tagName][$id] = $attributes;
		return $this;
	}
	
	/**
	 * 
	 * @param type $tagName
	 * @param type $id
	 * @return \Spatula_Template_Abstract
	 */
	public function removeElement($tagName, $id)
	{
		if(isset($this->_elements[$tagName]) && isset($this->_elements[$tagName][$id]))
			unset($this->_elements[$tagName][$id]);
		
		return $this;
	}
	
	public function addScript($id, $source)
	{
		$attribs = array();
		$attribs['type'] = 'text/javascript';
		$attribs['src'] = $source;
		return $this->addElement($id, 'script', $attribs);
	}
	
	public function removeScript($id)
	{
		return $this->removeElement('script', $id);
	}
	
	public function addLink($id, $href = null, $rel = 'stylesheet')
	{
		$attribs = array();
		if(!is_null($href))
			$attribs['href'] = $href;
		
		$attribs['rel'] = $rel;		
		
		return $this->addElement($id, 'link', $attribs);
	}
	
	public function removeLink($id)
	{
		return $this->removeElement('script', $id);
	}
	
	public function renderElements($tagName)
	{		
		if($GLOBALS['minify_assets'] && $this->_canMinify($tagName))
		{
			return $this->renderMinifiedAssets($tagName);
		}
		
		if(!isset($this->_elements[$tagName]))
			return null;
		
		$htmlArray = array();
		
		foreach ($this->_elements[$tagName] AS $id => $attribs)
		{			
			if(!isset($attribs['id']))
				$attribs['id'] = '_script_' . $tagName . '_' . $id;
			
			array_walk($attribs, array($this, 'arrayToAttribsStringCallback'));
			
			if($this->_isSelfClosing($tagName))
			{
				$htmlArray[$id] = sprintf('<%1$s %2$s />', $tagName, implode(' ', $attribs));
			} else {
				$htmlArray[$id] = sprintf('<%1$s %2$s></%1$s>', $tagName, implode(' ', $attribs));
			}			
		}
		
		return '<!-- START OF RENDERED ' . strtoupper($tagName) . ' TAGS -->' . PHP_EOL . implode(PHP_EOL, $htmlArray) . PHP_EOL . '<!-- END OF RENDERED ' . strtoupper($tagName) . ' TAGS -->' . PHP_EOL;
	}
	
	public function renderMinifiedAssets($tagName)
	{
		if(!isset($this->_elements[$tagName]))
			return null;
		
		$htmlArray = array();
		$sourceArray = array();
		$elementAttribs = array();
		
		switch($tagName)
			{
				case 'link':
					$sourceKey = 'href';
					$elementAttribs['rel'] = 'stylesheet';
					break;
				default:
					$sourceKey = 'src';
					$elementAttribs['type'] = 'text/javascript';
					break;
			}
		
		foreach ($this->_elements[$tagName] AS $id => $attribs)
		{						
			if(isset($attribs[$sourceKey]))
				$sourceArray[] = $attribs[$sourceKey];			
		}
		
		array_walk($elementAttribs, array($this, 'arrayToAttribsStringCallback'));
		
		if(empty($sourceArray))
			return '<!-- NO '.ucfirst ( $tagName ) . ' ELEMENTS WHERE ADDED -->';
		
		if($this->_isSelfClosing($tagName))
		{
			$tag = sprintf('<%1$s %4$s %2$s="/min/?f=%3$s" />',$tagName, $sourceKey, implode(',', $sourceArray), implode(' ', $elementAttribs));
		} else {
			$tag = sprintf('<%1$s %4$s %2$s="/min/?f=%3$s"></%1$s>',$tagName, $sourceKey, implode(',', $sourceArray), implode(' ', $elementAttribs));
		}
		
		return '<!-- START OF RENDERED ' . strtoupper($tagName) . ' TAGS -->' . PHP_EOL . $tag . PHP_EOL . '<!-- END OF RENDERED ' . strtoupper($tagName) . ' TAGS -->' . PHP_EOL;
	}
	
	public function arrayToAttribsStringCallback(&$value, &$key)
	{		
		$value = sprintf('%s="%s"', $key, $value);
	}
	
	public function _isSelfClosing($tagName)
	{
		switch($tagName)
		{
			case 'link':
			case 'img':
			case 'br':
				return true;
				break;
			default :
				return false;
				break;
		}
	}
	
	public function _canMinify($tagName)
	{
		switch($tagName)
		{
			case 'link':
			case 'script':			
				return true;
				break;
			default :
				return false;
				break;
		}
	}
	
	/**
	 * 
	 * @param string $name
	 * @return \Spatula_Template_Abstract
	 */
	public function partial($name, $render = true)
	{
		if(!$render)
		{
			ob_start();
		}
		include $this->getPartialPath($name);
		if(!$render)
		{
			$contents = ob_get_contents();
			ob_clean();
			return $contents;
		}
		
		
	}
	
	/**
	 * 
	 * @param string $name
	 * @return string
	 */
	public function getPartialPath($name)
	{
		$path = $this->getTemplatePath(false);
		$partialPath = $path . DIRECTORY_SEPARATOR . $name . '.phtml';
		return $partialPath;
	}
}