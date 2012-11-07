<?php
class Spatula_Session implements ArrayAccess
{
	private static $_instance;
	
	/**
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getValue($key, $default = null)
	{
		return isset($this[$key]) ? $this[$key] : $default;
	}
	
	/**
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return \Spatula_Session
	 */
	public function setValue($key, $value)
	{
		$this[$key] = $value;
		return $this;
	}
	
	/**
	 * 
	 * @return Spatula_Session
	 */
	public static function getInstance()
	{
		if(is_null(self::$_instance))
		{
			self::$_instance = new Spatula_Session;
		}
		
		return self::$_instance;
	}
	
	public function __construct() {
		if(  session_id() == '' )
			session_start();
	}
	
	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists ($offset) {
		return isset($_SESSION[$offset]);
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
	public function offsetGet ($offset) {
		return $_SESSION[$offset];
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
	 * @return void No value is returned.
	 */
	public function offsetSet ($offset, $value) {
		$_SESSION[$offset] = $value;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void No value is returned.
	 */
	public function offsetUnset ($offset) {
		unset($_SESSION[$offset]);
	}
	
}