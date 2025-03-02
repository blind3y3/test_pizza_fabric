<?php

use App\Http\Controllers\OrderController;
use App\Modules\Order\Decorator\OrderServiceLoggerDecorator;
use App\Modules\Order\Service\OrderServiceInterface;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use FastRoute\RouteCollector;
use Modules\Auth\Service\AuthService;
use Modules\Auth\Service\AuthServiceInterface;
use Modules\Order\Service\OrderService;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

use Psr\Log\LoggerInterface;

use function FastRoute\simpleDispatcher;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);

$containerBuilder->addDefinitions([
    'routes'                     => function () {
        return simpleDispatcher(function (RouteCollector $r) {
            $r->addGroup('/api/orders', function () use ($r) {
                $r->addRoute('GET', '', [OrderController::class, 'index']);
                $r->addRoute('POST', '', [OrderController::class, 'create']);
                $r->addRoute('POST', '/{order_id}/items', [OrderController::class, 'addItems']);
                $r->addRoute('POST', '/{order_id}/done', [OrderController::class, 'setDone']);
                $r->addRoute('GET', '/{order_id}', [OrderController::class, 'getById']);
            });
        });
    },
    'appLogger'                  => function () {
        $logger = new Logger('appLogger');
        $lf = new LineFormatter(null, 'Y-m-d H:i:s');
        $sh = new StreamHandler(__DIR__ . '/../../app.log', Level::Debug);
        $sh->setFormatter($lf);
        $logger->pushHandler($sh);
        return $logger;
    },
    'connection'                 => function () {
        return DriverManager::getConnection([
            'driver'        => 'pdo_mysql',
            'host'          => 'mysql',
            'port'          => 3306,
            'user'          => getenv('DB_USERNAME'),
            'password'      => getenv('DB_PASSWORD'),
            'dbname'        => getenv('DB_DATABASE'),
            'charset'       => 'utf8mb4',
            'driverOptions' => [
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ]
        ]);
    },
    //interfaces
    AuthServiceInterface::class  => DI\autowire(AuthService::class),
    //decorators
    OrderService::class          => DI\autowire(),
    OrderServiceInterface::class => function (DI\Container $container) {
        $orderService = $container->get(OrderService::class);
        $logger = $container->get('appLogger');
        return new OrderServiceLoggerDecorator($orderService, $logger);
    }
]);
//@TODO централизованный ExceptionHandler?
try {
    return $containerBuilder->build();
} catch (Exception $e) {
    die($e->getMessage());
}