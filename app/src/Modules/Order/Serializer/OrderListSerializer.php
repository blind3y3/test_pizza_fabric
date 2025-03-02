<?php

declare(strict_types=1);

namespace App\Modules\Order\Serializer;

use Modules\Order\Collection\OrderCollection;
use Modules\Order\Dto\OrderDto;

class OrderListSerializer
{
    public static function serialize(OrderCollection $collection): array
    {
        $result = [];

        /** @var OrderDto $order */
        foreach ($collection->get() as $order) {
            $result[] = [
                'order_id' => $order->orderId,
                'done'     => $order->done,
            ];
        }

        return $result;
    }
}