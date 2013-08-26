<?php
define('TIME', time());

require_once 'Base/Autoloader.php';
$autoloader = new Base_Autoloader();
$config = new Base_Config();

$application = new Base_Application($config);
$application->run();