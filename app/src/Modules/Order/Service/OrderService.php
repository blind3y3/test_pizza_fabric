<?php

declare(strict_types=1);

namespace Modules\Order\Service;

use App\Modules\Order\Exception\OrderCannotBeModifiedException;
use App\Modules\Order\Exception\OrderNotFoundException;
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

    /**
     * @throws OrderCannotBeModifiedException|Exception
     */
    public function checkOrderExistsAndNotDone(int $orderId): void
    {
        if (!$this->orderRepository->checkOrderExistsAndNotDone($orderId)) {
            throw new OrderCannotBeModifiedException();
        }
    }

    /**
     * @throws Exception
     * @throws OrderNotFoundException
     */
    public function addItems(int $orderId, array $items): void
    {
        $order = $this->orderRepository->getById($orderId);
        if (!$order) {
            throw new OrderNotFoundException();
        }
        $dto = OrderDto::createFromArray($order);
        $dto->items = [...$dto->items, ...$items];
        $this->orderRepository->updateItems($dto);
    }

    /**
     * @throws Exception
     * @throws OrderNotFoundException
     */
    public function getById(int $orderId): OrderDto
    {
        $order = $this->orderRepository->getById($orderId);
        if (!$order) {
            throw new OrderNotFoundException();
        }
        return OrderDto::createFromArray($order);
    }
}