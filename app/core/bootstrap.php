<?php
// Required script files
require_once APP_DIR . 'vendor/autoload.php';
require_once APP_DIR . 'app/core/debug.php';
require_once APP_DIR . 'app/core/Pi.php';

// Start main app
Pi::getInstance()->run(microtime(true));
