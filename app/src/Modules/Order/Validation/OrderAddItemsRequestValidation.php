<?php

declare(strict_types=1);

namespace App\Modules\Order\Validation;

use Exception;
use Respect\Validation\Validator as v;

class OrderAddItemsRequestValidation
{
    /**
     * @throws Exception
     */
    public static function validate(array $data): void
    {
        v::stringType()
            ->notEmpty()
            ->numericVal()
            ->setTemplate("'orderId' должен быть числом")
            ->assert($data['orderId']);
    }
}