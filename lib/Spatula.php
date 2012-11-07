<?php
class Spatula
{
    private static $_instance;
    public static $autoloadNamespaces = array();
    
    /**
     *
     * @return Spatula 
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Spatula;
        }
        
        return self::$_instance;
    }
    
    public function autoload($classname)
    {
        $namespace = preg_replace('/^([^_]*)_(.*)/', '$1', $classname);
        if(isset(Spatula::$autoloadNamespaces[$namespace]))
        {
            $pathPrefix = Spatula::$autoloadNamespaces[$namespace] . DIRECTORY_SEPARATOR;
            $path = $pathPrefix . implode(DIRECTORY_SEPARATOR, explode('_', $classname)) . '.php';
            if(file_exists($path))
            {
                require_once $path;
            }
            
        }
        
    }
}