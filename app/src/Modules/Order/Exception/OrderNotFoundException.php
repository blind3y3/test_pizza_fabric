<?php

declare(strict_types=1);

namespace App\Modules\Order\Exception;

use Exception;

class OrderNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Заказ с таким order_id не найден', 404);
    }
}