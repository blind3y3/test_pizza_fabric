<?php

declare(strict_types=1);

namespace App\Modules\Order\Exception;

use Exception;

class OrderCannotBeModifiedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Заказ не может быть изменен или не существует', 400);
    }
}