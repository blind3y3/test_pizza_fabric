<?php

declare(strict_types=1);

namespace Modules\Order\Dto;

class OrderDto
{
    public int $orderId;
    public array $items;
    public bool $done;

    /**
     * @param int $orderId
     * @param array $items
     * @param bool $done
     */
    public function __construct(int $orderId, array $items, bool $done)
    {
        $this->orderId = $orderId;
        $this->items = $items;
        $this->done = $done;
    }


    public static function createFromArray(array $data): self
    {
        return new self($data['order_id'], json_decode($data['items'], true), (bool)$data['done']);
    }
}