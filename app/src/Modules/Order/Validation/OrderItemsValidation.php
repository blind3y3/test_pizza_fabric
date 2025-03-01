<?php

declare(strict_types=1);

namespace Modules\Order\Validation;

use Exception;
use Respect\Validation\Validator as v;

class OrderItemsValidation
{
    /**
     * @throws Exception
     */
    public static function validate(array $data): void
    {
        v::arrayType()
            ->notEmpty()
            ->each(v::numericVal())
            ->each(v::min(1))
            ->each(v::max(5000))
            ->setTemplate("'items' должен быть непустым массивом чисел от 1 до 5000")
            ->assert($data);
    }
}