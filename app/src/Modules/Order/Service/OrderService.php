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
    public function getOrders(?string $done): OrderCollection
    {
        $rawOrders = $this->orderRepository->getOrders($done);
        $orders = new OrderCollection();

        foreach ($rawOrders as $rawOrder) {
            $orders->add(OrderDto::createFromArray($rawOrder));
        }

        return $orders;
    }

    /**
     * @throws Exception
     */
    public function create(array $items): OrderDto
    {
        // Уникальный id заказа длиной в 15 символов
        $orderId = mt_rand(100_000_000_000_000, 999_999_999_999_999);
        $dto = OrderDto::createFromRequestData($orderId, $items);
        $this->orderRepository->save($dto);
        return $dto;
    }
}