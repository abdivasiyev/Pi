<?php
// $time_start = microtime(true);
defined('APP_DEBUG') or define('APP_DEBUG', true);

require_once '../vendor/autoload.php';
require_once '../app/core/functions.php';
require_once '../app/core/App.php';

$config = require_once '../config/main.php';
$routes = require_once '../config/routes.php';

$app = new App($config, $routes);
// $time_end = microtime(true);
// $exec_time = ($time_end - $time_start);

// echo('Execution time in ' . date('Y-m-d H:m:s') . '<br>' . $exec_time);