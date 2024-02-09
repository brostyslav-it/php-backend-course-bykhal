<?php

use App\App\App;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("APP_PATH", dirname(__DIR__));
const STATIC_PATH = __DIR__ . '/static';

require_once APP_PATH . '/vendor/autoload.php';

$app = new App();
$app->run();
