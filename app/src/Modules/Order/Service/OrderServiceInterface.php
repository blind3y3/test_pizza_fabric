<?php

namespace App\Modules\Order\Service;

use Modules\Order\Collection\OrderCollection;
use Modules\Order\Dto\OrderDto;

interface OrderServiceInterface
{
    public function getOrders(?string $done): OrderCollection;

    public function create(array $items): OrderDto;

    public function checkOrderExistsAndNotDone(int $orderId): void;

    public function addItems(int $orderId, array $items): void;

    public function getById(int $orderId): OrderDto;

    public function setDone(int $orderId): void;
}