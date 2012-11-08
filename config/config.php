<?php

global $debug, $minify_assets;
$debug = false;
$minify_assets = false;

switch (APPLICATION_ENV)
{
    default:
        define('DB_TYPE', 'mysql');
		define('DB_HOST', '127.0.0.1');
        define('DB_NAME', 'spatula');
        define('DB_PORT', '3306');
        define('DB_USER', 'root');
        define('DB_PASS', 'root');
		
		$minify_assets = true;
        break;
	case 'sqlite_example':
		define('DB_TYPE', 'sqlite');
		define('DB_PATH', DB_FILES . DIRECTORY_SEPARATOR . 'sandbox.db');		
		break;
    	
}

switch (DB_TYPE)
{
	case 'mysql':
		define('DSN', sprintf('%1$s://%2$s:%3$s@tcp(%4$s:%5$s)/%6$s', DB_TYPE, DB_USER, DB_PASS, DB_HOST, DB_PORT, DB_NAME));
		break;
	case 'sqlite':
		define('DSN', sprintf('sqlite:///%1$s', DB_PATH));
		break;
	default:
		define('DSN', false);
		break;
}
