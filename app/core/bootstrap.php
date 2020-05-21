<?php
// Required script files
require_once APP_DIR . 'vendor/autoload.php';
require_once APP_DIR . 'app/core/debug.php';
require_once APP_DIR . 'app/core/App.php';

// Start main app
App::getInstance()->run(microtime(true));
