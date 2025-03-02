<?php

declare(strict_types=1);

namespace Modules\Order\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder;
use Modules\Order\Dto\OrderDto;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class OrderRepository
{
    private QueryBuilder $builder;

    /**
     * @throws ContainerExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $connection = $container->get('connection');
        $this->builder = $connection->createQueryBuilder();
    }

    /**
     * @throws Exception
     */
    public function getOrders(?string $done): array
    {
        /** @noinspection PhpDqlBuilderUnknownModelInspection */
        $this->builder
            ->select('*')
            ->from('orders');

        if ($done !== null) {
            $this->builder
                ->andWhere('done = :done')
                ->setParameter('done', $done);
        }

        $this->builder->executeQuery();
        return $this->builder->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function save(OrderDto $dto): void
    {
        $this->builder
            ->insert('orders')
            ->values([
                'order_id' => ':orderId',
                'items'    => ':items',
                'done'     => ':done',
            ])
            ->setParameter('orderId', $dto->orderId)
            ->setParameter('items', json_encode($dto->items))
            ->setParameter('done', $dto->done, ParameterType::BOOLEAN)
            ->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function getById(int $orderId): array|false
    {
        /** @noinspection PhpDqlBuilderUnknownModelInspection */
        return $this->builder
            ->select('*')
            ->from('orders')
            ->where('order_id = :orderId')
            ->setParameter('orderId', $orderId)
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();
    }

    /**
     * @throws Exception
     */
    public function updateItems(OrderDto $dto): void
    {
        $this->builder
            ->update('orders')
            ->where('order_id = :orderId')
            ->set('items', ':items')
            ->setParameter('orderId', $dto->orderId)
            ->setParameter('items', json_encode($dto->items))
            ->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function checkOrderExistsAndNotDone(int $orderId): bool
    {
        /** @noinspection PhpDqlBuilderUnknownModelInspection */
        return (bool)$this->builder
            ->select('*')
            ->from('orders')
            ->where('order_id = :orderId')
            ->andWhere('done = false')
            ->setParameter('orderId', $orderId)
            ->executeQuery()
            ->fetchAssociative();
    }

    /**
     * @throws Exception
     */
    public function setDone(int $orderId): void
    {
        $this->builder
            ->update('orders')
            ->where('order_id = :orderId')
            ->set('done', '1')
            ->setParameter('orderId', $orderId)
            ->executeStatement();
    }
}