<?php

use NF\Bootstrap\HandleExceptions;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'load.php';

$app = new \NF\Foundation\Application(dirname(__DIR__));

if (defined('APP_DEBUG') && APP_DEBUG == true) {
    $handler = new HandleExceptions($app);
    $handler->bootstrap($app);
}
return $app;
