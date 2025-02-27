<?php

use App\Http\Controllers\OrderController;
use DI\ContainerBuilder;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);

$containerBuilder->addDefinitions([
    'routes' => function () {
        return simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/orders', [OrderController::class, 'index']);
        });
    }
]);

return $containerBuilder->build();