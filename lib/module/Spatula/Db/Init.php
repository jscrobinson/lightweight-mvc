<?php
class Spatula_Db_Init
{
	private static $_instance;
	protected $_initialized = false;
	protected $_modelFormatter;
	
	/**
	 * 
	 * @return Spatula_Db_Init
	 */
	public static function getInstance()
	{
		if( is_null(self::$_instance) )
		{
			self::$_instance = new Spatula_Db_Init;
		}
		
		return self::$_instance;
	}
	
	public function init()
	{
		if(!$this->_initialized)
		{
			$this->_init();
		}
	}
	
	protected function _init()
	{
		$dsn = sprintf('mysql:dbname=%s;host=%s', DB_NAME, DB_HOST);
		
		
	}	
}