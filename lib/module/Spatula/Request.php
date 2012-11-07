<?php
class Spatula_Request {
    
    
    private static $_instance;
    protected $_get = array();
    
    public function __construct() {
        $this->_init();
    }
    
    public function _init()
    {
        $requestUri = $_SERVER['REQUEST_URI'];        
        $parseString = parse_url($requestUri);
        
        $pathParts = explode('/', $parseString['path']);
        array_shift($pathParts);
        $this->_get = $_GET;
        $pathParts = array_filter($pathParts, array($this, 'validateParam'));
        if(count($pathParts) == 1 && strlen(current($pathParts)) != 0)
        {
            if(!isset($this->_get['action']))
            {
                $this->_get['action'] = array_shift($pathParts);
            }
            
        } elseif(count($pathParts > 1)) {
            if(!isset($this->_get['module']))
            {
                $this->_get['module'] = array_shift($pathParts);
            }
            if(!isset($this->_get['action']))
            {
                $this->_get['action'] = array_shift($pathParts);
            }            
        }
        
        foreach ($pathParts AS $key => $part)
        {
            if(fmod($key, 2) == 0)
            {
                $param = $part;
            } else {
                if(!isset($this->_get[$key]))
                {
                    $this->_get[$param] = $part;
                }
            }
        }
        
    }
    
    public function validateParam($param)
    {
        $valid = true;
        if(empty($param))
        {
           $valid = false;
        }
        
        return $valid;
    }


    /**
     *
     * @return Spatula_Request 
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Spatula_Request;
        }
        
        return self::$_instance;
    }
    
    public function getAction()
    {
        return $this->get('action', null, 'get');
    }
    
    public function getModule()
    {
        return $this->get('module', null, 'get');
    }
    
    public function get($key, $default = null, $hash = 'get')
    {
        $hash = strtolower($hash);
        
        switch($hash)
        {
            case 'post':
                $requestArray = $_POST;
                break;
            default:
            case 'get':
                $requestArray = $this->_get;
                break;
            
            case 'files':
                $requestArray = $_FILES;
                break;
            
            case 'request':
                $requestArray = $_REQUEST;
                break;
        }
                
        return isset($requestArray[$key]) ? $requestArray[$key] : $default;
    }
}