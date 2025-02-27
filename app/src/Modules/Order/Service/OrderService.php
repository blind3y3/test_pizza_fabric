<?php

declare(strict_types=1);

namespace Modules\Order\Service;

use Modules\Order\Repository\OrderRepository;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {
    }

    public function getOrders(): array
    {
        return $this->orderRepository->getOrders();
    }
}