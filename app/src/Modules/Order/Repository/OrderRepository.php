<?php

declare(strict_types=1);

namespace Modules\Order\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class OrderRepository
{
    private Connection $connection;

    /**
     * @throws ContainerExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('connection');
    }

    /**
     * @throws Exception
     */
    public function getOrders(): array
    {
        $sql = 'SELECT * FROM `orders` limit 5';
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery();
        return $result->fetchAllAssociative();
    }
}