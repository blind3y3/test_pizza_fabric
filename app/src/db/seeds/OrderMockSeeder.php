<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use Random\RandomException;

class OrderMockSeeder extends AbstractSeed
{
    /**
     * Сидер моковых заказов для тестов
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'order_id' => random_int(1, 8096),
                'items'    => json_encode([2, 3, 5, 7, 11]),
                'done'     => false
            ];
        }

        $mockOrders = $this->table('orders');
        $mockOrders->insert($data)->saveData();
    }
}
