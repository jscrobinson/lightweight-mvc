<?php
date_default_timezone_set('Europe/London');

define('SITE_ROOT', dirname(__FILE__));
define('LIB_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'lib');
define('VENDOR_PATH', LIB_PATH . DIRECTORY_SEPARATOR . 'vendor');
define('GOOGLE_API_CLIENT_LIBRARY_PATH', LIB_PATH . DIRECTORY_SEPARATOR . 'google-api-php-client');
define('CONFIG_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'config');
define('TEMPLATE_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'templates');
define('ASSETS_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'assets');
define('XML_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'xml');
define('SKIN_PATH', ASSETS_PATH . DIRECTORY_SEPARATOR . 'skin');

define('ASSETS_URL', str_replace(DIRECTORY_SEPARATOR, '/', str_replace(SITE_ROOT, '', ASSETS_PATH)));
define('SKIN_URL', ASSETS_URL . '/skin');

set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH );

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.php';
require_once('Spatula.php');
Spatula::$autoloadNamespaces['Spatula'] = LIB_PATH;
Spatula::$autoloadNamespaces['Frontend'] = LIB_PATH;
Spatula::$autoloadNamespaces['Zend'] = LIB_PATH;
Spatula::$autoloadNamespaces['Google'] = LIB_PATH;
spl_autoload_register(array(Spatula::getInstance(), 'autoload'));

$controller = new Spatula_Controller('Frontend');

$controller->dispatch();