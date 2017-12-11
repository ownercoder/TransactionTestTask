<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Route;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

define('TEMPLATE_PATH', __DIR__ . '/Views');
define('TEMPLATE_EXT', '.php');
define('CONFIG_PATH', dirname(__DIR__) . '/config');
define('CONFIG_FILE', 'config.yaml');

$log      = new Logger('name');
$errorLog = dirname(__DIR__) . '/logs/log-' . date('Y-m-d') . '.log';
$log->pushHandler(new StreamHandler($errorLog, Logger::INFO));

try {
    $route = new Route();
    $route->start();
} catch (Exception $e) {
    $log->error('Exception: ' . $e->getMessage());
    echo $e->getMessage();
}