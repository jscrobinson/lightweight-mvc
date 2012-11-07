<?php

global $debug, $minify_assets;
$debug = false;
$minify_assets = false;

switch ($_SERVER['HTTP_HOST'])
{
    default:
        define('DB_HOST', '127.0.0.1');
        define('DB_NAME', 'spatula');
        define('DB_PORT', '3306');
        define('DB_USER', 'root');
        define('DB_PASS', 'root');
		$minify_assets = true;
        break;
    	
}