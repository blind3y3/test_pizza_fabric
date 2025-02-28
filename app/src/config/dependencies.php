<?php

use App\Http\Controllers\OrderController;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use FastRoute\RouteCollector;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

use function FastRoute\simpleDispatcher;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);


$containerBuilder->addDefinitions([
    'routes'     => function () {
        return simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/api/orders', [OrderController::class, 'index']);
            $r->addRoute('POST', '/api/orders', [OrderController::class, 'create']);
        });
    },
    'appLogger'  => function () {
        $logger = new Logger('appLogger');
        $lf = new LineFormatter(null, 'Y-m-d H:i:s');
        $sh = new StreamHandler(__DIR__ . '/../../app.log', Level::Debug);
        $sh->setFormatter($lf);
        $logger->pushHandler($sh);
        return $logger;
    },
    'connection' => function () {
        return DriverManager::getConnection([
            'driver'        => 'pdo_mysql',
            'host'          => 'mysql',
            'port'          => 3306,
            'user'          => 'root',
            'password'      => 'mypass',
            'dbname'        => 'localdb',
            'charset'       => 'utf8mb4',
            'driverOptions' => [
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ]
        ]);
    }
]);
//@TODO централизованный ExceptionHandler?
try {
    return $containerBuilder->build();
} catch (Exception $e) {
    die($e->getMessage());
}