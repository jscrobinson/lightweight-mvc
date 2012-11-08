<?php
date_default_timezone_set('Europe/London');

define('SITE_ROOT', dirname(__FILE__));
define('LIB_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'lib');

include_once LIB_PATH . DIRECTORY_SEPARATOR . 'bootstrap.php';


$controller = new Spatula_Controller('Frontend');

$controller->dispatch();