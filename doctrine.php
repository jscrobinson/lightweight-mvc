<?php
define("CLI", !isset($_SERVER['HTTP_USER_AGENT']));
if(CLI === false)
{
	header("HTTP/1.0 404 Not Found");
	header("Content-Type: text/html");
	die('<h1>404 Page not found</h1><p>The requested page could not be found.</p>');
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'doctrine-tools' . DIRECTORY_SEPARATOR . 'doctrine.php';