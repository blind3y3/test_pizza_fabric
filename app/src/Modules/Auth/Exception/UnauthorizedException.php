<?php

declare(strict_types=1);

namespace App\Modules\Auth\Exception;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Unauthorized", 401);
    }
}