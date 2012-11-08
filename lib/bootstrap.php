<?php
# APPLICATION_ENV switches dev and debug functionality on or off
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

define('MODEL_PATH', LIB_PATH . DIRECTORY_SEPARATOR . 'model');
define('MODULE_PATH', LIB_PATH . DIRECTORY_SEPARATOR . 'module');
define('VENDOR_PATH', LIB_PATH . DIRECTORY_SEPARATOR . 'vendor');
define('DOCTRINE_PATH', VENDOR_PATH . DIRECTORY_SEPARATOR . 'doctrine-1.2.4' . DIRECTORY_SEPARATOR . 'lib');
define('GOOGLE_API_CLIENT_LIBRARY_PATH', LIB_PATH . DIRECTORY_SEPARATOR . 'google-api-php-client');
define('CONFIG_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'config');
define('TEMPLATE_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'templates');
define('ASSETS_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'assets');
define('XML_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'xml');
define('SKIN_PATH', ASSETS_PATH . DIRECTORY_SEPARATOR . 'skin');

define('ASSETS_URL', str_replace(DIRECTORY_SEPARATOR, '/', str_replace(SITE_ROOT, '', ASSETS_PATH)));
define('SKIN_URL', ASSETS_URL . '/skin');

set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . MODULE_PATH );

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.php';
require_once('Spatula.php');
Spatula::$autoloadNamespaces['Spatula'] = MODULE_PATH;
Spatula::$autoloadNamespaces['Frontend'] = MODULE_PATH;
Spatula::$autoloadNamespaces['VideoConverter'] = MODULE_PATH;
Spatula::$autoloadNamespaces['Model'] = MODEL_PATH;

spl_autoload_register(array(Spatula::getInstance(), 'autoload'));

// DOCTRINE SETUP
define('MODELS_DIRECTORY', 'models');
define('DB_FILES', LIB_PATH);
define('DATA_FIXTURES_PATH', DB_FILES . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fixtures');
define('MODELS_PATH', DB_FILES . DIRECTORY_SEPARATOR . MODELS_DIRECTORY);
define('EXTENSIONS_PATH', DB_FILES . DIRECTORY_SEPARATOR . 'extensions');
define('MIGRATIONS_PATH', DB_FILES . DIRECTORY_SEPARATOR . 'migrations');
define('SQL_PATH', DB_FILES . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'sql');
define('YAML_SCHEMA_PATH', DB_FILES . DIRECTORY_SEPARATOR . 'schema');

require_once(DOCTRINE_PATH . DIRECTORY_SEPARATOR . 'Doctrine.php');

Doctrine_Core::setExtensionsPath(EXTENSIONS_PATH);

spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));
spl_autoload_register(array('Doctrine', 'extensionsAutoload'));

$manager = Doctrine_Manager::getInstance();
$manager->openConnection(DSN, 'doctrine');
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);

Doctrine_Core::setModelsDirectory(MODELS_DIRECTORY);
