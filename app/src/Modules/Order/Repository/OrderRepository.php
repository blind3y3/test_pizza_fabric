<?php

declare(strict_types=1);

namespace Modules\Order\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerInterface;

class OrderRepository
{
    private Connection $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('connection');
    }

    /**
     * @throws Exception
     */
    public function getOrders(): array
    {
//        $sql = 'SELECT * FROM `users`';
//        $stmt = $this->connection->prepare($sql);
//        dump($stmt->executeQuery());
        return [];
    }
}