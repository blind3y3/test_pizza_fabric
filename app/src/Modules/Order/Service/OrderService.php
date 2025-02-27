<?php

declare(strict_types=1);

namespace Modules\Order\Service;

use Doctrine\DBAL\Exception;
use Modules\Order\Collection\OrderCollection;
use Modules\Order\Dto\OrderDto;
use Modules\Order\Repository\OrderRepository;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function getOrders(): OrderCollection
    {
        $rawOrders = $this->orderRepository->getOrders();
        $orders = new OrderCollection();

        foreach ($rawOrders as $rawOrder) {
            $orders->add(OrderDto::createFromArray($rawOrder));

        }

        return $orders;
    }
}