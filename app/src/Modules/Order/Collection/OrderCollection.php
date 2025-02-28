<?php

declare(strict_types=1);

namespace Modules\Order\Collection;

use Modules\Order\Dto\OrderDto;

/**
 * @TODO Возможно, стоит делать интерфейс или базовый абстрактный класс, чтобы его указывать в тайп хинтах
 */
class OrderCollection
{
    private array $orders = [];

    public function add(OrderDto $order): void
    {
        $this->orders[] = $order;
    }

    public function get(): array
    {
        return $this->orders;
    }

    public function toArray(): array
    {
        $result = [];
        /** @var OrderDto $order */
        foreach ($this->orders as $order) {
            $result[] = [
                'order_id' => $order->orderId,
                'items'    => $order->items,
                'done'     => $order->done,
            ];
        }

        return $result;
    }
}