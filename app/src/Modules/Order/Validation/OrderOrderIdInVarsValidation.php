<?php

declare(strict_types=1);

namespace App\Modules\Order\Validation;

use Exception;
use Respect\Validation\Validator as v;

class OrderOrderIdInVarsValidation
{
    /**
     * @throws Exception
     */
    public static function validate(array $data): void
    {
        v::stringType()
            ->notEmpty()
            ->numericVal()
            ->setTemplate("'order_id' должен быть числом")
            ->assert($data['order_id']);
    }
}