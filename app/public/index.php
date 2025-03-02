<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;

require __DIR__ . '/../src/App.php';
require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../src/config/dependencies.php';

$app = new App($container);
try {
    $app->run();
} catch (ContainerExceptionInterface $e) {
    die($e->getMessage());
}