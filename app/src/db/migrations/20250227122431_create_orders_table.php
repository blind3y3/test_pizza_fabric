<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrdersTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('orders', ['id' => false, 'primary_key' => ['order_id']])
            ->addColumn('order_id', 'biginteger', ['null' => false])
            ->addColumn('items', 'json', ['null' => false])
            ->addColumn('done', 'boolean', ['null' => false, 'default' => false, 'comment' => 'Статус'])
            ->create();
    }
}
